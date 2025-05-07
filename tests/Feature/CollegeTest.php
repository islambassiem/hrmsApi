<?php

use Illuminate\Support\Str;

describe('Authentication', function () {

    test('guests cannot access colleges page', function () {
        $this
            ->getJson(route('colleges.index'))
            ->assertStatus(401);
    });

    test('guests cannot show a single college', function () {
        $college = college();
        $this
            ->getJson(route('colleges.show', $college->id))
            ->assertStatus(401);
    });

    test('guests cannot create a new college', function () {
        $college = college();
        $this
            ->postJson(route('colleges.store'), $college->toArray())
            ->assertStatus(401);
    });

    test('guests cannot update a college', function () {
        $college = college();
        $this
            ->putJson(route('colleges.update', $college->id), $college->toArray())
            ->assertStatus(401);
    });
});

describe('Authentication', function () {

    test('authenticated users cannot access colleges page', function () {
        $user = user();
        $this
            ->actingAs($user)
            ->getJson(route('colleges.index'))
            ->assertStatus(403);
    });

    test('authenticated users cannot access a single college page', function () {
        $user = user();
        $college = college();
        $this
            ->actingAs($user)
            ->getJson(route('colleges.show', $college->id))
            ->assertStatus(403);
    });

    test('authenticated users cannot create a new college', function () {
        $user = user();
        $college = college();
        $this
            ->actingAs($user)
            ->postJson(route('colleges.store'), $college->toArray())
            ->assertStatus(403);
    });

    test('authenticated users cannot update a college', function () {
        $user = user();
        $college = college();
        $this
            ->actingAs($user)
            ->putJson(route('colleges.update', $college->id), $college->toArray())
            ->assertStatus(403);
    });
});

describe('authorized users', function () {

    test('authorized users can see no content message when there is no colleges', function () {
        $user = user();
        assignAdmin($user);
        $this
            ->actingAs($user)
            ->getJson(route('colleges.index'))
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'message',
            ]);
    });

    test('authorized users can access colleges index', function () {
        $user = user();
        $college = college();
        assignAdmin($user);
        $this
            ->actingAs($user)
            ->getJson(route('colleges.index'))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'branch' => ['id', 'name_en', 'name_ar', 'code'],
                        'name_en',
                        'name_ar',
                        'code',
                        'created_by' => ['id', 'name', 'email'],
                        'updated_by' => ['id', 'name', 'email'],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    });

    test('authorized users can show a single college', function () {
        $user = user();
        $college = college();
        assignAdmin($user);

        $this
            ->actingAs($user)
            ->getJson(route('colleges.show', $college->id))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'branch' => ['id', 'name_en', 'name_ar', 'code'],
                    'name_en',
                    'name_ar',
                    'code',
                    'created_by' => ['id', 'name', 'email'],
                    'updated_by' => ['id', 'name', 'email'],
                    'created_at',
                    'updated_at',
                ],
            ]);
    });

    test('authorized users can create a new college', function () {
        $user = user();
        assignAdmin($user);
        $college = college();
        $this
            ->actingAs($user)
            ->postJson(route('colleges.store'), $college->toArray())
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name_en',
                    'name_ar',
                    'code',
                    'created_at',
                    'updated_at',
                ],
            ]);
    });

    test('authenticated users can update a college', function () {
        $user = user();
        assignAdmin($user);
        $college = college();
        $this
            ->actingAs($user)
            ->putJson(route('colleges.update', $college->id), [
                'name_en' => 'name_en',
                'name_ar' => 'name_ar',
                'code' => '1234567890',
                'branch_id' => branch()->id,
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name_en',
                    'name_ar',
                    'code',
                    'created_at',
                    'updated_at',
                ],
            ]);
    });

    test('authorized users cannot store invalid data', function ($colleges) {
        $user = user();
        assignAdmin($user);
        $this
            ->actingAs($user)
            ->postJson(route('colleges.store'), [$colleges])
            ->assertStatus(422);
    })->with('colleges');

    test('authorized users cannot update invalid data', function ($colleges) {
        $user = user();
        assignAdmin($user);
        $this
            ->actingAs($user)
            ->putJson(route('colleges.update', college()->id), [$colleges])
            ->assertStatus(422);
    })->with('colleges');

});

dataset('colleges', [
    'all empty' => [
        [
            'name_en' => null,
            'name_ar' => null,
            'code' => null,
            'branch_id' => null,
        ],
    ],
    'no branch id' => [
        [
            'name_en' => Str::length(20),
            'name_ar' => Str::length(20),
            'code' => Str::length(10),
            'branch_id' => null,
        ],
    ],
    'text too long' => [
        [
            'name_en' => Str::length(51),
            'name_ar' => Str::length(51),
            'code' => Str::length(15),
            'branch_id' => null,
        ],
    ],
    'branch does not exist' => [
        [
            'name_en' => Str::length(20),
            'name_ar' => Str::length(20),
            'code' => Str::length(5),
            'branch_id' => 999999,
        ],
    ],
]);
