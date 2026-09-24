<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Daily Routine Schedule for Market Rates (Gold, Silver, Sensex, Nifty)
 * Runs automatically every morning at 09:30 AM (Market Open) and 04:45 PM (Market Close) IST
 */
Schedule::command('market:fetch')
    ->dailyAt('09:30')
    ->timezone('Asia/Kolkata')
    ->description('Auto-fetch daily Gold, Silver, and Sensex rates at morning market open');

Schedule::command('market:fetch')
    ->dailyAt('16:45')
    ->timezone('Asia/Kolkata')
    ->description('Auto-fetch daily Gold, Silver, and Sensex rates at market close');
