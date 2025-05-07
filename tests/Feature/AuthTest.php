<?php

test('user can login successfully', function () {
    $user = user();

    $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertExactJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
            ],
            'token',
        ]);
});

test('correct email and wrong password', function () {
    $user = user();
    $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'an-entirly-wrong-password',
    ])
        ->assertStatus(422);
});

test('validation error with wrong credentials', function ($credientails) {
    $user = user();

    $this->postJson(route('login'), [
        'email' => $credientails['email'],
        'password' => $credientails['password'],
    ])
        ->assertStatus(422);
})->with('credentials');

test('authenticated user can logout', function () {
    $user = user();

    $this
        ->actingAs($user)
        ->postJson(route('logout'))
        ->assertStatus(204);
});

dataset('credentials', [
    'wrong email' => [
        [
            'email' => fake()->email(),
            'password' => 'password',
        ],
    ],
    'wrong email and password' => [
        [
            'email' => fake()->email(),
            'password' => fake()->text(10),
        ],
    ],
    'envalid email' => [
        [
            'email' => fake()->text(10),
            'password' => fake()->text(10),
        ],
    ],
]);
