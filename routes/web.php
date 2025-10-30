<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\FungsiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardUsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotifikasiController;

Route::get('/presensi', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/absensi/chart/{range}', [DashboardController::class, 'getChartData']);
    Route::post('/hide-alumni-popup', function () {
        session()->forget('show_alumni_popup');
        return response()->noContent();
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensis.index');
    Route::get('/keterangan/{absensi:slug}', [AbsensiController::class, 'show']);
    Route::get('/absensi-detail/{user:slug}', [AbsensiController::class, 'showDetail']);
    Route::get('/absensi-detail/{user:slug}/pdf', [AbsensiController::class, 'exportPdf'])->name('absensi.exportPdf');
    Route::get('/absensi-detail/{user:slug}/excel', [AbsensiController::class, 'exportExcel'])->name('absensi.exportExcel');
    Route::post('/absensi/{absensi}/stop', [AbsensiController::class, 'stopRange'])->name('absensi.stop');
    Route::post('/absensi/pulang', [AbsensiController::class, 'pulang']);
    Route::post('/absensi', [AbsensiController::class, 'store']);
    Route::get('/absensi/{user:slug}', [AbsensiController::class, 'create']);
    Route::post('/presensi-detail', [AbsensiController::class, 'storeDetail'])->name('absensi.storeDetail');
    Route::get('/presensi-detail/{user:slug}', [AbsensiController::class, 'createDetail']);
    Route::post('/upload-absensi', [AbsensiController::class, 'upload']);
    Route::delete('/revert-absensi', [AbsensiController::class, 'revert']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [CardUsersController::class, 'index'])->name('users.index');
    Route::delete('/users/{user:slug}', [CardUsersController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user:slug}/edit', [CardUsersController::class, 'edit']);
    Route::patch('/users/{user:slug}', [CardUsersController::class, 'update'])->name('users.update');
    Route::get('/users/{user:slug}', [CardUsersController::class, 'show'])->name('users.show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/fungsi', [FungsiController::class, 'index'])->name('fungsi');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pesan', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/broadcast', [NotifikasiController::class, 'broadcast']);
    Route::post('/pesan', [NotifikasiController::class, 'store']);
    Route::get('/pesan/admin/create', [NotifikasiController::class, 'create']);
    Route::delete('/pesan/{notif:slug}', [NotifikasiController::class, 'destroy']);
    Route::get('/pesan/{notif:slug}/edit', [NotifikasiController::class, 'edit']);
    Route::patch('/pesan/{notif:slug}', [NotifikasiController::class, 'update']);
    Route::post('/notifikasi/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::post('/notifikasi/unread', [NotifikasiController::class, 'markUnread'])->name('notifikasi.unread');
    Route::post('/notifikasi/delete', [NotifikasiController::class, 'delete'])->name('notifikasi.delete');
    Route::get('/pesan/{notif:slug}', [NotifikasiController::class, 'show']);
    Route::post('/pesan/bulk', [NotifikasiController::class, 'bulkAction'])->name('notifikasi.bulk');
    Route::get('/ajax/notifikasi', function () {
        if (!Auth::check()) {
            return response()->json(['unreadCount' => 0, 'recentNotifs' => []]);
        }

        $user = Auth::user();

        $unreadCount = \App\Models\Notif::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // ⛔ Sekarang tampilkan hanya yang belum dibaca
        $recentNotifs = \App\Models\Notif::where('user_id', $user->id)
            ->where('is_read', false) // tambahkan ini!
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'slug' => $n->slug,
                    'nama' => $n->nama,
                    'foto' => asset($n->foto),
                    'pesan' => \Illuminate\Support\Str::limit($n->pesan, 120),
                    'created_at' => $n->created_at->diffForHumans(),
                    'is_read' => $n->is_read,
                ];
            });

        return response()->json([
            'unreadCount' => $unreadCount,
            'recentNotifs' => $recentNotifs,
        ]);
    })->name('ajax.notifikasi');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload-profile', [ProfileController::class, 'upload']);
    Route::delete('/revert-profile', [ProfileController::class, 'revert']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{user:slug}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::get('/absensi-detail-alumni/{user:slug}', [AlumniController::class, 'showDetail'])->name('alumni.showDetail');
    Route::get('/alumni/{user:slug}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
});

require __DIR__ . '/auth.php';
