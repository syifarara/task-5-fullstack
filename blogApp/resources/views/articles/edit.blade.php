@extends('layouts.master')

@section('title', 'Edit Article')
@section('page-title', 'Edit Article')
@section('page-subtitle', 'Update your article')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendors/quill/quill.snow.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Article: {{ $article->title }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mt-3">
                <label for="category_id">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    <option value="" disabled>Select a category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mt-3">
                <label for="content">Content</label>
                <div id="editor"></div>
                <textarea id="content" name="content" style="display:none">{{ old('content', $article->content) }}</textarea>
                @error('content')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mt-3">
                <label for="image">Featured Image</label>
                @if($article->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                </div>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                <small class="text-muted">Optional. Leave empty to keep current image. Max size: 2MB. Allowed types: jpg, jpeg, png, gif</small>
                @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Update Article</button>
                <a href="{{ route('articles.index') }}" class="btn btn-light-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendors/quill/quill.min.js') }}"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],
                ['clean'],
                ['link', 'image', 'video']
            ]
        }
    });
    
    // Set initial content
    quill.root.innerHTML = {!! json_encode(old('content', $article->content)) !!};
    
    // Update hidden form field with content before submit
    var form = document.querySelector('form');
    form.onsubmit = function() {
        var content = document.querySelector('#content');
        content.value = quill.root.innerHTML;
        return true;
    };
</script>
@endpush