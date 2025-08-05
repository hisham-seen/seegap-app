<?php

/* Test debug logging functionality */
const DEBUG = 0;
const MYSQL_DEBUG = 0;
const LOGGING = 1;
const CACHE = 0;
const SEEGAP = 66;

require_once realpath(__DIR__) . '/app/init.php';

// Test debug logging
debug_log('DEBUG_TEST', 'Testing debug logging functionality');
debug_log('DEBUG_TEST_ARRAY', [
    'test_key' => 'test_value',
    'timestamp' => date('Y-m-d H:i:s'),
    'message' => 'This is a test debug log entry'
]);

echo "Debug test completed. Check the log file: uploads/logs/" . date('Y-m-d') . ".log\n";
