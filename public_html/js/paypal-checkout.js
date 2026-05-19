(function () {
    const params = new URLSearchParams(window.location.search);
    const agentName = params.get('agent') || 'ApiSpi Agent';
    const amount = parseFloat(params.get('amount') || '0').toFixed(2);
    const returnUrl = params.get('return') || 'agents/';

    // Populate order summary
    document.getElementById('summary-agent').textContent = agentName;
    document.getElementById('summary-label').textContent = agentName + ' plan';
    document.getElementById('summary-price').textContent = '$' + amount;
    document.getElementById('summary-total').textContent = '$' + amount;
    document.getElementById('note-amount').textContent = '$' + amount;
    document.getElementById('paypalme-link').href =
        'https://www.paypal.com/paypalme/glitchdata/' + amount;

    document.getElementById('back-link') &&
        (document.getElementById('back-link').href = returnUrl);

    // Guard: if PayPal SDK failed to load (e.g., invalid client ID in sandbox)
    if (typeof paypal === 'undefined') {
        document.getElementById('paypal-button-container').innerHTML =
            '<p style="color:#ff3b30;font-size:0.9rem;">PayPal SDK could not load. Please use the PayPal.me link below or contact <a href="mailto:payment@apispi.com">payment@apispi.com</a>.</p>';
        return;
    }

    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'rect',
            label: 'subscribe',
            height: 45
        },

        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    description: agentName + ' — Monthly Subscription',
                    amount: {
                        currency_code: 'USD',
                        value: amount
                    },
                    payee: {
                        email_address: 'payment@apispi.com'
                    }
                }],
                application_context: {
                    brand_name: 'ApiSpi',
                    shipping_preference: 'NO_SHIPPING'
                }
            });
        },

        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
                document.getElementById('checkout-form').style.display = 'none';
                document.getElementById('success-agent').textContent = agentName;
                document.getElementById('checkout-success').style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        },

        onError: function (err) {
            console.error('PayPal error:', err);
            const errorEl = document.getElementById('payment-error');
            errorEl.style.display = 'block';
            errorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },

        onCancel: function () {
            // User cancelled — no action needed
        }
    }).render('#paypal-button-container');
})();
