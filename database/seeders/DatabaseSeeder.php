<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan seeders dalam urutan yang benar
        $this->call([
            CategorySeeder::class,      // 1. Create categories dulu
            SubcategorySeeder::class,   // 2. Create subcategories
            MenuSeeder::class,          // 3. Create menus
        ]);
    }
}