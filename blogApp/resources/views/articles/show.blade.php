@extends('layouts.master')

@section('title', $article->title)
@section('page-title', 'Article Details')
@section('page-subtitle', 'View article information')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
<li class="breadcrumb-item active" aria-current="page">View</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h4>{{ $article->title }}</h4>
            <div>
                @if($article->user_id === auth()->id())
                <a href="{{ route('articles.edit', $article) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
                @endif
                <a href="{{ route('articles.index') }}" class="btn btn-light-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <strong>Category:</strong>
                <span class="badge bg-primary">{{ $article->category->name }}</span>
            </div>
            <div class="col-md-3 col-6">
                <strong>Author:</strong>
                <span>{{ $article->user->name }}</span>
            </div>
            <div class="col-md-3 col-6">
                <strong>Created at:</strong>
                <span>{{ $article->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="col-md-3 col-6">
                <strong>Last update:</strong>
                <span>{{ $article->updated_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
        
        @if($article->image)
        <div class="row mb-4">
            <div class="col-md-12">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="img-fluid rounded">
            </div>
        </div>
        @endif
        
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    {!! $article->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection