<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TickerController as AdminTickerController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Admin\AdController as AdminAdController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\TagController as AdminTagController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');
Route::get('/tag/{slug}', [TagController::class, 'show'])->name('tag.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming-soon');
Route::get('/weather/refresh', [HomeController::class, 'refreshWeather'])->name('weather.refresh');
Route::get('/videos', [HomeController::class, 'videos'])->name('videos.all');
Route::get('/top-stories', [HomeController::class, 'topStories'])->name('lead-news.all');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Resource CRUDs
    Route::resource('articles', AdminArticleController::class)->names([
        'index' => 'admin.articles.index',
        'create' => 'admin.articles.create',
        'store' => 'admin.articles.store',
        'edit' => 'admin.articles.edit',
        'update' => 'admin.articles.update',
        'destroy' => 'admin.articles.destroy',
    ]);
    Route::resource('categories', AdminCategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    Route::resource('tags', AdminTagController::class)->only(['index', 'store', 'destroy'])->names([
        'index' => 'admin.tags.index',
        'store' => 'admin.tags.store',
        'destroy' => 'admin.tags.destroy',
    ]);
    Route::resource('tickers', AdminTickerController::class)->names([
        'index' => 'admin.tickers.index',
        'store' => 'admin.tickers.store',
        'update' => 'admin.tickers.update',
        'destroy' => 'admin.tickers.destroy',
    ]);
    Route::resource('videos', AdminVideoController::class)->names([
        'index' => 'admin.videos.index',
        'store' => 'admin.videos.store',
        'update' => 'admin.videos.update',
        'destroy' => 'admin.videos.destroy',
    ]);
    Route::resource('ads', AdminAdController::class)->names([
        'index' => 'admin.ads.index',
        'store' => 'admin.ads.store',
        'update' => 'admin.ads.update',
        'destroy' => 'admin.ads.destroy',
    ]);
    
    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    Route::post('/settings/test-weather-api', [AdminSettingsController::class, 'testApi'])->name('admin.settings.test_weather_api');
    
    // Newsletter subscribers
    Route::get('/newsletter', [AdminNewsletterController::class, 'index'])->name('admin.newsletter');
});

