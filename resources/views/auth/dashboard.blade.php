@extends('layouts.app')

@section('title', 'Dashboard - ApiSpi')

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                Welcome, <span class="gradient-text">{{ Auth::user()->name }}</span>
            </h1>
            <p class="hero-subtitle">
                Manage your AI agents, track usage, and grow your workflow from your personal dashboard.
            </p>
        </div>
    </div>
</section>

<section style="padding: 4rem 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="agent-card">
                <h3 style="color:#FCD34D;">Account</h3>
                <p style="color:#9ca3af; margin: 0.5rem 0 1rem;">{{ Auth::user()->email }}</p>
                <p style="color:#6b7280; font-size:0.85rem;">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
            </div>
            <div class="agent-card">
                <h3 style="color:#FCD34D;">Active Agents</h3>
                <p style="color:#9ca3af; margin: 0.5rem 0 1rem;">You haven't deployed any agents yet.</p>
                <a href="{{ route('agents.index') }}" class="agent-link">Browse Agents →</a>
            </div>
            <div class="agent-card">
                <h3 style="color:#FCD34D;">Quick Actions</h3>
                <ul style="list-style:none; padding:0; margin:0.5rem 0 0; display:flex; flex-direction:column; gap:0.5rem;">
                    <li><a href="{{ route('agents.index') }}" style="color:#FCD34D; text-decoration:none;">→ Explore Agents</a></li>
                    <li><a href="{{ route('contact') }}" style="color:#FCD34D; text-decoration:none;">→ Contact Support</a></li>
                    <li><a href="{{ route('training') }}" style="color:#FCD34D; text-decoration:none;">→ Training Resources</a></li>
                </ul>
            </div>
        </div>

        <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; max-width: 500px;">
            <h3 style="margin-bottom: 1rem;">Account Settings</h3>
            <p style="color: #6b7280; margin-bottom: 1.5rem; font-size: 0.9rem;">More settings coming soon.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary" style="cursor:pointer; border:none;">Sign Out</button>
            </form>
        </div>
    </div>
</section>
@endsection
