<?php

namespace Database\Seeders;

use App\Models\Company;
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


        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        $roleSuperAdmin = Role::findByName('super_admin');
        $permissions = Permission::whereLike('name','%company%')->pluck('id','id')->all();
        $roleSuperAdmin->syncPermissions($permissions);

        $roleManager = Role::findByName('manager');
        $permissionsManager = Permission::where('name', 'not like', '%company%')->pluck('id','id')->all();
        $roleManager->syncPermissions($permissionsManager);

        $roleEmployee = Role::findByName('employee');
        $permissionsEmployee = Permission::whereIn('name',['employee-list','employee-show'])->pluck('id','id')->all();
        $roleEmployee->syncPermissions($permissionsEmployee);

        $user->assignRole('super_admin');

        $counterEmployee = 1; // Counter for employees
        $counterManager = 1;  // Counter for managers

        Company::factory(2)->create()->each(function ($company) use (&$counterEmployee, &$counterManager) {
            $employeeUsers = User::factory(rand(10, 20))->make();
            foreach ($employeeUsers as $user) {
                $user->email = 'employee' . $counterEmployee++ . '@example.com';
                $user->company_id = $company->id;
                $user->save();
                $user->assignRole('employee');
            }

            $managerUsers = User::factory(rand(10, 20))->make();
            foreach ($managerUsers as $user) {
                $user->email = 'manager' . $counterManager++ . '@example.com';
                $user->company_id = $company->id;
                $user->save();
                $user->assignRole('manager');
            }
        });

    }
}
