#!/bin/bash

# News Aggregator Setup Script

# Colors for better UI
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}Setting up News Aggregator Backend...${NC}"

# Step 1: Install composer dependencies
echo -e "${GREEN}Installing Composer dependencies...${NC}"
composer install

# Step 2: Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo -e "${GREEN}Creating .env file...${NC}"
    cp .env.example .env

    # Prompt for API keys
    echo -e "${BLUE}Please enter your NewsAPI key:${NC}"
    read newsapi_key
    echo -e "${BLUE}Please enter your Guardian API key:${NC}"
    read guardian_key
    echo -e "${BLUE}Please enter your New York Times API key:${NC}"
    read nytimes_key

    # Update the .env file
    sed -i "s/NEWSAPI_KEY=.*/NEWSAPI_KEY=$newsapi_key/" .env
    sed -i "s/GUARDIAN_API_KEY=.*/GUARDIAN_API_KEY=$guardian_key/" .env
    sed -i "s/NYTIMES_API_KEY=.*/NYTIMES_API_KEY=$nytimes_key/" .env
fi

# Step 3: Generate application key
echo -e "${GREEN}Generating application key...${NC}"
php artisan key:generate

# Step 4: Run migrations
echo -e "${GREEN}Running database migrations...${NC}"
php artisan migrate

# Step 5: Set up scheduling
echo -e "${GREEN}Setting up the scheduler...${NC}"
echo -e "${BLUE}To enable automatic news fetching, add this to your crontab:${NC}"
echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"

# Step 6: Fetch initial news data
echo -e "${GREEN}Fetching initial news data...${NC}"
php artisan news:fetch trending
php artisan news:fetch yesterday

echo -e "${BLUE}Setup complete! You can now run 'php artisan serve' to start the development server.${NC}"
