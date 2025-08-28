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
            'name' => 'Ahmad Fakhri Pratama',
            'slug' => 'ahmad-fakhri-pratama',
            'is_admin' => true,
            'email' => 'fialfakhri08@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'foto' => 'img/QDUXTA4gODAHsxXmssBaKtCp1Sjc3zQPVUNRGgBf.jpg',
            'password' => Hash::make('simple')
        ]);

        User::factory()->create([
            'name' => 'Sigit Ardis Admaja. K',
            'slug' => 'sigit-ardis-admaja.-k',
            'is_admin' => true,
            'email' => 'sigit.ardis2008@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'foto' => 'img/a2jp9a61i4CRujURwbEOYZMIsdbMS9toPcPF6gEc.jpg',
            'password' => Hash::make('13579024680')
        ]);
        
        User::factory()->create([
            'name' => 'Ridho Rajai Alfisyahri',
            'slug' => 'ridho-rajai-alfisyahri',
            'is_admin' => true,
            'email' => 'ridh0gaming@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'foto' => 'img/518g5ANKm9Q0aiEX9qXQpFGRrLu5aWOutISVF5dq.jpg',
        ]);
    }
}
