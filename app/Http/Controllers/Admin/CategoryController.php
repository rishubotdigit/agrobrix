<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->get();
        return view('admin.categories.index', compact('categories'));
    }

    // Category Management
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories']);

        Category::create($request->only('name'));

        return response()->json(['message' => 'Category created successfully']);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);

        $category->update($request->only('name'));

        return response()->json(['message' => 'Category updated successfully']);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    // Subcategory Management
    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        Subcategory::create($request->only(['name', 'category_id']));

        return response()->json(['message' => 'Subcategory created successfully']);
    }

    public function updateSubcategory(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        $subcategory->update($request->only(['name', 'category_id']));

        return response()->json(['message' => 'Subcategory updated successfully']);
    }

    public function destroySubcategory(Subcategory $subcategory)
    {
        $subcategory->delete();

        return response()->json(['message' => 'Subcategory deleted successfully']);
    }

}