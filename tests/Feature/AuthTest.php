<?php

use App\Models\User;

test('user can login successfully', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertExactJsonStructure([
            'user' => [
                'id', 'name', 'email',
            ],
            'token',
        ]);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->postJson('/api/v1/logout')
        ->assertStatus(204);
});
