<?php

namespace Tests\Feature\Dashboard;

use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
use App\ProjectStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_project_with_assigned_team(): void
    {
        $user = User::factory()->create();
        $teamMember = TeamMember::factory()->create();

        $response = $this->actingAs($user)->post(route('dashboard.projects.store'), [
            'title' => 'Mwangaza Operations Hub',
            'client' => 'Mwangaza Ltd',
            'category' => 'Web application',
            'summary' => 'One clear place for the operations team to coordinate delivery.',
            'description' => 'A detailed internal project note.',
            'status' => ProjectStatus::InProgress->value,
            'progress' => 45,
            'started_on' => '2026-09-01',
            'due_on' => '2026-12-01',
            'project_url' => 'https://example.com',
            'tags' => 'Laravel, Dashboard, Laravel',
            'theme' => 'lime',
            'is_featured' => '1',
            'display_order' => 2,
            'team_member_ids' => [$teamMember->id],
        ]);

        $response->assertRedirect(route('dashboard.projects.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('projects', [
            'title' => 'Mwangaza Operations Hub',
            'slug' => 'mwangaza-operations-hub',
            'status' => ProjectStatus::InProgress->value,
            'is_featured' => true,
        ]);
        $project = Project::where('slug', 'mwangaza-operations-hub')->firstOrFail();
        $this->assertSame(['Laravel', 'Dashboard'], $project->tags);
        $this->assertDatabaseHas('project_team_member', [
            'project_id' => $project->id,
            'team_member_id' => $teamMember->id,
        ]);
    }

    public function test_invalid_project_is_rejected_without_creating_record(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('dashboard.projects.store'), [
            'title' => '',
            'category' => '',
            'summary' => '',
            'status' => 'unknown',
            'progress' => 120,
            'due_on' => '2026-01-01',
            'started_on' => '2026-02-01',
            'theme' => 'blue',
            'display_order' => 0,
        ]);

        $response->assertSessionHasErrors(['title', 'category', 'summary', 'status', 'progress', 'due_on', 'theme']);
        $this->assertDatabaseCount('projects', 0);
    }

    public function test_authenticated_user_can_update_and_delete_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'title' => 'Old title',
            'slug' => 'old-title',
        ]);

        $this->actingAs($user)->put(route('dashboard.projects.update', $project), [
            'title' => 'New title',
            'category' => 'Website',
            'summary' => 'An updated project summary for the public website.',
            'status' => ProjectStatus::Completed->value,
            'progress' => 100,
            'tags' => '',
            'theme' => 'violet',
            'display_order' => 0,
        ])->assertRedirect(route('dashboard.projects.index'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'New title',
            'slug' => 'new-title',
            'status' => ProjectStatus::Completed->value,
        ]);

        $this->actingAs($user)
            ->delete(route('dashboard.projects.destroy', $project))
            ->assertRedirect(route('dashboard.projects.index'));

        $this->assertModelMissing($project);
    }

    public function test_project_list_escapes_user_supplied_content(): void
    {
        $user = User::factory()->create();
        Project::factory()->create(['title' => '<script>alert("project")</script>']);

        $response = $this->actingAs($user)->get(route('dashboard.projects.index'));

        $response->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert("project")</script>', false);
    }
}
