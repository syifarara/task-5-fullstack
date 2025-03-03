<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id
        ]);

        return redirect()->route('articles.index')
            ->with('success', 'Article created successfully');
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        // Check if the user is authorized
        if ($article->user_id !== auth()->id()) {
            return redirect()->route('articles.index')
                ->with('error', 'You are not authorized to edit this article');
        }

        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        // Check if the user is authorized
        if ($article->user_id !== auth()->id()) {
            return redirect()->route('articles.index')
                ->with('error', 'You are not authorized to edit this article');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $imagePath = $request->file('image')->store('articles', 'public');
        } else {
            $imagePath = $article->image;
        }

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('articles.index')
            ->with('success', 'Article updated successfully');
    }

    public function destroy(Article $article)
    {
        // Check if the user is authorized
        if ($article->user_id !== auth()->id()) {
            return redirect()->route('articles.index')
                ->with('error', 'You are not authorized to delete this article');
        }

        // Delete image if exists
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article deleted successfully');
    }
}