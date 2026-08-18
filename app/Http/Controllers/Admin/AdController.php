<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdController extends Controller
{
    public function index()
    {
        $ads = Advertisement::latest()->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slot_name' => 'required|string',
            'ad_image' => 'required|image',
            'destination_url' => 'nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('ad_image')) {
            $file = $request->file('ad_image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ads'), $filename);
            $imagePath = 'uploads/ads/' . $filename;
        }

        Advertisement::create([
            'title' => $request->title,
            'slot_name' => $request->slot_name,
            'image_url' => $imagePath,
            'destination_url' => $request->destination_url,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement created successfully.');
    }

    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slot_name' => 'required|string',
            'ad_image' => 'nullable|image',
            'destination_url' => 'nullable|url',
        ]);

        $imagePath = $ad->image_url;
        if ($request->hasFile('ad_image')) {
            if ($ad->image_url && file_exists(public_path($ad->image_url))) {
                @unlink(public_path($ad->image_url));
            }
            $file = $request->file('ad_image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ads'), $filename);
            $imagePath = 'uploads/ads/' . $filename;
        }

        $ad->update([
            'title' => $request->title,
            'slot_name' => $request->slot_name,
            'image_url' => $imagePath,
            'destination_url' => $request->destination_url,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement updated successfully.');
    }

    public function destroy($id)
    {
        $ad = Advertisement::findOrFail($id);
        if ($ad->image_url && file_exists(public_path($ad->image_url))) {
            @unlink(public_path($ad->image_url));
        }
        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement deleted successfully.');
    }
}
