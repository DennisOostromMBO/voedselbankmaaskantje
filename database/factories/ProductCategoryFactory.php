<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * ProductCategory Factory
 *
 * Factory for generating test product category data.
 */
class ProductCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Fresh Produce', 'Dairy Products', 'Meat & Poultry', 'Seafood',
            'Bakery Items', 'Canned Goods', 'Frozen Foods', 'Grains & Cereals',
            'Beverages', 'Snacks', 'Condiments & Spices', 'Personal Care',
            'Household Items', 'Baby Products', 'Health & Wellness'
        ];

        return [
            'product_id' => Product::factory(),
            'category_name' => $this->faker->randomElement($categories),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'note' => $this->faker->optional(0.3)->text(200), // 30% chance of having a note
        ];
    }

    /**
     * Indicate that the product category is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
