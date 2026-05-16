@extends('layouts.admin')

@section('title', 'Add News')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.news.index') }}" style="color:var(--cl-gray); margin-right:1rem; text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 style="font-weight:700; margin:0;">Add News Article</h4>
</div>

<div class="cl-card p-4" style="max-width:700px;">
    <form method="POST" action="{{ route('admin.news.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" style="font-weight:500; font-size:0.85rem;">Title *</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" placeholder="Enter news title" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-weight:500; font-size:0.85rem;">Content *</label>
            <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror"
                      placeholder="Full news content..." required>{{ old('content') }}</textarea>
            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Category *</label>
                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                    <option value="">Select</option>
                    @foreach(['Politics', 'Economy', 'Education', 'Health', 'Infrastructure', 'Defence', 'Environment', 'Technology', 'Sports', 'Other'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Language *</label>
                <select name="language" class="form-select @error('language') is-invalid @enderror" required>
                    <option value="">Select</option>
                    @foreach(['English', 'Hindi', 'Tamil', 'Telugu', 'Kannada', 'Malayalam', 'Bengali', 'Marathi', 'Gujarati', 'Punjabi', 'Urdu', 'Odia'] as $lang)
                        <option value="{{ $lang }}" {{ old('language') == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                    @endforeach
                </select>
                @error('language') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" style="font-weight:500; font-size:0.85rem;">Source *</label>
                <input type="text" name="source" class="form-control @error('source') is-invalid @enderror"
                       value="{{ old('source') }}" placeholder="e.g. NDTV, The Hindu" required>
                @error('source') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label class="form-label" style="font-weight:500; font-size:0.85rem;">Image URL (optional)</label>
            <input type="url" name="image" class="form-control @error('image') is-invalid @enderror"
                   value="{{ old('image') }}" placeholder="https://example.com/image.jpg">
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn" style="background:var(--cl-primary); color:#fff; font-weight:600; padding:0.5rem 2rem;">
            <i class="bi bi-check-circle"></i> Publish Article
        </button>
    </form>
</div>
@endsection
