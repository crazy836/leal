<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PhpMailerOtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    /**
     * Show the OTP verification form.
     *
     * @return \Illuminate\View\View
     */
    public function showOtpForm()
    {
        // Check if there's a pending user ID in session
        if (!session('pending_user_id')) {
            \Log::warning("No pending_user_id in session, redirecting to register");
            return redirect()->route('register');
        }

        $user = User::find(session('pending_user_id'));
        
        if (!$user) {
            \Log::warning("User not found for pending_user_id: " . session('pending_user_id') . ", redirecting to register");
            return redirect()->route('register');
        }

        \Log::info("Generating and sending OTP for user: " . $user->email . " (ID: " . $user->id . ")");

        // Generate and send OTP
        $this->generateAndSendOtp($user);

        // Don't pass emailSent variable anymore, just show the form
        return view('auth.otp');
    }

    /**
     * Verify the OTP code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        // Check if there's a pending user ID in session
        if (!session('pending_user_id')) {
            return redirect()->route('register')->withErrors(['otp' => 'Invalid session. Please register again.']);
        }

        $user = User::find(session('pending_user_id'));
        
        if (!$user) {
            return redirect()->route('register')->withErrors(['otp' => 'Invalid session. Please register again.']);
        }

        // Check if OTP is valid
        if (!$this->isValidOtp($user, $request->otp)) {
            return redirect()->back()->withErrors(['otp' => 'Invalid OTP code. Please try again.']);
        }

        // Mark OTP as used in database
        $user->otps()
            ->where('otp_code', $request->otp)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Clear OTP from session
        session()->forget(['pending_user_id', 'otp_code', 'otp_expires_at']);

        // Login the user
        auth()->login($user);

        return redirect()->intended('/');
    }

    /**
     * Resend the OTP code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendOtp(Request $request)
    {
        // Check if there's a pending user ID in session
        if (!session('pending_user_id')) {
            return redirect()->route('register');
        }

        $user = User::find(session('pending_user_id'));
        
        if (!$user) {
            return redirect()->route('register');
        }

        // Mark all previous OTPs as used/invalid
        $user->otps()
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Generate and send new OTP
        $this->generateAndSendOtp($user);

        return redirect()->back()->with('success', 'OTP has been resent to your email.');
    }

    /**
     * Generate and send OTP to user's email.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function generateAndSendOtp(User $user)
    {
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        \Log::info("Generated OTP: " . $otp . " for user: " . $user->email);

        // Store OTP in session with expiration (5 minutes)
        session([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        // Try to save OTP to database (continue even if this fails)
        try {
            $user->otps()->create([
                'otp_code' => $otp,
                'expires_at' => now()->addMinutes(5),
                'is_used' => false
            ]);
            \Log::info("Stored OTP in database for user: " . $user->email);
        } catch (\Exception $e) {
            \Log::warning("Failed to store OTP in database for user: " . $user->email . ". Error: " . $e->getMessage());
            // Continue with email sending even if database save fails
        }

        \Log::info("Stored OTP in session for user: " . $user->email);

        // Log the attempt to send OTP
        \Log::info("Attempting to send OTP to {$user->email}", [
            'user_id' => $user->id,
            'otp' => $otp,
        ]);

        // Try to send OTP using PHPMailer first, fallback to Laravel Mail if needed
        try {
            \Log::info("Initializing PHPMailer for user: " . $user->email);
            $mailer = new PhpMailerOtpVerification($otp, $user);
            $result = $mailer->send();
            
            if ($result) {
                \Log::info("OTP sent successfully to user {$user->email}: {$otp} using PHPMailer");
            } else {
                \Log::warning("PHPMailer failed to send OTP to {$user->email}, falling back to Laravel Mail");
                // Fallback to Laravel Mail
                \Mail::to($user->email)->send(new \App\Mail\OtpVerification($otp, $user));
                \Log::info("OTP sent successfully to user {$user->email}: {$otp} using Laravel Mail (fallback)");
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP for {$user->email} using PHPMailer. Error: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
            // Log the OTP if all sending methods fail
            \Log::info("OTP for user {$user->email}: {$otp}");
        }
    }

    /**
     * Check if the provided OTP is valid.
     *
     * @param  \App\Models\User  $user
     * @param  string  $otp
     * @return bool
     */
    protected function isValidOtp(User $user, $otp)
    {
        // Check if OTP exists in session
        if (!session('otp_code') || !session('otp_expires_at')) {
            return false;
        }

        // Check if OTP is expired
        if (now()->greaterThan(session('otp_expires_at'))) {
            return false;
        }

        // Check if OTP matches in session
        if ((string) session('otp_code') !== (string) $otp) {
            return false;
        }

        // Also check in database
        $dbOtp = $user->otps()
            ->where('otp_code', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        return $dbOtp !== null;
    }
}