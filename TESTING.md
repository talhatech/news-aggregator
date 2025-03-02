# Testing Guide for News Aggregator

This document provides information about the test suite and how to run tests for the News Aggregator application.

## Test Environment Setup

### Prerequisites

1. Make sure your development environment is properly set up
2. Install Pest PHP testing framework if not already installed:
   ```bash
   composer require pestphp/pest --dev
   composer require pestphp/pest-plugin-laravel --dev
   ```

### Configure Testing Database

For best results, configure your tests to use a dedicated testing database or SQLite in-memory database. 

#### Option 1: SQLite In-Memory Database

In your `phpunit.xml` file:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

#### Option 2: Dedicated MySQL Testing Database

1. Create a dedicated testing database:
   ```sql
   CREATE DATABASE news_aggregator_testing;
   ```

2. Update your `phpunit.xml`:
   ```xml
   <env name="DB_CONNECTION" value="mysql"/>
   <env name="DB_DATABASE" value="news_aggregator_testing"/>
   ```

## Running Tests

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suites

```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Run Specific Test Files

```bash
php artisan test tests/Feature/API/NewsApiTest.php
```

### Run Tests with Details

```bash
php artisan test --verbose
```

## Test Structure

### API Tests

The application includes comprehensive API tests in `tests/Feature/API/NewsApiTest.php` that cover:

1. Retrieving articles with and without pagination
2. Filtering articles by search terms
3. Filtering articles by date range
4. Accessing categories and sources
5. Validating request inputs

### Test Data Setup

Tests use the `RefreshDatabase` trait to ensure a clean database state for each test. Test data is generated dynamically with unique identifiers to avoid constraint violations.

## Writing New Tests

### Guidelines for New Tests

1. Use descriptive test names that explain what is being tested
2. Use `RefreshDatabase` trait to ensure tests start with a clean database
3. Generate unique data for each test run to avoid collisions
4. Use random strings for identifiers to avoid unique constraint violations
5. Keep identifier lengths under validation limits (e.g., 30 characters)
6. Test both happy paths and edge cases/validation errors

### Example Test Structure

```php
test('feature being tested', function () {
    // 1. Setup - Create test data or prerequisites
    $testData = SomeModel::create([...]);
    
    // 2. Action - Make API request or call method
    $response = getJson('/api/endpoint');
    
    // 3. Assertions - Verify the results
    $response->assertStatus(200);
    $response->assertJsonStructure([...]);
});
```

## Test Coverage

To generate a test coverage report (requires Xdebug):

```bash
XDEBUG_MODE=coverage php artisan test --coverage
```

For a detailed HTML coverage report:

```bash
XDEBUG_MODE=coverage php artisan test --coverage-html coverage
```

Then view the report by opening `coverage/index.html` in your browser.

## Continuous Integration

The test suite is designed to run in CI environments. Configure your CI system to:

1. Install dependencies: `composer install --no-interaction --prefer-dist`
2. Run migrations: `php artisan migrate --env=testing`
3. Run tests: `php artisan test`

## Troubleshooting Common Issues

### Database Constraint Violations

If you see unique constraint violations during tests:
- Make sure you're using the `RefreshDatabase` trait
- Generate unique identifiers with random suffixes
- Keep identifiers under validation limits

### Validation Errors in Tests

If tests fail with validation errors:
- Make sure your test parameters match validation rules
- Check for max length constraints
- Ensure date formats match expected format

### Assertion Failures

If assertions fail:
- Check if your default pagination settings match test expectations
- Verify JSON structure matches actual response structure
- Make sure test data is being created correctly

### Swagger Documentation Errors

When running Swagger generation:
- Ensure all array properties have `@OA\Items` annotations
- Keep examples within the validation rules defined in your requests

## Mock Options for External Services

For testing components that interact with external services, consider:

1. Using Laravel's HTTP faking capabilities
2. Creating mock implementations of your news source interfaces
3. Setting up fake API responses that match real-world data structures

```php
// Example of HTTP faking
Http::fake([
    'https://newsapi.org/v2/*' => Http::response(['status' => 'ok', 'articles' => [
        // fake article data
    ]], 200)
]);
```
