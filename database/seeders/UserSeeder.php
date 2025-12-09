<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'phone' => '1234567890',
        ]);
        
        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        $admin->assignRole($adminRole);
        
        // Create manager user
        $manager = User::firstOrCreate([
            'email' => 'manager@example.com',
        ], [
            'name' => 'Manager User',
            'password' => bcrypt('password'),
            'phone' => '0987654321',
        ]);
        
        // Assign manager role
        $managerRole = Role::where('name', 'manager')->first();
        $manager->assignRole($managerRole);
        
        // Create customer user
        $customer = User::firstOrCreate([
            'email' => 'customer@example.com',
        ], [
            'name' => 'Customer User',
            'password' => bcrypt('password'),
            'phone' => '1122334455',
        ]);
        
        // Assign customer role
        $customerRole = Role::where('name', 'customer')->first();
        $customer->assignRole($customerRole);
    }
}