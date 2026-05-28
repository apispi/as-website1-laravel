@extends('layouts.app')

@section('title', 'About ApiSpi - AI Agent Platform')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    About <span class="gradient-text">ApiSpi</span>
                </h1>
                <p class="hero-subtitle">
                    Democratizing Agentic AI for businesses of all sizes.
                </p>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section style="padding: 4rem 0;">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                <h2>Our Story</h2>
                <p>ApiSpi was founded in 2024 with a simple mission: make Agentic AI accessible to everyone. We recognized that while AI was becoming increasingly powerful, deploying and managing autonomous agents remained complex and expensive. Our platform simplifies this journey.</p>

                <p>Our founding team, consisting of AI researchers, software engineers, and business leaders, decided to build a platform that would simplify this process. We wanted to create a place where businesses could discover, deploy, and manage AI agents without needing deep technical expertise.</p>

                <p>Today, we're proud to serve over 10,000 organizations worldwide, from early-stage startups to Fortune 500 companies. Our platform powers millions of agent interactions daily, helping businesses automate tasks, improve customer experiences, and unlock new opportunities.</p>
            </div>
        </div>
    </section>

    <!-- Mission & Values -->
    <section style="background: rgba(217, 119, 6, 0.02); padding: 4rem 0; border-top: 1px solid rgba(217, 119, 6, 0.1); border-bottom: 1px solid rgba(217, 119, 6, 0.1);">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 3rem;">Our Mission & Values</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; max-width: 1000px; margin: 0 auto;">
                <div>
                    <h3 style="margin-bottom: 1rem;">Our Mission</h3>
                    <p>To democratize access to AI agents and enable organizations worldwide to harness the power of artificial intelligence to solve their most pressing challenges.</p>
                </div>
                <div>
                    <h3 style="margin-bottom: 1rem;">Our Vision</h3>
                    <p>A future where AI agents work seamlessly alongside humans, augmenting human capabilities, and enabling unprecedented innovation across all industries.</p>
                </div>
            </div>

            <div style="margin-top: 3rem; padding-top: 3rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
                <h3 style="text-align: center; margin-bottom: 2rem;">Core Values</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem;">🔐</div>
                        <h4>Trust</h4>
                        <p style="color: #6b7280;">We build trust through transparency, security, and reliability.</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem;">🚀</div>
                        <h4>Innovation</h4>
                        <p style="color: #6b7280;">We continuously innovate to push the boundaries of what's possible.</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem;">🤝</div>
                        <h4>Collaboration</h4>
                        <p style="color: #6b7280;">We believe in the power of collaboration and partnership.</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem;">♿</div>
                        <h4>Accessibility</h4>
                        <p style="color: #6b7280;">We're committed to making AI accessible to all.</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem;">⚖️</div>
                        <h4>Responsibility</h4>
                        <p style="color: #6b7280;">We're committed to responsible AI development.</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem;">🌱</div>
                        <h4>Growth</h4>
                        <p style="color: #6b7280;">We invest in continuous learning and improvement.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section style="padding: 4rem 0;">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 1rem;">Our Team</h2>
            <p class="section-subtitle" style="margin-bottom: 3rem;">A diverse group of experts passionate about AI and technology</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #D97706 0%, #EA580C 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">👨‍💼</div>
                    <h3 style="margin-bottom: 0.25rem;">Alex Chen</h3>
                    <p style="color: #FCD34D; font-weight: 600; margin-bottom: 0.75rem;">Founder & CEO</p>
                    <p style="color: #6b7280; font-size: 0.9rem;">Former AI researcher at leading tech companies with 10+ years of experience in machine learning.</p>
                </div>

                <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #D97706 0%, #EA580C 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">👩‍💻</div>
                    <h3 style="margin-bottom: 0.25rem;">Sarah Johnson</h3>
                    <p style="color: #FCD34D; font-weight: 600; margin-bottom: 0.75rem;">Chief Technology Officer</p>
                    <p style="color: #6b7280; font-size: 0.9rem;">Full-stack engineer specializing in distributed systems and cloud infrastructure.</p>
                </div>

                <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #D97706 0%, #EA580C 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">👨‍🔬</div>
                    <h3 style="margin-bottom: 0.25rem;">David Martinez</h3>
                    <p style="color: #FCD34D; font-weight: 600; margin-bottom: 0.75rem;">Head of Research</p>
                    <p style="color: #6b7280; font-size: 0.9rem;">PhD in Computer Science with focus on natural language processing and AI.</p>
                </div>

                <div style="background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.1); border-radius: 1rem; padding: 2rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #D97706 0%, #EA580C 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">👨‍💻</div>
                    <h3 style="margin-bottom: 0.25rem;">Terence Chia</h3>
                    <p style="color: #FCD34D; font-weight: 600; margin-bottom: 0.75rem;">Tech Lead</p>
                    <p style="color: #6b7280; font-size: 0.9rem;">Experienced software engineer driving the technical vision and architecture of the ApiSpi platform.</p>
                </div>
            </div>

            <p style="text-align: center; margin-top: 2rem; color: #6b7280;">...and a growing team of talented individuals passionate about AI and making a difference.</p>
        </div>
    </section>

    <!-- Milestones -->
    <section style="background: rgba(217, 119, 6, 0.02); padding: 4rem 0; border-top: 1px solid rgba(217, 119, 6, 0.1);">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 3rem;">Our Milestones</h2>
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="display: flex; gap: 2rem; margin-bottom: 2rem;">
                    <div style="min-width: 150px;"><h4 style="color: #FCD34D;">2024</h4></div>
                    <div style="padding-left: 2rem; border-left: 2px solid rgba(217, 119, 6, 0.3);">
                        <p><strong>ApiSpi Founded</strong><br>Launch of the ApiSpi platform with initial set of agents.</p>
                    </div>
                </div>
<div style="display: flex; gap: 2rem; margin-bottom: 2rem;">
                    <div style="min-width: 150px;"><h4 style="color: #FCD34D;">Mid 2025</h4></div>
                    <div style="padding-left: 2rem; border-left: 2px solid rgba(217, 119, 6, 0.3);">
                        <p><strong>200 Customers</strong><br>Reached 200 paying customers across multiple industries.</p>
                    </div>
                </div>
                <div style="display: flex; gap: 2rem; margin-bottom: 2rem;">
                    <div style="min-width: 150px;"><h4 style="color: #FCD34D;">Late 2025</h4></div>
                    <div style="padding-left: 2rem; border-left: 2px solid rgba(217, 119, 6, 0.3);">
                        <p><strong>API Launch</strong><br>Launched public API enabling custom agent development.</p>
                    </div>
                </div>
                <div style="display: flex; gap: 2rem;">
                    <div style="min-width: 150px;"><h4 style="color: #FCD34D;">2026</h4></div>
                    <div style="padding-left: 2rem; border-left: 2px solid rgba(217, 119, 6, 0.3);">
                        <p><strong>1,000+ Customers</strong><br>Growing to serve thousands of organizations worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Join Us in Shaping the Future of AI</h2>
            <p>Whether you're interested in our agents, partnering with us, or joining our team</p>
            <div class="cta-buttons">
                <a href="{{ route('agents.index') }}" class="btn btn-outline">Explore Agents</a>
                <a href="{{ route('partners') }}" class="btn btn-primary">Partner With Us</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Get in Touch</a>
            </div>
        </div>
    </section>
@endsection
