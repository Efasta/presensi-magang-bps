<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = fake()->word();
        return [
            'tanggal' => fake()->date('Y-m-d', now()->addYear()),
            'jam_masuk' => fake()->time('H:i', now()->subHours(4)),
            'jam_keluar' => fake()->time('H:i', now()->addHours(9)),
            'status_id' => Status::inRandomOrder()->first()?->id,
            'judul' => $judul,
            'slug' => Str::slug($judul) . uniqid(),
            'keterangan' => fake()->sentence(rand(10, 15), false)
            //user_id akan dibuat dengan aftercreating userfactory.

        ];
    }
}
