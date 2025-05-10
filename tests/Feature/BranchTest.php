<?php

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

describe('Authentication', function () {

    test('guest cannot access branches index', function () {
        $this
            ->getJson(route('branches.index'))
            ->assertStatus(401);
    });

    test('guest cannot access branch show', function () {
        $branch = branch();
        $this
            ->getJson(route('branches.show', $branch->id))
            ->assertStatus(401);
    });

    test('guest cannot store a new branch with invalid data', function () {
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
});

describe('Authorization', function () {
    test('unauthorized users cannot access branches index', function () {
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
            ->getJson(route('branches.index'))
            ->assertStatus(403);
    });

    test('unauthorized users cannot store a new branch', function () {
        $this
            ->actingAs(user())
            ->postJson(route('branches.store', branch()->toArray()))
            ->assertStatus(403);
    });

    test('unauthorized users cannot update branch', function () {
        $user = user();
        $branch = branch(user: $user);
        $this
            ->actingAs($user)
            ->putJson(route('branches.update', $branch->id), $branch->toArray())
            ->assertStatus(403);
    });
});

describe('authorized users', function () {

    test('authorized users can see no content branches page', function () {
        $user = user();
        giveAdminPermissionsToUserOnBranch($user);

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
        $branches = branch(user: $user);
        giveAdminPermissionsToUserOnBranch($user);

        $this
            ->actingAs($user)
            ->getJson(route('branches.index'))
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name_en',
                        'name_ar',
                        'code',
                        'entity' => [
                            'id',
                            'name_en',
                            'name_ar',
                            'code',
                            'created_at',
                            'updated_at',
                        ],
                        'created_by' => ['id', 'name', 'email'],
                        'updated_by' => ['id', 'name', 'email'],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    });

    test('authorized users can access single branch page', function () {
        $user = user();
        $branch = branch();
        giveAdminPermissionsToUserOnBranch($user);

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
        giveAdminPermissionsToUserOnBranch($user);
        $branch = branch()->toArray();

        $this
            ->actingAs($user)
            ->postJson(route('branches.store'), $branch)
            ->assertStatus(201);
    });

    test('correct validation rules', function ($branch) {
        $user = user();
        giveAdminPermissionsToUserOnBranch($user);
        entity();
        $this
            ->actingAs($user)
            ->postJson(route('branches.store'), [$branch])
            ->assertStatus(422);
    })->with(['branches']);

    test('authorized users can update branch', function () {
        $user = user();
        giveAdminPermissionsToUserOnBranch($user);
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

});

dataset('branches', [
    'all empty' => [
        [
            'name_en' => '',
            'name_ar' => '',
            'code' => '',
            'entity_id' => '',
            'created_by' => '',
            'updated_by' => '',
        ],
    ],
    'all filled' => [
        [
            'name_en' => Str::length(20),
            'name_ar' => Str::length(50),
            'code' => Str::length(10),
            'entity_id' => 100,
            'created_by' => 100,
            'updated_by' => 100,
        ],
    ],
    'missing names' => [
        [
            'name_en' => null,
            'name_ar' => null,
            'code' => Str::length(10),
            'entity_id' => 100,
            'created_by' => 100,
            'updated_by' => 100,
        ],
    ],
    'missing code' => [
        [
            'name_en' => Str::length(20),
            'name_ar' => Str::length(20),
            'code' => null,
            'entity_id' => 100,
            'created_by' => 100,
            'updated_by' => 100,
        ],
    ],
    'names too long' => [
        [
            'name_en' => Str::length(51),
            'name_ar' => Str::length(51),
            'code' => Str::length(10),
            'entity_id' => 100,
            'created_by' => 100,
            'updated_by' => 100,
        ],
    ],
]);

function giveAdminPermissionsToUserOnBranch($user)
{
    $role = Role::create(['name' => 'admin']);

    Permission::create(['name' => 'view_any_branch']);
    Permission::create(['name' => 'view_branch']);
    Permission::create(['name' => 'create_branch']);
    Permission::create(['name' => 'update_branch']);
    Permission::create(['name' => 'delete_branch']);
    Permission::create(['name' => 'restore_branch']);
    Permission::create(['name' => 'force_delete_branch']);

    $user->assignRole($role->name);

    $role->givePermissionTo('view_any_branch');
    $role->givePermissionTo('view_branch');
    $role->givePermissionTo('create_branch');
    $role->givePermissionTo('update_branch');
    $role->givePermissionTo('delete_branch');
    $role->givePermissionTo('restore_branch');
    $role->givePermissionTo('force_delete_branch');
}
