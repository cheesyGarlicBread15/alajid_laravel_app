<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
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
            if (!($this->authenticated($request, $user))) {
                return redirect()->route(route: 'auth.showLoginForm')->with('error', 'Please verify your email before logging in.');
            }

            Auth::login($user);

            // update last_login
            $user->last_login = now();
            $user->save();

            LogHelper::createLog('Login', 'User has login');

            return redirect()->route('products.index')->with('success', 'Login successful!');

            // with route which is above, use named route, without use uri
            // return redirect('products');
        }
        // TODO: fix error when login fails, must show error on login
        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    protected function authenticated(Request $request, $user) {
        if (!$user->is_verified) {
            Auth::logout();
            return false;
        }
        return true;
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
