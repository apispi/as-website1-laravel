@extends('layouts.app')

@section('title', 'Cyber Incident & Threat Intelligence Agent - ApiSpi')

@section('content')
    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: inline-block; background: rgba(217, 119, 6, 0.2); border: 1px solid #D97706; color: #FCD34D; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Premium &bull; Cyber Operations</span>
                    </div>
                    <h1>Cyber Incident &amp; Threat Intelligence Agent</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">Acts as a first-line cyber operations assistant for SOC teams, MSPs, and security consultancies. Summarises logs, correlates threat intel, triages incidents, and drafts executive reports.</p>
                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">4.9/5 (150+ reviews)</span>
                    </div>
                    <div class="agent-price">$699 / month</div>
                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent=Cyber+Incident+Threat+Intel&amount=699" class="btn btn-primary">Subscribe Now</a>
                    </div>
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Log summarisation and analysis</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Threat intelligence correlation</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Incident triage and classification</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>IOC extraction</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Executive incident reports</span></li>
                            <li style="display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Playbook recommendations</span></li>
                        </ul>
                    </div>
                    <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 0.75rem;">Coming Soon</h4>
                        <p style="color: #6b7280; font-size: 0.95rem;">Connect to SIEMs &mdash; Sentinel, Defender, Splunk, CrowdStrike integrations coming soon.</p>
                    </div>
                </div>
                <div class="agent-features">
                    <h2>Key Capabilities</h2>
                    <ul class="feature-list">
                        <li>Log summarisation</li>
                        <li>Threat intel correlation</li>
                        <li>Incident triage</li>
                        <li>IOC extraction</li>
                        <li>Executive reports</li>
                        <li>Playbook recommendations</li>
                    </ul>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Who It's For</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">SOC teams and analysts, MSPs with security offerings, Small government agencies, Security consultancies</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Incident Triage</h3>
                        <p style="color: #6b7280;">Feed in raw logs or alert data and receive a structured triage report with severity classification, affected assets, and recommended containment actions.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Threat Intel Correlation</h3>
                        <p style="color: #6b7280;">Cross-reference IOCs against threat feeds and produce enriched intelligence summaries for analysts.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Executive Reporting</h3>
                        <p style="color: #6b7280;">Automatically draft non-technical executive incident reports from technical findings, ready to brief leadership within minutes.</p>
                    </div>
                </div>
            </div>
                        <a href="{{ route('checkout') }}?agent=Cyber+Incident+Threat+Intel+SME&amount=399" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Incident triage</li>
                            <li style="margin-bottom: 0.5rem;">✓ IOC extraction</li>
                            <li style="margin-bottom: 0.5rem;">✓ Executive reports</li>
                            <li>✓ Email support</li>
                        </ul>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.05) 0%, rgba(217, 119, 6, 0.05) 100%); border: 2px solid #FCD34D; border-radius: 1rem; padding: 2rem; text-align: center; transform: scale(1.05);">
                        <div style="background: #FCD34D; color: #0D0B08; padding: 0.25rem 1rem; border-radius: 9999px; display: inline-block; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem;">POPULAR</div>
                        <h3 style="margin-bottom: 0.5rem;">Professional</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For SOC teams and MSPs</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$699<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Cyber+Incident+Threat+Intel&amount=699" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Full threat intel correlation</li>
                            <li style="margin-bottom: 0.5rem;">✓ Log analysis</li>
                            <li style="margin-bottom: 0.5rem;">✓ Playbooks</li>
                            <li>✓ Priority support</li>
                        </ul>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Enterprise</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For large organisations</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">Custom</div>
                        <a href="mailto:sales@apispi.com?subject=Cyber%20Incident%20Enterprise%20Inquiry" class="btn btn-secondary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Contact Sales</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ SIEM integration</li>
                            <li style="margin-bottom: 0.5rem;">✓ Dedicated instance</li>
                            <li style="margin-bottom: 0.5rem;">✓ Custom playbooks</li>
                            <li>✓ SLA</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Does it connect to our SIEM?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">SIEM integrations (Sentinel, Splunk, Defender, CrowdStrike) are on the roadmap. Currently, paste or upload log excerpts and alert data directly.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">How does it handle sensitive incident data?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">All data is processed in an isolated environment. Nothing is retained after the session. Suitable for protected and sensitive incident data.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can it generate playbooks from scratch?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Yes. Describe the incident type and environment and the agent generates a structured playbook with detection, containment, eradication, and recovery steps.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Respond Faster. Report Clearer.</h2>
            <p>AI-powered cyber operations for security teams</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent=Cyber+Incident+Threat+Intel&amount=699" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Get Support</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
