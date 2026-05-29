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
    .btn-google { display: flex; align-items: center; justify-content: center; gap: 0.65rem; width: 100%; padding: 0.8rem 1rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 0.5rem; color: #111827; font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: background 0.18s, box-shadow 0.18s; font-family: inherit; cursor: pointer; }
    .btn-google:hover { background: #f9fafb; box-shadow: 0 1px 6px rgba(0,0,0,0.12); }
    .btn-microsoft { display: flex; align-items: center; justify-content: center; gap: 0.65rem; width: 100%; padding: 0.8rem 1rem; background: #2f2f2f; border: 1px solid #444; border-radius: 0.5rem; color: #fff; font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: background 0.18s, box-shadow 0.18s; font-family: inherit; cursor: pointer; margin-top: 0.65rem; }
    .btn-microsoft:hover { background: #404040; box-shadow: 0 1px 6px rgba(0,0,0,0.2); }
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
                <p class="terms-note">By signing up you agree to our <a href="{{ route('terms') }}">Terms &amp; Conditions</a> and <a href="{{ route('privacy') }}">Privacy Policy</a>.</p>
            </form>

            <div class="divider">or</div>

            <a href="{{ route('auth.google.redirect') }}" class="btn-google">
                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.707A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.961L3.964 6.293C4.672 4.166 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
                Sign up with Google
            </a>

            <a href="{{ route('auth.azure.redirect') }}" class="btn-microsoft">
                <svg width="18" height="18" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><rect x="1" y="1" width="9" height="9" fill="#f25022"/><rect x="11" y="1" width="9" height="9" fill="#7fba00"/><rect x="1" y="11" width="9" height="9" fill="#00a4ef"/><rect x="11" y="11" width="9" height="9" fill="#ffb900"/></svg>
                Sign up with Microsoft
            </a>

            <div class="auth-links" style="margin-top:1.25rem;">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</section>
@endsection
