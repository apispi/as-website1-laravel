@extends('layouts.app')

@section('title', 'ApiSpi - Build & Deploy AI Agents')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    The Future of AI is <span class="gradient-text">Autonomous</span>
                </h1>
                <p class="hero-subtitle">
                    Deploy intelligent agents, build autonomous workflows, and scale your AI infrastructure with ApiSpi.
                </p>
                <div class="hero-cta">
                    <a href="{{ route('agents.index') }}" class="btn btn-primary">Explore Agents</a>
                    <a href="{{ route('blog.index') }}" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="neural-network">
                    <div class="node"></div>
                    <div class="node"></div>
                    <div class="node"></div>
                    <div class="node"></div>
                    <div class="node"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Agents -->
    <section class="featured">
        <div class="container">
            <h2>Featured AI Agents</h2>
            <p class="section-subtitle">Handpicked agents ready to power your business</p>
            <div class="agents-grid">
                <div class="agent-card">
                    <div class="agent-badge">Popular</div>
                    <h3>Bid &amp; Tender Response</h3>
                    <p>Automates government RFQ/RFT responses, selection criteria, and capability statements.</p>
                    <div class="agent-stats">
                        <span>⭐ 4.9/5</span>
                        <span>340+ users</span>
                    </div>
                    <a href="{{ route('agents.show', 'bid-tender') }}" class="agent-link">View Details →</a>
                </div>

                <div class="agent-card featured-highlight">
                    <div class="agent-badge premium">Premium</div>
                    <h3>Security &amp; IRAP Readiness</h3>
                    <p>Guides organisations through Essential Eight, ISM, PSPF, IRAP, ISO 27001, and cloud security.</p>
                    <div class="agent-stats">
                        <span>⭐ 4.95/5</span>
                        <span>180+ users</span>
                    </div>
                    <a href="{{ route('agents.show', 'security-compliance') }}" class="agent-link">View Details →</a>
                </div>

                <div class="agent-card">
                    <div class="agent-badge">New</div>
                    <h3>Digital Avatar &amp; Executive Clone</h3>
                    <p>AI-powered professional avatars for executives, consultants, and trainers with voice cloning.</p>
                    <div class="agent-stats">
                        <span>⭐ 4.9/5</span>
                        <span>520+ users</span>
                    </div>
                    <a href="{{ route('agents.show', 'digital-avatar') }}" class="agent-link">View Details →</a>
                </div>
            </div>
            <div class="section-cta">
                <a href="{{ route('agents.index') }}" class="btn btn-primary">Browse All Agents</a>
            </div>
        </div>
    </section>

    <!-- Why ApiSpi -->
    <section class="benefits">
        <div class="container">
            <h2>Why Choose ApiSpi?</h2>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon"><span data-icon="rocket" data-size="28"></span></div>
                    <h3>Deploy in Minutes</h3>
                    <p>Get your AI agents running in production with zero infrastructure setup.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><span data-icon="gear" data-size="28"></span></div>
                    <h3>Fully Customizable</h3>
                    <p>Tailor agents to your specific needs with flexible configuration options.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><span data-icon="chart" data-size="28"></span></div>
                    <h3>Real-time Analytics</h3>
                    <p>Monitor performance, track usage, and optimize with detailed insights.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><span data-icon="shield" data-size="28"></span></div>
                    <h3>Enterprise Security</h3>
                    <p>Bank-grade security with encryption, compliance, and privacy controls.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><span data-icon="chat" data-size="28"></span></div>
                    <h3>Expert Support</h3>
                    <p>24/7 dedicated support from our AI and DevOps specialists.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><span data-icon="globe" data-size="28"></span></div>
                    <h3>Global Scale</h3>
                    <p>Run agents worldwide with automatic load balancing and redundancy.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Blog Posts -->
    <section class="latest-posts">
        <div class="container">
            <h2>Latest News</h2>
            <p class="section-subtitle">Insights, tutorials, and updates on AI agents</p>
            <div class="blog-grid">
                <article class="blog-card">
                    <div class="blog-date">May 15, 2026</div>
                    <h3><a href="{{ route('blog.show', 'getting-started-with-agents') }}">Getting Started with AI Agents: A Beginner's Guide</a></h3>
                    <p>Learn the fundamentals of AI agents and how to deploy your first agent in production.</p>
                    <div class="blog-meta">
                        <span class="category">Tutorial</span>
                        <span class="read-time">8 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 12, 2026</div>
                    <h3><a href="{{ route('blog.show', 'agent-best-practices') }}">Best Practices for Production AI Agents</a></h3>
                    <p>Discover proven strategies for deploying reliable, scalable agents at enterprise scale.</p>
                    <div class="blog-meta">
                        <span class="category">Guide</span>
                        <span class="read-time">12 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 8, 2026</div>
                    <h3><a href="{{ route('blog.show', 'future-of-agentic-ai') }}">The Future of Agentic AI: What's Next?</a></h3>
                    <p>Explore emerging trends and the future roadmap of autonomous AI agents.</p>
                    <div class="blog-meta">
                        <span class="category">Insights</span>
                        <span class="read-time">10 min read</span>
                    </div>
                </article>
            </div>
            <div class="section-cta">
                <a href="{{ route('blog.index') }}" class="btn btn-secondary">View All Articles</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Build with AI Agents?</h2>
            <p>Start exploring ApiSpi today and transform your business</p>
            <div class="cta-buttons">
                <a href="{{ route('agents.index') }}" class="btn btn-primary">Explore Agents</a>
                <a href="{{ route('contact') }}" class="btn btn-outline">Get in Touch</a>
            </div>
        </div>
    </section>
@endsection
