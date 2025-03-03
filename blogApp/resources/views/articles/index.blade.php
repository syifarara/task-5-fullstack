@extends('layouts.master')

@section('title', 'Articles')
@section('page-title', 'Articles')
@section('page-subtitle', 'Manage your blog articles')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Articles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h4>All Articles</h4>
            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add New Article
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->category->name }}</td>
                        <td>{{ $article->user->name }}</td>
                        <td>{{ $article->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info me-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($article->user_id === auth()->id())
                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No articles found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection