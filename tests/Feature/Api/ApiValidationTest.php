<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires authentication for protected api endpoints', function () {
    $endpoints = [
        ['GET', '/api/v1/user'],
        ['GET', '/api/v1/dashboard'],
        ['GET', '/api/v1/projects'],
        ['GET', '/api/v1/users'],
    ];

    foreach ($endpoints as [$method, $url]) {
        $response = $this->json($method, $url);

        $response->assertUnauthorized();
    }
});

it('validates required registration fields', function () {
    $response = $this->postJson('/api/v1/register', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'email',
            'password',
        ]);
});

it('rejects duplicate registration email', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $response = $this->postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
        ]);
});

it('validates login fields', function () {
    $response = $this->postJson('/api/v1/login', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
            'password',
        ]);
});

it('validates project creation fields', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/v1/projects', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'status',
        ]);
});

it('validates task creation fields', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson(
            "/api/v1/projects/{$project->id}/tasks",
            []
        );

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'title',
            'status',
            'priority',
        ]);
});

it('validates comment creation fields', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson(
            "/api/v1/tasks/{$task->id}/comments",
            []
        );

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'body',
        ]);
});

it('rejects an invalid task status', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->patchJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}/status",
            [
                'status' => 'invalid_status',
            ]
        );

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'status',
        ]);
});