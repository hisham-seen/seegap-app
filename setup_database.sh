#!/bin/bash

# Setup script for form_submissions table
# This script will create the form_submissions table in your database

echo "🚀 Setting up form_submissions table..."

# Check if config.php exists to get database credentials
if [ ! -f "config.php" ]; then
    echo "❌ config.php not found. Please make sure you're in the SeeGap root directory."
    exit 1
fi

# Extract database credentials from config.php
DB_HOST=$(grep "define('DB_HOST'" config.php | cut -d"'" -f4)
DB_NAME=$(grep "define('DB_NAME'" config.php | cut -d"'" -f4)
DB_USERNAME=$(grep "define('DB_USERNAME'" config.php | cut -d"'" -f4)
DB_PASSWORD=$(grep "define('DB_PASSWORD'" config.php | cut -d"'" -f4)

echo "📊 Database: $DB_NAME on $DB_HOST"
echo "👤 Username: $DB_USERNAME"

# Check if mysql command is available
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL client not found. Please install MySQL client."
    exit 1
fi

# Run the SQL setup script
echo "🔧 Creating form_submissions table..."

if [ -z "$DB_PASSWORD" ]; then
    # No password
    mysql -h "$DB_HOST" -u "$DB_USERNAME" "$DB_NAME" < setup_form_submissions_table.sql
else
    # With password
    mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_NAME" < setup_form_submissions_table.sql
fi

if [ $? -eq 0 ]; then
    echo "✅ Database setup completed successfully!"
    echo ""
    echo "📋 Next steps:"
    echo "1. Visit http://localhost:8080/test_form_submissions.php to verify the setup"
    echo "2. Test form submissions at http://localhost:8080/ixrew"
    echo "3. Check the logs at uploads/logs/ for any errors"
    echo ""
    echo "🎉 Form submissions are now ready to use!"
else
    echo "❌ Database setup failed. Please check your database credentials and try again."
    echo ""
    echo "💡 Manual setup:"
    echo "mysql -u $DB_USERNAME -p $DB_NAME < setup_form_submissions_table.sql"
fi
