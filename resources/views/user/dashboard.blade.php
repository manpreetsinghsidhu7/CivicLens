@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="cl-card p-4">
                <div class="text-center">
                    <div style="width:64px; height:64px; background:var(--cl-primary-light); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; font-size:1.5rem; color:var(--cl-primary); font-weight:700;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h5 style="font-weight:700; margin-bottom:0.25rem;">{{ Auth::user()->name }}</h5>
                    <p style="color:var(--cl-gray); font-size:0.85rem;">{{ Auth::user()->email }}</p>
                    <span class="cl-badge" style="background:var(--cl-primary-light); color:var(--cl-primary);">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>
                <hr style="border-color:var(--cl-border);">
                <div style="font-size:0.85rem; color:var(--cl-gray);">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Member since</span>
                        <strong>{{ Auth::user()->created_at->format('M Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Feedback submitted</span>
                        <strong>{{ $feedbacks->total() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback History -->
        <div class="col-lg-8">
            <div class="cl-card p-4">
                <h5 style="font-weight:700; font-size:1.1rem; margin-bottom:1rem;">
                    <i class="bi bi-clock-history text-primary"></i> My Feedback History
                </h5>

                @forelse($feedbacks as $fb)
                    <div style="border-bottom:1px solid var(--cl-border); padding:1rem 0; {{ $loop->last ? 'border-bottom:none;' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <a href="{{ route('news.show', $fb->news_id) }}" style="font-weight:600; color:var(--cl-dark); text-decoration:none; font-size:0.9rem;">
                                    {{ Str::limit($fb->news->title ?? 'Deleted Article', 60) }}
                                </a>
                                <div style="font-size:0.75rem; color:var(--cl-gray); margin-top:0.25rem;">
                                    {{ $fb->created_at->format('d M Y, h:i A') }}
                                </div>
                            </div>
                            <span class="cl-badge" style="background:{{ $fb->sentiment == 'Positive' ? '#dcfce7' : ($fb->sentiment == 'Negative' ? '#fee2e2' : '#f3f4f6') }}; color:{{ $fb->sentiment == 'Positive' ? '#166534' : ($fb->sentiment == 'Negative' ? '#991b1b' : '#6b7280') }};">
                                {{ $fb->sentiment }}
                            </span>
                        </div>
                        <p style="font-size:0.85rem; color:#374151; margin:0.5rem 0 0.25rem;">{{ Str::limit($fb->comment, 120) }}</p>
                        <div style="font-size:0.75rem; color:var(--cl-gray);">
                            Trust: {{ $fb->trust_score }}/5 &middot;
                            Clarity: {{ $fb->clarity_score }}/5 &middot;
                            Bias: {{ $fb->bias_level }}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4" style="color:var(--cl-gray);">
                        <i class="bi bi-chat-dots" style="font-size:2rem; opacity:0.3;"></i>
                        <p class="mt-2 mb-0">No feedback submitted yet.</p>
                        <a href="{{ route('news.index') }}" style="font-size:0.85rem; color:var(--cl-primary);">Browse news to get started</a>
                    </div>
                @endforelse

                <div class="mt-3">
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
