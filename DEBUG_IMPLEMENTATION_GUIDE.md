# SeeGap Application Debug Implementation Guide

## Overview

This guide documents the comprehensive debugging system implemented for the SeeGap application to help identify and resolve the "Server returned an unexpected response" error when creating links.

## Debug Infrastructure

### 1. Core Debug Function

The application uses a custom `debug_log()` function located in `app/includes/debug.php`:

```php
function debug_log($type, $data = null) {
    if (!defined('LOGGING') || !LOGGING) {
        return;
    }
    
    $log_file = UPLOADS_PATH . 'logs/' . date('Y-m-d') . '.log';
    
    // Ensure the logs directory exists
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Format the log entry
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] {$type}: ";
    
    if (is_array($data) || is_object($data)) {
        $log_entry .= json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } else {
        $log_entry .= (string) $data;
    }
    
    $log_entry .= PHP_EOL;
    
    // Write to log file
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
```

### 2. Configuration

Debug logging is controlled by constants in `index.php`:

```php
const DEBUG = 0;        // Display errors (0 = off, 1 = on)
const MYSQL_DEBUG = 0;  // MySQL debug mode
const LOGGING = 1;      // File logging (0 = off, 1 = on)
const CACHE = 0;        // Caching system
```

**Important**: `LOGGING = 1` must be set for debug logging to work.

### 3. Log File Location

Debug logs are written to: `uploads/logs/YYYY-MM-DD.log`

Example: `uploads/logs/2025-08-04.log`

## Implemented Debug Points

### 1. LinkAjax Controller (`app/controllers/LinkAjax.php`)

**Entry Point Debugging:**
```php
debug_log('LINK_AJAX_REQUEST', [
    'user_id' => $this->user->user_id ?? 'unknown',
    'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
    'post_data' => $_POST,
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'ip' => get_ip(),
    'timestamp' => date('Y-m-d H:i:s')
]);
```

**Request Processing:**
```php
debug_log('LINK_AJAX_PROCESSING', [
    'request_type' => $_POST['request_type'],
    'type' => $_POST['type'] ?? 'not_set',
    'csrf_valid' => true,
    'user_id' => $this->user->user_id
]);
```

**Exception Handling:**
```php
debug_log('LINK_AJAX_EXCEPTION', [
    'message' => $e->getMessage(),
    'file' => $e->getFile(),
    'line' => $e->getLine(),
    'trace' => $e->getTraceAsString(),
    'request_type' => $_POST['request_type'] ?? 'unknown',
    'user_id' => $this->user->user_id
]);
```

**Validation Failures:**
```php
debug_log('LINK_AJAX_VALIDATION_FAILED', [
    'post_empty' => empty($_POST),
    'csrf_token_valid' => \SeeGap\Csrf::check('token'),
    'csrf_global_token_valid' => \SeeGap\Csrf::check('global_token'),
    'request_type_set' => isset($_POST['request_type']),
    'user_id' => $this->user->user_id ?? 'unknown'
]);
```

### 2. LinkHandler (`app/controllers/link-handlers/handlers/LinkHandler.php`)

**Creation Process Start:**
```php
debug_log('LINK_HANDLER_CREATE_START', [
    'type' => $type,
    'user_id' => $this->user->user_id,
    'post_data' => $_POST,
    'shortener_enabled' => settings()->links->shortener_is_enabled ?? 'not_set'
]);
```

**Data Processing:**
```php
debug_log('LINK_HANDLER_POST_PROCESSING', [
    'processed_location_url' => $_POST['location_url'],
    'processed_url' => $_POST['url'],
    'custom_url_allowed' => $this->user->plan_settings->custom_url ?? false
]);
```

**Validation Steps:**
```php
debug_log('LINK_HANDLER_VALIDATION_START', [
    'url_to_check' => $_POST['url'],
    'location_url_to_check' => $_POST['location_url']
]);

debug_log('LINK_HANDLER_VALIDATION_PASSED', 'URL validation completed successfully');
```

**Plan Limit Checks:**
```php
debug_log('LINK_HANDLER_LIMIT_CHECK', [
    'user_total_links' => $user_total_links,
    'links_limit' => $this->user->plan_settings->links_limit ?? 'not_set'
]);
```

**Database Operations:**
```php
debug_log('LINK_HANDLER_DATABASE_INSERT_START', [
    'insert_data' => $insert_data
]);

debug_log('LINK_HANDLER_DATABASE_INSERT_SUCCESS', [
    'link_id' => $link_id,
    'insert_successful' => !empty($link_id)
]);
```

**Success/Failure:**
```php
debug_log('LINK_HANDLER_CREATE_SUCCESS', [
    'link_id' => $link_id,
    'final_url' => $url,
    'response_url' => url('link/' . $link_id)
]);
```

### 3. BaseLinkHandler (`app/controllers/link-handlers/BaseLinkHandler.php`)

**URL Validation:**
```php
debug_log('BASE_HANDLER_CHECK_LOCATION_URL', [
    'url' => $url,
    'can_be_empty' => $can_be_empty,
    'url_empty' => empty(trim($url))
]);

debug_log('BASE_HANDLER_URL_PARSE', [
    'url' => $url,
    'parsed_details' => $url_details,
    'has_scheme' => isset($url_details['scheme'])
]);
```

**Domain Checks:**
```php
debug_log('BASE_HANDLER_DOMAIN_CHECK', [
    'url' => $url,
    'extracted_domain' => $domain,
    'blacklisted_domains' => settings()->links->blacklisted_domains ?? []
]);
```

**Google Safe Browsing:**
```php
debug_log('BASE_HANDLER_GOOGLE_SAFE_BROWSING_CHECK', [
    'url' => $url,
    'api_key_set' => !empty(settings()->links->google_safe_browsing_api_key)
]);
```

## How to Use Debug Logging

### 1. Basic Usage

```php
// Simple message
debug_log('DEBUG_TYPE', 'Your debug message here');

// With data array
debug_log('DEBUG_TYPE', [
    'key1' => 'value1',
    'key2' => $variable,
    'timestamp' => date('Y-m-d H:i:s')
]);
```

### 2. Debug Types Convention

Use descriptive, uppercase debug types with underscores:

- `CONTROLLER_NAME_ACTION` - For controller actions
- `HANDLER_NAME_STEP` - For handler steps
- `VALIDATION_TYPE` - For validation processes
- `DATABASE_OPERATION` - For database operations
- `ERROR_TYPE` - For error conditions

### 3. Best Practices

1. **Include Context**: Always include relevant data like user_id, request data, etc.
2. **Use Descriptive Types**: Make debug types searchable and meaningful
3. **Log Before Errors**: Add debug logs before potential failure points
4. **Include Timestamps**: For tracking execution flow timing
5. **Sanitize Sensitive Data**: Don't log passwords or sensitive information

### 4. Example Implementation

```php
public function someFunction($data) {
    debug_log('FUNCTION_START', [
        'function' => __FUNCTION__,
        'input_data' => $data,
        'user_id' => $this->user->user_id ?? 'unknown'
    ]);
    
    try {
        // Your code here
        $result = $this->processData($data);
        
        debug_log('FUNCTION_SUCCESS', [
            'function' => __FUNCTION__,
            'result' => $result
        ]);
        
        return $result;
        
    } catch (\Exception $e) {
        debug_log('FUNCTION_EXCEPTION', [
            'function' => __FUNCTION__,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'input_data' => $data
        ]);
        throw $e;
    }
}
```

## Troubleshooting Link Creation Issues

### 1. Check Log File

Monitor the log file in real-time:
```bash
tail -f uploads/logs/$(date +%Y-%m-%d).log
```

### 2. Common Debug Patterns

**CSRF Issues:**
Look for `LINK_AJAX_VALIDATION_FAILED` with `csrf_token_valid: false`

**Missing Data:**
Look for `LINK_HANDLER_CREATE_ERROR` with "Empty location_url"

**Plan Limits:**
Look for `LINK_HANDLER_CREATE_ERROR` with "Plan limit exceeded"

**Database Issues:**
Look for `LINK_HANDLER_DATABASE_EXCEPTION` or `LINK_HANDLER_DATABASE_INSERT_FAILED`

**URL Validation:**
Look for `BASE_HANDLER_CHECK_LOCATION_URL_ERROR` entries

### 3. Testing Debug System

Use the provided test file:
```bash
php test_debug.php
```

This will verify that debug logging is working correctly.

## Log Analysis

### 1. Log Format

```
[YYYY-MM-DD HH:MM:SS] DEBUG_TYPE: message or JSON data
```

### 2. Filtering Logs

```bash
# Filter by debug type
grep "LINK_HANDLER" uploads/logs/2025-08-04.log

# Filter by user ID
grep "user_id.*123" uploads/logs/2025-08-04.log

# Filter errors only
grep "ERROR\|EXCEPTION\|FAILED" uploads/logs/2025-08-04.log
```

### 3. JSON Data

Complex data is logged as formatted JSON for easy reading:

```
[2025-08-04 23:19:05] LINK_HANDLER_CREATE_START: {
    "type": "link",
    "user_id": 123,
    "post_data": {
        "location_url": "https://example.com",
        "request_type": "create"
    }
}
```

## Maintenance

### 1. Log Rotation

Logs are automatically created daily. Consider implementing log rotation:

```bash
# Archive old logs
find uploads/logs/ -name "*.log" -mtime +30 -exec gzip {} \;

# Delete very old logs
find uploads/logs/ -name "*.log.gz" -mtime +90 -delete
```

### 2. Performance Considerations

- Debug logging has minimal performance impact
- Large data structures may increase log file size
- Consider disabling in production if not needed

### 3. Security

- Log files may contain sensitive information
- Ensure proper file permissions (644 or 600)
- Consider log file access restrictions

## Conclusion

This debug implementation provides comprehensive logging for the link creation process, making it easy to identify where the "Server returned an unexpected response" error occurs and why. The debug logs will show the exact point of failure and provide context for troubleshooting.
