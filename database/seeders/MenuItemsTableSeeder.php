<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuItems = [
            // Appetizers
            ['item_name' => 'Bruschetta', 'item_price' => 20000, 'item_description' => 'Toasted bread with tomato and basil', 'item_label' => 'makanan', 'item_type' => 'appetizer', 'image_url' => 'https://example.com/images/bruschetta.jpg'],
            ['item_name' => 'Spring Rolls', 'item_price' => 18000, 'item_description' => 'Crispy spring rolls with vegetables', 'item_label' => 'makanan', 'item_type' => 'appetizer', 'image_url' => 'https://example.com/images/spring_rolls.jpg'],
            
            // Main Courses
            ['item_name' => 'Spaghetti Carbonara', 'item_price' => 50000, 'item_description' => 'Spaghetti with creamy carbonara sauce', 'item_label' => 'makanan', 'item_type' => 'main_course', 'image_url' => 'https://example.com/images/spaghetti_carbonara.jpg'],
            ['item_name' => 'Chicken Curry', 'item_price' => 60000, 'item_description' => 'Spicy chicken curry with rice', 'item_label' => 'makanan', 'item_type' => 'main_course', 'image_url' => 'https://example.com/images/chicken_curry.jpg'],
            
            // Desserts
            ['item_name' => 'Chocolate Cake', 'item_price' => 35000, 'item_description' => 'Rich chocolate cake with ganache', 'item_label' => 'makanan', 'item_type' => 'dessert', 'image_url' => 'https://example.com/images/chocolate_cake.jpg'],
            ['item_name' => 'Tiramisu', 'item_price' => 40000, 'item_description' => 'Classic Italian dessert with coffee and mascarpone', 'item_label' => 'makanan', 'item_type' => 'dessert', 'image_url' => 'https://example.com/images/tiramisu.jpg'],
            
            // Beverages
            ['item_name' => 'Iced Tea', 'item_price' => 12000, 'item_description' => 'Refreshing iced tea', 'item_label' => 'minuman', 'item_type' => 'drink', 'image_url' => 'https://example.com/images/iced_tea.jpg'],
            ['item_name' => 'Lemonade', 'item_price' => 15000, 'item_description' => 'Fresh lemonade', 'item_label' => 'minuman', 'item_type' => 'drink', 'image_url' => 'https://example.com/images/lemonade.jpg'],
            ['item_name' => 'Coffee', 'item_price' => 10000, 'item_description' => 'Hot brewed coffee', 'item_label' => 'minuman', 'item_type' => 'drink', 'image_url' => 'https://example.com/images/coffee.jpg'],
            
            // Additional items to reach 20 foods and 10 drinks
            ['item_name' => 'Garlic Bread', 'item_price' => 15000, 'item_description' => 'Toasted bread with garlic butter', 'item_label' => 'makanan', 'item_type' => 'appetizer', 'image_url' => 'https://example.com/images/garlic_bread.jpg'],
            ['item_name' => 'Caesar Salad', 'item_price' => 30000, 'item_description' => 'Classic Caesar salad with croutons', 'item_label' => 'makanan', 'item_type' => 'appetizer', 'image_url' => 'https://example.com/images/caesar_salad.jpg'],
            ['item_name' => 'Margherita Pizza', 'item_price' => 50000, 'item_description' => 'Pizza with tomato, mozzarella, and basil', 'item_label' => 'makanan', 'item_type' => 'main_course', 'image_url' => 'https://example.com/images/margherita_pizza.jpg'],
            ['item_name' => 'Beef Steak', 'item_price' => 75000, 'item_description' => 'Grilled beef steak with sauce', 'item_label' => 'makanan', 'item_type' => 'main_course', 'image_url' => 'https://example.com/images/beef_steak.jpg'],
            ['item_name' => 'Pancakes', 'item_price' => 20000, 'item_description' => 'Fluffy pancakes with syrup', 'item_label' => 'makanan', 'item_type' => 'dessert', 'image_url' => 'https://example.com/images/pancakes.jpg'],
            ['item_name' => 'Ice Cream Sundae', 'item_price' => 25000, 'item_description' => 'Ice cream with toppings', 'item_label' => 'makanan', 'item_type' => 'dessert', 'image_url' => 'https://example.com/images/ice_cream_sundae.jpg'],
            ['item_name' => 'Orange Juice', 'item_price' => 12000, 'item_description' => 'Freshly squeezed orange juice', 'item_label' => 'minuman', 'item_type' => 'drink', 'image_url' => 'https://example.com/images/orange_juice.jpg'],
            ['item_name' => 'Milkshake', 'item_price' => 20000, 'item_description' => 'Creamy milkshake with choice of flavor', 'item_label' => 'minuman', 'item_type' => 'drink', 'image_url' => 'https://example.com/images/milkshake.jpg'],
            ['item_name' => 'Smoothie', 'item_price' => 18000, 'item_description' => 'Healthy fruit smoothie', 'item_label' => 'minuman', 'item_type' => 'drink', 'image_url' => 'https://example.com/images/smoothie.jpg'],
        ];

        DB::table('menu_items')->insert($menuItems);
    }
}
