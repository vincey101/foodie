<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FoodItem;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $foods = [
            'Pizza' => [
                'Margherita' => [
                    'Classic tomato and mozzarella', 
                    14.99,
                    'images/pizza-margherita.jpg'
                ],
                'Pepperoni' => [
                    'Spicy pepperoni with cheese', 
                    16.99,
                    'images/pizza-pepperoni.jpg'
                ],
                'Supreme' => [
                    'Loaded with vegetables and meat', 
                    18.99,
                    'images/pizza-supreme.jpg'
                ],
                'BBQ Chicken' => [
                    'Grilled chicken with BBQ sauce', 
                    17.99,
                    'images/pizza-bbq.jpg'
                ]
            ],
            'Burgers' => [
                'Classic Beef' => [
                    '1/3 lb beef patty with fresh veggies', 
                    12.99,
                    'images/burger-classic.jpg'
                ],
                'Cheese Deluxe' => [
                    'Double cheese and bacon', 
                    14.99,
                    'images/burger-cheese.jpg'
                ],
                'Veggie' => [
                    'Plant-based patty with avocado', 
                    13.99,
                    'images/burger-veggie.jpg'
                ],
                'Spicy Chicken' => [
                    'Crispy chicken with spicy sauce', 
                    13.99,
                    'images/burger-chicken.jpg'
                ]
            ],
            'Asian' => [
                'Pad Thai' => [
                    'Rice noodles with shrimp', 
                    15.99,
                    'images/asian-padthai.jpg'
                ],
                'Sushi Roll' => [
                    'California roll with fresh fish', 
                    16.99,
                    'images/asian-sushi.jpg'
                ],
                'Fried Rice' => [
                    'Special house fried rice', 
                    12.99,
                    'images/asian-rice.jpg'
                ],
                'Ramen' => [
                    'Traditional Japanese ramen', 
                    14.99,
                    'images/asian-ramen.jpg'
                ]
            ],
            'Healthy' => [
                'Caesar Salad' => [
                    'Fresh romaine with grilled chicken', 
                    11.99,
                    'images/healthy-caesar.jpg'
                ],
                'Poke Bowl' => [
                    'Fresh tuna with rice', 
                    15.99,
                    'images/healthy-poke.jpg'
                ],
                'Quinoa Bowl' => [
                    'Superfood bowl with avocado', 
                    13.99,
                    'images/healthy-quinoa.jpg'
                ],
                'Greek Salad' => [
                    'Mediterranean style salad', 
                    11.99,
                    'images/healthy-greek.jpg'
                ]
            ]
        ];

        foreach ($foods as $category => $items) {
            foreach ($items as $name => $details) {
                FoodItem::create([
                    'name' => $name,
                    'description' => $details[0],
                    'price' => $details[1],
                    'category' => $category,
                    'image_path' => $details[2],
                    'is_available' => true
                ]);
            }
        }
    }
}
