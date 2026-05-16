@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h4 style="font-weight:700; margin-bottom:1.5rem;">Dashboard Overview</h4>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value">{{ $totalUsers }}</div>
                </div>
                <div class="stat-icon" style="background:#dbeafe; color:#1d4ed8;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">News Articles</div>
                    <div class="stat-value">{{ $totalNews }}</div>
                </div>
                <div class="stat-icon" style="background:#dcfce7; color:#166534;">
                    <i class="bi bi-newspaper"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Feedback</div>
                    <div class="stat-value">{{ $totalFeedback }}</div>
                </div>
                <div class="stat-icon" style="background:#fef3c7; color:#92400e;">
                    <i class="bi bi-chat-square-text"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Avg Trust Score</div>
                    <div class="stat-value">{{ $avgTrust ? number_format($avgTrust, 1) : '0' }}</div>
                </div>
                <div class="stat-icon" style="background:#ede9fe; color:#5b21b6;">
                    <i class="bi bi-star"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fetch API + Charts Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">Quick Actions</h6>
            <a href="{{ route('admin.news.create') }}" class="btn btn-sm w-100 mb-2" style="background:var(--cl-primary); color:#fff;">
                <i class="bi bi-plus-circle"></i> Add News Article
            </a>
            <form method="POST" action="{{ route('admin.news.fetchApi') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-cloud-download"></i> Fetch from NewsData.io
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">Sentiment Distribution</h6>
            <canvas id="sentimentPie" height="200"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">Bias Levels</h6>
            <canvas id="biasChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Trust/Clarity Scores per Article -->
@if($newsWithScores->count())
<div class="cl-card p-4">
    <h6 style="font-weight:700; margin-bottom:1rem;">Trust & Clarity Scores (Top Articles)</h6>
    <canvas id="scoresChart" height="120"></canvas>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Sentiment Pie Chart
    new Chart(document.getElementById('sentimentPie'), {
        type: 'doughnut',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: [{{ $sentiments['Positive'] }}, {{ $sentiments['Neutral'] }}, {{ $sentiments['Negative'] }}],
                backgroundColor: ['#10b981', '#6b7280', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11, family: 'Inter' } } } } }
    });

    // Bias Bar Chart
    new Chart(document.getElementById('biasChart'), {
        type: 'bar',
        data: {
            labels: ['Low', 'Medium', 'High'],
            datasets: [{
                label: 'Feedback Count',
                data: [{{ $biasLevels['Low'] }}, {{ $biasLevels['Medium'] }}, {{ $biasLevels['High'] }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderRadius: 6,
                barThickness: 40
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });

    @if($newsWithScores->count())
    // Trust & Clarity Scores
    new Chart(document.getElementById('scoresChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($newsWithScores->pluck('title')->map(fn($t) => Str::limit($t, 25))) !!},
            datasets: [
                {
                    label: 'Avg Trust',
                    data: {!! json_encode($newsWithScores->pluck('feedbacks_avg_trust_score')) !!},
                    backgroundColor: '#1a56db',
                    borderRadius: 4,
                    barThickness: 20
                },
                {
                    label: 'Avg Clarity',
                    data: {!! json_encode($newsWithScores->pluck('feedbacks_avg_clarity_score')) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                    barThickness: 20
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { font: { size: 11, family: 'Inter' } } } },
            scales: { y: { beginAtZero: true, max: 5 }, x: { ticks: { font: { size: 10 } } } }
        }
    });
    @endif
</script>
@endsection
