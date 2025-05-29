#!/bin/bash

echo "Dropping ALL tables using Docker..."

# Get the MySQL container ID
MYSQL_CONTAINER=$(docker ps -qf "name=mysql")

if [ -z "$MYSQL_CONTAINER" ]; then
    echo "Error: MySQL container not found. Make sure the Docker container is running."
    exit 1
fi

echo "Found MySQL container: $MYSQL_CONTAINER"

# Drop existing tables
echo "Dropping ALL existing tables..."
SQL_CONTENT=$(cat drop_all_tables.sql)

if [ -z "$SQL_CONTENT" ]; then
    echo "Error: Could not read drop_all_tables.sql file."
    exit 1
fi

echo "Read drop_all_tables.sql file successfully."
echo "Executing drop ALL tables SQL commands in the MySQL container..."
echo "$SQL_CONTENT" | docker exec -i $MYSQL_CONTAINER mysql -u seegap_user -pseegap_password seegap_db

echo "ALL tables dropped successfully."
