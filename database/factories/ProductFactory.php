<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Product Factory
 * 
 * Factory for generating test product data.
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodItems = [
            'Bread', 'Rice', 'Pasta', 'Milk', 'Eggs', 'Chicken', 'Beef', 'Fish',
            'Apples', 'Bananas', 'Carrots', 'Potatoes', 'Onions', 'Tomatoes',
            'Cereal', 'Oatmeal', 'Beans', 'Lentils', 'Cheese', 'Yogurt',
            'Canned Corn', 'Canned Beans', 'Olive Oil', 'Salt', 'Sugar'
        ];

        return [
            'name' => $this->faker->randomElement($foodItems),
            'description' => $this->faker->optional(0.7)->text(100), // 70% chance of having a description
            'barcode' => $this->faker->optional(0.8)->ean13, // 80% chance of having a barcode
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'note' => $this->faker->optional(0.2)->text(200), // 20% chance of having a note
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
