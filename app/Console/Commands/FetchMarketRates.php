<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MarketController;

class FetchMarketRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:fetch {--force : Force refresh bypassing any cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store daily market rates for Gold, Silver, Sensex, and Nifty';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching daily market rates (Gold, Silver, Sensex, Nifty)...');

        try {
            $data = MarketController::fetchAndStoreRates($this->option('force'));

            $this->info('Market rates updated successfully!');
            $this->table(
                ['Asset', 'Current Rate / Points', 'Change'],
                [
                    ['Gold 24K (10g)', '₹' . number_format($data['gold_24k']), ($data['gold_change'] >= 0 ? '+' : '') . '₹' . number_format($data['gold_change'])],
                    ['Gold 22K (10g)', '₹' . number_format($data['gold_22k']), ($data['gold_change'] >= 0 ? '+' : '') . '₹' . number_format(round($data['gold_change'] * 22 / 24))],
                    ['Silver (1 Kg)', '₹' . number_format($data['silver_1kg']), ($data['silver_change'] >= 0 ? '+' : '') . '₹' . number_format($data['silver_change'])],
                    ['BSE Sensex', number_format($data['sensex']['price'], 2), ($data['sensex']['is_positive'] ? '+' : '') . $data['sensex']['change_percent'] . '%'],
                    ['NSE Nifty 50', number_format($data['nifty']['price'], 2), ($data['nifty']['is_positive'] ? '+' : '') . $data['nifty']['change_percent'] . '%'],
                ]
            );

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to fetch market rates: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
