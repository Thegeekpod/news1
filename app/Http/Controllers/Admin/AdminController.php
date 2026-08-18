<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Ticker;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Display the admin panel dashboard.
     */
    public function dashboard()
    {
        $totalArticles = Article::count();
        $totalCategories = Category::count();
        $totalSubscribers = Newsletter::count();

        // Recent articles
        $recentArticles = Article::with(['category', 'author'])
            ->latest()
            ->take(5)
            ->get();

        // Recent subscribers
        $recentSubscribers = Newsletter::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalArticles',
            'totalCategories',
            'totalSubscribers',
            'recentArticles',
            'recentSubscribers'
        ));
    }
}
