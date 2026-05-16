<?php

namespace App\Console\Commands;

use App\Http\Controllers\NewsController;
use Illuminate\Console\Command;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'news:fetch
                            {--category= : Specific category to fetch (e.g. politics, technology)}
                            {--language= : Language code (e.g. en, hi, ta)}
                            {--query= : Search keyword to fetch}';

    /**
     * The console command description.
     */
    protected $description = 'Fetch latest news from NewsData.io API and insert into database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Fetching news from NewsData.io...');

        $category = $this->option('category');
        $language = $this->option('language');
        $query = $this->option('query');

        if ($category || $language || $query) {
            // Specific fetch
            $controller = new NewsController();
            $imported = $controller->fetchAndInsertFromApi($query, $category, $language ?? 'en');
            $this->info("✅ Imported {$imported} articles.");
        } else {
            // Full scheduled fetch across categories and languages
            $imported = NewsController::scheduledFetch();
            $this->info("✅ Scheduled fetch complete. Total imported: {$imported} articles.");
        }

        return Command::SUCCESS;
    }
}
