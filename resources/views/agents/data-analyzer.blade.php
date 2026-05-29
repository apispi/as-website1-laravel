@extends('layouts.app')

@section('title', 'Data Analyzer Pro Agent - ApiSpi')

@section('content')
    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: inline-block; background: rgba(217, 119, 6, 0.2); border: 1px solid #EA580C; color: #EA580C; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Premium &bull; Data Analysis</span>
                    </div>
                    <h1>Data Analyzer Pro</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">Advanced data analysis, visualization, and insights generation from any dataset. Uncover patterns and make data-driven decisions.</p>
                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">4.95/5 (2,100+ reviews)</span>
                    </div>
                    <div class="agent-price">$79 / month</div>
                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent=Data+Analyzer+Pro&amount=79" class="btn btn-primary">Subscribe Now</a>
                    </div>
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217, 119, 6, 0.05); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Unlimited data analysis</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Advanced visualization tools</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Predictive analytics</span></li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Real-time alerts</span></li>
                            <li style="display: flex; align-items: center; gap: 0.75rem;"><span style="color: #00d97e; font-weight: 700;">✓</span><span>Premium support</span></li>
                        </ul>
                    </div>
                </div>
                <div class="agent-features">
                    <h2>Key Features</h2>
                    <ul class="feature-list">
                        <li>Statistical Analysis</li>
                        <li>Data Visualization</li>
                        <li>Trend Detection</li>
                        <li>Anomaly Detection</li>
                        <li>Predictive Modeling</li>
                        <li>Report Generation</li>
                        <li>Multi-format Import</li>
                        <li>Real-time Dashboards</li>
                    </ul>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Trusted by</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">Used by 2,100+ data analysts, business intelligence teams, and enterprises worldwide.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">📊 Business Intelligence</h3>
                        <p style="color: #6b7280;">Analyze business metrics and identify growth opportunities.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">📈 Financial Analysis</h3>
                        <p style="color: #6b7280;">Track financial trends and forecast future performance.</p>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">🔍 Research &amp; Insights</h3>
                        <p style="color: #6b7280;">Deep dive analysis and comprehensive research reports.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Pricing Plans</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Basic</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For analysts</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$49<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Data+Analyzer+Basic&amount=49" class="btn btn-primary" style="display: block; width: 100%; text-align: center; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ 100 MB storage</li>
                            <li style="margin-bottom: 0.5rem;">✓ Basic analysis</li>
                            <li>✓ Email support</li>
                        </ul>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.05) 0%, rgba(217, 119, 6, 0.05) 100%); border: 2px solid #EA580C; border-radius: 1rem; padding: 2rem; text-align: center; transform: scale(1.05);">
                        <div style="background: #EA580C; color: #ffffff; padding: 0.25rem 1rem; border-radius: 9999px; display: inline-block; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem;">MOST POPULAR</div>
                        <h3 style="margin-bottom: 0.5rem;">Professional</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For teams</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">$79<span style="font-size: 1rem; color: #6b7280;">/month</span></div>
                        <a href="{{ route('checkout') }}?agent=Data+Analyzer+Pro&amount=79" class="btn btn-primary" style="display: block; width: 100%; text-align: center; margin-bottom: 1rem;">Get Started</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ 1 GB storage</li>
                            <li style="margin-bottom: 0.5rem;">✓ Advanced analytics</li>
                            <li style="margin-bottom: 0.5rem;">✓ Priority support</li>
                            <li>✓ Team collaboration</li>
                        </ul>
                    </div>
                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                        <h3 style="margin-bottom: 0.5rem;">Enterprise</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">For large organizations</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">Custom</div>
                        <a href="mailto:sales@apispi.com?subject=Data+Analyzer+Enterprise+Inquiry" class="btn btn-secondary" style="display: block; width: 100%; text-align: center; margin-bottom: 1rem;">Contact Sales</a>
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            <li style="margin-bottom: 0.5rem;">✓ Unlimited storage</li>
                            <li style="margin-bottom: 0.5rem;">✓ Custom integrations</li>
                            <li style="margin-bottom: 0.5rem;">✓ Dedicated support</li>
                            <li>✓ White-label options</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">What data formats are supported?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">We support CSV, Excel, JSON, SQL databases, and many more formats. You can even import directly from APIs.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Is my data secure?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Yes, all data is encrypted in transit and at rest. We comply with GDPR, HIPAA, and SOC 2 standards.</p>
                    </details>
                    <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Can I export my reports?</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">Absolutely! Export as PDF, PNG, SVG, or interactive HTML. Share reports directly with your team.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Start Analyzing Data Like Never Before</h2>
            <p>Unlock insights with advanced AI-powered analytics</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent=Data+Analyzer+Pro&amount=79" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Request Demo</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
