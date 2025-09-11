<?php

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('absensi:auto-mark-absent', function () {
    $yesterday = Carbon::yesterday()->toDateString();

    // Ambil semua user ID yang bukan admin
    $userIds = DB::table('users')
        ->where('is_admin', '!=', 1)
        ->pluck('id');

    foreach ($userIds as $userId) {
        // Cek apakah user ini sudah absen di tanggal kemarin
        $sudahAbsen = DB::table('absensis')
            ->where('user_id', $userId)
            ->whereDate('tanggal', $yesterday)
            ->exists();

        if (!$sudahAbsen) {
            // Kalau belum absen kemarin, tandai absen otomatis
            DB::table('absensis')->insert([
                'user_id' => $userId,
                'tanggal' => $yesterday,
                'status_id' => 4, // Asumsikan 4 = Absen
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info("User ID $userId ditandai absen pada $yesterday.");
        }
    }

    $this->info('Selesai tandai absen otomatis.');
});

Artisan::command('user:auto-delete', function () {
    $today = \Illuminate\Support\Carbon::now('Asia/Makassar')->toDateString();

    $users = DB::table('users')
        ->where('is_admin', '!=', 1)
        ->whereDate('tanggal_keluar', '<=', $today)
        ->get();

    $deletedCount = 0;

    foreach ($users as $user) {
        DB::table('deleted_users')->insert([
            'original_user_id'      => $user->id,
            'name'                  => $user->name,
            'email'                 => $user->email,
            'slug'                  => $user->slug,
            'is_admin'              => $user->is_admin,
            'tanggal_keluar'        => $user->tanggal_keluar,
            'full_data'             => json_encode($user),
            'deleted_by_system_at'  => now('Asia/Makassar'),
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        DB::table('users')->where('id', $user->id)->delete();

        $deletedCount++;
    }

    $this->info("[$deletedCount] user dihapus otomatis & dicatat ke deleted_users.");
});

Artisan::command('user:morning-absen-reminder', function () {
    $now = \Carbon\Carbon::now('Asia/Makassar');
    $jam = $now->format('H:i');

    // Validasi waktu (07:00 - 08:00 WITA)
    if ($now->lt($now->copy()->setTime(7, 0)) || $now->gt($now->copy()->setTime(8, 0))) {
        $this->info("Command dijalankan di luar jam 07:00-08:00. Dibatalkan.");
        return;
    }

    $pesan = "Halo, sekarang udah jam {$jam} nih, yuk absen sebelum terlambat!";

    $users = DB::table('users')->where('is_admin', '!=', 1)->get();
    $jumlahDikirim = 0;

    foreach ($users as $user) {
        $notifSudahAda = DB::table('notifs')
            ->where('user_id', $user->id)
            ->where('pesan', $pesan)
            ->whereDate('created_at', $now->toDateString())
            ->exists();

        if (!$notifSudahAda) {
            DB::table('notifs')->insert([
                'user_id'    => $user->id,
                'foto'       => 'img/BPS_Chatbot.jpg',
                'nama'       => 'Chatbot BPS ABSEN 2025',
                'slug'       => \Illuminate\Support\Str::uuid(),
                'pesan'      => $pesan,
                'is_read'    => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $jumlahDikirim++;
        }
    }

    $this->info("{$jumlahDikirim} notifikasi dikirim ke user non-admin pada {$jam}.");
})->purpose('Kirim notifikasi absen pagi setiap 5 menit antara jam 07:00 - 08:00 WITA s');

