<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Stock Model Factory
 * 
 * Generates fake stock data for testing and seeding.
 * Follows PSR-12 standards and provides realistic test data.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 * @author Wassim
 * @version 1.0
 */
class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodItems = [
            'Bread', 'Rice', 'Pasta', 'Milk', 'Eggs', 'Chicken', 'Beef', 'Fish',
            'Potatoes', 'Onions', 'Carrots', 'Tomatoes', 'Apples', 'Bananas',
            'Cheese', 'Butter', 'Oil', 'Sugar', 'Salt', 'Flour', 'Cereal',
            'Canned Beans', 'Canned Soup', 'Peanut Butter', 'Jam'
        ];

        $categories = [
            'Bakery', 'Dairy', 'Meat', 'Vegetables', 'Fruits', 'Pantry',
            'Canned Goods', 'Frozen', 'Beverages', 'Snacks'
        ];

        return [
            'name' => $this->faker->randomElement($foodItems),
            'category' => $this->faker->randomElement($categories),
            'quantity' => $this->faker->numberBetween(0, 100),
            'unit' => $this->faker->randomElement(['kg', 'pcs', 'liter', 'pack', 'box', 'can']),
            'expiry_date' => $this->faker->optional(0.8)->dateTimeBetween('now', '+1 year'), // 80% have expiry
            'description' => $this->faker->optional(0.5)->sentence(6), // 50% have description
            'is_available' => $this->faker->boolean(85), // 85% chance of being available
        ];
    }

    /**
     * Indicate that the stock item is available.
     *
     * @return static
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => true,
            'quantity' => $this->faker->numberBetween(1, 100),
        ]);
    }

    /**
     * Indicate that the stock item is out of stock.
     *
     * @return static
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => 0,
            'is_available' => false,
        ]);
    }

    /**
     * Create stock with low quantity.
     *
     * @return static
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->numberBetween(1, 5),
        ]);
    }

    /**
     * Create stock that expires soon.
     *
     * @return static
     */
    public function expiringSoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('now', '+1 week'),
        ]);
    }

    /**
     * Create expired stock.
     *
     * @return static
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'is_available' => false,
        ]);
    }

    /**
     * Create stock from specific category.
     *
     * @param string $category
     * @return static
     */
    public function inCategory(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }

    /**
     * Create stock with high quantity.
     *
     * @return static
     */
    public function highStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * Create stock without expiry date.
     *
     * @return static
     */
    public function nonPerishable(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => null,
        ]);
    }
}
