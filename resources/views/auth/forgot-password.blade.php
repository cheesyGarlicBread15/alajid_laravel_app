@extends('layouts.app_no_nav')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow p-4">
                <div class="card-body">
                    <h2 class="text-center mb-4">Forgot Password</h2>

                    @if(session('success'))
                    <p class="alert alert-success">{{ session('success') }}</p>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter your email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email Address" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('auth.showLoginForm') }}" class="text-primary">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection