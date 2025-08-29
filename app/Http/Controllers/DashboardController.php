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
            $absensis = Absensi::where('user_id', $userId)->whereNotNull('status_id')->get();

            $absensisPaginated = Absensi::where('user_id', $userId)
                ->whereNotNull('status_id')
                ->with(['status', 'user'])
                ->orderBy('tanggal', 'desc')
                ->paginate(7)
                ->withQueryString();

            $statusCounts = Status::withCount(['absensis as user_count' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->whereNotNull('status_id');
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
        ]);
    }

    public function getChartData($range)
{
    $query = Absensi::query();

    switch ($range) {
        case 'today':
            $query->whereDate('tanggal', today()); // pakai `tanggal` bukan `created_at`
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
    }

    $statusCounts = $query->join('statuses', 'absensis.status_id', '=', 'statuses.id')
                          ->select('statuses.nama', DB::raw('COUNT(absensis.id) as user_count'))
                          ->groupBy('statuses.nama')
                          ->get();

    return response()->json($statusCounts);
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
