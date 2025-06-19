<?php
/* Production Configuration for SeeGap Application - Docker Deployment */
define('DATABASE_SERVER',   'mysql');
define('DATABASE_USERNAME', 'seegap_prod_user_2025');
define('DATABASE_PASSWORD', 'SeeGapProd2025MySQLSecure');
define('DATABASE_NAME',     'seegap_application_db');
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
define('MAX_UPLOAD_SIZE', '50M');
define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,webp,pdf,doc,docx,txt,csv,xlsx');

/* Logging Configuration */
define('LOG_ERRORS', true);
define('LOG_FILE', '/var/www/seegap/uploads/logs/application.log');

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

/* Docker-specific settings */
define('WEB_SERVER', 'nginx');
define('REDIS_HOST', 'redis');
define('REDIS_PORT', 6379);

/* Email Configuration */
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');

/* CDN Configuration */
define('CDN_ENABLED', false);
define('CDN_URL', '');

/* Production Optimization */
define('MINIFY_CSS', true);
define('MINIFY_JS', true);
define('GZIP_COMPRESSION', true);

/* Security Headers */
define('SECURITY_HEADERS', true);
define('CSRF_PROTECTION', true);
define('XSS_PROTECTION', true);
?>
