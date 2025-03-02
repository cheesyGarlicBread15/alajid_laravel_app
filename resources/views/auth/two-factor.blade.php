@extends('layouts.app_no_nav')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left Column - 2FA Form -->
                        <div class="col-md-6 p-4">
                            <h3 class="text-center mb-4">Two-Factor Authentication</h3>
                            <p class="text-center mb-4">We have sent a 6-digit verification code to your email.</p>

                            @if(session('message'))
                            <div class="alert alert-info">
                                {{ session('message') }}
                            </div>
                            @endif

                            <form action="{{ route('2fa.verify') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="two_factor_code" class="form-label">Verification Code</label>
                                    <input type="number"
                                        class="form-control @error('two_factor_code') is-invalid @enderror"
                                        id="two_factor_code"
                                        name="two_factor_code"
                                        placeholder="Enter 6-digit code"
                                        required>
                                    @error('two_factor_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        Verify Code
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right Column - Image -->
                        <div class="col-md-6 d-none d-md-block p-0" style="min-height: 100%;">
                            <img src="{{ asset('storage/images/login.png') }}"
                                alt="2FA Illustration"
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