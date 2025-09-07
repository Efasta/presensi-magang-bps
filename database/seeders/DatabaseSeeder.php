<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Fungsi;
use App\Models\Status;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([StatusSeeder::class, FungsiSeeder::class, UserSeeder::class, NotifSeeder::class]);

        User::factory(20)->recycle([
            Status::all(), 
            Fungsi::all()
        ])->create();

    }
}
