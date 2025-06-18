<?php

/* Production Configuration for SeeGap Application */
define('DATABASE_SERVER',   'mysql');
define('DATABASE_USERNAME', 'seegap_prod_user_2025');
define('DATABASE_PASSWORD', 'SeeGapProd2025MySQLSecure');
define('DATABASE_NAME',     'seegap_production_db');
define('SITE_URL',          'https://si.seegap.com/');

/* Additional Production Settings */
define('ENVIRONMENT', 'production');
define('DEBUG_MODE', false);
define('ERROR_REPORTING', false);

/* Security Settings */
define('SECURE_COOKIES', true);
define('FORCE_HTTPS', true);

/* Performance Settings */
define('ENABLE_CACHING', true);
define('CACHE_LIFETIME', 3600);

/* File Upload Settings */
define('MAX_UPLOAD_SIZE', '10M');
define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,webp,pdf,doc,docx');

/* Logging Configuration */
define('LOG_ERRORS', true);
define('LOG_FILE', '/var/www/seegap/current/uploads/logs/application.log');

/* Session Configuration */
define('SESSION_LIFETIME', 86400);
define('SESSION_NAME', 'SEEGAP_SESSION');

/* API Configuration */
define('API_RATE_LIMIT', 1000);
define('API_VERSION', 'v1');

/* Backup Configuration */
define('BACKUP_ENABLED', true);
define('BACKUP_RETENTION_DAYS', 30);

/* Monitoring Configuration */
define('HEALTH_CHECK_ENABLED', true);
define('METRICS_ENABLED', true);

?>
