<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Alerts;
use SeeGap\Date;
use SeeGap\Models\MicrositesThemes;
use SeeGap\Models\Domain;
use SeeGap\Response;

defined('SEEGAP') || die();

class Ajax extends Controller {
    public $links_types = null;
    public $microsite_blocks = null;
    public $total_microsite_blocks = 0;

    public function index() {
        // Add debug logging
        debug_log('UNIVERSAL_AJAX_REQUEST', [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'post_data' => $_POST,
            'user_id' => $this->user->user_id ?? 'unknown'
        ]);

        /* Allow public form submissions without authentication */
        if(isset($_POST['request_type']) && $_POST['request_type'] === 'submit_form') {
            /* Skip authentication for public form submissions */
        } else {
            try {
                \SeeGap\Authentication::guard();
            } catch (\Exception $e) {
                debug_log('UNIVERSAL_AJAX_AUTH_FAILED', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                Response::json('Authentication failed', 'error');
            }
        }

        if(!empty($_POST) && (\SeeGap\Csrf::check('token') || \SeeGap\Csrf::check('global_token') || (isset($_POST['request_type']) && $_POST['request_type'] === 'submit_form'))) {
            
            $ajax_type = $_POST['ajax_type'] ?? $this->detectAjaxType();
            
            debug_log('UNIVERSAL_AJAX_PROCESSING', [
                'ajax_type' => $ajax_type,
                'request_type' => $_POST['request_type'] ?? 'not_set',
                'csrf_valid' => true,
                'user_id' => $this->user->user_id ?? 'unknown',
                'all_post_keys' => array_keys($_POST)
            ]);

            try {
                switch($ajax_type) {
                    case 'link':
                        return $this->handleLink();
                        
                    case 'microsite_block':
                        return $this->handleMicrositeBlock();
                        
                    case 'gs1_link':
                        return $this->handleGs1Link();
                        
                    case 'product':
                        return $this->handleProduct();
                        
                    default:
                        debug_log('UNIVERSAL_AJAX_ERROR', 'Unknown ajax_type: ' . $ajax_type);
                        Response::json('Unknown request type', 'error');
                        break;
                }
            } catch (\Exception $e) {
                debug_log('UNIVERSAL_AJAX_EXCEPTION', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'ajax_type' => $ajax_type,
                    'user_id' => $this->user->user_id ?? 'unknown'
                ]);
                Response::json('Server error: ' . $e->getMessage(), 'error');
            }

        } else {
            debug_log('UNIVERSAL_AJAX_VALIDATION_FAILED', [
                'post_empty' => empty($_POST),
                'csrf_token_valid' => \SeeGap\Csrf::check('token'),
                'csrf_global_token_valid' => \SeeGap\Csrf::check('global_token'),
                'user_id' => $this->user->user_id ?? 'unknown'
            ]);
            Response::json('Invalid request', 'error');
        }

        die();
    }

    /**
     * Detect AJAX type from request parameters
     */
    private function detectAjaxType() {
        // Parameter-based detection (primary method now)
        
        // Check for explicit ajax_type parameter first
        if (isset($_POST['ajax_type'])) {
            return $_POST['ajax_type'];
        }
        
        // Special case: is_enabled_toggle with id parameter should be microsite_block
        if (isset($_POST['request_type']) && $_POST['request_type'] === 'is_enabled_toggle' && isset($_POST['id'])) {
            return 'microsite_block';
        }
        
        // GS1 link operations (check before link operations)
        if (isset($_POST['gs1_link_id'])) {
            return 'gs1_link';
        }
        
        // Product operations - check for product-specific fields
        if (isset($_POST['product_id']) || 
            (isset($_POST['gtin']) && isset($_POST['product_name']) && isset($_POST['brand_name']))) {
            return 'product';
        }
        
        // Check for microsite settings updates FIRST (these should NOT go to microsite_block handler)
        if (isset($_POST['type']) && $_POST['type'] === 'microsite' && isset($_POST['link_id'])) {
            return 'link'; // Microsite settings updates go to link handler
        }
        
        // Microsite block operations (check AFTER microsite settings)
        if (isset($_POST['block_type']) || isset($_POST['microsite_block_id']) || 
            (isset($_POST['microsite_blocks']) && is_array($_POST['microsite_blocks']))) {
            return 'microsite_block';
        }
        
        // Other link operations
        if (isset($_POST['link_id']) || isset($_POST['type'])) {
            return 'link';
        }
        
        // Fallback to URI-based detection for backward compatibility
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        if (strpos($request_uri, 'microsite-block-ajax') !== false) {
            return 'microsite_block';
        }
        
        if (strpos($request_uri, 'gs1-link-ajax') !== false) {
            return 'gs1_link';
        }
        
        if (strpos($request_uri, 'link-ajax') !== false) {
            return 'link';
        }
        
        if (strpos($request_uri, 'product-ajax') !== false) {
            return 'product';
        }
        
        return 'unknown';
    }

    /**
     * Handle link operations (create, update, delete links and microsites)
     */
    private function handleLink() {
        if (!isset($_POST['request_type'])) {
            Response::json('Missing request_type parameter', 'error');
        }

        $this->links_types = require APP_PATH . 'includes/links_types.php';

        switch($_POST['request_type']) {
            case 'is_enabled_toggle':
                return $this->linkIsEnabledToggle();
            case 'create':
                return $this->linkCreate();
            case 'update':
                return $this->linkUpdate();
            case 'delete':
                return $this->linkDelete();
            case 'duplicate':
                return $this->linkDuplicate();
            default:
                Response::json('Unknown link request type', 'error');
        }
    }

    /**
     * Handle microsite block operations
     */
    private function handleMicrositeBlock() {
        if (!isset($_POST['request_type'])) {
            Response::json('Missing request_type parameter', 'error');
        }

        switch($_POST['request_type']) {
            case 'is_enabled_toggle':
                return $this->micrositeBlockIsEnabledToggle();
            case 'duplicate':
                return $this->micrositeBlockDuplicate();
            case 'order':
                return $this->micrositeBlockOrder();
            case 'create':
                return $this->micrositeBlockCreate();
            case 'update':
                return $this->micrositeBlockUpdate();
            case 'delete':
                return $this->micrositeBlockDelete();
            case 'submit_form':
                return $this->micrositeBlockSubmitForm();
            default:
                Response::json('Unknown microsite block request type', 'error');
        }
    }

    /**
     * Handle GS1 link operations
     */
    private function handleGs1Link() {
        /* Check if GS1 links feature is enabled */
        if(!settings()->gs1_links->gs1_links_is_enabled) {
            die();
        }

        if (!isset($_POST['request_type'])) {
            Response::json('Missing request_type parameter', 'error');
        }

        switch($_POST['request_type']) {
            case 'is_enabled_toggle':
                return $this->gs1LinkIsEnabledToggle();
            case 'delete':
                return $this->gs1LinkDelete();
            case 'duplicate':
                return $this->gs1LinkDuplicate();
            default:
                Response::json('Unknown GS1 link request type', 'error');
        }
    }

    /**
     * Handle product operations
     */
    private function handleProduct() {
        if (!isset($_POST['request_type'])) {
            Response::json('Missing request_type parameter', 'error');
        }

        switch($_POST['request_type']) {
            case 'create':
                return $this->productCreate();
            case 'is_enabled_toggle':
                return $this->productIsEnabledToggle();
            case 'duplicate':
                return $this->productDuplicate();
            default:
                Response::json('Unknown product request type', 'error');
        }
    }

    // ========================================
    // LINK OPERATIONS
    // ========================================

    private function linkIsEnabledToggle() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];

        /* Get the current status */
        $link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links', ['link_id', 'is_enabled']);

        if($link) {
            $new_is_enabled = (int) !$link->is_enabled;

            db()->where('link_id', $link->link_id)->update('links', ['is_enabled' => $new_is_enabled]);

            /* Clear the cache */
            cache()->deleteItem('link?link_id=' . $_POST['link_id']);
            cache()->deleteItem('microsite_blocks?link_id=' . $_POST['link_id']);
            cache()->deleteItemsByTag('link_id=' . $_POST['link_id']);

            Response::json(l('global.success_message.create2'), 'success');
        }
    }

    private function linkCreate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['type'] = trim(query_clean($_POST['type']));

        /* Check for possible errors */
        if(!array_key_exists($_POST['type'], $this->links_types)) {
            die();
        }

        $this->routeToLinkHandler($_POST['type'], 'create');
    }

    private function linkUpdate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(empty($_POST)) {
            die();
        }

        /* Check for possible errors */
        if(!array_key_exists($_POST['type'], $this->links_types)) {
            die();
        }

        $this->routeToLinkHandler($_POST['type'], 'update');
    }

    /**
     * Route requests to appropriate link handlers
     */
    private function routeToLinkHandler($link_type, $action) {
        // Define mapping of link types to handler classes
        $link_handlers = [
            'link' => 'LinkHandler',
            'microsite' => 'MicrositeHandler', 
            'file' => 'FileHandler',
            'event' => 'EventHandler',
            'static' => 'StaticHandler',
        ];

        // Check if handler exists for this link type
        if (!isset($link_handlers[$link_type])) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $handler_class = $link_handlers[$link_type];
        
        // Load dependencies in correct order
        require_once APP_PATH . 'controllers/link-handlers/interfaces/LinkHandlerInterface.php';
        require_once APP_PATH . 'controllers/link-handlers/BaseLinkHandler.php';
        require_once APP_PATH . 'controllers/link-handlers/handlers/' . $handler_class . '.php';

        // Create handler instance with proper namespace
        $handler_class_full = '\\SeeGap\\Controllers\\LinkHandlers\\Handlers\\' . $handler_class;
        $handler = new $handler_class_full();
        
        // Set user context
        $handler->user = $this->user;
        
        // Execute the action
        $handler->$action($link_type);
    }

    private function linkDelete() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];

        /* Check for possible errors */
        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links', ['link_id', 'type'])) {
            die();
        }

        (new \SeeGap\Models\Link())->delete($link->link_id);

        Response::json(l('global.success_message.delete2'), 'success', ['url' => url('links?type=' . $link->type)]);
    }

    private function linkDuplicate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('links');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('links');
        }

        /* Get the link data */
        $link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links');

        if(!$link) {
            redirect('links');
        }

        /* Make sure that the user didn't exceed the limit */
        if($link->type == 'link') {
            if(!settings()->links->shortener_is_enabled) {
                Response::json(l('global.error_message.basic'), 'error');
            }

            $user_total_links = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'link'")->fetch_object()->total;
            if($this->user->plan_settings->links_limit != -1 && $user_total_links >= $this->user->plan_settings->links_limit) {
                Alerts::add_error(l('global.info_message.plan_feature_limit'));
            }
        }

        elseif($link->type == 'microsite') {
            if(!settings()->links->microsites_is_enabled) {
                Response::json(l('global.error_message.basic'), 'error');
            }

            $user_total_microsites = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'microsite'")->fetch_object()->total;
            if($this->user->plan_settings->microsites_limit != -1 && $user_total_microsites >= $this->user->plan_settings->microsites_limit) {
                Alerts::add_error(l('global.info_message.plan_feature_limit'));
            }
        }

        elseif($link->type == 'file') {
            if(!settings()->links->files_is_enabled) {
                Response::json(l('global.error_message.basic'), 'error');
            }

            $user_total_files = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'file'")->fetch_object()->total;
            if($this->user->plan_settings->files_limit != -1 && $user_total_files >= $this->user->plan_settings->files_limit) {
                Alerts::add_error(l('global.info_message.plan_feature_limit'));
            }
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Duplicate the link */
            $link->settings = json_decode($link->settings ?? '');

            if($link->type == 'microsite') {
                $link->settings->seo->image = \SeeGap\Uploads::copy_uploaded_file($link->settings->seo->image, 'block_images/', 'block_images/', 'json_error');
                $link->settings->favicon = \SeeGap\Uploads::copy_uploaded_file($link->settings->favicon, 'favicons/', 'favicons/', 'json_error');
                if($link->settings->background_type == 'image') $link->settings->background = \SeeGap\Uploads::copy_uploaded_file($link->settings->background, 'backgrounds/', 'backgrounds/', 'json_error');
                $link->settings->pwa_is_enabled = false;
            }

            if($link->type == 'file') {
                $link->settings->file = \SeeGap\Uploads::copy_uploaded_file($link->settings->file, \SeeGap\Uploads::get_path('files'), \SeeGap\Uploads::get_path('files'), 'json_error');
            }

            /* Generate random url if not specified */
            $url = mb_strtolower(string_generate(settings()->links->random_url_length ?? 7));
            while (db()->where('url', $url)->where('domain_id', $link->domain_id)->getValue('links', 'link_id')) {
                $url = mb_strtolower(string_generate(settings()->links->random_url_length ?? 7));
            }

            /* Database query */
            $link_id = db()->insert('links', [
                'user_id' => $this->user->user_id,
                'project_id' => $link->project_id,
                'microsite_theme_id' => $link->microsite_theme_id,
                'domain_id' => $link->domain_id,
                'pixels_ids' => $link->pixels_ids,
                'type' => $link->type,
                'url' => $url,
                'location_url' => $link->location_url,
                'settings' => json_encode($link->settings),
                'additional' => $link->additional ?? '',
                'start_date' => $link->start_date,
                'end_date' => $link->end_date,
                'is_verified' => 0,
                'is_enabled' => $link->is_enabled,
                'datetime' => get_date(),
            ]);

            /* Duplicate the microsite blocks */
            if($link->type == 'microsite') {
                /* Get all microsite blocks if needed */
                $microsite_blocks = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->get('microsites_blocks');

                foreach($microsite_blocks as $microsite_block) {
                    $microsite_block->settings = json_decode($microsite_block->settings ?? '');

                    if(is_array($microsite_block->settings)) {
                        $microsite_block->settings = (object) $microsite_block->settings;
                    }

                    /* Duplication of resources */
                    switch($microsite_block->type) {
                        case 'file':
                        case 'audio':
                        case 'video':
                        case 'pdf_document':
                        case 'powerpoint_presentation':
                        case 'excel_spreadsheet':
                            $microsite_block->settings->file = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->file, \SeeGap\Uploads::get_path('files'), \SeeGap\Uploads::get_path('files'), 'json_error');
                            break;

                        case 'review':
                            $microsite_block->settings->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->image, \SeeGap\Uploads::get_path('block_images'), \SeeGap\Uploads::get_path('block_images'), 'json_error');
                            break;

                        case 'header':
                            $microsite_block->settings->avatar = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->avatar, 'avatars/', 'avatars/', 'json_error');
                            $microsite_block->settings->background = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->background, 'backgrounds/', 'backgrounds/', 'json_error');
                            break;

                        case 'image':
                        case 'image_grid':
                            $microsite_block->settings->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->image, 'block_images/', 'block_images/', 'json_error');
                            break;

                        case 'heading':
                            $microsite_block->settings->verified_location = '';
                            break;

                        case 'image_slider':
                            $microsite_block->settings->items = (array) $microsite_block->settings->items;
                            foreach($microsite_block->settings->items as $key => $item) {
                                $microsite_block->settings->items[$key]->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->items[$key]->image, 'block_images/', 'block_images/', 'json_error');
                            }

                            break;

                        default:
                            $microsite_block->settings->image = \SeeGap\Uploads::copy_uploaded_file($microsite_block->settings->image, 'block_thumbnail_images/', 'block_thumbnail_images/', 'json_error');
                            break;
                    }

                    /* Database query */
                    db()->insert('microsites_blocks', [
                        'user_id' => $this->user->user_id,
                        'link_id' => $link_id,
                        'type' => $microsite_block->type,
                        'location_url' => $microsite_block->location_url,
                        'settings' => json_encode($microsite_block->settings),
                        'order' => $microsite_block->order,
                        'start_date' => $microsite_block->start_date,
                        'end_date' => $microsite_block->end_date,
                        'is_enabled' => $microsite_block->is_enabled,
                        'datetime' => get_date(),
                    ]);
                }
            }

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.create2'));

            /* Redirect */
            redirect('link/' . $link_id);
        }

        redirect('links');
    }

    // ========================================
    // MICROSITE BLOCK OPERATIONS
    // ========================================

    private function micrositeBlockSubmitForm() {
        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];
        
        /* Get the microsite block */
        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->getOne('microsites_blocks')) {
            Response::json(l('global.error_message.invalid_request'), 'error');
        }

        /* Get the link to verify ownership */
        if(!$link = db()->where('link_id', $microsite_block->link_id)->getOne('links')) {
            Response::json(l('global.error_message.invalid_request'), 'error');
        }

        $microsite_block->settings = json_decode($microsite_block->settings ?? '');
        
        /* Add debug logging to track form submissions */
        debug_log('FORM_SUBMISSION_START', [
            'microsite_block_id' => $microsite_block->microsite_block_id,
            'block_type' => $microsite_block->type,
            'link_id' => $link->link_id,
            'timestamp' => date('Y-m-d H:i:s.u'),
            'ip' => get_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        /* Process form submission based on block type */
        if($microsite_block->type == 'feedback_collector') {
            $this->processFeedbackCollectorSubmission($microsite_block, $link);
        } elseif($microsite_block->type == 'form') {
            $this->processFormSubmission($microsite_block, $link);
        } else {
            debug_log('FORM_SUBMISSION_ERROR', [
                'error' => 'invalid_block_type',
                'block_type' => $microsite_block->type,
                'microsite_block_id' => $microsite_block->microsite_block_id
            ]);
            Response::json(l('global.error_message.invalid_request'), 'error');
        }
    }

    private function processFeedbackCollectorSubmission($microsite_block, $link) {
        /* Collect form data */
        $form_data = [];
        
        /* Process questions if they exist */
        if(isset($microsite_block->settings->questions) && is_array($microsite_block->settings->questions)) {
            foreach($microsite_block->settings->questions as $index => $question) {
                $field_name = 'question_' . $index;
                if(isset($_POST[$field_name])) {
                    $form_data[$question->question] = $_POST[$field_name];
                }
            }
        } else {
            /* Fallback for simple message field */
            if(isset($_POST['message'])) {
                $form_data['message'] = $_POST['message'];
            }
        }

        /* Store the submission */
        $this->storeFormSubmission($microsite_block, $link, $form_data, 'feedback_collector');
        
        /* Send notifications if configured */
        $this->sendFormNotifications($microsite_block, $link, $form_data, 'feedback_collector');
        
        /* Return success response */
        $success_message = $microsite_block->settings->success_text ?? 'Thank you for your feedback!';
        $response_data = ['message' => $success_message];
        
        if(!empty($microsite_block->settings->thank_you_url)) {
            $response_data['thank_you_url'] = $microsite_block->settings->thank_you_url;
        }
        
        Response::json($success_message, 'success', ['details' => $response_data]);
    }

    private function processFormSubmission($microsite_block, $link) {
        /* Process custom form submissions */
        $form_data = [];
        
        /* Process questions if they exist */
        if(isset($microsite_block->settings->questions) && is_array($microsite_block->settings->questions)) {
            foreach($microsite_block->settings->questions as $index => $question) {
                $field_name = 'question_' . $index;
                
                /* Handle file uploads for receipt_upload questions */
                if($question->type === 'receipt_upload') {
                    $form_data[$question->question] = $this->handleReceiptUpload($index, $question, $microsite_block, $link);
                } elseif(isset($_POST[$field_name])) {
                    $form_data[$question->question] = $_POST[$field_name];
                }
            }
        }

        /* Store the submission */
        $this->storeFormSubmission($microsite_block, $link, $form_data, 'form');
        
        /* Send notifications if configured */
        $this->sendFormNotifications($microsite_block, $link, $form_data, 'form');
        
        /* Return success response */
        $success_message = $microsite_block->settings->success_text ?? l('global.success_message.basic');
        $response_data = ['message' => $success_message];
        
        if(!empty($microsite_block->settings->thank_you_url)) {
            $response_data['thank_you_url'] = $microsite_block->settings->thank_you_url;
        }
        
        Response::json($success_message, 'success', ['details' => $response_data]);
    }

    /**
     * Handle receipt upload for form submissions
     */
    private function handleReceiptUpload($question_index, $question, $microsite_block, $link) {
        $field_name = 'question_' . $question_index;
        $uploaded_files = [];
        
        /* Check if files were uploaded */
        if (!isset($_FILES[$field_name]) || empty($_FILES[$field_name]['name'])) {
            return [
                'response' => 'No files uploaded',
                'files' => []
            ];
        }
        
        /* Get form owner (user who created the form) */
        $form_owner = db()->where('link_id', $link->link_id)->getOne('links', ['user_id']);
        if (!$form_owner) {
            error_log("Could not find form owner for link_id: {$link->link_id}");
            return [
                'response' => 'Error: Could not process upload',
                'files' => []
            ];
        }
        
        /* Create upload directory structure */
        $upload_base_path = UPLOADS_PATH . 'form_attachments/';
        $user_path = $upload_base_path . $form_owner->user_id . '/';
        $link_path = $user_path . $link->link_id . '/';
        
        /* Create directories if they don't exist */
        if (!is_dir($upload_base_path)) mkdir($upload_base_path, 0755, true);
        if (!is_dir($user_path)) mkdir($user_path, 0755, true);
        if (!is_dir($link_path)) mkdir($link_path, 0755, true);
        
        /* Handle multiple files */
        $files = $_FILES[$field_name];
        $file_count = is_array($files['name']) ? count($files['name']) : 1;
        
        for ($i = 0; $i < $file_count; $i++) {
            $file_info = [
                'name' => is_array($files['name']) ? $files['name'][$i] : $files['name'],
                'type' => is_array($files['type']) ? $files['type'][$i] : $files['type'],
                'tmp_name' => is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'],
                'error' => is_array($files['error']) ? $files['error'][$i] : $files['error'],
                'size' => is_array($files['size']) ? $files['size'][$i] : $files['size']
            ];
            
            /* Skip if no file or error */
            if ($file_info['error'] !== UPLOAD_ERR_OK || empty($file_info['name'])) {
                continue;
            }
            
            /* Validate file */
            $validation_result = $this->validateReceiptFile($file_info, $question);
            if ($validation_result !== true) {
                error_log("File validation failed: " . $validation_result);
                continue;
            }
            
            /* Generate secure filename */
            $file_extension = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
            $secure_filename = hash('sha256', $form_owner->user_id . '_' . $link->link_id . '_' . $file_info['name'] . '_' . time() . '_' . $i) . '.' . $file_extension;
            $full_path = $link_path . $secure_filename;
            
            /* Move uploaded file */
            if (move_uploaded_file($file_info['tmp_name'], $full_path)) {
                $uploaded_files[] = [
                    'file_id' => hash('sha256', $secure_filename . time()),
                    'original_name' => $file_info['name'],
                    'secure_filename' => $secure_filename,
                    'size' => $file_info['size'],
                    'mime_type' => $file_info['type'],
                    'uploaded_at' => date('Y-m-d H:i:s'),
                    'form_owner_id' => $form_owner->user_id,
                    'link_id' => $link->link_id,
                    'path' => 'form_attachments/' . $form_owner->user_id . '/' . $link->link_id . '/' . $secure_filename
                ];
                
                error_log("Successfully uploaded file: {$file_info['name']} as {$secure_filename}");
            } else {
                error_log("Failed to move uploaded file: {$file_info['name']}");
            }
        }
        
        /* Prepare response */
        $file_count = count($uploaded_files);
        $response_text = $file_count > 0 ? 
            ($file_count === 1 ? '1 file uploaded' : $file_count . ' files uploaded') : 
            'No files uploaded';
        
        /* Handle AI analysis if enabled */
        $ai_analysis = null;
        if (($question->options->ai_analysis_enabled ?? false) && !empty($uploaded_files)) {
            $ai_analysis = $this->processReceiptAIAnalysis($uploaded_files, $question);
        }
        
        return [
            'response' => $response_text,
            'files' => $uploaded_files,
            'ai_analysis' => $ai_analysis
        ];
    }
    
    /**
     * Validate uploaded receipt file
     */
    private function validateReceiptFile($file_info, $question) {
        /* Check file size */
        $max_size = ($question->options->max_file_size ?? 10) * 1024 * 1024; // Convert MB to bytes
        if ($file_info['size'] > $max_size) {
            return "File size exceeds limit of " . ($question->options->max_file_size ?? 10) . "MB";
        }
        
        /* Check file type */
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/heic', 'application/pdf'];
        if (!in_array($file_info['type'], $allowed_types)) {
            return "File type not allowed. Allowed types: JPG, PNG, GIF, WebP, HEIC, PDF";
        }
        
        /* Check file extension */
        $file_extension = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'pdf'];
        if (!in_array($file_extension, $allowed_extensions)) {
            return "File extension not allowed";
        }
        
        /* Additional security checks */
        if (!is_uploaded_file($file_info['tmp_name'])) {
            return "Invalid file upload";
        }
        
        return true;
    }
    
    /**
     * Process AI analysis for receipt images
     */
    private function processReceiptAIAnalysis($uploaded_files, $question) {
        /* Check if AI analysis is enabled and configured */
        if (!($question->options->ai_analysis_enabled ?? false)) {
            return null;
        }
        
        /* For now, return a placeholder - this will be integrated with the existing AI system */
        return [
            'status' => 'pending',
            'message' => 'AI analysis will be processed shortly',
            'providers' => $question->options->ai_providers ?? ['openai'],
            'extract_items' => $question->options->extract_items ?? false,
            'extract_totals' => $question->options->extract_totals ?? false,
            'extract_merchant' => $question->options->extract_merchant ?? false,
            'extract_date' => $question->options->extract_date ?? false,
            'extract_payment_method' => $question->options->extract_payment_method ?? false,
            'extract_tax' => $question->options->extract_tax ?? false
        ];
    }

    private function storeFormSubmission($microsite_block, $link, $form_data, $form_type) {
        /* Create form_submissions table if it doesn't exist */
        $this->createFormSubmissionsTable();
        
        /* Generate a unique hash for this submission to prevent duplicates */
        $submission_hash = hash('sha256', 
            $microsite_block->microsite_block_id . 
            $link->link_id . 
            get_ip() . 
            json_encode($form_data) . 
            ($_SERVER['HTTP_USER_AGENT'] ?? '') .
            date('Y-m-d H:i') // Include minute precision to allow multiple submissions per minute but prevent rapid duplicates
        );
        
        /* Check if this exact submission already exists within the last 5 minutes */
        $recent_submission = db()
            ->where('microsite_block_id', $microsite_block->microsite_block_id)
            ->where('ip', get_ip())
            ->where('submitted_at', \SeeGap\Date::$date, '>=')
            ->where('submitted_at', date('Y-m-d H:i:s', strtotime('-5 minutes')), '>=')
            ->getOne('form_submissions', ['form_submission_id', 'responses']);
        
        if ($recent_submission) {
            /* Compare the form data to see if it's identical */
            $recent_data = json_decode($recent_submission->responses, true);
            if ($recent_data === $form_data) {
                debug_log('FORM_SUBMISSION_DUPLICATE_PREVENTED', [
                    'microsite_block_id' => $microsite_block->microsite_block_id,
                    'ip' => get_ip(),
                    'existing_submission_id' => $recent_submission->form_submission_id,
                    'submission_hash' => $submission_hash
                ]);
                
                /* Return the existing submission ID instead of creating a duplicate */
                return $recent_submission->form_submission_id;
            }
        }
        
        /* Prepare submission data */
        $submission_data = [
            'microsite_block_id' => $microsite_block->microsite_block_id,
            'link_id' => $link->link_id,
            'form_type' => $form_type,
            'responses' => json_encode($form_data),
            'ip' => get_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'submitted_at' => \SeeGap\Date::$date
        ];
        
        /* Insert into database */
        try {
            $submission_id = db()->insert('form_submissions', $submission_data);
            
            /* Log the submission for debugging */
            debug_log('FORM_SUBMISSION_STORED', [
                'submission_id' => $submission_id,
                'microsite_block_id' => $microsite_block->microsite_block_id,
                'form_type' => $form_type,
                'ip' => get_ip(),
                'submission_hash' => $submission_hash,
                'data_size' => strlen(json_encode($form_data))
            ]);
            
            return $submission_id;
        } catch(Exception $e) {
            /* Log error and fallback to error log only */
            debug_log('FORM_SUBMISSION_ERROR', [
                'error' => $e->getMessage(),
                'microsite_block_id' => $microsite_block->microsite_block_id,
                'form_type' => $form_type,
                'submission_hash' => $submission_hash
            ]);
            error_log("Failed to save form submission to database: " . $e->getMessage());
            error_log("Form submission for block {$microsite_block->microsite_block_id}: " . json_encode($form_data));
            return false;
        }
    }

    private function createFormSubmissionsTable() {
        /* Check if table exists, if not create it */
        try {
            $table_exists = db()->rawQuery("SHOW TABLES LIKE 'form_submissions'")->num_rows > 0;
            
            if(!$table_exists) {
                db()->rawQuery("
                    CREATE TABLE `form_submissions` (
                        `form_submission_id` int(11) NOT NULL AUTO_INCREMENT,
                        `microsite_block_id` int(11) NOT NULL,
                        `link_id` int(11) NOT NULL,
                        `form_type` varchar(32) NOT NULL DEFAULT 'custom',
                        `responses` longtext,
                        `metadata` longtext,
                        `ip` varchar(64) DEFAULT NULL,
                        `user_agent` text,
                        `submitted_at` datetime NOT NULL,
                        PRIMARY KEY (`form_submission_id`),
                        KEY `microsite_block_id` (`microsite_block_id`),
                        KEY `link_id` (`link_id`),
                        KEY `submitted_at` (`submitted_at`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
                
                error_log("Created form_submissions table");
            }
        } catch(Exception $e) {
            error_log("Failed to create form_submissions table: " . $e->getMessage());
        }
    }

    private function sendFormNotifications($microsite_block, $link, $form_data, $form_type) {
        /* Send email notifications if configured */
        if(!empty($microsite_block->settings->email_notification)) {
            /* Send email notification */
            $subject = "New {$form_type} submission from {$link->url}";
            $message = "New form submission:\n\n" . print_r($form_data, true);
            
            /* You would implement email sending here */
            /* mail($microsite_block->settings->email_notification, $subject, $message); */
        }
        
        /* Send webhook if configured */
        if(!empty($microsite_block->settings->webhook_url)) {
            /* Send webhook */
            $webhook_data = [
                'form_type' => $form_type,
                'link_url' => $link->url,
                'submission_data' => $form_data,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            /* You would implement webhook sending here */
            /* $this->send_webhook($microsite_block->settings->webhook_url, $webhook_data); */
        }
    }

    private function micrositeBlockIsEnabledToggle() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        // Handle both 'id' and 'microsite_block_id' parameters
        $microsite_block_id = isset($_POST['microsite_block_id']) ? 
            (int) $_POST['microsite_block_id'] : 
            (int) $_POST['id'];

        /* Get the current status */
        $microsite_block = db()->where('microsite_block_id', $microsite_block_id)->where('user_id', $this->user->user_id)->getOne('microsites_blocks', ['microsite_block_id', 'link_id', 'is_enabled']);

        if($microsite_block) {
            $new_is_enabled = (int) !$microsite_block->is_enabled;

            db()->where('microsite_block_id', $microsite_block->microsite_block_id)->update('microsites_blocks', ['is_enabled' => $new_is_enabled]);

            /* Clear the cache */
            cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

            Response::json('', 'success');
        }
    }

    private function micrositeBlockDuplicate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('links');
        }

        /* Get the link data */
        $microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks');

        if(!$microsite_block) {
            redirect('links');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            $microsite_block->settings = json_decode($microsite_block->settings ?? '');

            $settings = json_encode($microsite_block->settings ?? '');

            /* Database query */
            db()->insert('microsites_blocks', [
                'user_id' => $this->user->user_id,
                'link_id' => $microsite_block->link_id,
                'type' => $microsite_block->type,
                'location_url' => $microsite_block->location_url,
                'settings' => $settings,
                'order' => $microsite_block->order + 1,
                'start_date' => $microsite_block->start_date,
                'end_date' => $microsite_block->end_date,
                'is_enabled' => $microsite_block->is_enabled,
                'datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.create2'));

            /* Redirect */
            redirect('link/' . $microsite_block->link_id . '?tab=blocks');
        }

        redirect('links');
    }

    private function micrositeBlockOrder() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        debug_log('MICROSITE_BLOCK_ORDER_START', [
            'user_id' => $this->user->user_id,
            'post_data' => $_POST['microsite_blocks'] ?? 'not_set',
            'timestamp' => date('Y-m-d H:i:s.u')
        ]);

        if(isset($_POST['microsite_blocks']) && is_array($_POST['microsite_blocks'])) {
            $updates_made = [];
            $link_id = null;
            
            foreach($_POST['microsite_blocks'] as $link) {
                if(!isset($link['microsite_block_id']) || !isset($link['order'])) {
                    debug_log('MICROSITE_BLOCK_ORDER_SKIP', [
                        'reason' => 'missing_required_fields',
                        'block_data' => $link
                    ]);
                    continue;
                }

                $microsite_block = db()->where('microsite_block_id', $link['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks', ['link_id', '`order`']);

                if(!$microsite_block) {
                    debug_log('MICROSITE_BLOCK_ORDER_SKIP', [
                        'reason' => 'block_not_found',
                        'microsite_block_id' => $link['microsite_block_id'],
                        'user_id' => $this->user->user_id
                    ]);
                    continue;
                }

                $link['microsite_block_id'] = (int) $link['microsite_block_id'];
                $link['order'] = (int) $link['order'];
                $link_id = $microsite_block->link_id;

                debug_log('MICROSITE_BLOCK_ORDER_UPDATE', [
                    'microsite_block_id' => $link['microsite_block_id'],
                    'old_order' => $microsite_block->order,
                    'new_order' => $link['order'],
                    'link_id' => $link_id
                ]);

                /* Update the link order */
                $result = db()->where('microsite_block_id', $link['microsite_block_id'])->where('user_id', $this->user->user_id)->update('microsites_blocks', ['order' => $link['order']]);
                
                $updates_made[] = [
                    'microsite_block_id' => $link['microsite_block_id'],
                    'new_order' => $link['order'],
                    'update_result' => $result
                ];
            }

            debug_log('MICROSITE_BLOCK_ORDER_UPDATES_COMPLETE', [
                'total_updates' => count($updates_made),
                'updates_made' => $updates_made,
                'link_id' => $link_id
            ]);

            if($link_id) {
                /* Clear the cache */
                cache()->deleteItem('microsite_blocks?link_id=' . $link_id);
                debug_log('MICROSITE_BLOCK_ORDER_CACHE_CLEARED', [
                    'cache_key' => 'microsite_blocks?link_id=' . $link_id,
                    'link_id' => $link_id
                ]);
                
                /* Force database commit */
                db()->rawQuery('COMMIT');
                
                debug_log('MICROSITE_BLOCK_ORDER_DB_COMMIT', [
                    'link_id' => $link_id,
                    'timestamp' => date('Y-m-d H:i:s.u')
                ]);
            }
        }

        debug_log('MICROSITE_BLOCK_ORDER_END', [
            'user_id' => $this->user->user_id,
            'timestamp' => date('Y-m-d H:i:s.u')
        ]);

        Response::json('', 'success');
    }

    private function micrositeBlockCreate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $this->microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php';

        /* Check for available microsite blocks */
        if(isset($_POST['block_type']) && array_key_exists($_POST['block_type'], $this->microsite_blocks)) {
            $_POST['block_type'] = query_clean($_POST['block_type']);
            $_POST['link_id'] = (int) $_POST['link_id'];

            /* Route to individual block handlers */
            $this->routeToBlockHandler($_POST['block_type'], 'create');
        }

        Response::json('', 'success');
    }

    private function micrositeBlockUpdate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $this->microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php';

        if(!empty($_POST)) {
            /* Check for available microsite blocks */
            if(isset($_POST['block_type']) && array_key_exists($_POST['block_type'], $this->microsite_blocks)) {
                $_POST['block_type'] = query_clean($_POST['block_type']);

                /* Route to individual block handlers */
                $this->routeToBlockHandler($_POST['block_type'], 'update');
            }
        }

        die();
    }

    /**
     * Route requests to individual block handlers
     */
    private function routeToBlockHandler($block_type, $action) {
        /* Map block types to their handler classes */
        $block_handlers = [
            'link' => 'LinkBlock',
            'text' => 'TextBlock',
            'form' => 'FormBlock',
            'big_link' => 'BigLinkBlock',
            'image' => 'ImageBlock',
            'divider' => 'DividerBlock',
            'socials' => 'SocialsBlock',
            'youtube' => 'YoutubeBlock',
            'countdown' => 'CountdownBlock',
            'alert' => 'AlertBlock',
            'accordion' => 'AccordionBlock',
            'instagram_media' => 'InstagramMediaBlock',
            'twitter_tweet' => 'TwitterTweetBlock',
            'tiktok_video' => 'TiktokVideoBlock',
            'facebook' => 'FacebookBlock',
            'telegram' => 'TelegramBlock',
            'cover' => 'CoverBlock',
            'image_grid' => 'ImageGridBlock',
            'review' => 'ReviewBlock',
            'cta' => 'CtaBlock',
            'share' => 'ShareBlock',
            'youtube_feed' => 'YoutubeFeedBlock',
            'image_slider' => 'ImageSliderBlock',
            'threads' => 'ThreadsBlock',
            'twitter_video' => 'TwitterVideoBlock',
            'twitter_profile' => 'TwitterProfileBlock',
        ];

        /* Check if we have a handler for this block type */
        if(!isset($block_handlers[$block_type])) {
            Response::json(l('global.error_message.invalid_request'), 'error');
            return;
        }

        $handler_class = $block_handlers[$block_type];
        $handler_file = APP_PATH . 'controllers/microsite-blocks/blocks/' . $handler_class . '.php';

        /* Check if the handler file exists */
        if(!file_exists($handler_file)) {
            Response::json(l('global.error_message.invalid_request'), 'error');
            return;
        }

        /* Include the BaseBlockHandler and interface first */
        require_once APP_PATH . 'controllers/microsite-blocks/interfaces/BlockHandlerInterface.php';
        require_once APP_PATH . 'controllers/microsite-blocks/BaseBlockHandler.php';
        
        /* Include and instantiate the handler */
        require_once $handler_file;
        $full_class_name = '\\SeeGap\\Controllers\\MicrositeBlocks\\Blocks\\' . $handler_class;
        
        if(!class_exists($full_class_name)) {
            Response::json(l('global.error_message.invalid_request'), 'error');
            return;
        }

        $handler = new $full_class_name();
        
        /* Set required properties from this controller */
        $handler->user = $this->user;
        $handler->total_microsite_blocks = $this->total_microsite_blocks;

        /* Execute the action */
        if(method_exists($handler, $action)) {
            $handler->$action($block_type);
        } else {
            Response::json(l('global.error_message.invalid_request'), 'error');
        }
    }

    private function micrositeBlockDelete() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];

        /* Check for possible errors */
        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        (new \SeeGap\Models\MicrositeBlock())->delete($microsite_block->microsite_block_id);

        Response::json(l('global.success_message.delete2'), 'success', ['url' => url('link/' . $microsite_block->link_id . '?tab=blocks')]);
    }

    // ========================================
    // GS1 LINK OPERATIONS
    // ========================================

    private function gs1LinkIsEnabledToggle() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['gs1_link_id'] = (int) $_POST['gs1_link_id'];

        /* Get the current status */
        $gs1_link = db()->where('gs1_link_id', $_POST['gs1_link_id'])->where('user_id', $this->user->user_id)->getOne('gs1_links', ['gs1_link_id', 'is_enabled']);

        if($gs1_link) {
            $new_is_enabled = (int) !$gs1_link->is_enabled;

            db()->where('gs1_link_id', $gs1_link->gs1_link_id)->update('gs1_links', ['is_enabled' => $new_is_enabled]);

            /* Clear the cache */
            cache()->deleteItem('gs1_link?gs1_link_id=' . $_POST['gs1_link_id']);
            cache()->deleteItemsByTag('gs1_link_id=' . $_POST['gs1_link_id']);

            Response::json(l('global.success_message.create2'), 'success');
        }
    }

    private function gs1LinkDelete() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['gs1_link_id'] = (int) $_POST['gs1_link_id'];

        /* Check for possible errors */
        if(!$gs1_link = db()->where('gs1_link_id', $_POST['gs1_link_id'])->where('user_id', $this->user->user_id)->getOne('gs1_links', ['gs1_link_id'])) {
            die();
        }

        (new \SeeGap\Models\Gs1Link())->delete($gs1_link->gs1_link_id);

        Response::json(l('global.success_message.delete2'), 'success', ['url' => url('gs1-links')]);
    }

    private function gs1LinkDuplicate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.gs1_links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('gs1-links');
        }

        $_POST['gs1_link_id'] = (int) $_POST['gs1_link_id'];

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('gs1-links');
        }

        /* Get the gs1_link data */
        $gs1_link = db()->where('gs1_link_id', $_POST['gs1_link_id'])->where('user_id', $this->user->user_id)->getOne('gs1_links');

        if(!$gs1_link) {
            redirect('gs1-links');
        }

        /* Make sure that the user didn't exceed the limit */
        $user_total_gs1_links = database()->query("SELECT COUNT(*) AS `total` FROM `gs1_links` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;
        if($this->user->plan_settings->gs1_links_limit != -1 && $user_total_gs1_links >= $this->user->plan_settings->gs1_links_limit) {
            Alerts::add_error(l('global.info_message.plan_feature_limit'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Duplicate the gs1_link */
            $gs1_link->settings = json_decode($gs1_link->settings ?? '');

            /* Generate a new GTIN by incrementing the last digit (keeping check digit valid) */
            $new_gtin = $gs1_link->gtin;
            $base_gtin = substr($new_gtin, 0, 12); // Get first 12 digits
            $sequence = (int) substr($base_gtin, -1); // Get last digit of base
            
            // Try incrementing sequence until we find an available GTIN
            $attempts = 0;
            do {
                $sequence = ($sequence + 1) % 10;
                $new_base = substr($base_gtin, 0, 11) . $sequence;
                $new_gtin = \SeeGap\Helpers\Gs1::calculate_gtin_check_digit($new_base);
                $attempts++;
                
                // If we've tried all possibilities, generate a random GTIN
                if($attempts >= 10) {
                    $new_gtin = \SeeGap\Helpers\Gs1::generate_random_gtin();
                    break;
                }
            } while (db()->where('gtin', $new_gtin)->where('domain_id', $gs1_link->domain_id)->getValue('gs1_links', 'gs1_link_id'));

            /* Database query */
            $gs1_link_id = db()->insert('gs1_links', [
                'user_id' => $this->user->user_id,
                'project_id' => $gs1_link->project_id,
                'domain_id' => $gs1_link->domain_id,
                'pixels_ids' => $gs1_link->pixels_ids,
                'gtin' => $new_gtin,
                'target_url' => $gs1_link->target_url,
                'title' => $gs1_link->title . ' (Copy)',
                'description' => $gs1_link->description,
                'settings' => json_encode($gs1_link->settings),
                'is_enabled' => $gs1_link->is_enabled,
                'datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItem('gs1_links_total?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.create2'));

            /* Redirect */
            redirect('gs1-link-manager/edit/' . $gs1_link_id);
        }

        redirect('gs1-links');
    }

    // ========================================
    // PRODUCT OPERATIONS
    // ========================================

    private function productCreate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.products')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        debug_log('PRODUCT_CREATE_ATTEMPT', [
            'user_id' => $this->user->user_id,
            'post_data' => $_POST,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if(($this->user->plan_settings->products_limit ?? -1) != -1 && $total_rows >= ($this->user->plan_settings->products_limit ?? 0)) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Clean and validate input */
        $gtin = trim($_POST['gtin'] ?? '');
        $product_name = trim($_POST['product_name'] ?? '');
        $brand_name = trim($_POST['brand_name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $project_id = (int) ($_POST['project_id'] ?? 0);
        $target_url = trim($_POST['target_url'] ?? '');
        $description = trim($_POST['description'] ?? $_POST['product_description'] ?? '');

        /* Validation */
        $errors = [];

        if(empty($gtin)) {
            $errors['gtin'] = l('products.error_message.gtin_required');
        } elseif(!preg_match('/^[0-9]{8,14}$/', $gtin)) {
            $errors['gtin'] = l('products.error_message.gtin_invalid_format');
        } else {
            /* Check GTIN uniqueness */
            if(db()->where('gtin', $gtin)->where('user_id', $this->user->user_id)->has('products')) {
                $errors['gtin'] = l('products.error_message.gtin_exists');
            }
        }

        // Check required fields based on admin settings
        if((settings()->products->require_product_name ?? true) && empty($product_name)) {
            $errors['product_name'] = l('products.error_message.product_name_required');
        }

        if((settings()->products->require_brand_name ?? false) && empty($brand_name)) {
            $errors['brand_name'] = l('products.error_message.brand_name_required');
        }

        if((settings()->products->require_category ?? false) && empty($category)) {
            $errors['category'] = l('products.error_message.category_required');
        }

        if(!empty($target_url) && !filter_var($target_url, FILTER_VALIDATE_URL)) {
            $errors['target_url'] = l('products.error_message.target_url_invalid');
        }

        /* Check if project exists and belongs to user */
        if($project_id > 0) {
            if(!db()->where('project_id', $project_id)->where('user_id', $this->user->user_id)->has('projects')) {
                $errors['project_id'] = l('projects.error_message.not_found');
            }
        }

        if(!empty($errors)) {
            debug_log('PRODUCT_CREATE_VALIDATION_FAILED', [
                'user_id' => $this->user->user_id,
                'errors' => $errors
            ]);
            Response::json(l('global.error_message.basic'), 'error', ['field_errors' => $errors]);
        }

        /* Prepare product data */
        $product_data = [
            'user_id' => $this->user->user_id,
            'project_id' => $project_id ?: null,
            'gtin' => $gtin,
            'brand_name' => $brand_name,
            'product_name' => $product_name,
            'product_description' => $description,
            'category' => $category,
            'target_url' => $target_url ?: null,
            'settings' => json_encode([]),
            'is_enabled' => 1
        ];

        try {
            $product_model = new \SeeGap\Models\Product();
            
            debug_log('PRODUCT_CREATE_BEFORE_MODEL', [
                'user_id' => $this->user->user_id,
                'product_data' => $product_data,
                'settings_check' => [
                    'require_product_name' => settings()->products->require_product_name ?? 'not_set',
                    'require_brand_name' => settings()->products->require_brand_name ?? 'not_set',
                    'gtin_validation_is_enabled' => settings()->products->gtin_validation_is_enabled ?? 'not_set',
                    'gtin_format_validation' => settings()->products->gtin_format_validation ?? 'not_set'
                ]
            ]);
            
            $result = $product_model->create_product($product_data);

            if(is_array($result) && isset($result['error'])) {
                // Handle specific GTIN validation errors
                debug_log('PRODUCT_CREATE_GTIN_VALIDATION_FAILED', [
                    'user_id' => $this->user->user_id,
                    'error_type' => $result['error'],
                    'error_message' => $result['message'],
                    'gtin' => $gtin
                ]);
                
                $field_errors = ['gtin' => $result['message']];
                Response::json(l('products.error_message.creation_failed'), 'error', ['field_errors' => $field_errors]);
            } elseif($result) {
                debug_log('PRODUCT_CREATE_SUCCESS', [
                    'user_id' => $this->user->user_id,
                    'product_id' => $result,
                    'gtin' => $gtin,
                    'product_name' => $product_name
                ]);

                Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $product_name . '</strong>'), 'success', [
                    'url' => url('product-update/' . $result)
                ]);
            } else {
                debug_log('PRODUCT_CREATE_FAILED', [
                    'user_id' => $this->user->user_id,
                    'product_data' => $product_data,
                    'possible_reasons' => [
                        'empty_gtin_after_cleaning',
                        'required_field_validation_failed',
                        'gtin_already_exists',
                        'database_insert_failed'
                    ]
                ]);
                
                // Generic failure - could be GTIN already exists or other issue
                $field_errors = ['gtin' => l('products.error_message.gtin_invalid_or_exists')];
                Response::json(l('products.error_message.creation_failed'), 'error', ['field_errors' => $field_errors]);
            }
        } catch(\Exception $e) {
            debug_log('PRODUCT_CREATE_EXCEPTION', [
                'user_id' => $this->user->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Response::json(l('global.error_message.basic') . ': ' . $e->getMessage(), 'error');
        }
    }

    private function productIsEnabledToggle() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.products')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(empty($_POST)) {
            die();
        }

        $product_id = (int) query_clean($_POST['product_id']);

        if(!\SeeGap\Csrf::check()) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        /* Get the product details */
        if(!$product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products')) {
            die();
        }

        $new_is_enabled = (int) !$product->is_enabled;

        /* Database query */
        db()->where('product_id', $product_id)->update('products', [
            'is_enabled' => $new_is_enabled,
            'last_datetime' => \SeeGap\Date::$date
        ]);

        /* Clear the cache */
        cache()->deleteItem('product?product_id=' . $product_id);
        cache()->deleteItemsByTag('product_id=' . $product_id);

        Response::json('', 'success', [
            'is_enabled' => $new_is_enabled
        ]);
    }

    private function productDuplicate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.products')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(empty($_POST)) {
            die();
        }

        $product_id = (int) query_clean($_POST['product_id']);

        if(!\SeeGap\Csrf::check()) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if(($this->user->plan_settings->products_limit ?? -1) != -1 && $total_rows >= ($this->user->plan_settings->products_limit ?? 0)) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Get the product details */
        if(!$product = db()->where('product_id', $product_id)->where('user_id', $this->user->user_id)->getOne('products')) {
            die();
        }

        /* Generate a new unique GTIN by appending a suffix */
        $base_gtin = preg_replace('/[^0-9]/', '', $product->gtin);
        $new_gtin = $base_gtin;
        $suffix = 1;
        
        while(db()->where('gtin', $new_gtin)->where('user_id', $this->user->user_id)->has('products')) {
            $new_gtin = $base_gtin . $suffix;
            $suffix++;
        }

        /* Duplicate the product */
        $product_data = [
            'user_id' => $this->user->user_id,
            'project_id' => $product->project_id,
            'gtin' => $new_gtin,
            'brand_name' => $product->brand_name,
            'product_name' => $product->product_name . ' (Copy)',
            'product_description' => $product->product_description,
            'category' => $product->category,
            'subcategory' => $product->subcategory,
            'manufacturer' => $product->manufacturer,
            'country_of_origin' => $product->country_of_origin,
            'net_weight' => $product->net_weight,
            'dimensions' => $product->dimensions,
            'ingredients' => $product->ingredients,
            'nutritional_info' => $product->nutritional_info,
            'allergen_info' => $product->allergen_info,
            'certifications' => $product->certifications,
            'product_images' => json_decode($product->product_images ?? '[]', true),
            'packaging_info' => $product->packaging_info,
            'storage_instructions' => $product->storage_instructions,
            'usage_instructions' => $product->usage_instructions,
            'target_url' => $product->target_url,
            'gs1_link_id' => null, // Don't duplicate GS1 link
            'settings' => json_decode($product->settings ?? '{}', true),
            'is_enabled' => 0 // Start disabled
        ];

        $product_model = new \SeeGap\Models\Product();
        $new_product_id = $product_model->create_product($product_data);

        if($new_product_id) {
            Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $product_data['product_name'] . '</strong>'), 'success', [
                'url' => url('product-update/' . $new_product_id)
            ]);
        } else {
            Response::json(l('products.error_message.creation_failed'), 'error');
        }
    }
}
