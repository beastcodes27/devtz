<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\TeamMember;
use App\ProjectStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_does_not_display_demo_work_without_published_projects(): void
    {
        $response = $this->get(route('portfolio'));

        $response->assertOk()
            ->assertDontSee('id="work"', false)
            ->assertDontSee('Pamoja')
            ->assertDontSee('Mwangaza')
            ->assertDontSee('Kijiji')
            ->assertSee('href="#services"', false);
    }

    public function test_portfolio_displays_featured_projects_and_active_team_members(): void
    {
        Storage::fake('public');
        $publishedProject = Project::factory()->create([
            'title' => 'Published project',
            'status' => ProjectStatus::Completed,
            'is_featured' => true,
        ]);
        $publishedProject->images()->createMany([
            ['path' => 'projects/published/one.jpg', 'display_order' => 0],
            ['path' => 'projects/published/two.jpg', 'display_order' => 1],
            ['path' => 'projects/published/three.jpg', 'display_order' => 2],
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
        $visibleTeamMember = TeamMember::factory()->create([
            'name' => 'Visible teammate',
            'photo_path' => 'team-members/visible/photo.jpg',
            'is_active' => true,
        ]);
        Storage::disk('public')->put($visibleTeamMember->photo_path, 'photo');
        TeamMember::factory()->create([
            'name' => 'Hidden teammate',
            'is_active' => false,
        ]);

        $response = $this->get(route('portfolio'));

        $response->assertOk()
            ->assertSee('id="work"', false)
            ->assertSee('Selected work')
            ->assertSee('Published project')
            ->assertSee('data-autoplay-interval="2000"', false)
            ->assertSee('data-gallery-next', false)
            ->assertDontSee('Private project')
            ->assertDontSee('Draft project')
            ->assertSee('Visible teammate')
            ->assertSee(Storage::disk('public')->url($visibleTeamMember->photo_path), false)
            ->assertDontSee('Hidden teammate');
        $this->assertSame(3, substr_count($response->getContent(), 'data-gallery-slide'));
    }
}
