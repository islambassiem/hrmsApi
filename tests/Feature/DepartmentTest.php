<?php

use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

describe('Authentication', function () {

    test('guests cannot access departments page', function () {
        $response = $this->getJson(route('departments.index'));

        $response->assertStatus(401);
    });

    test('guest cannot show single department page', function () {
        $department = department();

        $this->getJson(route('departments.show', $department->id))
            ->assertStatus(401);
    });

    test('guest cannot store a new department', function () {
        $department = department();

        $this->postJson(route('departments.store'), $department->toArray())
            ->assertStatus(401);
    });

    test('guest cannot update a department', function () {
        $department = department();

        $this->putJson(route('departments.update', $department->id), $department->toArray())
            ->assertStatus(401);
    });
});

describe('Authorization', function () {

    test('authenticated users cannot access departments page', function () {
        $user = user();
        $department = department($user);
        $this
            ->actingAs($user)
            ->getJson(route('departments.index'))
            ->assertStatus(403);
    });

    test('authenticated users cannot access single department page', function () {
        $user = user();
        $department = department($user);
        $this
            ->actingAs($user)
            ->getJson(route('departments.show', $department->id))
            ->assertStatus(403);
    });

    test('authenticated users cannot store a new department', function () {
        $user = user();
        $department = department($user);
        $this
            ->actingAs($user)
            ->postJson(route('departments.store'), $department->toArray())
            ->assertStatus(403);
    });

    test('authenticated users cannot update a department', function () {
        $user = user();
        $department = department($user);
        $this
            ->actingAs($user)
            ->putJson(route('departments.update', $department->id), $department->toArray())
            ->assertStatus(403);
    });
});

describe('Authorized users', function () {

    test('authorized users can access departments page', function () {
        $user = user();
        $department = department($user);
        giveAdminPermissionsToUserOnDepartment($user);
        $this
            ->actingAs($user)
            ->getJson(route('departments.index'))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('status', 'success')
                    ->where('data.0.id', $department->id)
                    ->where('data.0.name_en', $department->name_en)
                    ->where('data.0.name_ar', $department->name_ar)
                    ->where('data.0.code', $department->code)
                    ->where('data.0.created_by', ['id' => $user->id, 'name' => $user->name, 'email' => $user->email])
                    ->where('data.0.updated_by', ['id' => $user->id, 'name' => $user->name, 'email' => $user->email])
            );
    });

    test('authorized users can see no content message when there is no departments', function () {
        $user = user();
        giveAdminPermissionsToUserOnDepartment($user);
        $this
            ->actingAs($user)
            ->getJson(route('departments.index'))
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'success',
                'message' => 'No content',
            ]);
    });

    test('authorized users can see a single department page', function () {
        $user = user();
        giveAdminPermissionsToUserOnDepartment($user);
        $department = department($user);
        $this
            ->actingAs($user)
            ->getJson(route('departments.show', $department->id))
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'status',
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

    test('authorized users can store a department', function () {
        $department = [
            'name_en' => 'Department Name in English',
            'name_ar' => 'Department Name in Arabic',
            'code' => 'dept',
        ];

        $user = user();
        giveAdminPermissionsToUserOnDepartment($user);
        $this
            ->actingAs($user)
            ->postJson(route('departments.store', $department))
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('status', 'success')
                    ->where('data.name_en', $department['name_en'])
                    ->where('data.name_ar', $department['name_ar'])
                    ->where('data.code', $department['code'])
                    ->where('data.created_at', date('Y-m-d'))
                    ->where('data.created_by', ['id' => $user->id, 'name' => $user->name, 'email' => $user->email])
                    ->where('data.updated_by', null)
            );
    });

    test('authorized users can update a department', function () {

        $user = user();
        $department = department($user);
        giveAdminPermissionsToUserOnDepartment($user);
        $this
            ->actingAs($user)
            ->putJson(route('departments.update', $department->id), [
                'name_en' => 'Updated Department Name in English',
                'name_ar' => 'Updated Department Name in Arabic',
                'code' => 'dept2',
            ])
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('status', 'success')
                    ->where('data.name_en', 'Updated Department Name in English')
                    ->where('data.name_ar', 'Updated Department Name in Arabic')
                    ->where('data.code', 'dept2')
                    ->whereType('data.created_by', 'array')
                    ->where('data.updated_by', ['id' => $user->id, 'name' => $user->name,'email' => $user->email])
        );
    });

    test('authorized users fail storing a department with invalid data', function ($departments) {
        $user = user();
        giveAdminPermissionsToUserOnDepartment($user);
        $this
            ->actingAs($user)
            ->postJson(route('departments.store'), [$departments])
            ->assertStatus(422);
    })->with(['departments']);

    test('authorized users fail updating a department with invalid data', function ($departments) {
        $user = user();
        $department = department($user);
        giveAdminPermissionsToUserOnDepartment($user);
        $this
            ->actingAs($user)
            ->putJson(route('departments.update', $department->id), [$departments])
            ->assertStatus(422);
    })->with(['departments']);
});

dataset('departments', [
    'missing en name' => [
        'name_en' => null,
        'name_ar' => 'Department Name in Arabic',
        'code' => 'dept',
    ],
    'missing ar name' => [
        'name_en' => 'Department Name in English',
        'name_ar' => null,
        'code' => 'dept',
    ],
    'missing code' => [
        'name_en' => 'Department Name in English',
        'name_ar' => 'Department Name in Arabic',
        'code' => null,
    ],
    'too long name' => [
        'name_en' => Str::random(51),
        'name_ar' => Str::random(51),
        'code' => Str::random(11),
    ],
]);

function giveAdminPermissionsToUserOnDepartment($user)
{
    $role = Role::create(['name' => 'admin']);

    Permission::create(['name' => 'view_any_department']);
    Permission::create(['name' => 'view_department']);
    Permission::create(['name' => 'create_department']);
    Permission::create(['name' => 'update_department']);
    Permission::create(['name' => 'delete_department']);
    Permission::create(['name' => 'restore_department']);
    Permission::create(['name' => 'force_delete_department']);

    $user->assignRole($role->name);

    $role->givePermissionTo('view_any_department');
    $role->givePermissionTo('view_department');
    $role->givePermissionTo('create_department');
    $role->givePermissionTo('update_department');
    $role->givePermissionTo('delete_department');
    $role->givePermissionTo('restore_department');
    $role->givePermissionTo('force_delete_department');
}
