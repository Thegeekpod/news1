<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $aliases = [
            'technology' => 'tech',
            'state' => 'politics',
            'cinema' => 'entertainment',
            'world' => 'international',
        ];

        $targetSlug = $aliases[$slug] ?? $slug;
        $category = Category::where('slug', $targetSlug)->firstOrFail();

        if ($slug !== $category->slug) {
            return redirect()->route('category.show', $category->slug, 301);
        }
        
        // Paginated articles
        $articles = Article::published()
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('category', compact('category', 'articles'));
    }
}
