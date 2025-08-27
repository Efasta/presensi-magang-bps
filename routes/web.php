<?php

use App\Http\Controllers\AbsensiController;
use App\Models\User;
use App\Models\Notif;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Support\Carbon;
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
    $date = request('date') ? Carbon::parse(request('date')) : Carbon::today();

    // Ambil semua absensi pada tanggal tertentu
    $absensis = Absensi::whereDate('tanggal', $date)->get();

    // Ambil semua user yang memiliki absensi di tanggal tersebut
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

    // Status dengan jumlah user-nya per tanggal
    $statusCounts = Status::withCount(['absensis as user_count' => function ($query) use ($date) {
        $query->whereDate('tanggal', $date)->select(DB::raw('count(distinct user_id)'));
    }])->get();

    $statuses = Status::all();
    $fungsis = Fungsi::all();

    return view('dashboard', [
        'title' => 'Dashboard',
        'users' => $users,
        'statuses' => $statuses,
        'absensis' => $absensis,
        'fungsis' => $fungsis,
        'statusCounts' => $statusCounts,
        'selectedDate' => $date->format('Y-m-d'), // untuk frontend
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensis.index');
    Route::post('/absensi/pulang', [AbsensiController::class, 'pulang']);
    Route::post('/absensi', [AbsensiController::class, 'store']);
    Route::get('/absensi/{user:slug}', [AbsensiController::class, 'create']);
    Route::post('/presensi-detail', [AbsensiController::class, 'storeDetail'])->name('absensi.storeDetail');
    Route::get('/presensi-detail/{user:slug}', [AbsensiController::class, 'createDetail']);
});

Route::middleware('auth')->group(function () {
    Route::get('/users', [CardUsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user:slug}', [CardUsersController::class, 'show']);
});

Route::get('/pesan/{notif:slug}', function (Notif $notif) {
    return view('pesan-view', ['title' => 'Detail Pesan', 'notif' => $notif]);
});


Route::get('/fungsi', function () {
    $today = now()->toDateString(); // Gunakan tanggal sekarang (format Y-m-d)

    $users = User::with(['absensis' => function ($query) use ($today) {
        $query->where('tanggal', $today)  // filter absensi berdasarkan kolom 'tanggal'
            ->with('status');
    }, 'fungsi'])
        ->whereHas('absensis', function ($query) use ($today) {
            $query->where('tanggal', $today)
                ->whereNotNull('status_id');
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
                ->where('absensis.tanggal', $today) // pastikan filter tanggal juga di sini
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
})->middleware(['auth', 'verified'])->name('fungsi');

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
