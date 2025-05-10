<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EntityPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'admin')->first(); 

        Permission::create(['name' => 'view_any_entity']);
        Permission::create(['name' => 'view_entity']);
        Permission::create(['name' => 'create_entity']);
        Permission::create(['name' => 'update_entity']);
        Permission::create(['name' => 'delete_entity']);
        Permission::create(['name' => 'restore_entity']);
        Permission::create(['name' => 'force_delete_entity']);

        $role->givePermissionTo('view_any_entity');
        $role->givePermissionTo('view_entity');
        $role->givePermissionTo('create_entity');
        $role->givePermissionTo('update_entity');
        $role->givePermissionTo('delete_entity');
        $role->givePermissionTo('restore_entity');
        $role->givePermissionTo('force_delete_entity');
    }
}
