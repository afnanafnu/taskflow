<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can retrieve dashboard statistics', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    Task::factory()->count(2)->create([
        'project_id' => $project->id,
        'status' => 'todo',
    ]);

    Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'completed',
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson('/api/v1/dashboard');

    $response
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);

    expect($response->json('success'))->toBeTrue();
});

it('requires authentication to access dashboard', function () {
    $response = $this->getJson('/api/v1/dashboard');

    $response->assertUnauthorized();
});

it('allows an admin to retrieve dashboard statistics', function () {
    $admin = User::factory()->admin()->create();

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/dashboard');

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
});