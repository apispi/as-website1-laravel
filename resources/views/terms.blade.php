@extends('layouts.app')

@section('title', 'Terms and Conditions - ApiSpi')

@push('head')
<style>
    .policy-hero { padding: 7rem 0 3rem; text-align: center; }
    .policy-hero h1 { font-size: 2.5rem; margin-bottom: 0.75rem; }
    .policy-hero p { color: var(--gray); font-size: 1rem; }
    .policy-body { padding: 3rem 0 6rem; }
    .policy-container { max-width: 760px; margin: 0 auto; }
    .policy-container h2 { font-size: 1.2rem; font-weight: 700; color: var(--accent); margin: 2.5rem 0 0.75rem; }
    .policy-container h2:first-child { margin-top: 0; }
    .policy-container p { color: var(--gray); line-height: 1.8; margin-bottom: 1rem; font-size: 0.95rem; }
    .policy-container ul { color: var(--gray); line-height: 1.8; margin: 0 0 1rem 1.25rem; font-size: 0.95rem; }
    .policy-container ul li { margin-bottom: 0.35rem; }
    .policy-container a { color: var(--accent); text-decoration: none; }
    .policy-container a:hover { text-decoration: underline; }
    .policy-updated { display: inline-block; font-size: 0.8rem; color: var(--gray); background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.2); border-radius: 9999px; padding: 0.25rem 0.9rem; margin-bottom: 2rem; }
</style>
@endpush

@section('content')
    <section class="policy-hero">
        <div class="container">
            <h1>Terms &amp; <span class="gradient-text">Conditions</span></h1>
            <p>Please read these terms carefully before using ApiSpi.</p>
        </div>
    </section>

    <section class="policy-body">
        <div class="container">
            <div class="policy-container">

                <span class="policy-updated">Last updated: May 2026</span>

                <h2>1. Agreement to Terms</h2>
                <p>By accessing or using the ApiSpi platform ("Service") operated by ApiSpi Pty Ltd ("we", "us", "our"), you agree to be bound by these Terms and Conditions. If you do not agree, do not use the Service.</p>

                <h2>2. Description of Service</h2>
                <p>ApiSpi provides access to AI-powered agents, training courses, and related tools via <a href="{{ route('home') }}">apispi.com</a>. Features available to you depend on your subscription plan. We reserve the right to modify, suspend, or discontinue any part of the Service at any time with reasonable notice.</p>

                <h2>3. Accounts</h2>
                <p>You must be at least 16 years old to create an account. You are responsible for:</p>
                <ul>
                    <li>Maintaining the confidentiality of your login credentials</li>
                    <li>All activity that occurs under your account</li>
                    <li>Notifying us immediately of any unauthorised access at <a href="mailto:support@apispi.com">support@apispi.com</a></li>
                </ul>
                <p>We may suspend or terminate accounts that violate these Terms.</p>

                <h2>4. Subscriptions and Payment</h2>
                <p>Certain features require a paid subscription. By subscribing:</p>
                <ul>
                    <li>You authorise us to charge your payment method on the billing cycle you select</li>
                    <li>Fees are quoted in Australian dollars (AUD) unless stated otherwise</li>
                    <li>Subscriptions renew automatically unless cancelled before the renewal date</li>
                    <li>Prices may change with 30 days' notice to active subscribers</li>
                </ul>
                <p>Payments are processed by Stripe. We do not store your full payment card details.</p>

                <h2>5. Refund Policy</h2>
                <p>We offer a 7-day refund for new subscriptions if you have not materially used the Service during that period. Training course purchases are non-refundable once course materials have been accessed. For refund requests, email <a href="mailto:support@apispi.com">support@apispi.com</a> within the eligible window.</p>

                <h2>6. Acceptable Use</h2>
                <p>You agree not to use the Service to:</p>
                <ul>
                    <li>Generate content that is unlawful, harmful, defamatory, or fraudulent</li>
                    <li>Infringe intellectual property rights of any third party</li>
                    <li>Attempt to reverse engineer, scrape, or extract our AI models or proprietary data</li>
                    <li>Circumvent access controls, rate limits, or security measures</li>
                    <li>Resell or redistribute the Service without our written permission</li>
                    <li>Use the Service for automated bulk operations that degrade platform performance for others</li>
                </ul>
                <p>We reserve the right to suspend access without notice for material breaches of this section.</p>

                <h2>7. AI-Generated Content</h2>
                <p>Our agents use large language models (including Anthropic's Claude) to generate responses. You acknowledge that:</p>
                <ul>
                    <li>AI-generated content may be inaccurate, incomplete, or unsuitable for your specific circumstances</li>
                    <li>You are responsible for reviewing and verifying all AI outputs before relying on them</li>
                    <li>Nothing produced by our agents constitutes legal, financial, medical, or professional advice</li>
                </ul>

                <h2>8. Intellectual Property</h2>
                <p>All platform software, branding, and original content are owned by ApiSpi Pty Ltd and protected by Australian and international copyright law. You retain ownership of content you input into the Service. By using the Service, you grant us a limited licence to process your inputs solely to deliver the Service to you.</p>
                <p>You may not use the ApiSpi name, logo, or trademarks without our prior written consent.</p>

                <h2>9. Data and Privacy</h2>
                <p>Your use of the Service is also governed by our <a href="{{ route('privacy') }}">Privacy Policy</a>, which is incorporated into these Terms by reference.</p>

                <h2>10. Third-Party Services</h2>
                <p>The Service integrates with third-party platforms including Stripe, Google, Microsoft, and Anthropic. Your use of those integrations is subject to their respective terms and policies. We are not responsible for the availability or conduct of third-party services.</p>

                <h2>11. Disclaimer of Warranties</h2>
                <p>The Service is provided "as is" and "as available" without warranties of any kind, express or implied, including fitness for a particular purpose or uninterrupted availability. We do not warrant that the Service will be error-free or that AI outputs will be accurate.</p>

                <h2>12. Limitation of Liability</h2>
                <p>To the maximum extent permitted by law, ApiSpi Pty Ltd will not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the Service, including loss of profits, data, or business opportunities. Our total aggregate liability for any claim is limited to the amount you paid us in the 3 months preceding the claim.</p>
                <p>Nothing in these Terms excludes liability that cannot be excluded under the <em>Australian Consumer Law</em>.</p>

                <h2>13. Indemnification</h2>
                <p>You agree to indemnify and hold harmless ApiSpi Pty Ltd, its directors, employees, and agents from any claims, losses, or expenses (including legal fees) arising from your misuse of the Service or breach of these Terms.</p>

                <h2>14. Termination</h2>
                <p>You may cancel your account at any time from your account settings or by contacting us. We may terminate or suspend access immediately for material breach of these Terms, non-payment, or if required by law. Upon termination, your right to use the Service ceases; provisions that by their nature survive termination (including liability, IP, and dispute resolution clauses) will remain in effect.</p>

                <h2>15. Governing Law</h2>
                <p>These Terms are governed by the laws of New South Wales, Australia. Any disputes will be subject to the exclusive jurisdiction of the courts of New South Wales, unless mandatory consumer protection laws in your jurisdiction provide otherwise.</p>

                <h2>16. Changes to These Terms</h2>
                <p>We may update these Terms from time to time. We will provide at least 14 days' notice of material changes via email or in-app notification. Continued use of the Service after changes take effect constitutes your acceptance of the updated Terms.</p>

                <h2>17. Contact</h2>
                <p>For questions about these Terms, contact us at <a href="mailto:legal@apispi.com">legal@apispi.com</a> or via our <a href="{{ route('contact') }}">contact form</a>.</p>

            </div>
        </div>
    </section>
@endsection
