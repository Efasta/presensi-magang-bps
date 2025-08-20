<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notif>
 */
class NotifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array 
    {
        $notif_name = fake()->sentence(rand(1, 2), false);
        return [
            'nama' => $notif_name,
            'slug' => Str::slug($notif_name),
            'pesan' => fake()->sentence(rand(7, 10), false)
        ];
    }
}
    // public function definition()
    // {
    //     // Jam notifikasi yang sudah ditentukan
    //     $jamList = ['7:30', '7:35', '7:40', '7:45', '7:50', '7:55', '8:00'];

    //     // Pilih jam secara acak dari daftar jam
    //     $jam = $this->faker->randomElement($jamList);

    //     $pesan = "Halo, sekarang udah jam $jam nih, yuk absen sebelum terlambat!";

    //     if ($jam === '8:00') {
    //         $pesan = 'Halo, sekarang udah jam 8:00 nih, yuk cepet absen! kalo gak absen kamu dinyatain ga hadir loh!!';
    //     }

    //     return [
    //         'user_id' => User::inRandomOrder()->first()->id, // Ambil ID user secara acak
    //         'foto' => 'img/BPS_Chatbot.jpg',
    //         'nama' => 'Chatbot BPS ABSEN ' . date('Y'),
    //         'slug' => 'chatbot-bps-absen-' . uniqid(), // Unik slug untuk setiap notifikasi
    //         'pesan' => $pesan,
    //         'is_read' => false,
    //     ];
    // }
