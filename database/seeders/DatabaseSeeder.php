<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Jalankan: php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            KonsultanSeeder::class,
        ]);
    }
}