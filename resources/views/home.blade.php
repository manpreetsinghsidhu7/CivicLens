@extends('layouts.app')

@section('title', 'Home')

@section('styles')
<style>
    .news-grid-home { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
    .pagination-wrapper { display: flex; justify-content: center; margin-top: 2rem; }
    .pagination-wrapper .page-link { color: var(--cl-primary); font-size: 0.85rem; }
    .pagination-wrapper .page-item.active .page-link { background: var(--cl-primary); border-color: var(--cl-primary); color: #fff; }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #1a56db 0%, #1e40af 100%); padding: 4rem 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 style="color:#fff; font-weight:700; font-size:2.5rem; line-height:1.2; margin-bottom:1rem;">
                    360° Public Feedback<br>on Government News
                </h1>
                <p style="color:rgba(255,255,255,0.85); font-size:1.05rem; max-width:500px; margin-bottom:1.5rem;">
                    CivicLens empowers Indian citizens to share structured feedback on government news from regional media sources. Analyze trust, clarity, bias, and sentiment.
                </p>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ route('news.index') }}" class="btn btn-light" style="font-weight:600; padding:0.6rem 1.5rem;">
                            <i class="bi bi-newspaper me-1"></i> Browse News
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light" style="font-weight:600; padding:0.6rem 1.5rem;">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login to Read News
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light" style="font-weight:500; padding:0.6rem 1.5rem;">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-5 text-center mt-4 mt-lg-0">
                <div style="background:rgba(255,255,255,0.1); border-radius:16px; padding:2rem; backdrop-filter:blur(10px);">
                    <div class="row text-white text-center">
                        <div class="col-4">
                            <div style="font-size:2rem; font-weight:700;">{{ $totalNews }}</div>
                            <div style="font-size:0.75rem; opacity:0.8;">Articles</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:2rem; font-weight:700;">{{ $totalFeedback }}</div>
                            <div style="font-size:0.75rem; opacity:0.8;">Feedbacks</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:2rem; font-weight:700;">{{ $totalUsers }}</div>
                            <div style="font-size:0.75rem; opacity:0.8;">Users</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
@if($categories->count())
<section class="py-4" style="background:var(--cl-white); border-bottom:1px solid var(--cl-border);">
    <div class="container">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <span style="font-size:0.85rem; font-weight:600; color:var(--cl-gray);">Categories:</span>
            @foreach($categories as $cat)
                @auth
                    <a href="{{ route('news.index', ['category' => $cat]) }}"
                       class="cl-badge" style="background:var(--cl-primary-light); color:var(--cl-primary); text-decoration:none;">
                        {{ $cat }}
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="cl-badge" style="background:var(--cl-primary-light); color:var(--cl-primary); text-decoration:none;">
                        {{ $cat }}
                    </a>
                @endauth
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- All News with Pagination -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-size:1.4rem; font-weight:700; margin:0;">
                <i class="bi bi-newspaper text-primary"></i> Latest News
                <span style="font-size:0.85rem; font-weight:400; color:var(--cl-gray); margin-left:0.5rem;">
                    ({{ $latestNews->total() }} articles)
                </span>
            </h2>
            @auth
                <a href="{{ route('news.index') }}" style="color:var(--cl-primary); font-size:0.9rem; text-decoration:none; font-weight:500;">
                    Advanced Search <i class="bi bi-arrow-right"></i>
                </a>
            @endauth
        </div>

        @if($latestNews->count())
            <div class="news-grid-home">
                @foreach($latestNews as $item)
                    @auth
                        <a href="{{ route('news.show', $item->id) }}" class="text-decoration-none" style="color:inherit;">
                    @else
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color:inherit;">
                    @endauth
                        <div class="cl-card h-100">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->title }}"
                                     style="width:100%; height:180px; object-fit:cover;"
                                     onerror="this.style.display='none'">
                            @else
                                <div style="height:180px; background:var(--cl-primary-light); display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-newspaper" style="font-size:2.5rem; color:var(--cl-primary); opacity:0.4;"></i>
                                </div>
                            @endif
                            <div class="p-3">
                                <div class="d-flex gap-2 mb-2 flex-wrap">
                                    <span class="cl-badge" style="background:#dbeafe; color:#1d4ed8;">{{ $item->category }}</span>
                                    <span class="cl-badge" style="background:#f3f4f6; color:#6b7280;">{{ $item->language }}</span>
                                </div>
                                <h6 style="color:var(--cl-dark); font-weight:600; font-size:0.95rem; margin-bottom:0.5rem; line-height:1.4;">
                                    {{ Str::limit($item->title, 75) }}
                                </h6>
                                <div style="font-size:0.75rem; color:var(--cl-gray);">
                                    <i class="bi bi-building"></i> {{ $item->source }}
                                    &middot; {{ ($item->published_at ?? $item->created_at)->diffForHumans() }}
                                    &middot; <i class="bi bi-chat-dots"></i> {{ $item->feedbacks_count ?? $item->feedbacks()->count() }} feedback
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Numbered Pagination -->
            @if($latestNews->hasPages())
                <div class="pagination-wrapper">
                    {{ $latestNews->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="text-center py-5" style="color:var(--cl-gray);">
                <i class="bi bi-inbox" style="font-size:3rem; opacity:0.3;"></i>
                <p class="mt-2">No news articles available yet.</p>
            </div>
        @endif
    </div>
</section>
@endsection
