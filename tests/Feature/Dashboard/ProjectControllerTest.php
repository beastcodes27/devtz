<?php

namespace Tests\Feature\Dashboard;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\TeamMember;
use App\Models\User;
use App\ProjectStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_project_with_assigned_team(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $teamMember = TeamMember::factory()->create();
        $screenshots = [
            $this->fakeImage('home-page.jpg'),
            $this->fakeImage('dashboard.png'),
            $this->fakeImage('mobile-view.webp'),
        ];

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
            'screenshots' => $screenshots,
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
        $this->assertDatabaseCount('project_images', 3);
        Storage::disk('public')->assertExists($project->images()->pluck('path')->all());
    }

    public function test_invalid_project_is_rejected_without_creating_record(): void
    {
        Storage::fake('public');
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
            'screenshots' => [UploadedFile::fake()->create('project.pdf', 100, 'application/pdf')],
        ]);

        $response->assertSessionHasErrors(['title', 'category', 'summary', 'status', 'progress', 'due_on', 'theme', 'screenshots.0']);
        $this->assertDatabaseCount('projects', 0);
        Storage::disk('public')->assertEmpty();
    }

    public function test_authenticated_user_can_update_and_delete_project(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'title' => 'Old title',
            'slug' => 'old-title',
        ]);
        $existingImage = ProjectImage::factory()->for($project)->create([
            'path' => 'projects/'.$project->id.'/old-screenshot.jpg',
            'display_order' => 0,
        ]);
        Storage::disk('public')->put($existingImage->path, 'old image');

        $this->actingAs($user)->put(route('dashboard.projects.update', $project), [
            'title' => 'New title',
            'category' => 'Website',
            'summary' => 'An updated project summary for the public website.',
            'status' => ProjectStatus::Completed->value,
            'progress' => 100,
            'tags' => '',
            'theme' => 'violet',
            'display_order' => 0,
            'remove_image_ids' => [$existingImage->id],
            'screenshots' => [$this->fakeImage('new-screenshot.jpg')],
        ])->assertRedirect(route('dashboard.projects.index'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'New title',
            'slug' => 'new-title',
            'status' => ProjectStatus::Completed->value,
        ]);
        $this->assertModelMissing($existingImage);
        Storage::disk('public')->assertMissing($existingImage->path);
        $newImage = $project->images()->firstOrFail();
        Storage::disk('public')->assertExists($newImage->path);

        $this->actingAs($user)
            ->delete(route('dashboard.projects.destroy', $project))
            ->assertRedirect(route('dashboard.projects.index'));

        $this->assertModelMissing($project);
        Storage::disk('public')->assertMissing($newImage->path);
    }

    public function test_project_cannot_remove_another_projects_screenshot(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $otherProjectImage = ProjectImage::factory()->create();
        Storage::disk('public')->put($otherProjectImage->path, 'other project image');

        $this->actingAs($user)->put(route('dashboard.projects.update', $project), [
            'title' => $project->title,
            'category' => $project->category,
            'summary' => $project->summary,
            'status' => $project->status->value,
            'progress' => $project->progress,
            'theme' => $project->theme,
            'display_order' => $project->display_order,
            'remove_image_ids' => [$otherProjectImage->id],
        ])->assertRedirect(route('dashboard.projects.index'));

        $this->assertModelExists($otherProjectImage);
        Storage::disk('public')->assertExists($otherProjectImage->path);
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

    private function fakeImage(string $name): UploadedFile
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        );

        return UploadedFile::fake()->createWithContent($name, (string) $png);
    }
}
