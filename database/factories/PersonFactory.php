<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Person Factory
 * 
 * Factory for generating test person data.
 */
class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->optional(0.8)->phoneNumber, // 80% chance of having a phone
            'email' => $this->faker->optional(0.6)->unique()->safeEmail, // 60% chance of having an email
            'address' => $this->faker->optional(0.9)->address, // 90% chance of having an address
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'note' => $this->faker->optional(0.2)->text(200), // 20% chance of having a note
        ];
    }

    /**
     * Indicate that the person is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the person is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
