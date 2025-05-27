<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\BiolinkBlocks\Blocks;

use Altum\Controllers\BiolinkBlocks\BaseBlockHandler;
use Altum\Response;

defined('ALTUMCODE') || die();

/**
 * Feedback Collector Block Handler
 * 
 * Handles the creation and updating of feedback collector biolink blocks.
 */
class FeedbackCollectorBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['feedback_collector'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['title'] = mb_substr(input_clean($_POST['title']), 0, 128);
        $_POST['description'] = mb_substr(input_clean($_POST['description']), 0, 256);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'feedback_collector';
        $settings = json_encode([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'name_placeholder' => 'Your name',
            'email_placeholder' => 'Your email',
            'feedback_placeholder' => 'Your feedback',
            'button_text' => 'Submit Feedback',
            'success_text' => 'Thank you for your feedback!',
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

        $settings = $this->process_biolink_theme_id_settings($link, $settings, $type);

        /* Database query */
        db()->insert('biolinks_blocks', [
            'user_id' => $this->user->user_id,
            'link_id' => $_POST['link_id'],
            'type' => $type,
            'location_url' => null,
            'settings' => $settings,
            'order' => settings()->links->biolinks_new_blocks_position == 'top' ? -$this->total_biolink_blocks : $this->total_biolink_blocks,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $_POST['link_id']);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=blocks')]);
    }
    
    public function update($type) {
        $_POST['biolink_block_id'] = (int) $_POST['biolink_block_id'];
        $_POST['title'] = mb_substr(input_clean($_POST['title']), 0, 128);
        $_POST['description'] = mb_substr(input_clean($_POST['description']), 0, 256);
        $_POST['name_placeholder'] = mb_substr(input_clean($_POST['name_placeholder']), 0, 64);
        $_POST['email_placeholder'] = mb_substr(input_clean($_POST['email_placeholder']), 0, 64);
        $_POST['feedback_placeholder'] = mb_substr(input_clean($_POST['feedback_placeholder']), 0, 128);
        $_POST['button_text'] = mb_substr(input_clean($_POST['button_text']), 0, 32);
        $_POST['success_text'] = mb_substr(input_clean($_POST['success_text']), 0, 128);
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

        if(!$biolink_block = db()->where('biolink_block_id', $_POST['biolink_block_id'])->where('user_id', $this->user->user_id)->getOne('biolinks_blocks')) {
            die();
        }

        /* Check for any errors */
        $required_fields = ['title'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        $settings = json_encode([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'name_placeholder' => $_POST['name_placeholder'],
            'email_placeholder' => $_POST['email_placeholder'],
            'feedback_placeholder' => $_POST['feedback_placeholder'],
            'button_text' => $_POST['button_text'],
            'success_text' => $_POST['success_text'],
            'email_notification' => $_POST['email_notification'],
            'webhook_url' => $_POST['webhook_url'],
            'text_color' => $_POST['text_color'],
            'background_color' => $_POST['background_color'],
            'border_radius' => $_POST['border_radius'],
            'border_width' => $_POST['border_width'],
            'border_style' => $_POST['border_style'],
            'border_color' => $_POST['border_color'],

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
        db()->where('biolink_block_id', $_POST['biolink_block_id'])->update('biolinks_blocks', [
            'settings' => $settings,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $biolink_block->link_id);

        Response::json(l('global.success_message.update2'), 'success');
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
