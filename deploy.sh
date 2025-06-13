#!/bin/bash

# Simple deployment script for CI/CD
# This script can be used with GitHub Actions or other CI/CD systems

set -e

echo "🚀 Starting deployment..."

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "📋 Copying .env.example to .env"
    cp .env.example .env
fi

# Set proper permissions
echo "🔐 Setting file permissions..."
chmod 666 config.php
chmod -R 755 uploads/
chmod 777 install/

# Remove install/installed file if it exists (for fresh installations)
if [ -f install/installed ]; then
    echo "🗑️ Removing install/installed file"
    rm -f install/installed
fi

# Clear application cache
echo "🧹 Clearing application cache..."
if [ -f clear_all_cache.php ]; then
    php clear_all_cache.php
fi

# Restart Docker containers if docker-compose is available
if command -v docker-compose &> /dev/null; then
    echo "🐳 Restarting Docker containers..."
    docker-compose down
    docker-compose up -d
    
    # Wait for containers to be ready
    echo "⏳ Waiting for containers to be ready..."
    sleep 30
    
    # Clear cache again after restart
    docker-compose exec php php clear_all_cache.php
fi

echo "✅ Deployment completed successfully!"
echo "🌐 Application should be available at: https://si.seegap.com"
