<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return response()->json($articles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id
        ]);

        $article->load(['category', 'user']);

        return response()->json($article, 201);
    }

    public function show($id)
    {
        $article = Article::with(['category', 'user'])->findOrFail($id);
        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // Check if the user is authorized
        if ($article->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

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

        $article->load(['category', 'user']);
        
        return response()->json($article);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        
        // Check if the user is authorized
        if ($article->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete image if exists
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();
        return response()->json(['message' => 'Article deleted successfully']);
    }
}