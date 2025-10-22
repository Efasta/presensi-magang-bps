<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private function excludeSelesai($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('nama', '!=', 'Selesai');
        });
    }


    public function index()
    {
        $filters = [
            'status' => (array) request()->input('status', []),
            'fungsi' => (array) request()->input('fungsi', []),
        ];
        $userId = Auth::id();
        $isAdmin = Auth::user()->is_admin;

        // cek apakah user punya absensi dengan status_id = 5
        $isAlumni = Auth::user()->absensis()->latest()->first()?->status_id == 5;

        // ✅ Atur default range berdasarkan role / status
        if (request()->has('range')) {
            $range = request('range');
        } else {
            if ($isAdmin || $isAlumni) {
                // 🔹 Admin & Alumni sama-sama dapat range "all" (sepanjang waktu)
                $range = 'all';
            } else {
                // 🔹 Peserta aktif tetap default ke "today"
                $range = 'today';
            }
        }

        $absensisPaginated = null;
        $defaultRange = $range;
        $processedUsers = [];

        // 🎯 Tentukan tanggal awal & akhir berdasarkan range
        switch ($range) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'yesterday':
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
                break;
            case '7':
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '30':
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '60':
                $startDate = now()->subDays(59)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'all':
            default:
                $startDate = $endDate = null;
                break;
        }

        if ($isAdmin) {
            /** ============================
             * 👤 ADMIN MODE
             * ============================ */
            $userQuery = User::with(['absensis' => function ($query) use ($startDate, $endDate) {
                $query->whereNotNull('status_id')->with('status')
                    ->whereHas('status', fn($q) => $q->where('nama', '!=', 'Selesai'));
                if ($startDate && $endDate) {
                    // Gunakan whereDate untuk membandingkan hanya tanggal tanpa waktu
                    $query->whereDate('tanggal', '>=', $startDate->toDateString());
                    $query->whereDate('tanggal', '<=', $endDate->toDateString());
                }
            }, 'fungsi'])
                ->whereHas('absensis', function ($query) use ($startDate, $endDate) {
                    $query->whereNotNull('status_id')
                        ->whereHas('status', fn($q) => $q->where('nama', '!=', 'Selesai'));
                    if ($startDate && $endDate) {
                        $query->whereDate('tanggal', '>=', $startDate->toDateString());
                        $query->whereDate('tanggal', '<=', $endDate->toDateString());
                    }
                })
                ->filter($filters)
                ->orderBy('id', 'asc');

            // Pie chart data
            $absensiQuery = Absensi::whereNotNull('status_id')
                ->whereHas('status', fn($q) => $q->where('nama', '!=', 'Selesai'));
            if ($startDate && $endDate) {
                $absensiQuery->whereDate('tanggal', '>=', $startDate->toDateString());
                $absensiQuery->whereDate('tanggal', '<=', $endDate->toDateString());
            }
            $absensis = $absensiQuery->get();

            $statusCounts = Absensi::join('statuses', 'absensis.status_id', '=', 'statuses.id')
                ->where('statuses.nama', '!=', 'Selesai')
                ->select('statuses.id', 'statuses.nama', DB::raw('COUNT(absensis.id) as user_count'))
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
                })
                ->groupBy('statuses.id', 'statuses.nama')
                ->get();

            // Ambil semua user dulu
            $allUsers = $userQuery->get();

            // Flatten jadi baris2 absensi per user × status
            $processedUsers = collect();
            foreach ($allUsers as $user) {
                $absensisGrouped = $user->absensis
                    ->when(!empty($filters['status']), function ($query) use ($filters) {
                        return $query->filter(function ($absensi) use ($filters) {
                            return in_array($absensi->status->nama, $filters['status']);
                        });
                    })
                    ->groupBy('status_id');

                foreach ($absensisGrouped as $statusGroup) {
                    $status = $statusGroup->first()->status;
                    $processedUsers->push([
                        'user' => $user,
                        'status' => $status->nama ?? '-',
                        'status_color' => $status->warna ?? 'bg-gray-300',
                        'count' => $statusGroup->count(),
                    ]);
                }
            }


            // ✅ Paginasi berdasarkan baris tabel, bukan user
            $page = request()->get('page', 1);
            $perPage = 10; // atau sesuaikan
            $pagedProcessedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
                $processedUsers->forPage($page, $perPage)->values(),
                $processedUsers->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            /** =========================   ===
             * 👤 USER MODE
             * ============================ */
            $user = User::with(['absensis' => function ($query) use ($startDate, $endDate, $filters) {
                $query->whereNotNull('status_id')->with('status')
                    ->whereHas('status', fn($q) => $q->where('nama', '!=', 'Selesai'));

                if ($startDate && $endDate) {
                    $query->whereDate('tanggal', '>=', $startDate->toDateString());
                    $query->whereDate('tanggal', '<=', $endDate->toDateString());
                }

                if (!empty($filters['status'])) {
                    $query->whereHas('status', function ($q) use ($filters) {
                        $q->whereIn('nama', $filters['status']);
                    });
                }
            }, 'fungsi'])->findOrFail($userId);

            $absensiQuery = Absensi::where('user_id', $userId)
                ->whereNotNull('status_id')
                ->whereHas('status', fn($q) => $q->where('nama', '!=', 'Selesai'))
                ->when(!empty($filters['status']), function ($query) use ($filters) {
                    $query->whereHas('status', function ($q) use ($filters) {
                        $q->whereIn('nama', $filters['status']);
                    });
                })
                ->when(!empty($filters['fungsi']), function ($query) use ($filters) {
                    $query->whereHas('user.fungsi', function ($q) use ($filters) {
                        $q->whereIn('nama', $filters['fungsi']);
                    });
                });

            if ($startDate && $endDate) {
                $absensiQuery->whereDate('tanggal', '>=', $startDate->toDateString());
                $absensiQuery->whereDate('tanggal', '<=', $endDate->toDateString());
            }

            $absensis = $absensiQuery->get();

            $absensisPaginated = (clone $absensiQuery)
                ->with(['status', 'user'])
                ->orderBy('tanggal', 'desc')
                ->paginate(10)
                ->withQueryString();

            $statusCounts = Absensi::join('statuses', 'absensis.status_id', '=', 'statuses.id')
                ->select('statuses.id', 'statuses.nama', DB::raw('COUNT(absensis.id) as user_count'))
                ->where('user_id', $userId)
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
                })
                ->groupBy('statuses.id', 'statuses.nama')
                ->get();

            // ✅ Untuk user biasa, kita kosongkan saja processedUsers
            $pagedProcessedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // kosong
                0,
                10,
                request()->get('page', 1),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        // Static data
        $statuses = Status::where('id', '!=', 5)
            ->where('status', '!=', 'Selesai')
            ->get();
        $fungsis = Fungsi::all();

        $userId = Auth::id();

        if ($isAlumni && !session()->has('alumni_popup_shown')) {
            session()->put('show_alumni_popup', true);
            session()->put('alumni_popup_shown', true); // 🔹 hanya diset sekali
        }

        // ✅ Format tanggal masuk & keluar (contoh: "10 Oktober 2025")
        $tanggalMasuk = Auth::user()->tanggal_masuk
            ? Carbon::parse(Auth::user()->tanggal_masuk)->translatedFormat('d F Y')
            : '-';

        $tanggalKeluar = Auth::user()->tanggal_keluar
            ? Carbon::parse(Auth::user()->tanggal_keluar)->translatedFormat('d F Y')
            : '-';


        return view('dashboard', [
            'title' => 'Dashboard',
            'statuses' => $statuses,
            'absensis' => $absensis,
            'absensisPaginated' => $absensisPaginated,
            'fungsis' => $fungsis,
            'statusCounts' => $statusCounts,
            'selectedDate' => now()->format('Y-m-d'),
            'isAdmin' => $isAdmin,
            'defaultRange' => $defaultRange, // ✅ Untuk Blade JS dropdown
            'totalAbsensi' => $isAdmin ? $absensis->count() : null,
            'hasData' => $statusCounts->sum('user_count') > 0,
            'processedUsers' => $pagedProcessedUsers,
            'isAlumni' => $isAlumni,
            'status_id' => Auth::user()->status_id,
            'tanggal_masuk' => $tanggalMasuk,
            'tanggal_keluar' => $tanggalKeluar,
        ]);
    }

    public function getChartData($range)
    {
        $isAdmin = Auth::user()->is_admin;
        $userId  = Auth::id();

        $query = Absensi::query()->whereNotNull('status_id')
            ->whereHas('status', fn($q) => $q->where('nama', '!=', 'Selesai'));

        // filter tanggal (sama seperti yang ada)
        switch ($range) {
            case 'today':
                $query->whereDate('tanggal', today());
                break;
            case 'yesterday':
                if ($isAdmin) {
                    $query->whereDate('tanggal', today()->subDay());
                } else {
                    // kalau user bukan admin, fallback ke today
                    $query->whereDate('tanggal', today());
                }
                break;
            case '7':
                $query->whereBetween('tanggal', [now()->subDays(7), now()]);
                break;
            case '30':
                $query->whereBetween('tanggal', [now()->subDays(30), now()]);
                break;
            case '60':
                $query->whereBetween('tanggal', [now()->subDays(60), now()]);
                break;
            case 'all':
            default:
                break;
        }

        if (!$isAdmin) {
            $query->where('user_id', $userId);
        }

        // Clone the query before it's modified by join
        $totalAbsensi = (clone $query)->count();

        // hitung counts yang ada di range
        $counts = $query
            ->join('statuses', 'absensis.status_id', '=', 'statuses.id')
            ->select('statuses.id', 'statuses.nama', DB::raw('COUNT(absensis.id) as user_count'))
            ->groupBy('statuses.id', 'statuses.nama')
            ->get()
            ->keyBy('id');

        // ambil semua status dan gabungkan dengan counts (0 jika tidak ada)
        $statuses = Status::select('id', 'nama')->get();

        $result = $statuses->map(function ($s) use ($counts) {
            return [
                'nama' => $s->nama,
                'user_count' => $counts->has($s->id) ? $counts[$s->id]->user_count : 0,
            ];
        });

        return response()->json([
            'statusCounts' => $result->values(),
            'totalAbsensi' => $totalAbsensi
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
