@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">User Details</h2>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}"
                    class="rounded-circle mb-3"
                    style="width: 150px; height: 150px; object-fit: cover;"
                    alt="{{ $user->first_name }}'s avatar"
                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white\' style=\'width: 150px; height: 150px; font-size: 3rem;\'>{{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}</div>'">
                @else
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                    style="width: 150px; height: 150px; font-size: 3rem;">
                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                </div>
                @endif
            </div>

            <p><strong>First Name:</strong> {{ $user->first_name }}</p>
            <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to User List</a>
        </div>
    </div>
</div>
@endsection