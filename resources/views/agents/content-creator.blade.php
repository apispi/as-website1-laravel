@extends('layouts.app')

@section('title', 'Content Creator Agent - ApiSpi')

@section('content')
    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: inline-block; background: rgba(217, 119, 6, 0.2); border: 1px solid #D97706; color: #FCD34D; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Popular &bull; Content Creation</span>
                    </div>
                    <h1>Content Creator Agent</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">Autonomous content generation for blogs, social media, and marketing campaigns. Powered by advanced language models.</p>
                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">4.9/5 (1,250+ reviews)</span>
                    </div>
                    <div class="agent-price">$29 / month</div>
                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent=Content+Creator&amount=29" class="btn btn-primary">Subscribe Now</a>
                    </div>
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>50 content pieces/month</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Multiple content formats (blog, social, email)</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>AI-powered SEO optimization</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Brand voice customization</span></li>
                            <li style="display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>24/7 customer support</span></li>
                        </ul>
                    </div>
                </div>
                <div class="agent-features">
                    <h2>Key Features</h2>
                    <ul class="feature-list">
                        <li>Multi-format Generation</li>
                        <li>SEO Optimization</li>
                        <li>Brand Voice Learning</li>
                        <li>Tone &amp; Style Control</li>
                        <li>Plagiarism Detection</li>
                        <li>Real-time Collaboration</li>
                        <li>API Integration</li>
                        <li>Analytics Dashboard</li>
                    </ul>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Trusted by</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">Used by 1,250+ content creators, marketers, and agencies worldwide.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">📝 Blog Content</h3>
                        <p style="color: #6b7280;">Generate high-quality blog posts with SEO optimization and topic research.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">📱 Social Media</h3>
                        <p style="color: #6b7280;">Create engaging posts for Twitter, LinkedIn, Instagram, and more.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">📧 Email Campaigns</h3>
                        <p style="color: #6b7280;">Write compelling newsletters and marketing emails that convert.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can I cancel my subscription anytime?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Yes, you can cancel your subscription at any time. No questions asked. You'll have access until the end of your billing cycle.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can I upgrade or downgrade my plan?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Absolutely! You can change your plan at any time. Upgrades are prorated, and downgrades take effect at the start of your next billing cycle.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Ready to Transform Your Content?</h2>
            <p>Start creating with AI today</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent=Content+Creator&amount=29" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Get Support</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
