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
                        <option value="government">Government &amp; Procurement</option>
                        <option value="security">Security &amp; Compliance</option>
                        <option value="architecture">Architecture &amp; Strategy</option>
                        <option value="communications">Communications &amp; Avatar</option>
                        <option value="knowledge">Knowledge Management</option>
                        <option value="cyber">Cyber Operations</option>
                        <option value="content">Content &amp; Marketing</option>
                        <option value="customer">Customer Support</option>
                        <option value="data">Data &amp; Analytics</option>
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

                @foreach($agents as $agent)
                <a href="{{ route('agents.show', $agent->slug) }}"
                   class="agent-card {{ $agent->is_featured ? 'featured-highlight' : '' }}"
                   data-name="{{ strtolower($agent->name) }}"
                   data-category="{{ strtolower($agent->category ?? '') }}"
                   data-badge="{{ strtolower($agent->badge ?? '') }}"
                   data-rating="{{ $agent->rating }}"
                   data-price="{{ preg_replace('/[^0-9.]/', '', $agent->price ?? '0') }}">
                    @if($agent->badge)
                        <div class="agent-badge {{ strtolower($agent->badge) === 'premium' ? 'premium' : '' }}">{{ $agent->badge }}</div>
                    @endif
                    <h3>{{ $agent->name }}</h3>
                    <p>{{ $agent->description }}</p>
                    <div class="agent-stats">
                        @if($agent->rating)<span>⭐ {{ $agent->rating }}/5</span>@endif
                        @if($agent->users_count)<span>{{ $agent->users_count }} users</span>@endif
                    </div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(217, 119, 6, 0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #FCD34D;">{{ $agent->price }}</span>
                        <span style="padding: 0.5rem 1rem; font-size: 0.9rem; background: rgba(217,119,6,0.15); border: 1px solid rgba(217,119,6,0.35); border-radius: 0.5rem; color: #FCD34D; font-weight: 600;">View →</span>
                    </div>
                </a>
                @endforeach

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

@push('scripts')
<script>
(function () {
    const searchInput    = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortFilter     = document.getElementById('sortFilter');
    const container      = document.getElementById('agentsContainer');

    function applyFilters() {
        const q   = searchInput.value.toLowerCase().trim();
        const cat = categoryFilter.value.toLowerCase();

        let cards = Array.from(container.querySelectorAll('.agent-card'));

        // Filter
        cards.forEach(card => {
            const name = card.dataset.name || '';
            const desc = card.querySelector('p')?.textContent.toLowerCase() || '';
            const cardCat = card.dataset.category || '';

            const matchesSearch = !q || name.includes(q) || desc.includes(q);
            const matchesCat    = !cat || cardCat.includes(cat);

            card.style.display = (matchesSearch && matchesCat) ? '' : 'none';
        });

        // Sort visible cards
        const visible = cards.filter(c => c.style.display !== 'none');
        const sort = sortFilter.value;

        visible.sort((a, b) => {
            if (sort === 'rating')     return parseFloat(b.dataset.rating || 0) - parseFloat(a.dataset.rating || 0);
            if (sort === 'price-low')  return parseFloat(a.dataset.price  || 0) - parseFloat(b.dataset.price  || 0);
            if (sort === 'price-high') return parseFloat(b.dataset.price  || 0) - parseFloat(a.dataset.price  || 0);
            return 0; // popular / newest: keep server order
        });

        visible.forEach(card => container.appendChild(card));
    }

    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
})();
</script>
@endpush
