<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Delicious food items',
                'icon' => '🍔',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'description' => 'Refreshing beverages',
                'icon' => '🥤',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Bakery',
                'slug' => 'bakery',
                'description' => 'Fresh baked goods',
                'icon' => '🥐',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'description' => 'Sweet treats',
                'icon' => '🍰',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
