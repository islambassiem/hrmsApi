<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CollegePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'admin')->first();

        Permission::create(['name' => 'view_any_college']);
        Permission::create(['name' => 'view_college']);
        Permission::create(['name' => 'create_college']);
        Permission::create(['name' => 'update_college']);
        Permission::create(['name' => 'delete_college']);
        Permission::create(['name' => 'restore_college']);
        Permission::create(['name' => 'force_delete_college']);

        $role->givePermissionTo('view_any_college');
        $role->givePermissionTo('view_college');
        $role->givePermissionTo('create_college');
        $role->givePermissionTo('update_college');
        $role->givePermissionTo('delete_college');
        $role->givePermissionTo('restore_college');
        $role->givePermissionTo('force_delete_college');
    }
}
