<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notif;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
        // Jika ingin memberikan notifikasi atau log jika user tidak ditemukan, tambahkan log di sini
        echo "Tidak ada data user di tabel users. Seeder notifikasi dibatalkan.\n";
        return;
    }

        $jamList = ['7:30', '7:35', '7:40', '7:45', '7:50', '7:55', '8:00'];

        foreach ($jamList as $jam) {
            $pesan = "Halo, sekarang udah jam $jam nih, yuk absen sebelum terlambat!";

            // Jika jam 8:00, ubah pesannya jadi khusus
            if ($jam === '8:00') {
                $pesan = 'Halo, sekarang udah jam 8:00 nih, yuk cepet absen! kalo gak absen kamu dinyatain ga hadir loh!!';
            }

            Notif::create([
                'foto' => 'img/BPS_Chatbot.jpg',
                'nama' => 'Chatbot BPS ABSEN ' . date('Y'),
                'slug' => 'chatbot-bps-absen-' . uniqid(), // biar unik, gak bentrok
                'pesan' => $pesan,
                'user_id' => $user->id,
            ]);
        }
    }
}
