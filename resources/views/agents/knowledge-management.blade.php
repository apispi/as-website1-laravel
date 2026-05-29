@extends('layouts.app')

@section('title', 'Knowledge Management & SOP Agent - ApiSpi')

@section('content')
    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: inline-block; background: rgba(217, 119, 6, 0.2); border: 1px solid #D97706; color: #FCD34D; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Popular &bull; Knowledge Management</span>
                    </div>
                    <h1>Knowledge Management &amp; SOP Agent</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">Turns scattered organisational knowledge into searchable operational intelligence. Converts PDFs, wikis, and documents into SOPs, onboarding material, and instant answers.</p>
                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">4.8/5 (290+ reviews)</span>
                    </div>
                    <div class="agent-price">$399 / month</div>
                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent=Knowledge+Management+SOP&amount=399" class="btn btn-primary">Subscribe Now</a>
                    </div>
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>PDF, wiki, and document ingestion</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Automatic SOP generation</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Internal policy Q&amp;A</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Onboarding material creation</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>SharePoint &amp; Confluence integration</span></li>
                            <li style="display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Knowledge gap identification</span></li>
                        </ul>
                    </div>
                </div>
                <div class="agent-features">
                    <h2>Key Capabilities</h2>
                    <ul class="feature-list">
                        <li>Document-to-knowledge conversion</li>
                        <li>SOP generation</li>
                        <li>Policy Q&amp;A</li>
                        <li>Onboarding content</li>
                        <li>SharePoint/Confluence integration</li>
                        <li>Gap identification</li>
                    </ul>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Who It's For</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">Government departments, MSPs and service desks, Operations teams, HR and onboarding teams</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">SOP Generation</h3>
                        <p style="color: #6b7280;">Upload process documentation and the agent produces structured, version-controlled Standard Operating Procedures automatically.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Internal Q&amp;A</h3>
                        <p style="color: #6b7280;">Staff ask questions in plain language and receive instant, cited answers drawn from your internal knowledge base.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Onboarding Packs</h3>
                        <p style="color: #6b7280;">Generate role-specific onboarding material from existing documentation in minutes, not weeks.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Pricing Plans</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Starter</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For small teams</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$199<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Knowledge+Management+SOP+Starter&amount=199" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Up to 50 documents</li>
                            <li style="margin-bottom: 0.5rem;">✓ SOP generation</li>
                            <li>✓ Email support</li>
                        </ul>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.05) 0%, rgba(217, 119, 6, 0.05) 100%); border: 2px solid #FCD34D; border-radius: 1rem; padding: 2rem; text-align: center; transform: scale(1.05);">
                        <div style="background: #FCD34D; color: #0D0B08; padding: 0.25rem 1rem; border-radius: 9999px; display: inline-block; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem;">POPULAR</div>
                        <h3 style="margin-bottom: 0.5rem;">Professional</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For growing organisations</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$399<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Knowledge+Management+SOP&amount=399" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Unlimited documents</li>
                            <li style="margin-bottom: 0.5rem;">✓ SharePoint/Confluence integration</li>
                            <li style="margin-bottom: 0.5rem;">✓ Q&amp;A interface</li>
                            <li>✓ Priority support</li>
                        </ul>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Enterprise</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For large organisations</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">Custom</div>
                        <a href="mailto:sales@apispi.com?subject=Knowledge%20Management%20Enterprise%20Inquiry" class="btn btn-secondary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Contact Sales</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Dedicated instance</li>
                            <li style="margin-bottom: 0.5rem;">✓ Custom integrations</li>
                            <li style="margin-bottom: 0.5rem;">✓ Audit logging</li>
                            <li>✓ SLA</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">What document formats does it support?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">PDF, Word, Excel, PowerPoint, Markdown, HTML, SharePoint pages, Confluence spaces, and plain text.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">How does the Q&amp;A stay accurate?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Answers are grounded in your uploaded documents with citations. The agent does not hallucinate — if the answer isn't in the knowledge base, it says so.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can it stay up to date as documents change?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Yes. Connect it to your SharePoint or Confluence and it re-indexes on document change events automatically.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Stop Losing Institutional Knowledge</h2>
            <p>Turn your documents into operational intelligence</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent=Knowledge+Management+SOP&amount=399" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Get Support</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
