#!/bin/bash

# Initialize custom Superset setup

echo "Initializing custom Superset setup..."

# Build the custom Superset image
echo "Building custom Superset image..."
docker-compose build

# Start the services
echo "Starting services..."
docker-compose up -d

# Wait for the database to be ready
echo "Waiting for database to be ready..."
sleep 10

# Initialize the database
echo "Initializing the database..."
docker-compose exec superviz superset db upgrade

# Create an admin user
echo "Creating admin user..."
docker-compose exec superviz superset fab create-admin \
    --username admin \
    --firstname Admin \
    --lastname User \
    --email admin@example.com \
    --password admin

# Initialize Superset
echo "Initializing Superset..."
docker-compose exec superviz superset init

echo "Custom Superset setup complete!"
echo "Access Superset at http://localhost:8088"
echo "Username: admin"
echo "Password: admin"
