<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Status;
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
        return [
            'tanggal' => fake()->date('d-m-Y', now()->addYear()),
            'jam_masuk' => fake()->time('H:i', now()->subHours(4)),
            'jam_keluar' => fake()->time('H:i', now()->addHours(9)),
            'status_id' => Status::inRandomOrder()->first()?->id,
            'keterangan' => fake()->sentence(rand(10, 15), false)
            //user_id akan dibuat dengan aftercreating userfactory.

        ];
    }
}
