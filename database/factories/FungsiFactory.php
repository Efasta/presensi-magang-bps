<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fungsi>
 */
class FungsiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fungsi_name = fake()->sentence(rand(1, 2), false);
        return [
            'nama' => $fungsi_name,
            'slug' => Str::slug($fungsi_name),
            'warna' => fake()->colorName()
        ];
    }
}
