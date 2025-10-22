<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class FungsiController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Ambil filter dari query string
        $filters = [
            'status' => (array) $request->input('status', []),
            'fungsi' => (array) $request->input('fungsi', []),
        ];

        // ✅ Ambil range filter, default ke 'all' (Sepanjang waktu)
        $range = $request->has('range') ? $request->input('range') : 'all';
        $defaultRange = $range;

        // ✅ Ambil semua fungsi
        $fungsis = Fungsi::all();

        // ✅ Tentukan fungsi default
        $defaultFungsi = $fungsis->firstWhere('slug', 'ipds')?->slug ?? $fungsis->first()->slug ?? null;

        // ✅ Jika fungsi belum dipilih, pakai default
        if (empty($filters['fungsi']) && $defaultFungsi) {
            $filters['fungsi'] = [$defaultFungsi];
        }


        // Tentukan rentang tanggal
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

        // Data utama
        $statuses = Status::where('nama', '!=', 'Selesai')->get();
        $fungsis  = Fungsi::all();

        // Ambil keyword pencarian
        $search = $request->input('search');

        // ✅ Query utama user dengan relasi absensi
        $userQuery = User::with(['fungsi', 'absensis.status'])
            ->whereHas('fungsi', function ($q) use ($filters) {
                if (!empty($filters['fungsi'])) {
                    // 🔧 gunakan slug, bukan nama
                    $q->whereIn('slug', $filters['fungsi']);
                }
            })
            ->whereHas('absensis', function ($q) use ($startDate, $endDate, $filters) {
                $q->whereNotNull('status_id')
                    ->whereHas('status', fn($s) => $s->where('nama', '!=', 'Selesai'));

                if ($startDate && $endDate) {
                    $q->whereDate('tanggal', '>=', $startDate->toDateString())
                        ->whereDate('tanggal', '<=', $endDate->toDateString());
                }

                if (!empty($filters['status'])) {
                    $q->whereHas('status', fn($s) => $s->whereIn('nama', $filters['status']));
                }
            });

        // 🔍 Filter pencarian
        if ($search) {
            $userQuery->where('name', 'like', '%' . $search . '%');
        }

        $users = $userQuery->get();

        // 🔢 Proses hasil absensi per user dan status
        $processedUsers = collect();
        foreach ($users as $user) {
            $absensisGrouped = $user->absensis
                ->filter(function ($absen) use ($startDate, $endDate) {
                    if ($startDate && $endDate) {
                        return $absen->tanggal >= $startDate->toDateString() &&
                            $absen->tanggal <= $endDate->toDateString();
                    }
                    return true;
                })
                ->filter(fn($absen) => $absen->status?->nama !== 'Selesai')
                ->when(!empty($filters['status']), function ($absens) use ($filters) {
                    return $absens->filter(fn($a) => in_array($a->status->nama, $filters['status']));
                })
                ->groupBy('status_id');

            foreach ($absensisGrouped as $group) {
                $status = $group->first()->status;
                $processedUsers->push([
                    'user'         => $user,
                    'status'       => $status->nama ?? '-',
                    'status_color' => $status->warna ?? 'bg-gray-300',
                    'count'        => $group->count(),
                ]);
            }
        }

        // ✅ Pagination
        $page = $request->get('page', 1);
        $perPage = 10;
        $pagedUsers = new LengthAwarePaginator(
            $processedUsers->forPage($page, $perPage)->values(),
            $processedUsers->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ✅ Data Chart per fungsi × status
        $chartData = [];
        foreach ($fungsis as $fungsi) {
            $counts = [];
            foreach ($statuses as $status) {
                $countQuery = Absensi::join('users', 'absensis.user_id', '=', 'users.id')
                    ->where('users.fungsi_id', $fungsi->id)
                    ->where('absensis.status_id', $status->id)
                    ->whereHas('status', fn($q) => $q->where('nama', '!=', 'Selesai'));

                if ($startDate && $endDate) {
                    $countQuery->whereBetween('absensis.tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
                }

                $counts[] = $countQuery->count();
            }
            $chartData[$fungsi->slug] = $counts;
        }

        // ✅ Tentukan initial fungsi
        $initialFungsi = $filters['fungsi'][0] ?? $defaultFungsi;

        // ✅ Return ke view
        return view('fungsi', [
            'title'          => 'Fungsi',
            'chartData'      => $chartData,
            'fungsis'        => $fungsis,
            'statuses'       => $statuses,
            'processedUsers' => $pagedUsers,
            'defaultRange'   => $defaultRange,
            'search'         => $search,
            'filters'        => $filters,
            'initialFungsi'  => $initialFungsi,
        ]);
    }
}
