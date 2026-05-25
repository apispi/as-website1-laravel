<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Avatars — Always On, Perfectly Human | ApiSpi</title>
  <meta name="description" content="Engage customers 24/7 with photorealistic digital avatars. Personalised video at scale — for sales, support, onboarding, and more.">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-9NX96RC3FF"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-9NX96RC3FF');
  </script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:      #06091a;
      --bg2:     #0c1130;
      --blue:    #3b82f6;
      --purple:  #8b5cf6;
      --cyan:    #06b6d4;
      --text:    #f1f5f9;
      --muted:   #94a3b8;
      --border:  rgba(59,130,246,0.18);
      --card-bg: rgba(255,255,255,0.035);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* ─── NAV ─── */
    .nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      padding: 0 2rem;
      background: rgba(6,9,26,0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(59,130,246,0.1);
    }
    .nav-inner {
      max-width: 1100px; margin: 0 auto;
      display: flex; align-items: center; justify-content: space-between;
      height: 60px;
    }
    .nav-logo {
      display: flex; align-items: center; gap: 0.5rem;
      text-decoration: none; color: var(--text); font-weight: 700; font-size: 1.1rem;
    }
    .nav-logo svg { width: 24px; height: 27px; }
    .nav-cta {
      display: inline-flex; align-items: center;
      padding: 0.45rem 1.1rem; border-radius: 0.5rem;
      background: linear-gradient(135deg, var(--blue), var(--purple));
      color: #fff; font-size: 0.875rem; font-weight: 600;
      text-decoration: none; transition: opacity 0.18s;
    }
    .nav-cta:hover { opacity: 0.88; }

    /* ─── HERO ─── */
    .hero {
      min-height: 100vh;
      display: flex; align-items: center;
      padding: 100px 2rem 80px;
      position: relative;
      overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 20% 40%, rgba(59,130,246,0.14) 0%, transparent 70%),
        radial-gradient(ellipse 60% 50% at 80% 60%, rgba(139,92,246,0.12) 0%, transparent 70%);
      pointer-events: none;
    }
    .hero-inner {
      max-width: 1100px; margin: 0 auto;
      display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;
      position: relative;
    }
    .hero-eyebrow {
      display: inline-flex; align-items: center; gap: 0.5rem;
      padding: 0.3rem 0.85rem; border-radius: 99px;
      background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25);
      font-size: 0.78rem; font-weight: 600; color: #93c5fd; letter-spacing: 0.06em;
      text-transform: uppercase; margin-bottom: 1.25rem;
    }
    .hero-eyebrow::before { content: '●'; font-size: 0.5rem; color: var(--cyan); }
    .hero h1 {
      font-size: clamp(2.2rem, 5vw, 3.4rem);
      font-weight: 800; line-height: 1.15; margin-bottom: 1.25rem;
      letter-spacing: -0.02em;
    }
    .grad { background: linear-gradient(135deg, #93c5fd, #c4b5fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-sub {
      font-size: 1.1rem; color: var(--muted); line-height: 1.7;
      margin-bottom: 2rem; max-width: 480px;
    }
    .hero-form { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .hero-input {
      flex: 1; min-width: 220px;
      padding: 0.75rem 1rem;
      background: rgba(255,255,255,0.06); border: 1px solid var(--border);
      border-radius: 0.625rem; color: var(--text); font-size: 1rem; font-family: inherit;
    }
    .hero-input:focus { outline: none; border-color: var(--blue); }
    .hero-input::placeholder { color: #475569; }
    .btn-grad {
      display: inline-flex; align-items: center;
      padding: 0.75rem 1.5rem; border-radius: 0.625rem;
      background: linear-gradient(135deg, var(--blue), var(--purple));
      color: #fff; font-size: 0.95rem; font-weight: 700;
      border: none; cursor: pointer; font-family: inherit; transition: opacity 0.18s;
      white-space: nowrap; text-decoration: none;
    }
    .btn-grad:hover { opacity: 0.88; }
    .hero-note { font-size: 0.78rem; color: #475569; margin-top: 0.75rem; }

    /* Hero visual — avatar grid */
    .hero-visual {
      display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
      position: relative;
    }
    .avatar-card {
      border-radius: 1rem; overflow: hidden;
      border: 1px solid var(--border);
      background: var(--card-bg);
      aspect-ratio: 3/4;
      display: flex; flex-direction: column; align-items: center; justify-content: flex-end;
      position: relative;
      cursor: pointer;
    }
    .avatar-card:nth-child(1) { transform: translateY(0); }
    .avatar-card:nth-child(2) { transform: translateY(1.5rem); }
    .avatar-card:nth-child(3) { transform: translateY(-1rem); }
    .avatar-card:nth-child(4) { transform: translateY(0.5rem); }
    .avatar-card-bg {
      position: absolute; inset: 0;
      background-size: cover; background-position: center top;
    }
    .avatar-card-bg-1 { background: linear-gradient(160deg, #1e3a5f 0%, #0f2040 40%, #0a1628 100%); }
    .avatar-card-bg-2 { background: linear-gradient(160deg, #2d1b4e 0%, #1a0f3a 40%, #0e0820 100%); }
    .avatar-card-bg-3 { background: linear-gradient(160deg, #1a3040 0%, #0f2230 40%, #080f1a 100%); }
    .avatar-card-bg-4 { background: linear-gradient(160deg, #2a1f50 0%, #18123a 40%, #0c0920 100%); }
    .avatar-figure {
      position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
      font-size: 4.5rem; line-height: 1; opacity: 0.9;
    }
    .avatar-label {
      position: relative; z-index: 2;
      width: 100%; padding: 1rem 0.75rem 0.75rem;
      background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
      font-size: 0.72rem; font-weight: 600; color: #cbd5e1; text-align: center;
    }
    .avatar-live {
      position: absolute; top: 0.6rem; right: 0.6rem; z-index: 2;
      display: inline-flex; align-items: center; gap: 0.3rem;
      padding: 0.2rem 0.5rem; border-radius: 99px;
      background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3);
      font-size: 0.65rem; font-weight: 700; color: #10b981; letter-spacing: 0.04em;
    }
    .avatar-live::before { content: '●'; font-size: 0.45rem; }

    /* ─── STATS ─── */
    .stats {
      background: rgba(59,130,246,0.04);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 2.5rem 2rem;
    }
    .stats-inner {
      max-width: 1100px; margin: 0 auto;
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem;
      text-align: center;
    }
    .stat-num { font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, var(--blue), var(--purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .stat-label { font-size: 0.82rem; color: var(--muted); margin-top: 0.25rem; }

    /* ─── SECTION SHARED ─── */
    section { padding: 6rem 2rem; }
    .section-inner { max-width: 1100px; margin: 0 auto; }
    .section-tag {
      display: inline-block;
      padding: 0.25rem 0.75rem; border-radius: 99px;
      background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.25);
      font-size: 0.72rem; font-weight: 700; color: #c4b5fd;
      letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1rem;
    }
    .section-h2 { font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; letter-spacing: -0.02em; margin-bottom: 1rem; }
    .section-sub { font-size: 1.05rem; color: var(--muted); max-width: 560px; line-height: 1.7; margin-bottom: 3rem; }
    .text-center { text-align: center; }
    .text-center .section-sub { margin-left: auto; margin-right: auto; }

    /* ─── VIDEO GALLERY ─── */
    .video-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.25rem;
    }
    .video-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 1rem; overflow: hidden;
      cursor: pointer; transition: transform 0.2s, border-color 0.2s;
      position: relative;
    }
    .video-card:hover { transform: translateY(-4px); border-color: rgba(59,130,246,0.4); }
    .video-thumb {
      aspect-ratio: 16/9;
      position: relative;
      display: flex; align-items: center; justify-content: center;
    }
    .video-thumb-bg {
      position: absolute; inset: 0;
      background-size: cover; background-position: center;
    }
    .video-thumb-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.55) 100%);
    }
    .play-btn {
      position: relative; z-index: 2;
      width: 52px; height: 52px; border-radius: 50%;
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(8px);
      border: 2px solid rgba(255,255,255,0.4);
      display: flex; align-items: center; justify-content: center;
      transition: all 0.2s;
    }
    .video-card:hover .play-btn {
      background: rgba(59,130,246,0.5);
      border-color: rgba(255,255,255,0.7);
      transform: scale(1.1);
    }
    .play-icon { width: 0; height: 0; border-style: solid; border-width: 9px 0 9px 18px; border-color: transparent transparent transparent #fff; margin-left: 4px; }
    .video-duration {
      position: absolute; bottom: 0.5rem; right: 0.6rem; z-index: 2;
      font-size: 0.7rem; font-weight: 600; color: #fff;
      background: rgba(0,0,0,0.65); padding: 0.15rem 0.4rem; border-radius: 0.25rem;
    }
    .video-info { padding: 1rem 1.1rem 1.1rem; }
    .video-category {
      font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
      color: #93c5fd; margin-bottom: 0.3rem;
    }
    .video-title { font-size: 0.95rem; font-weight: 600; color: var(--text); margin-bottom: 0.35rem; line-height: 1.4; }
    .video-desc { font-size: 0.8rem; color: var(--muted); line-height: 1.5; }

    /* ─── VIDEO MODAL ─── */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 9999;
      background: rgba(0,0,0,0.88);
      backdrop-filter: blur(6px);
      display: flex; align-items: center; justify-content: center;
      padding: 1.5rem;
      opacity: 0; pointer-events: none; transition: opacity 0.22s;
    }
    .modal-overlay.open { opacity: 1; pointer-events: all; }
    .modal-box {
      width: 100%; max-width: 900px;
      background: #0c1130;
      border: 1px solid var(--border);
      border-radius: 1rem; overflow: hidden;
      transform: scale(0.95); transition: transform 0.22s;
    }
    .modal-overlay.open .modal-box { transform: scale(1); }
    .modal-top {
      display: flex; justify-content: space-between; align-items: center;
      padding: 0.875rem 1.25rem;
      border-bottom: 1px solid var(--border);
    }
    .modal-title-text { font-size: 0.9rem; font-weight: 600; color: var(--text); }
    .modal-close-btn {
      background: none; border: none; color: var(--muted); cursor: pointer;
      font-size: 1.25rem; line-height: 1; padding: 0.2rem; transition: color 0.15s;
    }
    .modal-close-btn:hover { color: var(--text); }
    .modal-video { aspect-ratio: 16/9; background: #000; }
    .modal-video iframe { width: 100%; height: 100%; border: none; display: block; }

    /* ─── FEATURES ─── */
    .feature-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;
    }
    .feature-card {
      padding: 1.75rem;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 1rem;
      transition: border-color 0.2s;
    }
    .feature-card:hover { border-color: rgba(59,130,246,0.35); }
    .feature-icon {
      width: 48px; height: 48px; border-radius: 0.75rem; margin-bottom: 1.1rem;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem;
      background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2);
    }
    .feature-h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .feature-p { font-size: 0.875rem; color: var(--muted); line-height: 1.6; }

    /* ─── USE CASES ─── */
    .use-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;
    }
    .use-card {
      padding: 1.5rem 1.25rem;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 1rem; text-align: center;
      transition: all 0.2s;
    }
    .use-card:hover { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.3); transform: translateY(-2px); }
    .use-icon { font-size: 1.8rem; margin-bottom: 0.75rem; }
    .use-h3 { font-size: 0.88rem; font-weight: 700; margin-bottom: 0.35rem; }
    .use-p { font-size: 0.78rem; color: var(--muted); line-height: 1.5; }

    /* ─── LEAD FORM ─── */
    .form-section {
      background: linear-gradient(135deg, rgba(59,130,246,0.07) 0%, rgba(139,92,246,0.07) 100%);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
    }
    .form-card {
      max-width: 640px; margin: 0 auto;
      background: rgba(12,17,48,0.8);
      border: 1px solid var(--border);
      border-radius: 1.25rem; padding: 2.5rem;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: #94a3b8; }
    .form-input, .form-select {
      padding: 0.75rem 1rem;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(59,130,246,0.2);
      border-radius: 0.625rem; color: var(--text); font-size: 0.95rem; font-family: inherit;
      transition: border-color 0.18s; width: 100%;
    }
    .form-input:focus, .form-select:focus { outline: none; border-color: var(--blue); }
    .form-input::placeholder { color: #334155; }
    .form-select option { background: #0c1130; }
    .form-submit {
      width: 100%; margin-top: 0.5rem;
      padding: 0.875rem;
      background: linear-gradient(135deg, var(--blue), var(--purple));
      border: none; border-radius: 0.625rem;
      color: #fff; font-size: 1rem; font-weight: 700;
      cursor: pointer; font-family: inherit; transition: opacity 0.18s;
    }
    .form-submit:hover { opacity: 0.88; }
    .form-privacy { font-size: 0.75rem; color: #475569; text-align: center; margin-top: 0.75rem; }

    .flash-success {
      background: rgba(16,185,129,0.1);
      border: 1px solid rgba(16,185,129,0.3);
      color: #10b981; border-radius: 0.75rem;
      padding: 1rem 1.25rem; font-size: 0.9rem;
      margin-bottom: 1.5rem; text-align: center;
    }
    .flash-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; border-radius: 0.75rem; padding: 0.75rem 1rem; font-size: 0.875rem; margin-bottom: 1rem; }

    /* ─── FOOTER ─── */
    .page-footer {
      padding: 2rem;
      border-top: 1px solid rgba(255,255,255,0.06);
      text-align: center;
    }
    .footer-inner { max-width: 1100px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .footer-copy { font-size: 0.82rem; color: #475569; }
    .footer-links { display: flex; gap: 1.5rem; }
    .footer-links a { font-size: 0.82rem; color: #475569; text-decoration: none; }
    .footer-links a:hover { color: var(--muted); }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 768px) {
      .hero-inner { grid-template-columns: 1fr; gap: 2.5rem; }
      .hero-visual { order: -1; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; }
      .avatar-card { aspect-ratio: 3/5; }
      .avatar-card:nth-child(n) { transform: none; }
      .stats-inner { grid-template-columns: repeat(2, 1fr); }
      .form-grid { grid-template-columns: 1fr; }
      .footer-inner { flex-direction: column; text-align: center; }
    }
    @media (max-width: 480px) {
      .hero-visual { grid-template-columns: repeat(2, 1fr); }
      .avatar-card:nth-child(3), .avatar-card:nth-child(4) { display: none; }
    }
  </style>
</head>
<body>

  <!-- ── NAV ── -->
  <nav class="nav">
    <div class="nav-inner">
      <a href="{{ route('home') }}" class="nav-logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27">
          <defs><linearGradient id="nlg" x1=".5" y1="0" x2=".5" y2="1">
            <stop offset="0%" stop-color="#93c5fd"/><stop offset="100%" stop-color="#3b82f6"/>
          </linearGradient></defs>
          <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#nlg)"/>
          <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#nlg)"/>
        </svg>
        ApiSpi
      </a>
      <a href="#get-started" class="nav-cta">Request a Demo</a>
    </div>
  </nav>

  <!-- ── HERO ── -->
  <section class="hero">
    <div class="hero-inner">
      <div>
        <div class="hero-eyebrow">Now in Early Access</div>
        <h1>Your Brand.<br><span class="grad">Always On.</span><br>Perfectly Human.</h1>
        <p class="hero-sub">
          Engage customers 24/7 with photorealistic digital avatars that speak your language, know your products, and never have a bad day.
        </p>
        <form class="hero-form" action="{{ route('digital-avatars.store') }}" method="POST">
          @csrf
          <input type="hidden" name="use_case" value="hero">
          <input
            class="hero-input"
            type="email" name="email"
            placeholder="Enter your work email"
            required
            value="{{ old('email') }}">
          <input
            class="hero-input"
            type="text" name="name"
            placeholder="Your name"
            required
            value="{{ old('name') }}"
            style="max-width:180px;">
          <button type="submit" class="btn-grad">Get Early Access →</button>
        </form>
        <p class="hero-note">No credit card required &nbsp;·&nbsp; Response within 1 business day</p>
      </div>

      <div class="hero-visual">
        <div class="avatar-card">
          <div class="avatar-card-bg avatar-card-bg-1"></div>
          <div class="avatar-live">LIVE</div>
          <div class="avatar-figure">🧑‍💼</div>
          <div class="avatar-label">Sales Rep</div>
        </div>
        <div class="avatar-card">
          <div class="avatar-card-bg avatar-card-bg-2"></div>
          <div class="avatar-live">LIVE</div>
          <div class="avatar-figure">👩‍🏫</div>
          <div class="avatar-label">Trainer</div>
        </div>
        <div class="avatar-card">
          <div class="avatar-card-bg avatar-card-bg-3"></div>
          <div class="avatar-live">LIVE</div>
          <div class="avatar-figure">🧑‍💻</div>
          <div class="avatar-label">Support Agent</div>
        </div>
        <div class="avatar-card">
          <div class="avatar-card-bg avatar-card-bg-4"></div>
          <div class="avatar-live">LIVE</div>
          <div class="avatar-figure">👩‍⚕️</div>
          <div class="avatar-label">Specialist</div>
        </div>
      </div>
    </div>
  </section>

  @if(session('success'))
  <div style="max-width:700px;margin:0 auto;padding:0 2rem;">
    <div class="flash-success">{{ session('success') }}</div>
  </div>
  @endif

  <!-- ── STATS ── -->
  <div class="stats">
    <div class="stats-inner">
      <div>
        <div class="stat-num">10×</div>
        <div class="stat-label">More video content, same budget</div>
      </div>
      <div>
        <div class="stat-num">24/7</div>
        <div class="stat-label">Always-on customer engagement</div>
      </div>
      <div>
        <div class="stat-num">40+</div>
        <div class="stat-label">Languages supported</div>
      </div>
      <div>
        <div class="stat-num">3 min</div>
        <div class="stat-label">To generate a personalised video</div>
      </div>
    </div>
  </div>

  <!-- ── VIDEO GALLERY ── -->
  <section id="gallery">
    <div class="section-inner">
      <div class="text-center">
        <div class="section-tag">See It In Action</div>
        <h2 class="section-h2">What Digital Avatars<br>Can Do For You</h2>
        <p class="section-sub">See how leading teams are using digital avatars to scale their presence, personalise outreach, and reduce production costs.</p>
      </div>

      <div class="video-grid">
        @php
        $videos = [
          [
            'id'       => 'ADD_YOUTUBE_ID_1',
            'cat'      => 'Sales & Outreach',
            'title'    => 'Personalised Sales Videos at Scale',
            'desc'     => 'Send hundreds of bespoke video pitches with your avatar delivering tailored messages to each prospect.',
            'duration' => '2:14',
            'grad'     => 'linear-gradient(135deg, #1e3a5f 0%, #0f2040 100%)',
            'emoji'    => '🎯',
          ],
          [
            'id'       => 'ADD_YOUTUBE_ID_2',
            'cat'      => 'E-Learning',
            'title'    => 'AI Instructor Avatars for Online Courses',
            'desc'     => 'Update course content instantly without a camera crew. Your instructor avatar stays consistent across every module.',
            'duration' => '3:02',
            'grad'     => 'linear-gradient(135deg, #2d1b4e 0%, #1a0f3a 100%)',
            'emoji'    => '📚',
          ],
          [
            'id'       => 'ADD_YOUTUBE_ID_3',
            'cat'      => 'Customer Support',
            'title'    => '24/7 Support Agent That Never Sleeps',
            'desc'     => 'Handle common queries, product walk-throughs, and FAQs with a lifelike avatar on your website or app.',
            'duration' => '1:58',
            'grad'     => 'linear-gradient(135deg, #1a3040 0%, #0f2230 100%)',
            'emoji'    => '🛎',
          ],
          [
            'id'       => 'ADD_YOUTUBE_ID_4',
            'cat'      => 'Internal Comms',
            'title'    => 'CEO Announcements Without the Studio',
            'desc'     => 'Deliver polished leadership communications in minutes. Consistent tone, on-brand presentation, every time.',
            'duration' => '2:30',
            'grad'     => 'linear-gradient(135deg, #2a1f50 0%, #18123a 100%)',
            'emoji'    => '📣',
          ],
          [
            'id'       => 'ADD_YOUTUBE_ID_5',
            'cat'      => 'Social Media',
            'title'    => 'Daily Content Without Daily Filming',
            'desc'     => 'Keep your brand voice consistent across TikTok, LinkedIn, and Instagram — without stepping in front of a camera.',
            'duration' => '1:45',
            'grad'     => 'linear-gradient(135deg, #0f3040 0%, #082030 100%)',
            'emoji'    => '📱',
          ],
          [
            'id'       => 'ADD_YOUTUBE_ID_6',
            'cat'      => 'Multilingual',
            'title'    => 'One Avatar. 40+ Languages.',
            'desc'     => 'Enter new markets without re-filming. Your avatar speaks fluent French, Spanish, Mandarin and more with lip-sync accuracy.',
            'duration' => '2:47',
            'grad'     => 'linear-gradient(135deg, #1b3050 0%, #0d1e38 100%)',
            'emoji'    => '🌏',
          ],
          [
            'id'       => 'ADD_YOUTUBE_ID_7',
            'cat'      => 'Product Demos',
            'title'    => 'Interactive Product Walk-Throughs',
            'desc'     => 'Let your avatar guide prospects through features, pricing, and setup — embedded directly in your product.',
            'duration' => '3:15',
            'grad'     => 'linear-gradient(135deg, #2a1040 0%, #180a28 100%)',
            'emoji'    => '🚀',
          ],
          [
            'id'       => 'ADD_YOUTUBE_ID_8',
            'cat'      => 'HR & Onboarding',
            'title'    => 'Scalable Onboarding for Every New Hire',
            'desc'     => 'Deliver consistent, engaging onboarding experiences. Update once and every future hire gets the same great introduction.',
            'duration' => '2:55',
            'grad'     => 'linear-gradient(135deg, #0e3028 0%, #071c18 100%)',
            'emoji'    => '🤝',
          ],
        ];
        @endphp

        @foreach($videos as $v)
        <div class="video-card" onclick="openVideo('{{ $v['id'] }}', '{{ addslashes($v['title']) }}')">
          <div class="video-thumb">
            <div class="video-thumb-bg" style="background: {{ $v['grad'] }}; display:flex; align-items:center; justify-content:center; font-size:3.5rem;">
              {{ $v['emoji'] }}
            </div>
            <div class="video-thumb-overlay"></div>
            <div class="play-btn"><div class="play-icon"></div></div>
            <div class="video-duration">{{ $v['duration'] }}</div>
          </div>
          <div class="video-info">
            <div class="video-category">{{ $v['cat'] }}</div>
            <div class="video-title">{{ $v['title'] }}</div>
            <div class="video-desc">{{ $v['desc'] }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ── FEATURES ── -->
  <section style="background: rgba(59,130,246,0.03); border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
    <div class="section-inner">
      <div class="text-center" style="margin-bottom: 3rem;">
        <div class="section-tag">Why Digital Avatars</div>
        <h2 class="section-h2">Built for the Way<br>Modern Teams Work</h2>
      </div>
      <div class="feature-grid">
        <div class="feature-card">
          <div class="feature-icon">⚡</div>
          <h3 class="feature-h3">Generate in Minutes</h3>
          <p class="feature-p">From script to finished video in under 5 minutes. No camera, no lighting, no post-production.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🎨</div>
          <h3 class="feature-h3">Fully On-Brand</h3>
          <p class="feature-p">Your avatar. Your background. Your voice. Customise every detail to match your brand identity.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🔁</div>
          <h3 class="feature-h3">Update Instantly</h3>
          <p class="feature-p">Product changed? New pricing? Swap the script and regenerate. No re-shoots, no reshooting fees.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">📊</div>
          <h3 class="feature-h3">Track Engagement</h3>
          <p class="feature-p">See who watched, how long, and where they dropped off. Optimise your avatar videos like any other asset.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🔗</div>
          <h3 class="feature-h3">Integrate Everywhere</h3>
          <p class="feature-p">Embed in your website, CRM, LMS, or send via email. Works with your existing workflows.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🔒</div>
          <h3 class="feature-h3">Secure & Compliant</h3>
          <p class="feature-p">Enterprise-grade security, GDPR-ready, with full content moderation and IP protection built in.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── USE CASES ── -->
  <section>
    <div class="section-inner">
      <div class="text-center" style="margin-bottom: 3rem;">
        <div class="section-tag">Use Cases</div>
        <h2 class="section-h2">Perfect For Your Team</h2>
      </div>
      <div class="use-grid">
        <div class="use-card">
          <div class="use-icon">💼</div>
          <h3 class="use-h3">Sales Teams</h3>
          <p class="use-p">Video prospecting and follow-ups at scale</p>
        </div>
        <div class="use-card">
          <div class="use-icon">🎓</div>
          <h3 class="use-h3">Learning & Dev</h3>
          <p class="use-p">Course content and compliance training</p>
        </div>
        <div class="use-card">
          <div class="use-icon">🛍</div>
          <h3 class="use-h3">Marketing</h3>
          <p class="use-p">Ads, social content, and product demos</p>
        </div>
        <div class="use-card">
          <div class="use-icon">🏥</div>
          <h3 class="use-h3">Healthcare</h3>
          <p class="use-p">Patient education and onboarding</p>
        </div>
        <div class="use-card">
          <div class="use-icon">🏦</div>
          <h3 class="use-h3">Finance</h3>
          <p class="use-p">Explainers, advisories, and client comms</p>
        </div>
        <div class="use-card">
          <div class="use-icon">🏗</div>
          <h3 class="use-h3">HR & Ops</h3>
          <p class="use-p">Onboarding, policy updates, and culture</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── LEAD FORM ── -->
  <section class="form-section" id="get-started">
    <div class="section-inner">
      <div class="text-center" style="margin-bottom: 2.5rem;">
        <div class="section-tag">Early Access</div>
        <h2 class="section-h2">Ready to See Your<br><span class="grad">Avatar in Action?</span></h2>
        <p class="section-sub">Tell us about your use case and we'll show you a personalised demo built around your brand.</p>
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
              <label class="form-label" for="name">Full Name *</label>
              <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Alex Johnson" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="email">Work Email *</label>
              <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="alex@company.com" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="company">Company</label>
              <input class="form-input" id="company" type="text" name="company" value="{{ old('company') }}" placeholder="Acme Corp">
            </div>
            <div class="form-group">
              <label class="form-label" for="role">Your Role</label>
              <select class="form-select" id="role" name="role">
                <option value="">Select role…</option>
                <option value="Founder / CEO" {{ old('role') === 'Founder / CEO' ? 'selected' : '' }}>Founder / CEO</option>
                <option value="Sales Leader" {{ old('role') === 'Sales Leader' ? 'selected' : '' }}>Sales Leader</option>
                <option value="Marketing" {{ old('role') === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                <option value="L&D / HR" {{ old('role') === 'L&D / HR' ? 'selected' : '' }}>L&D / HR</option>
                <option value="Product / Tech" {{ old('role') === 'Product / Tech' ? 'selected' : '' }}>Product / Tech</option>
                <option value="Other" {{ old('role') === 'Other' ? 'selected' : '' }}>Other</option>
              </select>
            </div>
            <div class="form-group full">
              <label class="form-label" for="use_case">Primary Use Case</label>
              <select class="form-select" id="use_case" name="use_case">
                <option value="">What will you use avatars for?</option>
                <option value="Sales Outreach" {{ old('use_case') === 'Sales Outreach' ? 'selected' : '' }}>Sales Outreach</option>
                <option value="E-Learning / Training" {{ old('use_case') === 'E-Learning / Training' ? 'selected' : '' }}>E-Learning / Training</option>
                <option value="Customer Support" {{ old('use_case') === 'Customer Support' ? 'selected' : '' }}>Customer Support</option>
                <option value="Marketing Content" {{ old('use_case') === 'Marketing Content' ? 'selected' : '' }}>Marketing Content</option>
                <option value="Internal Comms" {{ old('use_case') === 'Internal Comms' ? 'selected' : '' }}>Internal Communications</option>
                <option value="Product Demos" {{ old('use_case') === 'Product Demos' ? 'selected' : '' }}>Product Demos</option>
                <option value="Other" {{ old('use_case') === 'Other' ? 'selected' : '' }}>Other</option>
              </select>
            </div>
          </div>
          <button type="submit" class="form-submit">Request My Personalised Demo →</button>
          <p class="form-privacy">🔒 No spam. No credit card. We'll reach out within 1 business day.</p>
        </form>
      </div>
    </div>
  </section>

  <!-- ── FOOTER ── -->
  <footer class="page-footer">
    <div class="footer-inner">
      <span class="footer-copy">© 2026 ApiSpi. All rights reserved.</span>
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
    <div class="modal-box">
      <div class="modal-top">
        <span class="modal-title-text" id="modalTitle"></span>
        <button class="modal-close-btn" onclick="closeVideo()">✕</button>
      </div>
      <div class="modal-video">
        <iframe id="modalIframe" src="" allow="autoplay; encrypted-media" allowfullscreen></iframe>
      </div>
    </div>
  </div>

  <script>
    function openVideo(videoId, title) {
      if (!videoId || videoId.startsWith('ADD_')) {
        alert('Demo video coming soon — check back shortly!');
        return;
      }
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

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeVideo();
    });
  </script>

</body>
</html>
