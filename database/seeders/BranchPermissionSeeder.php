<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BranchPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role = Role::where('name', 'admin')->first(); 

        Permission::create(['name' => 'view_any_branch']);
        Permission::create(['name' => 'view_branch']);
        Permission::create(['name' => 'create_branch']);
        Permission::create(['name' => 'update_branch']);
        Permission::create(['name' => 'delete_branch']);
        Permission::create(['name' => 'restore_branch']);
        Permission::create(['name' => 'force_delete_branch']);

        $role->givePermissionTo('view_any_branch');
        $role->givePermissionTo('view_branch');
        $role->givePermissionTo('create_branch');
        $role->givePermissionTo('update_branch');
        $role->givePermissionTo('delete_branch');
        $role->givePermissionTo('restore_branch');
        $role->givePermissionTo('force_delete_branch');
    }
}
