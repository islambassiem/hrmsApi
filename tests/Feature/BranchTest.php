<?php

use App\Models\Branch;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;



/**
 * Authentication Tests
 */

test('guest cannot access branches list', function () {
    $this
        ->getJson(route('branches.index'))
        ->assertStatus(401);
});

test('guest cannot access single branch page', function () {
    $branch = branch();
    $this
        ->getJson(route('branches.show', $branch->id))
        ->assertStatus(401);
});

test('guest cannot store a new branch', function () {
    $this
        ->postJson(route('branches.store'), [])
        ->assertStatus(401);
});

test('guest cannot create branch with valid data', function () {
    $barnch = branch()->toarray();
    $this
        ->postJson(route('branches.store'), $barnch)
        ->assertStatus(401);
});


test('guest users cannot update branch', function () {
    $branch = branch();
    $this
        ->putJson(route('branches.update', $branch->id), [])
        ->assertStatus(401);
});

test('guest users cannot update branch with valid data', function () {
    $branch = branch();
    $this
        ->putJson(route('branches.update', $branch->id), $branch->toArray())
        ->assertStatus(401);
});

////////////////////////////////////////////////////////////////////////////////

/**
 * Authorization Test
 */

test('unauthorized users cannot access branches page', function () {
    $user = user();
    $this
        ->actingAs($user)
        ->getJson(route('branches.index'))
        ->assertStatus(403);
});

test('unauthorized users cannot access single branch page', function () {
    $user = user();
    $branch = branch();
    $this
        ->actingAs($user)
        ->getJson(route('branches.index', $branch->id))
        ->assertStatus(403);
});

test('unauthorized users cannot store a new branch', function (){
    $this
        ->actingAs(user())
        ->postJson(route('branches.store', branch()->toArray()))
        ->assertStatus(403);
});

test('unauthorized users cannot update branch', function () {
    $user = user();
    $branch = branch();
    $this
        ->actingAs($user)
        ->putJson(route('branches.update', $branch->id), $branch->toArray())
        ->assertStatus(403);
});

/**
 * Authorized users
 */

test('authorized users can see no content branches page', function () {
    $user = user();
    assignAdmin($user);

    $this
        ->actingAs($user)
        ->getJson(route('branches.index'))
        ->assertStatus(200)
        ->assertExactJsonStructure([
            'message',
        ]);
});

test('authorized users can access branches page', function () {
    $user = user();
    $branches = branch();
    assignAdmin($user);

    $this
        ->actingAs($user)
        ->getJson(route('branches.index'))
        ->assertStatus(200)
        ->assertJsonFragment([
            'name_en' => $branches->name_en,
            'name_ar' => $branches->name_ar,
        ]);
});

test('authorized users can access single branch page', function () {
    $user = user();
    $branch = branch();
    assignAdmin($user);

    $this
        ->actingAs($user)
        ->getJson(route('branches.show', $branch->id))
        ->assertStatus(200)
        ->assertExactJsonStructure([
            'data' => [
                'id',
                'code',
                'name_en',
                'name_ar',
                'created_at',
                'updated_at',
                'created_by' => ['id', 'name', 'email'],
                'updated_by' => ['id', 'name', 'email'],
                'entity' => [
                    'id',
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

test('authenticated users can store a branch', function () {
    $user = user();
    assignAdmin($user);
    $branch = branch()->toArray();

    $this
        ->actingAs($user)
        ->postJson(route('branches.store'), $branch)
        ->assertStatus(201);
});

test('correct validation rules', function ($branch) {
    $user = user();
    assignAdmin($user);
    entity();
    $this
        ->actingAs($user)
        ->postJson(route('branches.store'), $branch)
        ->assertStatus(422);
})->with('branches');


test('authorized users can update branch', function () {
    $user = user();
    assignAdmin($user);
    $branch = branch();

    $this
        ->actingAs($user)
        ->putJson(route('branches.update', $branch->id), $branch->toArray())
        ->assertStatus(200)
        ->assertExactJsonStructure([
            'data' => [
                'id',
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


function user(): User
{
    return User::factory()->create();
}

function entity()
{
    return Entity::factory()->create([
        'created_by' => user()->id,
        'updated_by' => user()->id,
    ]);
}

function branch(): Branch
{
    return Branch::factory()->create([
        'entity_id' => entity()->id,
        'created_by' => user()->id,
        'updated_by' => user()->id,
    ]);
}

function assignAdmin(User $user): void
{
    $role = Role::create(['name' => 'admin']);
    $user->assignRole($role->name);
}

dataset('branches', [
    'all empty' => [
        ['name_en' => '',
        'name_ar' => '',
        'code' => '',
        'entity_id' => '',
        'created_by' => '',
        'updated_by' => ''
        ]
    ],
    'all filled' => [
        ['name_en' => Str::length(20),
        'name_ar' => Str::length(50),
        'code' => Str::length(10),
        'entity_id' => 100,
        'created_by' => 100,
        'updated_by' => 100
        ]
    ],
]);
