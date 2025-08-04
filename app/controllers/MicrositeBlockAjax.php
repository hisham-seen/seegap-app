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
use SeeGap\Response;
use Unirest\Request;

defined('SEEGAP') || die();

class MicrositeBlockAjax extends Controller {
    public $microsite_blocks = null;
    public $total_microsite_blocks = 0;

    public function index() {
        /* Allow public form submissions without authentication */
        if(isset($_POST['request_type']) && $_POST['request_type'] === 'submit_form') {
            /* Skip authentication for public form submissions */
        } else {
            \SeeGap\Authentication::guard();
        }

        if(!empty($_POST) && (\SeeGap\Csrf::check('token') || \SeeGap\Csrf::check('global_token') || (isset($_POST['request_type']) && $_POST['request_type'] === 'submit_form')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Duplicate link */
                case 'duplicate': $this->duplicate(); break;

                /* Order links */
                case 'order': $this->order(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

                /* Submit form */
                case 'submit_form': $this->submit_form(); break;

            }

        }

        die();
    }

    private function submit_form() {
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
        
        /* Process form submission based on block type */
        if($microsite_block->type == 'feedback_collector') {
            $this->process_feedback_collector_submission($microsite_block, $link);
        } elseif($microsite_block->type == 'form') {
            $this->process_form_submission($microsite_block, $link);
        } else {
            Response::json(l('global.error_message.invalid_request'), 'error');
        }
    }

    private function process_feedback_collector_submission($microsite_block, $link) {
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
        $this->store_form_submission($microsite_block, $link, $form_data, 'feedback_collector');
        
        /* Send notifications if configured */
        $this->send_form_notifications($microsite_block, $link, $form_data, 'feedback_collector');
        
        /* Return success response */
        $success_message = $microsite_block->settings->success_text ?? 'Thank you for your feedback!';
        $response_data = ['message' => $success_message];
        
        if(!empty($microsite_block->settings->thank_you_url)) {
            $response_data['thank_you_url'] = $microsite_block->settings->thank_you_url;
        }
        
        Response::json($success_message, 'success', ['details' => $response_data]);
    }

    private function process_form_submission($microsite_block, $link) {
        /* Process custom form submissions */
        $form_data = [];
        
        /* Process questions if they exist */
        if(isset($microsite_block->settings->questions) && is_array($microsite_block->settings->questions)) {
            foreach($microsite_block->settings->questions as $index => $question) {
                $field_name = 'question_' . $index;
                if(isset($_POST[$field_name])) {
                    $form_data[$question->question] = $_POST[$field_name];
                }
            }
        }

        /* Store the submission */
        $this->store_form_submission($microsite_block, $link, $form_data, 'form');
        
        /* Send notifications if configured */
        $this->send_form_notifications($microsite_block, $link, $form_data, 'form');
        
        /* Return success response */
        $success_message = $microsite_block->settings->success_text ?? l('global.success_message.basic');
        $response_data = ['message' => $success_message];
        
        if(!empty($microsite_block->settings->thank_you_url)) {
            $response_data['thank_you_url'] = $microsite_block->settings->thank_you_url;
        }
        
        Response::json($success_message, 'success', ['details' => $response_data]);
    }

    private function store_form_submission($microsite_block, $link, $form_data, $form_type) {
        /* Create form_submissions table if it doesn't exist */
        $this->create_form_submissions_table();
        
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
            error_log("Form submission saved to database - ID: {$submission_id}, Block: {$microsite_block->microsite_block_id}, Data: " . json_encode($form_data));
            
            return $submission_id;
        } catch(Exception $e) {
            /* Log error and fallback to error log only */
            error_log("Failed to save form submission to database: " . $e->getMessage());
            error_log("Form submission for block {$microsite_block->microsite_block_id}: " . json_encode($form_data));
            return false;
        }
    }

    private function create_form_submissions_table() {
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

    private function send_form_notifications($microsite_block, $link, $form_data, $form_type) {
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

    private function is_enabled_toggle() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];

        /* Get the current status */
        $microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks', ['microsite_block_id', 'link_id', 'is_enabled']);

        if($microsite_block) {
            $new_is_enabled = (int) !$microsite_block->is_enabled;

            db()->where('microsite_block_id', $microsite_block->microsite_block_id)->update('microsites_blocks', ['is_enabled' => $new_is_enabled]);

            /* Clear the cache */
            cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

            Response::json('', 'success');
        }
    }

    public function duplicate() {
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

    private function order() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(isset($_POST['microsite_blocks']) && is_array($_POST['microsite_blocks'])) {
            foreach($_POST['microsite_blocks'] as $link) {
                if(!isset($link['microsite_block_id']) || !isset($link['order'])) {
                    continue;
                }

                $microsite_block = db()->where('microsite_block_id', $link['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks', ['link_id']);

                if(!$microsite_block) {
                    continue;
                }

                $link['microsite_block_id'] = (int) $link['microsite_block_id'];
                $link['order'] = (int) $link['order'];

                /* Update the link order */
                db()->where('microsite_block_id', $link['microsite_block_id'])->where('user_id', $this->user->user_id)->update('microsites_blocks', ['order' => $link['order']]);
            }

            if(isset($microsite_block)) {
                /* Clear the cache */
                cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);
            }
        }

        Response::json('', 'success');
    }

    private function create() {
        /* Enhanced debug logging */
        error_log("=== MicrositeBlockAjax CREATE START ===");
        error_log("POST data received: " . json_encode($_POST));
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("User ID: " . ($this->user->user_id ?? 'unknown'));
        
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.microsites_blocks')) {
            error_log("Team access denied for create.microsites_blocks");
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $this->microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php';
        error_log("Available microsite blocks: " . implode(', ', array_keys($this->microsite_blocks)));
        error_log("Requested block_type: " . ($_POST['block_type'] ?? 'not_set'));

        /* Check for available microsite blocks */
        if(isset($_POST['block_type']) && array_key_exists($_POST['block_type'], $this->microsite_blocks)) {
            $_POST['block_type'] = query_clean($_POST['block_type']);
            $_POST['link_id'] = (int) $_POST['link_id'];

            error_log("Valid block_type found: " . $_POST['block_type']);
            error_log("Link ID: " . $_POST['link_id']);
            error_log("Routing to block handler...");

            /* Route to individual block handlers */
            $this->route_to_block_handler($_POST['block_type'], 'create');
        } else {
            error_log("Invalid or missing block_type!");
            error_log("block_type: " . ($_POST['block_type'] ?? 'not set'));
            error_log("Available types: " . implode(', ', array_keys($this->microsite_blocks)));
        }

        error_log("=== MicrositeBlockAjax CREATE END ===");
        Response::json('', 'success');
    }

    private function update() {
        /* Enhanced debug logging */
        error_log("=== MicrositeBlockAjax UPDATE START ===");
        error_log("POST data received: " . json_encode($_POST));
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("HTTP_X_REQUESTED_WITH: " . ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'not set'));
        
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.microsites_blocks')) {
            error_log("Team access denied for update.microsites_blocks");
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $this->microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php';
        error_log("Available microsite blocks: " . implode(', ', array_keys($this->microsite_blocks)));

        if(!empty($_POST)) {
            error_log("POST data is not empty, checking block_type...");
            /* Check for available microsite blocks */
            if(isset($_POST['block_type']) && array_key_exists($_POST['block_type'], $this->microsite_blocks)) {
                $_POST['block_type'] = query_clean($_POST['block_type']);
                error_log("Valid block_type found: " . $_POST['block_type']);

                /* Route to individual block handlers */
                error_log("Routing to block handler for: " . $_POST['block_type']);
                $this->route_to_block_handler($_POST['block_type'], 'update');
            } else {
                error_log("Invalid or missing block_type. block_type: " . ($_POST['block_type'] ?? 'not set'));
                error_log("Available types: " . implode(', ', array_keys($this->microsite_blocks)));
            }
        } else {
            error_log("POST data is empty!");
        }

        error_log("=== MicrositeBlockAjax UPDATE END ===");
        die();
    }

    /**
     * Route requests to individual block handlers
     */
    private function route_to_block_handler($block_type, $action) {
        /* Debug logging */
        error_log("Routing request - Block Type: {$block_type}, Action: {$action}");
        
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

        error_log("Available handlers: " . implode(', ', array_keys($block_handlers)));

        /* Check if we have a handler for this block type */
        if(!isset($block_handlers[$block_type])) {
            /* Log the missing handler for debugging */
            error_log("Missing block handler for type: {$block_type}");
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

    private function delete() {
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
}
