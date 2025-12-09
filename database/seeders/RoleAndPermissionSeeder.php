<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view orders', 'edit orders', 'delete orders',
            'view users', 'create users', 'edit users', 'delete users',
            'view reviews', 'approve reviews', 'delete reviews',
            'view reports', 'manage settings'
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName);
        }

        // Create roles
        $adminRole = Role::findOrCreate('admin');
        $managerRole = Role::findOrCreate('manager');
        $customerRole = Role::findOrCreate('customer');
        
        // Assign permissions to admin role (all permissions)
        $adminRole->syncPermissions(Permission::all());
        
        // Assign permissions to manager role
        $managerPermissions = [
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view orders', 'edit orders',
            'view users', 'edit users',
            'view reviews', 'approve reviews', 'delete reviews',
            'view reports'
        ];
        $managerRole->syncPermissions($managerPermissions);
        
        // Assign permissions to customer role
        $customerPermissions = [
            'view products'
        ];
        $customerRole->syncPermissions($customerPermissions);
    }
}