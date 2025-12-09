<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Otp;

class OtpController extends Controller
{
    /**
     * Display a listing of OTP records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $otps = Otp::with('user')->latest()->paginate(20);
        
        return view('admin.otps.index', compact('otps'));
    }
}