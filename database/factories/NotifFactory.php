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
