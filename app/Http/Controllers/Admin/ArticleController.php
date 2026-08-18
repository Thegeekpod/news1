<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'author'])
            ->latest()
            ->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|file',
            'status' => 'required|in:draft,published',
        ]);

        $slug = $request->slug ? $this->generateSlug($request->slug) : $this->generateSlug($request->title);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/thumbnails'), $filename);
            $thumbnailPath = 'uploads/thumbnails/' . $filename;
        }

        $article = Article::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'excerpt' => $request->excerpt ?? Str::limit(strip_tags($request->content), 150),
            'thumbnail_url' => $thumbnailPath,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'status' => $request->status,
            'is_breaking' => $request->has('is_breaking'),
            'is_lead' => $request->has('is_lead'),
            'is_sub_lead' => $request->has('is_sub_lead'),
            'is_popular' => $request->has('is_popular'),
            'is_latest' => $request->has('is_latest'),
            'is_special_banner' => $request->has('is_special_banner'),
            'published_at' => $request->status === 'published' ? ($request->published_at ? Carbon::parse($request->published_at) : now()) : null,
            'meta_title' => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description ?? Str::limit(strip_tags($request->content), 150),
            'keywords' => $request->keywords,
        ]);

        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|file',
            'status' => 'required|in:draft,published',
        ]);

        $slug = $article->slug;
        if ($request->slug && $request->slug !== $article->slug) {
            $slug = $this->generateSlug($request->slug, $article->id);
        } elseif ($request->title !== $article->title && !$request->slug) {
            $slug = $this->generateSlug($request->title, $article->id);
        }

        $thumbnailPath = $article->getRawOriginal('thumbnail_url'); // keep old
        if ($request->hasFile('thumbnail')) {
            // Remove old file if exists
            if ($article->thumbnail_url) {
                $oldPath = str_replace(asset(''), public_path(''), $article->thumbnail_url);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/thumbnails'), $filename);
            $thumbnailPath = 'uploads/thumbnails/' . $filename;
        }

        $article->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'excerpt' => $request->excerpt ?? Str::limit(strip_tags($request->content), 150),
            'thumbnail_url' => $thumbnailPath,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'is_breaking' => $request->has('is_breaking'),
            'is_lead' => $request->has('is_lead'),
            'is_sub_lead' => $request->has('is_sub_lead'),
            'is_popular' => $request->has('is_popular'),
            'is_latest' => $request->has('is_latest'),
            'is_special_banner' => $request->has('is_special_banner'),
            'published_at' => $request->status === 'published' ? ($request->published_at ? Carbon::parse($request->published_at) : ($article->published_at ?? now())) : null,
            'meta_title' => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description ?? Str::limit(strip_tags($request->content), 150),
            'keywords' => $request->keywords,
        ]);

        $article->tags()->sync($request->tags ?? []);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        // Delete image
        if ($article->thumbnail_url) {
            $filePath = str_replace(asset(''), public_path(''), $article->thumbnail_url);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }

    private function generateSlug($title, $excludeId = null)
    {
        $slug = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', mb_strtolower($title));
        $slug = preg_replace('/[\s\-]+/u', '-', $slug);
        $slug = trim($slug, '-');
        
        $original = $slug;
        $count = 1;
        
        $query = Article::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        while ($query->exists()) {
            $slug = $original . '-' . $count++;
            $query = Article::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }
        return $slug;
    }
}
