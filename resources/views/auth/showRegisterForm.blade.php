@extends('layouts.app_no_nav')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left Column - Image -->
                        <div class="col-md-6 d-none d-md-block p-0" style="min-height: 100%;">
                            <img src="{{ asset('storage/images/register.png') }}"
                                alt="Register Illustration"
                                class="w-100 h-100"
                                style="object-fit: cover; border-top-left-radius: 0.375rem; border-bottom-left-radius: 0.375rem;">
                        </div>

                        <!-- Right Column - Register Form -->
                        <div class="col-md-6 p-4">
                            <h3 class="text-center mb-4">Register</h3>

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ route('auth.register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Register</button>
                                </div>
                            </form>

                            <p class="mt-3 text-center">
                                Already have an account?
                                <a href="{{ route('auth.showLoginForm') }}" class="text-primary">Login Here.</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection