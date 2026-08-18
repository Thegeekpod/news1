<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('articles')->latest()->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_bn' => 'required|string|max:255|unique:tags,name_bn',
        ]);

        $slug = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', mb_strtolower($request->name_bn));
        $slug = preg_replace('/[\s\-]+/u', '-', $slug);
        $slug = trim($slug, '-');
        if (empty($slug)) {
            $slug = 'tag-' . Str::random(6);
        }

        $original = $slug;
        $count = 1;
        while (Tag::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        Tag::create([
            'name_bn' => $request->name_bn,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
