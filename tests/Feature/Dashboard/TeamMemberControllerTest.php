<?php

namespace Tests\Feature\Dashboard;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamMemberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_team_member_with_photo_and_login(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $photo = $this->fakeImage('asha.jpg');

        $response = $this->actingAs($user)->post(route('dashboard.team-members.store'), [
            'name' => 'Asha Mrema',
            'role' => 'Product designer',
            'email' => 'asha@example.com',
            'password' => 'secure-member-password',
            'password_confirmation' => 'secure-member-password',
            'photo' => $photo,
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
        Storage::disk('public')->assertExists($teamMember->photo_path);
        $memberUser = $teamMember->user()->firstOrFail();
        $this->assertSame('Asha Mrema', $memberUser->name);
        $this->assertTrue(Hash::check('secure-member-password', $memberUser->password));
    }

    public function test_duplicate_team_member_email_is_rejected(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        TeamMember::factory()->create(['email' => 'asha@example.com']);

        $response = $this->actingAs($user)->post(route('dashboard.team-members.store'), [
            'name' => 'Another Asha',
            'role' => 'Developer',
            'email' => 'asha@example.com',
            'password' => 'secure-member-password',
            'password_confirmation' => 'secure-member-password',
            'photo' => $this->fakeImage('asha.jpg'),
            'display_order' => 0,
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('team_members', 1);
        Storage::disk('public')->assertEmpty();
    }

    public function test_authenticated_user_can_update_and_delete_team_member_account_and_photo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $teamMember = TeamMember::factory()->create([
            'photo_path' => 'team-members/1/old-photo.jpg',
        ]);
        $memberUser = $teamMember->user()->create([
            'name' => $teamMember->name,
            'email' => $teamMember->email,
            'password' => 'old-member-password',
        ]);
        Storage::disk('public')->put($teamMember->photo_path, 'old photo');

        $this->actingAs($user)->put(route('dashboard.team-members.update', $teamMember), [
            'name' => 'Updated teammate',
            'role' => 'Technical lead',
            'email' => 'updated@example.com',
            'password' => 'new-member-password',
            'password_confirmation' => 'new-member-password',
            'photo' => $this->fakeImage('new-photo.webp'),
            'skills' => 'Laravel, APIs',
            'display_order' => 4,
        ])->assertRedirect(route('dashboard.team-members.index'));

        $this->assertDatabaseHas('team_members', [
            'id' => $teamMember->id,
            'name' => 'Updated teammate',
            'email' => 'updated@example.com',
            'is_active' => false,
        ]);
        $teamMember->refresh();
        $memberUser->refresh();
        $this->assertSame('updated@example.com', $memberUser->email);
        $this->assertTrue(Hash::check('new-member-password', $memberUser->password));
        Storage::disk('public')->assertMissing('team-members/1/old-photo.jpg');
        Storage::disk('public')->assertExists($teamMember->photo_path);
        $newPhotoPath = $teamMember->photo_path;

        $this->actingAs($user)
            ->delete(route('dashboard.team-members.destroy', $teamMember))
            ->assertRedirect(route('dashboard.team-members.index'));

        $this->assertModelMissing($teamMember);
        $this->assertModelMissing($memberUser);
        Storage::disk('public')->assertMissing($newPhotoPath);
    }

    public function test_invalid_photo_and_password_are_rejected_without_creating_accounts(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('dashboard.team-members.store'), [
            'name' => 'Asha Mrema',
            'role' => 'Developer',
            'email' => 'asha@example.com',
            'password' => 'short',
            'password_confirmation' => 'different',
            'photo' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
            'display_order' => 0,
        ]);

        $response->assertSessionHasErrors(['password', 'photo']);
        $this->assertDatabaseCount('team_members', 0);
        $this->assertDatabaseCount('users', 1);
        Storage::disk('public')->assertEmpty();
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
