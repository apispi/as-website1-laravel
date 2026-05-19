@extends('layouts.app')

@section('title', 'Sign In - ApiSpi')

@push('head')
<style>
    .auth-section { padding: 5rem 0; min-height: calc(100vh - 200px); display: flex; align-items: center; }
    .auth-card { max-width: 480px; margin: 0 auto; background: rgba(28, 24, 16, 0.8); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1.5rem; padding: 3rem; width: 100%; }
    .auth-title { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .auth-subtitle { color: #6b7280; margin-bottom: 2.5rem; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; }
    .form-group input { width: 100%; padding: 0.75rem 1rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; font-size: 1rem; transition: all 0.3s ease; box-sizing: border-box; }
    .form-group input:focus { outline: none; border-color: #D97706; box-shadow: 0 0 20px rgba(217, 119, 6, 0.3); }
    .form-group input::placeholder { color: rgba(217, 119, 6, 0.4); }
    .form-check { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
    .form-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: #D97706; cursor: pointer; }
    .form-check label { font-size: 0.9rem; color: #9ca3af; cursor: pointer; }
    .btn-auth { width: 100%; padding: 0.875rem; font-size: 1rem; font-weight: 600; cursor: pointer; border: none; border-radius: 0.5rem; transition: all 0.3s ease; }
    .btn-auth:hover { transform: translateY(-2px); }
    .auth-links { margin-top: 1.5rem; text-align: center; font-size: 0.9rem; color: #6b7280; }
    .auth-links a { color: #FCD34D; text-decoration: none; }
    .auth-links a:hover { text-decoration: underline; }
    .divider { display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; color: #4b5563; font-size: 0.85rem; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(217, 119, 6, 0.15); }
    .error-msg { background: rgba(255, 59, 48, 0.1); border: 1px solid rgba(255, 59, 48, 0.3); border-radius: 0.5rem; padding: 0.75rem 1rem; color: #ff3b30; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .success-msg { background: rgba(0, 217, 126, 0.1); border: 1px solid rgba(0, 217, 126, 0.3); border-radius: 0.5rem; padding: 0.75rem 1rem; color: #00d97e; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .field-error { color: #ff3b30; font-size: 0.8rem; margin-top: 0.3rem; }
</style>
@endpush

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-subtitle">Sign in to your ApiSpi account</p>

            @if(session('status'))
                <div class="success-msg">{{ session('status') }}</div>
            @endif

            @if($errors->has('email') && !$errors->has('password'))
                <div class="error-msg">{{ $errors->first('email') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password" style="display:flex; justify-content:space-between;">
                        <span>Password</span>
                        <a href="{{ route('password.request') }}" style="color:#FCD34D; font-weight:400; font-size:0.85rem; text-decoration:none;">Forgot password?</a>
                    </label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Keep me signed in</label>
                </div>
                <button type="submit" class="btn btn-primary btn-auth">Sign In</button>
            </form>

            <div class="divider">or</div>

            <div class="auth-links">
                Don't have an account? <a href="{{ route('register') }}">Create one free</a>
            </div>
        </div>
    </div>
</section>
@endsection
