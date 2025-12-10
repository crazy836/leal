<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\PhpMailerOtpVerification;
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
            // Try PHPMailer first
            $this->info("Trying to send test email using PHPMailer...");
            $mailer = new PhpMailerOtpVerification($otp, $user);
            $result = $mailer->send();
            
            if ($result) {
                $this->info("Test email sent successfully to {$email} using PHPMailer");
            } else {
                // Fallback to Laravel Mail
                $this->info("PHPMailer failed, trying Laravel Mail as fallback...");
                Mail::to($email)->send(new \App\Mail\OtpVerification($otp, $user));
                $this->info("Test email sent successfully to {$email} using Laravel Mail");
            }
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}