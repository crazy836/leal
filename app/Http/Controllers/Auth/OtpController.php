<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            return redirect()->route('register');
        }

        $user = User::find(session('pending_user_id'));
        
        if (!$user) {
            return redirect()->route('register');
        }

        // Generate and send OTP
        $emailSent = $this->generateAndSendOtp($user);

        return view('auth.otp', compact('emailSent'));
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

        // Store OTP in session with expiration (5 minutes)
        session([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        // Save OTP to database
        $user->otps()->create([
            'otp_code' => $otp,
            'expires_at' => now()->addMinutes(5),
            'is_used' => false
        ]);

        // Log the attempt to send OTP
        \Log::info("Attempting to send OTP to {$user->email}", [
            'user_id' => $user->id,
            'otp' => $otp,
            'mail_config' => [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
            ]
        ]);

        // Send OTP via email
        try {
            \Mail::to($user->email)->send(new \App\Mail\OtpVerification($otp, $user));
            \Log::info("OTP sent successfully to user {$user->email}: {$otp}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP to {$user->email}. Error: " . $e->getMessage() . ". Trace: " . $e->getTraceAsString());
            // Fallback to logging the OTP if email fails
            \Log::info("OTP for user {$user->email}: {$otp}");
            return false;
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