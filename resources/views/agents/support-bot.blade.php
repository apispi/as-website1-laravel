@extends('layouts.app')

@section('title', 'Customer Support Bot Agent - ApiSpi')

@section('content')
    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: inline-block; background: rgba(217, 119, 6, 0.2); border: 1px solid #D97706; color: #FCD34D; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">New &bull; Customer Support</span>
                    </div>
                    <h1>Customer Support Bot</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">24/7 intelligent customer support with natural language understanding. Resolve issues faster and improve customer satisfaction.</p>
                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">4.8/5 (890+ reviews)</span>
                    </div>
                    <div class="agent-price">$49 / month</div>
                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent=Customer+Support+Bot&amount=49" class="btn btn-primary">Subscribe Now</a>
                    </div>
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Unlimited conversations</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Multi-language support</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Integration with ticketing systems</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Sentiment analysis</span></li>
                            <li style="display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>24/7 monitoring</span></li>
                        </ul>
                    </div>
                </div>
                <div class="agent-features">
                    <h2>Key Features</h2>
                    <ul class="feature-list">
                        <li>Natural Language Processing</li>
                        <li>Multi-Channel Support</li>
                        <li>Sentiment Detection</li>
                        <li>Human Handoff</li>
                        <li>Knowledge Base Integration</li>
                        <li>Conversation History</li>
                        <li>Issue Classification</li>
                        <li>Performance Analytics</li>
                    </ul>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Trusted by</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">Used by 890+ companies to handle over 10 million customer interactions monthly.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">💬 Chat Support</h3>
                        <p style="color: #6b7280;">Instant responses to customer inquiries on web and mobile.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">📞 Helpdesk Integration</h3>
                        <p style="color: #6b7280;">Seamless integration with your existing helpdesk platform.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">🎯 Lead Qualification</h3>
                        <p style="color: #6b7280;">Qualify leads and route them to the right sales teams.</p>
                    </div>
                </div>
            </div>
                        <a href="{{ route('checkout') }}?agent=Support+Bot+Starter&amount=29" class="btn btn-primary" style="display: block; width: 100%; text-align: center; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ 1,000 conversations/month</li>
                            <li style="margin-bottom: 0.5rem;">✓ Email support</li>
                            <li>✓ Basic analytics</li>
                        </ul>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.05) 0%, rgba(217, 119, 6, 0.05) 100%); border: 2px solid #FCD34D; border-radius: 1rem; padding: 2rem; text-align: center; transform: scale(1.05);">
                        <div style="background: #FCD34D; color: #0D0B08; padding: 0.25rem 1rem; border-radius: 9999px; display: inline-block; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem;">RECOMMENDED</div>
                        <h3 style="margin-bottom: 0.5rem;">Professional</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For growing teams</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$49<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Customer+Support+Bot&amount=49" class="btn btn-primary" style="display: block; width: 100%; text-align: center; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Unlimited conversations</li>
                            <li style="margin-bottom: 0.5rem;">✓ Priority support</li>
                            <li style="margin-bottom: 0.5rem;">✓ Advanced analytics</li>
                            <li>✓ Integrations</li>
                        </ul>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Enterprise</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For large organizations</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">Custom</div>
                        <a href="mailto:sales@apispi.com?subject=Support+Bot+Enterprise+Inquiry" class="btn btn-secondary" style="display: block; width: 100%; text-align: center; margin-bottom: 1rem;">Contact Sales</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Custom volume</li>
                            <li style="margin-bottom: 0.5rem;">✓ Custom integrations</li>
                            <li style="margin-bottom: 0.5rem;">✓ Dedicated support</li>
                            <li>✓ SLA guarantee</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">How long does setup take?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Setup typically takes 30 minutes. You can customize the bot to match your brand and connect it to your existing systems.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can it handle complex issues?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Yes, our bot can handle complex conversations and automatically escalates to human agents when needed.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">What languages are supported?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">We support 50+ languages. The bot can even detect and switch between languages in the same conversation.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Improve Customer Support Today</h2>
            <p>Reduce response times and increase satisfaction</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent=Customer+Support+Bot&amount=49" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Schedule Demo</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
