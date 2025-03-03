@extends('layouts.master')

@section('title', 'Create Category')
@section('page-title', 'Create Category')
@section('page-subtitle', 'Add a new category for your articles')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create Category</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Create Category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-light-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection