<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Customer Model Factory
 * 
 * Generates fake customer data for testing and seeding.
 * Follows PSR-12 standards and provides realistic test data.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 * @author Wassim
 * @version 1.0
 */
class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'family_id' => Family::factory(),
            'number' => 'CUST-' . $this->faker->unique()->numberBetween(1000, 9999),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'note' => $this->faker->optional(0.3)->sentence(8), // 30% chance of having notes
        ];
    }

    /**
     * Indicate that the customer is active.
     *
     * @return static
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the customer is inactive.
     *
     * @return static
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create customer with notes.
     *
     * @return static
     */
    public function withNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $this->faker->paragraph(2),
        ]);
    }

    /**
     * Create customer from a specific city.
     *
     * @param string $city
     * @return static
     */
    public function fromCity(string $city): static
    {
        return $this->state(fn (array $attributes) => [
            'city' => $city,
        ]);
    }
}
