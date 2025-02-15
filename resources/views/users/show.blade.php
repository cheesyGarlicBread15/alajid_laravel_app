@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">User Details</h2>
        </div>
        <div class="card-body">
            <p><strong>First Name:</strong> {{ $user->first_name }}</p>
            <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to User List</a>
            @if(Auth::check() && Auth::user()->role === 'Admin')
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>

            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete User</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection