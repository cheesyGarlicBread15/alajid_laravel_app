<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthController extends Controller
{
    public function verifyForm()
    {
        return view('auth.two-factor');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ]);

        $user = Auth::user();

        // typecast to User since the above var is Authenticatable
        $user = $user instanceof User ? $user : null;

        if ($request->input('two_factor_code') == $user->two_factor_code && now()->lt($user->two_factor_expires_at)) {
            $user->update(['two_factor_code' => null, 'two_factor_expires_at' => null]);

            // originally from AuthController login
            $user->last_login = now();
            $user->save();
            LogHelper::createLog($request, 'Login', 'Logged in successfully');
            return redirect()->route('products.index')->with('success', 'Login successful!');
        }
        
        LogHelper::createLog($request, 'Login', 'Invalid OTP');
        return back()->withErrors(['two_factor_code' => 'Invalid or expired OTP.']);
    }
}
