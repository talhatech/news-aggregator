# News Aggregator Backend

This is a Laravel-based news aggregator backend that fetches articles from various sources and provides API endpoints for a frontend application.

## Features

- Fetches news from multiple sources (NewsAPI, The Guardian, New York Times)
- Standardizes article format across different sources
- Provides RESTful API endpoints for article retrieval and filtering
- Automatically updates the news database through scheduled tasks
- Implements scalable architecture for easy addition of new news sources

## Diagrams
- See [Schema Diagram](schema-diagram.mermaid) for database structure.
- See [Architecture Diagram](architecture-diagram.mermaid) for system design.
- See [Directory Structure Diagram](directory-structure.mermaid) for codebase organization.

## Setup Instructions
### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or compatible database
- Laravel requirements

### Installation

1. Clone the repository:

   ```bash
   git clone git@github.com:talhatech/news-aggregator.git
   cd news-aggregator
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy the environment file and configure your database:

   ```bash
   cp .env.example .env
   ```

4. Edit the `.env` file to set up your database connection and add your API keys:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=news_aggregator
   DB_USERNAME=root
   DB_PASSWORD=

   NEWSAPI_KEY=your_newsapi_key_here
   GUARDIAN_API_KEY=your_guardian_api_key_here
   NYTIMES_API_KEY=your_nytimes_api_key_here
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Run the migrations:

   ```bash
   php artisan migrate --seed
   ```

7. Start the development server:

   ```bash
   php artisan serve
   ```

### Schedule Setup

To enable automatic news fetching, add the Laravel scheduler to your crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Alternatively, you can manually run the fetch commands:

```bash
# Fetch trending news
php artisan news:fetch trending

# Fetch yesterday's news
php artisan news:fetch yesterday
```

## Architecture

The application uses several design patterns to ensure flexibility and scalability:

- **Strategy Pattern**: Each news source implements a common interface.
- **Factory Pattern**: Creates news source instances.
- **Adapter Pattern**: Standardizes different API responses.
- **Repository Pattern**: For data access and filtering.
- **Command Pattern**: For scheduled tasks.

This architecture makes it easy to add new sources without modifying existing code.

## Adding a New News Source

To add a new news source:

    1. Create a new class in `app/Services/NewsAggregator` that extends `AbstractNewsSource`.
    2. Implement the required methods (`fetchTrending`, `fetchYesterday`, `mapToArticleModel`, `getSourceIdentifier`).
    3. Add the new source to the `NewsSourceFactory`.
    4. Add the API key configuration to `config/news_sources.php`.

## API Endpoints

| Endpoint | Description | Query Parameters |
|----------|-------------|------------------|
| `GET /api/news` | Get articles with filters | search, source, category, date_from, date_to, per_page |
| `GET /api/news/categories` | Get available categories | None |
| `GET /api/news/sources` | Get available sources | None |

## API Documentation

Interactive API documentation is available via Swagger UI at:

```
http://localhost:8000/api/documentation
```

The Swagger documentation provides:
- Detailed parameter information
- Response schemas
- Interactive testing interface
- Example requests and responses

## Testing

Run the tests with:

```bash
php artisan test
```

See [TESTING.md](TESTING.md) for more information on the test suite.
