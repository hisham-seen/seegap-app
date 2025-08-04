<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Response;

defined('SEEGAP') || die();

class DebugLog extends Controller {

    public function index() {
        if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] === 'debug_log') {
            
            $message = $_POST['message'] ?? 'No message';
            $data = $_POST['data'] ?? null;
            
            // Format the debug log entry
            $log_entry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => 'FRONTEND_DEBUG',
                'message' => $message,
                'data' => $data,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'ip' => get_ip(),
                'user_id' => $this->user->user_id ?? 'guest'
            ];
            
            // Use the debug_log function to write to the log file
            debug_log('FRONTEND_DEBUG', $log_entry);
            
            // Return success response
            Response::json('', 'success');
        }
        
        die();
    }
}
