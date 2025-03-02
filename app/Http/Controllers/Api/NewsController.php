<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepository;
use App\Http\Resources\API\SourceResource;
use App\Http\Requests\ArticleFilterRequest;
use App\Http\Resources\API\ArticleResource;
use App\Http\Resources\API\CategoryResource;

/**
 * @OA\Info(
 *     title="News Aggregator API",
 *     version="1.0.0",
 *     description="API for retrieving news articles from various sources",
 *     @OA\Contact(
 *         email="support@example.com",
 *         name="Support Team"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */
class NewsController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    /**
     * Get articles with filters
     *
     * @OA\Get(
     *     path="/api/news",
     *     operationId="getArticles",
     *     tags={"Articles"},
     *     summary="Get a list of articles with optional filters",
     *     description="Returns a paginated list of articles that can be filtered by search term, source, category, and date range",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to filter articles (max: 100 characters)",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=100, example="Technology")
     *     ),
     *     @OA\Parameter(
     *         name="source_ids[]",
     *         in="query",
     *         description="Filter articles by source IDs (UUID format)",
     *         required=false,
     *         style="form",
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string", format="uuid"),
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="category_ids[]",
     *         in="query",
     *         description="Filter articles by category IDs (UUID format)",
     *         required=false,
     *         style="form",
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string", format="uuid"),
     *         )
     *     ),

     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter articles published after this date (format: YYYY-MM-DD HH:mm:ss)",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time", example="2017-11-21 20:59:01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter articles published before this date (format: YYYY-MM-DD HH:mm:ss, must be after or equal to date_from)",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time", example="2025-03-09 23:59:59")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="string", example="022a7a2b-2c92-4fef-92c1-efc1e9b21fb5"),
     *                     @OA\Property(
     *                         property="source",
     *                         type="object",
     *                         @OA\Property(property="id", type="string", example="9e563059-80d9-40ae-8107-4c07cea47458"),
     *                         @OA\Property(property="name", type="string", example="CNN")
     *                     ),
     *                     @OA\Property(
     *                         property="platform",
     *                         type="object",
     *                         @OA\Property(property="id", type="string", example="9e56305a-23f5-445b-bba1-34d6a893d8fc"),
     *                         @OA\Property(property="name", type="string", example="Web")
     *                     ),
     *                     @OA\Property(property="author", type="string", nullable=true, example="John Doe"),
     *                     @OA\Property(property="title", type="string", example="Breaking News: Something Happened"),
     *                     @OA\Property(property="description", type="string", nullable=true, example="A detailed description of the event."),
     *                     @OA\Property(property="url", type="string", example="https://example.com/article"),
     *                     @OA\Property(property="image_url", type="string", nullable=true, example="https://example.com/image.jpg"),
     *                     @OA\Property(property="published_at", type="string", format="date-time", example="2024-02-27T14:30:00Z"),
     *                     @OA\Property(
     *                         property="category",
     *                         type="object",
     *                         nullable=true,
     *                         @OA\Property(property="id", type="string", example="9e56305a-4d17-4830-a940-249876eb42fb"),
     *                         @OA\Property(property="name", type="string", example="Technology")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=150)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function index(ArticleFilterRequest $request)
    {
        $filters = $request->validated();
        $articlesQuery = $this->articleRepository->getArticles($filters);
        return ArticleResource::collection($this->getPaginate($request, $articlesQuery));
    }

    /**
     * Get available categories
     *
     * @OA\Get(
     *     path="/api/news/categories",
     *     operationId="getCategories",
     *     tags={"Categories"},
     *     summary="Get a list of all available categories",
     *     description="Returns a list of all news categories available in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="string", example="022a7a2b-2c92-4fef-92c1-efc1e9b21fb5"),
     *                     @OA\Property(property="name", type="string", example="Politics")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function categories(Request $request)
    {
        $categoriesQuery = $this->articleRepository->getCategories();
        return CategoryResource::collection($this->getPaginate($request, $categoriesQuery));
    }

    /**
     * Get available sources
     *
     * @OA\Get(
     *     path="/api/news/sources",
     *     operationId="getSources",
     *     tags={"Sources"},
     *     summary="Get a list of all available news sources",
     *     description="Returns a list of all news sources available in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="string", example="022a7a2b-2c92-4fef-92c1-efc1e9b21fb6"),
     *                     @OA\Property(property="name", type="string", example="BBC News")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function sources(Request $request)
    {
        $sourcesQuery = $this->articleRepository->getSources();
        return SourceResource::collection($this->getPaginate($request, $sourcesQuery));
    }
}
