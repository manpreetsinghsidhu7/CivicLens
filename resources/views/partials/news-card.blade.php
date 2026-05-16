<a href="{{ route('news.show', $item->id) }}" class="news-card-link">
    <div class="cl-card h-100">
        @if($item->image)
            <img src="{{ $item->image }}" alt="{{ $item->title }}"
                 style="width:100%; height:180px; object-fit:cover;"
                 onerror="this.parentElement.innerHTML='<div style=\'height:180px; background:var(--cl-primary-light); display:flex; align-items:center; justify-content:center;\'><i class=\'bi bi-newspaper\' style=\'font-size:2rem; color:var(--cl-primary); opacity:0.4;\'></i></div>' + this.parentElement.innerHTML.split('</div>').slice(1).join('</div>')">
        @else
            <div style="height:180px; background:var(--cl-primary-light); display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-newspaper" style="font-size:2rem; color:var(--cl-primary); opacity:0.4;"></i>
            </div>
        @endif
        <div class="p-3">
            <div class="d-flex gap-2 mb-2 flex-wrap">
                <span class="cl-badge" style="background:#dbeafe; color:#1d4ed8;">{{ $item->category }}</span>
                <span class="cl-badge" style="background:#f3f4f6; color:#6b7280;">{{ $item->language }}</span>
                @if($item->source_type === 'api')
                    <span class="cl-badge" style="background:#ede9fe; color:#5b21b6; font-size:0.65rem;">
                        <i class="bi bi-cloud"></i> API
                    </span>
                @else
                    <span class="cl-badge" style="background:#dcfce7; color:#166534; font-size:0.65rem;">
                        <i class="bi bi-person"></i> Admin
                    </span>
                @endif
            </div>
            <h6 style="font-weight:600; font-size:0.9rem; margin-bottom:0.5rem; line-height:1.4; color:var(--cl-dark);">
                {{ Str::limit($item->title, 80) }}
            </h6>
            <p style="font-size:0.8rem; color:var(--cl-gray); margin-bottom:0.5rem; line-height:1.5;">
                {{ Str::limit(strip_tags($item->content), 100) }}
            </p>
            <div style="font-size:0.7rem; color:#9ca3af;">
                <i class="bi bi-building"></i> {{ $item->source }}
                &middot; {{ ($item->published_at ?? $item->created_at)->diffForHumans() }}
                &middot; <i class="bi bi-chat-dots"></i> {{ $item->feedbacks_count ?? $item->feedbacks()->count() }} feedback
                &middot; ID: #{{ $item->id }}
            </div>
        </div>
    </div>
</a>
