<?php

use App\Models\Entity;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('unauthenticated users cannot access entities page', function () {
    $this
        ->getJson('/api/v1/entities')
        ->assertStatus(401);
});

test('unauthenticated users cannot access sigle entity page', function () {
    $entity = Entity::factory()->create();
    $this
        ->getJson('api/v1/entities/'.$entity->id)
        ->assertStatus(401);
});

test('unauthorized users cannot access entities page', function () {
    $entity = Entity::factory()->create();
    $this
        ->actingAs($this->user)
        ->getJson('api/v1/entities/'.$entity->id)
        ->assertStatus(403);
});

test('unauthorized users cannot access sigle entity page', function () {
    $this
        ->actingAs($this->user)
        ->getJson('/api/v1/entities')
        ->assertStatus(403);
});

test('authenticated and authorized users can see a single entity page', function (){
    $entity = Entity::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($this->user)
        ->getJson('api/v1/entities/'.$entity->id)
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
            ]
        ]);
});

test('authenticated and authorized users can see no content message with empty page', function () {
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($this->user)
        ->getJson('/api/v1/entities')
        ->assertStatus(200)
        ->assertJson([
            'message' => 'There is no content',
        ]);
});

test('authenticated and authorized users can access entities page', function () {
    Entity::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($this->user)
        ->getJson('/api/v1/entities')
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
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
                'links' => [
                    '*' => ['url', 'label', 'active'],
                ],
            ],
        ]);
});

test('anauthenticated and authorized users cannot store entity', function (){
    $entity = Entity::factory()->make()->toArray();
    $this
        ->postJson('api/v1/entities', $entity)
        ->assertStatus(401);
    ;
});

test('authorized users cannot store entities', function (){
    $entity = Entity::factory()->make()->toArray();
    $user = User::factory()->create();
    $this
        ->actingAs($user)
        ->postJson('api/v1/entities', $entity)
        ->assertStatus(403);
    ;
});

test('authorized users cannot store entity with invalid data', function (){
    $entity = [
        'name_en' => '',
        'name_ar' => '',
        'code' => '',
        'created_by' => '',
        'updated_by' => '',
    ];
    $user = User::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($user)
        ->postJson('api/v1/entities', $entity)
        ->assertStatus(422);
    ;
});

test('authorized users can store entity with valid data', function (){
    $entity = Entity::factory()->create([
        'created_by' => $this->user->id,
        'updated_by' => $this->user->id,
    ])->toArray();
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $this
        ->actingAs($this->user)
        ->postJson('api/v1/entities', $entity)
        ->assertStatus(201);
});

test('unauthenticated and unauthorized users cannot update an entity', function (){
    $entity = Entity::factory()->create();
    $this
        ->putJson('api/v1/entities/'.$entity->id)
        ->assertStatus(401);
});

test('unauthorized users cannot update an entity', function (){
    $entity = Entity::factory()->create();

    $data = [
        'name_en' => 'name_en',
        'name_ar' => 'name_ar',
        'code' => '1234567890',
    ];

    $this
        ->actingAs($this->user)
        ->putJson('api/v1/entities/'.$entity->id, $data)
        ->assertStatus(403);
});


test('authorized users cannot update an entity with invalid data', function (){
    $entity = Entity::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);
    $data = [
        'name_en' => '',
        'name_ar' => '',
        'code' => '',
    ];

    $this
        ->actingAs($this->user)
        ->putJson('api/v1/entities/'.$entity->id, $data)
        ->assertStatus(422);
});


test('authorized users can update an entity', function (){
    $entity = Entity::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $this->user->assignRole($role->name);

    $data = [
        'name_en' => 'name_en',
        'name_ar' => 'name_ar',
        'code' => '1234567890',
    ];

    $this
        ->actingAs($this->user)
        ->putJson('api/v1/entities/'.$entity->id, $data)
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
            ]
        ]);
});
