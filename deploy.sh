#!/bin/bash

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build frontend assets
npm run build

# Generate application key if not already set
if [ -z "$APP_KEY" ]; then
  echo "Generating application key..."
  php artisan key:generate --force
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Seed the database with sample data
echo "Seeding database with sample data..."
php artisan db:seed --force

# Clear caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "Deployment completed!"