<?php

namespace Tests\Feature\Dashboard;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamMemberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_team_member(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('dashboard.team-members.store'), [
            'name' => 'Asha Mrema',
            'role' => 'Product designer',
            'email' => 'asha@example.com',
            'avatar_url' => 'https://example.com/asha.jpg',
            'bio' => 'Asha turns complex product ideas into clear, useful experiences.',
            'skills' => 'Research, UI design, Research',
            'is_active' => '1',
            'display_order' => 1,
        ]);

        $response->assertRedirect(route('dashboard.team-members.index'))
            ->assertSessionHas('success');
        $teamMember = TeamMember::where('email', 'asha@example.com')->firstOrFail();
        $this->assertSame(['Research', 'UI design'], $teamMember->skills);
        $this->assertTrue($teamMember->is_active);
    }

    public function test_duplicate_team_member_email_is_rejected(): void
    {
        $user = User::factory()->create();
        TeamMember::factory()->create(['email' => 'asha@example.com']);

        $response = $this->actingAs($user)->post(route('dashboard.team-members.store'), [
            'name' => 'Another Asha',
            'role' => 'Developer',
            'email' => 'asha@example.com',
            'display_order' => 0,
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('team_members', 1);
    }

    public function test_authenticated_user_can_update_and_delete_team_member(): void
    {
        $user = User::factory()->create();
        $teamMember = TeamMember::factory()->create();

        $this->actingAs($user)->put(route('dashboard.team-members.update', $teamMember), [
            'name' => 'Updated teammate',
            'role' => 'Technical lead',
            'email' => $teamMember->email,
            'skills' => 'Laravel, APIs',
            'display_order' => 4,
        ])->assertRedirect(route('dashboard.team-members.index'));

        $this->assertDatabaseHas('team_members', [
            'id' => $teamMember->id,
            'name' => 'Updated teammate',
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->delete(route('dashboard.team-members.destroy', $teamMember))
            ->assertRedirect(route('dashboard.team-members.index'));

        $this->assertModelMissing($teamMember);
    }
}
