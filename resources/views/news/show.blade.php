@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="cl-card p-4 mb-4">
                <!-- Breadcrumb -->
                <nav style="font-size:0.8rem; margin-bottom:1rem;">
                    <a href="{{ route('news.index') }}" style="color:var(--cl-primary); text-decoration:none;">News</a>
                    <span class="text-muted mx-1">/</span>
                    <span class="text-muted">{{ Str::limit($news->title, 40) }}</span>
                </nav>

                <!-- Image -->
                @if($news->image)
                    <img src="{{ $news->image }}" alt="{{ $news->title }}"
                         style="width:100%; max-height:400px; object-fit:cover; border-radius:8px; margin-bottom:1.25rem;"
                         onerror="this.style.display='none'">
                @endif

                <!-- Meta -->
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <span class="cl-badge" style="background:#dbeafe; color:#1d4ed8;">{{ $news->category }}</span>
                    <span class="cl-badge" style="background:#f3f4f6; color:#6b7280;">{{ $news->language }}</span>
                    <span class="cl-badge" style="background:#fef3c7; color:#92400e;">
                        <i class="bi bi-building"></i> {{ $news->source }}
                    </span>
                    @if($news->source_type === 'api')
                        <span class="cl-badge" style="background:#ede9fe; color:#5b21b6;">
                            <i class="bi bi-cloud"></i> API Fetched
                        </span>
                    @else
                        <span class="cl-badge" style="background:#dcfce7; color:#166534;">
                            <i class="bi bi-person"></i> Admin Created
                        </span>
                    @endif
                    <span class="cl-badge" style="background:#f3f4f6; color:#374151;">
                        <i class="bi bi-hash"></i> ID: {{ $news->id }}
                    </span>
                </div>

                <!-- Title -->
                <h1 style="font-size:1.5rem; font-weight:700; line-height:1.3; margin-bottom:1rem;">
                    {{ $news->title }}
                </h1>

                <p style="font-size:0.8rem; color:var(--cl-gray); margin-bottom:1.5rem;">
                    Published {{ ($news->published_at ?? $news->created_at)->format('d M Y, h:i A') }}
                    &middot; {{ $feedbackCount }} feedback{{ $feedbackCount != 1 ? 's' : '' }}
                    @if($news->source_url)
                        &middot; <a href="{{ $news->source_url }}" target="_blank" style="color:var(--cl-primary); text-decoration:none;">
                            <i class="bi bi-box-arrow-up-right"></i> Original Source
                        </a>
                    @endif
                </p>

                <!-- Content -->
                <div style="font-size:0.95rem; line-height:1.8; color:#374151;">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>

            <!-- Feedback Form -->
            @auth
                <div class="cl-card p-4 mb-4">
                    <h5 style="font-weight:700; font-size:1.1rem; margin-bottom:1rem;">
                        <i class="bi bi-chat-square-text text-primary"></i> Submit Your Feedback
                    </h5>

                    <form method="POST" action="{{ route('feedback.store') }}">
                        @csrf
                        <input type="hidden" name="news_id" value="{{ $news->id }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Trust Score</label>
                                <select name="trust_score" class="form-select @error('trust_score') is-invalid @enderror" required>
                                    <option value="">Rate 1-5</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('trust_score') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Very Low','Low','Medium','High','Very High'][$i-1] }}</option>
                                    @endfor
                                </select>
                                @error('trust_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Clarity Score</label>
                                <select name="clarity_score" class="form-select @error('clarity_score') is-invalid @enderror" required>
                                    <option value="">Rate 1-5</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('clarity_score') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Very Low','Low','Medium','High','Very High'][$i-1] }}</option>
                                    @endfor
                                </select>
                                @error('clarity_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Bias Level</label>
                                <select name="bias_level" class="form-select @error('bias_level') is-invalid @enderror" required>
                                    <option value="">Select</option>
                                    @foreach(['Low', 'Medium', 'High'] as $level)
                                        <option value="{{ $level }}" {{ old('bias_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                                @error('bias_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Sentiment</label>
                                <select name="sentiment" class="form-select @error('sentiment') is-invalid @enderror" required>
                                    <option value="">Select</option>
                                    @foreach(['Positive', 'Neutral', 'Negative'] as $s)
                                        <option value="{{ $s }}" {{ old('sentiment') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                                @error('sentiment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Comment</label>
                                <textarea name="comment" rows="3" class="form-control @error('comment') is-invalid @enderror"
                                          placeholder="Share your detailed feedback (minimum 10 characters)..." required>{{ old('comment') }}</textarea>
                                @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn mt-3" style="background:var(--cl-primary); color:#fff; font-weight:600; padding:0.5rem 1.5rem;">
                            <i class="bi bi-send"></i> Submit Feedback
                        </button>
                    </form>
                </div>
            @endauth

            <!-- Existing Feedback -->
            @if($news->feedbacks->count())
                <div class="cl-card p-4">
                    <h5 style="font-weight:700; font-size:1.1rem; margin-bottom:1rem;">
                        Community Feedback ({{ $news->feedbacks->count() }})
                    </h5>

                    @foreach($news->feedbacks->take(10) as $fb)
                        <div style="border-bottom:1px solid var(--cl-border); padding:1rem 0; {{ $loop->last ? 'border-bottom:none;' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong style="font-size:0.9rem;">{{ $fb->user->name }}</strong>
                                    <span style="font-size:0.75rem; color:var(--cl-gray); margin-left:0.5rem;">{{ $fb->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="cl-badge" style="background:{{ $fb->sentiment == 'Positive' ? '#dcfce7' : ($fb->sentiment == 'Negative' ? '#fee2e2' : '#f3f4f6') }}; color:{{ $fb->sentiment == 'Positive' ? '#166534' : ($fb->sentiment == 'Negative' ? '#991b1b' : '#6b7280') }};">
                                        {{ $fb->sentiment }}
                                    </span>
                                </div>
                            </div>
                            <p style="font-size:0.85rem; color:#374151; margin:0.5rem 0;">{{ $fb->comment }}</p>
                            <div style="font-size:0.75rem; color:var(--cl-gray);">
                                Trust: {{ $fb->trust_score }}/5 &middot;
                                Clarity: {{ $fb->clarity_score }}/5 &middot;
                                Bias: {{ $fb->bias_level }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Feedback Summary -->
            <div class="cl-card p-4 mb-3">
                <h6 style="font-weight:700; font-size:0.95rem; margin-bottom:1rem;">Feedback Summary</h6>

                <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                    <span>Avg Trust Score</span>
                    <strong>{{ $avgTrust ? number_format($avgTrust, 1) : 'N/A' }}/5</strong>
                </div>
                <div class="progress mb-3" style="height:6px;">
                    <div class="progress-bar" style="width:{{ ($avgTrust ?? 0) * 20 }}%; background:var(--cl-primary);"></div>
                </div>

                <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                    <span>Avg Clarity Score</span>
                    <strong>{{ $avgClarity ? number_format($avgClarity, 1) : 'N/A' }}/5</strong>
                </div>
                <div class="progress mb-3" style="height:6px;">
                    <div class="progress-bar" style="width:{{ ($avgClarity ?? 0) * 20 }}%; background:#10b981;"></div>
                </div>

                <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                    <span>Total Feedback</span>
                    <strong>{{ $feedbackCount }}</strong>
                </div>
            </div>

            <!-- Sentiment Chart -->
            @if($feedbackCount > 0)
                <div class="cl-card p-4 mb-3">
                    <h6 style="font-weight:700; font-size:0.95rem; margin-bottom:1rem;">Sentiment Distribution</h6>
                    <canvas id="sentimentChart" height="200"></canvas>
                </div>
            @endif

            <!-- Related News -->
            @if(isset($relatedNews) && $relatedNews->count())
                <div class="cl-card p-4">
                    <h6 style="font-weight:700; font-size:0.95rem; margin-bottom:1rem;">
                        <i class="bi bi-link-45deg text-primary"></i> Related News
                    </h6>
                    @foreach($relatedNews as $related)
                        <a href="{{ route('news.show', $related->id) }}" class="d-block text-decoration-none mb-3" style="color:inherit;">
                            <div class="d-flex gap-2">
                                @if($related->image)
                                    <img src="{{ $related->image }}" style="width:60px; height:60px; object-fit:cover; border-radius:6px;" onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <p style="font-size:0.8rem; font-weight:600; margin-bottom:0.2rem; line-height:1.3; color:var(--cl-dark);">
                                        {{ Str::limit($related->title, 60) }}
                                    </p>
                                    <span style="font-size:0.7rem; color:var(--cl-gray);">{{ ($related->published_at ?? $related->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($feedbackCount > 0)
<script>
    new Chart(document.getElementById('sentimentChart'), {
        type: 'doughnut',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: [{{ $sentiments['Positive'] }}, {{ $sentiments['Neutral'] }}, {{ $sentiments['Negative'] }}],
                backgroundColor: ['#10b981', '#6b7280', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12, family: 'Inter' }, padding: 15 } }
            }
        }
    });
</script>
@endif
@endsection
