@extends('layouts.app')

@section('title', 'Checkout - ApiSpi')

@push('head')
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_SANDBOX_CLIENT_ID&currency=USD" data-sdk-integration-source="button-factory"></script>
<style>
    .checkout-page { min-height: 100vh; padding: 6rem 0 4rem; }
    .checkout-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; max-width: 900px; margin: 0 auto; }
    @media (max-width: 768px) { .checkout-grid { grid-template-columns: 1fr; gap: 2rem; } }
    .order-summary { background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1.25rem; padding: 2rem; }
    .order-summary h2 { font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--accent); }
    .order-agent-name { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
    .order-period { color: var(--gray); font-size: 0.9rem; margin-bottom: 2rem; }
    .order-line { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(217, 119, 6, 0.1); font-size: 0.95rem; }
    .order-line:last-of-type { border-bottom: none; }
    .order-total { display: flex; justify-content: space-between; padding: 1rem 0 0; border-top: 2px solid rgba(217, 119, 6, 0.3); margin-top: 0.5rem; font-size: 1.1rem; font-weight: 700; }
    .order-total .amount { color: var(--accent); font-size: 1.4rem; }
    .order-note { margin-top: 1.5rem; padding: 1rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.15); border-radius: 0.75rem; font-size: 0.85rem; color: var(--gray); line-height: 1.6; }
    .payment-section { display: flex; flex-direction: column; gap: 1.5rem; }
    .payment-section h2 { font-size: 1.25rem; color: var(--accent); }
    .secure-badge { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--gray); margin-bottom: 0.5rem; }
    #paypal-button-container { min-height: 150px; }
    .payment-divider { display: flex; align-items: center; gap: 1rem; color: var(--gray); font-size: 0.85rem; }
    .payment-divider::before, .payment-divider::after { content: ''; flex: 1; height: 1px; background: rgba(217, 119, 6, 0.2); }
    #checkout-success { display: none; text-align: center; padding: 3rem 2rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(0, 217, 126, 0.3); border-radius: 1.25rem; max-width: 560px; margin: 0 auto; }
    #checkout-success .success-icon { font-size: 4rem; margin-bottom: 1.5rem; }
    #checkout-success h2 { font-size: 1.75rem; margin-bottom: 1rem; color: var(--success); }
    #checkout-success p { color: var(--gray); margin-bottom: 2rem; line-height: 1.7; }
    .payment-error { display: none; padding: 1rem; background: rgba(255, 59, 48, 0.1); border: 1px solid rgba(255, 59, 48, 0.3); border-radius: 0.75rem; color: #ff3b30; font-size: 0.9rem; }
    .back-link { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gray); text-decoration: none; font-size: 0.9rem; margin-bottom: 2rem; transition: color var(--transition-fast); }
    .back-link:hover { color: var(--accent); }
    .checkout-header { text-align: center; margin-bottom: 3rem; }
    .checkout-header h1 { font-size: 2rem; margin-bottom: 0.5rem; }
    .checkout-header p { color: var(--gray); }
    .sandbox-notice { display: inline-block; background: rgba(255, 180, 30, 0.15); border: 1px solid rgba(255, 180, 30, 0.4); color: #ffb41e; padding: 0.4rem 1rem; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 1.5rem; }
</style>
@endpush

@section('content')
    <section class="checkout-page">
        <div class="container">
            <a href="{{ route('agents.index') }}" class="back-link">← Back to Agents</a>

            <div class="checkout-header">
                <div class="sandbox-notice">SANDBOX / TEST MODE</div>
                <h1>Complete Your Order</h1>
                <p>Secure checkout powered by PayPal</p>
            </div>

            <div id="checkout-form">
                <div class="checkout-grid">
                    <div class="order-summary">
                        <h2>Order Summary</h2>
                        <div class="order-agent-name" id="summary-agent">Loading...</div>
                        <div class="order-period" id="summary-period">Monthly subscription</div>
                        <div class="order-line">
                            <span id="summary-label">Agent plan</span>
                            <span id="summary-price">$0.00</span>
                        </div>
                        <div class="order-line">
                            <span>Billing</span>
                            <span>Monthly</span>
                        </div>
                        <div class="order-line">
                            <span>Setup fee</span>
                            <span>$0.00</span>
                        </div>
                        <div class="order-total">
                            <span>Due today</span>
                            <span class="amount" id="summary-total">$0.00</span>
                        </div>
                        <div class="order-note">
                            You will be charged <strong id="note-amount">$0.00</strong> monthly. Cancel anytime — access continues through the end of your billing cycle. Payments processed securely via PayPal.
                        </div>
                    </div>

                    <div class="payment-section">
                        <h2>Payment Method</h2>
                        <div class="secure-badge">🔒 256-bit SSL encryption &nbsp;·&nbsp; Powered by PayPal</div>
                        <div id="paypal-button-container"></div>
                        <div class="payment-error" id="payment-error">
                            Payment failed. Please try again or contact <a href="mailto:payment@apispi.com">payment@apispi.com</a>.
                        </div>
                        <div class="payment-divider">or pay with email</div>
                        <a href="" id="paypalme-link" class="btn btn-secondary" style="text-align:center;">Pay via PayPal.me</a>
                    </div>
                </div>
            </div>

            <div id="checkout-success">
                <div class="success-icon">✅</div>
                <h2>Payment Successful!</h2>
                <p>
                    Thank you for subscribing to <strong id="success-agent"></strong>.<br>
                    A confirmation has been sent to your email. Our team will reach out within 24 hours to get you set up.
                </p>
                <p style="font-size:0.9rem;">Questions? Email us at <a href="mailto:payment@apispi.com">payment@apispi.com</a></p>
                <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1rem;">
                    <a href="{{ route('agents.index') }}" class="btn btn-primary">Browse More Agents</a>
                    <a href="{{ route('contact') }}" class="btn btn-secondary">Contact Support</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:payment@apispi.com">Payment Inquiries</a> | <a href="{{ route('contact') }}">Support</a></p>
@endsection

@push('scripts')
<script src="{{ asset('js/paypal-checkout.js') }}"></script>
@endpush
