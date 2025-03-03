@extends('layouts.master')

@section('title', 'Categories')
@section('page-title', 'Categories')
@section('page-subtitle', 'Manage your blog categories')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Categories</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h4>All Categories</h4>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add New Category
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created By</th>
                        <th>Articles</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->user->name }}</td>
                        <td>{{ $category->articles_count }}</td>
                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex">
                                @if($category->user_id === auth()->id())
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" @if($category->articles_count > 0) disabled title="Cannot delete category with articles" @endif>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No categories found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection