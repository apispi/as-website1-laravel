@extends('layouts.app')

@section('title', 'Checkout - ApiSpi')

@push('head')
<style>
    .checkout-page { min-height: 100vh; padding: 6rem 0 4rem; }
    .checkout-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; max-width: 900px; margin: 0 auto; }
    @media (max-width: 768px) { .checkout-grid { grid-template-columns: 1fr; gap: 2rem; } }
    .order-summary { background: rgba(28,24,16,0.6); border: 1px solid rgba(217,119,6,0.2); border-radius: 1.25rem; padding: 2rem; }
    .order-summary h2 { font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--accent); }
    .order-agent-name { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
    .order-period { color: var(--gray); font-size: 0.9rem; margin-bottom: 2rem; }
    .order-line { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(217,119,6,0.1); font-size: 0.95rem; }
    .order-line:last-of-type { border-bottom: none; }
    .order-total { display: flex; justify-content: space-between; padding: 1rem 0 0; border-top: 2px solid rgba(217,119,6,0.3); margin-top: 0.5rem; font-size: 1.1rem; font-weight: 700; }
    .order-total .amount { color: var(--accent); font-size: 1.4rem; }
    .order-note { margin-top: 1.5rem; padding: 1rem; background: rgba(217,119,6,0.05); border: 1px solid rgba(217,119,6,0.15); border-radius: 0.75rem; font-size: 0.85rem; color: var(--gray); line-height: 1.6; }
    .payment-section { display: flex; flex-direction: column; gap: 1.5rem; }
    .payment-section h2 { font-size: 1.25rem; color: var(--accent); }
    .secure-badge { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--gray); }
    .checkout-header { text-align: center; margin-bottom: 3rem; }
    .checkout-header h1 { font-size: 2rem; margin-bottom: 0.5rem; }
    .checkout-header p { color: var(--gray); }
    .btn-stripe {
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        width: 100%; padding: 0.9rem 1.5rem; border-radius: 0.75rem;
        background: #635BFF; border: none; color: #fff;
        font-size: 1rem; font-weight: 700; cursor: pointer;
        font-family: inherit; transition: background 0.18s; min-height: 52px;
    }
    .btn-stripe:hover { background: #5046e5; }
    .btn-stripe:disabled { opacity: 0.6; cursor: not-allowed; }
    .stripe-logo { opacity: 0.85; }
    .payment-note { font-size: 0.8rem; color: var(--gray); text-align: center; }
</style>
@endpush

@section('content')
    <section class="checkout-page">
        <div class="container">
            <a href="{{ route('agents.index') }}" class="back-link">← Back to Agents</a>

            <div class="checkout-header">
                <h1>Complete Your Order</h1>
                <p>Secure checkout powered by Stripe</p>
            </div>

            <div class="checkout-grid">
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <div class="order-agent-name" id="summary-agent">Loading…</div>
                    <div class="order-period" id="summary-period">Monthly subscription</div>
                    <div class="order-line">
                        <span id="summary-label">Agent plan</span>
                        <span id="summary-price">$0.00</span>
                    </div>
                    <div class="order-line">
                        <span>Billing</span>
                        <span id="summary-billing">Monthly</span>
                    </div>
                    <div class="order-line">
                        <span>Setup fee</span>
                        <span>$0.00</span>
                    </div>
                    <div class="order-total">
                        <span>Due today</span>
                        <span class="amount" id="summary-total">$0.00</span>
                    </div>
                    <div class="order-note" id="summary-note">
                        You will be charged <strong id="note-amount">$0.00</strong> monthly.
                        Cancel anytime — access continues through the end of your billing cycle.
                        Payments processed securely via Stripe.
                    </div>
                </div>

                <div class="payment-section">
                    <h2>Payment</h2>
                    <div class="secure-badge">🔒 256-bit SSL encryption &nbsp;·&nbsp; Powered by Stripe</div>

                    <form id="stripe-form" method="POST" action="{{ route('checkout.session') }}">
                        @csrf
                        <input type="hidden" name="agent"  id="form-agent">
                        <input type="hidden" name="amount" id="form-amount">
                        <input type="hidden" name="type"   id="form-type">
                        <button type="submit" class="btn-stripe" id="pay-btn">
                            <svg class="stripe-logo" width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M13.479 9.883c-1.626-.604-2.512-1.066-2.512-1.75 0-.608.498-.96 1.395-.96 1.637 0 3.295.634 4.461 1.226l.652-4.018C16.448 3.817 14.779 3 12.36 3 9.36 3 7.2 4.76 7.2 7.453c0 2.606 1.927 3.761 4.226 4.6 1.672.608 2.24 1.066 2.24 1.75 0 .673-.554 1.066-1.578 1.066-1.595 0-3.59-.73-4.9-1.5l-.654 4.05C7.753 18.14 9.76 19 12.3 19c3.12 0 5.49-1.697 5.49-4.618 0-2.492-1.56-3.835-4.311-4.499z" fill="#fff"/></svg>
                            Pay with Card
                        </button>
                    </form>

                    <p class="payment-note">You'll be redirected to Stripe's secure checkout page.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:payment@apispi.com">Payment Inquiries</a> | <a href="{{ route('contact') }}">Support</a></p>
@endsection

@push('scripts')
<script>
(function () {
    const params  = new URLSearchParams(window.location.search);
    const agent   = params.get('agent')  || 'ApiSpi Agent';
    const amount  = parseFloat(params.get('amount') || '0').toFixed(2);

    const isOneOff = params.get('type') === 'training';

    document.getElementById('summary-agent').textContent  = agent;
    document.getElementById('summary-label').textContent  = agent + (isOneOff ? '' : ' plan');
    document.getElementById('summary-price').textContent  = '$' + amount;
    document.getElementById('summary-total').textContent  = '$' + amount;
    document.getElementById('form-agent').value           = agent;
    document.getElementById('form-amount').value          = amount;
    document.getElementById('form-type').value            = params.get('type') || '';

    if (isOneOff) {
        document.getElementById('summary-period').textContent  = 'One-off payment';
        document.getElementById('summary-billing').textContent = 'One-off';
        document.getElementById('summary-note').innerHTML =
            'This is a one-off payment of <strong>$' + amount + '</strong>. ' +
            'Payments processed securely via Stripe.';
    } else {
        document.getElementById('note-amount').textContent = '$' + amount;
    }

    document.getElementById('stripe-form').addEventListener('submit', function () {
        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.textContent = 'Redirecting…';
    });
})();
</script>
@endpush
