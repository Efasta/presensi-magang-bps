<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    { //randomElement(['Hadir', 'Sakit', 'Izin', 'Absen']);
        return [
            'nama' => fake()->sentence(rand(1, 2), false),
            'warna' => fake()->colorName()
        ];
    }
}
