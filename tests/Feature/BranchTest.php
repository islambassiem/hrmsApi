<?php

use App\Models\Branch;
use App\Models\Entity;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->url = 'api/v1/branches';
});

test('guest cannot access branches page', function () {
    $this->getJson($this->url)->assertStatus(401);
});

test('guest cannot access single branch page', function () {
    $entity = Entity::factory()->create();
    $branch = Branch::factory()->create([
        'entity_id' => $entity->id,
    ]);
    $this
        ->getJson($this->url.'/'.$branch->id)
        ->assertStatus(401);
});

test('authenticated users cannot access branches page', function () {
    $this
        ->actingAs($this->user)
        ->getJson($this->url)
        ->assertStatus(403);
});

test('unauthenticated users cannot access single branch page', function () {
    $entity = Entity::factory()->create();
    $branch = Branch::factory()->create([
        'entity_id' => $entity->id,
    ]);
    $this
        ->actingAs($this->user)
        ->getJson($this->url.'/'.$branch->id)
        ->assertStatus(403);
});

test('authorized users can access single branch page', function () {
    $entity = Entity::factory()->create();
    $branch = Branch::factory()->create([
        'entity_id' => $entity->id,
    ]);
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($this->user)
        ->getJson($this->url.'/'.$branch->id)
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

test('authorized users can access branches page', function () {
    $branches = Branch::factory()->create([
        'entity_id' => Entity::factory()->create()->id,
    ]);
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($this->user)
        ->getJson($this->url)
        ->assertStatus(200)
        ->assertJsonFragment([
            'name_en' => $branches->name_en,
            'name_ar' => $branches->name_ar,
        ]);
});

test('authorized users can see no content branches page...', function () {
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($this->user)
        ->getJson($this->url)
        ->assertStatus(200)
        ->assertExactJsonStructure([
            'message',
        ]);
});

test('guest cannot create branch', function () {
    $branch = Branch::factory()->create([
        'entity_id' => Entity::factory()->create()->id,
    ]);

    $this
        ->postJson($this->url, [])
        ->assertStatus(401);
});

test('guest cannot create branch with valid data', function () {
    $barnch = Branch::factory()->make([
        'entity_id' => Entity::factory()->create()->id,
    ])->toArray();
    $this
        ->postJson($this->url, $barnch)
        ->assertStatus(401);
});

test('authenticated users can create branch', function () {
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);
    $branch = Branch::factory()->make([
        'entity_id' => Entity::factory()->create()->id,
        'created_by' => $this->user->id,
        'updated_by' => $this->user->id,
    ])->toArray();

    $this
        ->actingAs($this->user)
        ->postJson($this->url, $branch)
        ->assertStatus(201);
});

test('guest users cannot update branch', function () {
    $entity_id = Entity::factory()->create()->id;
    $branch = Branch::factory()->create([
        'entity_id' => $entity_id,
    ]);
    $this
        ->putJson($this->url.'/'.$branch->id, [])
        ->assertStatus(401);
});

test('guest users cannot update branch with valid data', function () {
    $branch = Branch::factory()->create([
        'entity_id' => Entity::factory()->create()->id,
    ]);
    $data = Branch::factory()->make()->toArray();
    $this
        ->putJson($this->url.'/'.$branch->id, $data)
        ->assertStatus(401);
});

test('unauthorized users cannot update branch', function () {
    $branch = Branch::factory()->create([
        'entity_id' => Entity::factory()->create()->id,]);
    $data = Branch::factory()->make([])->toArray();
    $this
        ->actingAs($this->user)
        ->putJson($this->url.'/'.$branch->id, $data)
        ->assertStatus(403);
});

test('authorized users can update branch', function () {
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);
    $branch = Branch::factory()->create([
        'entity_id' => Entity::factory()->create()->id,
    ]);

    $data = [
        'name_en' => $branch->name_en,
        'name_ar' => $branch->name_ar,
        'code' => $branch->code,
    ];

    $this
        ->actingAs($this->user)
        ->putJson($this->url.'/'.$branch->id, $data)
        ->assertStatus(200)
        ->assertExactJsonStructure([
            'data' => [
                'id',
                'name_en',
                'name_ar',
                'code',
                // 'entity' => [
                //     'id',
                //     'name_en',
                //     'name_ar',
                //     'code',
                //     'created_by' => ['id', 'name', 'email'],
                //     'updated_by' => ['id', 'name', 'email'],
                //     'created_at',
                //     'updated_at',
                // ],
                'created_by' => ['id', 'name', 'email'],
                'updated_by' => ['id', 'name', 'email'],
                'created_at',
                'updated_at',
            ],
        ]);
});
