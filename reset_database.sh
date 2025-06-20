#!/bin/bash

# Stop MySQL service
sudo systemctl stop mysql

# Remove all MySQL data
sudo rm -rf /var/lib/mysql/*

# Reinitialize MySQL
sudo mysqld --initialize-insecure --user=mysql --datadir=/var/lib/mysql

# Start MySQL service
sudo systemctl start mysql

# Set root password
sudo mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'Root#MySQL!SuperSecure@GCP';"

# Create fresh database and user with correct credentials
sudo mysql -u root -p'Root#MySQL!SuperSecure@GCP' -e "
CREATE DATABASE seegap_application_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'seegap_prod_user_2025'@'localhost' IDENTIFIED BY 'SeeGapProd2025MySQLSecure';
GRANT ALL PRIVILEGES ON seegap_application_db.* TO 'seegap_prod_user_2025'@'localhost';
FLUSH PRIVILEGES;
"

echo 'Database reset and created successfully!'

# Verify the setup
echo 'Verifying database setup:'
sudo mysql -u root -p'Root#MySQL!SuperSecure@GCP' -e 'SHOW DATABASES;'

echo 'Testing new credentials:'
mysql -u seegap_prod_user_2025 -p'SeeGapProd2025MySQLSecure' -e "SELECT 'Connection successful!' as status;" seegap_application_db

echo 'Database setup completed successfully!'
