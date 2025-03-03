@extends('layouts.master')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('page-subtitle', 'Update category details')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Category: {{ $category->name }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-light-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection