<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

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
            ]);
            return redirect('/login')->with('success', 'Registration successful! Please log in.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required:email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['user' => $user]);
            return redirect()->route('products.index')->with('success', 'Login successful!');

            // with route which is above, use named route, without use uri
            // return redirect('products');
        }
        // TODO: fix error when login fails, must show error on login
        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    public function logout()
    {
        session()->forget('user');
        return redirect()->route('auth.showLoginForm')->with('success', 'You have been logged out.');
    }
}
