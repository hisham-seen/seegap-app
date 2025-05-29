<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\MicrositeBlocks\Blocks;

use Altum\Controllers\MicrositeBlocks\BaseBlockHandler;
use Altum\Response;

defined('ALTUMCODE') || die();

/**
 * Feedback Collector Block Handler
 * 
 * Handles the creation and updating of feedback collector microsite blocks.
 */
class FeedbackCollectorBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['feedback_collector'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['name'] = mb_substr(input_clean($_POST['name']), 0, 128);
        $_POST['description'] = mb_substr(input_clean($_POST['description']), 0, 256);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'feedback_collector';
        
        // Process questions
        $questions = [];
        if(isset($_POST['question_type']) && is_array($_POST['question_type'])) {
            foreach($_POST['question_type'] as $key => $question_type) {
                if(!empty($_POST['question_text'][$key])) {
                    $question = [
                        'type' => in_array($question_type, ['text', 'textarea', 'rating_star', 'rating_number', 'rating_emoji', 'checkbox', 'radio', 'dropdown']) ? $question_type : 'text',
                        'question' => mb_substr(input_clean($_POST['question_text'][$key]), 0, 256),
                        'required' => isset($_POST['question_required'][$key]) ? true : false,
                        'options' => new \stdClass()
                    ];
                    
                    // Add specific options based on question type
                    if(in_array($question_type, ['rating_star', 'rating_number'])) {
                        $question['options']->max_rating = isset($_POST['question_max_rating'][$key]) ? (int) $_POST['question_max_rating'][$key] : 5;
                    }
                    
                    if(in_array($question_type, ['checkbox', 'radio', 'dropdown'])) {
                        $choices = isset($_POST['question_choices'][$key]) ? explode("\n", $_POST['question_choices'][$key]) : [];
                        $question['options']->choices = array_filter(array_map('trim', $choices));
                    }
                    
                    $questions[] = $question;
                }
            }
        }

        $settings = json_encode([
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'questions' => $questions,
            'button_text' => 'Submit Feedback',
            'success_text' => 'Thank you for your feedback!',
            'thank_you_url' => '',
            'show_agreement' => false,
            'agreement_text' => '',
            'agreement_url' => '',
            'email_notification' => '',
            'webhook_url' => '',
            'text_color' => '#ffffff',
            'background_color' => '#00000000',
            'border_radius' => 'rounded',
            'border_width' => 0,
            'border_style' => 'solid',
            'border_color' => '#ffffff',

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
    }
    
    public function update($type) {
        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];
        $_POST['name'] = mb_substr(input_clean($_POST['name']), 0, 128);
        $_POST['description'] = mb_substr(input_clean($_POST['description']), 0, 256);
        $_POST['button_text'] = mb_substr(input_clean($_POST['button_text']), 0, 64);
        $_POST['success_text'] = mb_substr(input_clean($_POST['success_text']), 0, 128);
        $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
        $_POST['agreement_text'] = mb_substr(input_clean($_POST['agreement_text']), 0, 256);
        $_POST['agreement_url'] = get_url($_POST['agreement_url']);
        $_POST['email_notification'] = mb_substr(filter_var($_POST['email_notification'], FILTER_SANITIZE_EMAIL), 0, 320);
        $_POST['webhook_url'] = get_url($_POST['webhook_url']);
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#ffffff' : $_POST['text_color'];
        $_POST['background_color'] = !verify_hex_color($_POST['background_color']) ? '#00000000' : $_POST['background_color'];
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
        $_POST['border_width'] = in_array($_POST['border_width'], [0, 1, 2, 3, 4, 5]) ? (int) $_POST['border_width'] : 0;
        $_POST['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
        $_POST['border_color'] = !verify_hex_color($_POST['border_color']) ? '#ffffff' : $_POST['border_color'];

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        /* Check for any errors */
        $required_fields = ['name'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        // Process questions
        $questions = [];
        if(isset($_POST['question_type']) && is_array($_POST['question_type'])) {
            foreach($_POST['question_type'] as $key => $question_type) {
                if(!empty($_POST['question_text'][$key])) {
                    $question = [
                        'type' => in_array($question_type, ['text', 'textarea', 'rating_star', 'rating_number', 'rating_emoji', 'checkbox', 'radio', 'dropdown']) ? $question_type : 'text',
                        'question' => mb_substr(input_clean($_POST['question_text'][$key]), 0, 256),
                        'required' => isset($_POST['question_required'][$key]) ? true : false,
                        'options' => new \stdClass()
                    ];
                    
                    // Add specific options based on question type
                    if(in_array($question_type, ['rating_star', 'rating_number'])) {
                        $question['options']->max_rating = isset($_POST['question_max_rating'][$key]) ? (int) $_POST['question_max_rating'][$key] : 5;
                    }
                    
                    if(in_array($question_type, ['checkbox', 'radio', 'dropdown'])) {
                        $choices = isset($_POST['question_choices'][$key]) ? explode("\n", $_POST['question_choices'][$key]) : [];
                        $question['options']->choices = array_filter(array_map('trim', $choices));
                    }
                    
                    $questions[] = $question;
                }
            }
        }

        $settings = json_encode([
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'questions' => $questions,
            'button_text' => $_POST['button_text'],
            'success_text' => $_POST['success_text'],
            'thank_you_url' => $_POST['thank_you_url'],
            'show_agreement' => isset($_POST['show_agreement']),
            'agreement_text' => $_POST['agreement_text'],
            'agreement_url' => $_POST['agreement_url'],
            'email_notification' => $_POST['email_notification'],
            'webhook_url' => $_POST['webhook_url'],
            'icon' => $_POST['icon'] ?? '',
            'image' => $_POST['image'] ?? '',
            'image_display' => $_POST['image_display'] ?? 'icon',
            'image_fit' => $_POST['image_fit'] ?? 'cover',
            'mega_button_height' => $_POST['mega_button_height'] ?? '200',
            'show_text' => isset($_POST['show_text']),
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'] ?? 'center',
            'background_color' => $_POST['background_color'],
            'border_radius' => $_POST['border_radius'],
            'border_width' => $_POST['border_width'],
            'border_style' => $_POST['border_style'],
            'border_color' => $_POST['border_color'],
            'border_shadow_offset_x' => $_POST['border_shadow_offset_x'] ?? 0,
            'border_shadow_offset_y' => $_POST['border_shadow_offset_y'] ?? 0,
            'border_shadow_blur' => $_POST['border_shadow_blur'] ?? 0,
            'border_shadow_spread' => $_POST['border_shadow_spread'] ?? 0,
            'border_shadow_color' => $_POST['border_shadow_color'] ?? '#000000',
            'animation' => $_POST['animation'] ?? false,
            'animation_runs' => $_POST['animation_runs'] ?? 'repeat-1',

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

        Response::json(l('global.success_message.update2'), 'success');
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
