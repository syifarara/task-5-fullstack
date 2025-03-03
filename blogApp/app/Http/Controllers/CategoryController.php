<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('user')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'user_id' => auth()->id()
        ]);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::with(['user', 'articles'])->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        // Check if the user is authorized
        if ($category->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $category->update([
            'name' => $request->name
        ]);

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if the user is authorized
        if ($category->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if category has articles
        if ($category->articles()->count() > 0) {
            return response()->json(['error' => 'Cannot delete category with articles. Please delete or reassign articles first.'], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}