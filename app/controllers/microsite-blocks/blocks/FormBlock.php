<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers\MicrositeBlocks\Blocks;

use SeeGap\Controllers\MicrositeBlocks\BaseBlockHandler;
use SeeGap\Response;

defined('SEEGAP') || die();

/**
 * Form Block Handler
 * 
 * Handles the creation and updating of form microsite blocks.
 * Supports email, phone, contact, custom, and feedback_collector form types.
 */
class FormBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['form'];
    }
    
    public function create($type) {
        try {
            $_POST['link_id'] = (int) $_POST['link_id'];
            $_POST['name'] = mb_substr(input_clean($_POST['name'] ?? ''), 0, 128);
            $_POST['display_mode'] = in_array($_POST['display_mode'] ?? 'inline', ['inline', 'modal', 'button']) ? query_clean($_POST['display_mode']) : 'inline';
            $_POST['form_heading'] = mb_substr(input_clean($_POST['form_heading'] ?? ''), 0, 128);
            $_POST['form_text'] = mb_substr(input_clean($_POST['form_text'] ?? ''), 0, 2048);
            $_POST['button_text'] = mb_substr(query_clean($_POST['button_text'] ?? ''), 0, 64);

            if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
                Response::json(l('global.error_message.invalid_request'), 'error');
            }

            /* Check for any errors */
            $required_fields = ['name', 'button_text'];

            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Response::json(l('global.error_message.empty_fields'), 'error');
                    break 1;
                }
            }

            /* Process questions */
            $questions = $this->process_questions();

            $type = 'form';
            $settings = json_encode([
                'name' => $_POST['name'],
                'display_mode' => $_POST['display_mode'],
                'form_heading' => $_POST['form_heading'],
                'form_text' => $_POST['form_text'],
                'questions' => $questions,
                'button_text' => $_POST['button_text'] ?: l('global.submit'),
                'success_text' => l('global.success_message.basic'),
                'thank_you_url' => '',
                'email_notification' => '',
                'webhook_url' => '',
                'show_agreement' => false,
                'agreement_text' => '',
                'agreement_url' => '',
                'image' => '',
                'icon' => '',
                'text_color' => '#000000',
                'background_color' => '#ffffff',
                'text_alignment' => 'center',
                'border_radius' => 0,
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#ffffff',
                'border_shadow_offset_x' => 0,
                'border_shadow_offset_y' => 0,
                'border_shadow_blur' => 0,
                'border_shadow_spread' => 0,
                'border_shadow_color' => '#00000010',
                'animation' => false,
                'animation_runs' => 'repeat-1',
                'animation_delay' => 0,
                'inline_form_style' => 'card',
                'modal_form_style' => 'standard',
                'button_trigger_style' => 'button',
                'metadata_capture' => new \stdClass(),
                'data_retention_days' => 365,
                'anonymize_after_days' => 90,
                'gdpr_consent_required' => false,

                /* Display settings */
                'display_continents' => [],
                'display_countries' => [],
                'display_cities' => [],
                'display_devices' => [],
                'display_languages' => [],
                'display_operating_systems' => [],
                'display_browsers' => [],
            ]);

            $settings = $this->process_microsite_theme_id_settings($link, $settings, $type);

            /* Database query */
            db()->insert('microsites_blocks', [
                'user_id' => $this->user->user_id,
                'link_id' => $_POST['link_id'],
                'type' => $type,
                'location_url' => null,
                'settings' => $settings,
                'order' => settings()->links->microsites_new_blocks_position == 'top' ? -$this->total_microsite_blocks : $this->total_microsite_blocks,
                'datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItem('microsite_blocks?link_id=' . $_POST['link_id']);

            Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=blocks')]);
            
        } catch (\Exception $e) {
            error_log("FormBlock create error: " . $e->getMessage());
            Response::json(l('global.error_message.basic'), 'error');
        }
    }
    
    public function update($type) {
        try {
            
            $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];
            $_POST['name'] = mb_substr(input_clean($_POST['name'] ?? ''), 0, 128);
            $_POST['display_mode'] = in_array($_POST['display_mode'] ?? 'inline', ['inline', 'modal', 'button']) ? query_clean($_POST['display_mode']) : 'inline';
            $_POST['form_heading'] = mb_substr(input_clean($_POST['form_heading'] ?? ''), 0, 128);
            $_POST['form_text'] = mb_substr(input_clean($_POST['form_text'] ?? ''), 0, 2048);
            $_POST['button_text'] = mb_substr(query_clean($_POST['button_text'] ?? ''), 0, 64);
            $_POST['success_text'] = mb_substr(input_clean($_POST['success_text'] ?? ''), 0, 256);
            $_POST['thank_you_url'] = get_url($_POST['thank_you_url'] ?? '');
            $_POST['email_notification'] = filter_var($_POST['email_notification'] ?? '', FILTER_VALIDATE_EMAIL) ? $_POST['email_notification'] : '';
            $_POST['webhook_url'] = get_url($_POST['webhook_url'] ?? '');
            $_POST['show_agreement'] = isset($_POST['show_agreement']);
            $_POST['agreement_text'] = mb_substr(input_clean($_POST['agreement_text'] ?? ''), 0, 256);
            $_POST['agreement_url'] = get_url($_POST['agreement_url'] ?? '');
            $_POST['text_color'] = !verify_hex_color($_POST['text_color'] ?? '') ? '#000000' : $_POST['text_color'];
            $_POST['background_color'] = !verify_hex_color($_POST['background_color'] ?? '') ? '#ffffff' : $_POST['background_color'];
            $_POST['text_alignment'] = in_array($_POST['text_alignment'] ?? 'center', ['center', 'left', 'right', 'justify']) ? query_clean($_POST['text_alignment']) : 'center';
            $_POST['border_radius'] = (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 0;
            $_POST['border_width'] = (int) ($_POST['border_width'] ?? 0);
            $_POST['border_width'] = $_POST['border_width'] > 20 ? 20 : $_POST['border_width']; // Cap at 20px
            $_POST['border_style'] = in_array($_POST['border_style'] ?? 'solid', ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
            $_POST['border_color'] = !verify_hex_color($_POST['border_color'] ?? '') ? '#000000' : $_POST['border_color'];
            $_POST['border_shadow_offset_x'] = (int) ($_POST['border_shadow_offset_x'] ?? 0);
            $_POST['border_shadow_offset_y'] = (int) ($_POST['border_shadow_offset_y'] ?? 0);
            $_POST['border_shadow_blur'] = (int) ($_POST['border_shadow_blur'] ?? 0);
            $_POST['border_shadow_spread'] = (int) ($_POST['border_shadow_spread'] ?? 0);
            $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color'] ?? '') ? '#00000010' : $_POST['border_shadow_color'];
            $_POST['animation'] = in_array($_POST['animation'] ?? 'false', array_merge(['false'], require APP_PATH . 'includes/microsite_animations.php')) ? query_clean($_POST['animation']) : false;
            $_POST['animation_runs'] = in_array($_POST['animation_runs'] ?? 'repeat-1', ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? query_clean($_POST['animation_runs']) : 'repeat-1';
            $_POST['animation_delay'] = (int) ($_POST['animation_delay'] ?? 0);
            $_POST['inline_form_style'] = in_array($_POST['inline_form_style'] ?? 'card', ['card', 'minimal']) ? query_clean($_POST['inline_form_style']) : 'card';
            $_POST['modal_form_style'] = in_array($_POST['modal_form_style'] ?? 'standard', ['standard', 'fullscreen', 'sidebar']) ? query_clean($_POST['modal_form_style']) : 'standard';
            $_POST['button_trigger_style'] = in_array($_POST['button_trigger_style'] ?? 'button', ['button', 'link', 'icon']) ? query_clean($_POST['button_trigger_style']) : 'button';
            $_POST['data_retention_days'] = in_array($_POST['data_retention_days'] ?? 365, [30, 90, 180, 365, 730, 1095]) ? (int) $_POST['data_retention_days'] : 365;
            $_POST['anonymize_after_days'] = in_array($_POST['anonymize_after_days'] ?? 90, [7, 30, 90, 180]) ? (int) $_POST['anonymize_after_days'] : 90;
            $_POST['gdpr_consent_required'] = isset($_POST['gdpr_consent_required']);

            /* Display settings */
            $this->process_display_settings();

            if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
                Response::json(l('global.error_message.invalid_request'), 'error');
            }
            $microsite_block->settings = json_decode($microsite_block->settings ?? '');

            /* Check for any errors */
            $required_fields = ['name', 'button_text'];

            /* Check for any errors */
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Response::json(l('global.error_message.empty_fields'), 'error');
                    break 1;
                }
            }

            /* Process questions */
            $questions = $this->process_questions();

            /* Process metadata capture settings */
            $metadata_capture = $this->process_metadata_capture();

            /* Image upload - use the same pattern as other blocks */
            $db_image = $this->handle_file_upload(
                $microsite_block->settings->image ?? '', 
                'image', 
                'image_remove', 
                ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], 
                'block_thumbnail_images/', 
                settings()->links->thumbnail_image_size_limit
            );

            $image_url = $db_image ? \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $db_image : null;

            $settings = json_encode([
                'name' => $_POST['name'],
                'display_mode' => $_POST['display_mode'],
                'form_heading' => $_POST['form_heading'],
                'form_text' => $_POST['form_text'],
                'questions' => $questions,
                'button_text' => $_POST['button_text'],
                'success_text' => $_POST['success_text'],
                'thank_you_url' => $_POST['thank_you_url'],
                'email_notification' => $_POST['email_notification'],
                'webhook_url' => $_POST['webhook_url'],
                'show_agreement' => $_POST['show_agreement'],
                'agreement_text' => $_POST['agreement_text'],
                'agreement_url' => $_POST['agreement_url'],
                'image' => $db_image,
                'icon' => mb_substr(input_clean($_POST['icon'] ?? ''), 0, 64),
                'text_color' => $_POST['text_color'],
                'background_color' => $_POST['background_color'],
                'text_alignment' => $_POST['text_alignment'],
                'border_radius' => $_POST['border_radius'],
                'border_width' => $_POST['border_width'],
                'border_style' => $_POST['border_style'],
                'border_color' => $_POST['border_color'],
                'border_shadow_offset_x' => $_POST['border_shadow_offset_x'],
                'border_shadow_offset_y' => $_POST['border_shadow_offset_y'],
                'border_shadow_blur' => $_POST['border_shadow_blur'],
                'border_shadow_spread' => $_POST['border_shadow_spread'],
                'border_shadow_color' => $_POST['border_shadow_color'],
                'animation' => $_POST['animation'],
                'animation_runs' => $_POST['animation_runs'],
                'animation_delay' => $_POST['animation_delay'],
                'inline_form_style' => $_POST['inline_form_style'],
                'modal_form_style' => $_POST['modal_form_style'],
                'button_trigger_style' => $_POST['button_trigger_style'],
                'metadata_capture' => $metadata_capture,
                'data_retention_days' => $_POST['data_retention_days'],
                'anonymize_after_days' => $_POST['anonymize_after_days'],
                'gdpr_consent_required' => $_POST['gdpr_consent_required'],

                /* Display settings */
                'display_continents' => $_POST['display_continents'],
                'display_countries' => $_POST['display_countries'],
                'display_cities' => $_POST['display_cities'],
                'display_devices' => $_POST['display_devices'],
                'display_languages' => $_POST['display_languages'],
                'display_operating_systems' => $_POST['display_operating_systems'],
                'display_browsers' => $_POST['display_browsers'],
            ]);

            /* Database query */
            db()->where('microsite_block_id', $_POST['microsite_block_id'])->update('microsites_blocks', [
                'settings' => $settings,
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'last_datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

            Response::json(l('global.success_message.update2'), 'success', ['images' => ['image' => $image_url]]);
            
        } catch (\Exception $e) {
            error_log("FormBlock update error: " . $e->getMessage());
            Response::json(l('global.error_message.basic'), 'error');
        }
    }
    
    public function validate($type, $data = []) {
        return true;
    }
    
    
    /**
     * Process questions for custom and feedback_collector forms
     */
    private function process_questions() {
        $questions = [];
        
        try {
            if(isset($_POST['question_text']) && is_array($_POST['question_text'])) {
                foreach($_POST['question_text'] as $key => $question_text) {
                    if(!empty(trim($question_text))) {
                        $question = new \stdClass();
                        $question->question = mb_substr(input_clean($question_text), 0, 256);
                        $question->type = in_array($_POST['question_type'][$key] ?? 'text', ['text', 'textarea', 'email', 'phone', 'rating_star', 'rating_number', 'rating_emoji', 'checkbox', 'radio', 'dropdown', 'receipt_upload']) ? $_POST['question_type'][$key] : 'text';
                        $question->required = isset($_POST['question_required'][$key]);
                        
                        $question->options = new \stdClass();
                        
                        /* Handle choices for checkbox, radio, dropdown */
                        if(in_array($question->type, ['checkbox', 'radio', 'dropdown']) && isset($_POST['question_choices'][$key])) {
                            $choices = array_filter(array_map('trim', explode("\n", $_POST['question_choices'][$key])));
                            $question->options->choices = array_slice($choices, 0, 20); // Limit to 20 choices
                        }
                        
                        /* Handle max rating for rating questions */
                        if(in_array($question->type, ['rating_star', 'rating_number']) && isset($_POST['question_max_rating'][$key])) {
                            $question->options->max_rating = in_array($_POST['question_max_rating'][$key], range(3, 10)) ? (int) $_POST['question_max_rating'][$key] : 5;
                        }
                        
                        /* Handle AI analysis settings for receipt upload questions */
                        if($question->type === 'receipt_upload') {
                            $question->options->ai_analysis_enabled = isset($_POST['question_ai_analysis'][$key]);
                            
                            if($question->options->ai_analysis_enabled) {
                                // AI providers configuration
                                $question->options->ai_providers = [];
                                $available_providers = ['openai', 'google', 'anthropic'];
                                
                                foreach($available_providers as $provider) {
                                    if(isset($_POST['question_ai_providers'][$key]) && 
                                       is_array($_POST['question_ai_providers'][$key]) && 
                                       in_array($provider, $_POST['question_ai_providers'][$key])) {
                                        $question->options->ai_providers[] = $provider;
                                    }
                                }
                                
                                // Default to OpenAI if no providers selected but AI analysis is enabled
                                if(empty($question->options->ai_providers)) {
                                    $question->options->ai_providers = ['openai'];
                                }
                                
                                // Analysis options
                                $question->options->extract_items = isset($_POST['question_extract_items'][$key]);
                                $question->options->extract_totals = isset($_POST['question_extract_totals'][$key]);
                                $question->options->extract_merchant = isset($_POST['question_extract_merchant'][$key]);
                                $question->options->extract_date = isset($_POST['question_extract_date'][$key]);
                                $question->options->extract_payment_method = isset($_POST['question_extract_payment_method'][$key]);
                                $question->options->extract_tax = isset($_POST['question_extract_tax'][$key]);
                                
                                // Processing priority
                                $question->options->processing_priority = in_array($_POST['question_processing_priority'][$key] ?? 'normal', ['low', 'normal', 'high']) ? $_POST['question_processing_priority'][$key] : 'normal';
                                
                                // Auto-retry settings
                                $question->options->auto_retry = isset($_POST['question_auto_retry'][$key]);
                                $question->options->max_retries = in_array($_POST['question_max_retries'][$key] ?? 3, [1, 2, 3, 5]) ? (int) $_POST['question_max_retries'][$key] : 3;
                            } else {
                                // Set default values when AI analysis is disabled
                                $question->options->ai_providers = [];
                                $question->options->extract_items = false;
                                $question->options->extract_totals = false;
                                $question->options->extract_merchant = false;
                                $question->options->extract_date = false;
                                $question->options->extract_payment_method = false;
                                $question->options->extract_tax = false;
                                $question->options->processing_priority = 'normal';
                                $question->options->auto_retry = false;
                                $question->options->max_retries = 3;
                            }
                            
                            // File upload settings
                            $question->options->max_file_size = in_array($_POST['question_max_file_size'][$key] ?? 5, [1, 2, 5, 10]) ? (int) $_POST['question_max_file_size'][$key] : 5; // MB
                            $question->options->allowed_formats = ['jpg', 'jpeg', 'png', 'pdf', 'heic', 'webp']; // Common receipt formats
                            $question->options->camera_quality = in_array($_POST['question_camera_quality'][$key] ?? 'high', ['low', 'medium', 'high']) ? $_POST['question_camera_quality'][$key] : 'high';
                            $question->options->multiple_uploads = isset($_POST['question_multiple_uploads'][$key]);
                            $question->options->max_uploads = $question->options->multiple_uploads ? 
                                (in_array($_POST['question_max_uploads'][$key] ?? 3, [1, 2, 3, 5, 10]) ? (int) $_POST['question_max_uploads'][$key] : 3) : 1;
                        }
                        
                        $questions[] = $question;
                    }
                }
            }
        } catch (\Exception $e) {
            error_log("FormBlock process_questions error: " . $e->getMessage());
            // Return empty array on error
        }
        
        return $questions;
    }
    
    /**
     * Process metadata capture settings
     */
    private function process_metadata_capture() {
        $metadata_capture = new \stdClass();
        
        try {
            if(isset($_POST['metadata_capture']) && is_array($_POST['metadata_capture'])) {
                $allowed_metadata_fields = [
                    'country_alpha3', 'region_code', 'city_alpha3', 'timezone', 'browser_name', 'browser_version',
                    'os_name', 'device_type', 'screen_resolution', 'language', 'referrer_domain', 'time_on_page',
                    'pages_visited', 'ip_address', 'latitude', 'longitude', 'postal_code', 'user_agent',
                    'device_brand', 'device_model', 'referrer_url', 'landing_page_url', 'current_page_url',
                    'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid',
                    'affiliate_id', 'is_return_visitor', 'previous_submissions', 'field_interactions',
                    'copy_paste_events', 'tab_switches', 'battery_level', 'network_speed', 'webgl_enabled',
                    'color_depth', 'pixel_ratio'
                ];
                
                foreach($_POST['metadata_capture'] as $field) {
                    if(in_array($field, $allowed_metadata_fields)) {
                        $metadata_capture->$field = true;
                    }
                }
            }
        } catch (\Exception $e) {
            error_log("FormBlock process_metadata_capture error: " . $e->getMessage());
            // Return empty object on error
        }
        
        return $metadata_capture;
    }
}
