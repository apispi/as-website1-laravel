@extends('layouts.app')

@section('title', 'Sign In - ApiSpi')

@push('head')
<style>
    .auth-section { padding: 5rem 0; min-height: calc(100vh - 200px); display: flex; align-items: center; }
    .auth-card { max-width: 520px; margin: 0 auto; background: rgba(28, 24, 16, 0.8); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1.5rem; padding: 3rem; width: 100%; }
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
    .btn-google { display: flex; align-items: center; justify-content: center; gap: 0.65rem; width: 100%; padding: 0.8rem 1rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 0.5rem; color: #111827; font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: background 0.18s, box-shadow 0.18s; font-family: inherit; cursor: pointer; }
    .btn-google:hover { background: #f9fafb; box-shadow: 0 1px 6px rgba(0,0,0,0.12); }
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
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password') <div class="field-error">{{ $message }}</div> @enderror
                    <a href="{{ route('password.request') }}" style="color:#FCD34D; font-weight:400; font-size:0.85rem; text-decoration:none; display:inline-block; margin-top:0.4rem;">Forgot password?</a>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Keep me signed in</label>
                </div>
                <button type="submit" class="btn btn-primary btn-auth">Sign In</button>
            </form>

            <div class="divider">or</div>

            <a href="{{ route('auth.google.redirect') }}" class="btn-google">
                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.707A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.961L3.964 6.293C4.672 4.166 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
                Continue with Google
            </a>

            <div class="auth-links" style="margin-top:1.25rem;">
                Don't have an account? <a href="{{ route('register') }}">Create one free</a>
            </div>
        </div>
    </div>
</section>
@endsection
