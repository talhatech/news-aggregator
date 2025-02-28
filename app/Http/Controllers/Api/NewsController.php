<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewsAggregator\NewsService;
use App\Repositories\ArticleRepository;

class NewsController extends Controller
{
    protected $newsService;
    protected $articleRepository;

    public function __construct(NewsService $newsService, ArticleRepository $articleRepository)
    {
        $this->newsService = $newsService;
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get articles with filters
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'source',
            'category',
            'date_from',
            'date_to',
        ]);

        $perPage = $request->input('per_page', 15);

        $articles = $this->articleRepository->getArticles($filters, $perPage);

        return response()->json([
            'data' => $articles->items(),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'from' => $articles->firstItem(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'to' => $articles->lastItem(),
                'total' => $articles->total(),
            ]
        ]);
    }

    /**
     * Get available categories
     */
    public function categories()
    {
        return response()->json([
            'data' => $this->articleRepository->getCategories()
        ]);
    }

    /**
     * Get available sources
     */
    public function sources()
    {
        return response()->json([
            'data' => $this->articleRepository->getSources()
        ]);
    }

    /**
     * Get trending news (for immediate fetching)
     */
    public function trending(Request $request)
    {
        $sources = $request->input('sources', []);
        $articles = $this->newsService->getTrendingNews($sources);

        return response()->json([
            'data' => $articles
        ]);
    }

    /**
     * Get yesterday's news (for immediate fetching)
     */
    public function yesterday(Request $request)
    {
        $sources = $request->input('sources', []);
        $articles = $this->newsService->getYesterdayNews($sources);

        return response()->json([
            'data' => $articles
        ]);
    }
}
