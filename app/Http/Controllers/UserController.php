<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Log;
use App\Models\User;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $users = User::when($search, function ($query, $search) {
            return $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })
            ->paginate(10);

        return view('users.usersList', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $isProfile = $request->input('isProfile', false);

            // Base validation rules
            $validationRules = [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];

            // Add password validation rules based on context
            if ($isProfile) {
                $validationRules['oldPassword'] = 'nullable|required_with:newPassword';
                $validationRules['newPassword'] = 'nullable|min:4|confirmed';
            } else {
                $this->checkAdmin();
                $validationRules['role'] = 'required|in:Admin,User';
                $validationRules['newPassword'] = 'nullable|min:4|confirmed';
            }

            $validated = $request->validate($validationRules);

            // Check old password if changing password in profile mode
            if ($isProfile && !empty($validated['newPassword'])) {
                if (!Hash::check($validated['oldPassword'], $user->password)) {
                    return redirect()->back()
                        ->withErrors(['oldPassword' => 'The current password is incorrect.'])
                        ->withInput();
                }
            }

            // Update user data
            $user->first_name = $validated['firstName'];
            $user->last_name = $validated['lastName'];
            $user->email = $validated['email'];

            // Update role if admin is editing
            if (!$isProfile) {
                $user->role = $validated['role'];
            }

            // Update password if provided
            if (!empty($validated['newPassword'])) {
                $user->password = bcrypt($validated['newPassword']);
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar) {
                    FacadesStorage::disk('public')->delete($user->avatar);
                }

                $avatar = $request->file('avatar');
                $filename = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
                $path = $avatar->storeAs('avatars', $filename, 'public');
                $user->avatar = $path;
            }

            $user->save();

            // Log the update and redirect
            if ($isProfile) {
                LogHelper::createLog($request, 'Update', 'Updated their account');
                return redirect()->route('users.profile')
                    ->with('success', 'Profile updated successfully.');
            } else {
                LogHelper::createLog($request, 'Update', 'Updated account ' . $user->first_name . ' ' . $user->last_name);
                return redirect()->route('users.index')
                    ->with('success', 'User updated successfully.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        $user->delete();
        LogHelper::createLog($request, 'Delete', 'Deleted account ' . $user->first_name . ' ' . $user->last_name);
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function checkAdmin()
    {
        // }
        // if (session('user')->role != 'Admin') {
        //     abort(403);

        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    public function logs(Request $request)
    {
        $logs = Log::orderBy('created_at', 'desc')->paginate(20);
        return view('users.logs', compact('logs'));
    }

    public function profile()
    {
        return view('users.profile');
    }
}
