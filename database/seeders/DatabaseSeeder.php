<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Article;
use App\Models\Ticker;
use App\Models\Video;
use App\Models\Advertisement;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'নিউজ১ এডমিন',
            'email' => 'admin@news1.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'designation' => 'প্রধান সম্পাদক',
            'avatar_url' => 'https://picsum.photos/seed/admin/100/100'
        ]);

        $writer1 = User::create([
            'name' => 'রাহুল দাস',
            'email' => 'rahul@news1.com',
            'password' => Hash::make('password123'),
            'role' => 'writer',
            'designation' => 'সিনিয়র সংবাদদাতা',
            'avatar_url' => 'https://picsum.photos/seed/rahul/100/100'
        ]);

        $writer2 = User::create([
            'name' => 'সুজয় গুহ',
            'email' => 'sujoy@news1.com',
            'password' => Hash::make('password123'),
            'role' => 'writer',
            'designation' => 'বিশেষ সংবাদদাতা',
            'avatar_url' => 'https://picsum.photos/seed/sujoy/100/100'
        ]);

        // 2. Create Categories
        $categoriesData = [
            ['name_bn' => 'রাজনীতি', 'name_en' => 'Politics', 'slug' => 'politics', 'description' => 'দেশ, রাজ্য ও বিশ্বের সর্বশেষ রাজনৈতিক সংবাদ, নির্বাচনী আলোচনা, দলীয় রদবদল, আইনসভার গতিবিধি ও নিরপেক্ষ রাজনৈতিক বিশ্লেষণ।', 'display_order' => 1],
            ['name_bn' => 'আন্তর্জাতিক', 'name_en' => 'International', 'slug' => 'international', 'description' => 'বিশ্বের বিভিন্ন দেশের সর্বশেষ সংবাদ ও আন্তর্জাতিক ঘটনাবলি।', 'display_order' => 2],
            ['name_bn' => 'খেলা', 'name_en' => 'Sports', 'slug' => 'sports', 'description' => 'ক্রিকেট, ফুটবল, কাবাডি এবং অন্যান্য খেলার খবরের সব আপডেট।', 'display_order' => 3],
            ['name_bn' => 'বিনোদন', 'name_en' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'বলিউড, টলিউড, ও হলিউড সিনেমার খবর এবং সেলিব্রিটি গসিপ।', 'display_order' => 4],
            ['name_bn' => 'টাকা পয়সা', 'name_en' => 'Finance', 'slug' => 'finance', 'description' => 'ব্যবসা, বাণিজ্য, শেয়ার বাজার ও সোনার দামের খবর।', 'display_order' => 5],
            ['name_bn' => 'প্রযুক্তি', 'name_en' => 'Tech', 'slug' => 'tech', 'description' => 'মোবাইল, গ্যাজেট, কৃত্রিম বুদ্ধিমত্তা ও বিজ্ঞানের নতুন খবর।', 'display_order' => 6],
            ['name_bn' => 'স্বাস্থ্য', 'name_en' => 'Health', 'slug' => 'health', 'description' => 'গরমে সুস্থ থাকার উপায় ও স্বাস্থ্য সম্পর্কিত নানা পরামর্শ।', 'display_order' => 7],
            ['name_bn' => 'লাইফস্টাইল', 'name_en' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'ভ্রমণ, ফ্যাশন, রেসিপি ও যাপনচিত্র।', 'display_order' => 8]
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Create Tags
        $tagsData = [
            ['name_bn' => 'রাজনীতি', 'slug' => 'politics'],
            ['name_bn' => 'ক্রিকেট', 'slug' => 'cricket'],
            ['name_bn' => 'বাজেট', 'slug' => 'budget'],
            ['name_bn' => 'পশ্চিমবঙ্গ', 'slug' => 'west-bengal'],
            ['name_bn' => 'ভারত', 'slug' => 'india'],
            ['name_bn' => 'আবহাওয়া', 'slug' => 'weather'],
            ['name_bn' => 'আইপিএল', 'slug' => 'ipl'],
            ['name_bn' => 'শেয়ার বাজার', 'slug' => 'stock-market'],
            ['name_bn' => 'বিনোদন', 'slug' => 'entertainment'],
            ['name_bn' => 'প্রযুক্তি', 'slug' => 'technology'],
            ['name_bn' => 'স্বাস্থ্য', 'slug' => 'health'],
            ['name_bn' => 'শিক্ষা', 'slug' => 'education']
        ];

        $tags = [];
        foreach ($tagsData as $t) {
            $tags[$t['slug']] = Tag::create($t);
        }

        // 4. Create Tickers (Breaking news)
        $tickers = [
            ['text_bn' => 'কেন্দ্রীয় বাজেট ২০২৬-২৭ ঘোষণা — মধ্যবিত্তদের জন্য বড় সুখবর', 'link_url' => '/article/budget-2026', 'is_active' => true],
            ['text_bn' => 'ভারত-পাকিস্তান সীমান্তে উত্তেজনা বাড়ছে, সেনা মোতায়েন', 'link_url' => '#', 'is_active' => true],
            ['text_bn' => 'পশ্চিমবঙ্গে তীব্র তাপপ্রবাহ, ৪৫ ডিগ্রি ছুঁয়েছে পারদ', 'link_url' => '#', 'is_active' => true],
            ['text_bn' => 'আইপিএল ২০২৬: কলকাতা নাইট রাইডার্স ফাইনালে', 'link_url' => '#', 'is_active' => true],
            ['text_bn' => 'শেয়ার বাজারে ধস — সেনসেক্স ১৫০০ পয়েন্ট নিচে', 'link_url' => '#', 'is_active' => true],
            ['text_bn' => 'বাংলাদেশে আকস্মিক বন্যা, ৫ লক্ষ মানুষ ক্ষতিগ্রস্ত', 'link_url' => '#', 'is_active' => true],
        ];
        foreach ($tickers as $ticker) {
            Ticker::create($ticker);
        }

        // 5. Create Videos
        $videos = [
            ['title_bn' => 'কলকাতায় ব্রিজ ভাঙার ঘটনা — লাইভ রিপোর্ট করলেন আমাদের সংবাদদাতা', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'duration' => '৩:২৪', 'thumbnail_url' => 'https://picsum.photos/seed/v1/500/300'],
            ['title_bn' => 'বাজেট পরবর্তী বিশেষজ্ঞদের বিশ্লেষণ — কী লাভ হবে সাধারণ মানুষের?', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'duration' => '৫:১০', 'thumbnail_url' => 'https://picsum.photos/seed/v2/500/300'],
            ['title_bn' => 'আইপিএলের হাইলাইটস — দেখুন সেরা মুহূর্তগুলো', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'duration' => '২:৪৫', 'thumbnail_url' => 'https://picsum.photos/seed/v3/500/300'],
        ];
        foreach ($videos as $video) {
            Video::create($video);
        }

        // 6. Create Advertisements
        $ads = [
            ['title' => 'প্রিমিয়াম স্পনসরশিপ ব্যানার', 'slot_name' => 'home_banner_top', 'image_url' => 'assets/ad_banner_premium.png', 'destination_url' => 'https://google.com', 'is_active' => true],
            ['title' => 'টেকনোলজি বিজ্ঞাপন ব্যানার', 'slot_name' => 'tech_banner', 'image_url' => 'assets/ad_banner_travel.png', 'destination_url' => 'https://google.com', 'is_active' => true],
            ['title' => 'সাইডবার বিজ্ঞাপন স্কয়ার', 'slot_name' => 'sidebar_banner', 'image_url' => 'assets/ad_banner_travel.png', 'destination_url' => 'https://google.com', 'is_active' => true],
        ];
        foreach ($ads as $ad) {
            Advertisement::create($ad);
        }

        // 8. Create Settings
        $settings = [
            'site_name' => 'নিউজ১',
            'facebook_url' => 'https://facebook.com/news1',
            'twitter_url' => 'https://twitter.com/news1',
            'youtube_url' => 'https://youtube.com/news1',
            'instagram_url' => 'https://instagram.com/news1',
            'whatsapp_url' => 'https://whatsapp.com/channel/news1',
            'facebook_followers' => '২.৪ লক্ষ ফলোয়ার',
            'youtube_subscribers' => '১.১ লক্ষ সাবস্ক্রাইবার',
            'twitter_followers' => '৮৭ হাজার ফলোয়ার',
            'weather_location' => 'দূর্গাপুর, পশ্চিমবঙ্গ',
            'weather_temp' => '৩৩',
            'weather_desc' => 'পরিষ্কার আকাশ',
            'weather_humidity' => '৬৪%',
            'weather_wind' => '১১ km/h',
            'weather_high' => '৪১°C',
            'weather_low' => '২৮°C',
            'footer_about' => 'নিউজ১ হল ভারতের অন্যতম অগ্রণী ডিজিটাল বাংলা সংবাদ মাধ্যম। সত্য, নিরপেক্ষ ও বস্তুনিষ্ঠ খবর পৌঁছে দেওয়াই আমাদের একমাত্র লক্ষ্য।'
        ];
        foreach ($settings as $key => $val) {
            Setting::create(['key' => $key, 'value' => $val]);
        }

        // 9. Create Articles
        // Lead Story: Budget 2026-27 (Politics)
        $lead = Article::create([
            'title' => 'কেন্দ্রীয় বাজেট ২০২৬-২৭ ঘোষণা: মধ্যবিত্তদের জন্য আয়করে বিশাল ছাড়, কৃষকদের জন্য বিশেষ প্যাকেজ ঘোষণা করলেন অর্থমন্ত্রী',
            'slug' => 'budget-2026',
            'content' => '<p>নতুন দিল্লি, ২ জুন: কেন্দ্রীয় অর্থমন্ত্রী আজ বাজেট ২০২৬-২৭ সংসদে উপস্থাপন করেছেন। এই বাজেটে মধ্যবিত্ত পরিবারের জন্য আয়করে উল্লেখযোগ্য ছাড় ঘোষণা করা হয়েছে। বার্ষিক ১২ লক্ষ টাকা পর্যন্ত আয়ে কোনো আয়কর দিতে হবে না বলে জানিয়েছেন অর্থমন্ত্রী।</p>
            <p>এই বাজেটে সরকার কৃষি খাতে বরাদ্দ ব্যাপকভাবে বাড়িয়েছে। প্রধানমন্ত্রী কিষাণ সম্মান নিধির পরিমাণ বছরে ৬,০০০ টাকা থেকে বাড়িয়ে ৯,০০০ টাকা করা হয়েছে। পাশাপাশি কৃষিপণ্যের সংরক্ষণ ও পরিবহনে ভর্তুকি বাড়ানোর ঘোষণাও করা হয়েছে।</p>
            <blockquote>"এই বাজেট হল ভারতের সাধারণ মানুষের জন্য একটি আশার আলো। মধ্যবিত্তের স্বপ্ন পূরণ এবং কৃষকদের সমৃদ্ধির পথেই আমরা এগিয়ে যাচ্ছি।" — কেন্দ্রীয় অর্থমন্ত্রী</blockquote>
            <h2>আয়করে নতুন ছাড়ের বিবরণ</h2>
            <p>নতুন কর কাঠামো অনুযায়ী, বার্ষিক ১২ লক্ষ টাকা পর্যন্ত আয়ে শূন্য কর, ১২ থেকে ২০ লক্ষ টাকায় ১০%, ২০ থেকে ৩০ লক্ষ টাকায় ২০% এবং ৩০ লক্ষ টাকার ঊর্ধ্বে ৩০% করের প্রস্তাব রয়েছে। এই সিদ্ধান্ত কার্যকর হলে দেশের প্রায় ৮ কোটি করদাতা উপকৃত হবেন বলে সরকার জানিয়েছে।</p>
            <p>স্বাস্থ্য বীমার প্রিমিয়ামে ছাড়ের সীমাও বাড়ানো হয়েছে। এর আগে ধারা ৮০ডি-র অধীনে ২৫,০০০ টাকা পর্যন্ত ছাড় পাওয়া যেত, এখন সেটি বাড়িয়ে ৫০,০০০ টাকা করা হয়েছে।</p>
            <h2>কৃষি খাতে বিনিয়োগ</h2>
            <p>কৃষি খাতে মোট ৩.৫ লক্ষ কোটি টাকার বরাদ্দ করা হয়েছে, যা গত বছরের তুলনায় ১৮% বেশি। নতুন কৃষি পরিকাঠামো তহবিলের মাধ্যমে গ্রামে গ্রামে কোল্ড স্টোরেজ ও গুদামঘর নির্মাণ করা হবে।</p>
            <h3>শিক্ষা ও স্বাস্থ্যে বিশেষ নজর</h3>
            <p>শিক্ষা খাতে GDP-র ৪% বরাদ্দের লক্ষ্যমাত্রা নির্ধারণ করা হয়েছে। নতুন মেডিক্যাল কলেজ স্থাপন এবং প্রাথমিক স্বাস্থ্য কেন্দ্রের উন্নয়নে বিশেষ তহবিল গঠন করা হবে।</p>
            <p>পরিকাঠামো উন্নয়নে ১৫ লক্ষ কোটি টাকা বরাদ্দ করা হয়েছে। নতুন এক্সপ্রেসওয়ে, রেলপথ এবং বন্দর নির্মাণের মাধ্যমে দেশের যোগাযোগ ব্যবস্থাকে আধুনিক করে তোলা হবে। বুলেট ট্রেন প্রকল্পেও নতুন করে বিনিয়োগ হচ্ছে।</p>
            <h3>ডিজিটাল ভারতে নতুন গতি</h3>
            <p>ডিজিটাল ইন্ডিয়া প্রকল্পে ২ লক্ষ কোটি টাকা বরাদ্দ করা হয়েছে। কৃত্রিম বুদ্ধিমত্তা (AI) গবেষণা ও উন্নয়নে বিশেষ তহবিল গঠন করা হবে। সরকারি কাজকর্মে AI-র ব্যবহার বাড়াতে নতুন নীতিমালাও ঘোষণা করা হয়েছে।</p>
            <p>বিশেষজ্ঞরা মনে করছেন, এই বাজেট দেশের অর্থনীতিকে একটি নতুন গতিপথে নিয়ে যেতে সহায়ক হবে। তবে বিরোধীরা মনে করছেন, সাধারণ মানুষের জীবনযাত্রার ব্যয় কমাতে এই বাজেট যথেষ্ট নয়।</p>',
            'excerpt' => 'চলতি বছরের বাজেটে সরকার মধ্যবিত্ত পরিবারের জন্য আয়করে উল্লেখযোগ্য ছাড় ঘোষণা করেছে। পাশাপাশি কৃষি খাতে বরাদ্দ বাড়িয়ে দেশের কৃষকদের স্বস্তি দেওয়ার চেষ্টা করা হয়েছে।',
            'thumbnail_url' => 'https://picsum.photos/seed/hero1/800/480',
            'category_id' => $categories['politics']->id,
            'user_id' => $writer1->id,
            'status' => 'published',
            'view_count' => 12456,
            'is_breaking' => true,
            'is_lead' => true,
            'is_sub_lead' => false,
            'is_popular' => true,
            'is_latest' => true,
            'is_special_banner' => true,
            'published_at' => Carbon::now()->subHours(2),
            'meta_title' => 'কেন্দ্রীয় বাজেট ২০২৬-২৭ ঘোষণা: মধ্যবিত্তদের জন্য বিশাল স্বস্তি',
            'meta_description' => 'চলতি বছরের বাজেটে সরকার মধ্যবিত্ত পরিবারের জন্য আয়করে উল্লেখযোগ্য ছাড় ঘোষণা করেছে।',
            'keywords' => 'বাজেট, আয়কর, কৃষি, অর্থনীতি, কেন্দ্র সরকার, রাজনীতি'
        ]);
        $lead->tags()->attach([$tags['budget']->id, $tags['politics']->id, $tags['india']->id]);

        // Sub Lead 1: IPL match (Sports)
        $sub1 = Article::create([
            'title' => 'আইপিএল ফাইনালে কেকেআর বনাম মুম্বাই ইন্ডিয়ান্স — জমজমাট লড়াইয়ের প্রস্তুতি',
            'slug' => 'ipl-final-kkr-mi',
            'content' => '<p>কলকাতা: আইপিএল ২০২৬-এর ফাইনাল ম্যাচে মুখোমুখি হতে চলেছে কলকাতা নাইট রাইডার্স ও মুম্বাই ইন্ডিয়ান্স। দুই দরের পারফরম্যান্সই এবার টুর্নামেন্টে নজরকাড়া হয়েছে। ইডেন গার্ডেন্সে ফাইনাল ম্যাচটি অনুষ্ঠিত হবে। টিকিট ইতিমধ্যেই সব বিক্রি হয়ে গিয়েছে।</p>
            <p>কেকেআর অধিনায়ক শ্রেয়স আইয়ার জানিয়েছেন, "আমরা চ্যাম্পিয়ন হতে নিজেদের সবটুকু উজাড় করে দেব।" অন্যদিকে মুম্বাই শিবিরের মেন্টর শচীন তেন্ডুলকর মনে করছেন, "ফাইনালের চাপ যে দল ভালো সামলাবে, তারাই জিতবে।"</p>',
            'excerpt' => 'আইপিএল ফাইনালে মুখোমুখি কেকেআর ও মুম্বাই ইন্ডিয়ান্স। ইডেনে ট্রফি জয়ের লড়াইয়ের সমস্ত খুঁটিনাটি খবর জানুন।',
            'thumbnail_url' => 'https://picsum.photos/seed/hero2/600/360',
            'category_id' => $categories['sports']->id,
            'user_id' => $writer2->id,
            'status' => 'published',
            'view_count' => 9876,
            'is_breaking' => false,
            'is_lead' => false,
            'is_sub_lead' => true,
            'is_popular' => true,
            'is_latest' => true,
            'published_at' => Carbon::now()->subHours(3),
            'meta_title' => 'আইপিএল ফাইনালে কেকেআর বনাম মুম্বাই ইন্ডিয়ান্স',
            'meta_description' => 'আইপিএল ফাইনালে মুখোমুখি কেকেআর ও মুম্বাই ইন্ডিয়ান্স। ইডেনে ট্রফি জয়ের লড়াইয়ের প্রস্তুতি তুঙ্গে।',
            'keywords' => 'আইপিএল, কেকেআর, cricket, মুম্বাই ইন্ডিয়ান্স'
        ]);
        $sub1->tags()->attach([$tags['ipl']->id, $tags['cricket']->id]);

        // Sub Lead 2: Tollywood movies (Entertainment)
        $sub2 = Article::create([
            'title' => 'টলিউডে নতুন ঢেউ — শ্রেষ্ঠ পুরস্কারের মঞ্চে বাংলা ছবির দাপট',
            'slug' => 'tollywood-movies-national-awards',
            'content' => '<p>কলকাতা: জাতীয় চলচ্চিত্র পুরস্কারের মঞ্চে বাংলা ছবির অসামান্য সাফল্য টলিপাড়ায় খুশির হাওয়া নিয়ে এসেছে। এবার মোট ৫টি প্রধান বিভাগে পুরস্কৃত হয়েছে বাংলা চলচ্চিত্র। সেরা পরিচালকের সম্মান পেয়েছেন সৃজিত মুখোপাধ্যায় এবং সেরা চলচ্চিত্রের খেতাব জিতেছে একটি নতুন সমাজধর্মী ছবি।</p>',
            'excerpt' => 'জাতীয় চলচ্চিত্র পুরস্কারের মঞ্চে বাংলা চলচ্চিত্রের অসাধারণ পারফরম্যান্স। ৫টি বিভাগে পুরস্কার ছিনিয়ে আনল টলিউড।',
            'thumbnail_url' => 'https://picsum.photos/seed/hero3/600/360',
            'category_id' => $categories['entertainment']->id,
            'user_id' => $writer2->id,
            'status' => 'published',
            'view_count' => 8234,
            'is_breaking' => false,
            'is_lead' => false,
            'is_sub_lead' => true,
            'is_popular' => true,
            'is_latest' => true,
            'published_at' => Carbon::now()->subHours(5),
            'meta_title' => 'জাতীয় পুরস্কারের মঞ্চে টলিউডের জয়জয়কার',
            'meta_description' => 'জাতীয় চলচ্চিত্র পুরস্কারে ৫টি বিভাগে পুরস্কৃত হল বাংলা ছবি। টলিপাড়ায় খুশির হাওয়া।',
            'keywords' => 'টলিউড, চলচ্চিত্র, বিনোদন, জাতীয় পুরস্কার'
        ]);
        $sub2->tags()->attach([$tags['entertainment']->id, $tags['west-bengal']->id]);

        // 3. Politics Articles (for category listing)
        $polArticles = [
            [
                'title' => 'বিধানসভা নির্বাচন ২০২৬: জোরকদমে প্রচার শুরু রাজনৈতিক দলগুলির, ইস্তাহার প্রকাশের প্রস্তুতি তুঙ্গে',
                'slug' => 'assembly-election-2026-campaigns',
                'content' => '<p>কলকাতা: আসন্ন বিধানসভা নির্বাচনকে কেন্দ্র করে রাজ্য রাজনীতিতে দলগুলির তৎপরতা বৃদ্ধি পেয়েছে। সমস্ত দলই নিজেদের নির্বাচনী রণকৌশল তৈরি করতে বৈঠক শুরু করেছে এবং ভোটারদের কাছে টানতে নতুন প্রতিশ্রুতি ও জনসভার পরিকল্পনা চলছে। রাজনৈতিক দলগুলি ভোটারদের উদ্দেশ্যে নিজেদের ইস্তাহার চূড়ান্ত করার কাজে ব্যস্ত।</p>',
                'excerpt' => 'আসন্ন বিধানসভা নির্বাচনকে কেন্দ্র করে রাজ্য রাজনীতিতে দলগুলির তৎপরতা বৃদ্ধি পেয়েছে। সমস্ত দলই নিজেদের নির্বাচনী রণকৌশল তৈরি করতে ব্যস্ত।',
                'thumbnail_url' => 'https://picsum.photos/seed/polhero1/800/480',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer2->id,
                'status' => 'published',
                'view_count' => 8245,
                'is_popular' => true,
                'is_latest' => true,
                'published_at' => Carbon::now()->subMinutes(30)
            ],
            [
                'title' => 'সংসদে বাজেট অধিবেশন: কর কাঠামো পরিবর্তন নিয়ে অর্থমন্ত্রীর জবাবি ভাষণ আজ',
                'slug' => 'parliament-budget-session-minister-reply',
                'content' => '<p>নতুন দিল্লি: সংসদে শুরু হয়েছে বাজেট অধিবেশন। বিভিন্ন রাজনৈতিক দলের পক্ষ থেকে বাজেট নিয়ে সমালোচনার জবাবে আজ বক্তব্য রাখবেন কেন্দ্রীয় অর্থমন্ত্রী। বিশেষ করে আয়করের নতুন স্ল্যাব এবং কর্পোরেট করে ছাড় নিয়ে বিতর্ক চলছে। অর্থমন্ত্রী কর কাঠামো সংস্কার নিয়ে বিস্তারিত তথ্য পেশ করবেন আজ দুপুরে।</p>',
                'excerpt' => 'সংসদে বাজেট অধিবেশন: কর কাঠামো পরিবর্তন নিয়ে অর্থমন্ত্রীর জবাবি ভাষণ আজ।',
                'thumbnail_url' => 'https://picsum.photos/seed/polhero2/600/360',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer1->id,
                'status' => 'published',
                'view_count' => 5412,
                'is_popular' => true,
                'is_latest' => true,
                'published_at' => Carbon::now()->subHours(2)
            ],
            [
                'title' => 'জেলায় জেলায় তৃণমূলের সাংগঠনিক পরিবর্তন, নতুন নেতৃত্বের হাতে দায়িত্ব',
                'slug' => 'tmc-district-committee-reorganization',
                'content' => '<p>কলকাতা: বিধানসভা নির্বাচনের ঠিক আগে জেলায় জেলায় নিজেদের সংগঠনের শক্তি বৃদ্ধি করতে রদবদল ঘোষণা করল তৃণমূল কংগ্রেস। বেশ কয়েকটি জেলায় সভাপতি বদল করা হয়েছে এবং যুব নেতাদের সামনের সারিতে নিয়ে আসা হয়েছে। লক্ষ্য হল নিচুতলার কর্মীদের চাঙ্গা করা।</p>',
                'excerpt' => 'জেলায় জেলায় তৃণমূলের সাংগঠনিক পরিবর্তন, নতুন নেতৃত্বের হাতে দায়িত্ব।',
                'thumbnail_url' => 'https://picsum.photos/seed/polhero3/600/360',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer2->id,
                'status' => 'published',
                'view_count' => 4567,
                'is_popular' => false,
                'is_latest' => true,
                'published_at' => Carbon::now()->subHours(4)
            ],
            [
                'title' => 'লোকসভায় বিরোধীদের হইচই, কেন্দ্রীয় নীতি নিয়ে তুমুল বিতর্ক',
                'slug' => 'lok-sabha-opposition-protest',
                'content' => '<p>নতুন দিল্লি: দেশের সার্বিক অর্থনৈতিক পরিস্থিতি ও মূল্যবৃদ্ধি ইস্যুতে লোকসভায় সরব হল বিরোধী দলগুলি। সরকার পক্ষের বিরুদ্ধে একতরফা সিদ্ধান্ত ও নীতি প্রণয়নের অভিযোগ তুলে অধিবেশনের শুরু থেকেই বিরোধীরা স্লোগান দিতে থাকেন। পরিস্থিতি নিয়ন্ত্রণে আনতে স্পিকারকে অধিবেশন সাময়িক স্থগিত করতে হয়।</p>',
                'excerpt' => 'লোকসভায় বিরোধীদের হইচই, কেন্দ্রীয় নীতি নিয়ে তুমুল বিতর্ক।',
                'thumbnail_url' => 'https://picsum.photos/seed/pol1/600/380',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer1->id,
                'status' => 'published',
                'view_count' => 6123,
                'published_at' => Carbon::now()->subHours(5)
            ],
            [
                'title' => 'মমতা ব্যানার্জির নেতৃত্বে তৃণমূল কংগ্রেসের বিশাল পদযাত্রা কলকাতায়',
                'slug' => 'mamata-banerjee-rally-kolkata',
                'content' => '<p>কলকাতা: ধর্মতলা থেকে রাসবিহারী মোড় পর্যন্ত মমতা ব্যানার্জির নেতৃত্বে বিশাল মিছিল অনুষ্ঠিত হল। রাজ্যের দাবিদাওয়া আদায় ও কেন্দ্রের বঞ্চনার প্রতিবাদে এই মিছিলের আয়োজন করা হয়। মিছিলে সাধারণ মানুষের পাশাপাশি দলের শীর্ষ নেতৃত্ব পা মেলান। পথসভা থেকে তীব্র আক্রমণ করেন তৃণমূল নেত্রী।</p>',
                'excerpt' => 'মমতা ব্যানার্জির নেতৃত্বে তৃণমূল কংগ্রেসের বিশাল পদযাত্রা কলকাতায়।',
                'thumbnail_url' => 'https://picsum.photos/seed/pol2/600/380',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer2->id,
                'status' => 'published',
                'view_count' => 7450,
                'published_at' => Carbon::now()->subHours(7)
            ],
            [
                'title' => 'কংগ্রেসের নতুন কৌশল — জোট শরিকদের নিয়ে আসন বণ্টন নিয়ে আলোচনা',
                'slug' => 'congress-seat-sharing-alliance-talks',
                'content' => '<p>নতুন দিল্লি: আসন্ন নির্বাচনের লড়াইয়ে জোটকে আরও মজবুত করতে আসন রফায় মনোযোগী হয়েছে কংগ্রেস। বিভিন্ন রাজ্যের আঞ্চলিক দলগুলির সাথে দফায় দফায় বৈঠক করছেন শীর্ষ নেতৃত্ব। আসন বণ্টন নিয়ে সমঝোতা দ্রুত সম্পন্ন করার তাগিদ দেওয়া হয়েছে দলীয় স্তরে।</p>',
                'excerpt' => 'কংগ্রেসের নতুন কৌশল — জোট শরিকদের নিয়ে আসন বণ্টন নিয়ে আলোচনা।',
                'thumbnail_url' => 'https://picsum.photos/seed/pol3/600/380',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer1->id,
                'status' => 'published',
                'view_count' => 3123,
                'published_at' => Carbon::now()->subHours(8)
            ],
            [
                'title' => 'বিজেপির নতুন প্রচার কর্মসূচি — বুথ স্তরে শক্তি বৃদ্ধিতে বিশেষ নজর',
                'slug' => 'bjp-booth-level-campaign-strategy',
                'content' => '<p>কলকাতা: বুথ লেভেলে কর্মী সংগঠনকে আরও শক্তিশালী করতে বিশেষ টিম গঠন করেছে বিজেপি। প্রতিটি এলাকার স্থানীয় ক্ষোভ ও সুবিধা-অসুবিধা চিহ্নিত করে প্রচার করার রণকৌশল নেওয়া হয়েছে। সর্বভারতীয় নেতাদের উপস্থিতিতে বাংলায় একাধিক বুথ সম্মেলনের প্রস্তুতি শুরু হয়েছে।</p>',
                'excerpt' => 'বিজেপির নতুন প্রচার কর্মসূচি — বুথ স্তরে শক্তি বৃদ্ধিতে বিশেষ নজর।',
                'thumbnail_url' => 'https://picsum.photos/seed/pol4/600/380',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer2->id,
                'status' => 'published',
                'view_count' => 2894,
                'published_at' => Carbon::now()->subHours(9)
            ],
            [
                'title' => 'দিল্লির রাজনৈতিক পরিস্থিতি — নতুন মুখ্যমন্ত্রী পদের দাবিদারদের তালিকা',
                'slug' => 'delhi-political-scenario-chief-minister',
                'content' => '<p>নতুন দিল্লি: দিল্লির রাজনীতিতে মুখ্যমন্ত্রী পদ ঘিরে জল্পনা তীব্র হচ্ছে। আম আদমি পার্টির অভ্যন্তরে নেতৃত্ব পরিবর্তন নিয়ে বৈঠকের পর কয়েকটি নাম নিয়ে আলোচনা চলছে। বিরোধীদের দাবি, সরকারের অবিলম্বে নির্বাচন ঘোষণা করা উচিত। আপ নেতৃত্ব খুব শীঘ্রই দলীয় সিদ্ধান্তের কথা জানাবে।</p>',
                'excerpt' => 'দিল্লির political পরিস্থিতি — নতুন মুখ্যমন্ত্রী পদের দাবিদারদের তালিকা।',
                'thumbnail_url' => 'https://picsum.photos/seed/pol5/600/380',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer1->id,
                'status' => 'published',
                'view_count' => 1982,
                'published_at' => Carbon::now()->subHours(10)
            ],
            [
                'title' => 'নির্বাচন কমিশনের সর্বদলীয় বৈঠক — voter তালিকা সংশোধন নিয়ে আলোচনা',
                'slug' => 'election-commission-all-party-meeting',
                'content' => '<p>কলকাতা: রাজ্যের মুখ্য নির্বাচনী আধিকারিকের কার্যালয়ে আয়োজিত হল সর্বদলীয় বৈঠক। ভোটার তালিকায় ত্রুটি দূরীকরণ, নতুন ভোটারদের নাম সংযোজন ও বুথের পুনর্বিন্যাস নিয়ে আলোচনা করা হয়। সমস্ত রাজনৈতিক দলই তাদের অভিযোগ ও মতামত কমিশনের কাছে পেশ করে।</p>',
                'excerpt' => 'নির্বাচন কমিশনের সর্বদলীয় বৈঠক — ভোটার তালিকা সংশোধন নিয়ে আলোচনা।',
                'thumbnail_url' => 'https://picsum.photos/seed/pol6/600/380',
                'category_id' => $categories['politics']->id,
                'user_id' => $writer1->id,
                'status' => 'published',
                'view_count' => 3128,
                'published_at' => Carbon::now()->subHours(12)
            ]
        ];

        foreach ($polArticles as $art) {
            $a = Article::create($art);
            $a->tags()->attach([$tags['politics']->id, $tags['west-bengal']->id]);
        }

        // 10. Write other category stories to fill widgets
        // International
        $intl = Article::create([
            'title' => 'ইউক্রেন যুদ্ধে নতুন মোড়, ন্যাটো জরুরি বৈঠক ডাকল',
            'slug' => 'ukraine-war-nato-emergency-meeting',
            'content' => '<p>আন্তর্জাতিক সংবাদ: পূর্ব ইউরোপের রণক্ষেত্রে নতুন করে ব্যাপক গোলাবর্ষণ ও সেনা অভিযানের জেরে উদ্বেগ বৃদ্ধি পেয়েছে। পরিস্থিতির গুরুত্ব পর্যালোচনা করতে ব্রাসেলসে জরুরি বৈঠক ডেকেছে ন্যাটো। বিশ্ব বাণিজ্যে এর প্রভাব নিয়েও গভীর আলোচনা চলছে শরিক দেশগুলির মধ্যে।</p>',
            'excerpt' => 'ইউক্রেন যুদ্ধে নতুন মোড়, ন্যাটো জরুরি বৈঠক ডাকল।',
            'thumbnail_url' => 'https://picsum.photos/seed/l1/400/250',
            'category_id' => $categories['international']->id,
            'user_id' => $writer1->id,
            'status' => 'published',
            'view_count' => 4567,
            'published_at' => Carbon::now()->subHours(1)
        ]);
        $intl->tags()->attach([$tags['india']->id]);

        // Tech
        $tech = Article::create([
            'title' => 'ভারতে AI স্টার্টআপে রেকর্ড বিনিয়োগ, তিন মাসে ৫০০০ কোটি টাকা',
            'slug' => 'india-ai-startups-record-funding',
            'content' => '<p>প্রযুক্তি: ভারতের প্রযুক্তি খাতে কৃত্রিম বুদ্ধিমত্তা (AI) স্টার্টআপগুলিতে বিপুল বিনিয়োগ লক্ষ্য করা যাচ্ছে। গত তিন মাসে প্রায় ৫০০০ কোটি টাকার বৈদেশিক লগ্নি এসেছে। ব্যাঙ্গালোর ও হায়দ্রাবাদের স্টার্টআপগুলি এই তহবিলের সিংহভাগ পেয়েছে, যা নতুন কর্মসংস্থান তৈরি করবে।</p>',
            'excerpt' => 'ভারতে AI স্টার্টআপে রেকর্ড বিনিয়োগ, তিন মাসে ৫০০০ কোটি টাকা।',
            'thumbnail_url' => 'https://picsum.photos/seed/l2/400/250',
            'category_id' => $categories['tech']->id,
            'user_id' => $writer2->id,
            'status' => 'published',
            'view_count' => 5123,
            'published_at' => Carbon::now()->subHours(2)
        ]);
        $tech->tags()->attach([$tags['technology']->id, $tags['india']->id]);

        $tech2 = Article::create([
            'title' => 'অ্যাপলের নতুন আইফোন সিরিজে বড় চমক — স্যাটেলাইট কানেক্টিভিটি ও উন্নত AI প্রসেসর',
            'slug' => 'apple-iphone-new-series-satellite-ai-chip',
            'content' => '<p>প্রযুক্তি: বিশ্বখ্যাত প্রযুক্তি সংস্থা অ্যাপল তাদের নতুন স্মার্টফোন সিরিজে বিশেষ এআই ফিচারের ঘোষণা দিল। এতে স্যাটেলাইট কলিং এবং অত্যন্ত শক্তিশালী চিপসেট ব্যবহার করা হয়েছে।</p>',
            'excerpt' => 'নতুন প্রসেসর ও স্যাটেলাইট কানেক্টিভিটি ফিচার নিয়ে লঞ্চ হলো নতুন আইফোন সিরিজ।',
            'thumbnail_url' => 'https://picsum.photos/seed/tech2/400/250',
            'category_id' => $categories['tech']->id,
            'user_id' => $writer1->id,
            'status' => 'published',
            'view_count' => 5890,
            'published_at' => Carbon::now()->subHours(1)
        ]);
        $tech2->tags()->attach([$tags['technology']->id]);

        // Health
        $health = Article::create([
            'title' => 'গরমে সুস্থ থাকার সহজ উপায় — বিশেষজ্ঞদের পরামর্শ জানুন',
            'slug' => 'heatwave-health-precautions-expert-tips',
            'content' => '<p>স্বাস্থ্য: গরমে সুস্থ থাকতে প্রচুর জল পান করা ও হালকা সুতির পোশাক পরিধানের পরামর্শ দিচ্ছেন চিকিৎসকরা। বিশেষ করে দুপুর ১২টা থেকে বিকেল ৩টে পর্যন্ত কড়া রোদ এড়িয়ে চলাই ভালো। খাদ্যতালিকায় ফলমূল ও সহজপাচ্য খাবার রাখার তাগিদ দেওয়া হচ্ছে।</p>',
            'excerpt' => 'গরমে সুস্থ থাকার সহজ উপায় — বিশেষজ্ঞদের পরামর্শ জানুন।',
            'thumbnail_url' => 'https://picsum.photos/seed/l3/400/250',
            'category_id' => $categories['health']->id,
            'user_id' => $writer1->id,
            'status' => 'published',
            'view_count' => 3110,
            'published_at' => Carbon::now()->subHours(3)
        ]);
        $health->tags()->attach([$tags['health']->id]);

        // Lifestyle
        $life = Article::create([
            'title' => 'রোজকার রান্নায় এই ৫টি মশলা যোগ করুন, রোগ থেকে দূরে থাকুন',
            'slug' => 'healthy-spices-to-include-in-daily-cooking',
            'content' => '<p>লাইফস্টাইল: রান্নায় ব্যবহৃত মশলা যেমন হলুদ, জিরে, মেথি ও গোলমরিচ শুধুমাত্র খাবারের স্বাদই বাড়ায় না, পাশাপাশি রোগ প্রতিরোধ ক্ষমতা বৃদ্ধিতেও গুরুত্বপূর্ণ ভূমিকা পালন করে। এগুলিতে রয়েছে বিভিন্ন ভেষজ গুণ যা শরীরকে সুস্থ রাখে।</p>',
            'excerpt' => 'রোজকার রান্নায় এই ৫টি মশলা যোগ করুন, রোগ থেকে দূরে থাকুন।',
            'thumbnail_url' => 'https://picsum.photos/seed/l4/400/250',
            'category_id' => $categories['lifestyle']->id,
            'user_id' => $writer2->id,
            'status' => 'published',
            'view_count' => 2341,
            'published_at' => Carbon::now()->subHours(4)
        ]);

        // Finance
        $fin = Article::create([
            'title' => 'সোনার দাম আজ রেকর্ড উচ্চতায়, কি করবেন বিনিয়োগকারীরা?',
            'slug' => 'gold-prices-hit-record-highs-investors-guide',
            'content' => '<p>টাকা পয়সা: বিশ্ববাজারে মুদ্রাস্ফীতির জেরে সোনার দাম রেকর্ড উচ্চতা স্পর্শ করেছে। প্রতি ১০ গ্রাম পাকা সোনার দাম ৮২ হাজার টাকা পার করেছে আজ। বাজার বিশেষজ্ঞরা মনে করছেন, দীর্ঘমেয়াদী বিনিয়োগের জন্য সোনা এখনও নিরাপদ বিকল্প, তবে বর্তমানে দেখেশুনে লগ্নির পরামর্শ।</p>',
            'excerpt' => 'সোনার দাম আজ রেকর্ড উচ্চতায়, কি করবেন বিনিয়োগকারীরা?',
            'thumbnail_url' => 'https://picsum.photos/seed/l5/400/250',
            'category_id' => $categories['finance']->id,
            'user_id' => $writer1->id,
            'status' => 'published',
            'view_count' => 7821,
            'published_at' => Carbon::now()->subHours(5)
        ]);
        $fin->tags()->attach([$tags['stock-market']->id]);

        // Additional Sports Articles
        $sports2 = Article::create([
            'title' => 'টি-২০ বিশ্বকাপ: দুর্দান্ত সেঞ্চুরি করে ভারতকে ঐতিহাসিক জয় এনে দিলেন শুভমান গিল',
            'slug' => 't20-world-cup-shubman-gill-century-india-win',
            'content' => '<p>খেলাধুলো: টি-২০ বিশ্বকাপের সেমিফাইনালে অসাধারণ ব্যাটিং পারফরম্যান্স দেখা গেল। শুভমান গিলের বিধ্বংসী শতরণে ভর করে ভারত প্রতিপক্ষকে ৬ উইকেটে হারিয়ে বিশ্বকাপের ফাইনালে টিকিট নিশ্চিত করল।</p>',
            'excerpt' => 'টি-২০ বিশ্বকাপ: দুর্দান্ত সেঞ্চুরি করে ভারতকে ঐতিহাসিক জয় এনে দিলেন শুভমান গিল।',
            'thumbnail_url' => 'https://picsum.photos/seed/sports2/400/250',
            'category_id' => $categories['sports']->id,
            'user_id' => $writer2->id,
            'status' => 'published',
            'view_count' => 6420,
            'published_at' => Carbon::now()->subHours(1)
        ]);
        $sports2->tags()->attach([$tags['cricket']->id]);

        $sports3 = Article::create([
            'title' => 'আইএসএল ডার্বি: ইস্টবেঙ্গলকে ২-১ গোলে হারিয়ে বড় জয় মোহনবাগানের',
            'slug' => 'isl-derby-mohun-bagan-beat-east-bengal',
            'content' => '<p>ফুটবল: যুবভারতী ক্রীড়াঙ্গনে রুদ্ধশ্বাস আইএসএল ডার্বি। ইস্টবেঙ্গলকে ২-১ ব্যবধানে পরাজিত করে লিগ তালিকার শীর্ষে উঠে এলো মোহনবাগান সুপার জায়ান্ট।</p>',
            'excerpt' => 'আইএসএল ডার্বি: ইস্টবেঙ্গলকে ২-১ গোলে হারিয়ে বড় জয় মোহনবাগানের।',
            'thumbnail_url' => 'https://picsum.photos/seed/sports3/400/250',
            'category_id' => $categories['sports']->id,
            'user_id' => $writer1->id,
            'status' => 'published',
            'view_count' => 8190,
            'published_at' => Carbon::now()->subHours(2)
        ]);
    }
}
