<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_name' => 'required|email|unique:users',
            'password' => 'required|min:4|confirmed',
        ]);

        $usre = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    public function login(Request $request) {
        $request->validte([
            'email' => 'required:email',
            'passwrod' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['user' => $user]);
            return redirect('products.index');
        }
        // TODO: error whenl login fails
        // return back()->withErrors()(['email' => 'Invalid email or password.']);
    }

    public function logout() {
        session()->forget('user');
        return redirect('/login')->with('success', 'YOu have been logged out.');
    }
}
