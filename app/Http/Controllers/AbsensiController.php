<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Support\Str;
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

        // ✅ Validasi waktu absen masuk
        $now = Carbon::now('Asia/Makassar');
        $hour = $now->hour; 
        if ($hour < 7 || $hour >= 12) {
            return redirect('/dashboard')->with('error', 'Absen masuk hanya bisa dilakukan antara pukul 07:00 hingga 12:00 WITA.');
        }

        // Cek apakah user sudah absen Hadir
        $alreadyAbsenHadir = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->where('status_id', 1)
            ->exists();

        if ($alreadyAbsenHadir) {
            return redirect('/dashboard')->with('error', 'Kamu sudah absen hari ini!');
        }

        // Cek apakah sudah izin atau sakit
        $sudahIzinAtauSakit = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->whereIn('status_id', [2, 3])
            ->exists();

        if ($sudahIzinAtauSakit) {
            return redirect('/dashboard')->with('error', 'Kamu sudah mengajukan izin/sakit hari ini!');
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => $now->format('H:i'),
            'status_id' => 1,
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
            'gambar_path' => 'nullable|string',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'status_id.required' => 'Status wajib dipilih.',
            'status_id.exists' => 'Status tidak valid.',
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
        if ($request->filled('gambar_path')) {
            $from = storage_path('app/public/' . $request->gambar_path);
            $toDir = storage_path('app/public/uploads/gambar');
            if (file_exists($from)) {
                if (!file_exists($toDir)) {
                    mkdir($toDir, 0755, true);
                }
                $fileName = basename($from);
                $to = $toDir . '/' . $fileName;
                rename($from, $to);
                $gambarPath = 'uploads/gambar/' . $fileName;
            }
        }

        // Buat slug dari judul + uniqid
        $slug = Str::slug($request->judul) . uniqid();

        // Simpan data izin/sakit dengan slug
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'status_id' => $request->status_id,
            'judul' => $request->judul,
            'slug' => $slug,
            'keterangan' => $request->keterangan,
            'gambar' => $gambarPath,
        ]);

        return redirect('/dashboard')->with('success', 'Pengajuan berhasil dikirim!');
    }


    public function upload(Request $request)
    {
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('tmp', 'public');
        }

        return $path;
    }

    public function revert(Request $request)
    {
        $filename = $request->input('filename');

        if ($filename) {
            // Pastikan path benar
            $filePath = storage_path('app/public/tmp/' . basename($filename));

            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file
                return response()->json(['message' => 'File berhasil dihapus.']);
            }
        }

        return response()->json(['message' => 'File tidak ditemukan.'], 404);
    }

    public function pulang(Request $request)
    {
        // Set manual koordinat (contoh koordinat kantor)
        $manualLatitude = -5.147665;
        $manualLongitude = 119.432731;

        $user = auth()->user();
        $today = now()->toDateString();

        $now = Carbon::now('Asia/Makassar');
        $hour = $now->hour;
        if ($hour < 16 || $hour >= 24) {
            return redirect('/dashboard')->with('error', 'Absen pulang hanya bisa dilakukan antara pukul 16:00 hingga 00:00 WITA.');
        }

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
            'jam_keluar' => $now->format('H:i'),
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
        // eager load relasi user, user.fungsi, dan status
        $absensi->load('user.fungsi', 'status');

        return view('absensi.view', [
            'title' => 'Keterangan',
            'absensi' => $absensi,
        ]);
    }

    public function showDetail(User $user)
    {
        $absensis = $user->absensis()
            ->with('status')
            ->orderBy('tanggal', 'desc')
            ->paginate(12);

        return view('absensi.view-detail', [
            'title' => 'Detail Absensi',
            'user' => $user,
            'absensis' => $absensis,
        ]);
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
