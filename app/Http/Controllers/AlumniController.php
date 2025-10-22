<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil filter dari query string (search, fungsi, jenis_kelamin, tahun)
        $filters = request()->only(['search', 'fungsi', 'jenis_kelamin', 'tahun']);

        // Ambil semua user alumni magang (status_id = 5 berarti selesai)
        $users = User::with(['fungsi'])
            ->whereHas('absensis', function ($query) {
                $query->where('status_id', 5);
            })
            ->filter($filters)
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Ambil semua fungsi untuk dropdown filter
        $fungsis = Fungsi::all();

        return view('users.alumni', [
            'title' => 'Alumni Magang',
            'users' => $users,
            'fungsis' => $fungsis
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['fungsi']);

        return view('users.card', [
            'title' => 'Detail Alumni: ' . $user->name,
            'user' => $user,
            'from' => 'alumni',
        ]);
    }

    public function showDetail(User $user)
    {
        $absensis = $user->absensis()
            ->with('status')
            ->orderBy('tanggal', 'desc')
            ->paginate(12);

        return view('absensi.view-detail', [
            'title' => 'Absensi Alumni',
            'user' => $user,
            'absensis' => $absensis,
            'from' => 'alumni',
        ]);
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'title' => 'Edit Alumni: ' . $user->name,
            'user' => $user,
            'fungsis' => Fungsi::all(),
            'from' => 'alumni',
        ]);
    }
}
