<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregator\NewsService;
use App\Enums\NewsType;

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

    public function handle():void
    {
        $typeStr = $this->argument('type');

        $type = match($typeStr) {
            'trending' => NewsType::TRENDING,
            'yesterday' => NewsType::YESTERDAY,
            default => null
        };

        if (!$type) {
            $this->error("Invalid news type: {$typeStr}");
            $this->info("Available types: trending, yesterday");
            return;
        }

        $this->info("Fetching {$type->label()}...");

        $this->newsService->fetchAndSaveNews($type);

        $this->info('Done!');
        return;
    }
}
