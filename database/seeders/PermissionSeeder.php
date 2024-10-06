<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view dashboard',

            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'show categories',

            'view products',
            'create products',
            'edit products',
            'delete products',
            'show products',

            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'show orders',
            'approve orders',

            'view merchants',
            'create merchants',
            'edit merchants',
            'delete merchants',
            'show merchants',
            'approve merchants',

            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'show roles',

            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'show permissions',

            'view users',
            'create users',
            'edit users',
            'delete users',
            'show users',
        ];

        foreach ($permissions as $value) {
            Permission::updateOrCreate(
                ['name' => $value],
            );
        }
    }
}
