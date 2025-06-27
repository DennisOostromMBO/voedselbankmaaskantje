<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Family Factory
 * 
 * Factory for generating test family data.
 */
class FamilyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Family::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'family_member_id' => FamilyMember::factory(),
            'name' => $this->faker->lastName . ' Family',
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'note' => $this->faker->optional(0.3)->text(200), // 30% chance of having a note
        ];
    }

    /**
     * Indicate that the family is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the family is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
