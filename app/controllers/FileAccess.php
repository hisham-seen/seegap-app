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

class FileAccess extends Controller {

    public function index() {
        /* Require authentication */
        \SeeGap\Authentication::guard();
        
        /* Get the file ID from the URL */
        $file_id = $_GET['file_id'] ?? null;
        
        if (!$file_id) {
            http_response_code(404);
            die('File not found');
        }
        
        /* Find the file in form submissions */
        $file_info = $this->findFileInSubmissions($file_id);
        
        if (!$file_info) {
            http_response_code(404);
            die('File not found');
        }
        
        /* Verify user owns the form */
        if (!$this->verifyFormOwnership($file_info['link_id'])) {
            http_response_code(403);
            die('Access denied');
        }
        
        /* Serve the file */
        $this->serveFile($file_info);
    }
    
    /**
     * Find file information in form submissions
     */
    private function findFileInSubmissions($file_id) {
        /* Get all form submissions for this user */
        $submissions = database()->query("
            SELECT fs.responses, fs.link_id, fs.form_submission_id
            FROM form_submissions fs
            LEFT JOIN links l ON fs.link_id = l.link_id
            WHERE l.user_id = {$this->user->user_id}
        ");
        
        while ($submission = $submissions->fetch_object()) {
            $responses = json_decode($submission->responses, true);
            
            if (!$responses) continue;
            
            foreach ($responses as $question => $response) {
                if (is_array($response) && isset($response['files'])) {
                    foreach ($response['files'] as $file) {
                        if (isset($file['file_id']) && $file['file_id'] === $file_id) {
                            return [
                                'file_id' => $file['file_id'],
                                'original_name' => $file['original_name'],
                                'secure_filename' => $file['secure_filename'],
                                'mime_type' => $file['mime_type'],
                                'size' => $file['size'],
                                'path' => $file['path'],
                                'link_id' => $submission->link_id,
                                'form_submission_id' => $submission->form_submission_id,
                                'form_owner_id' => $file['form_owner_id']
                            ];
                        }
                    }
                }
            }
        }
        
        return null;
    }
    
    /**
     * Verify user owns the form
     */
    private function verifyFormOwnership($link_id) {
        $link = db()->where('link_id', $link_id)->where('user_id', $this->user->user_id)->getOne('links', ['link_id']);
        return $link !== null;
    }
    
    /**
     * Serve the file with proper security headers
     */
    private function serveFile($file_info) {
        $file_path = UPLOADS_PATH . $file_info['path'];
        
        /* Check if file exists */
        if (!file_exists($file_path)) {
            http_response_code(404);
            die('File not found on disk');
        }
        
        /* Log file access for audit */
        error_log("File access: User {$this->user->user_id} accessed file {$file_info['file_id']} ({$file_info['original_name']})");
        
        /* Set security headers */
        header('Content-Type: ' . $file_info['mime_type']);
        header('Content-Length: ' . $file_info['size']);
        header('Content-Disposition: inline; filename="' . addslashes($file_info['original_name']) . '"');
        header('Cache-Control: private, max-age=3600');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        
        /* Serve the file */
        readfile($file_path);
        exit;
    }
    
    /**
     * Handle thumbnail generation for images
     */
    public function thumbnail() {
        /* Require authentication */
        \SeeGap\Authentication::guard();
        
        $file_id = $_GET['file_id'] ?? null;
        $size = (int) ($_GET['size'] ?? 150);
        
        /* Limit thumbnail size */
        $size = min(max($size, 50), 500);
        
        if (!$file_id) {
            http_response_code(404);
            die('File not found');
        }
        
        /* Find the file */
        $file_info = $this->findFileInSubmissions($file_id);
        
        if (!$file_info) {
            http_response_code(404);
            die('File not found');
        }
        
        /* Verify user owns the form */
        if (!$this->verifyFormOwnership($file_info['link_id'])) {
            http_response_code(403);
            die('Access denied');
        }
        
        /* Only generate thumbnails for images */
        if (!str_starts_with($file_info['mime_type'], 'image/')) {
            http_response_code(400);
            die('Not an image file');
        }
        
        $file_path = UPLOADS_PATH . $file_info['path'];
        
        if (!file_exists($file_path)) {
            http_response_code(404);
            die('File not found on disk');
        }
        
        /* Generate and serve thumbnail */
        $this->generateThumbnail($file_path, $file_info['mime_type'], $size);
    }
    
    /**
     * Generate thumbnail for image
     */
    private function generateThumbnail($file_path, $mime_type, $size) {
        /* Check if GD extension is available and functions exist */
        if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
            /* Fallback: serve original image with size limit headers */
            header('Content-Type: ' . $mime_type);
            header('Cache-Control: public, max-age=86400');
            header('X-Content-Type-Options: nosniff');
            readfile($file_path);
            exit;
        }
        
        /* Create image resource based on type */
        $source = false;
        switch ($mime_type) {
            case 'image/jpeg':
            case 'image/jpg':
                if (function_exists('imagecreatefromjpeg')) {
                    $source = \imagecreatefromjpeg($file_path);
                }
                break;
            case 'image/png':
                if (function_exists('imagecreatefrompng')) {
                    $source = \imagecreatefrompng($file_path);
                }
                break;
            case 'image/gif':
                if (function_exists('imagecreatefromgif')) {
                    $source = \imagecreatefromgif($file_path);
                }
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $source = \imagecreatefromwebp($file_path);
                }
                break;
            default:
                /* Fallback for unsupported types */
                header('Content-Type: ' . $mime_type);
                header('Cache-Control: public, max-age=86400');
                header('X-Content-Type-Options: nosniff');
                readfile($file_path);
                exit;
        }
        
        /* If image creation failed, serve original */
        if (!$source) {
            header('Content-Type: ' . $mime_type);
            header('Cache-Control: public, max-age=86400');
            header('X-Content-Type-Options: nosniff');
            readfile($file_path);
            exit;
        }
        
        /* Get original dimensions */
        $orig_width = \imagesx($source);
        $orig_height = \imagesy($source);
        
        /* Calculate thumbnail dimensions (maintain aspect ratio) */
        if ($orig_width > $orig_height) {
            $thumb_width = $size;
            $thumb_height = intval($orig_height * $size / $orig_width);
        } else {
            $thumb_height = $size;
            $thumb_width = intval($orig_width * $size / $orig_height);
        }
        
        /* Create thumbnail */
        $thumbnail = \imagecreatetruecolor($thumb_width, $thumb_height);
        
        /* Preserve transparency for PNG and GIF */
        if ($mime_type === 'image/png' || $mime_type === 'image/gif') {
            \imagealphablending($thumbnail, false);
            \imagesavealpha($thumbnail, true);
            $transparent = \imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            \imagefilledrectangle($thumbnail, 0, 0, $thumb_width, $thumb_height, $transparent);
        }
        
        /* Resize image */
        \imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $orig_width, $orig_height);
        
        /* Set headers */
        header('Content-Type: image/jpeg');
        header('Cache-Control: public, max-age=86400');
        header('X-Content-Type-Options: nosniff');
        
        /* Output thumbnail */
        \imagejpeg($thumbnail, null, 85);
        
        /* Clean up */
        \imagedestroy($source);
        \imagedestroy($thumbnail);
        
        exit;
    }
}
