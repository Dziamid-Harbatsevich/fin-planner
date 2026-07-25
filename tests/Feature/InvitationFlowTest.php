<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_invitation_can_be_sent_to_new_user(): void
    {
        $owner = User::factory()->create();
        $team = Team::factory()->create();

        $owner->teams()->attach($team, ['role' => 'owner']);

        $this->actingAs($owner);

        $response = $this->post(route('teams.invitations.store', $team), [
            'email' => 'newuser@example.com',
            'role' => 'member',
        ]);

        $response->assertRedirect(route('teams.edit', $team));
        $this->assertDatabaseHas('team_invitations', [
            'email' => 'newuser@example.com',
            'team_id' => $team->id,
        ]);
    }

    public function test_invitation_can_be_viewed_publicly(): void
    {
        $owner = User::factory()->create();
        $team = Team::factory()->create();

        $owner->teams()->attach($team, ['role' => 'owner']);

        $invitation = TeamInvitation::create([
            'team_id' => $team->id,
            'email' => 'test@example.com',
            'role' => 'member',
            'invited_by' => $owner->id,
            'expires_at' => now()->addDays(3),
        ]);

        $response = $this->get(route('invitations.show', $invitation));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('invitations/show')
            ->has('invitation')
        );
    }

    public function test_existing_user_can_accept_invitation(): void
    {
        $owner = User::factory()->create();
        $team = Team::factory()->create();
        $user = User::factory()->create(['email' => 'member@example.com']);

        $owner->teams()->attach($team, ['role' => 'owner']);

        $invitation = TeamInvitation::create([
            'team_id' => $team->id,
            'email' => 'member@example.com',
            'role' => 'member',
            'invited_by' => $owner->id,
            'expires_at' => now()->addDays(3),
        ]);

        $this->actingAs($user);

        $response = $this->get(route('invitations.accept', $invitation));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('team_members', [
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);
        $this->assertDatabaseHas('team_invitations', [
            'id' => $invitation->id,
        ]);

        $invitation->refresh();
        $this->assertNotNull($invitation->accepted_at);
    }

    public function test_invitation_shows_in_dashboard(): void
    {
        $owner = User::factory()->create();
        $team = Team::factory()->create();
        $user = User::factory()->create(['email' => 'member@example.com']);

        $owner->teams()->attach($team, ['role' => 'owner']);
        // Make user a member of the team to access dashboard
        $user->teams()->attach($team, ['role' => 'member']);
        $user->switchTeam($team);

        $invitation = TeamInvitation::create([
            'team_id' => $team->id,
            'email' => 'member@example.com',
            'role' => 'member',
            'invited_by' => $owner->id,
            'expires_at' => now()->addDays(3),
        ]);

        $this->actingAs($user);

        $response = $this->get(route('dashboard', $team));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->has('pendingInvitations', 1)
        );
    }
}
