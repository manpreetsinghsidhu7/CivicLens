@extends('layouts.admin')

@section('title', 'Manage News')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="font-weight:700; margin:0;">News Articles</h4>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('admin.news.fetchApi') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-cloud-download"></i> Fetch API
            </button>
        </form>
        <a href="{{ route('admin.news.create') }}" class="btn btn-sm" style="background:var(--cl-primary); color:#fff;">
            <i class="bi bi-plus-circle"></i> Add News
        </a>
    </div>
</div>

<div class="cl-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Language</th>
                    <th>Source</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td style="max-width:250px;">
                            <a href="{{ route('news.show', $item->id) }}" style="color:var(--cl-dark); text-decoration:none; font-weight:500;">
                                {{ Str::limit($item->title, 50) }}
                            </a>
                        </td>
                        <td><span class="cl-badge" style="background:#dbeafe; color:#1d4ed8;">{{ $item->category }}</span></td>
                        <td>{{ $item->language }}</td>
                        <td style="font-size:0.8rem;">{{ Str::limit($item->source, 20) }}</td>
                        <td style="font-size:0.8rem;">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" onsubmit="return confirm('Delete this article?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:0.75rem;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No news articles yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $news->links() }}
</div>
@endsection
