<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_bn' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'duration' => 'nullable|string|max:50',
            'thumbnail_url' => 'nullable|url',
            'thumbnail_file' => 'nullable|file',
        ]);

        $thumbnailUrl = null;
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/videos'), $filename);
            $thumbnailUrl = 'uploads/videos/' . $filename;
        } elseif ($request->thumbnail_url) {
            $thumbnailUrl = $request->thumbnail_url;
        } else {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $request->youtube_url, $match);
            $youtubeId = $match[1] ?? null;
            if ($youtubeId) {
                $thumbnailUrl = "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            } else {
                $thumbnailUrl = 'https://picsum.photos/seed/video/500/300';
            }
        }

        Video::create([
            'title_bn' => $request->title_bn,
            'youtube_url' => $request->youtube_url,
            'duration' => $request->duration ?? '3:00',
            'thumbnail_url' => $thumbnailUrl,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Video added successfully.');
    }

    public function destroy(Video $video)
    {
        $rawPath = $video->getRawOriginal('thumbnail_url');
        if ($rawPath && str_starts_with($rawPath, 'uploads/')) {
            $filePath = public_path($rawPath);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }
}
