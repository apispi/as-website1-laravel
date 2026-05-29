@extends('layouts.app')

@section('title', 'Enterprise Architecture Agent - ApiSpi')

@section('content')
    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: inline-block; background: rgba(217, 119, 6, 0.2); border: 1px solid #D97706; color: #FCD34D; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Popular &bull; Architecture &amp; Strategy</span>
                    </div>
                    <h1>Enterprise Architecture Agent</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">Acts as a virtual enterprise and solution architect. Generates architecture options, decision records, integration patterns, and migration roadmaps for complex transformation programs.</p>
                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">4.85/5 (210+ reviews)</span>
                    </div>
                    <div class="agent-price">$599 / month</div>
                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent=Enterprise+Architecture&amount=599" class="btn btn-primary">Subscribe Now</a>
                    </div>
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Architecture options analysis</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Target-state model generation</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Integration pattern documentation</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Architecture decision records (ADRs)</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Business capability mapping</span></li>
                            <li style="display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Migration roadmap generation</span></li>
                        </ul>
                    </div>
                </div>
                <div class="agent-features">
                    <h2>Key Capabilities</h2>
                    <ul class="feature-list">
                        <li>Architecture options</li>
                        <li>Target-state models</li>
                        <li>Integration patterns</li>
                        <li>ADR generation</li>
                        <li>Capability mapping</li>
                        <li>Migration roadmaps</li>
                    </ul>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Who It's For</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">CIO offices, Digital transformation programs, ERP and cloud migration projects, Government ICT programs</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Target State Design</h3>
                        <p style="color: #6b7280;">Define your future-state architecture across application, data, infrastructure, and security domains.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Decision Records</h3>
                        <p style="color: #6b7280;">Generate Architecture Decision Records (ADRs) that document options considered, rationale, and trade-offs.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Migration Roadmaps</h3>
                        <p style="color: #6b7280;">Produce phased migration plans with dependencies, risks, and business capability alignment.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Pricing Plans</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Starter</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For project teams</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$349<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Enterprise+Architecture+Starter&amount=349" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ ADR generation</li>
                            <li style="margin-bottom: 0.5rem;">✓ Architecture options</li>
                            <li>✓ Email support</li>
                        </ul>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.05) 0%, rgba(217, 119, 6, 0.05) 100%); border: 2px solid #FCD34D; border-radius: 1rem; padding: 2rem; text-align: center; transform: scale(1.05);">
                        <div style="background: #FCD34D; color: #0D0B08; padding: 0.25rem 1rem; border-radius: 9999px; display: inline-block; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem;">POPULAR</div>
                        <h3 style="margin-bottom: 0.5rem;">Professional</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For transformation programs</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$599<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Enterprise+Architecture&amount=599" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Full target-state modelling</li>
                            <li style="margin-bottom: 0.5rem;">✓ Integration patterns</li>
                            <li style="margin-bottom: 0.5rem;">✓ Roadmaps</li>
                            <li>✓ Priority support</li>
                        </ul>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Enterprise</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For program-level architecture</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">Custom</div>
                        <a href="mailto:sales@apispi.com?subject=Enterprise%20Architecture%20Enterprise%20Inquiry" class="btn btn-secondary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Contact Sales</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Program-level architecture</li>
                            <li style="margin-bottom: 0.5rem;">✓ Custom frameworks (TOGAF, SABSA)</li>
                            <li style="margin-bottom: 0.5rem;">✓ Dedicated architect</li>
                            <li>✓ SLA</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Does it follow TOGAF or other frameworks?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Yes. The agent can align outputs to TOGAF, SABSA, and DTA frameworks, or operate framework-agnostic to suit your program.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can it work with our existing documentation?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Upload existing architecture diagrams, system lists, or strategy documents and the agent builds on them rather than starting from scratch.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Is it suitable for government ICT programs?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Absolutely. It is optimised for the documentation burden in government and regulated environments, producing audit-ready artefacts.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Build Better Architecture Faster</h2>
            <p>Reduce your architecture documentation burden with AI</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent=Enterprise+Architecture&amount=599" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Get Support</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
