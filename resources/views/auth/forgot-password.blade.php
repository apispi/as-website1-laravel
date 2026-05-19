@extends('layouts.app')

@section('title', 'Reset Password - ApiSpi')

@push('head')
<style>
    .auth-section { padding: 5rem 0; min-height: calc(100vh - 200px); display: flex; align-items: center; }
    .auth-card { max-width: 480px; margin: 0 auto; background: rgba(28, 24, 16, 0.8); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1.5rem; padding: 3rem; width: 100%; }
    .auth-title { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .auth-subtitle { color: #6b7280; margin-bottom: 2.5rem; line-height: 1.6; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; }
    .form-group input { width: 100%; padding: 0.75rem 1rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; font-size: 1rem; transition: all 0.3s ease; box-sizing: border-box; }
    .form-group input:focus { outline: none; border-color: #D97706; box-shadow: 0 0 20px rgba(217, 119, 6, 0.3); }
    .form-group input::placeholder { color: rgba(217, 119, 6, 0.4); }
    .btn-auth { width: 100%; padding: 0.875rem; font-size: 1rem; font-weight: 600; cursor: pointer; border: none; border-radius: 0.5rem; transition: all 0.3s ease; }
    .btn-auth:hover { transform: translateY(-2px); }
    .auth-links { margin-top: 1.5rem; text-align: center; font-size: 0.9rem; color: #6b7280; }
    .auth-links a { color: #FCD34D; text-decoration: none; }
    .auth-links a:hover { text-decoration: underline; }
    .field-error { color: #ff3b30; font-size: 0.8rem; margin-top: 0.3rem; }
    .error-msg { background: rgba(255, 59, 48, 0.1); border: 1px solid rgba(255, 59, 48, 0.3); border-radius: 0.5rem; padding: 0.75rem 1rem; color: #ff3b30; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .success-msg { background: rgba(0, 217, 126, 0.1); border: 1px solid rgba(0, 217, 126, 0.3); border-radius: 0.5rem; padding: 0.75rem 1rem; color: #00d97e; font-size: 0.9rem; margin-bottom: 1.5rem; }
</style>
@endpush

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <h1 class="auth-title">Forgot password?</h1>
            <p class="auth-subtitle">No worries — enter your email address and we'll send you a link to reset your password.</p>

            @if(session('status'))
                <div class="success-msg">{{ session('status') }}</div>
            @endif

            @error('email')
                <div class="error-msg">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary btn-auth">Send Reset Link</button>
            </form>

            <div class="auth-links" style="margin-top: 1.5rem;">
                <a href="{{ route('login') }}">← Back to sign in</a>
            </div>
        </div>
    </div>
</section>
@endsection
