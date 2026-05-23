@php $agent->load('skills', 'connectors'); @endphp
@extends('layouts.app')

@section('title', $agent->name . ' - ApiSpi')

@push('head')
@auth
@if(Auth::user()->is_admin)
<style>
    .admin-edit-bar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 8000; background: rgba(13,11,8,0.95); border-top: 1px solid rgba(217,119,6,0.3); padding: 0.6rem 1.5rem; display: flex; align-items: center; gap: 1rem; font-size: 0.8rem; color: rgba(252,211,77,0.7); backdrop-filter: blur(8px); }
    .admin-edit-bar strong { color: #FCD34D; }
    .admin-edit-bar a { padding: 0.3rem 0.75rem; border-radius: 0.375rem; background: rgba(217,119,6,0.15); border: 1px solid rgba(217,119,6,0.3); color: #FCD34D; text-decoration: none; font-weight: 600; transition: background 0.15s; }
    .admin-edit-bar a:hover { background: rgba(217,119,6,0.3); }
</style>
@endif
@endauth
@endpush

@section('content')
@auth
@if(Auth::user()->is_admin)
<div class="admin-edit-bar">
    <strong>Admin</strong>
    <a href="{{ route('admin.agents.edit', $agent->id) }}">Edit Agent ↗</a>
    <a href="{{ route('admin.agents.index') }}">All Agents</a>
</div>
@endif
@endauth

    <section class="agent-detail">
        <div class="container">
            <div class="agent-header">
                <div class="agent-info">
                    @if($agent->badge || $agent->category)
                    <div style="margin-bottom: 1.5rem;">
                        @php
                            $badgeLabel = $agent->badge ?? '';
                            $badgeStyles = match(strtolower($badgeLabel)) {
                                'popular'  => 'background: rgba(217,119,6,0.2); border: 1px solid #D97706; color: #FCD34D;',
                                'premium'  => 'background: rgba(239,68,68,0.2); border: 1px solid #EF4444; color: #FCA5A5;',
                                'new'      => 'background: rgba(0,217,126,0.1); border: 1px solid #00d97e; color: #00d97e;',
                                default    => 'background: rgba(107,114,128,0.2); border: 1px solid #6b7280; color: #9ca3af;',
                            };
                        @endphp
                        <span style="display: inline-block; {{ $badgeStyles }} padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                            @if($badgeLabel){{ $badgeLabel }} &bull; @endif{{ $agent->category }}
                        </span>
                    </div>
                    @endif

                    <h1>{{ $agent->name }}</h1>
                    <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1.5rem;">{{ $agent->description }}</p>

                    <div class="agent-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <span class="count">{{ $agent->rating }}/5 ({{ number_format((int) $agent->users_count) }}+ reviews)</span>
                    </div>

                    <div class="agent-price">{{ $agent->price }}</div>

                    <div class="agent-actions">
                        <a href="{{ route('checkout') }}?agent={{ urlencode($agent->checkout_name ?? $agent->name) }}&amount={{ $agent->price ? preg_replace('/[^0-9]/', '', $agent->price) : '0' }}" class="btn btn-primary">Subscribe Now</a>
                        <a href="mailto:sales@apispi.com?subject={{ urlencode($agent->name . ' Free Trial') }}" class="btn btn-secondary">Try Free Trial</a>
                    </div>

                    @if($agent->includes && count($agent->includes))
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(217,119,6,0.05); border: 1px solid rgba(217,119,6,0.2); border-radius: 1rem;">
                        <h4 style="margin-bottom: 1rem;">What's Included</h4>
                        <ul style="list-style: none;">
                            @foreach($agent->includes as $i => $item)
                            <li style="{{ $loop->last ? '' : 'margin-bottom: 0.75rem;' }} display: flex; align-items: center; gap: 0.75rem;">
                                <span style="color: #00d97e; font-weight: 700;">✓</span><span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <div class="agent-features">
                    @if($agent->features && count($agent->features))
                    <h2>Key Capabilities</h2>
                    <ul class="feature-list">
                        @foreach($agent->features as $feature)
                        <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if($agent->target_audience)
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(217,119,6,0.1);">
                        <h3 style="text-align: left; margin-bottom: 1rem;">Who It's For</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">{{ $agent->target_audience }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($agent->use_cases && count($agent->use_cases))
            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217,119,6,0.1);">
                <h2>Use Cases</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    @foreach($agent->use_cases as $case)
                    <div style="background: rgba(28,24,16,0.6); border: 1px solid rgba(217,119,6,0.1); border-radius: 1rem; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.75rem;">{{ $case['title'] }}</h3>
                        <p style="color: #6b7280;">{{ $case['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($agent->skills->isNotEmpty())
            @php $skillsByCategory = $agent->skills->groupBy('category'); @endphp
            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217,119,6,0.1);">
                <h2>Skills</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; margin-top: 2rem;">
                    @foreach($skillsByCategory as $category => $skills)
                    <div style="background: rgba(28,24,16,0.6); border: 1px solid rgba(217,119,6,0.1); border-radius: 0.875rem; padding: 1.25rem;">
                        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #D97706; margin-bottom: 0.75rem;">{{ $category }}</div>
                        <ul style="list-style: none;">
                            @foreach($skills as $skill)
                            <li style="{{ $loop->last ? '' : 'margin-bottom: 0.4rem;' }} display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #d1d5db;">
                                <span style="color: #D97706; font-size: 0.65rem;">◇</span> {{ $skill->name }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($agent->connectors->isNotEmpty())
            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217,119,6,0.1);">
                <h2>Required Connectors</h2>
                <p style="color: #6b7280; font-size: 0.95rem; margin-top: 0.75rem; margin-bottom: 2rem;">This agent works with the following integrations. Connect them from your dashboard to get started.</p>
                <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                    @foreach($agent->connectors as $connector)
                    <div style="background: rgba(28,24,16,0.6); border: 1px solid rgba(217,119,6,0.1); border-radius: 0.875rem; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; min-width: 160px;">
                        @if($connector->icon)
                        <span style="font-size: 1.4rem; line-height: 1;">{{ $connector->icon }}</span>
                        @endif
                        <div>
                            <div style="font-size: 0.9rem; font-weight: 600; color: #e5e7eb;">{{ $connector->name }}</div>
                            @if($connector->category)
                            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem;">{{ $connector->category }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($agent->pricing_plans && count($agent->pricing_plans))
            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217,119,6,0.1);">
                <h2>Pricing Plans</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
                    @foreach($agent->pricing_plans as $plan)
                    @php $recommended = !empty($plan['is_recommended']); @endphp
                    <div style="{{ $recommended ? 'background: linear-gradient(135deg, rgba(217,119,6,0.05) 0%, rgba(217,119,6,0.05) 100%); border: 2px solid #FCD34D; transform: scale(1.05);' : 'background: rgba(28,24,16,0.6); border: 1px solid rgba(217,119,6,0.1);' }} border-radius: 1rem; padding: 2rem; text-align: center;">
                        @if($recommended)
                        <div style="background: #FCD34D; color: #0D0B08; padding: 0.25rem 1rem; border-radius: 9999px; display: inline-block; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem;">POPULAR</div>
                        @endif
                        <h3 style="margin-bottom: 0.5rem;">{{ $plan['name'] }}</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">{{ $plan['description'] }}</p>
                        <div style="font-size: 2rem; font-weight: 700; color: #FCD34D; margin-bottom: 1.5rem;">
                            @if(!empty($plan['amount']))
                                {{ $plan['price'] }}<span style="font-size: 1rem; color: #6b7280;">/month</span>
                            @else
                                Custom
                            @endif
                        </div>
                        @if(!empty($plan['amount']))
                            @php $checkoutName = $plan['checkout_name'] ?? ($agent->checkout_name ?? $agent->name); @endphp
                            <a href="{{ route('checkout') }}?agent={{ urlencode($checkoutName) }}&amount={{ $plan['amount'] }}" class="btn btn-primary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Get Started</a>
                        @else
                            @php $contactSubject = $plan['contact_subject'] ?? ($agent->name . ' Enterprise Inquiry'); @endphp
                            <a href="mailto:sales@apispi.com?subject={{ urlencode($contactSubject) }}" class="btn btn-secondary" style="display: inline-block; width: auto; margin-bottom: 1rem;">Contact Sales</a>
                        @endif
                        @if(!empty($plan['features']))
                        <ul style="list-style: none; text-align: left; color: #6b7280; font-size: 0.9rem;">
                            @foreach($plan['features'] as $f)
                            <li style="{{ $loop->last ? '' : 'margin-bottom: 0.5rem;' }}">✓ {{ $f }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($agent->faqs && count($agent->faqs))
            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid rgba(217,119,6,0.1);">
                <h2>Frequently Asked Questions</h2>
                <div style="max-width: 600px; margin: 2rem auto;">
                    @foreach($agent->faqs as $faq)
                    <details style="{{ $loop->last ? '' : 'margin-bottom: 1rem;' }} padding: 1.5rem; background: rgba(28,24,16,0.6); border: 1px solid rgba(217,119,6,0.1); border-radius: 1rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">{{ $faq['question'] }}</summary>
                        <p style="margin-top: 1rem; color: #6b7280;">{{ $faq['answer'] }}</p>
                    </details>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>{{ $agent->cta_headline ?? ('Get Started with ' . $agent->name) }}</h2>
            <p>{{ $agent->cta_sub ?? 'Start automating your workflows today' }}</p>
            <div class="cta-buttons">
                <a href="{{ route('checkout') }}?agent={{ urlencode($agent->checkout_name ?? $agent->name) }}&amount={{ $agent->price ? preg_replace('/[^0-9]/', '', $agent->price) : '0' }}" class="btn btn-outline">Subscribe Now</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">Get Support</a>
            </div>
        </div>
    </section>
@endsection

@section('footer-extra')
    <p><a href="mailto:sales@apispi.com">Contact Sales</a> | <a href="mailto:payment@apispi.com">Payment Inquiries</a></p>
@endsection
