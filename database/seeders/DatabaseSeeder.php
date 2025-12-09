<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the seeders in order
        $this->call([
            CategorySeeder::class,
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            NikeProductImagesSeeder::class,
            WishlistSeeder::class,
        ]);
    }
}