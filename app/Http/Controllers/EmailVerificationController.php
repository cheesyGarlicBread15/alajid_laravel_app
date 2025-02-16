<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid verification token.');
        }

        $user->is_verified = true;
        $user->verification_token = null;
        $user->save();

        return redirect('/login')->with('success', 'Email verified successfully. You can now log in.');
    }
}
