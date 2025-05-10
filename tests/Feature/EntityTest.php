<?php

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;



describe('Authentication', function () {

    test('guests cannot access entities page', function () {
        $this
            ->getJson(route('entities.index'))
            ->assertStatus(401);
    });

    test('guests cannot access sigle entity page', function () {
        $entity = entity();
        $this
            ->getJson(route('entities.show', $entity->id))
            ->assertStatus(401);
    });

    test('guest cannot update an entity', function () {
        $entity = entity();
        $this
            ->putJson(route('entities.update', $entity->id), [])
            ->assertStatus(401);
    });

    test('guests cannot store entity', function () {
        $entity = entity();
        $this
            ->postJson(route('entities.store'), $entity->toArray())
            ->assertStatus(401);
    });
});

describe('Authorization', function () {

    test('authenticated users cannot access entities page', function () {
        $user = user();
        $entity = entity($user);
        $this
            ->actingAs($user)
            ->getJson(route('entities.show', $entity->id))
            ->assertStatus(403);
    });

    test('authenticated users cannot access sigle entity page', function () {
        $user = user();
        $entity = entity($user);
        $this
            ->actingAs($user)
            ->getJson(route('entities.show', $entity->id))
            ->assertStatus(403);
    });

    test('authenticated users cannot store entities', function () {
        $user = user();
        $entity = entity($user);
        $this
            ->actingAs($user)
            ->postJson(route('entities.store'), $entity->toArray())
            ->assertStatus(403);
    });

    test('unauthorized users cannot update an entity', function () {
        $user = user();
        $entity = entity($user);

        $data = [
            'name_en' => 'name_en',
            'name_ar' => 'name_ar',
            'code' => '1234567890',
        ];

        $this
            ->actingAs($user)
            ->putJson(route('entities.update', $entity->id), $data)
            ->assertStatus(403);
    });
});

describe('authorized users', function () {

    test('authorized users can see no content message with empty page', function () {
        $user = user();
        giveAdminPermissionsToUserOnEntity($user);
        $this
            ->actingAs($user)
            ->getJson(route('entities.index'))
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'message',
            ]);
    });

    test('authorized users can access entities page', function () {

        $user = user();
        $entity = entity($user);
        giveAdminPermissionsToUserOnEntity($user);
        $this
            ->actingAs($user)
            ->getJson(route('entities.index'))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name_en',
                        'name_ar',
                        'code',
                        'created_at',
                        'updated_at',
                        'created_by' => ['id', 'name', 'email'],
                        'updated_by' => ['id', 'name', 'email'],
                    ],
                ],
            ]);
    });

    test('authorized users can see a single entity page', function () {
        $user = user();
        $entity = entity($user);
        giveAdminPermissionsToUserOnEntity($user);
        $this
            ->actingAs($user)
            ->getJson(route('entities.show', $entity->id))
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

    test('authorized users cannot store entity with invalid data', function ($entity) {
        $user = user();
        giveAdminPermissionsToUserOnEntity($user);

        $this
            ->actingAs($user)
            ->postJson(route('entities.store'), [$entity])
            ->assertStatus(422);
    })->with('entities');

    test('authorized users can store entity with valid data', function () {
        $user = user();
        $entity = entity($user);
        giveAdminPermissionsToUserOnEntity($user);
        $this
            ->actingAs($user)
            ->postJson(route('entities.store'), $entity->toArray())
            ->assertStatus(201);
    });

    test('authorized users cannot update an entity with invalid data', function () {
        $user = user();
        $entity = entity($user);
        giveAdminPermissionsToUserOnEntity($user);
        $data = [
            'name_en' => '',
            'name_ar' => '',
            'code' => '',
        ];

        $this
            ->actingAs($user)
            ->putJson(route('entities.update', $entity->id), $data)
            ->assertStatus(422);
    });

    test('authorized users can update an entity', function () {
        $user = user();
        $entity = entity($user);
        giveAdminPermissionsToUserOnEntity($user);
        $data = [
            'name_en' => 'name_en',
            'name_ar' => 'name_ar',
            'code' => '1234567890',
        ];

        $this
            ->actingAs($user)
            ->putJson(route('entities.update', $entity->id), $data)
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

dataset('entities', [
    'all empty' => [
        [
            'name_en' => '',
            'name_ar' => '',
            'code' => '',
            'created_by' => '',
            'updated_by' => '',
        ],
    ],
    'names too long' => [
        [
            'name_en' => Str::length(51),
            'name_ar' => Str::length(51),
            'code' => Str::length(10),
            'created_by' => 100,
            'updated_by' => 100,
        ],
    ],
    'missing code' => [
        [
            'name_en' => Str::length(20),
            'name_ar' => Str::length(20),
            'code' => null,
            'created_by' => 100,
            'updated_by' => 100,
        ],
    ],
]);

function giveAdminPermissionsToUserOnEntity($user)
{
    $role = Role::create(['name' => 'admin']);

    Permission::create(['name' => 'view_any_entity']);
    Permission::create(['name' => 'view_entity']);
    Permission::create(['name' => 'create_entity']);
    Permission::create(['name' => 'update_entity']);
    Permission::create(['name' => 'delete_entity']);
    Permission::create(['name' => 'restore_entity']);
    Permission::create(['name' => 'force_delete_entity']);

    $user->assignRole($role->name);

    $role->givePermissionTo('view_any_entity');
    $role->givePermissionTo('view_entity');
    $role->givePermissionTo('create_entity');
    $role->givePermissionTo('update_entity');
    $role->givePermissionTo('delete_entity');
    $role->givePermissionTo('restore_entity');
    $role->givePermissionTo('force_delete_entity');
}
