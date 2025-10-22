<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only(['search', 'status', 'fungsi']);
        $today = now()->toDateString();

        $users = User::with([
            'fungsi',
            'absensis' => function ($query) use ($today) {
                $query->where('tanggal', $today)->with('status');
            }
        ])
            ->whereHas('absensis', function ($query) use ($today) {
                $query->where('tanggal', $today);
            })
            ->whereDoesntHave('absensis', function ($query) {
                $query->where('status_id', 5); // abaikan user yang absensinya sudah selesai
            })
            ->filter($filters)
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $statuses = Status::where('id', '!=', 5)
            ->where('status', '!=', 'Selesai')
            ->get();

        return view('absensi', [
            'title' => 'Absensi',
            'users' => $users,
            'statuses' => $statuses,
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

        $now = Carbon::now('Asia/Makassar');
        $isWeekend = in_array($now->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
        $namaHari = $now->translatedFormat('l'); // contoh: "Senin", "Selasa"

        $absensiToday = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        $isAlumni = Absensi::where('user_id', Auth::id())
            ->where('status_id', 5)
            ->exists();

        return view('absensi.create', [
            'title' => 'Absensi',
            'absensi' => $absensiToday,
            'isWeekend' => $isWeekend,
            'namaHari' => $namaHari,
            'isAlumni' => $isAlumni,
        ]);
    }

    public function createDetail()
    {
        $user = auth()->user();
        $today = now()->toDateString();
        $now = Carbon::now('Asia/Makassar');

        // 🕒 Validasi waktu izin/sakit
        $dayOfWeek = $now->dayOfWeek; // 1=Senin, ..., 5=Jumat
        $currentTime = $now->format('H:i');

        if (in_array($dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            return redirect('/dashboard')->with('error', 'Pengajuan izin/sakit tidak bisa dilakukan di hari Sabtu atau Minggu.');
        }

        $maxTime = $now->isFriday() ? '16:30' : '16:00';

        if ($currentTime < '00:01' || $currentTime > $maxTime) {
            return redirect('/dashboard')->with('error', "Pengajuan izin/sakit hanya bisa dilakukan antara pukul 00:01 sampai $maxTime WITA.");
        }

        // 🚫 Cegah alumni
        $isAlumni = Absensi::where('user_id', $user->id)
            ->where('status_id', 5)
            ->exists();

        if ($isAlumni) {
            return redirect('/dashboard')->with('error', 'Alumni tidak dapat mengajukan perizinan.');
        }

        $absensiToday = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        return view('absensi.create-detail', [
            'title' => 'Absensi',
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
        $now = Carbon::now('Asia/Makassar');

        $isAlumni = Absensi::where('user_id', Auth::id())
            ->where('status_id', 5)
            ->exists();

        // Lokasi kantor
        $kantorLat = -5.1488763012991425;
        $kantorLng = 119.41054079290649;
        $radius = 50; // meter

        // Hitung jarak
        $distance = $this->haversine($request->latitude, $request->longitude, $kantorLat, $kantorLng);

        if ($distance > $radius) {
            return redirect('/dashboard')->with('error', 'Kamu berada di luar area kantor (max 50m).');
        }

        // ✅ Validasi hari Sabtu/Minggu
        if (in_array($now->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            return redirect('/dashboard')->with('error', 'Kamu gak bisa absen di hari Sabtu atau Minggu!');
        }

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

        if ($isAlumni) {
            return redirect('/dashboard')->with('error', 'Alumni tidak dapat melakukan absensi.');
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

    // Fungsi haversine untuk hitung jarak dalam meter
    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }


    public function storeDetail(Request $request)
    {
        $user = auth()->user();

        // 🚫 Cegah alumni
        $isAlumni = Absensi::where('user_id', $user->id)
            ->where('status_id', 5)
            ->exists();

        if ($isAlumni) {
            return redirect('/dashboard')->with('error', 'Alumni tidak dapat mengajukan perizinan.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'status_id' => 'required|exists:statuses,id',
            'gambar_path' => 'nullable|string',
            'tanggal_selesai' => 'required|date|after_or_equal:' . now()->toDateString() . '|before_or_equal:' . now()->addDays(30)->toDateString(),
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'status_id.required' => 'Status wajib dipilih.',
            'status_id.exists' => 'Status tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum hari ini.',
            'tanggal_selesai.before_or_equal' => 'Tanggal selesai tidak boleh lebih dari 30 hari ke depan.',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();
        $tanggalSelesai = $request->input('tanggal_selesai');

        // ✅ Cek apakah user sudah pernah ajukan izin/sakit di rentang tanggal itu
        $sudahAdaPengajuan = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$today, $tanggalSelesai])
            ->whereIn('status_id', [2, 3]) // 2 = izin, 3 = sakit
            ->exists();

        if ($sudahAdaPengajuan) {
            return redirect('/dashboard')->with('error', 'Kamu sudah mengajukan izin/sakit di periode ini!');
        }

        $periode = Carbon::parse($today)->diffInDays(Carbon::parse($tanggalSelesai)) + 1;

        // Upload gambar (seperti sebelumnya)
        $gambarPath = null;
        if ($request->filled('gambar_path')) {
            $fileName = basename($request->gambar_path);
            $from = $request->gambar_path;
            $to = "uploads/gambar/$fileName";

            if (Storage::disk(config('filesystems.default_public_disk'))->exists($from)) {
                Storage::disk(config('filesystems.default_public_disk'))->move($from, $to);
                $gambarPath = $to;
            }
        }

        $slug = Str::slug($request->judul) . '-' . uniqid();

        for ($i = 0; $i < $periode; $i++) {
            $tanggal = Carbon::parse($today)->addDays($i)->toDateString();

            $sudahAda = Absensi::where('user_id', $user->id)
                ->where('tanggal', $tanggal)
                ->exists();

            if (!$sudahAda) {
                Absensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                    'tanggal_selesai' => $tanggalSelesai,
                    'status_id' => $request->status_id,
                    'judul' => $request->judul,
                    'slug' => $slug,
                    'keterangan' => $request->keterangan,
                    'gambar' => $gambarPath,
                ]);
            }
        }

        return redirect('/dashboard')->with('success', 'Pengajuan berhasil dikirim!');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('tmp', config('filesystems.default_public_disk'));
            return $path;
        }
        return null;
    }

    public function revert(Request $request)
    {
        $filename = basename($request->input('filename'));
        +$filePath = "tmp/$filename";

        if (Storage::disk(config('filesystems.default_public_disk'))->exists($filePath)) {
            Storage::disk(config('filesystems.default_public_disk'))->delete($filePath);
            return response()->json(['message' => 'File berhasil dihapus.']);
        }

        return response()->json(['message' => 'File tidak ditemukan.'], 404);
    }


    public function pulang(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();
        $now = Carbon::now('Asia/Makassar');

        $isAlumni = Absensi::where('user_id', Auth::id())
            ->where('status_id', 5)
            ->exists();

        // Validasi jam pulang
        if ($now->isFriday()) {
            // Jumat: mulai jam 16:30
            if ($now->lt(Carbon::createFromTime(16, 30, 0, 'Asia/Makassar')) || $now->hour >= 24) {
                return redirect('/dashboard')->with('error', 'Absen pulang hari Jumat hanya bisa dilakukan antara pukul 16:30 hingga 00:00 WITA.');
            }
        } else {
            // Senin-Kamis: mulai jam 16:00
            if ($now->lt(Carbon::createFromTime(16, 0, 0, 'Asia/Makassar')) || $now->hour >= 24) {
                return redirect('/dashboard')->with('error', 'Absen pulang hanya bisa dilakukan antara pukul 16:00 hingga 00:00 WITA.');
            }
        }

        // Pastikan sudah absen masuk
        $absensi = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi) {
            return redirect('/dashboard')->with('error', 'Belum melakukan absen masuk!');
        }

        if ($absensi->jam_keluar !== null) {
            return redirect('/dashboard')->with('error', 'Sudah absen pulang!');
        }

        if ($isAlumni) {
            return redirect('/dashboard')->with('error', 'Alumni tidak dapat melakukan absensi.');
        }

        $absensi->update([
            'jam_keluar' => $now->format('H:i'),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
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
        $from = request('from');

        $absensis = $user->absensis()
            ->with('status')
            ->orderBy('tanggal', 'desc')
            ->paginate(12);

        $title = $from === 'alumni'
            ? 'Absensi Alumni'
            : 'Absensi User';

        return view('absensi.view-detail', [
            'title' => $title,
            'user' => $user,
            'absensis' => $absensis,
            'from' => $from,
        ]);
    }

    public function stopRange(Absensi $absensi)
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // Pastikan user hanya bisa menghapus absensinya sendiri
        if ($absensi->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Ambil slug untuk identifikasi grup absensi izin/sakit
        $slug = $absensi->slug;

        // Hapus semua absensi dari hari ini sampai tanggal_selesai yang slug-nya sama
        Absensi::where('user_id', $user->id)
            ->where('slug', $slug)
            ->whereDate('tanggal', '>=', $today)
            ->delete();

        // Cari absensi yang masih tersisa dengan slug sama
        $sisaAbsensi = Absensi::where('user_id', $user->id)
            ->where('slug', $slug)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Jika masih ada sisa absensi, update tanggal_selesai-nya ke tanggal terakhir
        if ($sisaAbsensi->isNotEmpty()) {
            $tanggalTerakhir = $sisaAbsensi->max('tanggal');
            $absensiUtama = $sisaAbsensi->first(); // record pertama (biasanya jadi referensi)

            $absensiUtama->update([
                'tanggal_selesai' => $tanggalTerakhir,
            ]);
        }

        if ($sisaAbsensi->isEmpty()) {
            $absensi->delete();
        }

        return redirect('/dashboard')->with('success', 'Rentang waktu berhasil dihentikan mulai hari ini.');
    }

    public function exportPdf(User $user)
    {
        // ambil semua data absensi user, urut dari lama ke terbaru
        $absensis = $user->absensis()
            ->with('status')
            ->orderBy('tanggal', 'asc') // ASC = lama ke baru
            ->get();

        // hitung total per status (Hadir, Izin, Sakit, dll)
        $rekap = $absensis->groupBy(fn($a) => $a->status->nama ?? 'Tidak Ada')
            ->map(fn($g) => $g->count());

        // kirim ke view
        $pdf = Pdf::loadView('pdf.absensi', [
            'user' => $user,
            'absensis' => $absensis,
            'rekap' => $rekap,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("Absensi-{$user->name}.pdf");
    }

    public function exportExcel(User $user)
    {
        $export = new class($user) implements FromView, WithEvents {
            protected $user;

            public function __construct($user)
            {
                $this->user = $user;
            }

            public function view(): View
            {
                $absensis = $this->user->absensis()
                    ->with('status')
                    ->orderBy('tanggal', 'asc')
                    ->get();

                $rekap = $absensis->groupBy(fn($a) => $a->status->nama ?? 'Tidak Ada')
                    ->map(fn($g) => $g->count());

                return view('excel.absensi', [
                    'user' => $this->user,
                    'absensis' => $absensis,
                    'rekap' => $rekap,
                ]);
            }

            // ✨ Tambahkan event styling di sini
            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();

                        // Auto size semua kolom dari A sampai F
                        foreach (range('A', 'F') as $col) {
                            $sheet->getColumnDimension($col)->setAutoSize(true);
                        }

                        // Bold judul & tengah
                        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
                        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

                        // Tengahin header kolom juga
                        $sheet->getStyle('A3:F3')->getAlignment()->setHorizontal('center');

                        // Supaya wrap text di kolom keterangan panjang
                        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
                    },
                ];
            }
        };

        // 🧹 Bersihkan nama & batasi panjang
        $safeName = preg_replace('/[:\\\\\\/?*\\[\\]]/', '', $user->name);
        $safeName = Str::limit($safeName, 25, '');
        $fileName = "Absensi-{$safeName}.xlsx";

        return Excel::download($export, $fileName);
    }
}
