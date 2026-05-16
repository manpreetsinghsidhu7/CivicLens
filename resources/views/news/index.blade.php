@extends('layouts.app')

@section('title', 'News')

@section('styles')
<style>
    .news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
    .filter-bar { background: var(--cl-white); border: 1px solid var(--cl-border); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; }
    .news-card-link { text-decoration: none; color: inherit; display: block; height: 100%; }
    .news-card-link:hover .cl-card { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .sort-pills { display: flex; gap: 0.4rem; flex-wrap: wrap; }
    .sort-pill {
        padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500;
        border: 1px solid var(--cl-border); background: var(--cl-white); color: var(--cl-gray);
        text-decoration: none; transition: all 0.2s;
    }
    .sort-pill:hover, .sort-pill.active { background: var(--cl-primary); color: #fff; border-color: var(--cl-primary); }
</style>
@endsection

@section('content')
<div class="container py-4">
    <h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1rem;">
        <i class="bi bi-newspaper text-primary"></i> Government News
    </h1>

    <!-- Searching State -->
    @if($searching)
        <div class="text-center py-5">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
            <h3 style="font-weight:700;">Searching news about "{{ request('search') }}"...</h3>
            <p class="text-muted">We're checking global news sources for the latest updates.</p>
            <script>
                setTimeout(function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('fetch', '1');
                    window.location.href = url.toString();
                }, 1500);
            </script>
        </div>
    @else
        <!-- Auto-fetch Success Banner -->
        @if($autoFetched)
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius:10px; border:none; background:#dcfce7; color:#166534;">
                <i class="bi bi-check-circle-fill me-2"></i>
                Successfully searched and imported latest news about <strong>"{{ request('search') }}"</strong>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('news.index') }}" id="filterForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label" style="font-size:0.8rem; font-weight:500;">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search news..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" style="font-size:0.8rem; font-weight:500;">Category</label>
                        <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" style="font-size:0.8rem; font-weight:500;">Language</label>
                        <select name="language" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Languages</option>
                            @foreach($allLanguages as $lang)
                                <option value="{{ $lang }}" {{ (request('language', 'English') == $lang) ? 'selected' : '' }}>{{ $lang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" style="font-size:0.8rem; font-weight:500;">Source</label>
                        <select name="source_type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Sources</option>
                            <option value="api" {{ request('source_type') == 'api' ? 'selected' : '' }}>API Fetched</option>
                            <option value="admin" {{ request('source_type') == 'admin' ? 'selected' : '' }}>Admin Created</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <input type="hidden" name="sort" value="{{ $sortBy }}">
                        <button type="submit" class="btn btn-sm w-100" style="background:var(--cl-primary); color:#fff; font-weight:500;">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    @if(request()->hasAny(['search', 'category', 'language', 'source_type', 'sort']))
                    <div class="col-md-2">
                        <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                    @endif
                </div>
            </form>

            <!-- Sorting -->
            <div class="mt-3 d-flex align-items-center gap-2">
                <span style="font-size:0.8rem; font-weight:500; color:var(--cl-gray);">Sort:</span>
                <div class="sort-pills">
                    @php
                        $sortOptions = [
                            'latest' => 'Latest First',
                            'oldest' => 'Oldest First',
                            'most_feedback' => 'Most Feedback',
                            'highest_trust' => 'Highest Trust',
                            'title_az' => 'Title A→Z',
                            'title_za' => 'Title Z→A',
                        ];
                        $currentParams = request()->except('sort', 'page');
                    @endphp
                    @foreach($sortOptions as $key => $label)
                        <a href="{{ route('news.index', array_merge($currentParams, ['sort' => $key])) }}"
                           class="sort-pill {{ $sortBy == $key ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
        </div>{{-- end filter-bar --}}

    <!-- Results count -->
    <p style="font-size:0.85rem; color:var(--cl-gray); margin-bottom:1rem;">
        Showing {{ $news->firstItem() ?? 0 }}–{{ $news->lastItem() ?? 0 }} of {{ $news->total() }} articles
    </p>

    <!-- News Grid -->
    <div id="news-container" class="news-grid">
        @forelse($news as $item)
            @include('partials.news-card', ['item' => $item])
        @empty
            <div class="text-center py-5" style="grid-column: 1/-1; color:var(--cl-gray);">
                <i class="bi bi-inbox" style="font-size:3rem; opacity:0.3;"></i>
                <p class="mt-2">No news articles found.</p>
                @if(request('search'))
                    <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-arrow-left"></i> View All News
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($news->hasMorePages())
        <div class="text-center mt-4" id="load-more-container">
            <button id="load-more-btn" class="btn btn-outline-primary" data-page="{{ $news->currentPage() + 1 }}">
                <i class="bi bi-arrow-down-circle"></i> Load More
            </button>
        </div>
    @endif
    @endif
</div>
@endsection

@section('scripts')
<script>
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const page = this.dataset.page;
            const container = document.getElementById('news-container');
            const btn = this;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
            btn.disabled = true;
            const params = new URLSearchParams(window.location.search);
            params.set('page', page);
            fetch('{{ route("news.index") }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                container.insertAdjacentHTML('beforeend', data.html);
                if (data.hasMore) {
                    btn.dataset.page = parseInt(page) + 1;
                    btn.innerHTML = '<i class="bi bi-arrow-down-circle"></i> Load More';
                    btn.disabled = false;
                } else {
                    document.getElementById('load-more-container').remove();
                }
            })
            .catch(() => { btn.innerHTML = '<i class="bi bi-arrow-down-circle"></i> Load More'; btn.disabled = false; });
        });
    }
</script>
@endsection
