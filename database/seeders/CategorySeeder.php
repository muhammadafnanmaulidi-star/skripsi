<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Foods',
            'slug' => 'foods',
            'order' => 1
        ]);

        Category::create([
            'name' => 'Drinks',
            'slug' => 'drinks',
            'order' => 2
        ]);
    }
}