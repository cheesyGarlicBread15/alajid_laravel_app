<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            // Validate the incoming request data
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|in:Admin,User', // Ensure valid role selection
            ]);
    
            // Create a new user with validated data
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'], // Role from input
            ]);
            
            // Redirect to login page with success message
            return redirect()->route('auth.showLoginForm')->with('success', 'Registration successful! Please login.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, this block is triggered
            // Log the error or handle it differently if necessary
            
            // Redirect back to the register form with error messages
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
    


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            session(['user' => $user]);
            return redirect()->route('products.index');
        }
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/login')->with('success', 'You have been logged out.');
    }
}
