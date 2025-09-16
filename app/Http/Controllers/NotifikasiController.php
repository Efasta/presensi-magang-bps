<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notif;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // Method untuk menampilkan halaman pesan
    public function index()
    {
        $userId = Auth::id(); // Ambil ID user yang sedang login
        $notifs = Notif::where('user_id', $userId)->latest()->paginate(6); // Filter berdasarkan user_id

        return view('pesan', [
            'title' => 'Pesan',
            'notifs' => $notifs
        ]);
    }

    public function show(Notif $notif)
    {
        if ($notif->user_id !== Auth::id()) {
            abort(403); // atau redirect dengan pesan error
        }

        if (! $notif->is_read) {
            $notif->is_read = true;
            $notif->save();
        }

        return view('pesan.view', [
            'title' => 'Detail Pesan',
            'notif' => $notif,
        ]);
    }


    // Update notifikasi jadi read
    public function markRead(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            Notif::whereIn('id', $ids)->update(['is_read' => true]);
        }
        return response()->json(['status' => 'success']);
    }

    // Update notifikasi jadi unread
    public function markUnread(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            Notif::whereIn('id', $ids)->update(['is_read' => false]);
        }
        return response()->json(['status' => 'success']);
    }

    // Hapus notifikasi
    public function delete(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            Notif::whereIn('id', $ids)->delete();
        }
        return response()->json(['status' => 'success']);
    }

    public function create()
    {
        return view('pesan.create', [
            'title' => 'Pesan',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'pesan' => 'required|string',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'pesan.required' => 'Pesan wajib diisi.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.'
        ]);

        $users = User::all(); // ambil semua user

        foreach ($users as $user) {
            Notif::create([
                'user_id' => $user->id,
                'foto' => 'img/BPS_Chatbot.jpg', // default path atau ambil dari config
                'nama' => $validated['nama'],
                'slug' => Str::slug($validated['nama']) . '-' . uniqid(), // pakai UUID agar unik
                'pesan' => $validated['pesan'],
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect('/pesan')->with('success', 'Pesan berhasil dikirim ke semua user!');
    }

    public function destroy(Notif $notif)
    {
        Notif::where('nama', $notif->nama)
            ->where('pesan', $notif->pesan)
            ->delete();

        return redirect('/pesan')->with(['success' => 'Pesan broadcast berhasil dihapus dari semua user!']);
    }

    public function edit(Notif $notif)
    {
        return view('pesan.edit', ['title' => 'Pesan', 'notif' => $notif]);
    }

    public function update(Request $request, Notif $notif)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:notifs,nama' . $notif->id,
            'pesan' => 'required|string',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'pesan.required' => 'Pesan wajib diisi.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.'
        ]);

        // Update semua pesan broadcast yang identik
        Notif::where('nama', $notif->nama)
            ->where('pesan', $notif->pesan)
            ->update([
                'nama' => $validated['nama'],
                'pesan' => $validated['pesan'],
                'updated_at' => now(),
            ]);

        return redirect('/pesan')->with(['success' => 'Pesan broadcast berhasil diupdate untuk semua user!']);
    }
}
