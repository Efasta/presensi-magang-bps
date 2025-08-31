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
        $filters = request()->only(['status', 'fungsi']);
        $date = request('date') ? Carbon::parse(request('date')) : Carbon::today();

        $isAdmin = Auth::user()->is_admin;
        $userId = Auth::id();

        $absensisPaginated = null;
        $defaultRange = 'today';

        $processedUsers = [];

        if ($isAdmin) {
            // ADMIN: lihat absensi semua user di tanggal itu
            $users = User::with(['absensis' => function ($query) use ($date) {
                $query->whereDate('tanggal', $date)->whereNotNull('status_id')->with('status');
            }, 'fungsi'])
                ->whereHas('absensis', function ($query) use ($date) {
                    $query->whereDate('tanggal', $date)->whereNotNull('status_id');
                })
                ->filter($filters)
                ->orderBy('id', 'asc')
                ->paginate(10)
                ->withQueryString();

            // Pie chart dan statistik untuk semua user (di tanggal itu)
            $absensis = Absensi::whereDate('tanggal', $date)->get();

            $statusCounts = Status::withCount(['absensis as user_count' => function ($query) use ($date) {
                $query->whereDate('tanggal', $date)->select(DB::raw('count(distinct user_id)'));
            }])->get();

            // Mulai dari tanggal 7 hari ke belakang hingga hari ini (atau bisa disesuaikan)
            $startDate = now()->subDays(7);
            $endDate = now();

            $allUsers = User::with([
                'absensis' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])->with('status');
                },
                'fungsi'
            ])->get();

            $processedUsers = [];

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
            // USER: lihat SEMUA absensinya sendiri, tidak hanya hari ini
            $users = User::with(['absensis' => function ($query) {
                $query->whereNotNull('status_id')->with('status');
            }, 'fungsi'])
                ->where('id', $userId)
                ->orderBy('id', 'asc')
                ->paginate(10)
                ->withQueryString();

            // Ambil seluruh absensi user ini saja untuk grafik
            $absensis = Absensi::where('user_id', $userId)->whereDate('tanggal', $date)->whereNotNull('status_id')->get();

            $absensisPaginated = Absensi::where('user_id', $userId)
                ->whereDate('tanggal', $date) // 🔥 tambahkan filter tanggal juga
                ->whereNotNull('status_id')
                ->with(['status', 'user'])
                ->orderBy('tanggal', 'desc')
                ->paginate(7)
                ->withQueryString();

            $statusCounts = Status::withCount(['absensis as user_count' => function ($query) use ($userId, $date) {
                $query->where('user_id', $userId)->whereDate('tanggal', $date)->whereNotNull('status_id');
            }])->get();
        }

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
            'selectedDate' => $date->format('Y-m-d'),
            'isAdmin' => $isAdmin,
            'defaultRange' => $defaultRange,
            'isAdmin' => $isAdmin,
            'totalAbsensi' => $isAdmin ? $absensis->count() : null,
            'hasData' => $statusCounts->sum('user_count') > 0,
            'processedUsers' => $processedUsers, // ✅ Tambahkan ini
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

        return response()->json($result->values());
    }

    public function getTableData($range)
    {
        $isAdmin = Auth::user()->is_admin;
        $userId = Auth::id();
        $query = Absensi::query()->whereNotNull('status_id');

        // filter tanggal seperti getChartData...
        switch ($range) {
            case 'today':
                $query->whereDate('tanggal', today());
                break;
            case 'yesterday':
                $query->whereDate('tanggal', today()->subDay());
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
            $result = $query->with(['status', 'user'])->orderBy('tanggal', 'desc')->get();

            $mapped = $result->map(function ($a) {
                return [
                    'tanggal' => $a->tanggal,
                    'jam_masuk' => $a->jam_masuk,
                    'jam_keluar' => $a->jam_keluar,
                    'status_nama' => $a->status->nama ?? '-',
                    'status_warna' => $a->status->warna ?? 'bg-gray-300',
                    'judul' => $a->judul,
                    'slug_keterangan' => $a->slug ?? null,
                    'foto' => $a->user->foto ? asset('storage/' . $a->user->foto) : asset('img/Anonymous.png'),
                    'nama' => $a->user->name,
                    'slug_user' => $a->user->slug ?? null, // 🔧 tambahkan ini
                ];
            });


            return response()->json($mapped);
        }

        // Untuk admin: ambil user dan absensinya dalam range
        $startDate = $query->min('tanggal') ?? now()->subDays(7); // fallback
        $endDate = $query->max('tanggal') ?? now();

        $users = User::with([
            'absensis' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])->with('status');
            },
            'fungsi'
        ])->get();

        $processedUsers = [];

        foreach ($users as $user) {
            $absensisGrouped = $user->absensis->groupBy('status_id');
            foreach ($absensisGrouped as $statusGroup) {
                $status = $statusGroup->first()->status;
                $processedUsers[] = [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'user_slug' => $user->slug,
                        'nim' => $user->nim,
                        'fungsi' => $user->fungsi->nama ?? 'Umum',
                        'fungsi_slug' => $user->fungsi->slug,
                        'fungsi_warna' => $user->fungsi->warna ?? 'bg-gray-200',
                        'foto' => $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png'),
                    ],
                    'status' => [
                        'nama' => $status->nama ?? '-',
                        'warna' => $status->warna ?? 'bg-gray-300',
                    ],
                    'count' => $statusGroup->count(),
                ];
            }
        }

        return response()->json(collect($processedUsers));
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
