@extends('layouts.app')

@section('title', 'Create Account - ApiSpi')

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
    .btn-auth { width: 100%; padding: 0.875rem; font-size: 1rem; font-weight: 600; cursor: pointer; border: none; border-radius: 0.5rem; transition: all 0.3s ease; }
    .btn-auth:hover { transform: translateY(-2px); }
    .auth-links { margin-top: 1.5rem; text-align: center; font-size: 0.9rem; color: #6b7280; }
    .auth-links a { color: #FCD34D; text-decoration: none; }
    .auth-links a:hover { text-decoration: underline; }
    .divider { display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; color: #4b5563; font-size: 0.85rem; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(217, 119, 6, 0.15); }
    .field-error { color: #ff3b30; font-size: 0.8rem; margin-top: 0.3rem; }
    .error-msg { background: rgba(255, 59, 48, 0.1); border: 1px solid rgba(255, 59, 48, 0.3); border-radius: 0.5rem; padding: 0.75rem 1rem; color: #ff3b30; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .terms-note { font-size: 0.8rem; color: #6b7280; text-align: center; margin-top: 1rem; }
    .terms-note a { color: #FCD34D; text-decoration: none; }
</style>
@endpush

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <h1 class="auth-title">Create your account</h1>
            <p class="auth-subtitle">Join ApiSpi and start building with AI agents</p>

            @if($errors->any())
                <div class="error-msg">
                    <ul style="list-style:none; margin:0; padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Jane Smith" required autofocus>
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 8 characters" required>
                    @error('password') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-auth">Create Account</button>
                <p class="terms-note">By signing up you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
            </form>

            <div class="divider">or</div>

            <div class="auth-links">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</section>
@endsection
