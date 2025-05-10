<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DepartmentPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'admin')->first();

        Permission::create(['name' => 'view_any_department']);
        Permission::create(['name' => 'view_department']);
        Permission::create(['name' => 'create_department']);
        Permission::create(['name' => 'update_department']);
        Permission::create(['name' => 'delete_department']);
        Permission::create(['name' => 'restore_department']);
        Permission::create(['name' => 'force_delete_department']);

        $role->givePermissionTo('view_any_department');
        $role->givePermissionTo('view_department');
        $role->givePermissionTo('create_department');
        $role->givePermissionTo('update_department');
        $role->givePermissionTo('delete_department');
        $role->givePermissionTo('restore_department');
        $role->givePermissionTo('force_delete_department');
    }
}
