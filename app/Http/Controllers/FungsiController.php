<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class FungsiController extends Controller
{
    public function index(Request $request)
    {
        $startDate = now();
        $endDate = now();
        if ($request->has('range')) {
            $range = $request->get('range');
        } else {
            $range = 'all'; // default khusus admin
        }
        $defaultRange = $range;

        switch ($range) {
            case 'yesterday':
                $startDate = now()->subDay();
                $endDate = now()->subDay();
                break;
            case '7':
                $startDate = now()->subDays(6);
                break;
            case '30':
                $startDate = now()->subDays(29);
                break;
            case '60':
                $startDate = now()->subDays(59);
                break;
            case 'all':
                $startDate = null;
                break;
        }

        $statuses = Status::all();
        $fungsis  = Fungsi::all();

        // fungsi yang dipilih
        $initialFungsi = $request->get('fungsi', $fungsis->first()?->slug ?? '');

        // ambil user berdasarkan fungsi yang dipilih
        $users = User::whereHas('absensis', function ($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
            }
            $query->whereNotNull('status_id');
        })
            ->whereHas('fungsi', function ($q) use ($initialFungsi) {
                if ($initialFungsi) {
                    $q->where('slug', $initialFungsi);
                }
            })
            ->with(['fungsi', 'absensis.status'])
            ->get();

        // proses users → processedUsers
        $processedUsers = [];

        foreach ($users as $user) {
            $filteredAbsensis = $user->absensis
                ->filter(function ($absen) use ($startDate, $endDate) {
                    if ($startDate) {
                        return $absen->tanggal >= $startDate->toDateString() && $absen->tanggal <= $endDate->toDateString();
                    }
                    return true;
                });

            $grouped = $filteredAbsensis->groupBy('status_id');

            foreach ($grouped as $statusId => $absens) {
                $status = $absens->first()->status;

                $processedUsers[] = [
                    'id'           => $user->id,
                    'user'         => $user,
                    'status'       => $status->nama,
                    'status_color' => $status->warna,
                    'count'        => $absens->count(),
                ];
            }
        }

        // pagination untuk processedUsers
        $page    = $request->get('page', 1);
        $perPage = 10;
        $offset  = ($page - 1) * $perPage;

        $processedUsers = new LengthAwarePaginator(
            collect($processedUsers)->slice($offset, $perPage)->values(),
            count($processedUsers),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // data untuk chart
        $data = [];

        foreach ($fungsis as $fungsi) {
            $counts = [];

            foreach ($statuses as $status) {
                $query = DB::table('users')
                    ->join('absensis', 'users.id', '=', 'absensis.user_id')
                    ->where('users.fungsi_id', $fungsi->id)
                    ->where('absensis.status_id', $status->id);

                if ($startDate) {
                    $query->whereBetween('absensis.tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
                }

                $counts[] = $query->count();
            }

            $data[$fungsi->slug] = $counts;
        }

        return view('fungsi', [
            'title'        => 'Fungsi',
            'chartData'    => $data,
            'fungsis'      => $fungsis,
            'statuses'     => $statuses,
            'processedUsers' => $processedUsers,
            'initialFungsi'  => $initialFungsi,
            'defaultRange'   => $defaultRange, // 🔥
        ]);
    }
}
