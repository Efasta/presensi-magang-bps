<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notif;

class NotifikasiController extends Controller
{
    // Method untuk menampilkan halaman pesan
    public function index()
    {
        $notifs = Notif::all();
        return view('pesan', ['title' => 'Pesan', 'notifs' => $notifs]);
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
