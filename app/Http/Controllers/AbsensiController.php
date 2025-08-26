<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only(['search', 'status', 'fungsi']);

        $today = now()->toDateString(); // Sesuaikan dengan format di database

        $users = User::with([
            'fungsi',
            'absensis' => function ($query) use ($today) {
                $query->where('tanggal', $today)->with('status');
            }
        ])
            ->whereHas('absensis', function ($query) use ($today) {
                $query->where('tanggal', $today);
            })
            ->filter($filters)
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('absensi', [
            'title' => 'Absensi',
            'users' => $users,
            'statuses' => Status::all(),
            'fungsis' => Fungsi::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('absensi.create', ['title' => 'Absensi']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric', //Sementara
            'longitude' => 'nullable|numeric',
        ]);

        $user = auth()->user();

        $today = now()->toDateString();

        // Cek apakah user sudah absen hari ini
        $alreadyAbsen = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->exists();

        if ($alreadyAbsen) {
            return redirect('/dashboard')->with('error', 'Kamu sudah absen hari ini!');
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => now()->format('H:i'),
            'status_id' => 1, // contoh: 1 = Hadir
            'keterangan' => 'Tepat waktu',
        ]);

        return redirect('/dashboard')->with('success', 'Absen berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
}
