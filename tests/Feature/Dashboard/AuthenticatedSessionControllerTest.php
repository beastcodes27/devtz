<?php

namespace Tests\Feature\Dashboard;

use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get(route('dashboard.index'))
            ->assertRedirect(route('login'));
    }

    public function test_valid_credentials_authenticate_user_and_regenerate_session(): void
    {
        $user = User::factory()->create(['password' => 'secure-password']);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'secure-password',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_return_a_visible_error(): void
    {
        User::factory()->create([
            'email' => 'owner@devtz.test',
            'password' => 'secure-password',
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'owner@devtz.test',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_sign_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('dashboard.logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_authenticated_user_can_render_every_management_screen(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $teamMember = TeamMember::factory()->create();

        $this->actingAs($user)->get(route('dashboard.index'))->assertOk();
        $this->actingAs($user)->get(route('dashboard.projects.index'))->assertOk();
        $this->actingAs($user)->get(route('dashboard.projects.create'))->assertOk();
        $this->actingAs($user)->get(route('dashboard.projects.edit', $project))->assertOk();
        $this->actingAs($user)->get(route('dashboard.team-members.index'))->assertOk();
        $this->actingAs($user)->get(route('dashboard.team-members.create'))->assertOk();
        $this->actingAs($user)->get(route('dashboard.team-members.edit', $teamMember))->assertOk();
    }
}
