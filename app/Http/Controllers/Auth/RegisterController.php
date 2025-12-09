<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate reCAPTCHA
        $this->validateRecaptcha($request);

        $this->validator($request->all())->validate();

        // Create user but don't login yet
        $user = $this->create($request->all());

        // Store user ID in session for OTP verification
        session(['pending_user_id' => $user->id]);

        // Redirect to OTP verification page
        return redirect()->route('register.otp');
    }

    /**
     * Validate reCAPTCHA response
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateRecaptcha(Request $request)
    {
        // Skip reCAPTCHA validation if keys are not configured
        $recaptchaSiteKey = env('RECAPTCHA_SITE_KEY');
        $recaptchaSecretKey = env('RECAPTCHA_SECRET_KEY');
        
        // Skip validation in local environment or if keys are not set properly
        if (app()->environment('local') || !$recaptchaSiteKey || !$recaptchaSecretKey || 
            $recaptchaSiteKey === 'your_site_key_here' || $recaptchaSecretKey === 'your_secret_key_here') {
            return; // Skip reCAPTCHA validation
        }

        $recaptchaResponse = $request->input('g-recaptcha-response');

        if (!$recaptchaResponse) {
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'Please complete the reCAPTCHA verification.']);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $body = $response->json();

        if (!$body['success']) {
            return redirect()->back()->withErrors(['g-recaptcha-response' => '']);
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        // Automatically verify the user's email
        $user->markEmailAsVerified();
        
        return $user;
    }
}