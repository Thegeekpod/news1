<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Bengali translations for OpenWeatherMap weather condition descriptions.
     */
    private static array $bengaliConditions = [
        // Thunderstorm
        'thunderstorm with light rain' => 'হালকা বৃষ্টি সহ বজ্রপাত',
        'thunderstorm with rain' => 'বৃষ্টি সহ বজ্রপাত',
        'thunderstorm with heavy rain' => 'ভারী বৃষ্টি সহ বজ্রপাত',
        'light thunderstorm' => 'হালকা বজ্রপাত',
        'thunderstorm' => 'বজ্রপাত',
        'heavy thunderstorm' => 'ভারী বজ্রপাত',
        'ragged thunderstorm' => 'তীব্র বজ্রপাত',
        'thunderstorm with light drizzle' => 'হালকা গুঁড়ি বৃষ্টি সহ বজ্রপাত',
        'thunderstorm with drizzle' => 'গুঁড়ি বৃষ্টি সহ বজ্রপাত',
        'thunderstorm with heavy drizzle' => 'ভারী গুঁড়ি বৃষ্টি সহ বজ্রপাত',
        // Drizzle
        'light intensity drizzle' => 'হালকা গুঁড়ি বৃষ্টি',
        'drizzle' => 'গুঁড়ি বৃষ্টি',
        'heavy intensity drizzle' => 'ভারী গুঁড়ি বৃষ্টি',
        'light intensity drizzle rain' => 'হালকা গুঁড়ি বৃষ্টিপাত',
        'drizzle rain' => 'গুঁড়ি বৃষ্টিপাত',
        'heavy intensity drizzle rain' => 'ভারী গুঁড়ি বৃষ্টিপাত',
        'shower rain and drizzle' => 'ঝরনা বৃষ্টি ও গুঁড়ি',
        'heavy shower rain and drizzle' => 'ভারী ঝরনা বৃষ্টি ও গুঁড়ি',
        'shower drizzle' => 'ঝরনা গুঁড়ি বৃষ্টি',
        // Rain
        'light rain' => 'হালকা বৃষ্টি',
        'moderate rain' => 'মাঝারি বৃষ্টি',
        'heavy intensity rain' => 'ভারী বৃষ্টি',
        'very heavy rain' => 'অতিভারী বৃষ্টি',
        'extreme rain' => 'অতিরিক্ত বৃষ্টি',
        'freezing rain' => 'শীতল বৃষ্টি',
        'light intensity shower rain' => 'হালকা ঝরনা বৃষ্টি',
        'shower rain' => 'ঝরনা বৃষ্টি',
        'heavy intensity shower rain' => 'ভারী ঝরনা বৃষ্টি',
        'ragged shower rain' => 'তীব্র ঝরনা বৃষ্টি',
        // Snow
        'light snow' => 'হালকা তুষারপাত',
        'snow' => 'তুষারপাত',
        'heavy snow' => 'ভারী তুষারপাত',
        'sleet' => 'শিলাবৃষ্টি মিশ্রিত বৃষ্টি',
        'light shower sleet' => 'হালকা শিলাবৃষ্টি',
        'shower sleet' => 'শিলাবৃষ্টি',
        'light rain and snow' => 'হালকা বৃষ্টি ও তুষার',
        'rain and snow' => 'বৃষ্টি ও তুষার',
        'light shower snow' => 'হালকা তুষার ঝরনা',
        'shower snow' => 'তুষার ঝরনা',
        'heavy shower snow' => 'ভারী তুষার ঝরনা',
        // Atmosphere
        'mist' => 'কুয়াশা',
        'smoke' => 'ধোঁয়া',
        'haze' => 'ধুঁয়াশা',
        'sand/dust whirls' => 'বালি/ধুলা ঘূর্ণি',
        'fog' => 'ঘন কুয়াশা',
        'sand' => 'বালুঝড়',
        'dust' => 'ধুলিঝড়',
        'volcanic ash' => 'আগ্নেয় ছাই',
        'squalls' => 'ঝড়ো হাওয়া',
        'tornado' => 'টর্নেডো',
        // Clear
        'clear sky' => 'পরিষ্কার আকাশ',
        // Clouds
        'few clouds' => 'কিছু মেঘ',
        'few clouds: 11-25%' => 'সামান্য মেঘ',
        'scattered clouds' => 'আংশিক মেঘলা',
        'scattered clouds: 25-50%' => 'আংশিক মেঘলা',
        'broken clouds' => 'মেঘাচ্ছন্ন',
        'broken clouds: 51-84%' => 'মেঘাচ্ছন্ন',
        'overcast clouds' => 'ঘন মেঘলা',
        'overcast clouds: 85-100%' => 'ঘন মেঘলা',
    ];

    private static array $stateTranslations = [
        'West Bengal' => 'পশ্চিমবঙ্গ',
        'Tripura' => 'ত্রিপুরা',
        'Assam' => 'অসম',
        'Jharkhand' => 'ঝাড়খণ্ড',
        'Bihar' => 'বিহার',
        'Odisha' => 'ওড়িশা',
        'Delhi' => 'দিল্লি',
        'Maharashtra' => 'মহারাষ্ট্র',
        'Karnataka' => 'কর্ণাটক',
        'Tamil Nadu' => 'তামিলনাড়ু',
        'Rangpur Division' => 'রংপুর বিভাগ',
        'Rajshahi Division' => 'রাজশাহী বিভাগ',
        'Dhaka Division' => 'ঢাকা বিভাগ',
        'Chittagong Division' => 'চট্টগ্রাম বিভাগ',
        'Sylhet Division' => 'সিলেট বিভাগ',
        'Khulna Division' => 'খুলনা বিভাগ',
        'Barisal Division' => 'বরিশাল বিভাগ',
        'Mymensingh Division' => 'ময়মনসিংহ বিভাগ',
    ];

    private static function translateToBengali(string $desc): string
    {
        $lower = strtolower(trim($desc));
        return self::$bengaliConditions[$lower] ?? $desc;
    }

    private static function geocodeLocation(?string $location, string $apiKey): ?array
    {
        $searchQuery = trim($location ?: 'Durgapur');

        $geoResponse = \Illuminate\Support\Facades\Http::timeout(5)->get('http://api.openweathermap.org/geo/1.0/direct', [
            'q' => $searchQuery,
            'limit' => 5,
            'appid' => $apiKey
        ]);

        if (!$geoResponse->successful() || empty($geoResponse->json())) {
            return null;
        }

        $geoData = $geoResponse->json();
        $selectedGeo = null;

        $queryLower = mb_strtolower($searchQuery);
        $wantsBD = str_contains($queryLower, 'bd') || str_contains($queryLower, 'bangladesh') || str_contains($queryLower, 'বাংলাদেশ');

        if (!$wantsBD) {
            foreach ($geoData as $item) {
                if (($item['country'] ?? '') === 'IN' && ($item['state'] ?? '') === 'West Bengal') {
                    $selectedGeo = $item;
                    break;
                }
            }
            if (!$selectedGeo) {
                foreach ($geoData as $item) {
                    if (($item['country'] ?? '') === 'IN') {
                        $selectedGeo = $item;
                        break;
                    }
                }
            }
        }

        if (!$selectedGeo) {
            $selectedGeo = $geoData[0];
        }

        $cityName = $selectedGeo['local_names']['bn'] ?? $selectedGeo['name'];
        if (($selectedGeo['name'] ?? '') === 'Durgapur' && ($selectedGeo['state'] ?? '') === 'West Bengal' && empty($selectedGeo['local_names']['bn'])) {
            $cityName = 'দুর্গাপুর';
        }

        $stateRaw = $selectedGeo['state'] ?? '';
        $stateName = self::$stateTranslations[$stateRaw] ?? $stateRaw;
        $locationName = $cityName . ($stateName ? ', ' . $stateName : '');

        return [
            'lat' => $selectedGeo['lat'],
            'lon' => $selectedGeo['lon'],
            'location_name' => $locationName
        ];
    }

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Exclude csrf token
        $data = $request->except('_token');

        // Handle checkbox fields not sent in request when unchecked
        if (!$request->has('weather_auto_fetch')) {
            $data['weather_auto_fetch'] = '0';
        }
        if (!$request->has('hide_view_count')) {
            $data['hide_view_count'] = '0';
        }
        if (!$request->has('site_live_mode')) {
            $data['site_live_mode'] = '0';
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Update currently logged in admin user's name if default_author_name is provided
        if ($request->has('default_author_name') && !empty($request->input('default_author_name'))) {
            if (auth()->check()) {
                auth()->user()->update(['name' => $request->input('default_author_name')]);
            }
        }

        // If auto-fetch is enabled, trigger immediate fetch
        if (($data['weather_auto_fetch'] ?? '0') === '1' && !empty($data['weather_api_key'])) {
            $fetched = self::fetchWeather($data['weather_api_key'], $data['weather_location'] ?? 'Durgapur');
            if (!$fetched) {
                return redirect()->route('admin.settings')->with('error', 'Settings saved, but Weather API fetch failed. Please check your API key and location.');
            }
        }

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    public function testApi(Request $request)
    {
        $apiKey = $request->input('api_key');
        $location = $request->input('location') ?: 'Durgapur';

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API Key is required.'
            ]);
        }

        try {
            // 1. Geocode location to coordinates
            $geo = self::geocodeLocation($location, $apiKey);
            if (!$geo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location "' . $location . '" not found. Please try a different city name.'
                ]);
            }

            $lat = $geo['lat'];
            $lon = $geo['lon'];
            $locationName = $geo['location_name'];

            // 2. Fetch current weather (free tier - data/2.5/weather)
            $weatherResponse = \Illuminate\Support\Facades\Http::timeout(8)->get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'bn'
            ]);

            if (!$weatherResponse->successful()) {
                $err = $weatherResponse->json();
                $message = $err['message'] ?? 'Weather API failed with HTTP ' . $weatherResponse->status();
                return response()->json([
                    'success' => false,
                    'message' => 'Weather API Error: ' . $message
                ]);
            }

            $weather = $weatherResponse->json();
            $temp = round($weather['main']['temp'] ?? 0);
            $desc = self::translateToBengali($weather['weather'][0]['description'] ?? '');
            $humidity = ($weather['main']['humidity'] ?? 0) . '%';
            $windSpeed = $weather['wind']['speed'] ?? 0;
            $wind = round($windSpeed * 3.6) . ' km/h';

            // 3. Fetch 5-day forecast to get today's high/low (free tier - data/2.5/forecast)
            $high = '--';
            $low = '--';
            $forecastResponse = \Illuminate\Support\Facades\Http::timeout(8)->get('https://api.openweathermap.org/data/2.5/forecast', [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $apiKey,
                'units' => 'metric',
                'cnt' => 8  // 8 x 3hr = 24hrs
            ]);
            if ($forecastResponse->successful()) {
                $forecasts = $forecastResponse->json()['list'] ?? [];
                $todayDate = date('Y-m-d');
                $todayTemps = array_filter($forecasts, fn($f) => date('Y-m-d', $f['dt']) === $todayDate);
                if (!empty($todayTemps)) {
                    $maxTemp = max(array_column(array_column($todayTemps, 'main'), 'temp_max'));
                    $minTemp = min(array_column(array_column($todayTemps, 'main'), 'temp_min'));
                    $high = round($maxTemp) . '°C';
                    $low = round($minTemp) . '°C';
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Weather API connection verified successfully! (' . $locationName . ')',
                'data' => [
                    'location' => $locationName,
                    'temp' => $temp,
                    'desc' => $desc,
                    'humidity' => $humidity,
                    'wind' => $wind,
                    'high' => $high,
                    'low' => $low
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection Error: ' . $e->getMessage()
            ]);
        }
    }

    public static function fetchWeather($apiKey, $location)
    {
        try {
            // 1. Geocode location to coordinates
            $geo = self::geocodeLocation($location, $apiKey);

            if ($geo) {
                $lat = $geo['lat'];
                $lon = $geo['lon'];
                $locationName = $geo['location_name'];

                // 2. Fetch current weather (free tier - data/2.5/weather)
                $weatherResponse = \Illuminate\Support\Facades\Http::timeout(8)->get('https://api.openweathermap.org/data/2.5/weather', [
                    'lat' => $lat,
                    'lon' => $lon,
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang' => 'bn'
                ]);

                if ($weatherResponse->successful()) {
                    $weather = $weatherResponse->json();
                    $temp = round($weather['main']['temp'] ?? 0);
                    $desc = self::translateToBengali($weather['weather'][0]['description'] ?? '');
                    $humidity = ($weather['main']['humidity'] ?? 0) . '%';
                    $windSpeed = $weather['wind']['speed'] ?? 0;
                    $wind = round($windSpeed * 3.6) . ' km/h';

                    // 3. Fetch 5-day forecast for today's high/low (free tier - data/2.5/forecast)
                    $high = '--';
                    $low = '--';
                    $forecastResponse = \Illuminate\Support\Facades\Http::timeout(8)->get('https://api.openweathermap.org/data/2.5/forecast', [
                        'lat' => $lat,
                        'lon' => $lon,
                        'appid' => $apiKey,
                        'units' => 'metric',
                        'cnt' => 8
                    ]);
                    if ($forecastResponse->successful()) {
                        $forecasts = $forecastResponse->json()['list'] ?? [];
                        $todayDate = date('Y-m-d');
                        $todayTemps = array_filter($forecasts, fn($f) => date('Y-m-d', $f['dt']) === $todayDate);
                        if (!empty($todayTemps)) {
                            $maxTemp = max(array_column(array_column($todayTemps, 'main'), 'temp_max'));
                            $minTemp = min(array_column(array_column($todayTemps, 'main'), 'temp_min'));
                            $high = round($maxTemp) . '°C';
                            $low = round($minTemp) . '°C';
                        }
                    }

                    $updates = [
                        'weather_location' => $locationName,
                        'weather_temp' => $temp,
                        'weather_desc' => $desc,
                        'weather_humidity' => $humidity,
                        'weather_wind' => $wind,
                        'weather_high' => $high,
                        'weather_low' => $low
                    ];

                    foreach ($updates as $key => $val) {
                        Setting::updateOrCreate(
                            ['key' => $key],
                            ['value' => $val]
                        );
                    }

                    // Cache success flag for 30 minutes
                    \Illuminate\Support\Facades\Cache::put('weather_last_fetched', true, 1800);

                    return true;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("OpenWeatherMap fetch error: " . $e->getMessage());
        }
        return false;
    }

    /**
     * Manually trigger live Gold, Silver & Sensex Market rates update
     */
    public function refreshMarketRates()
    {
        try {
            \App\Http\Controllers\MarketController::fetchAndStoreRates(true);
            return redirect()->route('admin.settings')->with('success', 'সোনা, রূপো ও সেনসেক্স বাজার দর সফলভাবে আপডেট করা হয়েছে!');
        } catch (\Throwable $e) {
            return redirect()->route('admin.settings')->with('error', 'মার্কেট দর আপডেট করতে ব্যর্থ: ' . $e->getMessage());
        }
    }
}
