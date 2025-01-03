<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'company-list',
            'company-create',
            'company-show',
            'company-edit',
            'company-delete',
            'employee-list',
            'employee-create',
            'employee-show',
            'employee-edit',
            'employee-delete',
            'manager-list',
            'manager-create',
            'manager-show',
            'manager-edit',
            'manager-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
