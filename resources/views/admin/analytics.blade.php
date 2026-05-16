@extends('layouts.admin')
@section('title', 'Analytics')
@section('content')
<h4 style="font-weight:700; margin-bottom:1.5rem;">Analytics Dashboard</h4>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">Sentiment Distribution</h6>
            <canvas id="sentimentPie" height="250"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">Bias Level Distribution</h6>
            <canvas id="biasBar" height="250"></canvas>
        </div>
    </div>
</div>
@if($newsWithScores->count())
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">Trust & Clarity Scores per Article</h6>
            <canvas id="scoresChart" height="120"></canvas>
        </div>
    </div>
</div>
@endif
@if($categoryFeedback->count())
<div class="row g-3">
    <div class="col-md-6">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">Feedback by Category</h6>
            <canvas id="categoryChart" height="250"></canvas>
        </div>
    </div>
</div>
@endif
@endsection
@section('scripts')
<script>
new Chart(document.getElementById('sentimentPie'), {
    type: 'pie',
    data: {
        labels: ['Positive', 'Neutral', 'Negative'],
        datasets: [{ data: [{{ $sentiments['Positive'] }}, {{ $sentiments['Neutral'] }}, {{ $sentiments['Negative'] }}], backgroundColor: ['#10b981', '#6b7280', '#ef4444'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 12, family: 'Inter' } } } } }
});
new Chart(document.getElementById('biasBar'), {
    type: 'bar',
    data: {
        labels: ['Low', 'Medium', 'High'],
        datasets: [{ label: 'Count', data: [{{ $biasLevels['Low'] }}, {{ $biasLevels['Medium'] }}, {{ $biasLevels['High'] }}], backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderRadius: 6, barThickness: 50 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
@if($newsWithScores->count())
new Chart(document.getElementById('scoresChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($newsWithScores->pluck('title')->map(fn($t) => Str::limit($t, 25))) !!},
        datasets: [
            { label: 'Avg Trust', data: {!! json_encode($newsWithScores->pluck('feedbacks_avg_trust_score')) !!}, backgroundColor: '#1a56db', borderRadius: 4, barThickness: 20 },
            { label: 'Avg Clarity', data: {!! json_encode($newsWithScores->pluck('feedbacks_avg_clarity_score')) !!}, backgroundColor: '#10b981', borderRadius: 4, barThickness: 20 }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true, max: 5 }, x: { ticks: { font: { size: 10 } } } } }
});
@endif
@if($categoryFeedback->count())
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($categoryFeedback->keys()) !!},
        datasets: [{ data: {!! json_encode($categoryFeedback->values()) !!}, backgroundColor: ['#1a56db','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16','#f97316','#6366f1'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11, family: 'Inter' } } } } }
});
@endif
</script>
@endsection
