<?php

use App\Models\User;
use App\Models\Notif;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardUsersController;
use App\Http\Controllers\NotifikasiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $filters = request()->only(['status', 'fungsi']);

    $users = User::with(['absensis.status', 'fungsi']) // eager load
        ->whereHas('absensis', function ($query) {
            $query->whereNotNull('status_id'); // hanya absensi yang punya status
        })
        ->filter($filters)
        ->orderBy('id', 'asc')
        ->paginate(10)
        ->withQueryString();

    $statuses = Status::all();
    $fungsis = Fungsi::all();
    $absensis = Absensi::all();

    $statusCounts = Status::withCount(['absensis as user_count' => function ($query) {
        $query->select(DB::raw('count(distinct id)'));
    }])->get();

    return view('dashboard', [
        'title' => 'Dashboard',
        'users' => $users,
        'statuses' => $statuses,
        'absensis' => $absensis,
        'fungsis' => $fungsis,
        'statusCounts' => $statusCounts
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/absensi', function () {
    $filters = request()->only(['search', 'status', 'fungsi']); // bisa juga tambah 'jenis_kelamin' kalau mau

    $users = User::with(['absensis.status', 'fungsi'])
        ->whereHas('absensis', function ($query) {
            $query->whereDate('tanggal', today());
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
});

// public function showAll()
//     {
//         $filters = request()->only(['search', 'fungsi', 'jenis_kelamin']);

//         $users = User::with(['fungsi'])
//             ->filter($filters)
//             ->orderBy('id', 'asc')
//             ->paginate(20)
//             ->withQueryString();

//         $fungsis = Fungsi::all();

//         return view('users', [
//             'title' => 'Users',
//             'users' => $users,
//             'fungsis' => $fungsis
//         ]);
//     }

Route::middleware('auth')->group(function () {
    Route::get('/users', [CardUsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user:slug}', [CardUsersController::class, 'show']);
});

Route::get('/pesan/{notif:slug}', function (Notif $notif) {
    return view('pesan-view', ['title' => 'Detail Pesan', 'notif' => $notif]);
});


Route::get('/fungsi', function () {
    $users = User::with(['absensis.status', 'fungsi'])
        ->whereHas('absensis', function ($query) {
            $query->whereNotNull('status_id'); // hanya absensi yang punya status
        })->get();
    $fungsis = Fungsi::all();
    $statuses = Status::all();

    $data = [];

    foreach ($fungsis as $fungsi) {
        $counts = [];

        foreach ($statuses as $status) {
            $counts[] = DB::table('users')
                ->join('absensis', 'users.id', '=', 'absensis.user_id')
                ->where('users.fungsi_id', $fungsi->id)
                ->where('absensis.status_id', $status->id)
                ->count();
        }

        $data[$fungsi->slug] = $counts;
    }

    return view('fungsi', [
        'title' => 'Fungsi',
        'chartData' => $data,
        'fungsis' => $fungsis,
        'statuses' => $statuses,
        'users' => $users
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/pesan', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::post('/notifikasi/unread', [NotifikasiController::class, 'markUnread'])->name('notifikasi.unread');
    Route::post('/notifikasi/delete', [NotifikasiController::class, 'delete'])->name('notifikasi.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload', [ProfileController::class, 'upload']);
});

require __DIR__ . '/auth.php';
