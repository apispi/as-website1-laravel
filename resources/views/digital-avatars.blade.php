<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Avatars for Tradies, Property, Law, Accounting, Beauty & Hospitality | ApiSpi</title>
  <meta name="description" content="Win more business with a professional digital avatar. Made for tradies, property agents, lawyers, accountants, beauticians, and hotel marketers. Always on. Always impressive.">
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
      display: flex; align-items: center;
      padding: 90px 2rem 60px; position: relative; overflow: hidden;
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
      background-size: cover; background-position: center;
    }
    .video-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.65) 100%); }
    @keyframes play-pulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0.45); }
      60%       { box-shadow: 0 0 0 14px rgba(245,158,11,0); }
    }
    .play-btn {
      position: relative; z-index: 2;
      width: 68px; height: 68px; border-radius: 50%;
      background: rgba(245,158,11,0.55); backdrop-filter: blur(6px);
      border: 2.5px solid rgba(245,158,11,0.9);
      display: flex; align-items: center; justify-content: center;
      transition: all 0.2s;
      animation: play-pulse 2s ease-out infinite;
    }
    .video-card:hover .play-btn {
      background: var(--gold); border-color: var(--gold-lt);
      transform: scale(1.1); animation: none;
      box-shadow: 0 0 28px rgba(245,158,11,0.6);
    }
    .play-icon { width: 0; height: 0; border-style: solid; border-width: 11px 0 11px 22px; border-color: transparent transparent transparent #fff; margin-left: 5px; }
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

    /* ── PRICING ── */
    .pricing-card {
      max-width: 780px; margin: 0 auto;
      background: rgba(15,22,35,0.9); border: 2px solid rgba(245,158,11,0.35);
      border-radius: 1.5rem; padding: 3rem 3.5rem;
      display: grid; grid-template-columns: 1fr auto; gap: 3rem; align-items: center;
    }
    .pricing-badge {
      display: inline-block; padding: 0.3rem 0.85rem; border-radius: 99px;
      background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3);
      font-size: 0.7rem; font-weight: 700; color: var(--gold-lt);
      letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 1rem;
    }
    .pricing-name { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.4rem; }
    .pricing-tagline { font-size: 0.95rem; color: var(--muted); margin-bottom: 1.5rem; line-height: 1.6; }
    .pricing-includes { list-style: none; display: flex; flex-direction: column; gap: 0.55rem; }
    .pricing-includes li { font-size: 0.88rem; color: var(--muted); display: flex; align-items: flex-start; gap: 0.6rem; }
    .pricing-includes li::before { content: '✓'; color: var(--gold); font-weight: 700; flex-shrink: 0; margin-top: 0.05rem; }
    .pricing-right { text-align: center; }
    .pricing-amount { font-size: 3rem; font-weight: 900; background: linear-gradient(135deg, var(--gold-lt), var(--gold-dk)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; margin-bottom: 0.3rem; }
    .pricing-per { font-size: 0.82rem; color: var(--muted); margin-bottom: 1.5rem; }
    .btn-buy {
      display: inline-flex; align-items: center; justify-content: center;
      padding: 0.9rem 2rem; border-radius: 0.75rem;
      background: linear-gradient(135deg, var(--gold), var(--gold-dk));
      color: #1a0e00; font-size: 1rem; font-weight: 800;
      text-decoration: none; white-space: nowrap; transition: opacity 0.18s;
      border: none; cursor: pointer; font-family: inherit; min-width: 180px;
    }
    .btn-buy:hover { opacity: 0.88; }
    .pricing-note { font-size: 0.75rem; color: var(--dim); margin-top: 0.6rem; }
    @media (max-width: 640px) {
      .pricing-card { grid-template-columns: 1fr; padding: 2rem; text-align: center; }
      .pricing-includes li { justify-content: center; }
      .pricing-right { border-top: 1px solid var(--border); padding-top: 1.5rem; }
    }

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
      <a href="/checkout?agent=Digital+Avatar&amount=800&type=training" class="btn-gold">Buy Now — $800</a>
      <a href="#get-started" class="nav-link" style="font-size:0.85rem;">Book a Free Demo</a>
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
        <span class="ind-pill">Law</span>
        <span class="ind-pill">Accounting</span>
        <span class="ind-pill">Beauty</span>
        <span class="ind-pill">Hospitality</span>
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
      <p class="hero-note">Free demo available &nbsp;·&nbsp; or <a href="/checkout?agent=Digital+Avatar&amount=800&type=training" style="color:var(--gold-lt);text-decoration:underline;">buy direct for $800</a> &nbsp;·&nbsp; use code <strong style="color:var(--gold-lt);">SALE20</strong> for 20% off</p>
    </div>

    <div class="prof-grid">
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">🔧</span>
        <div class="prof-name">Dave Tradie</div>
        <div class="prof-role">Electrician · Sydney</div>
        <div class="prof-quote">"Hi, I'm Dave. Free quotes within 24 hours, fully licensed. Here's what I can do for you…"</div>
      </div>
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">🏡</span>
        <div class="prof-name">Sarah Chen</div>
        <div class="prof-role">Property Agent · Melbourne</div>
        <div class="prof-quote">"Thinking of selling? I've sold 12 homes in your suburb this year. Here's what yours could fetch…"</div>
      </div>
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">💅</span>
        <div class="prof-name">Mia Nguyen</div>
        <div class="prof-role">Beauty Therapist · Brisbane</div>
        <div class="prof-quote">"Here's exactly what your first facial includes and why clients come back every single month…"</div>
      </div>
      <div class="prof-card">
        <div class="prof-badge">Live</div>
        <span class="prof-emoji">🏨</span>
        <div class="prof-name">James Hartley</div>
        <div class="prof-role">Hotel Marketing · Gold Coast</div>
        <div class="prof-quote">"Book direct and I'll show you what makes a stay here different to anything else on the strip…"</div>
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
        ['id' => 'zzz9qbzW3HA', 'ind' => 'Tradie',         'cat' => 'Lead Generation',      'title' => 'The Quote Intro That Wins Jobs',               'desc' => 'A 90-second avatar video on a tradie\'s website converts browsers into quote requests — 24/7, no phone needed.',                                   'dur' => '1:52'],
        ['id' => 'l9qKz-0fFPA', 'ind' => 'Property Agent', 'cat' => 'Vendor Outreach',       'title' => 'Personalised Market Updates at Scale',         'desc' => 'Send 200 vendors a personalised suburb report video. Same agent, same message, individually addressed.',                                          'dur' => '2:18'],
        ['id' => 'atzI67DUlUM', 'ind' => 'Lawyer',         'cat' => 'Client Intake',         'title' => 'The FAQ Video That Saves 10 Hours a Week',    'desc' => 'One conveyancing explainer video, deployed on your site. Clients arrive at consultation already knowing the basics.',                             'dur' => '2:44'],
        ['id' => 'vnng3HU9mCM', 'ind' => 'Accountant',     'cat' => 'Year-Round Engagement', 'title' => 'The EOFY Video That Keeps Clients Coming Back','desc' => 'A proactive tax-time checklist video sent to every client in June — cements your value before they think about switching.',                       'dur' => '2:11'],
        ['id' => '-9JbFyjPmyY', 'ind' => 'Beautician',     'cat' => 'Client Conversion',     'title' => 'Fill Your Books While You\'re With a Client', 'desc' => 'Your avatar showcases your treatments and results around the clock — turning Instagram browsers into booked regulars.',                            'dur' => '1:45'],
        ['id' => '-G3XVPGsPXs', 'ind' => 'Hotel Marketer', 'cat' => 'Direct Bookings',       'title' => 'Turn Lookers Into Bookers — All Day, Every Day','desc' => 'An avatar walking guests through your rooms and local experiences drives direct bookings even when your team is offline.',                        'dur' => '2:05'],
      ];
      @endphp

      @foreach($videos as $v)
      <div class="video-card" onclick="openVideo('{{ $v['id'] }}', '{{ addslashes($v['title']) }}')">
        <div class="video-thumb">
          <div class="video-bg" style="background-image: url('https://img.youtube.com/vi/{{ $v['id'] }}/hqdefault.jpg');"></div>
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
        <h3 class="ind-h3">Tradie</h3>
        <div class="ind-tagline">"Win the job before your competitor calls back."</div>
        <p class="ind-p">
          Homeowners Google, compare three quotes, and pick whoever looks most credible. An avatar intro video on your website or Google listing builds that credibility instantly — before you've even answered the phone. Stop losing jobs to less-skilled tradies who just look better online.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Quote intro videos</span>
          <span class="ind-use">Service explainers</span>
          <span class="ind-use">After-hours enquiries</span>
          <span class="ind-use">Safety briefings</span>
          <span class="ind-use">Review narration</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">🏡</span>
        <h3 class="ind-h3">Property Agent</h3>
        <div class="ind-tagline">"More appraisals. More listings. More sold stickers."</div>
        <p class="ind-p">
          Vendors pick the agent who communicates best. Send a personalised suburb market update — delivered by your avatar — to every prospect on your list. Stand out in a suburb with 20 other agents competing for the same listing.
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
        <span class="ind-icon">⚖️</span>
        <h3 class="ind-h3">Lawyer</h3>
        <div class="ind-tagline">"Stop explaining the basics. Start billing for the complex."</div>
        <p class="ind-p">
          Every minute spent on the same conveyancing questions is a minute you're not billing. Your avatar handles client intake, FAQs, and process education — so consultations start where they should: at the part that actually needs a lawyer.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Client intake videos</span>
          <span class="ind-use">Legal process explainers</span>
          <span class="ind-use">Settlement walkthroughs</span>
          <span class="ind-use">Will & estate guides</span>
          <span class="ind-use">Practice area intros</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">🧾</span>
        <h3 class="ind-h3">Accountant</h3>
        <div class="ind-tagline">"Front of mind all year — not just in July."</div>
        <p class="ind-p">
          Most clients only think of their accountant at tax time. A digital avatar keeps you visible year-round — proactive tips, deadline reminders, and service explainers that make you the trusted advisor they'd never consider leaving.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Tax deadline reminders</span>
          <span class="ind-use">Deduction tip videos</span>
          <span class="ind-use">New client onboarding</span>
          <span class="ind-use">EOFY checklists</span>
          <span class="ind-use">ATO update alerts</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">💅</span>
        <h3 class="ind-h3">Beautician</h3>
        <div class="ind-tagline">"Fill your books without lifting a finger."</div>
        <p class="ind-p">
          Clients book the therapist they trust, not just the closest salon. An avatar showcasing your treatments, results, and personality converts Instagram browsers into loyal regulars — around the clock, even when you're fully booked and offline.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Treatment explainers</span>
          <span class="ind-use">Before & after walkthroughs</span>
          <span class="ind-use">New service launches</span>
          <span class="ind-use">Loyalty program promos</span>
          <span class="ind-use">Aftercare instructions</span>
        </div>
      </div>

      <div class="industry-card">
        <span class="ind-icon">🏨</span>
        <h3 class="ind-h3">Hotel Marketer</h3>
        <div class="ind-tagline">"Turn lookers into bookers. Every hour of the day."</div>
        <p class="ind-p">
          Travellers compare a dozen properties before committing. An avatar video showcasing your rooms, experiences, and local tips cuts through the noise and drives direct bookings — even when your team is offline and OTAs are eating your margin.
        </p>
        <div class="ind-uses">
          <span class="ind-use">Room & suite showcases</span>
          <span class="ind-use">Local experience guides</span>
          <span class="ind-use">Direct booking campaigns</span>
          <span class="ind-use">Event & wedding packages</span>
          <span class="ind-use">Guest FAQ videos</span>
        </div>
      </div>

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
        <p class="step-p">Tell your avatar what to say. Use our industry templates for tradies, agents, lawyers, accountants, beauticians, and hotel marketers — or write your own. Takes about 10 minutes.</p>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <h3 class="step-h3">Deploy &amp; Win Business</h3>
        <p class="step-p">Embed on your website, share in emails, add to your Google Business profile. Your avatar starts converting leads while you focus on the work.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── PRICING ── -->
<section style="background: rgba(245,158,11,0.03); border-top: 1px solid var(--border);">
  <div class="section-inner">
    <div class="text-center" style="margin-bottom: 2.5rem;">
      <div class="section-tag">Pricing</div>
      <h2 class="section-h2">Simple, Transparent Pricing</h2>
      <p class="section-sub">One avatar. One price. No subscriptions, no hidden fees.</p>
    </div>
    <div class="pricing-card">
      <div>
        <div class="pricing-badge">Most Popular</div>
        <div class="pricing-name">Digital Avatar — Starter</div>
        <p class="pricing-tagline">Everything you need to put a professional, always-on version of you in front of every prospect.</p>
        <ul class="pricing-includes">
          <li>Custom AI avatar built from your photo or video</li>
          <li>Up to 5 avatar videos in your first month</li>
          <li>Industry-specific script templates included</li>
          <li>Ready to embed on your website in 24 hours</li>
          <li>Delivered via secure ApiSpi dashboard</li>
          <li>Email support included</li>
        </ul>
      </div>
      <div class="pricing-right">
        <div class="pricing-amount">$800</div>
        <div class="pricing-per">AUD · one-off payment</div>
        <a href="/checkout?agent=Digital+Avatar&amount=800&type=training" class="btn-buy">Buy Now →</a>
        <div class="pricing-note">Secure checkout via Stripe<br>No subscription · Cancel anytime</div>
        <div style="margin-top:0.9rem; padding:0.55rem 1rem; border-radius:0.5rem; background:rgba(245,158,11,0.1); border:1px dashed rgba(245,158,11,0.4); font-size:0.8rem; color:var(--gold-lt);">
          Use code <strong style="letter-spacing:0.05em;">SALE20</strong> at checkout for 20% off
        </div>
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
        <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>
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
              <option value="Property Agent" {{ old('role') === 'Property Agent' ? 'selected' : '' }}>Property Agent</option>
              <option value="Lawyer" {{ old('role') === 'Lawyer' ? 'selected' : '' }}>Lawyer / Solicitor / Conveyancer</option>
              <option value="Accountant" {{ old('role') === 'Accountant' ? 'selected' : '' }}>Accountant / Tax Agent</option>
              <option value="Beautician" {{ old('role') === 'Beautician' ? 'selected' : '' }}>Beautician / Beauty Therapist</option>
              <option value="Hotel Marketer" {{ old('role') === 'Hotel Marketer' ? 'selected' : '' }}>Hotel / Hospitality Marketer</option>
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
  var shortsIds = ['LBGEgbbsqqw', 'l9qKz-0fFPA', 'zzz9qbzW3HA', '-9JbFyjPmyY'];

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
