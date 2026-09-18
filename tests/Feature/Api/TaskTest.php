<?php

use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list tasks for a project', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $project->users()->attach($user->id, [
        'role' => 'manager',
    ]);

    Task::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}/tasks");

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'due_date',
                ],
            ],
        ]);
});

it('can create a task in a project', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson("/api/v1/projects/{$project->id}/tasks", [
            'title' => 'Test Task',
            'description' => 'Test task description',
            'status' => 'todo',
            'priority' => 'high',
        ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.title', 'Test Task')
        ->assertJsonPath('data.status', 'todo')
        ->assertJsonPath('data.priority', 'high');

    $this->assertDatabaseHas('tasks', [
        'project_id' => $project->id,
        'title' => 'Test Task',
        'status' => 'todo',
        'priority' => 'high',
    ]);
});

it('can view a task in a project', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}"
        );

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $task->id);
});

it('can update a task', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->putJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}",
            [
                'title' => 'Updated Task',
                'description' => 'Updated description',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => '2026-12-31',
            ]
        );

    $response
        ->assertOk()
        ->assertJsonPath('data.title', 'Updated Task')
        ->assertJsonPath('data.status', 'in_progress')
        ->assertJsonPath('data.priority', 'high');

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Task',
        'status' => 'in_progress',
    ]);
});

it('can delete a task', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->deleteJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}"
        );

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});

it('can change task status', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'todo',
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->patchJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}/status",
            [
                'status' => 'completed',
            ]
        );

    $response
        ->assertOk()
        ->assertJsonPath('data.status', 'completed');

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'completed',
    ]);
});

it('can attach a label to a task', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $label = Label::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}/labels",
            [
                'label_id' => $label->id,
            ]
        );

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $task->id);

    $this->assertDatabaseHas('label_task', [
        'task_id' => $task->id,
        'label_id' => $label->id,
    ]);
});

it('can detach a label from a task', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $label = Label::factory()->create();

    $task->labels()->attach($label->id);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->deleteJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}/labels/{$label->id}"
        );

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $task->id);

    $this->assertDatabaseMissing('label_task', [
        'task_id' => $task->id,
        'label_id' => $label->id,
    ]);
});

it('prevents a user outside the project from viewing its tasks', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($otherUser, 'sanctum')
        ->getJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}"
        );

    $response->assertForbidden();
});

it('prevents a user outside the project from creating a task', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $response = $this
        ->actingAs($otherUser, 'sanctum')
        ->postJson(
            "/api/v1/projects/{$project->id}/tasks",
            [
                'title' => 'Unauthorized Task',
                'description' => 'Should not be created',
                'status' => 'todo',
                'priority' => 'medium',
            ]
        );

    $response->assertForbidden();

    $this->assertDatabaseMissing('tasks', [
        'project_id' => $project->id,
        'title' => 'Unauthorized Task',
    ]);
});

it('allows an admin to manage tasks in any project', function () {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->getJson(
            "/api/v1/projects/{$project->id}/tasks/{$task->id}"
        );

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $task->id);
});