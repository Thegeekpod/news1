<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function show($slug)
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags'])
            ->firstOrFail();

        // Atomically increment view count to prevent race conditions
        $article->increment('view_count');

        // Fetch up to 6 related articles in the same category, excluding the current article
        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('article', compact('article', 'relatedArticles'));
    }
}
