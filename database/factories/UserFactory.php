<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->name();
        return [
            'fungsi_id' => Fungsi::inRandomOrder()->first()?->id,
            'name' => $name,
            'slug' => Str::slug($name) . uniqid(),
            'nim' => fake()->numerify(str_repeat('#', rand(8, 15))), // 12 digit NIM
            'jurusan' => fake()->word(),
            'universitas' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'telepon' => '08' . fake()->numerify(str_repeat('#', rand(9, 13))), // 13 digit phone number
            'alamat' => fake()->address(),
            'tanggal_masuk' => fake()->date('d-m-Y', now()->subYear(1)),
            'tanggal_keluar' => fake()->date('d-m-Y', now()->addYear(1)),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'keahlian' => fake()->word(),
            // 'warna' => fake()->colorName(), // Uncomment if needed
            //randomElement(['IPDS', 'Neraca', 'Umum', 'Statistik Sosial', 'Statistik Produksi', 'Statistik Distribusi']),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            Absensi::factory()->create([
                'user_id' => $user->id,
                'status_id' => Status::inRandomOrder()->first()?->id,
            ]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function is_admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_admin' => true,
        ]);
    }
}
