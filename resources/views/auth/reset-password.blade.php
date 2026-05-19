@extends('layouts.app')

@section('title', 'Set New Password - ApiSpi')

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
    .field-error { color: #ff3b30; font-size: 0.8rem; margin-top: 0.3rem; }
    .error-msg { background: rgba(255, 59, 48, 0.1); border: 1px solid rgba(255, 59, 48, 0.3); border-radius: 0.5rem; padding: 0.75rem 1rem; color: #ff3b30; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .auth-links { margin-top: 1.5rem; text-align: center; font-size: 0.9rem; color: #6b7280; }
    .auth-links a { color: #FCD34D; text-decoration: none; }
    .auth-links a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <h1 class="auth-title">Set new password</h1>
            <p class="auth-subtitle">Choose a strong password for your account.</p>

            @if($errors->any())
                <div class="error-msg">
                    <ul style="list-style:none; margin:0; padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password">New password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 8 characters" required>
                    @error('password') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm new password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-auth">Reset Password</button>
            </form>

            <div class="auth-links">
                <a href="{{ route('login') }}">← Back to sign in</a>
            </div>
        </div>
    </div>
</section>
@endsection
