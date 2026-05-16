@extends('layouts.app')

@section('title', 'News')

@section('styles')
<style>
    .news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
    .filter-bar { background: var(--cl-white); border: 1px solid var(--cl-border); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; }
    .news-card-link { text-decoration: none; color: inherit; display: block; height: 100%; }
    .news-card-link:hover .cl-card { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
</style>
@endsection

@section('content')
<div class="container py-4">
    <h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1rem;">
        <i class="bi bi-newspaper text-primary"></i> Government News
    </h1>

    <!-- Filters -->
    <div class="filter-bar">
        <form method="GET" action="{{ route('news.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label" style="font-size:0.8rem; font-weight:500;">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search news..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:0.8rem; font-weight:500;">Category</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:0.8rem; font-weight:500;">Language</label>
                <select name="language" class="form-select form-select-sm">
                    <option value="">All Languages</option>
                    @foreach($languages as $lang)
                        <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm w-100" style="background:var(--cl-primary); color:#fff; font-weight:500;">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Skeleton placeholders (hidden once loaded) -->
    <div id="skeleton-container" style="display:none;">
        <div class="news-grid">
            @for($i = 0; $i < 6; $i++)
                <div class="skeleton-card">
                    <div class="skeleton skeleton-img"></div>
                    <div class="p-3">
                        <div class="skeleton skeleton-line short mb-2"></div>
                        <div class="skeleton skeleton-line full"></div>
                        <div class="skeleton skeleton-line medium"></div>
                        <div class="skeleton skeleton-line short mt-2"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- News Grid -->
    <div id="news-container" class="news-grid">
        @forelse($news as $item)
            @include('partials.news-card', ['item' => $item])
        @empty
            <div class="text-center py-5" style="grid-column: 1/-1; color:var(--cl-gray);">
                <i class="bi bi-inbox" style="font-size:3rem; opacity:0.3;"></i>
                <p class="mt-2">No news articles found.</p>
            </div>
        @endforelse
    </div>

    <!-- Load More Button (infinite scroll trigger) -->
    @if($news->hasMorePages())
        <div class="text-center mt-4" id="load-more-container">
            <button id="load-more-btn" class="btn btn-outline-primary" data-page="{{ $news->currentPage() + 1 }}">
                <i class="bi bi-arrow-down-circle"></i> Load More
            </button>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Infinite scroll / Load More
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const page = this.dataset.page;
            const container = document.getElementById('news-container');
            const btn = this;

            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
            btn.disabled = true;

            // Build URL with current filters
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
            .catch(() => {
                btn.innerHTML = '<i class="bi bi-arrow-down-circle"></i> Load More';
                btn.disabled = false;
            });
        });
    }
</script>
@endsection
