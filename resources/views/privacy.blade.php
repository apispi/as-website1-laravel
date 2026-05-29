@extends('layouts.app')

@section('title', 'Privacy Policy - ApiSpi')

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
            <h1>Privacy <span class="gradient-text">Policy</span></h1>
            <p>How we collect, use, and protect your information.</p>
        </div>
    </section>

    <section class="policy-body">
        <div class="container">
            <div class="policy-container">

                <span class="policy-updated">Last updated: May 2026</span>

                <h2>1. Who We Are</h2>
                <p>ApiSpi ("we", "us", "our") is an AI agent platform operated by ApiSpi Pty Ltd, based in Australia. We provide AI-powered agents, training courses, and related services through <a href="{{ route('home') }}">apispi.com</a>. Questions about this policy can be directed to <a href="mailto:privacy@apispi.com">privacy@apispi.com</a>.</p>

                <h2>2. Information We Collect</h2>
                <p>We collect information you provide directly and information generated when you use our services:</p>
                <ul>
                    <li><strong>Account data</strong> — name, email address, and password when you register. If you sign in via Google or Microsoft, we receive your name, email, and profile picture from those providers.</li>
                    <li><strong>Payment data</strong> — payment processing is handled by Stripe. We do not store your full card details; we only receive a transaction reference and amount.</li>
                    <li><strong>Usage data</strong> — pages visited, features used, agent interactions, and activity logs to operate and improve the platform.</li>
                    <li><strong>Communications</strong> — messages you send via our contact form or support email.</li>
                    <li><strong>Technical data</strong> — IP address, browser type, device type, and cookies necessary to deliver a secure and functional service.</li>
                </ul>

                <h2>3. How We Use Your Information</h2>
                <p>We use your information to:</p>
                <ul>
                    <li>Create and manage your account and subscriptions</li>
                    <li>Provide and personalise our AI agent services</li>
                    <li>Process payments and send receipts</li>
                    <li>Respond to support enquiries</li>
                    <li>Send service-related emails (e.g. password reset, subscription confirmation)</li>
                    <li>Detect fraud, enforce our terms, and maintain platform security</li>
                    <li>Analyse usage trends to improve our products</li>
                </ul>
                <p>We do not sell your personal information to third parties.</p>

                <h2>4. Legal Basis for Processing (Australian &amp; International Users)</h2>
                <p>We process personal information under the <em>Privacy Act 1988</em> (Cth) and the Australian Privacy Principles (APPs). For users in the European Economic Area, our legal bases include contract performance, legitimate interests, and consent where applicable.</p>

                <h2>5. Cookies</h2>
                <p>We use essential session cookies to keep you logged in and to protect against cross-site request forgery. We do not use third-party advertising cookies. Google Analytics may set cookies to help us understand aggregate site usage. You can disable cookies in your browser settings, but some features may not function correctly.</p>

                <h2>6. Third-Party Services</h2>
                <p>We use the following third-party services that may process your data:</p>
                <ul>
                    <li><strong>Stripe</strong> — payment processing (<a href="https://stripe.com/privacy" target="_blank" rel="noopener">stripe.com/privacy</a>)</li>
                    <li><strong>Google Sign-In / Google Analytics</strong> — authentication and analytics (<a href="https://policies.google.com/privacy" target="_blank" rel="noopener">policies.google.com/privacy</a>)</li>
                    <li><strong>Microsoft Entra</strong> — optional authentication (<a href="https://privacy.microsoft.com" target="_blank" rel="noopener">privacy.microsoft.com</a>)</li>
                    <li><strong>Anthropic (Claude)</strong> — AI model powering our agents. Messages processed through agents may be sent to Anthropic's API in accordance with their <a href="https://www.anthropic.com/privacy" target="_blank" rel="noopener">privacy policy</a>.</li>
                </ul>

                <h2>7. Data Retention</h2>
                <p>We retain your account data for as long as your account is active or as needed to provide services. After account deletion, we may retain transaction records for up to 7 years for legal and accounting obligations. Usage logs are retained for up to 90 days.</p>

                <h2>8. Data Security</h2>
                <p>We use industry-standard measures including HTTPS encryption in transit, hashed passwords, and access controls to protect your data. No method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>

                <h2>9. Your Rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access the personal information we hold about you</li>
                    <li>Request correction of inaccurate information</li>
                    <li>Request deletion of your account and associated data</li>
                    <li>Opt out of non-essential communications</li>
                </ul>
                <p>To exercise these rights, email <a href="mailto:privacy@apispi.com">privacy@apispi.com</a>. We will respond within 30 days.</p>

                <h2>10. Children's Privacy</h2>
                <p>Our services are not directed at children under 16. We do not knowingly collect personal information from children. If you believe a child has provided us with their information, please contact us and we will delete it promptly.</p>

                <h2>11. Changes to This Policy</h2>
                <p>We may update this policy from time to time. We will notify registered users of material changes by email or in-app notice. Continued use of our services after changes take effect constitutes acceptance of the updated policy.</p>

                <h2>12. Contact</h2>
                <p>For privacy-related questions or complaints, contact us at <a href="mailto:privacy@apispi.com">privacy@apispi.com</a> or via our <a href="{{ route('contact') }}">contact form</a>.</p>

            </div>
        </div>
    </section>
@endsection
