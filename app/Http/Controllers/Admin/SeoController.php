<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    /**
     * Display a listing of the SEO page records and sitemap overview.
     */
    public function index()
    {
        $seoPages = SeoSetting::orderBy('page_url', 'asc')->paginate(15);
        $articlesCount = Article::published()->count();
        $categoriesCount = Category::count();
        $tagsCount = Tag::count();
        $seoCount = SeoSetting::count();

        return view('admin.seo.index', compact('seoPages', 'articlesCount', 'categoriesCount', 'tagsCount', 'seoCount'));
    }

    /**
     * Show the form for creating a new SEO record.
     */
    public function create()
    {
        return view('admin.seo.create');
    }

    /**
     * Store a newly created SEO record in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_url' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'other_tags' => 'nullable|string',
        ]);

        $normalizedUrl = SeoSetting::normalizePath($request->page_url);

        if (SeoSetting::where('page_url', $normalizedUrl)->exists()) {
            return back()->withInput()->with('error', 'এই URL-এর জন্য ইতিমধ্যে একটি SEO রেকর্ড তৈরি আছে!');
        }

        SeoSetting::create([
            'page_url' => $normalizedUrl,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'other_tags' => $request->other_tags,
        ]);

        return redirect()->route('admin.seo.index')->with('success', 'নতুন SEO রেকর্ড সফলভাবে তৈরি করা হয়েছে!');
    }

    /**
     * Show the form for editing the specified SEO record.
     */
    public function edit(SeoSetting $seo)
    {
        return view('admin.seo.edit', compact('seo'));
    }

    /**
     * Update the specified SEO record in storage.
     */
    public function update(Request $request, SeoSetting $seo)
    {
        $request->validate([
            'page_url' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'other_tags' => 'nullable|string',
        ]);

        $normalizedUrl = SeoSetting::normalizePath($request->page_url);

        // Check unique constraint excluding current
        $exists = SeoSetting::where('page_url', $normalizedUrl)
            ->where('id', '!=', $seo->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'এই URL-এর জন্য অন্য একটি SEO রেকর্ড ইতিমধ্যে তৈরি আছে!');
        }

        $seo->update([
            'page_url' => $normalizedUrl,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'other_tags' => $request->other_tags,
        ]);

        return redirect()->route('admin.seo.index')->with('success', 'SEO রেকর্ড সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Remove the specified SEO record from storage.
     */
    public function destroy(SeoSetting $seo)
    {
        $seo->delete();
        return redirect()->route('admin.seo.index')->with('success', 'SEO রেকর্ডটি মুছে ফেলা হয়েছে!');
    }
}
