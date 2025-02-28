<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsAggregator\NewsService;
use App\Repositories\ArticleRepository;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsService::class, function ($app) {
            return new NewsService();
        });

        $this->app->singleton(ArticleRepository::class, function ($app) {
            return new ArticleRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
