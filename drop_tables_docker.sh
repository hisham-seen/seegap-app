#!/bin/bash

echo "Dropping and reinstalling tables using Docker..."

# Get the MySQL container ID
MYSQL_CONTAINER=$(docker ps -qf "name=mysql")

if [ -z "$MYSQL_CONTAINER" ]; then
    echo "Error: MySQL container not found. Make sure the Docker container is running."
    exit 1
fi

echo "Found MySQL container: $MYSQL_CONTAINER"

# Step 1: Drop existing tables
echo "Step 1: Dropping existing tables..."
SQL_CONTENT=$(cat drop_tables.sql)

if [ -z "$SQL_CONTENT" ]; then
    echo "Error: Could not read drop_tables.sql file."
    exit 1
fi

echo "Read drop_tables.sql file successfully."
echo "Executing drop tables SQL commands in the MySQL container..."
echo "$SQL_CONTENT" | docker exec -i $MYSQL_CONTAINER mysql -u seegap_user -pseegap_password seegap_db

echo "Tables dropped successfully."

# Step 2: Install tables with updated schema
echo "Step 2: Installing tables with updated schema..."
INSTALL_SQL_FILE="install/dump.sql"

if [ ! -f "$INSTALL_SQL_FILE" ]; then
    echo "Error: $INSTALL_SQL_FILE not found."
    exit 1
fi

echo "Found $INSTALL_SQL_FILE file."
echo "Executing install tables SQL commands in the MySQL container..."
cat "$INSTALL_SQL_FILE" | docker exec -i $MYSQL_CONTAINER mysql -u seegap_user -pseegap_password seegap_db

echo "Tables installed successfully with updated schema."
echo "Database reset complete. The admin panel should now work correctly."
