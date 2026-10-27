<?php

namespace Database\Factories;

use App\Models\Project;
use App\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(10, 9999),
            'client' => fake()->company(),
            'category' => fake()->randomElement(['Website', 'Web application', 'Product design']),
            'summary' => fake()->sentence(16),
            'description' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(ProjectStatus::cases()),
            'progress' => fake()->numberBetween(0, 100),
            'started_on' => fake()->optional()->dateTimeBetween('-6 months', 'now'),
            'due_on' => fake()->optional()->dateTimeBetween('now', '+6 months'),
            'project_url' => fake()->optional()->url(),
            'tags' => fake()->randomElements(['Laravel', 'Design', 'Strategy', 'API', 'Mobile'], 3),
            'theme' => fake()->randomElement(['violet', 'lime', 'orange']),
            'is_featured' => false,
            'display_order' => 0,
        ];
    }
}
