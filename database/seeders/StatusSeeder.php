<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'nama' => "Hadir",
            'warna' => "bg-green-500"
        ]);

        Status::create([
            'nama' => "Sakit",
            'warna' => "bg-yellow-500"
        ]);

        Status::create([
            'nama' => "Izin",
            'warna' => "bg-blue-500"
        ]);

        Status::create([
            'nama' => "Absen",
            'warna' => "bg-red-500"
        ]);
    }
}
