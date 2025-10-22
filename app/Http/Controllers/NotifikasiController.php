<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Notif;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // 📨 Menampilkan pesan masuk (user biasa & admin)
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin) {
            // Admin hanya melihat pesan yang:
            // - Dikirim oleh admin lain (admin_id != dirinya)
            // - ATAU dikirim oleh sistem (tidak punya admin_id)
            $notifs = Notif::where(function ($q) use ($user) {
                $q->where('admin_id', '!=', $user->id)
                    ->orWhereNull('admin_id');
            })
                ->where('user_id', $user->id) // tetap hanya pesan yang ditujukan untuk admin ini
                ->latest()
                ->paginate(9);
        } else {
            // User biasa → hanya pesan untuk dirinya sendiri
            $notifs = Notif::where('user_id', $user->id)
                ->latest()
                ->paginate(9);
        }

        return view('pesan', [
            'title' => 'Pesan',
            'notifs' => $notifs
        ]);
    }

    public function broadcast()
    {
        $user = Auth::user();
        // Ambil id notifikasi terbaru untuk setiap pesan unik dari admin yang login
        $latestNotifIds = Notif::where('admin_id', $user->id)
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('pesan', 'nama')
            ->pluck('id');

        // Ambil data lengkap berdasarkan ID yang sudah didapat
        $notifs = Notif::whereIn('id', $latestNotifIds)
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('pesan.broadcast', [
            'title' => 'Broadcast',
            'notifs' => $notifs
        ]);
    }

    // 🔍 Menampilkan pesan detail
    public function show(Notif $notif)
    {
        $user = Auth::user();

        // Izinkan jika pesan milik user sendiri ATAU dikirim oleh admin login
        if ($notif->user_id !== $user->id && $notif->admin_id !== $user->id) {
            abort(403, 'Kamu tidak punya akses ke pesan ini.');
        }

        if (!$notif->is_read && $notif->user_id === $user->id) {
            $notif->is_read = true;
            $notif->save();
        }

        return view('pesan.view', [
            'title' => 'Pesan',
            'notif' => $notif,
        ]);
    }


    // ✅ Tandai pesan sudah dibaca
    public function markRead(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            Notif::whereIn('id', $ids)->update(['is_read' => true]);
        }
        return response()->json(['status' => 'success']);
    }

    // 🔁 Tandai pesan belum dibaca
    public function markUnread(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            Notif::whereIn('id', $ids)->update(['is_read' => false]);
        }
        return response()->json(['status' => 'success']);
    }

    // 🗑️ Hapus pesan (hanya pesan milik admin sendiri)
    public function delete(Request $request)
    {
        $ids = $request->input('ids');
        $userId = Auth::id();

        if (is_array($ids)) {
            Notif::whereIn('id', $ids)
                ->where('user_id', $userId) // Hanya pesan yang ditujukan untuk user/admin ini
                ->delete();
        }

        return response()->json(['status' => 'success']);
    }


    // 📝 Form buat pesan baru
    public function create()
    {
        return view('pesan.create', [
            'title' => 'Pesan',
        ]);
    }

    // 🚀 Simpan dan kirim pesan broadcast (admin → semua user biasa)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesan' => 'required|string',
        ], [
            'pesan.required' => 'Pesan wajib diisi.',
        ]);

        $admin = Auth::user();

        if (!$admin->is_admin) {
            abort(403, 'Hanya admin yang dapat mengirim broadcast.');
        }

        // Kirim ke semua user non-admin
        $users = User::where('is_admin', false)->get();

        foreach ($users as $user) {
            Notif::create([
                'user_id' => $user->id,
                'foto' => 'img/BPS_Chatbot.jpg',
                'nama' => $admin->name,
                'slug' => Str::slug($admin->name) . '-' . uniqid(),
                'pesan' => $validated['pesan'],
                'is_read' => false,
                'admin_id' => $admin->id, // ID admin pengirim
            ]);
        }

        return redirect('/broadcast')->with('success', 'Pesan berhasil dikirim ke semua user!');
    }

    // ❌ Hapus semua pesan broadcast admin login
    public function destroy(Notif $notif)
    {
        if ($notif->admin_id !== Auth::id()) {
            return redirect('/broadcast')->with('error', 'Kamu tidak dapat menghapus pesan dari admin lain.');
        }

        Notif::where('admin_id', Auth::id())
            ->where('nama', $notif->nama)
            ->where('pesan', $notif->pesan)
            ->delete();

        return redirect('/broadcast')->with(['success' => 'Pesan broadcast berhasil dihapus dari semua user!']);
    }

    // ✏️ Edit pesan broadcast
    public function edit(Notif $notif)
    {
        if ($notif->admin_id !== Auth::id()) {
            abort(403, 'Kamu tidak bisa mengedit pesan dari admin lain.');
        }

        return view('pesan.edit', ['title' => 'Pesan', 'notif' => $notif]);
    }

    // 💾 Update pesan broadcast
    public function update(Request $request, Notif $notif)
    {
        if ($notif->admin_id !== Auth::id()) {
            abort(403, 'Kamu tidak bisa mengedit pesan dari admin lain.');
        }

        $validated = $request->validate([
            'pesan' => 'required|string',
        ], [
            'pesan.required' => 'Pesan wajib diisi.',
        ]);

        Notif::where('admin_id', Auth::id())
            ->where('nama', $notif->nama)
            ->where('pesan', $notif->pesan)
            ->update([
                'pesan' => $validated['pesan'],
                'updated_at' => now(),
            ]);

        return redirect('/broadcast')->with(['success' => 'Pesan broadcast berhasil diupdate untuk semua user!']);
    }

    public function bulkAction(Request $request)
    {
        $userId = Auth::id();
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada pesan yang dipilih.');
        }

        $query = Notif::whereIn('id', $ids)
            ->where('user_id', $userId);

        switch ($action) {
            case 'read':
                $query->update(['is_read' => true]);
                return back()->with('success', 'Pesan berhasil ditandai sebagai telah dibaca.');
            case 'unread':
                $query->update(['is_read' => false]);
                return back()->with('success', 'Pesan berhasil ditandai sebagai belum dibaca.');
            case 'delete':
                $query->delete();
                return back()->with('success', 'Pesan berhasil dihapus.');
            default:
                return back()->with('error', 'Aksi tidak dikenali.');
        }
    }
}
