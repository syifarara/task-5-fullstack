@extends('layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Your blog statistics at a glance')

@section('content')
<div class="row">
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon purple">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Articles</h6>
                        <h6 class="font-extrabold mb-0"></h6> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon blue">
                            <i class="bi bi-grid-1x2"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Categories</h6>
                        <h6 class="font-extrabold mb-0"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon green">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Users</h6>
                        <h6 class="font-extrabold mb-0"></h6> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Recent Articles</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-lg">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Created</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection