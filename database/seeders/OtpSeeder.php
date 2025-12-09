<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Otp;
use App\Models\User;

class OtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one if none exists
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        // Create sample OTP records
        Otp::create([
            'user_id' => $user->id,
            'otp_code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'is_used' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Otp::create([
            'user_id' => $user->id,
            'otp_code' => '789012',
            'expires_at' => now()->subMinutes(10), // Expired
            'is_used' => false,
            'created_at' => now()->subMinutes(15),
            'updated_at' => now()->subMinutes(15)
        ]);

        Otp::create([
            'user_id' => $user->id,
            'otp_code' => '345678',
            'expires_at' => now()->addMinutes(5),
            'is_used' => true, // Already used
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(5)
        ]);
    }
}