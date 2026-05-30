@extends('layouts.app')

@section('title', 'News - ApiSpi')

@section('content')
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    The <span class="gradient-text">ApiSpi</span> News
                </h1>
                <p class="hero-subtitle">
                    Insights, tutorials, and best practices for building and deploying AI agents.
                </p>
            </div>
        </div>
    </section>

    <section class="latest-posts" style="padding: 4rem 0;">
        <div class="container">
            <div class="blog-grid" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));">
                <article class="blog-card">
                    <div class="blog-date">May 31, 2026</div>
                    <h3><a href="{{ route('blog.show', 'five-agents-service-business-2026') }}">The 5 AI Agents Every Australian Service Business Should Have in 2026</a></h3>
                    <p>Consultancies, agencies, trades, and professional services firms are leaving significant time and revenue on the table. Here are the five agents with the clearest ROI — and how to sequence deploying them.</p>
                    <div class="blog-meta">
                        <span class="category">Guide</span>
                        <span class="read-time">7 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 30, 2026</div>
                    <h3><a href="{{ route('blog.show', 'ai-agents-australian-privacy-law') }}">AI Agents and Australian Privacy Law: What You Need to Know Before Deploying</a></h3>
                    <p>The 2024 Privacy Act reforms introduced new obligations for automated decision-making. Here is what the framework means for your agent deployment — and the practical steps to stay compliant.</p>
                    <div class="blog-meta">
                        <span class="category">Compliance</span>
                        <span class="read-time">9 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 29, 2026</div>
                    <h3><a href="{{ route('blog.show', 'chatbot-to-agent-upgrade') }}">From Chatbot to Agent: Why the Upgrade Matters for Your Business</a></h3>
                    <p>Most businesses that experimented with AI in 2023–24 deployed chatbots. The architecture has fundamentally changed — and the distinction between a chatbot and an agent determines what your AI can actually do.</p>
                    <div class="blog-meta">
                        <span class="category">Insights</span>
                        <span class="read-time">8 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 26, 2026</div>
                    <h3><a href="{{ route('blog.show', 'ai-agents-australian-smb-2026') }}">AI Agents for Australian SMBs: The 2026 Opportunity</a></h3>
                    <p>Model costs have collapsed, platforms have abstracted the complexity, and the competitive gap between early adopters and late movers is widening. Here's where small businesses should start.</p>
                    <div class="blog-meta">
                        <span class="category">Insights</span>
                        <span class="read-time">9 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 24, 2026</div>
                    <h3><a href="{{ route('blog.show', 'digital-avatars-professional-services') }}">Digital Avatars: How AI Video Personas Are Winning Clients for Professional Services</a></h3>
                    <p>Tradies, property agents, lawyers, accountants, beauticians, and hotel marketers are using AI-generated video to build trust at scale. Here's what works and why.</p>
                    <div class="blog-meta">
                        <span class="category">Guide</span>
                        <span class="read-time">8 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 23, 2026</div>
                    <h3><a href="{{ route('blog.show', 'ai-agents-partner-program') }}">Why Agencies Are Adding AI Agents to Their Service Stack in 2026</a></h3>
                    <p>Digital agencies that move first into AI agent delivery for SMB clients are building recurring revenue and client lock-in that pure-service competitors can't replicate.</p>
                    <div class="blog-meta">
                        <span class="category">Business</span>
                        <span class="read-time">7 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 21, 2026</div>
                    <h3><a href="{{ route('blog.show', 'claude-4-agentic-leap') }}">Claude 4: The Agentic Leap That Changes Everything</a></h3>
                    <p>Opus 4.7, Sonnet 4.6, and Haiku 4.5 bring extended thinking, native multi-agent orchestration, and dramatically improved accuracy for production agent workloads.</p>
                    <div class="blog-meta">
                        <span class="category">News</span>
                        <span class="read-time">11 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 19, 2026</div>
                    <h3><a href="{{ route('blog.show', 'mcp-model-context-protocol') }}">MCP: The Protocol Quietly Becoming the Backbone of Enterprise AI Agents</a></h3>
                    <p>Model Context Protocol has crossed the adoption inflection point. With 400+ community servers and native support from every major AI platform, it's the connectivity layer serious agent deployments now run on.</p>
                    <div class="blog-meta">
                        <span class="category">Infrastructure</span>
                        <span class="read-time">9 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 17, 2026</div>
                    <h3><a href="{{ route('blog.show', 'agentic-ai-government-procurement') }}">Agentic AI in Government Procurement: Australia's 2026 Landscape</a></h3>
                    <p>How AI agents are reshaping the competitive dynamics of Australian government tender responses — and what the regulatory framework means for organisations using them.</p>
                    <div class="blog-meta">
                        <span class="category">Insights</span>
                        <span class="read-time">10 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 15, 2026</div>
                    <h3><a href="{{ route('blog.show', 'getting-started-with-agents') }}">Getting Started with AI Agents: A Beginner's Guide</a></h3>
                    <p>Learn the fundamentals of AI agents, how they work, and how to deploy your first agent in production. Perfect for beginners.</p>
                    <div class="blog-meta">
                        <span class="category">Tutorial</span>
                        <span class="read-time">8 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 12, 2026</div>
                    <h3><a href="{{ route('blog.show', 'agent-best-practices') }}">Best Practices for Production AI Agents</a></h3>
                    <p>Discover proven strategies for deploying reliable, scalable agents at enterprise scale. Learn from our experience.</p>
                    <div class="blog-meta">
                        <span class="category">Guide</span>
                        <span class="read-time">12 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 8, 2026</div>
                    <h3><a href="{{ route('blog.show', 'future-of-agentic-ai') }}">The Future of Agentic AI: What's Next?</a></h3>
                    <p>Explore emerging trends in agentic AI, upcoming technologies, and the future roadmap of autonomous agents.</p>
                    <div class="blog-meta">
                        <span class="category">Insights</span>
                        <span class="read-time">10 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 5, 2026</div>
                    <h3><a href="{{ route('blog.show', 'building-custom-agents') }}">Building Custom Agents: Advanced Techniques</a></h3>
                    <p>Deep dive into advanced techniques for building specialized agents tailored to your specific business needs.</p>
                    <div class="blog-meta">
                        <span class="category">Advanced</span>
                        <span class="read-time">15 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">May 1, 2026</div>
                    <h3><a href="{{ route('blog.show', 'scaling-agents') }}">Scaling Agents: From Prototype to Production</a></h3>
                    <p>Learn how to scale your AI agents from proof-of-concept to handling millions of interactions daily.</p>
                    <div class="blog-meta">
                        <span class="category">Infrastructure</span>
                        <span class="read-time">11 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">April 28, 2026</div>
                    <h3><a href="{{ route('blog.show', 'monitoring-optimization') }}">Monitoring and Optimization: Keeping Your Agents Healthy</a></h3>
                    <p>Discover essential monitoring practices and optimization techniques to keep your agents performing at their best.</p>
                    <div class="blog-meta">
                        <span class="category">Operations</span>
                        <span class="read-time">9 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">April 25, 2026</div>
                    <h3><a href="{{ route('blog.show', 'real-world-success-stories') }}">Real-World Success Stories: Agents in Action</a></h3>
                    <p>Explore how companies are using ApiSpi to transform their operations and improve customer experiences.</p>
                    <div class="blog-meta">
                        <span class="category">Case Studies</span>
                        <span class="read-time">7 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">April 22, 2026</div>
                    <h3><a href="{{ route('blog.show', 'api-integration-guide') }}">API Integration Guide: Connect Your Systems</a></h3>
                    <p>Complete guide on integrating ApiSpi agents with your existing systems and applications via our API.</p>
                    <div class="blog-meta">
                        <span class="category">Integration</span>
                        <span class="read-time">13 min read</span>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-date">April 19, 2026</div>
                    <h3><a href="{{ route('blog.show', 'security-and-compliance') }}">Security and Compliance: Protecting Your Data</a></h3>
                    <p>Important information about security features, compliance certifications, and best practices for data protection.</p>
                    <div class="blog-meta">
                        <span class="category">Security</span>
                        <span class="read-time">10 min read</span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section style="background: var(--gradient-primary); padding: 4rem 0; margin-top: 4rem;">
        <div class="container" style="text-align: center;">
            <h2 style="color: white; margin-bottom: 1rem;">Stay Updated</h2>
            <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 2rem;">Get the latest insights on AI agents delivered to your inbox.</p>
            <form id="newsletterForm" style="display: flex; gap: 1rem; max-width: 500px; margin: 0 auto; flex-wrap: wrap;">
                @csrf
                <input id="newsletterEmail" type="email" name="email" placeholder="Enter your email" required style="flex: 1; padding: 0.75rem 1rem; border: none; border-radius: 0.5rem; background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.3);">
                <button id="newsletterBtn" type="submit" class="btn btn-outline">Subscribe</button>
            </form>
            <p id="newsletterMsg" style="margin-top: 1rem; font-size: 0.95rem; min-height: 1.4em;"></p>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const form = document.getElementById('newsletterForm');
    const btn  = document.getElementById('newsletterBtn');
    const msg  = document.getElementById('newsletterMsg');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        btn.disabled = true;
        btn.textContent = 'Subscribing…';
        msg.style.color = 'rgba(255,255,255,0.8)';
        msg.textContent = '';

        const body = new FormData(form);

        try {
            const res  = await fetch('{{ route("subscribe") }}', { method: 'POST', body });
            const data = await res.json();

            if (data.success) {
                btn.textContent = 'Subscribed ✓';
                btn.style.borderColor = '#4ade80';
                btn.style.color = '#4ade80';
                msg.style.color = '#4ade80';
                msg.textContent = "You're on the list — we'll be in touch!";
                form.reset();
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = 'Subscribe';
                    btn.style.borderColor = '';
                    btn.style.color = '';
                    msg.textContent = '';
                }, 5000);
            } else {
                throw new Error('failed');
            }
        } catch {
            btn.disabled = false;
            btn.textContent = 'Subscribe';
            msg.style.color = '#f87171';
            msg.textContent = 'Something went wrong — please email admin@apispi.com directly.';
        }
    });
</script>
@endpush
