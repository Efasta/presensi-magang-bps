<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FungsiController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'today'); // default: today

        $startDate = now();
        $endDate = now();

        switch ($range) {
            case 'yesterday':
                $startDate = now()->subDay();
                $endDate = now()->subDay();
                break;
            case '7':
                $startDate = now()->subDays(6); // termasuk hari ini
                break;
            case '30':
                $startDate = now()->subDays(29);
                break;
            case '90':
                $startDate = now()->subDays(89);
                break;
            case 'all':
                $startDate = null;
                break;
        }

        $statuses = Status::all();
        $fungsis = Fungsi::all();

        $users = User::whereHas('absensis', function ($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
            }
            $query->whereNotNull('status_id');
        })
            ->with(['fungsi'])
            ->get()
            ->map(function ($user) use ($startDate, $endDate) {
                $absensi = $user->absensis()
                    ->whereNotNull('status_id')
                    ->when($startDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
                    })
                    ->with('status')
                    ->orderBy('tanggal', 'desc')
                    ->first();

                return [
                    'id' => $user->id,
                    'user' => $user,
                    'slug' => $user->slug,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'status' => optional($absensi->status)->nama ?? '-',
                    'status_color' => optional($absensi->status)->warna ?? 'bg-gray-300',
                    'count' => $user->absensis()
                        ->whereNotNull('status_id')
                        ->when($startDate, function ($query) use ($startDate, $endDate) {
                            return $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
                        })->count(),
                ];
            });


        // Buat data untuk chart
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
            'title' => 'Fungsi',
            'chartData' => $data,
            'fungsis' => $fungsis,
            'statuses' => $statuses,
            'processedUsers' => $users, // sekarang pakai variabel ini
        ]);
    }
}
