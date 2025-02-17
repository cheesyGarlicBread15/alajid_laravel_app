@extends('layouts.app_no_nav')

@section('content')
<div class="container">
    <h2>Verify Your Login</h2>
    <p>We have sent a 6-digit verification code to your email.</p>

    @if(session('message'))
    <p class="alert alert-info">{{ session('message') }}</p>
    @endif

    <form action="{{ route('2fa.verify') }}" method="POST">
        @csrf
        <input type="number" name="two_factor_code" placeholder="Enter OTP" required>
        <button type="submit">Verify</button>
    </form>

    @error('two_factor_code')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
@endsection