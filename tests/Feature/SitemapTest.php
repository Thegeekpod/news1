<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_index_renders_valid_xml()
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name_bn' => 'জাতীয়',
            'name_en' => 'National',
            'slug' => 'national',
            'description' => 'National news',
            'display_order' => 1
        ]);

        $article = Article::create([
            'title' => 'জাতীয় নির্বাচনের সর্বশেষ খবর',
            'slug' => 'national-election-latest-news',
            'content' => 'Article content here',
            'excerpt' => 'Short excerpt',
            'thumbnail_url' => 'https://example.com/image.jpg',
            'category_id' => $category->id,
            'user_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $tag = Tag::create([
            'name_bn' => 'নির্বাচন',
            'slug' => 'election'
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset', false);
        $response->assertSee('/category/national', false);
        $response->assertSee('/article/national-election-latest-news', false);
        $response->assertSee('/tag/election', false);
    }

    public function test_sitemap_news_renders_valid_xml()
    {
        $user = User::create([
            'name' => 'Author User',
            'email' => 'author@example.com',
            'password' => bcrypt('password'),
            'role' => 'writer',
        ]);

        $category = Category::create([
            'name_bn' => 'খেলাধুলা',
            'name_en' => 'Sports',
            'slug' => 'sports',
            'description' => 'Sports news',
            'display_order' => 2
        ]);

        $article = Article::create([
            'title' => 'ভারত বনাম অস্ট্রেলিয়া ক্রিকেট ম্যাচ আপডেট',
            'slug' => 'ind-vs-aus-cricket-match-update',
            'content' => 'Cricket match details',
            'thumbnail_url' => 'https://example.com/sports.jpg',
            'category_id' => $category->id,
            'user_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get('/sitemap-news.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('news:publication', false);
        $response->assertSee('/article/ind-vs-aus-cricket-match-update', false);
    }
}
