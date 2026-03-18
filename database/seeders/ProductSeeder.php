<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $food = Category::where('slug', 'food')->first();
        $drinks = Category::where('slug', 'drinks')->first();
        $bakery = Category::where('slug', 'bakery')->first();
        $desserts = Category::where('slug', 'desserts')->first();

        $products = [
            // Food
            [
                'category_id' => $food->id,
                'name' => 'Classic Burger',
                'slug' => 'classic-burger',
                'description' => 'Beef patty with lettuce, tomato, and cheese',
                'price' => 12.99,
                'sku' => 'FOOD-001',
                'modifiers' => ['No onions', 'Extra cheese', 'No pickles', 'Add bacon'],
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $food->id,
                'name' => 'Chicken Wings',
                'slug' => 'chicken-wings',
                'description' => '8 pieces of crispy chicken wings',
                'price' => 10.99,
                'sku' => 'FOOD-002',
                'modifiers' => ['Extra spicy', 'Mild', 'BBQ sauce', 'Buffalo sauce'],
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $food->id,
                'name' => 'Caesar Salad',
                'slug' => 'caesar-salad',
                'description' => 'Fresh romaine lettuce with parmesan and croutons',
                'price' => 8.99,
                'sku' => 'FOOD-003',
                'modifiers' => ['Add chicken', 'No croutons', 'Extra dressing'],
                'is_available' => true,
                'sort_order' => 3,
            ],
            [
                'category_id' => $food->id,
                'name' => 'Margherita Pizza',
                'slug' => 'margherita-pizza',
                'description' => 'Fresh mozzarella, tomato, and basil',
                'price' => 14.99,
                'sku' => 'FOOD-004',
                'modifiers' => ['Extra cheese', 'Thin crust', 'Thick crust'],
                'is_available' => true,
                'sort_order' => 4,
            ],
            
            // Drinks
            [
                'category_id' => $drinks->id,
                'name' => 'Coca Cola',
                'slug' => 'coca-cola',
                'description' => 'Classic Coca Cola',
                'price' => 2.99,
                'sku' => 'DRINK-001',
                'modifiers' => ['No ice', 'Extra ice'],
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $drinks->id,
                'name' => 'Fresh Orange Juice',
                'slug' => 'fresh-orange-juice',
                'description' => 'Freshly squeezed orange juice',
                'price' => 4.99,
                'sku' => 'DRINK-002',
                'modifiers' => ['No sugar', 'Less ice'],
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $drinks->id,
                'name' => 'Iced Coffee',
                'slug' => 'iced-coffee',
                'description' => 'Cold brew coffee with ice',
                'price' => 3.99,
                'sku' => 'DRINK-003',
                'modifiers' => ['No sugar', 'Extra sugar', 'Add milk', 'Almond milk'],
                'is_available' => true,
                'sort_order' => 3,
            ],
            [
                'category_id' => $drinks->id,
                'name' => 'Sparkling Water',
                'slug' => 'sparkling-water',
                'description' => 'Refreshing sparkling water',
                'price' => 2.49,
                'sku' => 'DRINK-004',
                'modifiers' => ['Lemon', 'Lime'],
                'is_available' => true,
                'sort_order' => 4,
            ],
            
            // Bakery
            [
                'category_id' => $bakery->id,
                'name' => 'Croissant',
                'slug' => 'croissant',
                'description' => 'Buttery French croissant',
                'price' => 3.49,
                'sku' => 'BAKERY-001',
                'modifiers' => ['Warm', 'Add butter'],
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $bakery->id,
                'name' => 'Blueberry Muffin',
                'slug' => 'blueberry-muffin',
                'description' => 'Fresh baked blueberry muffin',
                'price' => 2.99,
                'sku' => 'BAKERY-002',
                'modifiers' => ['Warm'],
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $bakery->id,
                'name' => 'Bagel with Cream Cheese',
                'slug' => 'bagel-cream-cheese',
                'description' => 'Toasted bagel with cream cheese',
                'price' => 4.49,
                'sku' => 'BAKERY-003',
                'modifiers' => ['Plain bagel', 'Everything bagel', 'Extra cream cheese'],
                'is_available' => true,
                'sort_order' => 3,
            ],
            
            // Desserts
            [
                'category_id' => $desserts->id,
                'name' => 'Chocolate Cake',
                'slug' => 'chocolate-cake',
                'description' => 'Rich chocolate layer cake',
                'price' => 6.99,
                'sku' => 'DESSERT-001',
                'modifiers' => ['Add ice cream'],
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $desserts->id,
                'name' => 'Cheesecake',
                'slug' => 'cheesecake',
                'description' => 'New York style cheesecake',
                'price' => 5.99,
                'sku' => 'DESSERT-002',
                'modifiers' => ['Strawberry topping', 'Chocolate topping'],
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $desserts->id,
                'name' => 'Ice Cream Sundae',
                'slug' => 'ice-cream-sundae',
                'description' => 'Three scoops with toppings',
                'price' => 4.99,
                'sku' => 'DESSERT-003',
                'modifiers' => ['Vanilla', 'Chocolate', 'Strawberry', 'Add whipped cream'],
                'is_available' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
