<?php

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list comments for a task', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    Comment::factory()->count(2)->create([
        'task_id' => $task->id,
        'user_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/tasks/{$task->id}/comments");

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'body',
                    'user',
                ],
            ],
        ]);
});

it('can create a comment on a task', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson("/api/v1/tasks/{$task->id}/comments", [
            'body' => 'This is a test comment.',
        ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.body', 'This is a test comment.')
        ->assertJsonPath('data.user.id', $user->id);

    $this->assertDatabaseHas('comments', [
        'task_id' => $task->id,
        'user_id' => $user->id,
        'body' => 'This is a test comment.',
    ]);
});

it('can update its own comment', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $comment = Comment::factory()->create([
        'task_id' => $task->id,
        'user_id' => $user->id,
        'body' => 'Original comment.',
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->putJson("/api/v1/comments/{$comment->id}", [
            'body' => 'Updated comment.',
        ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.body', 'Updated comment.');

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'Updated comment.',
    ]);
});

it('prevents a user from updating another users comment', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $comment = Comment::factory()->create([
        'task_id' => $task->id,
        'user_id' => $owner->id,
        'body' => 'Original comment.',
    ]);

    $response = $this
        ->actingAs($otherUser, 'sanctum')
        ->putJson("/api/v1/comments/{$comment->id}", [
            'body' => 'Unauthorized update.',
        ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'Original comment.',
    ]);
});

it('can delete its own comment', function () {
    $user = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $comment = Comment::factory()->create([
        'task_id' => $task->id,
        'user_id' => $user->id,
        'body' => 'Comment to delete.',
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->deleteJson("/api/v1/comments/{$comment->id}");

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});

it('prevents a user outside the project from viewing comments', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    Comment::factory()->create([
        'task_id' => $task->id,
        'user_id' => $owner->id,
    ]);

    $response = $this
        ->actingAs($otherUser, 'sanctum')
        ->getJson("/api/v1/tasks/{$task->id}/comments");

    $response->assertForbidden();
});

it('allows an admin to update any comment', function () {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $comment = Comment::factory()->create([
        'task_id' => $task->id,
        'user_id' => $owner->id,
        'body' => 'Original comment.',
    ]);

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->putJson("/api/v1/comments/{$comment->id}", [
            'body' => 'Admin updated comment.',
        ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.body', 'Admin updated comment.');
});

it('allows an admin to delete any comment', function () {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->create();

    $project = Project::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    $comment = Comment::factory()->create([
        'task_id' => $task->id,
        'user_id' => $owner->id,
    ]);

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->deleteJson("/api/v1/comments/{$comment->id}");

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});