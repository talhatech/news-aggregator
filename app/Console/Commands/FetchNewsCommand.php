<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregator\NewsService;

class FetchNewsCommand extends Command
{
    protected $signature = 'news:fetch {type=trending}';
    protected $description = 'Fetch news from all sources';

    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        parent::__construct();
        $this->newsService = $newsService;
    }

    public function handle()
    {
        $type = $this->argument('type');
        $this->info("Fetching {$type} news...");

        $this->newsService->fetchAndSaveNews($type);

        $this->info('Done!');
    }
}
