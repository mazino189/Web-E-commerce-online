<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // this is category controller for admin panel //
    public function index()
    {
        // show all categories in admin panel //
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    // show create form
    public function create()
    {
        return view('admin.categories.create');
    }
    
    // store category into database //
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:categories',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);
    
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    // show edit form for category
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // update category in database
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:categories,slug,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    // delete category from database
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
}
}
