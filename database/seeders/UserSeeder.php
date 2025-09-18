<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'ADMIN',
            'is_admin' => true,
            'email' => 'chatbotbpsabsen@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin$%^!@#')
        ]);

        User::factory()->create([
            'name' => 'Ahmad Fakhri Pratama',
            'slug' => 'ahmad-fakhri-pratama',
            'is_admin' => false,
            'email' => 'fialfakhri08@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'foto' => 'img/foto saya.jpeg',
        ]);
    }
}
