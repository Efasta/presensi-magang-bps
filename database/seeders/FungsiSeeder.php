<?php
namespace Database\Seeders;

use App\Models\Fungsi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FungsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fungsi::create([
            'nama' => 'IPDS',
            'slug' => 'ipds',
            'warna' => 'bg-blue-100 text-blue-800'
        ]);

        Fungsi::create([
            'nama' => 'Umum',
            'slug' => 'umum',
            'warna' => 'bg-gray-100 text-gray-800'
        ]);

        Fungsi::create([
            'nama' => 'Neraca',
            'slug' => 'neraca',
            'warna' => 'bg-green-100 text-green-800'
        ]);

        Fungsi::create([
            'nama' => 'Statistik Sosial',
            'slug' => 'statistik-sosial',
            'warna' => 'bg-purple-100 text-purple-800'
        ]);

        Fungsi::create([
            'nama' => 'Statistik Produksi',
            'slug' => 'statistik-produksi',
            'warna' => 'bg-red-100 text-red-800'
        ]);
        
        Fungsi::create([
            'nama' => 'Statistik Distribusi',
            'slug' => 'statistik-distribusi',
            'warna' => 'bg-amber-100 text-amber-800'
        ]);
    }
}
