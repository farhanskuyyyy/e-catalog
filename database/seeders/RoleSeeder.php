<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Fundraiser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadminRole = Role::updateOrCreate([
            'name' => 'superadmin'
        ]);
        $allPermissions = Permission::get()->pluck('name');
        $superadminRole->syncPermissions($allPermissions);

        $merchantRole = Role::updateOrCreate([
            'name' => 'merchant'
        ]);
        $merchantRole->syncPermissions([
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
        ]);

        $userRole = Role::updateOrCreate([
            'name' => 'user'
        ]);
        $userRole->syncPermissions([
            'view dashboard',

            'view merchants',
            'create merchants',
        ]);

        $superadmin = User::updateOrCreate([
            'email' => 'superadmin@ecatalog.com',
        ], [
            'name' => 'superadmin',
            'avatar' => 'images/default-images.png',
            'email' => 'superadmin@ecatalog.com',
            'password' => Hash::make('password')
        ]);
        $superadmin->assignRole($superadminRole);

        $merchant = User::updateOrCreate([
            'email' => 'merchant@ecatalog.com',
        ], [
            'name' => 'merchant',
            'avatar' => 'images/default-images.png',
            'email' => 'merchant@ecatalog.com',
            'password' => Hash::make('password')
        ]);

        $merchantCreate = Merchant::updateOrCreate([
            'user_id' => $merchant->id,
        ], [
            'user_id' => $merchant->id,
            'is_active' => true
        ]);
        $merchant->assignRole($merchantRole);

        $user = User::updateOrCreate([
            'email' => 'user@ecatalog.com',
        ], [
            'name' => 'user',
            'avatar' => 'images/default-images.png',
            'email' => 'user@ecatalog.com',
            'password' => Hash::make('password')
        ]);
        $user->assignRole($userRole);
    }
}
