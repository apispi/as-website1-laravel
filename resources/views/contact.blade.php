@extends('layouts.app')

@section('title', 'Contact ApiSpi - Get in Touch')

@push('head')
<style>
    input, textarea, select { transition: all 0.3s ease; }
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #D97706;
        box-shadow: 0 0 20px rgba(217, 119, 6, 0.3);
        background: rgba(28, 24, 16, 0.8);
    }
    input::placeholder { color: rgba(217, 119, 6, 0.5); }
    button[type="submit"]:hover { transform: translateY(-2px); }
    details summary:hover { color: #FCD34D; }
    details[open] summary { color: #FCD34D; }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Get in <span class="gradient-text">Touch</span>
                </h1>
                <p class="hero-subtitle">
                    Have questions? We'd love to hear from you. Let's talk about how ApiSpi can help your business.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section style="padding: 4rem 0;">
        <div class="container">
            @if(session('success'))
                <div style="max-width: 600px; margin: 0 auto 2rem; padding: 1rem 1.5rem; background: rgba(0,217,126,0.1); border: 1px solid rgba(0,217,126,0.3); border-radius: 0.75rem; color: #00d97e;">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div style="max-width: 600px; margin: 0 auto 2rem; padding: 1rem 1.5rem; background: rgba(255,59,48,0.1); border: 1px solid rgba(255,59,48,0.3); border-radius: 0.75rem; color: #ff3b30;">
                    <ul style="list-style: none; margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; max-width: 1200px; margin: 0 auto;">
                <!-- Contact Form -->
                <div>
                    <h2 style="margin-bottom: 2rem;">Send us a Message</h2>
                    <form id="contactForm" method="POST" action="{{ route('contact.send') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @csrf
                        <div>
                            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Name</label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}" style="width: 100%; padding: 0.75rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; font-size: 1rem;">
                        </div>
                        <div>
                            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" style="width: 100%; padding: 0.75rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; font-size: 1rem;">
                        </div>
                        <div>
                            <label for="company" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Company</label>
                            <input type="text" id="company" name="company" value="{{ old('company') }}" style="width: 100%; padding: 0.75rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; font-size: 1rem;">
                        </div>
                        <div>
                            <label for="subject" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Subject</label>
                            <select id="subject" name="subject" required style="width: 100%; padding: 0.75rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; font-size: 1rem; cursor: pointer;">
                                <option value="">Select a subject...</option>
                                <option value="support" @selected(old('subject') === 'support')>Product Support</option>
                                <option value="sales" @selected(old('subject') === 'sales')>Sales Inquiry</option>
                                <option value="partnership" @selected(old('subject') === 'partnership')>Partnership</option>
                                <option value="feedback" @selected(old('subject') === 'feedback')>Feedback</option>
                                <option value="other" @selected(old('subject') === 'other')>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Message</label>
                            <textarea id="message" name="message" required rows="6" style="width: 100%; padding: 0.75rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; font-size: 1rem; font-family: inherit; resize: vertical;">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 style="margin-bottom: 2rem;">Contact Information</h2>

                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: #FCD34D;">📧 Email</h3>
                        <p style="color: #e5e7eb; margin-bottom: 0.5rem;"><strong>General Inquiries:</strong></p>
                        <p style="color: #6b7280; margin-bottom: 1rem;"><a href="mailto:sales@apispi.com" style="color: #FCD34D; text-decoration: none;">sales@apispi.com</a></p>
                        <p style="color: #e5e7eb; margin-bottom: 0.5rem;"><strong>Sales:</strong></p>
                        <p style="color: #6b7280; margin-bottom: 1rem;"><a href="mailto:sales@apispi.com" style="color: #FCD34D; text-decoration: none;">sales@apispi.com</a></p>
                        <p style="color: #e5e7eb; margin-bottom: 0.5rem;"><strong>Payments:</strong></p>
                        <p style="color: #6b7280;"><a href="mailto:payment@apispi.com" style="color: #FCD34D; text-decoration: none;">payment@apispi.com</a></p>
                    </div>

                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: #FCD34D;">🌍 Office</h3>
                        <p style="color: #e5e7eb;">ApiSpi Inc.<br>San Francisco, CA 94105<br>United States</p>
                    </div>

                    <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: #FCD34D;">⏰ Support Hours</h3>
                        <p style="color: #e5e7eb;"><strong>Monday - Friday:</strong> 9 AM - 6 PM PST<br><strong>Saturday - Sunday:</strong> Limited support</p>
                        <p style="color: #6b7280; margin-top: 1rem; font-size: 0.9rem;">For urgent issues, premium customers have 24/7 support.</p>
                    </div>

                    <div style="margin-top: 2rem;">
                        <h3 style="margin-bottom: 1rem;">Follow Us</h3>
                        <div style="display: flex; gap: 1rem;">
                            <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(217, 119, 6, 0.2); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 50%; color: #FCD34D; font-size: 1.2rem;">𝕏</a>
                            <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(217, 119, 6, 0.2); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 50%; color: #FCD34D; font-size: 1.2rem;">f</a>
                            <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(217, 119, 6, 0.2); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 50%; color: #FCD34D; font-size: 1.2rem;">in</a>
                            <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(217, 119, 6, 0.2); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 50%; color: #FCD34D; font-size: 1.2rem;">D</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section style="background: rgba(217, 119, 6, 0.02); padding: 4rem 0; border-top: 1px solid rgba(217, 119, 6, 0.1); border-bottom: 1px solid rgba(217, 119, 6, 0.1);">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 3rem;">Frequently Asked Questions</h2>
            <div style="max-width: 800px; margin: 0 auto;">
                <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                    <summary style="font-weight: 600; cursor: pointer;">How long does it take to get a response?</summary>
                    <p style="margin-top: 1rem; color: #6b7280;">We typically respond to inquiries within 24 hours during business days. Premium customers receive priority support.</p>
                </details>
                <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                    <summary style="font-weight: 600; cursor: pointer;">Can we schedule a demo?</summary>
                    <p style="margin-top: 1rem; color: #6b7280;">Yes! Please email sales@apispi.com or use our contact form. We're happy to schedule demos for interested organizations.</p>
                </details>
                <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                    <summary style="font-weight: 600; cursor: pointer;">Do you offer custom solutions?</summary>
                    <p style="margin-top: 1rem; color: #6b7280;">Absolutely! We work with enterprises on custom agent development and integration. Contact our sales team to discuss your needs.</p>
                </details>
                <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                    <summary style="font-weight: 600; cursor: pointer;">Are you hiring?</summary>
                    <p style="margin-top: 1rem; color: #6b7280;">Yes! We're always looking for talented engineers, researchers, and product experts. Send your resume to careers@apispi.com.</p>
                </details>
                <details style="margin-bottom: 1rem; padding: 1.5rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; cursor: pointer;">
                    <summary style="font-weight: 600; cursor: pointer;">What payment methods do you accept?</summary>
                    <p style="margin-top: 1rem; color: #6b7280;">We accept all major credit cards, PayPal, wire transfers, and can set up custom invoicing for enterprise customers.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of organizations using ApiSpi</p>
            <div class="cta-buttons">
                <a href="{{ route('agents.index') }}" class="btn btn-outline">Browse Agents</a>
                <a href="#contactForm" class="btn btn-secondary">Send Message</a>
            </div>
        </div>
    </section>
@endsection
