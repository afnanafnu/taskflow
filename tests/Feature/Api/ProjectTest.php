<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list projects for the authenticated user', function () {
    $user = User::factory()->create();

    Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson('/api/v1/projects');

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'status',
                ],
            ],
        ]);
});

it('can create a project', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/v1/projects', [
            'name' => 'Test Project',
            'description' => 'Test project description',
            'status' => 'active',
        ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.name', 'Test Project')
        ->assertJsonPath('data.owner_id', $user->id);

    $this->assertDatabaseHas('projects', [
        'name' => 'Test Project',
        'owner_id' => $user->id,
    ]);
});

it('can view a project the user owns', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $project->id);
});

it('can update a project owned by the user', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->putJson("/api/v1/projects/{$project->id}", [
            'name' => 'Updated Project',
            'description' => 'Updated description',
            'status' => 'completed',
        ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated Project')
        ->assertJsonPath('data.status', 'completed');

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Updated Project',
        'status' => 'completed',
    ]);
});

it('prevents a normal user from updating another users project', function () {
    $owner = User::factory()->create();
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->putJson("/api/v1/projects/{$project->id}", [
            'name' => 'Unauthorized Update',
            'description' => 'Should not update',
            'status' => 'active',
        ]);

    $response->assertForbidden();
});

it('can delete a project owned by the user', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->deleteJson("/api/v1/projects/{$project->id}");

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('projects', [
        'id' => $project->id,
    ]);
});

it('allows an admin to view any project', function () {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $project->id);
});
