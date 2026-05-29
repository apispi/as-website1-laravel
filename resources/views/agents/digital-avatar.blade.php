@extends('layouts.app')

@section('title', 'Digital Avatar & Executive Clone Agent - ApiSpi')

@section('content')
    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: inline-block; background: rgba(217, 119, 6, 0.2); border: 1px solid #D97706; color: #FCD34D; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">New &bull; Communications &amp; Avatar</span>
                    </div>
                    <h1>Digital Avatar &amp; Executive Clone Agent</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">Creates AI-powered professional avatars for executives, consultants, trainers, and public-facing staff. Enterprise-grade, secure, and multilingual.</p>
                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">4.9/5 (520+ reviews)</span>
                    </div>
                    <div class="agent-price">$800</div>
                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent=Digital+Avatar&amount=800" class="btn btn-primary">Subscribe Now</a>
                    </div>
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Talking avatar generation</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Voice cloning (licensed)</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>FAQ and Q&amp;A handling</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Sales and demo presentations</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Training content delivery</span></li>
                            <li style="display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Multilingual output</span></li>
                        </ul>
                    </div>
                    <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 0.75rem;">Why Choose This Agent</h4>
                        <p style="color: #6b7280; font-size: 0.95rem;">Professional-grade secure avatars for enterprise and government</p>
                    </div>
                </div>
                <div class="agent-features">
                    <h2>Key Capabilities</h2>
                    <ul class="feature-list">
                        <li>Talking avatar generation</li>
                        <li>Voice cloning</li>
                        <li>FAQ handling</li>
                        <li>Sales/demo presentations</li>
                        <li>Training delivery</li>
                        <li>Multilingual delivery</li>
                    </ul>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Who It's For</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">Consultants and professional services firms, Corporate trainers, Influencers and content creators, Government communications teams</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Executive Communications</h3>
                        <p style="color: #6b7280;">Scale your executive presence with AI avatars that deliver consistent messages across channels without scheduling constraints.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Training Delivery</h3>
                        <p style="color: #6b7280;">Convert PowerPoint decks and scripts into talking-head training videos in minutes. Supports multilingual delivery.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">Sales Presentations</h3>
                        <p style="color: #6b7280;">Create personalised demo videos for prospects at scale, customised by industry and use case.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Pricing Plans</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Starter</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For individuals</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$149<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Digital+Avatar+Starter&amount=149" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ 10 avatar videos/month</li>
                            <li style="margin-bottom: 0.5rem;">✓ Single voice</li>
                            <li>✓ Email support</li>
                        </ul>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.05) 0%, rgba(217, 119, 6, 0.05) 100%); border: 2px solid #FCD34D; border-radius: 1rem; padding: 2rem; text-align: center; transform: scale(1.05);">
                        <div style="background: #FCD34D; color: #0D0B08; padding: 0.25rem 1rem; border-radius: 9999px; display: inline-block; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem;">POPULAR</div>
                        <h3 style="margin-bottom: 0.5rem;">Professional</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For teams and businesses</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$800</div>
                        <a href="{{ route('checkout') }}?agent=Digital+Avatar&amount=800" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ 50 videos/month</li>
                            <li style="margin-bottom: 0.5rem;">✓ Voice cloning</li>
                            <li style="margin-bottom: 0.5rem;">✓ Multilingual</li>
                            <li>✓ Priority support</li>
                        </ul>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Enterprise</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For large organisations</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">Custom</div>
                        <a href="mailto:sales@apispi.com?subject=Digital%20Avatar%20Enterprise%20Inquiry" class="btn btn-secondary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Contact Sales</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Unlimited videos</li>
                            <li style="margin-bottom: 0.5rem;">✓ Custom avatar build</li>
                            <li style="margin-bottom: 0.5rem;">✓ Secure hosting</li>
                            <li>✓ SLA</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">How realistic is the avatar?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Avatars are indistinguishable from recorded video in most contexts. You can use your own likeness or choose from a library of professional presenters.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Is voice cloning legal and ethical?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Yes — all voice cloning requires explicit consent from the voice owner. We provide a consent and licensing workflow as part of onboarding.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can it handle live Q&amp;A?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">The agent can be configured to answer pre-defined FAQs in real time via a chat-backed avatar interface.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Scale Your Presence with AI</h2>
            <p>Deploy professional avatars for enterprise and government</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent=Digital+Avatar&amount=800" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Get Support</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
