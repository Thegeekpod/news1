<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create([
            'email' => $request->email,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'ধন্যবাদ! আপনার সাবস্ক্রিপশন সফল হয়েছে।']);
        }

        return back()->with('success', 'ধন্যবাদ! আপনার সাবস্ক্রিপশন সফল হয়েছে।');
    }
}
