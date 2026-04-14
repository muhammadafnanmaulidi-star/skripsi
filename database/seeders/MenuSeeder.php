<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // ========== FOODS - Burger ==========
        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 1,
            'name' => 'Ocean Burger',
            'description' => 'Crispy fish fillet with sauce and fresh veggies on a soft bun',
            'price' => 28000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 1,
            'name' => 'Firebird Burger',
            'description' => 'Fried chicken, coleslaw, and smoked mayo in a bold, fiery burger',
            'price' => 24000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 1,
            'name' => 'Northern Burger',
            'description' => 'Grilled beef patty, fresh coleslaw, and smoked mayo',
            'price' => 32000,
            'is_available' => true,
        ]);

        // ========== FOODS - Super Big ==========
        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 2,
            'name' => 'Ocean Burger Double Party',
            'description' => 'Double fish patty, double the flavor—stacked with sauce and greens',
            'price' => 36000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 2,
            'name' => 'Northern Burger Double Party',
            'description' => 'Double beef patty with all the toppings',
            'price' => 44000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 2,
            'name' => 'Firebird Burger Double Party',
            'description' => 'Double chicken patty with fiery flavor',
            'price' => 30000,
            'is_available' => true,
        ]);

        // ========== FOODS - Snacks & Sides ==========
        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 3,
            'name' => 'Wedge Wonders',
            'description' => 'Crispy potato wedges with seasoning',
            'price' => 18000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 3,
            'name' => 'Crunchy Rings',
            'description' => 'Fried onion rings, golden and crispy',
            'price' => 18000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 1,
            'subcategory_id' => 3,
            'name' => 'Golden Corn',
            'description' => 'Sweet corn fritters',
            'price' => 16000,
            'is_available' => true,
        ]);

        // ========== DRINKS - Kopi Susu ==========
        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 4,
            'name' => 'Kopi Susu Gula Aren',
            'description' => 'Coffee with palm sugar and fresh milk.',
            'price' => 28000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 4,
            'name' => 'Kopi Susu Salted Caramel',
            'description' => 'Coffee with caramel and a hint of salt.',
            'price' => 32000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 4,
            'name' => 'Kopi Susu Butterscotch',
            'description' => 'Coffee with butterscotch and milk.',
            'price' => 32000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 4,
            'name' => 'Kopi Susu Almond',
            'description' => 'Coffee with almond and fresh milk.',
            'price' => 32000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 4,
            'name' => 'Kopi Susu Caramel',
            'description' => 'Caramel coffee with milk.',
            'price' => 28000,
            'is_available' => true,
        ]);

        // ========== DRINKS - Espresso Based ==========
        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 5,
            'name' => 'Cappuccino',
            'description' => 'Classic hot cappuccino with rich espresso and steamed milk.',
            'price' => 25000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 5,
            'name' => 'Latte',
            'description' => 'Smooth and milky hot latte.',
            'price' => 25000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 5,
            'name' => 'Dirty Latte',
            'description' => 'Cold latte with a shot of espresso on top.',
            'price' => 25000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 5,
            'name' => 'Spanish Latte',
            'description' => 'Iced latte sweetened with condensed milk.',
            'price' => 28000,
            'is_available' => true,
        ]);

        // ========== DRINKS - Americano Series ==========
        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 6,
            'name' => 'Hot Americano',
            'description' => 'Strong and bold hot americano.',
            'price' => 18000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 6,
            'name' => 'Iced Americano',
            'description' => 'Refreshing cold americano with ice.',
            'price' => 20000,
            'is_available' => true,
        ]);

        // ========== DRINKS - Non-Coffee ==========
        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 7,
            'name' => 'Matcha Latte',
            'description' => 'Creamy matcha with steamed milk.',
            'price' => 28000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 7,
            'name' => 'Chocolate Latte',
            'description' => 'Rich chocolate with steamed milk.',
            'price' => 26000,
            'is_available' => true,
        ]);

        Menu::create([
            'category_id' => 2,
            'subcategory_id' => 7,
            'name' => 'Strawberry Smoothie',
            'description' => 'Fresh strawberry blend with yogurt.',
            'price' => 24000,
            'is_available' => true,
        ]);
    }
}