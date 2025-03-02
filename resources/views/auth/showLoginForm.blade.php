@extends('layouts.app_no_nav')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left Column - Login Form -->
                        <div class="col-md-6 p-4">
                            <h3 class="text-center mb-4">Login</h3>

                            @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif

                            @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ route('auth.login') }}" method="POST">
                                @csrf
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
                                <div class="d-flex justify-content-end mb-3">
                                    <a href="{{ route('password.request') }}" class="text-primary">Forgot Password?</a>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </form>
                            <p class="mt-3 text-center">
                                Don't have an account?
                                <a href="{{ route('auth.showRegisterForm') }}" class="text-primary">Register Here.</a>
                            </p>
                        </div>

                        <!-- Right Column - Image -->
                        <div class="col-md-6 d-none d-md-block p-0" style="min-height: 100%;">
                            <img src="{{ asset('storage/images/login.png') }}"
                                alt="Login Illustration"
                                class="w-100 h-100"
                                style="object-fit: cover; border-top-right-radius: 0.375rem; border-bottom-right-radius: 0.375rem;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection