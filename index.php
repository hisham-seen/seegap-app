<?php

/* Enabling debug mode is only for debugging / development purposes. */
const DEBUG = 0;

/* Enabling mysql debug mode is only for debugging / development purposes. */
const MYSQL_DEBUG = 0;

/* Enabling the file logging will store errors that occur, in the uploads/logs/ folder */
const LOGGING = 1;

/* Enabling the cache will use file caching where implemented for better performance */
const CACHE = 0;

/* Only meant for Demo purposes, don't change :) */
//SEEGAP:DEMO const DEMO = 1;

const SEEGAP = 66;

require_once realpath(__DIR__) . '/app/init.php';

/* Handle client debug logs */
if (isset($_POST['client_debug']) && $_POST['client_debug'] === '1') {
    $message = $_POST['message'] ?? 'No message';
    $block_id = $_POST['block_id'] ?? 'unknown';
    $timestamp = $_POST['timestamp'] ?? date('c');
    $debug_data = $_POST['debug_data'] ?? '';
    
    $log_entry = "CLIENT DEBUG [Block: {$block_id}] [{$timestamp}] {$message}";
    if ($debug_data) {
        $log_entry .= " | Data: {$debug_data}";
    }
    
    error_log($log_entry);
    
    // Return minimal response and exit
    http_response_code(200);
    echo 'logged';
    exit;
}

$App = new SeeGap\App();
