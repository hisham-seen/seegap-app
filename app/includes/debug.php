<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('ALTUMCODE') || die();

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
