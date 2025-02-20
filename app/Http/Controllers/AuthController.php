<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Mail\TwoFactorCodeMail;
use App\Mail\VerifyEmail as MailVerifyEmail;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.showLoginForm');
    }

    public function showRegisterForm()
    {
        return view('auth.showRegisterForm');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:4|confirmed',
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'verification_token' => Str::random(64),
            ]);
            // dd($user->verification_token);
            Mail::to($user->email)->send(new MailVerifyEmail($user));
            LogHelper::createLog('Register', $user->first_name . ' ' . $user->first_name . ' registered successfully', $user->id);
            return redirect('/login')->with('success', 'A verification link has been sent to your email.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // manual?
            // session(['user' => $user]);
            Auth::login(user: $user);
            return $this->authenticated($request, $user);
        }
        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->is_verified) {
            Auth::logout();
            return redirect()->route(route: 'auth.showLoginForm')->with('error', 'Please verify your email before logging in.');
        }

        if ($user->two_factor_code === null) {
            $user->two_factor_code = rand(100000, 999999);
            $user->two_factor_expires_at = now()->addMinutes(1);
            $user->save();

            // TODO: switch to smtp gmail instead of mailtrap for actual and real emails
            Mail::to($user->email)->send(new TwoFactorCodeMail($user));
        }
        // Auth::logout();
        return redirect()->route('2fa.verify.form')->with('message', 'A 2FA code has been sent to your email.');
    }


    public function logout()
    {
        // TODO: implement additoinal logging for: edit, delete, register, and create
        LogHelper::createLog('Logout', 'User has logged out');
        // session()->forget('user');
        Auth::logout();
        return redirect()->route('auth.showLoginForm')->with('success', 'You have been logged out.');
    }
}
