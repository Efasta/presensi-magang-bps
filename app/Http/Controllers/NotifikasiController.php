<?php

namespace App\Http\Controllers;

use App\Models\Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // Method untuk menampilkan halaman pesan
    public function index()
    {
        $userId = Auth::id(); // Ambil ID user yang sedang login
        $notifs = Notif::where('user_id', $userId)->latest()->get(); // Filter berdasarkan user_id

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
}
