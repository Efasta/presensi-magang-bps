<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('absensi:auto-mark-absent', function () {
    $yesterday = Carbon::yesterday('Asia/Makassar');

    // Cek apakah hari kemarin Sabtu (6) atau Minggu (0)
    if (in_array($yesterday->dayOfWeek, [0, 6])) {
        $this->info("Hari kemarin adalah {$yesterday->format('l')} ({$yesterday->toDateString()}), tidak ada absen otomatis.");
        return;
    }

    // Ambil tanggal sebagai string
    $yesterdayStr = $yesterday->toDateString();

    // Ambil semua user ID yang bukan admin
    $userIds = DB::table('users')
        ->where('is_admin', '!=', 1)
        ->pluck('id');

    foreach ($userIds as $userId) {
        // Cek apakah user ini sudah absen di tanggal kemarin
        $sudahAbsen = DB::table('absensis')
            ->where('user_id', $userId)
            ->whereDate('tanggal', $yesterdayStr)
            ->exists();

        if (!$sudahAbsen) {
            // Kalau belum absen kemarin, tandai absen otomatis
            DB::table('absensis')->insert([
                'user_id' => $userId,
                'tanggal' => $yesterdayStr,
                'status_id' => 4, // Asumsikan 4 = Absen
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info("User ID $userId ditandai absen pada $yesterdayStr.");
        }
    }

    $this->info('Selesai tandai absen otomatis.');
});

Artisan::command('user:auto-delete', function () {
    $today = Carbon::now('Asia/Makassar')->toDateString();

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

    // ✅ Hanya kirim email jika ada user yang dihapus
    if ($deletedCount > 0) {
        $to = "chatbotbpsabsen@gmail.com"; // ganti sesuai email penerima
        $subject = "Laporan Auto Delete User - {$today}";
        $body = "Tanggal: {$today}\n"
            . "Jumlah user dihapus: {$deletedCount}\n\n"
            . "Detail user:\n";

        foreach ($users as $u) {
            $body .= "- {$u->name} ({$u->email}) [keluar: {$u->tanggal_keluar}]\n";
        }

        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)
                ->subject($subject);
        });

        $this->info("[$deletedCount] user dihapus otomatis & laporan terkirim ke {$to}.");
    } else {
        $this->info("Tidak ada user yang dihapus hari ini. Email tidak dikirim.");
    }
});

Artisan::command('user:morning-absen-reminder', function () {
    $now = \Carbon\Carbon::now('Asia/Makassar');
    $jam = $now->format('H:i');

    // ✅ Validasi jika hari ini Sabtu (6) atau Minggu (0)
    if (in_array($now->dayOfWeek, [0, 6])) {
        $this->info("Hari ini adalah {$now->format('l')} ({$now->toDateString()}), tidak ada pengiriman notifikasi absen.");
        return;
    }

    // ✅ Validasi waktu (07:00 - 08:00 WITA)
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
})->purpose('Kirim notifikasi absen pagi setiap 5 menit antara jam 07:00 - 08:00 WITA');

Artisan::command('notif:auto-cleanup', function () {
    $batasWaktu = \Carbon\Carbon::now('Asia/Makassar')->subDay(); // Lebih dari 1 hari
    $totalDihapus = DB::table('notifs')
        ->where('is_read', 0)
        ->where('pesan', 'like', 'Halo, sekarang udah jam%yuk absen%') // format pesan reminder
        ->where('created_at', '<', $batasWaktu)
        ->delete();

    $this->info("Berhasil menghapus $totalDihapus notifikasi reminder yang belum dibaca dan sudah lewat 1 hari.");
})->purpose('Hapus notifikasi reminder otomatis yang tidak dibaca user setelah 1 hari');