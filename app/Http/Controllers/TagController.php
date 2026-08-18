<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $articles = $tag->articles()
            ->published()
            ->latest('published_at')
            ->with(['category', 'author'])
            ->paginate(12);

        return view('search', [
            'articles' => $articles,
            'query' => '#' . $tag->name_bn,
        ]);
    }
}
