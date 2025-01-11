<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view stores',
            'manage stores',
            'view transactions',
            'create transactions',
            'view inventory',
            'manage inventory',
            'view reports',
            'manage users',
            'print reports',
            'view products',
            'manage products'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $owner = Role::create(['name' => 'owner']);
        $owner->givePermissionTo(Permission::all());

        $manager = Role::create(['name' => 'store_manager']);
        $manager->givePermissionTo([
            'view stores',
            'view transactions',
            'view inventory',
            'manage inventory',
            'view reports',
            'print reports',
            'view products',
            'manage products'
        ]);

        $supervisor = Role::create(['name' => 'supervisor']);
        $supervisor->givePermissionTo([
            'view transactions',
            'view inventory',
            'view reports',
            'view products'
        ]);

        $cashier = Role::create(['name' => 'cashier']);
        $cashier->givePermissionTo([
            'view transactions',
            'create transactions',
            'view products'
        ]);

        $warehouse = Role::create(['name' => 'warehouse']);
        $warehouse->givePermissionTo([
            'view inventory',
            'manage inventory',
            'view products',
            'manage products'
        ]);
    }
}