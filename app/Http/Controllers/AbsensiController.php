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
        $user = auth()->user();
        $today = now()->toDateString();

        // Ambil data absen hari ini milik user
        $absensiToday = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        return view('absensi.create', [
            'title' => 'Absensi',
            'absensi' => $absensiToday,
        ]);
    }

    public function createDetail()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // Ambil data absen hari ini milik user yang dipilih
        $absensiToday = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        return view('absensi.create-detail', [
            'title' => 'Detail Presensi',
            'absensi' => $absensiToday,
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();

        // Cek apakah user sudah absen Hadir
        $alreadyAbsenHadir = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->where('status_id', 1) // 1 = Hadir
            ->exists();

        if ($alreadyAbsenHadir) {
            return redirect('/dashboard')->with('error', 'Kamu sudah absen hari ini!');
        }

        // ✅ Cek apakah sudah izin atau sakit
        $sudahIzinAtauSakit = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->whereIn('status_id', [2, 3]) // 2 = Izin, 3 = Sakit (pastikan ini sesuai di database kamu)
            ->exists();

        if ($sudahIzinAtauSakit) {
            return redirect('/dashboard')->with('error', 'Kamu sudah mengajukan izin/sakit hari ini!');
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => now()->format('H:i'),
            'status_id' => 1, // Hadir
            'keterangan' => 'Tepat waktu',
        ]);

        return redirect('/dashboard')->with('success', 'Absen berhasil!');
    }


    public function storeDetail(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'status_id' => 'required|exists:statuses,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:10000',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'status_id.required' => 'Status wajib dipilih.',
            'status_id.exists' => 'Status tidak valid.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'gambar.max' => 'Ukuran gambar maksimal 10MB.',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();

        // Cek apakah user sudah absen hadir hari ini
        $sudahHadir = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->where('status_id', 1) // 1 = Hadir
            ->exists();

        if ($sudahHadir) {
            return redirect('/dashboard')->with('error', 'Anda sudah absen hari ini!');
        }

        // Upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('uploads/gambar', 'public');
        }

        // Simpan data izin/sakit
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'status_id' => $request->status_id,
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'gambar' => $gambarPath,
        ]);

        return redirect('/dashboard')->with('success', 'Pengajuan berhasil dikirim!');
    }


    public function pulang(Request $request)
    {
        // $request->validate([
        //     'latitude' => 'required|numeric', //Sementara nullable ->('required')
        //     'longitude' => 'required|numeric',
        // ]);

        // Set manual koordinat (contoh koordinat kantor) sementara set longlat :)
        $manualLatitude = -5.147665;
        $manualLongitude = 119.432731;

        $user = auth()->user();
        $today = now()->toDateString();

        $absensi = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi) {
            return redirect('/dashboard')->with('error', 'Belum melakukan absen masuk!');
        }

        if ($absensi->jam_keluar !== null) {
            return redirect('/dashboard')->with('error', 'Sudah absen pulang!');
        }

        $absensi->update([
            'jam_keluar' => now()->format('H:i'),
            'latitude' => $manualLatitude,
            'longitude' => $manualLongitude,
        ]);

        return redirect('/dashboard')->with('success', 'Absen pulang berhasil!');
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
