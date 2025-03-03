<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalArticles = Article::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $recentArticles = Article::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('totalArticles', 'totalCategories', 'totalUsers', 'recentArticles'));
    }
}