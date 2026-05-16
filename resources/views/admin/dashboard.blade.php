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
                <div class="stat-icon" style="background:#dbeafe; color:#1d4ed8;"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">News Articles</div>
                    <div class="stat-value" id="statTotalNews">{{ $totalNews }}</div>
                </div>
                <div class="stat-icon" style="background:#dcfce7; color:#166534;"><i class="bi bi-newspaper"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Feedback</div>
                    <div class="stat-value" id="statTotalFeedback">{{ $totalFeedback }}</div>
                </div>
                <div class="stat-icon" style="background:#fef3c7; color:#92400e;"><i class="bi bi-chat-square-text"></i></div>
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
                <div class="stat-icon" style="background:#ede9fe; color:#5b21b6;"><i class="bi bi-star"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Fetch API Section -->
<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">
                <i class="bi bi-cloud-download text-primary"></i> Fetch News from API
            </h6>

            <!-- Single Fetch -->
            <form method="POST" action="{{ route('admin.news.fetchApi') }}" class="mb-3">
                @csrf
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label" style="font-size:0.75rem; font-weight:500;">Category</label>
                        <select name="category" class="form-select form-select-sm" id="singleCategory">
                            @foreach(['politics', 'business', 'technology', 'science', 'health', 'sports', 'entertainment', 'education', 'environment', 'food', 'tourism', 'world', 'top', 'domestic', 'crime', 'lifestyle'] as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:0.75rem; font-weight:500;">Language</label>
                        <select name="language" class="form-select form-select-sm" id="singleLanguage">
                            <option value="en">English</option>
                            <option value="hi">Hindi</option>
                            <option value="ta">Tamil</option>
                            <option value="te">Telugu</option>
                            <option value="kn">Kannada</option>
                            <option value="ml">Malayalam</option>
                            <option value="bn">Bengali</option>
                            <option value="mr">Marathi</option>
                            <option value="gu">Gujarati</option>
                            <option value="pa">Punjabi</option>
                            <option value="ur">Urdu</option>
                            <option value="or">Odia</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-sm w-100 mt-2" style="background:var(--cl-primary); color:#fff; font-weight:500;">
                    <i class="bi bi-cloud-download"></i> Fetch Single
                </button>
            </form>

            <hr>

            <!-- Bulk Fetch All -->
            <h6 style="font-weight:600; font-size:0.85rem; margin-bottom:0.5rem;">
                <i class="bi bi-lightning-charge text-warning"></i> Bulk Fetch (All Categories × Languages)
            </h6>
            <p style="font-size:0.75rem; color:var(--cl-gray); margin-bottom:0.5rem;">
                Fetches 10 news per category per language. Multiple API calls will be made sequentially.
            </p>

            <div class="mb-2">
                <label class="form-label" style="font-size:0.75rem; font-weight:500;">Select Languages for Bulk Fetch</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['en' => 'English', 'hi' => 'Hindi', 'pa' => 'Punjabi', 'ta' => 'Tamil', 'te' => 'Telugu', 'bn' => 'Bengali', 'mr' => 'Marathi', 'gu' => 'Gujarati', 'kn' => 'Kannada', 'ml' => 'Malayalam', 'ur' => 'Urdu', 'or' => 'Odia'] as $code => $name)
                        <label style="font-size:0.75rem; cursor:pointer;" class="d-flex align-items-center gap-1">
                            <input type="checkbox" class="bulk-lang-check" value="{{ $code }}" {{ in_array($code, ['en', 'hi']) ? 'checked' : '' }}> {{ $name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="button" id="bulkFetchBtn" class="btn btn-sm btn-warning w-100" onclick="startBulkFetch()">
                <i class="bi bi-lightning-charge"></i> Fetch All Categories
            </button>

            <!-- Progress -->
            <div id="bulkProgress" style="display:none; margin-top:0.75rem;">
                <div class="progress mb-2" style="height:8px;">
                    <div class="progress-bar" id="bulkProgressBar" style="width:0%; background:var(--cl-primary);"></div>
                </div>
                <div id="bulkStatus" style="font-size:0.75rem; color:var(--cl-gray);"></div>
                <div id="bulkLog" style="max-height:150px; overflow-y:auto; font-size:0.7rem; color:#6b7280; margin-top:0.5rem; background:#f9fafb; border-radius:6px; padding:0.5rem;"></div>
            </div>

            <hr class="mt-3">
            <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-outline-primary w-100">
                <i class="bi bi-plus-circle"></i> Create News Manually
            </a>
        </div>
    </div>

    <div class="col-md-3">
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

<!-- Source Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">
                <i class="bi bi-pie-chart text-primary"></i> News by Source
            </h6>
            <div class="d-flex gap-4">
                <div class="text-center">
                    <div style="font-size:1.75rem; font-weight:700; color:var(--cl-primary);" id="statApiNews">{{ $apiNewsCount }}</div>
                    <div style="font-size:0.8rem; color:var(--cl-gray);">API Fetched</div>
                </div>
                <div class="text-center">
                    <div style="font-size:1.75rem; font-weight:700; color:#10b981;">{{ $adminNewsCount }}</div>
                    <div style="font-size:0.8rem; color:var(--cl-gray);">Admin Created</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cl-card p-4">
            <h6 style="font-weight:700; margin-bottom:1rem;">
                <i class="bi bi-clock-history text-primary"></i> Auto-Fetch Schedule
            </h6>
            <p style="font-size:0.85rem; color:var(--cl-gray); margin-bottom:0.5rem;">
                Run <code>php artisan schedule:work</code> for hourly auto-fetch.
            </p>
            <p style="font-size:0.75rem; color:#9ca3af; margin-bottom:0;">
                Manual: <code>php artisan news:fetch</code>
            </p>
        </div>
    </div>
</div>

<!-- Trust/Clarity Chart -->
@if($newsWithScores->count())
<div class="cl-card p-4">
    <h6 style="font-weight:700; margin-bottom:1rem;">Trust & Clarity Scores (Top Articles)</h6>
    <canvas id="scoresChart" height="120"></canvas>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Charts
    new Chart(document.getElementById('sentimentPie'), {
        type: 'doughnut',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{ data: [{{ $sentiments['Positive'] }}, {{ $sentiments['Neutral'] }}, {{ $sentiments['Negative'] }}], backgroundColor: ['#10b981', '#6b7280', '#ef4444'], borderWidth: 0 }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11, family: 'Inter' } } } } }
    });

    new Chart(document.getElementById('biasChart'), {
        type: 'bar',
        data: {
            labels: ['Low', 'Medium', 'High'],
            datasets: [{ label: 'Count', data: [{{ $biasLevels['Low'] }}, {{ $biasLevels['Medium'] }}, {{ $biasLevels['High'] }}], backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderRadius: 6, barThickness: 40 }]
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
        options: { responsive: true, plugins: { legend: { labels: { font: { size: 11, family: 'Inter' } } } }, scales: { y: { beginAtZero: true, max: 5 }, x: { ticks: { font: { size: 10 } } } } }
    });
    @endif

    // Bulk Fetch Logic
    const CATEGORIES = ['politics', 'business', 'technology', 'science', 'health', 'sports', 'entertainment', 'education', 'environment', 'food', 'tourism', 'world', 'top', 'domestic', 'crime', 'lifestyle'];

    async function startBulkFetch() {
        const btn = document.getElementById('bulkFetchBtn');
        const progressDiv = document.getElementById('bulkProgress');
        const progressBar = document.getElementById('bulkProgressBar');
        const statusEl = document.getElementById('bulkStatus');
        const logEl = document.getElementById('bulkLog');

        // Get selected languages
        const langChecks = document.querySelectorAll('.bulk-lang-check:checked');
        const languages = Array.from(langChecks).map(cb => cb.value);
        if (languages.length === 0) {
            alert('Select at least one language!');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Fetching...';
        progressDiv.style.display = 'block';
        logEl.innerHTML = '';

        const totalCalls = CATEGORIES.length * languages.length;
        let completed = 0;
        let totalImported = 0;

        for (const lang of languages) {
            for (const cat of CATEGORIES) {
                completed++;
                const pct = Math.round((completed / totalCalls) * 100);
                progressBar.style.width = pct + '%';
                statusEl.textContent = `Fetching ${cat}/${lang}... (${completed}/${totalCalls})`;

                try {
                    const resp = await fetch('{{ route("admin.news.fetchApiAjax") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ category: cat, language: lang })
                    });
                    const data = await resp.json();
                    totalImported += data.imported;
                    logEl.innerHTML += `<div>✅ ${cat}/${lang}: ${data.imported} articles</div>`;
                } catch (e) {
                    logEl.innerHTML += `<div>❌ ${cat}/${lang}: error</div>`;
                }
                logEl.scrollTop = logEl.scrollHeight;

                // Small delay to avoid rate limiting
                await new Promise(r => setTimeout(r, 300));
            }
        }

        progressBar.style.width = '100%';
        statusEl.innerHTML = `<strong>✅ Done! Imported ${totalImported} total articles.</strong>`;
        btn.innerHTML = '<i class="bi bi-lightning-charge"></i> Fetch All Categories';
        btn.disabled = false;

        // Update stat counters
        document.getElementById('statTotalNews').textContent = parseInt(document.getElementById('statTotalNews').textContent) + totalImported;
    }
</script>
@endsection
