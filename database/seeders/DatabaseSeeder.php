<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        $counter = 1;
        $employee = User::factory(120)->create()->each(function ($user) use (&$counter) {
            $user->update(['email' => 'employee' . $counter++ . '@example.com']);
        });

        $manager = User::factory(50)->create()->each(function ($user) use (&$counter) {
            $user->update(['email' => 'manager' . $counter++ . '@example.com']);
        });


        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        $roleSuperAdmin = Role::findByName('super admin');
        $permissions = Permission::pluck('id','id')->all();
        $roleSuperAdmin->syncPermissions($permissions);

        $roleManager = Role::findByName('manager');
        $permissionsManager = Permission::where('name', 'not like', '%company%')->pluck('id','id')->all();
        $roleManager->syncPermissions($permissionsManager);

        $roleEmployee = Role::findByName('employee');
        $permissionsEmployee = Permission::whereIn('name',['employee-list','employee-show'])->pluck('id','id')->all();
        $roleEmployee->syncPermissions($permissionsEmployee);

        $user->assignRole('super admin');

        $employee->each(function ($employee) {
            $employee->assignRole('employee');
        });

        $manager->each(function ($manager) {
            $manager->assignRole('manager');
        });
    }
}
