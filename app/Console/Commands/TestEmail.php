<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\OtpVerification;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test OTP email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Create a mock user object
        $user = (object) [
            'name' => 'Test User',
            'email' => $email
        ];
        
        $otp = 123456;
        
        try {
            Mail::to($email)->send(new OtpVerification($otp, $user));
            $this->info("Test email sent successfully to {$email}");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
        }
        
        return 0;
    }
}