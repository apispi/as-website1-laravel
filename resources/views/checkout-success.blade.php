@extends('layouts.app')

@section('title', 'Payment Successful - ApiSpi')

@push('head')
<style>
    .success-page { min-height: 100vh; display: flex; align-items: center; padding: 4rem 0; }
    .success-card {
        text-align: center; padding: 3rem 2rem;
        background: rgba(28,24,16,0.6); border: 1px solid rgba(0,217,126,0.3);
        border-radius: 1.25rem; max-width: 560px; margin: 0 auto;
    }
    .success-icon { font-size: 4rem; margin-bottom: 1.5rem; }
    .success-card h1 { font-size: 1.75rem; margin-bottom: 1rem; color: #00d97e; }
    .success-card p { color: var(--gray); margin-bottom: 1.5rem; line-height: 1.7; }
    .success-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1rem; }
</style>
@endpush

@section('content')
    <section class="success-page">
        <div class="container">
            <div class="success-card">
                <div class="success-icon">✅</div>
                <h1>Payment Successful!</h1>
                <p>
                    Thank you for subscribing to <strong>{{ $agentName }}</strong>.<br>
                    @if($customerEmail)
                        A confirmation has been sent to <strong>{{ $customerEmail }}</strong>.
                    @else
                        A confirmation has been sent to your email.
                    @endif
                    Our team will reach out within 24 hours to get you set up.
                </p>
                <p style="font-size:0.9rem;">Questions? Email us at <a href="mailto:payment@apispi.com">payment@apispi.com</a></p>
                <div class="success-actions">
                    <a href="{{ route('agents.index') }}" class="btn btn-primary">Browse More Agents</a>
                    <a href="{{ route('contact') }}" class="btn btn-secondary">Contact Support</a>
                </div>
            </div>
        </div>
    </section>
@endsection
