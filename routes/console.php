<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
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

    $yesterdayStr = $yesterday->toDateString();

    $userIds = DB::table('users')
        ->where('is_admin', '!=', 1)
        ->pluck('id');

    foreach ($userIds as $userId) {
        // ✅ Cek apakah user ini sudah berstatus "selesai"
        $sudahSelesai = DB::table('absensis')
            ->where('user_id', $userId)
            ->where('status_id', 5)
            ->exists();

        if ($sudahSelesai) {
            $this->info("User ID {$userId} dilewati (sudah berstatus selesai).");
            continue;
        }

        // Cek apakah user ini sudah absen di tanggal kemarin
        $sudahAbsen = DB::table('absensis')
            ->where('user_id', $userId)
            ->whereDate('tanggal', $yesterdayStr)
            ->exists();

        if (!$sudahAbsen) {
            DB::table('absensis')->insert([
                'user_id' => $userId,
                'tanggal' => $yesterdayStr,
                'status_id' => 4, // Absen otomatis
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info("User ID {$userId} ditandai absen otomatis pada {$yesterdayStr}.");
        }
    }

    $this->info('Selesai tandai absen otomatis.');
});

Artisan::command('user:auto-complete-status', function () {
    $today = Carbon::now('Asia/Makassar')->toDateString();

    $users = DB::table('users')
        ->where('is_admin', '!=', 1)
        ->whereDate('tanggal_keluar', '<=', $today)
        ->get();

    $jumlahDiproses = 0;

    foreach ($users as $user) {
        // ✅ Pastikan hanya 1 absensi selesai per user (tanpa tergantung tanggal)
        $sudahAda = DB::table('absensis')
            ->where('user_id', $user->id)
            ->where('status_id', 5)
            ->exists();

        if (!$sudahAda) {
            // Tambahkan entri absensi baru hanya sekali
            DB::table('absensis')->insert([
                'user_id'       => $user->id,
                'tanggal'       => $user->tanggal_keluar,
                'status_id'     => 5,
                'created_at'    => now('Asia/Makassar'),
                'updated_at'    => now('Asia/Makassar'),
            ]);

            $this->info("✅ Status 'selesai' dibuat untuk user ID {$user->id} ({$user->name})");
            $jumlahDiproses++;
        } else {
            $this->info("⏩ Lewati user {$user->name}, absensi 'selesai' sudah ada.");
        }

        // Kirim notifikasi hanya jika belum pernah dikirim
        if (empty($user->notif_alumni_sent) || $user->notif_alumni_sent == false) {

            Carbon::setLocale('id');
            $tanggalMasukFormatted = Carbon::parse($user->tanggal_masuk)->translatedFormat('d F Y');
            $tanggalKeluarFormatted = Carbon::parse($user->tanggal_keluar)->translatedFormat('d F Y');

            $admins = DB::table('users')->where('is_admin', 1)->get();

            $pesan = <<<EOT
            🎓 <b>Informasi Alumni Magang</b>

            User <b>{$user->name}</b> (<i>NIM/NISN: {$user->nim}</i>) telah resmi menyelesaikan masa magangnya dan dinyatakan sebagai <b>Alumni</b>.

            📚 Jurusan: {$user->jurusan}  
            🏫 Universitas: {$user->universitas}  
            🗓️ Periode Magang: {$tanggalMasukFormatted} – {$tanggalKeluarFormatted}

            Terima kasih atas kontribusi dan dedikasi yang telah diberikan selama menjalani magang di BPS Provinsi Sulawesi Selatan.
            EOT;

            foreach ($admins as $admin) {
                DB::table('notifs')->insert([
                    'user_id'    => $admin->id,
                    'foto'       => 'img/BPS_Chatbot.jpg',
                    'nama'       => 'Chatbot BPS ABSEN 2025',
                    'slug'       => Str::uuid(),
                    'pesan'      => $pesan,
                    'is_read'    => 0,
                    'created_at' => now('Asia/Makassar'),
                    'updated_at' => now('Asia/Makassar'),
                ]);
            }

            // Tandai bahwa notifikasi sudah dikirim
            DB::table('users')
                ->where('id', $user->id)
                ->update(['notif_alumni_sent' => true]);

            $this->info("📩 Notifikasi alumni dikirim ke semua admin untuk user {$user->name}.");
        }
    }

    $this->info("🏁 Total absensi status 'selesai' yang ditambahkan: {$jumlahDiproses}");
})->purpose('Buat absensi status selesai otomatis dan kirim notifikasi ke admin saat user jadi alumni');

Artisan::command('user:morning-absen-reminder', function () {
    $now = Carbon::now('Asia/Makassar');
    $jam = $now->format('H:i');

    // ✅ Jangan kirim di hari Sabtu atau Minggu
    if (in_array($now->dayOfWeek, [0, 6])) {
        $this->info("Hari ini adalah {$now->format('l')} ({$now->toDateString()}), tidak ada pengiriman notifikasi absen.");
        return;
    }

    // ✅ Batasi waktu antara jam 07:00 - 08:00
    if ($now->lt($now->copy()->setTime(7, 0)) || $now->gt($now->copy()->setTime(8, 0))) {
        $this->info("Command dijalankan di luar jam 07:00-08:00. Dibatalkan.");
        return;
    }

    $pesan = "Halo, sekarang udah jam {$jam} nih, yuk absen sebelum terlambat!";
    $jumlahDikirim = 0;

    $users = DB::table('users')
        ->where('is_admin', '!=', 1)
        ->get();

    foreach ($users as $user) {
        // ✅ Cek apakah user ini sudah punya status selesai
        $sudahSelesai = DB::table('absensis')
            ->where('user_id', $user->id)
            ->where('status_id', 5)
            ->exists();

        if ($sudahSelesai) {
            $this->info("User ID {$user->id} dilewati (sudah selesai magang).");
            continue;
        }

        // Cegah notifikasi ganda di hari yang sama
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

    $this->info("{$jumlahDikirim} notifikasi dikirim ke user non-admin aktif pada {$jam}.");
});

Artisan::command('notif:auto-cleanup', function () {
    $batasWaktu = Carbon::now('Asia/Makassar')->subHours(10); // Lebih dari 10 jam
    $totalDihapus = DB::table('notifs')
        ->where('is_read', 0)
        ->where('pesan', 'like', 'Halo, sekarang udah jam%') // format pesan reminder
        ->where('created_at', '<', $batasWaktu)
        ->delete();

    $this->info("Berhasil menghapus $totalDihapus notifikasi reminder yang belum dibaca dan sudah lewat 1 hari.");
})->purpose('Hapus notifikasi reminder otomatis yang tidak dibaca user setelah 1 hari');
