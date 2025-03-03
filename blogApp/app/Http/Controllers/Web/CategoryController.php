<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories'
        ]);

        Category::create([
            'name' => $request->name,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        // Check if the user is authorized
        if ($category->user_id !== auth()->id()) {
            return redirect()->route('categories.index')
                ->with('error', 'You are not authorized to edit this category');
        }

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Check if the user is authorized
        if ($category->user_id !== auth()->id()) {
            return redirect()->route('categories.index')
                ->with('error', 'You are not authorized to edit this category');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        // Check if the user is authorized
        if ($category->user_id !== auth()->id()) {
            return redirect()->route('categories.index')
                ->with('error', 'You are not authorized to delete this category');
        }

        // Check if category has articles
        if ($category->articles()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with articles. Please delete or reassign articles first.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}