<?php

/* Configuration of the site */
/* Copy this file to config.php and update with your actual values */

define('DATABASE_SERVER',   'mysql');                    // Database host
define('DATABASE_USERNAME', 'your_database_username');   // Database username
define('DATABASE_PASSWORD', 'your_database_password');   // Database password
define('DATABASE_NAME',     'your_database_name');       // Database name
define('SITE_URL',          'https://your-domain.com/'); // Your site URL with trailing slash

/*
 * Instructions:
 * 1. Copy this file to config.php
 * 2. Replace the placeholder values with your actual configuration
 * 3. Never commit config.php to version control
 * 
 * Example for local development:
 * define('DATABASE_SERVER',   'mysql');
 * define('DATABASE_USERNAME', 'seegap_local_user');
 * define('DATABASE_PASSWORD', 'local_password_123');
 * define('DATABASE_NAME',     'seegap_local_db');
 * define('SITE_URL',          'http://localhost/');
 * 
 * Example for production:
 * define('DATABASE_SERVER',   'mysql');
 * define('DATABASE_USERNAME', 'seegap_prod_user_2025');
 * define('DATABASE_PASSWORD', 'SeeGap#Prod$2025!MySQL@Secure');
 * define('DATABASE_NAME',     'seegap_production_db');
 * define('SITE_URL',          'https://si.seegap.com/');
 */
