@extends('layouts.admin')
@section('title', 'Manage Feedback')
@section('content')
<h4 style="font-weight:700; margin-bottom:1.5rem;">Feedback Management</h4>
<div class="cl-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>User</th><th>News Article</th><th>Trust</th><th>Clarity</th><th>Bias</th><th>Sentiment</th><th>Date</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $fb)
                    <tr>
                        <td>{{ $fb->id }}</td>
                        <td style="font-size:0.85rem;">{{ $fb->user->name ?? 'N/A' }}</td>
                        <td style="max-width:200px; font-size:0.85rem;">{{ Str::limit($fb->news->title ?? 'Deleted', 35) }}</td>
                        <td>{{ $fb->trust_score }}/5</td>
                        <td>{{ $fb->clarity_score }}/5</td>
                        <td><span class="cl-badge" style="background:{{ $fb->bias_level == 'Low' ? '#dcfce7' : ($fb->bias_level == 'High' ? '#fee2e2' : '#fef3c7') }}; color:{{ $fb->bias_level == 'Low' ? '#166534' : ($fb->bias_level == 'High' ? '#991b1b' : '#92400e') }};">{{ $fb->bias_level }}</span></td>
                        <td><span class="cl-badge" style="background:{{ $fb->sentiment == 'Positive' ? '#dcfce7' : ($fb->sentiment == 'Negative' ? '#fee2e2' : '#f3f4f6') }}; color:{{ $fb->sentiment == 'Positive' ? '#166534' : ($fb->sentiment == 'Negative' ? '#991b1b' : '#6b7280') }};">{{ $fb->sentiment }}</span></td>
                        <td style="font-size:0.8rem;">{{ $fb->created_at->format('d M Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.feedback.destroy', $fb->id) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" style="font-size:0.75rem;"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No feedback entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $feedbacks->links() }}</div>
@endsection
