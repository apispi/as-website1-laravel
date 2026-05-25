<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Avatars for Tradies, Property, Finance, Law & Accounting | ApiSpi</title>
  <meta name="description" content="Win more business with a professional digital avatar. Made for tradies, property agents, finance brokers, lawyers, and accountants. Always on. Always impressive.">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-9NX96RC3FF"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-9NX96RC3FF');</script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:       #0b0f1a;
      --bg2:      #111827;
      --gold:     #f59e0b;
      --gold-lt:  #fcd34d;
      --gold-dk:  #d97706;
      --blue:     #3b82f6;
      --text:     #f9fafb;
      --muted:    #9ca3af;
      --dim:      #6b7280;
      --border:   rgba(245,158,11,0.18);
      --card:     rgba(255,255,255,0.03);
    }

    html { scroll-behavior: smooth; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
      background: var(--bg); color: var(--text); line-height: 1.6; overflow-x: hidden;
    }

    /* ── NAV ── */
    .nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      padding: 0 2rem;
      background: rgba(11,15,26,0.9); backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(245,158,11,0.12);
    }
    .nav-inner {
      max-width: 1140px; margin: 0 auto;
      display: flex; align-items: center; justify-content: space-between; height: 62px;
    }
    .nav-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--text); font-weight: 700; font-size: 1.1rem; }
    .nav-logo svg { width: 24px; height: 27px; }
    .nav-right { display: flex; align-items: center; gap: 1rem; }
    .nav-link { font-size: 0.875rem; color: var(--muted); text-decoration: none; transition: color 0.15s; }
    .nav-link:hover { color: var(--text); }
    .btn-gold {
      display: inline-flex; align-items: center;
      padding: 0.5rem 1.15rem; border-radius: 0.5rem;
      background: linear-gradient(135deg, var(--gold), var(--gold-dk));
      color: #1a0e00; font-size: 0.875rem; font-weight: 700;
      text-decoration: none; transition: opacity 0.18s; white-space: nowrap;
    }
    .btn-gold:hover { opacity: 0.88; }
    button.btn-gold { border: none; cursor: pointer; font-family: inherit; }

    /* ── HERO ── */
    .hero {
      min-height: 100vh; display: flex; align-items: center;
      padding: 110px 2rem 80px; position: relative; overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 70% 55% at 15% 50%, rgba(245,158,11,0.1) 0%, transparent 65%),
        radial-gradient(ellipse 50% 40% at 85% 55%, rgba(217,119,6,0.07) 0%, transparent 65%);
      pointer-events: none;
    }
    .hero-inner {
      max-width: 1140px; margin: 0 auto;
      display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center;
      position: relative;
    }
    .hero-industries {
      display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
    }
    .ind-pill {
      padding: 0.25rem 0.7rem; border-radius: 99px;
      background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25);
      font-size: 0.72rem; font-weight: 700; color: var(--gold-lt);
      letter-spacing: 0.04em; text-transform: uppercase;
    }
    .hero h1 {
      font-size: clamp(2.1rem, 4.5vw, 3.2rem);
      font-weight: 900; line-height: 1.15; margin-bottom: 1.25rem;
      letter-spacing: -0.025em;
    }
    .grad { background: linear-gradient(135deg, var(--gold-lt), var(--gold-dk)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-sub { font-size: 1.05rem; color: var(--muted); line-height: 1.75; margin-bottom: 2rem; max-width: 500px; }
    .hero-sub strong { color: var(--text); font-weight: 600; }

    .hero-form { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.75rem; }
    .hero-input {
      flex: 1; min-width: 200px;
      padding: 0.8rem 1rem;
      background: rgba(255,255,255,0.06); border: 1px solid rgba(245,158,11,0.2);
      border-radius: 0.625rem; color: var(--text); font-size: 1rem; font-family: inherit;
    }
    .hero-input:focus { outline: none; border-color: var(--gold); }
    .hero-input::placeholder { color: #4b5563; }
    .hero-note { font-size: 0.78rem; color: var(--dim); }
    .hero-note span { color: var(--muted); }

    /* Hero visual */
    .prof-grid {
      display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
    }
    .prof-card {
      border-radius: 1.1rem; overflow: hidden;
      border: 1px solid var(--border);
      background: var(--card);
      padding: 1.5rem 1.25rem 1.25rem;
      position: relative; transition: border-color 0.2s;
    }
    .prof-card:hover { border-color: rgba(245,158,11,0.4); }
    .prof-emoji { font-size: 2.4rem; margin-bottom: 0.75rem; display: block; }
    .prof-name { font-size: 0.88rem; font-weight: 700; color: var(--text); margin-bottom: 0.2rem; }
    .prof-role { font-size: 0.72rem; color: var(--gold-lt); font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 0.6rem; }
    .prof-quote { font-size: 0.78rem; color: var(--muted); line-height: 1.55; font-style: italic; }
    .prof-badge {
      position: absolute; top: 0.75rem; right: 0.75rem;
      display: flex; align-items: center; gap: 0.25rem;
      padding: 0.2rem 0.5rem; border-radius: 99px;
      background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25);
      font-size: 0.62rem; font-weight: 700; color: #10b981;
    }
    .prof-badge::before { content: '●'; font-size: 0.4rem; }

    /* ── TRUST BAR ── */
    .trust-bar {
      background: rgba(245,158,11,0.04);
      border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
      padding: 2rem 2rem;
    }
    .trust-inner {
      max-width: 1140px; margin: 0 auto;
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; text-align: center;
    }
    .trust-num { font-size: 1.9rem; font-weight: 800; background: linear-gradient(135deg, var(--gold-lt), var(--gold-dk)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .trust-label { font-size: 0.8rem; color: var(--muted); margin-top: 0.2rem; line-height: 1.4; }

    /* ── SHARED SECTION ── */
    section { padding: 6rem 2rem; }
    .section-inner { max-width: 1140px; margin: 0 auto; }
    .section-tag {
      display: inline-block; padding: 0.25rem 0.75rem; border-radius: 99px;
      background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.22);
      font-size: 0.7rem; font-weight: 700; color: var(--gold-lt);
      letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1rem;
    }
    .section-h2 { font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 800; letter-spacing: -0.02em; margin-bottom: 1rem; line-height: 1.2; }
    .section-sub { font-size: 1rem; color: var(--muted); line-height: 1.75; margin-bottom: 3rem; max-width: 580px; }
    .text-center { text-align: center; }
    .text-center .section-sub { margin-left: auto; margin-right: auto; }

    /* ── WHO IT'S FOR ── */
    .industry-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    .industry-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 1.1rem; padding: 2rem; transition: all 0.2s;
    }
    .industry-card:hover { border-color: rgba(245,158,11,0.4); background: rgba(245,158,11,0.04); transform: translateY(-2px); }
    .ind-icon { font-size: 2.2rem; margin-bottom: 1rem; display: block; }
    .ind-h3 { font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem; }
    .ind-tagline { font-size: 0.88rem; color: var(--gold-lt); font-weight: 600; margin-bottom: 0.75rem; }
    .ind-p { font-size: 0.875rem; color: var(--muted); line-height: 1.65; margin-bottom: 1.1rem; }
    .ind-uses { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .ind-use {
      padding: 0.2rem 0.6rem; border-radius: 99px;
      background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.18);
      font-size: 0.7rem; font-weight: 600; color: var(--gold-lt);
    }

    /* ── VIDEO GALLERY ── */
    .video-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
    .video-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 1rem; overflow: hidden; cursor: pointer;
      transition: transform 0.2s, border-color 0.2s;
    }
    .video-card:hover { transform: translateY(-4px); border-color: rgba(245,158,11,0.4); }
    .video-thumb {
      aspect-ratio: 16/9; position: relative;
      display: flex; align-items: center; justify-content: center;
    }
    .video-bg {
      position: absolute; inset: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 3.5rem;
    }
    .video-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.55) 100%); }
    .play-btn {
      position: relative; z-index: 2;
      width: 52px; height: 52px; border-radius: 50%;
      background: rgba(245,158,11,0.25); backdrop-filter: blur(6px);
      border: 2px solid rgba(245,158,11,0.6);
      display: flex; align-items: center; justify-content: center;
      transition: all 0.2s;
    }
    .video-card:hover .play-btn { background: rgba(245,158,11,0.5); border-color: var(--gold-lt); transform: scale(1.08); }
    .play-icon { width: 0; height: 0; border-style: solid; border-width: 9px 0 9px 18px; border-color: transparent transparent transparent #fff; margin-left: 4px; }
    .video-dur { position: absolute; bottom: 0.5rem; right: 0.6rem; z-index: 2; font-size: 0.68rem; font-weight: 600; color: #fff; background: rgba(0,0,0,0.65); padding: 0.15rem 0.4rem; border-radius: 0.25rem; }
    .video-ind { position: absolute; top: 0.6rem; left: 0.6rem; z-index: 2; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.65rem; font-weight: 700; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); color: var(--gold-lt); border: 1px solid rgba(245,158,11,0.3); }
    .video-info { padding: 1rem 1.1rem 1.1rem; }
    .video-cat { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--gold); margin-bottom: 0.3rem; }
    .video-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.3rem; line-height: 1.4; }
    .video-desc { font-size: 0.8rem; color: var(--muted); line-height: 1.5; }

    /* ── MODAL ── */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 9999;
      background: rgba(0,0,0,0.9); backdrop-filter: blur(6px);
      display: flex; align-items: center; justify-content: center; padding: 1.5rem;
      opacity: 0; pointer-events: none; transition: opacity 0.22s;
    }
    .modal-overlay.open { opacity: 1; pointer-events: all; }
    .modal-box { width: 100%; max-width: 900px; background: #0f1623; border: 1px solid var(--border); border-radius: 1rem; overflow: hidden; transform: scale(0.95); transition: transform 0.22s; }
    .modal-overlay.open .modal-box { transform: scale(1); }
    .modal-top { display: flex; justify-content: space-between; align-items: center; padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--border); }
    .modal-title-text { font-size: 0.9rem; font-weight: 600; }
    .modal-close-btn { background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.25rem; padding: 0.2rem; line-height: 1; transition: color 0.15s; }
    .modal-close-btn:hover { color: var(--text); }
    .modal-video { aspect-ratio: 16/9; background: #000; }
    .modal-video iframe { width: 100%; height: 100%; border: none; display: block; }

    /* ── HOW IT WORKS ── */
    .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
    .step { text-align: center; }
    .step-num {
      width: 52px; height: 52px; border-radius: 50%; margin: 0 auto 1.1rem;
      background: rgba(245,158,11,0.1); border: 2px solid rgba(245,158,11,0.3);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem; font-weight: 800; color: var(--gold-lt);
    }
    .step-h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .step-p { font-size: 0.875rem; color: var(--muted); line-height: 1.65; }
    .step-connector { display: none; }

    /* ── FORM ── */
    .form-section { background: rgba(245,158,11,0.03); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .form-card {
      max-width: 660px; margin: 0 auto;
      background: rgba(15,22,35,0.9); border: 1px solid var(--border);
      border-radius: 1.25rem; padding: 2.5rem;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: var(--muted); }
    .form-input, .form-select {
      padding: 0.75rem 1rem;
      background: rgba(255,255,255,0.05); border: 1px solid rgba(245,158,11,0.2);
      border-radius: 0.625rem; color: var(--text); font-size: 0.95rem; font-family: inherit;
      transition: border-color 0.18s; width: 100%;
    }
    .form-input:focus, .form-select:focus { outline: none; border-color: var(--gold); }
    .form-input::placeholder { color: #374151; }
    .form-select option { background: #0f1623; }
    .form-submit {
      width: 100%; margin-top: 0.5rem;
      padding: 0.9rem;
      background: linear-gradient(135deg, var(--gold), var(--gold-dk));
      border: none; border-radius: 0.625rem;
      color: #1a0e00; font-size: 1rem; font-weight: 800;
      cursor: pointer; font-family: inherit; transition: opacity 0.18s;
    }
    .form-submit:hover { opacity: 0.88; }
    .form-note { font-size: 0.75rem; color: var(--dim); text-align: center; margin-top: 0.75rem; }

    .flash-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #10b981; border-radius: 0.75rem; padding: 1rem 1.25rem; font-size: 0.9rem; margin-bottom: 1.5rem; text-align: center; }
    .flash-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; border-radius: 0.75rem; padding: 0.75rem 1rem; font-size: 0.875rem; margin-bottom: 1rem; }

    /* ── FOOTER ── */
    .page-footer { padding: 2rem; border-top: 1px solid rgba(255,255,255,0.06); }
    .footer-inner { max-width: 1140px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .footer-copy { font-size: 0.8rem; color: var(--dim); }
    .footer-links { display: flex; gap: 1.5rem; }
    .footer-links a { font-size: 0.8rem; color: var(--dim); text-decoration: none; }
    .footer-links a:hover { color: var(--muted); }

    /* ── RESPONSIVE ── */
    @media (max-width: 860px) {
      .hero-inner { grid-template-columns: 1fr; gap: 3rem; }
      .prof-grid { order: -1; grid-template-columns: repeat(4, 1fr); }
      .prof-card { padding: 1rem; }
      .industry-grid { grid-template-columns: 1fr; }
      .trust-inner { grid-template-columns: repeat(2, 1fr); }
      .steps { grid-template-columns: 1fr; gap: 2.5rem; }
    }
    @media (max-width: 540px) {
      .prof-grid { grid-template-columns: repeat(2, 1fr); }
      .prof-card:nth-child(3), .prof-card:nth-child(4) { display: none; }
      .form-grid { grid-template-columns: 1fr; }
      .footer-inner { flex-direction: column; text-align: center; }
    }
  </style>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9NX96RC3FF"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-9NX96RC3FF');</script>
</head>
<body>

<!-- ── NAV ── -->
<nav class="nav">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-logo">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27">
        <defs><linearGradient id="nlg" x1=".5" y1="0" x2=".5" y2="1">
          <stop offset="0%" stop-color="#FCD34D"/><stop offset="100%" stop-color="#D97706"/>
        </linearGradient></defs>
        <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#nlg)"/>
        <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#nlg)"/>
      </svg>
      ApiSpi
    </a>
    <div class="nav-right">
      <a href="{{ route('home') }}" class="nav-link">Home</a>
      <a href="#get-started" class="btn-gold">Book a Free Demo</a>
    </div>
  </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-inner">
    <div>
      <div class="hero-industries">
        <span class="ind-pill">Tradies</span>
        <span class="ind-pill">Property</span>
        <span class="ind-pill">Finance</span>
        <span class="ind-pill">Law</span>
        <span class="ind-pill">Tax & Accounting</span>
      </div>
      <h1>Your clients decide<br>before they <span class="grad">ever call you.</span></h1>
      <p class="hero-sub">
        A digital avatar puts a <strong>professional, always-on version of you</strong> in front of every prospect — answering questions, building trust, and winning business while you're on the tools, at settlement, or in a meeting.
      </p>
      <form class="hero-form" action="{{ route('digital-avatars.store') }}" method="POST">
        @csrf
        <input type="hidden" name="use_case" value="hero">
        <input class="hero-input" type="text" name="name" placeholder="Your name" required value="{{ old('name') }}">
        <input class="hero-input" type="email" name="email" placeholder="Work email" required value="{{ old('email') }}" style="max-width:220px;">
        <button type="submit" class="btn-gold" style="min-height:48px; padding:0.8rem 1.4rem; font-size:0.95rem;">See My Avatar →</button>
      </form>
      <p class="hero-note">Free demo &nbsp;·&nbsp; No setup fees &nbsp;·&nbsp; <span>Built for Australian professionals</span></p>
    </div>

    <div class="prof-grid">
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">🔧</span>
        <div class="prof-name">Dave Tradie</div>
        <div class="prof-role">Electrician · Sydney</div>
        <div class="prof-quote">"Hi, I'm Dave. I give free quotes within 24 hours and I'm fully licensed. Here's what I can do for you…"</div>
      </div>
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">🏡</span>
        <div class="prof-name">Sarah Chen</div>
        <div class="prof-role">Property Agent · Melbourne</div>
        <div class="prof-quote">"Thinking of selling? I've sold 12 homes in your suburb this year. Let me show you what yours could fetch…"</div>
      </div>
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">💼</span>
        <div class="prof-name">Mark Broker</div>
        <div class="prof-role">Finance Broker · Brisbane</div>
        <div class="prof-quote">"Rates are moving. Here's exactly what that means for your borrowing capacity and how I can help…"</div>
      </div>
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">⚖️</span>
        <div class="prof-name">Priya Sharma</div>
        <div class="prof-role">Solicitor · Perth</div>
        <div class="prof-quote">"Worried about your contract? Watch this 2-minute explainer before your consultation and save us both time…"</div>
      </div>
    </div>
  </div>
</section>

<!-- ── TRUST BAR ── -->
<div class="trust-bar">
  <div class="trust-inner">
    <div>
      <div class="trust-num">3 min</div>
      <div class="trust-label">To generate your first avatar video</div>
    </div>
    <div>
      <div class="trust-num">24/7</div>
      <div class="trust-label">Answers leads even when you're busy</div>
    </div>
    <div>
      <div class="trust-num">10×</div>
      <div class="trust-label">More content than traditional video</div>
    </div>
    <div>
      <div class="trust-num">$0</div>
      <div class="trust-label">Camera crew, studio, or editing needed</div>
    </div>
  </div>
</div>

<!-- ── WHO IT'S FOR ── -->
<section>
  <div class="section-inner">
    <div class="text-center">
      <div class="section-tag">Who It's For</div>
      <h2 class="section-h2">Built for Professionals<br>Who Win Business on Trust</h2>
      <p class="section-sub">Your prospects size you up before they pick up the phone. A digital avatar makes sure they like what they see.</p>
    </div>
    <div class="industry-grid">

      <div class="industry-card">
        <span class="ind-icon">🔧</span>
        <h3 class="ind-h3">Tradies</h3>
        <div class="ind-tagline">"Win jobs before your competitors even call back."</div>
        <p class="ind-p">
          Homeowners search online, compare three quotes, and go with whoever feels most professional. A polished video introduction from your avatar — on your website or Google listing — builds trust before you've said a word.
          Stop losing jobs to less-skilled tradies who just look better online.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Quote intro videos</span>
          <span class="ind-use">Service explainers</span>
          <span class="ind-use">After-hours enquiries</span>
          <span class="ind-use">Testimonial narration</span>
          <span class="ind-use">Safety briefings</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">🏡</span>
        <h3 class="ind-h3">Property Agents</h3>
        <div class="ind-tagline">"More appraisals. More listings. More sold stickers."</div>
        <p class="ind-p">
          Vendors choose the agent who communicates best. Send a personalised video market update to every prospect on your list — with your avatar delivering it — and watch your appraisal conversion rate climb. Stand out in a suburb with 20 other agents.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Listing presentations</span>
          <span class="ind-use">Market update videos</span>
          <span class="ind-use">Open home follow-ups</span>
          <span class="ind-use">Buyer intro videos</span>
          <span class="ind-use">Off-market alerts</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">💼</span>
        <h3 class="ind-h3">Finance Brokers</h3>
        <div class="ind-tagline">"Explain the complex. Close more loans."</div>
        <p class="ind-p">
          Borrowers are nervous and overwhelmed. An avatar video that walks them through the process — in plain English, at 11pm when they're anxious — converts browsers into booked appointments. Build the trust that banks can't buy.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Loan product explainers</span>
          <span class="ind-use">Rate change alerts</span>
          <span class="ind-use">Pre-approval walkthroughs</span>
          <span class="ind-use">Refinance outreach</span>
          <span class="ind-use">Compliance education</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">⚖️</span>
        <h3 class="ind-h3">Lawyers & Solicitors</h3>
        <div class="ind-tagline">"Bill more hours. Spend fewer explaining the basics."</div>
        <p class="ind-p">
          Every hour spent explaining the same conveyancing process for the hundredth time is an hour you're not billing. Your avatar handles the FAQs, intake, and client education — so your meetings start at the interesting part.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Legal process explainers</span>
          <span class="ind-use">Client intake videos</span>
          <span class="ind-use">Settlement walkthroughs</span>
          <span class="ind-use">Will & estate guides</span>
          <span class="ind-use">Practice area intros</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">🧾</span>
        <h3 class="ind-h3">Tax Agents & Accountants</h3>
        <div class="ind-tagline">"Turn tax time chaos into a year-round client relationship."</div>
        <p class="ind-p">
          Most clients only think about their accountant in July. A digital avatar keeps you front of mind all year — sending proactive tax tips, deadline reminders, and service explainers that position you as the trusted advisor they can't afford to lose.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Tax time deadline reminders</span>
          <span class="ind-use">Deduction tip videos</span>
          <span class="ind-use">New client onboarding</span>
          <span class="ind-use">EOFY checklists</span>
          <span class="ind-use">Service package explainers</span>
          <span class="ind-use">ATO update alerts</span>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ── VIDEO GALLERY ── -->
<section style="background: rgba(245,158,11,0.02); border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
  <div class="section-inner">
    <div class="text-center">
      <div class="section-tag">See It In Action</div>
      <h2 class="section-h2">Real Use Cases.<br>Real Results.</h2>
      <p class="section-sub">Watch how professionals like you are using digital avatars to win more business, save time, and never miss a lead.</p>
    </div>

    <div class="video-grid">
      @php
      $videos = [
        [
          'id'    => 'ADD_YOUTUBE_ID_1',
          'ind'   => 'Tradies',
          'cat'   => 'Lead Generation',
          'title' => 'The Quote Intro That Wins Jobs',
          'desc'  => 'A 90-second avatar video on a tradie\'s website converts browsers into quote requests — 24/7, no phone needed.',
          'dur'   => '1:52',
          'bg'    => 'linear-gradient(135deg, #1c2a1a 0%, #0f1a0e 100%)',
          'emoji' => '🔧',
        ],
        [
          'id'    => 'evFW076v9pE',
          'ind'   => 'Property',
          'cat'   => 'Vendor Outreach',
          'title' => 'Personalised Market Updates at Scale',
          'desc'  => 'Send 200 vendors a personalised suburb report video. Same agent, same message, individually addressed.',
          'dur'   => '2:18',
          'bg'    => 'linear-gradient(135deg, #1a1c2a 0%, #0e0f1a 100%)',
          'emoji' => '🏡',
        ],
        [
          'id'    => 'ADD_YOUTUBE_ID_3',
          'ind'   => 'Finance',
          'cat'   => 'Client Education',
          'title' => 'Walking Borrowers Through the Process',
          'desc'  => 'Pre-approval, LVR, offset accounts — your avatar explains it calmly before the first meeting, so clients arrive ready.',
          'dur'   => '3:05',
          'bg'    => 'linear-gradient(135deg, #1a2a1a 0%, #0e1a0e 100%)',
          'emoji' => '💼',
        ],
        [
          'id'    => 'LydnIF2gpzo',
          'ind'   => 'Law',
          'cat'   => 'Client Intake',
          'title' => 'The FAQ Video That Saves 10 Hours a Week',
          'desc'  => 'One conveyancing explainer video, deployed on your site. Clients arrive at consultation already knowing the basics.',
          'dur'   => '2:44',
          'bg'    => 'linear-gradient(135deg, #2a1a1a 0%, #1a0e0e 100%)',
          'emoji' => '⚖️',
        ],
        [
          'id'    => 'ADD_YOUTUBE_ID_5',
          'ind'   => 'Property',
          'cat'   => 'Open Home Follow-Up',
          'title' => 'Same-Night Follow-Up for Every Buyer',
          'desc'  => 'Your avatar sends a personalised follow-up video to every open home attendee within hours. No other agent does this.',
          'dur'   => '1:38',
          'bg'    => 'linear-gradient(135deg, #1a1a2a 0%, #0e0e1a 100%)',
          'emoji' => '🔑',
        ],
        [
          'id'    => 'ADD_YOUTUBE_ID_6',
          'ind'   => 'Finance',
          'cat'   => 'Rate Alert Campaign',
          'title' => 'Rate Change? Your Clients Hear From You First',
          'desc'  => 'RBA announcement drops. Your avatar has already sent a personalised video to every client in your CRM. Loyalty locked.',
          'dur'   => '2:02',
          'bg'    => 'linear-gradient(135deg, #2a2a1a 0%, #1a1a0e 100%)',
          'emoji' => '📊',
        ],
        [
          'id'    => 'ADD_YOUTUBE_ID_7',
          'ind'   => 'Tradies',
          'cat'   => 'After-Hours Enquiries',
          'title' => 'Never Lose a Lead to Voicemail Again',
          'desc'  => 'Enquiry lands at 9pm. Your avatar responds instantly with your credentials, pricing, and a booking link.',
          'dur'   => '1:55',
          'bg'    => 'linear-gradient(135deg, #1a2a2a 0%, #0e1a1a 100%)',
          'emoji' => '⚡',
        ],
        [
          'id'    => 'ADD_YOUTUBE_ID_8',
          'ind'   => 'Law',
          'cat'   => 'Practice Area Intro',
          'title' => 'Your Practice Areas Explained in Under 2 Minutes',
          'desc'  => 'Website visitors understand exactly what you do and who you help — before they decide whether to book.',
          'dur'   => '2:29',
          'bg'    => 'linear-gradient(135deg, #251a2a 0%, #180e1a 100%)',
          'emoji' => '📋',
        ],
        [
          'id'    => 'LBGEgbbsqqw',
          'ind'   => 'Tax & Accounting',
          'cat'   => 'Year-Round Engagement',
          'title' => 'The EOFY Video That Keeps Clients Coming Back',
          'desc'  => 'A proactive tax-time checklist video from your avatar — sent to every client in June — cements your value before they even think about switching.',
          'dur'   => '2:11',
          'bg'    => 'linear-gradient(135deg, #1a2518 0%, #0e1a0c 100%)',
          'emoji' => '🧾',
        ],
      ];
      @endphp

      @foreach($videos as $v)
      <div class="video-card" onclick="openVideo('{{ $v['id'] }}', '{{ addslashes($v['title']) }}')">
        <div class="video-thumb">
          <div class="video-bg" style="background: {{ $v['bg'] }};">{{ $v['emoji'] }}</div>
          <div class="video-overlay"></div>
          <div class="video-ind">{{ $v['ind'] }}</div>
          <div class="play-btn"><div class="play-icon"></div></div>
          <div class="video-dur">{{ $v['dur'] }}</div>
        </div>
        <div class="video-info">
          <div class="video-cat">{{ $v['cat'] }}</div>
          <div class="video-title">{{ $v['title'] }}</div>
          <div class="video-desc">{{ $v['desc'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ── HOW IT WORKS ── -->
<section>
  <div class="section-inner">
    <div class="text-center" style="margin-bottom:3.5rem;">
      <div class="section-tag">How It Works</div>
      <h2 class="section-h2">Up and Running in a Day</h2>
      <p class="section-sub">No technical knowledge required. If you can send an email, you can have an avatar working for you by tomorrow.</p>
    </div>
    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <h3 class="step-h3">We Build Your Avatar</h3>
        <p class="step-p">Send us a short video or use one of our professional avatar templates. We'll clone your look and voice — or build a branded persona — in 24 hours.</p>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <h3 class="step-h3">You Write the Script</h3>
        <p class="step-p">Tell your avatar what to say. Use our industry templates for tradies, agents, brokers, and lawyers — or write your own. Takes about 10 minutes.</p>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <h3 class="step-h3">Deploy &amp; Win Business</h3>
        <p class="step-p">Embed on your website, share in emails, add to your Google Business profile. Your avatar starts converting leads while you focus on the work.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── LEAD FORM ── -->
<section class="form-section" id="get-started">
  <div class="section-inner">
    <div class="text-center" style="margin-bottom:2.5rem;">
      <div class="section-tag">Free Demo</div>
      <h2 class="section-h2">See <span class="grad">Your Avatar</span><br>Before You Commit to Anything</h2>
      <p class="section-sub">Tell us what you do and we'll show you a personalised demo built around your profession. No sales pitch. Just your avatar, ready to work.</p>
    </div>

    <div class="form-card">
      @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="flash-error">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif

      <form action="{{ route('digital-avatars.store') }}" method="POST">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label" for="name">Your Name *</label>
            <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Dave Johnson" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="email">Email *</label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="dave@djplumbing.com.au" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="company">Business Name</label>
            <input class="form-input" id="company" type="text" name="company" value="{{ old('company') }}" placeholder="DJ Plumbing &amp; Gas">
          </div>
          <div class="form-group">
            <label class="form-label" for="role">I am a… *</label>
            <select class="form-select" id="role" name="role" required>
              <option value="">Select your profession</option>
              <option value="Tradie" {{ old('role') === 'Tradie' ? 'selected' : '' }}>Tradie (electrician, plumber, builder…)</option>
              <option value="Property Agent" {{ old('role') === 'Property Agent' ? 'selected' : '' }}>Property / Real Estate Agent</option>
              <option value="Finance Broker" {{ old('role') === 'Finance Broker' ? 'selected' : '' }}>Finance / Mortgage Broker</option>
              <option value="Lawyer" {{ old('role') === 'Lawyer' ? 'selected' : '' }}>Lawyer / Solicitor / Conveyancer</option>
              <option value="Tax Agent / Accountant" {{ old('role') === 'Tax Agent / Accountant' ? 'selected' : '' }}>Tax Agent / Accountant</option>
              <option value="Other" {{ old('role') === 'Other' ? 'selected' : '' }}>Other professional</option>
            </select>
          </div>
          <div class="form-group full">
            <label class="form-label" for="use_case">What's your biggest challenge right now?</label>
            <select class="form-select" id="use_case" name="use_case">
              <option value="">Choose one…</option>
              <option value="Not enough leads" {{ old('use_case') === 'Not enough leads' ? 'selected' : '' }}>Not enough leads coming in</option>
              <option value="Losing to competitors" {{ old('use_case') === 'Losing to competitors' ? 'selected' : '' }}>Losing jobs / listings to competitors</option>
              <option value="Too much time on enquiries" {{ old('use_case') === 'Too much time on enquiries' ? 'selected' : '' }}>Spending too much time on basic enquiries</option>
              <option value="After-hours leads missed" {{ old('use_case') === 'After-hours leads missed' ? 'selected' : '' }}>Missing leads after hours</option>
              <option value="Need more content" {{ old('use_case') === 'Need more content' ? 'selected' : '' }}>Need more marketing content without the cost</option>
              <option value="Build trust faster" {{ old('use_case') === 'Build trust faster' ? 'selected' : '' }}>Want to build trust with clients faster</option>
            </select>
          </div>
        </div>
        <button type="submit" class="form-submit">Book My Free Demo →</button>
        <p class="form-note">🔒 No credit card · No commitment · We'll call you within 1 business day</p>
      </form>
    </div>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer class="page-footer">
  <div class="footer-inner">
    <span class="footer-copy">© 2026 ApiSpi. All rights reserved. &nbsp;·&nbsp; Built for Australian professionals.</span>
    <div class="footer-links">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('agents.index') }}">Agents</a>
      <a href="{{ route('contact') }}">Contact</a>
      <a href="{{ route('login') }}">Sign In</a>
    </div>
  </div>
</footer>

<!-- ── VIDEO MODAL ── -->
<div class="modal-overlay" id="videoModal" onclick="closeVideoIfOutside(event)">
  <div class="modal-box" id="modalBox">
    <div class="modal-top">
      <span class="modal-title-text" id="modalTitle"></span>
      <button class="modal-close-btn" onclick="closeVideo()">✕</button>
    </div>
    <div class="modal-video" id="modalVideo">
      <iframe id="modalIframe" src="" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
  </div>
</div>

<script>
  var shortsIds = ['LBGEgbbsqqw', 'evFW076v9pE'];

  function openVideo(videoId, title) {
    if (!videoId || videoId.startsWith('ADD_')) {
      alert('Demo video coming soon — check back shortly!');
      return;
    }
    var isShort = shortsIds.indexOf(videoId) !== -1;
    var mv = document.getElementById('modalVideo');
    mv.style.aspectRatio = isShort ? '9/16' : '16/9';
    mv.style.maxHeight   = isShort ? '80vh' : '';
    document.getElementById('modalBox').style.maxWidth = isShort ? '420px' : '900px';
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalIframe').src =
      'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
    document.getElementById('videoModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeVideo() {
    document.getElementById('videoModal').classList.remove('open');
    document.getElementById('modalIframe').src = '';
    document.body.style.overflow = '';
  }
  function closeVideoIfOutside(e) {
    if (e.target === document.getElementById('videoModal')) closeVideo();
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeVideo(); });
</script>

</body>
</html>
