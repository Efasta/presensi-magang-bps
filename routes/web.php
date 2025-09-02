<?php

use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardUsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotifikasiController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/absensi/chart/{range}', [DashboardController::class, 'getChartData']);
    // ✅ Jika sudah tidak pakai table endpoint:
    // Route::get('/absensi/table/{range}', [DashboardController::class, 'getTableData']); ❌ BISA DIHAPUS
});

Route::middleware('auth')->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensis.index');
    Route::get('/keterangan/{absensi:slug}', [AbsensiController::class, 'show']);
    Route::get('/absensi-detail/{user:slug}', [AbsensiController::class, 'showDetail']);
    Route::post('/absensi/pulang', [AbsensiController::class, 'pulang']);
    Route::post('/absensi', [AbsensiController::class, 'store']);
    Route::get('/absensi/{user:slug}', [AbsensiController::class, 'create']);
    Route::post('/presensi-detail', [AbsensiController::class, 'storeDetail'])->name('absensi.storeDetail');
    Route::get('/presensi-detail/{user:slug}', [AbsensiController::class, 'createDetail']);
    Route::post('/upload-absensi', [AbsensiController::class, 'upload']);
    Route::delete('/revert-absensi', [AbsensiController::class, 'revert']);
});

Route::middleware('auth')->group(function () {
    Route::get('/users', [CardUsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user:slug}', [CardUsersController::class, 'show']);
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
    Route::get('/pesan/{notif:slug}', [NotifikasiController::class, 'show']);
    Route::post('/notifikasi/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::post('/notifikasi/unread', [NotifikasiController::class, 'markUnread'])->name('notifikasi.unread');
    Route::post('/notifikasi/delete', [NotifikasiController::class, 'delete'])->name('notifikasi.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload-profile', [ProfileController::class, 'upload']);
    Route::delete('/revert-profile', [ProfileController::class, 'revert']);
});

require __DIR__ . '/auth.php';
