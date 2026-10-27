<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\TeamMember;
use App\ProjectStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_displays_featured_projects_and_active_team_members(): void
    {
        Project::factory()->create([
            'title' => 'Published project',
            'status' => ProjectStatus::Completed,
            'is_featured' => true,
        ]);
        Project::factory()->create([
            'title' => 'Private project',
            'status' => ProjectStatus::InProgress,
            'is_featured' => false,
        ]);
        Project::factory()->create([
            'title' => 'Draft project',
            'status' => ProjectStatus::Draft,
            'is_featured' => true,
        ]);
        TeamMember::factory()->create([
            'name' => 'Visible teammate',
            'is_active' => true,
        ]);
        TeamMember::factory()->create([
            'name' => 'Hidden teammate',
            'is_active' => false,
        ]);

        $response = $this->get(route('portfolio'));

        $response->assertOk()
            ->assertSee('Published project')
            ->assertDontSee('Private project')
            ->assertDontSee('Draft project')
            ->assertSee('Visible teammate')
            ->assertDontSee('Hidden teammate');
    }
}
