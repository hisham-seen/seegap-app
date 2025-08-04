<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('SEEGAP') || die();

if(DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 'Off');
}

if(LOGGING) {
    ini_set('log_errors', 1);
    ini_set('error_log', UPLOADS_PATH . 'logs/' . date('Y-m-d') . '.log');
} else {
    ini_set('log_errors', 0);
}

ini_set('html_errors', 0);

/**
 * Custom debug logging function
 * 
 * @param string $type The type/category of the log entry
 * @param mixed $data The data to log (array, string, object, etc.)
 */
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
