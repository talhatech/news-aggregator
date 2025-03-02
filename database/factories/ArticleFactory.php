<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Source;
use App\Models\Platform;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source_id' => Source::factory(),
            'platform_id' => Platform::factory(),
            'category_id' => Category::factory(),
            'author' => $this->faker->name(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'url' => $this->faker->unique()->url(),
            'image_url' => $this->faker->imageUrl(),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'content' => $this->faker->paragraphs(3, true),
            'external_id' => Str::random(20),
        ];
    }
}
