<?php

namespace Database\Factories;

use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * FamilyMember Factory
 *
 * Factory for generating test family member data.
 */
class FamilyMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FamilyMember::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $relationships = ['Spouse', 'Child', 'Parent', 'Sibling', 'Guardian', 'Other'];

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'age' => $this->faker->numberBetween(0, 100),
            'relationship' => $this->faker->randomElement($relationships),
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'note' => $this->faker->optional(0.2)->text(200), // 20% chance of having a note
        ];
    }

    /**
     * Indicate that the family member is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the family member is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the family member is a child.
     */
    public function child(): static
    {
        return $this->state(fn (array $attributes) => [
            'age' => $this->faker->numberBetween(0, 17),
            'relationship' => 'Child',
        ]);
    }

    /**
     * Indicate that the family member is a spouse.
     */
    public function spouse(): static
    {
        return $this->state(fn (array $attributes) => [
            'age' => $this->faker->numberBetween(18, 80),
            'relationship' => 'Spouse',
        ]);
    }
}
