<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $color = $this->faker->randomElement(['000000', 'ffffff', '8B4513', 'FFD700']); // black, white, brown, gold
        return [
            'name' => $this->faker->randomElement(['Classic Frame', 'Modern Edge', 'Vintage Touch', 'Coastal Breeze']),
            'description' => $this->faker->text(200),
            'color' => $this->faker->randomElement(['Black', 'White', 'Brown', 'Gold']),
            'size' => $this->faker->randomElement(['5x7', '8x10', '11x14', '16x20']),
            'price' => $price = $this->faker->randomFloat(2, 25, 200),
            'sale_price' => $this->faker->boolean(30) ? round($price * $this->faker->randomFloat(2, 0.6, 0.9), 2) : null,
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl(640, 480, 'products', true),
            'is_featured' => $this->faker->boolean(20)  
        ];
        
    }
}
