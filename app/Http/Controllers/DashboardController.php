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
    public function index()
    {
        $filters = [
            'status' => (array) request()->input('status', []),
            'fungsi' => (array) request()->input('fungsi', []),
        ];
        $range = request('range', 'today'); // 🔥 Ambil dari query string `?range=`
        $isAdmin = Auth::user()->is_admin;
        $userId = Auth::id();

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
            case '90':
                $startDate = now()->subDays(89)->startOfDay();
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
                $query->whereNotNull('status_id')->with('status');
                if ($startDate && $endDate) {
                    // Gunakan whereDate untuk membandingkan hanya tanggal tanpa waktu
                    $query->whereDate('tanggal', '>=', $startDate->toDateString());
                    $query->whereDate('tanggal', '<=', $endDate->toDateString());
                }
            }, 'fungsi'])
                ->whereHas('absensis', function ($query) use ($startDate, $endDate) {
                    $query->whereNotNull('status_id');
                    if ($startDate && $endDate) {
                        $query->whereDate('tanggal', '>=', $startDate->toDateString());
                        $query->whereDate('tanggal', '<=', $endDate->toDateString());
                    }
                })
                ->filter($filters)
                ->orderBy('id', 'asc');

            $users = $userQuery->paginate(10)->withQueryString();

            // Pie chart data
            $absensiQuery = Absensi::whereNotNull('status_id');
            if ($startDate && $endDate) {
                $absensiQuery->whereDate('tanggal', '>=', $startDate->toDateString());
                $absensiQuery->whereDate('tanggal', '<=', $endDate->toDateString());
            }
            $absensis = $absensiQuery->get();

            $statusCounts = Status::withCount([
                'absensis as user_count' => function ($query) use ($startDate, $endDate) {
                    $query->whereNotNull('status_id');
                    if ($startDate && $endDate) {
                        $query->whereDate('tanggal', '>=', $startDate->toDateString());
                        $query->whereDate('tanggal', '<=', $endDate->toDateString());
                    }
                    $query->select(DB::raw('count(distinct user_id)'));
                }
            ])->get();

            $allUsers = $userQuery->get(); // re-use query

            foreach ($allUsers as $user) {
                $absensisGrouped = $user->absensis->groupBy('status_id');
                foreach ($absensisGrouped as $statusGroup) {
                    $status = $statusGroup->first()->status;
                    $processedUsers[] = [
                        'user' => $user,
                        'status' => $status->nama ?? '-',
                        'status_color' => $status->warna ?? 'bg-gray-300',
                        'count' => $statusGroup->count(),
                    ];
                }
            }
        } else {
            /** ============================
             * 👤 USER MODE
             * ============================ */
            $user = User::with(['absensis' => function ($query) use ($startDate, $endDate) {
                $query->whereNotNull('status_id')->with('status');
                if ($startDate && $endDate) {
                    $query->whereDate('tanggal', '>=', $startDate->toDateString());
                    $query->whereDate('tanggal', '<=', $endDate->toDateString());
                }
            }, 'fungsi'])->findOrFail($userId);

            $users = collect([$user]);

            $absensiQuery = Absensi::where('user_id', $userId)
                ->whereNotNull('status_id')
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
                ->paginate(7)
                ->withQueryString();

            $statusCounts = Status::withCount([
                'absensis as user_count' => function ($query) use ($userId, $startDate, $endDate) {
                    $query->where('user_id', $userId)
                        ->whereNotNull('status_id');
                    if ($startDate && $endDate) {
                        $query->whereDate('tanggal', '>=', $startDate->toDateString());
                        $query->whereDate('tanggal', '<=', $endDate->toDateString());
                    }
                }
            ])->get();
        }

        // Static data
        $statuses = Status::all();
        $fungsis = Fungsi::all();

        return view('dashboard', [
            'title' => 'Dashboard',
            'users' => $users,
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
            'processedUsers' => $processedUsers,
        ]);
    }

    public function getChartData($range)
    {
        $isAdmin = Auth::user()->is_admin;
        $userId  = Auth::id();

        $query = Absensi::query()->whereNotNull('status_id');

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
            case '90':
                $query->whereBetween('tanggal', [now()->subDays(90), now()]);
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
