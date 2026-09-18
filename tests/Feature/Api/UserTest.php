<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents a normal user from listing users', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson('/api/v1/users');

    $response->assertForbidden();
});

it('allows an admin to list users', function () {
    $admin = User::factory()->admin()->create();

    User::factory()->count(3)->create();

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/users');

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
            ],
        ]);
});

it('allows an admin to create a user', function () {
    $admin = User::factory()->admin()->create();

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->postJson('/api/v1/users', [
            'name' => 'New Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.name', 'New Test User')
        ->assertJsonPath('data.email', 'newuser@example.com')
        ->assertJsonPath('data.role', 'user');

    $this->assertDatabaseHas('users', [
        'name' => 'New Test User',
        'email' => 'newuser@example.com',
        'role' => 'user',
    ]);
});

it('prevents a normal user from creating a user', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/v1/users', [
            'name' => 'Unauthorized User',
            'email' => 'unauthorized@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

    $response->assertForbidden();

    $this->assertDatabaseMissing('users', [
        'email' => 'unauthorized@example.com',
    ]);
});

it('allows an admin to view a user', function () {
    $admin = User::factory()->admin()->create();

    $user = User::factory()->create();

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/users/{$user->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.email', $user->email);
});

it('prevents a normal user from viewing another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/users/{$otherUser->id}");

    $response->assertForbidden();
});

it('allows an admin to update a user', function () {
    $admin = User::factory()->admin()->create();

    $user = User::factory()->create([
        'name' => 'Old Name',
    ]);

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->putJson("/api/v1/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
            'role' => 'user',
        ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated Name');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
    ]);
});

it('prevents a normal user from updating another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->putJson("/api/v1/users/{$otherUser->id}", [
            'name' => 'Unauthorized Update',
            'email' => $otherUser->email,
            'password' => '',
            'password_confirmation' => '',
            'role' => 'user',
        ]);

    $response->assertForbidden();
});

it('allows an admin to delete another user', function () {
    $admin = User::factory()->admin()->create();

    $user = User::factory()->create();

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->deleteJson("/api/v1/users/{$user->id}");

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('prevents an admin from deleting their own account', function () {
    $admin = User::factory()->admin()->create();

    $response = $this
        ->actingAs($admin, 'sanctum')
        ->deleteJson("/api/v1/users/{$admin->id}");

    $response->assertForbidden();

    $this->assertDatabaseHas('users', [
        'id' => $admin->id,
    ]);
});