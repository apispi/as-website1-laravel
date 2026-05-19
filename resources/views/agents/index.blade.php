@extends('layouts.app')

@section('title', 'AI Agents Marketplace - ApiSpi')

@push('head')
<style>
    .agents-search { margin-top: 2rem; }
    .search-box { width: 100%; max-width: 500px; padding: 1rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.3); border-radius: 0.75rem; color: white; font-size: 1rem; transition: all 0.3s ease; }
    .search-box:focus { outline: none; border-color: #D97706; box-shadow: 0 0 20px rgba(217, 119, 6, 0.3); background: rgba(28, 24, 16, 0.8); }
    .search-box::placeholder { color: rgba(217, 119, 6, 0.5); }
    .filters { display: flex; gap: 2rem; flex-wrap: wrap; align-items: center; }
    .filter-group { display: flex; align-items: center; gap: 0.75rem; }
    .filter-group label { font-weight: 600; color: #e5e7eb; }
    .filter-group select { padding: 0.5rem 1rem; background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 0.5rem; color: white; cursor: pointer; transition: all 0.3s ease; }
    .filter-group select:hover, .filter-group select:focus { border-color: #D97706; box-shadow: 0 0 15px rgba(217, 119, 6, 0.2); }
    .filter-group select:focus { outline: none; }
    .filter-group select option { background: #1C1810; color: white; }
</style>
@endpush

@section('content')
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    AI Agents <span class="gradient-text">Marketplace</span>
                </h1>
                <p class="hero-subtitle">
                    Discover and deploy production-ready AI agents. Powered by ApiSpi's agentic AI expertise.
                </p>
                <div class="agents-search">
                    <input type="text" id="searchInput" placeholder="Search agents..." class="search-box">
                </div>
            </div>
        </div>
    </section>

    <section style="background: rgba(217, 119, 6, 0.02); border-bottom: 1px solid rgba(217, 119, 6, 0.1); padding: 2rem 0;">
        <div class="container">
            <div class="filters">
                <div class="filter-group">
                    <label for="categoryFilter">Category:</label>
                    <select id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="procurement">Government &amp; Procurement</option>
                        <option value="security">Security &amp; Compliance</option>
                        <option value="architecture">Architecture &amp; Strategy</option>
                        <option value="communications">Communications &amp; Avatar</option>
                        <option value="knowledge">Knowledge Management</option>
                        <option value="cyber">Cyber Operations</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="sortFilter">Sort by:</label>
                    <select id="sortFilter">
                        <option value="popular">Most Popular</option>
                        <option value="newest">Newest</option>
                        <option value="rating">Highest Rated</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <section class="featured" style="padding: 4rem 0;">
        <div class="container">
            <div class="agents-grid" id="agentsContainer">

                <div class="agent-card">
                    <div class="agent-badge">Popular</div>
                    <h3>Bid &amp; Tender Response</h3>
                    <p>Automates government RFQ/RFT responses, selection criteria, and capability statements for procurement teams.</p>
                    <div class="agent-stats"><span>⭐ 4.9/5</span><span>340+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$499/mo</span>
                        <a href="{{ route('agents.show', 'bid-tender') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card featured-highlight">
                    <div class="agent-badge premium">Premium</div>
                    <h3>Security &amp; IRAP Readiness</h3>
                    <p>Guides organisations through Essential Eight, ISM, PSPF, IRAP, ISO 27001, and cloud security readiness.</p>
                    <div class="agent-stats"><span>⭐ 4.95/5</span><span>180+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$799/mo</span>
                        <a href="{{ route('agents.show', 'security-compliance') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card">
                    <div class="agent-badge">Popular</div>
                    <h3>Enterprise Architecture</h3>
                    <p>Acts as a virtual enterprise/solution architect — generating options, decision records, and migration roadmaps.</p>
                    <div class="agent-stats"><span>⭐ 4.85/5</span><span>210+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$599/mo</span>
                        <a href="{{ route('agents.show', 'enterprise-architecture') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card">
                    <div class="agent-badge">New</div>
                    <h3>Digital Avatar &amp; Executive Clone</h3>
                    <p>Creates AI-powered professional avatars for executives, consultants, trainers, and public-facing staff.</p>
                    <div class="agent-stats"><span>⭐ 4.9/5</span><span>520+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$250/mo</span>
                        <a href="{{ route('agents.show', 'digital-avatar') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card">
                    <div class="agent-badge">Popular</div>
                    <h3>Knowledge Management &amp; SOP</h3>
                    <p>Turns scattered organisational knowledge into searchable operational intelligence and auto-generated SOPs.</p>
                    <div class="agent-stats"><span>⭐ 4.8/5</span><span>290+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$399/mo</span>
                        <a href="{{ route('agents.show', 'knowledge-management') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card featured-highlight">
                    <div class="agent-badge premium">Premium</div>
                    <h3>Cyber Incident &amp; Threat Intel</h3>
                    <p>Acts as a first-line cyber operations assistant for log summarisation, triage, IOC extraction, and playbooks.</p>
                    <div class="agent-stats"><span>⭐ 4.9/5</span><span>150+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$699/mo</span>
                        <a href="{{ route('agents.show', 'cyber-incident') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card">
                    <div class="agent-badge">Popular</div>
                    <h3>Content Creator</h3>
                    <p>Autonomous content generation for blogs, social media, and marketing campaigns.</p>
                    <div class="agent-stats"><span>⭐ 4.9/5</span><span>1,250+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$29/mo</span>
                        <a href="{{ route('agents.show', 'content-creator') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card">
                    <div class="agent-badge">New</div>
                    <h3>Customer Support Bot</h3>
                    <p>24/7 intelligent customer support with natural language understanding. Resolve issues faster.</p>
                    <div class="agent-stats"><span>⭐ 4.8/5</span><span>890+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$49/mo</span>
                        <a href="{{ route('agents.show', 'support-bot') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

                <div class="agent-card featured-highlight">
                    <div class="agent-badge premium">Premium</div>
                    <h3>Data Analyzer Pro</h3>
                    <p>Advanced data analysis, visualization, and insights generation from any dataset.</p>
                    <div class="agent-stats"><span>⭐ 4.95/5</span><span>2,100+ users</span></div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">$79/mo</span>
                        <a href="{{ route('agents.show', 'data-analyzer') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Want to Build Your Own Agent?</h2>
            <p>Join our agent developers community and monetize your creations</p>
            <div class="cta-buttons">
                <a href="{{ route('contact') }}" class="btn btn-outline">Get Started</a>
                <a href="{{ route('about') }}" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
    </section>
@endsection
