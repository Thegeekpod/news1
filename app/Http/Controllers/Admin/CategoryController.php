<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('display_order', 'asc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'required|integer',
        ]);

        $slug = Str::slug($request->name_en);
        if (Category::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        Category::create([
            'name_bn' => $request->name_bn,
            'name_en' => $request->name_en,
            'slug' => $slug,
            'description' => $request->description,
            'display_order' => $request->display_order,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'required|integer',
        ]);

        $slug = $category->slug;
        if (Str::slug($request->name_en) !== Str::slug($category->name_en)) {
            $slug = Str::slug($request->name_en);
            if (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $slug . '-' . time();
            }
        }

        $category->update([
            'name_bn' => $request->name_bn,
            'name_en' => $request->name_en,
            'slug' => $slug,
            'description' => $request->description,
            'display_order' => $request->display_order,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
