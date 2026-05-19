@extends('layouts.app')

@section('title', 'Training - ApiSpi')

@push('head')
<style>
    .training-hero { padding: 8rem 0 4rem; text-align: center; }
    .training-hero h1 { font-size: 3rem; margin-bottom: 1rem; }
    .training-hero p { font-size: 1.2rem; color: var(--gray); max-width: 600px; margin: 0 auto; }
    .courses-section { padding: 4rem 0 6rem; }
    .courses-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 2.5rem; margin-top: 3rem; }
    .course-card { background: rgba(28, 24, 16, 0.6); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: 1.25rem; overflow: hidden; transition: transform var(--transition-base), box-shadow var(--transition-base); }
    .course-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); }
    .course-banner { height: 220px; position: relative; overflow: hidden; }
    .course-banner svg { position: absolute; inset: 0; width: 100%; height: 100%; }
    .course-badge { position: absolute; top: 1rem; right: 1rem; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-new { background: rgba(0, 217, 126, 0.2); border: 1px solid rgba(0, 217, 126, 0.4); color: var(--success); }
    .badge-popular { background: rgba(255, 180, 30, 0.2); border: 1px solid rgba(255, 180, 30, 0.4); color: var(--warning); }
    .course-body { padding: 2rem; }
    .course-meta { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .course-meta span { font-size: 0.8rem; color: var(--gray); display: flex; align-items: center; gap: 0.3rem; }
    .course-body h2 { font-size: 1.5rem; margin-bottom: 0.75rem; line-height: 1.3; }
    .course-body p { color: var(--gray); margin-bottom: 1.5rem; line-height: 1.7; }
    .course-topics { list-style: none; margin-bottom: 1.5rem; }
    .course-topics li { display: flex; align-items: flex-start; gap: 0.6rem; padding: 0.4rem 0; font-size: 0.9rem; color: var(--light); border-bottom: 1px solid rgba(217, 119, 6, 0.08); }
    .course-topics li:last-child { border-bottom: none; }
    .course-topics li::before { content: '✓'; color: var(--success); font-weight: 700; flex-shrink: 0; margin-top: 0.05rem; }
    .course-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 1.5rem; border-top: 1px solid rgba(217, 119, 6, 0.1); flex-wrap: wrap; gap: 1rem; }
    .course-price { font-size: 1.6rem; font-weight: 700; color: var(--accent); }
    .course-price span { font-size: 0.9rem; color: var(--gray); font-weight: 400; }
    .instructor-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; }
    .instructor-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .instructor-info { font-size: 0.85rem; }
    .instructor-info strong { display: block; color: var(--light); }
    .instructor-info span { color: var(--gray); }
    .why-train { padding: 4rem 0; background: rgba(217, 119, 6, 0.03); border-top: 1px solid rgba(217, 119, 6, 0.1); border-bottom: 1px solid rgba(217, 119, 6, 0.1); }
    .why-train h2 { text-align: center; margin-bottom: 2.5rem; }
    .why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; }
    .why-item { text-align: center; }
    .why-item .icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(217, 119, 6, 0.08); border: 1.5px solid rgba(252, 211, 77, 0.2); border-radius: 14px; margin: 0 auto 0.75rem; color: var(--accent); filter: drop-shadow(0 0 6px rgba(252, 211, 77, 0.25)); }
    .why-item h3 { font-size: 1rem; margin-bottom: 0.5rem; }
    .why-item p { font-size: 0.9rem; color: var(--gray); }
</style>
@endpush

@section('content')
    <section class="training-hero">
        <div class="container">
            <h1>AI <span class="gradient-text">Training</span> Courses</h1>
            <p>Hands-on courses built by AI practitioners. Go from zero to production-ready with the skills that matter.</p>
        </div>
    </section>

    <section class="why-train">
        <div class="container">
            <h2>Why Train with ApiSpi?</h2>
            <div class="why-grid">
                <div class="why-item">
                    <div class="icon"><span data-icon="target" data-size="26"></span></div>
                    <h3>Practical Focus</h3>
                    <p>Real projects, not just theory</p>
                </div>
                <div class="why-item">
                    <div class="icon"><span data-icon="bolt" data-size="26"></span></div>
                    <h3>Self-Paced</h3>
                    <p>Learn on your own schedule</p>
                </div>
                <div class="why-item">
                    <div class="icon"><span data-icon="trophy" data-size="26"></span></div>
                    <h3>Certificate</h3>
                    <p>Shareable completion certificate</p>
                </div>
                <div class="why-item">
                    <div class="icon"><span data-icon="chat" data-size="26"></span></div>
                    <h3>Community</h3>
                    <p>Discord support & peer learning</p>
                </div>
            </div>
        </div>
    </section>

    <section class="courses-section">
        <div class="container">
            <h2 style="text-align:center;">Available Courses</h2>
            <p style="text-align:center;color:var(--gray);margin-top:0.5rem;">2 courses · Updated May 2026</p>

            <div class="courses-grid">

                <!-- Course 1: Digital Avatar -->
                <div class="course-card">
                    <div class="course-banner">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 220" preserveAspectRatio="xMidYMid slice">
                            <defs>
                                <linearGradient id="av-bg" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#0d0400"/>
                                    <stop offset="100%" stop-color="#2d0e00"/>
                                </linearGradient>
                                <radialGradient id="av-glow-r" cx="72%" cy="45%" r="45%">
                                    <stop offset="0%" stop-color="#D97706" stop-opacity="0.35"/>
                                    <stop offset="100%" stop-color="#D97706" stop-opacity="0"/>
                                </radialGradient>
                                <radialGradient id="av-glow-l" cx="18%" cy="65%" r="35%">
                                    <stop offset="0%" stop-color="#EA580C" stop-opacity="0.18"/>
                                    <stop offset="100%" stop-color="#EA580C" stop-opacity="0"/>
                                </radialGradient>
                            </defs>
                            <rect width="560" height="220" fill="url(#av-bg)"/>
                            <rect width="560" height="220" fill="url(#av-glow-r)"/>
                            <rect width="560" height="220" fill="url(#av-glow-l)"/>
                            <text x="25" y="55" font-size="9" fill="#D97706" font-family="monospace" letter-spacing="3" opacity="0.8">DIGITAL AVATAR COURSE</text>
                            <text x="22" y="105" font-size="44" font-weight="800" fill="white" font-family="'Outfit',sans-serif" letter-spacing="-1">SCALE YOUR</text>
                            <text x="22" y="152" font-size="44" font-weight="800" fill="#FCD34D" font-family="'Outfit',sans-serif" letter-spacing="-1">PRESENCE</text>
                            <line x1="22" y1="165" x2="248" y2="165" stroke="#D97706" stroke-width="1" opacity="0.35"/>
                            <text x="22" y="181" font-size="9" fill="#D97706" font-family="monospace" opacity="0.65" letter-spacing="0.5">VIDEO · LIVE · SOCIAL · MULTILINGUAL</text>
                            <g transform="rotate(-9,440,105)"><rect x="362" y="22" width="82" height="108" rx="5" fill="#1a0800" stroke="#EA580C" stroke-width="1" opacity="0.4"/></g>
                            <g transform="rotate(-3,443,103)"><rect x="378" y="16" width="82" height="108" rx="5" fill="#1a0800" stroke="#D97706" stroke-width="1.2" opacity="0.62"/></g>
                            <rect x="396" y="10" width="82" height="108" rx="5" fill="#200d00" stroke="#FCD34D" stroke-width="2" opacity="0.95"/>
                            <circle cx="437" cy="46" r="21" fill="#2d1200" stroke="#FCD34D" stroke-width="1" opacity="0.8"/>
                            <circle cx="430" cy="43" r="3.5" fill="#FCD34D" opacity="0.85"/>
                            <circle cx="444" cy="43" r="3.5" fill="#FCD34D" opacity="0.85"/>
                            <path d="M430,58 Q437,65 444,58" fill="none" stroke="#FCD34D" stroke-width="1.5" opacity="0.65"/>
                            <path d="M404,115 Q404,92 437,88 Q470,92 470,115" fill="none" stroke="#FCD34D" stroke-width="1" opacity="0.55"/>
                            <rect x="396" y="100" width="82" height="18" rx="0 0 5 5" fill="#D97706" opacity="0.85"/>
                            <text x="437" y="112" text-anchor="middle" font-size="7.5" font-weight="700" fill="#0d0400" font-family="monospace" letter-spacing="1">LIVE ANYWHERE</text>
                            <text x="502" y="75" text-anchor="middle" font-size="34" font-weight="800" fill="#FCD34D" font-family="sans-serif" opacity="0.9">×∞</text>
                            <text x="502" y="93" text-anchor="middle" font-size="8" fill="#D97706" font-family="monospace" opacity="0.6" letter-spacing="1">INSTANCES</text>
                            <line x1="480" y1="60" x2="495" y2="72" stroke="#D97706" stroke-width="1" opacity="0.35" stroke-dasharray="3,3"/>
                            <g font-size="8.5" font-family="monospace" fill="#D97706" opacity="0.55" letter-spacing="0.5">
                                <text x="396" y="148">VIDEO</text>
                                <text x="396" y="162">TRAINING</text>
                                <text x="396" y="176">DEMOS</text>
                            </g>
                        </svg>
                        <span class="course-badge badge-new">New</span>
                    </div>
                    <div class="course-body">
                        <div class="course-meta">
                            <span><span data-icon="clock" data-size="14" style="vertical-align:middle;margin-right:4px"></span> 6 hours</span>
                            <span><span data-icon="book" data-size="14" style="vertical-align:middle;margin-right:4px"></span> 8 modules</span>
                            <span><span data-icon="star" data-size="14" style="vertical-align:middle;margin-right:4px"></span> Beginner–Intermediate</span>
                        </div>
                        <h2>Digital Avatar</h2>
                        <p>Create lifelike AI-powered digital avatars for business, content creation, and customer engagement. Learn to build, train, and deploy your own avatar from scratch.</p>
                        <div class="instructor-row">
                            <div class="instructor-avatar"><span data-icon="bolt" data-size="20" data-color="#ffffff"></span></div>
                            <div class="instructor-info">
                                <strong>ApiSpi Team</strong>
                                <span>AI & Creative Technology</span>
                            </div>
                        </div>
                        <ul class="course-topics">
                            <li>What are digital avatars & real-world use cases</li>
                            <li>Choosing the right AI models (image, voice, video)</li>
                            <li>Building a custom avatar with generative AI</li>
                            <li>Lip-sync, animation & real-time rendering</li>
                            <li>Integrating your avatar into web & mobile apps</li>
                            <li>Monetizing digital avatar products</li>
                        </ul>
                        <div class="course-footer">
                            <div class="course-price">$250 <span>/ avatar</span></div>
                            <a href="{{ route('checkout') }}?agent=Digital+Avatar+Course&amount=250" class="btn btn-primary">Enrol Now</a>
                        </div>
                    </div>
                </div>

                <!-- Course 2: Introduction to AI -->
                <div class="course-card">
                    <div class="course-banner">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 220" preserveAspectRatio="xMidYMid slice">
                            <defs>
                                <linearGradient id="ai-bg" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#0d0400"/>
                                    <stop offset="100%" stop-color="#331000"/>
                                </linearGradient>
                                <radialGradient id="ai-glow" cx="25%" cy="70%" r="40%">
                                    <stop offset="0%" stop-color="#EA580C" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#EA580C" stop-opacity="0"/>
                                </radialGradient>
                                <linearGradient id="ai-bar-grad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#FCD34D" stop-opacity="0.9"/>
                                    <stop offset="100%" stop-color="#D97706" stop-opacity="0.4"/>
                                </linearGradient>
                            </defs>
                            <rect width="560" height="220" fill="url(#ai-bg)"/>
                            <rect width="560" height="220" fill="url(#ai-glow)"/>
                            <text x="25" y="55" font-size="9" fill="#D97706" font-family="monospace" letter-spacing="3" opacity="0.8">INTRODUCTION TO AI</text>
                            <text x="20" y="148" font-size="100" font-weight="800" fill="#FCD34D" font-family="'Outfit',sans-serif" opacity="0.95">5</text>
                            <text x="98" y="100" font-size="20" font-weight="700" fill="white" font-family="'Outfit',sans-serif">AI TOOLS</text>
                            <text x="98" y="124" font-size="20" font-weight="700" fill="white" font-family="'Outfit',sans-serif">THAT CHANGE</text>
                            <text x="98" y="148" font-size="20" font-weight="700" fill="#FCD34D" font-family="'Outfit',sans-serif">EVERYTHING</text>
                            <line x1="22" y1="162" x2="245" y2="162" stroke="#D97706" stroke-width="1" opacity="0.35"/>
                            <text x="22" y="178" font-size="9" fill="#D97706" font-family="monospace" opacity="0.65" letter-spacing="0.5">One full day · Hands-on · Lifetime access</text>
                            <g stroke="#D97706" stroke-width="0.4" opacity="0.12">
                                <line x1="265" y1="30" x2="265" y2="195"/>
                                <line x1="265" y1="80" x2="545" y2="80"/>
                                <line x1="265" y1="120" x2="545" y2="120"/>
                                <line x1="265" y1="160" x2="545" y2="160"/>
                            </g>
                            <rect x="272" y="148" width="32" height="47" rx="3" fill="url(#ai-bar-grad)" opacity="0.45"/>
                            <rect x="322" y="120" width="32" height="75" rx="3" fill="url(#ai-bar-grad)" opacity="0.55"/>
                            <rect x="372" y="90"  width="32" height="105" rx="3" fill="url(#ai-bar-grad)" opacity="0.65"/>
                            <rect x="422" y="60"  width="32" height="135" rx="3" fill="url(#ai-bar-grad)" opacity="0.75"/>
                            <rect x="472" y="25"  width="32" height="170" rx="3" fill="url(#ai-bar-grad)" opacity="0.9"/>
                            <polyline points="288,148 338,120 388,90 438,60 488,25" fill="none" stroke="#FCD34D" stroke-width="2" opacity="0.85"/>
                            <circle cx="288" cy="148" r="4" fill="#D97706" opacity="0.8"/>
                            <circle cx="338" cy="120" r="4" fill="#D97706" opacity="0.85"/>
                            <circle cx="388" cy="90"  r="4" fill="#EA580C" opacity="0.9"/>
                            <circle cx="438" cy="60"  r="5" fill="#FCD34D" opacity="0.9"/>
                            <circle cx="488" cy="25"  r="6" fill="#FCD34D" opacity="1"/>
                            <polyline points="481,18 488,8 495,18" fill="none" stroke="#FCD34D" stroke-width="2" opacity="0.9"/>
                            <g font-size="7" font-family="monospace" fill="#D97706" opacity="0.65" text-anchor="middle">
                                <text x="288" y="210">ChatGPT</text>
                                <text x="338" y="210">Claude</text>
                                <text x="388" y="210">HeyGen</text>
                                <text x="438" y="210">Notebook</text>
                                <text x="488" y="210">Hermes</text>
                            </g>
                        </svg>
                        <span class="course-badge badge-popular">Popular</span>
                    </div>
                    <div class="course-body">
                        <div class="course-meta">
                            <span><span data-icon="clock" data-size="14" style="vertical-align:middle;margin-right:4px"></span> Full Day (8 hrs)</span>
                            <span><span data-icon="book" data-size="14" style="vertical-align:middle;margin-right:4px"></span> 5 tools</span>
                            <span><span data-icon="star" data-size="14" style="vertical-align:middle;margin-right:4px"></span> All levels</span>
                        </div>
                        <h2>Introduction to AI</h2>
                        <p>A full day, hands-on workshop covering the 5 top AI tools for personal efficiency. Work through real exercises with each tool, leave with skills you can apply immediately — and revisit everything with lifetime video access after the course.</p>
                        <div class="instructor-row">
                            <div class="instructor-avatar"><span data-icon="bolt" data-size="20" data-color="#ffffff"></span></div>
                            <div class="instructor-info">
                                <strong>ApiSpi Team</strong>
                                <span>AI Productivity & Tools</span>
                            </div>
                        </div>
                        <ul class="course-topics">
                            <li>ChatGPT — writing, research & idea generation</li>
                            <li>Claude — analysis, summarisation & long-form work</li>
                            <li>HeyGen — AI video avatars & personalised video at scale</li>
                            <li>NotebookLM — AI-powered research, notes & source analysis</li>
                            <li>Hermes Agent — autonomous AI agent for personal task automation</li>
                        </ul>
                        <div style="margin-bottom:1.5rem;padding:1rem;background:rgba(0,217,255,0.05);border:1px solid rgba(0,217,255,0.15);border-radius:0.75rem;font-size:0.85rem;color:var(--gray);">
                            <strong style="color:var(--light);display:block;margin-bottom:0.4rem;">What's included</strong>
                            ✓ Full day live hands-on workshop &nbsp;·&nbsp; ✓ Lifetime video replay access &nbsp;·&nbsp; ✓ Course materials & templates &nbsp;·&nbsp; ✓ Certificate of completion
                        </div>
                        <div class="course-footer">
                            <div class="course-price">$1,500 <span>/ per person</span></div>
                            <a href="{{ route('checkout') }}?agent=Introduction+to+AI+Course&amount=1500" class="btn btn-primary">Enrol Now</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Not sure which course to start with?</h2>
            <p>Get in touch and we'll point you in the right direction</p>
            <div class="cta-buttons">
                <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us</a>
                <a href="{{ route('agents.index') }}" class="btn btn-secondary">Explore Agents</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:payment@apispi.com">Payment Inquiries</a> | <a href="{{ route('contact') }}">Support</a></p>
@endsection
