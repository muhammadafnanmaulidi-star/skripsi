<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        // Foods Subcategories
        Subcategory::create([
            'category_id' => 1,
            'name' => 'Burger',
            'slug' => 'burger',
            'order' => 1
        ]);

        Subcategory::create([
            'category_id' => 1,
            'name' => 'Super Big',
            'slug' => 'super-big',
            'order' => 2
        ]);

        Subcategory::create([
            'category_id' => 1,
            'name' => 'Snacks & Sides',
            'slug' => 'snacks-sides',
            'order' => 3
        ]);

        // Drinks Subcategories
        Subcategory::create([
            'category_id' => 2,
            'name' => 'Kopi Susu',
            'slug' => 'kopi-susu',
            'order' => 1
        ]);

        Subcategory::create([
            'category_id' => 2,
            'name' => 'Espresso Based',
            'slug' => 'espresso-based',
            'order' => 2
        ]);

        Subcategory::create([
            'category_id' => 2,
            'name' => 'Americano Series',
            'slug' => 'americano-series',
            'order' => 3
        ]);

        Subcategory::create([
            'category_id' => 2,
            'name' => 'Non-Coffee',
            'slug' => 'non-coffee',
            'order' => 4
        ]);
    }
}