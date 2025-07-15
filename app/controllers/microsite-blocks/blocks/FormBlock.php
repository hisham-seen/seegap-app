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
 * Unified handler for all form types: email_collector, phone_collector, contact_collector, feedback_collector
 */
class FormBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['form'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['name'] = mb_substr(query_clean($_POST['name']), 0, 128);
        $_POST['form_type'] = in_array($_POST['form_type'], ['email', 'phone', 'contact', 'custom']) ? $_POST['form_type'] : 'email';

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'form';
        
        // Default metadata capture settings (GDPR-safe essentials only)
        $default_metadata = [
            // Essential data (always enabled)
            'submission_timestamp' => true,
            'form_id' => true,
            'form_version' => true,
            'session_id' => true,
            'javascript_enabled' => true,
            'cookies_enabled' => true,
            'validation_errors' => true,
            'submission_attempts' => true,
            
            // Analytics data (disabled by default)
            'country_alpha3' => false,
            'region_code' => false,
            'city_alpha3' => false,
            'timezone' => false,
            'browser_name' => false,
            'browser_version' => false,
            'os_name' => false,
            'device_type' => false,
            'screen_resolution' => false,
            'language' => false,
            'referrer_domain' => false,
            'time_on_page' => false,
            'pages_visited' => false,
            
            // Restricted data (requires consent)
            'ip_address' => false,
            'latitude' => false,
            'longitude' => false,
            'postal_code' => false,
            'user_agent' => false,
            'device_brand' => false,
            'device_model' => false,
            'referrer_url' => false,
            'landing_page_url' => false,
            'current_page_url' => false,
            'utm_source' => false,
            'utm_medium' => false,
            'utm_campaign' => false,
            'utm_term' => false,
            'utm_content' => false,
            'gclid' => false,
            'fbclid' => false,
            'affiliate_id' => false,
            'is_return_visitor' => false,
            'previous_submissions' => false,
            'field_interactions' => false,
            'copy_paste_events' => false,
            'tab_switches' => false,
            
            // High-risk data (disabled by default)
            'battery_level' => false,
            'network_speed' => false,
            'webgl_enabled' => false,
            'color_depth' => false,
            'pixel_ratio' => false,
        ];

        // Form type specific settings
        $form_settings = $this->getFormTypeDefaults($_POST['form_type']);
        
        $settings = json_encode([
            'name' => $_POST['name'],
            'form_type' => $_POST['form_type'],
            'image' => '',
            'text_color' => 'black',
            'text_alignment' => 'center',
            'background_color' => 'white',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 20,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000010',
            'border_width' => 0,
            'border_style' => 'solid',
            'border_color' => 'white',
            'border_radius' => 'rounded',
            'animation' => false,
            'animation_runs' => 'repeat-1',
            'icon' => '',

            // Form type specific settings
            ...$form_settings,

            // Metadata capture settings
            'metadata_capture' => $default_metadata,
            'data_retention_days' => 365,
            'anonymize_after_days' => 30,
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
            'settings' => $settings,
            'order' => settings()->links->microsites_new_blocks_position == 'top' ? -$this->total_microsite_blocks : $this->total_microsite_blocks,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('microsite_blocks?link_id=' . $_POST['link_id']);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=blocks')]);
    }
    
    public function update($type) {
        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];
        $_POST['name'] = mb_substr(query_clean($_POST['name']), 0, 128);
        $_POST['form_type'] = in_array($_POST['form_type'], ['email', 'phone', 'contact', 'custom']) ? $_POST['form_type'] : 'email';
        
        // Common styling settings
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
        $_POST['border_width'] = in_array($_POST['border_width'], [0, 1, 2, 3, 4, 5]) ? (int) $_POST['border_width'] : 0;
        $_POST['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
        $_POST['border_color'] = !verify_hex_color($_POST['border_color']) ? '#000000' : $_POST['border_color'];
        $_POST['border_shadow_offset_x'] = in_array($_POST['border_shadow_offset_x'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_x'] : 0;
        $_POST['border_shadow_offset_y'] = in_array($_POST['border_shadow_offset_y'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_y'] : 0;
        $_POST['border_shadow_blur'] = in_array($_POST['border_shadow_blur'], range(0, 20)) ? (int) $_POST['border_shadow_blur'] : 0;
        $_POST['border_shadow_spread'] = in_array($_POST['border_shadow_spread'], range(0, 10)) ? (int) $_POST['border_shadow_spread'] : 0;
        $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color']) ? '#000000' : $_POST['border_shadow_color'];
        $_POST['animation'] = in_array($_POST['animation'], require APP_PATH . 'includes/microsite_animations.php') || $_POST['animation'] == 'false' ? query_clean($_POST['animation']) : false;
        $_POST['animation_runs'] = isset($_POST['animation_runs']) && in_array($_POST['animation_runs'], ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? query_clean($_POST['animation_runs']) : false;
        $_POST['icon'] = query_clean($_POST['icon']);
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#000000' : $_POST['text_color'];
        $_POST['text_alignment'] = in_array($_POST['text_alignment'], ['center', 'left', 'right', 'justify']) ? query_clean($_POST['text_alignment']) : 'center';
        $_POST['background_color'] = !verify_hex_color($_POST['background_color']) ? '#ffffff' : $_POST['background_color'];

        // Common form settings
        $_POST['button_text'] = input_clean($_POST['button_text'], 64);
        $_POST['success_text'] = mb_substr(query_clean($_POST['success_text']), 0, 256);
        $_POST['show_agreement'] = (int) isset($_POST['show_agreement']);
        $_POST['agreement_url'] = get_url($_POST['agreement_url']);
        $_POST['agreement_text'] = mb_substr(query_clean($_POST['agreement_text']), 0, 256);
        $_POST['email_notification'] = mb_substr(query_clean($_POST['email_notification']), 0, 320);
        $_POST['webhook_url'] = get_url($_POST['webhook_url']);
        $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);

        // Process metadata capture settings
        $metadata_capture = [];
        if(isset($_POST['metadata_capture']) && is_array($_POST['metadata_capture'])) {
            $allowed_metadata = $this->getAllowedMetadataFields();
            foreach($_POST['metadata_capture'] as $field) {
                if(in_array($field, $allowed_metadata)) {
                    $metadata_capture[$field] = true;
                }
            }
        }

        // Data retention settings
        $_POST['data_retention_days'] = (int) ($_POST['data_retention_days'] ?? 365);
        $_POST['anonymize_after_days'] = (int) ($_POST['anonymize_after_days'] ?? 30);
        $_POST['gdpr_consent_required'] = (int) isset($_POST['gdpr_consent_required']);

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        /* Image upload */
        $db_image = $this->handle_image_upload($microsite_block->settings->image, 'block_thumbnail_images/', settings()->links->thumbnail_image_size_limit);
        $image_url = $db_image ? \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $db_image : null;

        // Process form type specific settings
        $form_type_settings = $this->processFormTypeSettings($_POST['form_type']);

        $settings = json_encode([
            'name' => $_POST['name'],
            'form_type' => $_POST['form_type'],
            'image' => $db_image,
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'],
            'background_color' => $_POST['background_color'],
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
            'icon' => $_POST['icon'],
            'button_text' => $_POST['button_text'],
            'success_text' => $_POST['success_text'],
            'thank_you_url' => $_POST['thank_you_url'],
            'show_agreement' => $_POST['show_agreement'],
            'agreement_url' => $_POST['agreement_url'],
            'agreement_text' => $_POST['agreement_text'],
            'email_notification' => $_POST['email_notification'],
            'webhook_url' => $_POST['webhook_url'],

            // Form type specific settings
            ...$form_type_settings,

            // Metadata capture settings
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

        db()->where('microsite_block_id', $_POST['microsite_block_id'])->update('microsites_blocks', [
            'settings' => $settings,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

        Response::json(l('global.success_message.update2'), 'success', ['images' => ['image' => $image_url]]);
    }

    private function getFormTypeDefaults($form_type) {
        switch($form_type) {
            case 'email':
                return [
                    'email_placeholder' => l('microsite_email_collector.email_placeholder_default'),
                    'name_placeholder' => l('microsite_email_collector.name_placeholder_default'),
                    'button_text' => l('microsite_email_collector.button_text_default'),
                    'success_text' => l('microsite_email_collector.success_text_default'),
                    'mailchimp_api' => '',
                    'mailchimp_api_list' => '',
                ];

            case 'phone':
                return [
                    'phone_placeholder' => l('microsite_phone_collector.phone_placeholder_default'),
                    'name_placeholder' => l('microsite_phone_collector.name_placeholder_default'),
                    'button_text' => l('microsite_phone_collector.button_text_default'),
                    'success_text' => l('microsite_phone_collector.success_text_default'),
                ];

            case 'contact':
                return [
                    'email_placeholder' => l('microsite_contact_collector.email_placeholder_default'),
                    'name_placeholder' => l('microsite_contact_collector.name_placeholder_default'),
                    'message_placeholder' => l('microsite_contact_collector.message_placeholder_default'),
                    'button_text' => l('microsite_contact_collector.button_text_default'),
                    'success_text' => l('microsite_contact_collector.success_text_default'),
                ];

            case 'custom':
                return [
                    'form_heading' => '',
                    'form_text' => '',
                    'questions' => [],
                    'button_text' => 'Submit',
                    'success_text' => 'Thank you for your submission!',
                ];

            default:
                return [];
        }
    }

    private function processFormTypeSettings($form_type) {
        $settings = [];

        switch($form_type) {
            case 'email':
                $settings['email_placeholder'] = mb_substr(query_clean($_POST['email_placeholder']), 0, 64);
                $settings['name_placeholder'] = mb_substr(query_clean($_POST['name_placeholder']), 0, 64);
                $settings['mailchimp_api'] = mb_substr(query_clean($_POST['mailchimp_api']), 0, 64);
                $settings['mailchimp_api_list'] = mb_substr(query_clean($_POST['mailchimp_api_list']), 0, 64);
                break;

            case 'phone':
                $settings['phone_placeholder'] = mb_substr(query_clean($_POST['phone_placeholder']), 0, 64);
                $settings['name_placeholder'] = mb_substr(query_clean($_POST['name_placeholder']), 0, 64);
                break;

            case 'contact':
                $settings['email_placeholder'] = mb_substr(query_clean($_POST['email_placeholder']), 0, 64);
                $settings['name_placeholder'] = mb_substr(query_clean($_POST['name_placeholder']), 0, 64);
                $settings['message_placeholder'] = mb_substr(query_clean($_POST['message_placeholder']), 0, 128);
                break;

            case 'custom':
                $settings['form_heading'] = mb_substr(input_clean($_POST['form_heading'] ?? ''), 0, 128);
                $settings['form_text'] = mb_substr(trim($_POST['form_text'] ?? ''), 0, 2048);
                
                // Process custom questions
                $questions = [];
                if(isset($_POST['question_type']) && is_array($_POST['question_type'])) {
                    foreach($_POST['question_type'] as $key => $question_type) {
                        if(!empty($_POST['question_text'][$key])) {
                            $question = [
                                'type' => in_array($question_type, ['text', 'textarea', 'email', 'phone', 'rating_star', 'rating_number', 'rating_emoji', 'checkbox', 'radio', 'dropdown']) ? $question_type : 'text',
                                'question' => mb_substr(input_clean($_POST['question_text'][$key]), 0, 256),
                                'required' => isset($_POST['question_required'][$key]) ? true : false,
                                'options' => new \stdClass()
                            ];
                            
                            if(in_array($question_type, ['rating_star', 'rating_number'])) {
                                $question['options']->max_rating = isset($_POST['question_max_rating'][$key]) ? (int) $_POST['question_max_rating'][$key] : 5;
                            }
                            
                            if(in_array($question_type, ['checkbox', 'radio', 'dropdown'])) {
                                $choices = isset($_POST['question_choices'][$key]) ? explode("\n", $_POST['question_choices'][$key]) : [];
                                $question['options']->choices = array_values(array_filter(array_map('trim', $choices)));
                            }
                            
                            $questions[] = $question;
                        }
                    }
                }
                $settings['questions'] = $questions;
                break;
        }

        return $settings;
    }

    private function getAllowedMetadataFields() {
        return [
            'submission_timestamp', 'form_id', 'form_version', 'session_id', 'javascript_enabled', 'cookies_enabled',
            'validation_errors', 'submission_attempts', 'country_alpha3', 'region_code', 'city_alpha3', 'timezone',
            'browser_name', 'browser_version', 'os_name', 'device_type', 'screen_resolution', 'language',
            'referrer_domain', 'time_on_page', 'pages_visited', 'ip_address', 'latitude', 'longitude',
            'postal_code', 'user_agent', 'device_brand', 'device_model', 'referrer_url', 'landing_page_url',
            'current_page_url', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
            'gclid', 'fbclid', 'affiliate_id', 'is_return_visitor', 'previous_submissions', 'field_interactions',
            'copy_paste_events', 'tab_switches', 'battery_level', 'network_speed', 'webgl_enabled',
            'color_depth', 'pixel_ratio'
        ];
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
