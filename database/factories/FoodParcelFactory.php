<?php

namespace Database\Factories;

use App\Models\FoodParcel;
use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Food Parcel Model Factory
 * 
 * Generates fake data for food parcels for testing and seeding.
 * Follows PSR-12 standards and provides realistic test data.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodParcel>
 * @author Wassim
 * @version 1.0
 */
class FoodParcelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = FoodParcel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stock_id' => Stock::factory(),
            'customer_id' => Customer::factory(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'note' => $this->faker->optional(0.6)->sentence(10), // 60% chance of having a note
        ];
    }

    /**
     * Indicate that the food parcel is active.
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
     * Indicate that the food parcel is inactive.
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
     * Add a detailed note to the food parcel.
     *
     * @return static
     */
    public function withNote(): static
    {
        return $this->state(fn (array $attributes) => [
            'note' => $this->faker->paragraph(3),
        ]);
    }

    /**
     * Create food parcel without note.
     *
     * @return static
     */
    public function withoutNote(): static
    {
        return $this->state(fn (array $attributes) => [
            'note' => null,
        ]);
    }

    /**
     * Create food parcel for a specific customer.
     *
     * @param Customer|\Illuminate\Database\Eloquent\Factories\Factory|int $customer
     * @return static
     */
    public function forCustomer($customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer instanceof Customer ? $customer->id : $customer,
        ]);
    }

    /**
     * Create food parcel for a specific stock.
     *
     * @param Stock|\Illuminate\Database\Eloquent\Factories\Factory|int $stock
     * @return static
     */
    public function forStock($stock): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_id' => $stock instanceof Stock ? $stock->id : $stock,
        ]);
    }

    /**
     * Create food parcel from this month.
     *
     * @return static
     */
    public function thisMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('first day of this month', 'now'),
        ]);
    }

    /**
     * Create food parcel from last month.
     *
     * @return static
     */
    public function lastMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('first day of last month', 'last day of last month'),
        ]);
    }

    /**
     * Create older food parcel.
     *
     * @return static
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-2 months'),
        ]);
    }
}
