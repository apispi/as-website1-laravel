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
    .course-banner-generic { background: linear-gradient(135deg, #0d0400 0%, #2a1000 60%, #0d0400 100%); display: flex; align-items: flex-end; }
    .course-banner-inner { padding: 1.75rem 2rem; }
    .course-banner-category { font-size: 0.7rem; font-family: monospace; letter-spacing: 0.12em; color: #D97706; margin-bottom: 0.6rem; opacity: 0.85; }
    .course-banner-title { font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 0.5rem; }
    .course-banner-meta { font-size: 0.72rem; font-family: monospace; letter-spacing: 0.06em; color: #D97706; opacity: 0.65; }
    .badge-cert { background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.4); color: #93c5fd; }
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
            <p style="text-align:center;color:var(--gray);margin-top:0.5rem;">{{ $trainings->count() }} {{ Str::plural('course', $trainings->count()) }} · Updated {{ now()->format('F Y') }}</p>

            <div class="courses-grid">
                @foreach($trainings as $training)
                @php
                    $badgeClass = match(strtolower($training->badge ?? '')) {
                        'popular'       => 'badge-popular',
                        'new'           => 'badge-new',
                        'certification' => 'badge-cert',
                        default         => '',
                    };
                @endphp
                <div class="course-card">
                    <div class="course-banner course-banner-generic">
                        <div class="course-banner-inner">
                            <div class="course-banner-category">{{ strtoupper($training->category ?? 'Course') }}</div>
                            <div class="course-banner-title">{{ $training->title }}</div>
                            @if($training->format)
                            <div class="course-banner-meta">{{ strtoupper($training->format) }}{{ $training->duration ? ' · ' . strtoupper($training->duration) : '' }}</div>
                            @endif
                        </div>
                        @if($training->badge)
                        <span class="course-badge {{ $badgeClass }}">{{ $training->badge }}</span>
                        @endif
                    </div>
                    <div class="course-body">
                        <div class="course-meta">
                            @if($training->duration)
                            <span><span data-icon="clock" data-size="14" style="vertical-align:middle;margin-right:4px"></span> {{ $training->duration }}</span>
                            @endif
                            @if($training->modules_count)
                            <span><span data-icon="book" data-size="14" style="vertical-align:middle;margin-right:4px"></span> {{ $training->modules_count }} {{ $training->format === 'Workshop' ? 'topics' : 'modules' }}</span>
                            @endif
                            @if($training->level)
                            <span><span data-icon="star" data-size="14" style="vertical-align:middle;margin-right:4px"></span> {{ $training->level }}</span>
                            @endif
                        </div>
                        <h2>{{ $training->title }}</h2>
                        <p>{{ $training->description }}</p>
                        @if($training->instructor)
                        <div class="instructor-row">
                            <div class="instructor-avatar"><span data-icon="bolt" data-size="20" data-color="#ffffff"></span></div>
                            <div class="instructor-info">
                                <strong>{{ $training->instructor }}</strong>
                                @if($training->instructor_role)<span>{{ $training->instructor_role }}</span>@endif
                            </div>
                        </div>
                        @endif
                        @if($training->topics && count($training->topics))
                        <ul class="course-topics">
                            @foreach($training->topics as $topic)
                            <li>{{ $topic }}</li>
                            @endforeach
                        </ul>
                        @endif
                        @if($training->includes && count($training->includes))
                        <div style="margin-bottom:1.5rem;padding:1rem;background:rgba(0,217,255,0.05);border:1px solid rgba(0,217,255,0.15);border-radius:0.75rem;font-size:0.85rem;color:var(--gray);">
                            <strong style="color:var(--light);display:block;margin-bottom:0.4rem;">What's included</strong>
                            {{ collect($training->includes)->map(fn($i) => '✓ ' . $i)->implode('  ·  ') }}
                        </div>
                        @endif
                        <div class="course-footer">
                            @if($training->price)
                            <div class="course-price">{{ $training->price }} @if($training->price_unit)<span>/ {{ $training->price_unit }}</span>@endif</div>
                            @endif
                            @if($training->checkout_amount)
                            <a href="{{ route('checkout') }}?agent={{ urlencode($training->checkout_name ?? $training->title) }}&amount={{ $training->checkout_amount }}" class="btn btn-primary">Enrol Now</a>
                            @else
                            <a href="{{ route('contact') }}" class="btn btn-secondary">Get in Touch</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                @if($trainings->isEmpty())
                <p style="text-align:center;color:var(--gray);grid-column:1/-1;padding:3rem 0;">No courses available at the moment. Check back soon.</p>
                @endif
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
