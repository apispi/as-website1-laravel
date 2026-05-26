@extends('layouts.app')

@section('title', 'Partner Program - ApiSpi')

@push('head')
<style>
    .partner-hero { padding: 7rem 0 4rem; text-align: center; }
    .partner-hero h1 { font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; letter-spacing: -0.025em; margin-bottom: 1rem; }
    .partner-hero p { color: var(--gray); font-size: 1.1rem; max-width: 560px; margin: 0 auto 2rem; line-height: 1.75; }
    .partner-tag {
        display: inline-block; padding: 0.3rem 0.85rem; border-radius: 99px;
        background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.25);
        font-size: 0.72rem; font-weight: 700; color: var(--accent);
        letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;
    }

    /* Tiers */
    .tiers-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-top: 3rem; }
    @media (max-width: 700px) { .tiers-grid { grid-template-columns: 1fr; } }
    .tier-card {
        background: rgba(28,24,16,0.6); border: 1px solid rgba(217,119,6,0.15);
        border-radius: 1.25rem; padding: 2rem; transition: border-color 0.2s, transform 0.2s;
    }
    .tier-card:hover { border-color: rgba(217,119,6,0.4); transform: translateY(-2px); }
    .tier-icon { font-size: 2.2rem; margin-bottom: 1rem; display: block; }
    .tier-name { font-size: 1.15rem; font-weight: 800; margin-bottom: 0.3rem; }
    .tier-tagline { font-size: 0.85rem; color: var(--accent); font-weight: 600; margin-bottom: 0.85rem; }
    .tier-desc { font-size: 0.875rem; color: var(--gray); line-height: 1.65; margin-bottom: 1.1rem; }
    .tier-perks { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.4rem; }
    .tier-perks li { font-size: 0.82rem; color: var(--gray); display: flex; align-items: flex-start; gap: 0.5rem; }
    .tier-perks li::before { content: '✓'; color: var(--accent); font-weight: 700; flex-shrink: 0; margin-top: 0.05rem; }

    /* Benefits bar */
    .benefits-bar {
        background: rgba(217,119,6,0.03);
        border-top: 1px solid rgba(217,119,6,0.1); border-bottom: 1px solid rgba(217,119,6,0.1);
        padding: 3rem 0;
    }
    .benefits-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; text-align: center; }
    @media (max-width: 700px) { .benefits-grid { grid-template-columns: repeat(2, 1fr); } }
    .benefit-num { font-size: 1.9rem; font-weight: 800; color: var(--accent); }
    .benefit-label { font-size: 0.8rem; color: var(--gray); margin-top: 0.25rem; line-height: 1.4; }

    /* How it works */
    .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
    @media (max-width: 700px) { .steps { grid-template-columns: 1fr; } }
    .step { text-align: center; }
    .step-num {
        width: 52px; height: 52px; border-radius: 50%; margin: 0 auto 1rem;
        background: rgba(217,119,6,0.1); border: 2px solid rgba(217,119,6,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; font-weight: 800; color: var(--accent);
    }
    .step h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .step p { font-size: 0.875rem; color: var(--gray); line-height: 1.65; }

    /* Form */
    .partner-form-section { background: rgba(217,119,6,0.02); border-top: 1px solid rgba(217,119,6,0.1); }
    .partner-form-card {
        max-width: 680px; margin: 0 auto;
        background: rgba(28,24,16,0.8); border: 1px solid rgba(217,119,6,0.15);
        border-radius: 1.25rem; padding: 2.5rem;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
    @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: var(--gray); }
    .form-input, .form-select, .form-textarea {
        padding: 0.75rem 1rem;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(217,119,6,0.2);
        border-radius: 0.625rem; color: var(--text); font-size: 0.95rem; font-family: inherit;
        transition: border-color 0.18s; width: 100%;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--accent); }
    .form-input::placeholder, .form-textarea::placeholder { color: #4b5563; }
    .form-select option { background: #1c1810; }
    .form-textarea { resize: vertical; min-height: 100px; }
    .form-submit {
        width: 100%; margin-top: 0.5rem; padding: 0.9rem;
        background: linear-gradient(135deg, var(--accent), #b45309);
        border: none; border-radius: 0.625rem;
        color: #1a0e00; font-size: 1rem; font-weight: 800;
        cursor: pointer; font-family: inherit; transition: opacity 0.18s;
    }
    .form-submit:hover { opacity: 0.88; }
    .form-note { font-size: 0.75rem; color: var(--gray); text-align: center; margin-top: 0.75rem; opacity: 0.7; }
    .flash-success { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25); color: #10b981; border-radius: 0.75rem; padding: 1rem 1.25rem; font-size: 0.9rem; margin-bottom: 1.5rem; text-align: center; }
    .flash-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; border-radius: 0.75rem; padding: 0.75rem 1rem; font-size: 0.875rem; margin-bottom: 1rem; }
</style>
@endpush

@section('content')

    {{-- Hero --}}
    <section class="partner-hero">
        <div class="container">
            <div class="partner-tag">Partner Program</div>
            <h1>Grow Together with <span class="gradient-text">ApiSpi</span></h1>
            <p>Join our partner ecosystem and deliver AI agent solutions to your clients — backed by our platform, support, and revenue share.</p>
            <a href="#apply" class="btn btn-primary">Apply to Partner →</a>
        </div>
    </section>

    {{-- Partner Tiers --}}
    <section style="padding: 2rem 0 5rem;">
        <div class="container">
            <div style="text-align:center; margin-bottom:1rem;">
                <div class="partner-tag">Program Types</div>
                <h2 style="font-size:clamp(1.6rem,3vw,2.2rem); font-weight:800; letter-spacing:-0.02em;">Find the Right Partnership</h2>
            </div>
            <div class="tiers-grid">

                <div class="tier-card">
                    <span class="tier-icon">🤝</span>
                    <div class="tier-name">Referral Partner</div>
                    <div class="tier-tagline">Earn commission. Zero overhead.</div>
                    <p class="tier-desc">Refer businesses to ApiSpi and earn a recurring commission on every subscription they take up. No delivery required — just the introduction.</p>
                    <ul class="tier-perks">
                        <li>Up to 20% recurring revenue share</li>
                        <li>Partner dashboard to track referrals</li>
                        <li>Co-branded sales materials</li>
                        <li>Dedicated partner manager</li>
                    </ul>
                </div>

                <div class="tier-card">
                    <span class="tier-icon">🏢</span>
                    <div class="tier-name">Agency Partner</div>
                    <div class="tier-tagline">Deliver AI solutions. We power the stack.</div>
                    <p class="tier-desc">Digital, marketing, and consulting agencies that build and deploy AI agent solutions for clients. ApiSpi provides the infrastructure so you can focus on strategy and delivery.</p>
                    <ul class="tier-perks">
                        <li>Agency-tier pricing and volume discounts</li>
                        <li>Joint go-to-market opportunities</li>
                        <li>Early access to new agents and features</li>
                        <li>Co-sell support from our team</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    {{-- Benefits bar --}}
    <div class="benefits-bar">
        <div class="container">
            <div class="benefits-grid">
                <div>
                    <div class="benefit-num">20%</div>
                    <div class="benefit-label">Recurring revenue share for referral partners</div>
                </div>
                <div>
                    <div class="benefit-num">48 hr</div>
                    <div class="benefit-label">Onboarding time from approval to live</div>
                </div>
                <div>
                    <div class="benefit-num">6+</div>
                    <div class="benefit-label">Industries covered by our agent catalog</div>
                </div>
                <div>
                    <div class="benefit-num">AU</div>
                    <div class="benefit-label">Built and supported locally in Australia</div>
                </div>
            </div>
        </div>
    </div>

    {{-- How it works --}}
    <section style="padding: 5rem 0;">
        <div class="container">
            <div style="text-align:center; margin-bottom:3rem;">
                <div class="partner-tag">How It Works</div>
                <h2 style="font-size:clamp(1.6rem,3vw,2.2rem); font-weight:800; letter-spacing:-0.02em;">From Application to Active Partner</h2>
            </div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <h3>Submit Your Application</h3>
                    <p>Tell us about your business, your clients, and what type of partnership suits you best. Takes less than 5 minutes.</p>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <h3>We Review & Onboard</h3>
                    <p>Our team reviews your application within 2 business days. Once approved, we'll walk you through the partner portal and tools.</p>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <h3>Start Earning</h3>
                    <p>Access your partner dashboard, share your referral link or resell credentials, and start delivering value to your clients from day one.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Application form --}}
    <section class="partner-form-section" id="apply" style="padding: 5rem 0;">
        <div class="container">
            <div style="text-align:center; margin-bottom:2.5rem;">
                <div class="partner-tag">Apply Now</div>
                <h2 style="font-size:clamp(1.6rem,3vw,2.2rem); font-weight:800; letter-spacing:-0.02em;">Submit Your <span class="gradient-text">Partnership Application</span></h2>
                <p style="color:var(--gray); margin-top:0.75rem;">We review every application personally. You'll hear from us within 2 business days.</p>
            </div>

            <div class="partner-form-card">
                @if(session('success'))
                    <div class="flash-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="flash-error">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                @endif

                <form action="{{ route('partners.store') }}" method="POST">
                    @csrf
                    {{-- Honeypot --}}
                    <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                        <label for="website_url">Website</label>
                        <input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="name">Your Name *</label>
                            <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Jane Smith" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email *</label>
                            <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="jane@yourcompany.com.au" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="company">Company Name *</label>
                            <input class="form-input" id="company" type="text" name="company" value="{{ old('company') }}" placeholder="Your Company Pty Ltd" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="partner_type">Partnership Type *</label>
                            <select class="form-select" id="partner_type" name="partner_type" required>
                                <option value="">Select a type…</option>
                                <option value="Referral Partner" {{ old('partner_type') === 'Referral Partner' ? 'selected' : '' }}>Referral Partner</option>
                                <option value="Agency Partner" {{ old('partner_type') === 'Agency Partner' ? 'selected' : '' }}>Agency Partner</option>
                            </select>
                        </div>
                        <div class="form-group full">
                            <label class="form-label" for="message">Tell us about your business and how you'd like to partner</label>
                            <textarea class="form-textarea" id="message" name="message" placeholder="What do you do, who are your clients, and what does a successful partnership look like for you?">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="form-submit">Submit Application →</button>
                    <p class="form-note">No commitment required · We'll review personally and respond within 2 business days</p>
                </form>
            </div>
        </div>
    </section>

@endsection
