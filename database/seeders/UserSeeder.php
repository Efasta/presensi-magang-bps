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
            'email' => 'admin@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin!@#')
        ]);

        User::factory()->create([
            'name' => 'ADMINIPDS',
            'is_admin' => true,
            'email' => 'ipds@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin!@#')
        ]);

        User::factory()->create([
            'name' => 'ADMINSDM',
            'is_admin' => true,
            'email' => 'sdm@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin!@#')
        ]);

        User::factory()->create([
            'name' => 'ADMINNERACA',
            'is_admin' => true,
            'email' => 'neraca@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin!@#')
        ]);

        User::factory()->create([
            'name' => 'ADMINSOSIAL',
            'is_admin' => true,
            'email' => 'sosial@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin!@#')
        ]);

        User::factory()->create([
            'name' => 'ADMINPRODUKSI',
            'is_admin' => true,
            'email' => 'produksi@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin!@#')
        ]);

        User::factory()->create([
            'name' => 'ADMINDISTRIBUSI',
            'is_admin' => true,
            'email' => 'distribusi@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin!@#')
        ]);
    }
}
