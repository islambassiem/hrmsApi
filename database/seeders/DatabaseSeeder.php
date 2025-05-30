<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EntitySeeder::class,
            BranchSeeder::class,
            CollegeSeeder::class,
            DepartmentSeeder::class,
        ]);

        $user = User::where('name', 'admin')->first();
        $role = Role::create(['name' => 'admin']);
        $user->assignRole($role->name);

        $this->call([
            EntityPermissionSeeder::class,
            BranchPermissionSeeder::class,
            CollegePermissionSeeder::class,
            DepartmentPermissionsSeeder::class,
        ]);
    }
}
