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

# Clear and rebuild caches
echo "Optimizing caches for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed!"