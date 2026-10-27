<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->randomElement(['Designer', 'Developer', 'Project lead', 'Strategist']),
            'email' => fake()->unique()->safeEmail(),
            'avatar_url' => null,
            'photo_path' => null,
            'bio' => fake()->sentence(18),
            'skills' => fake()->randomElements(['Laravel', 'UI design', 'Strategy', 'JavaScript', 'APIs'], 3),
            'is_active' => true,
            'display_order' => 0,
        ];
    }
}
