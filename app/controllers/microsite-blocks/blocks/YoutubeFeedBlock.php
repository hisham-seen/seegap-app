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
 * YouTube Feed Block Handler
 * 
 * Handles the creation and updating of YouTube feed microsite blocks.
 */
class YoutubeFeedBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['youtube_feed'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['youtube_channel_id'] = mb_substr(query_clean($_POST['youtube_channel_id']), 0, 64);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'youtube_feed';
        $settings = json_encode([
            'youtube_channel_id' => $_POST['youtube_channel_id'],
            'amount' => 3,
            'open_in_new_tab' => false,

            /* Style settings */
            'text_color' => '#ffffff',
            'text_alignment' => 'center',
            'background_color' => '#007bff',

            /* Border settings */
            'border_width' => 0,
            'border_color' => '#000000',
            'border_radius' => 'rounded',
            'border_style' => 'solid',

            /* Border shadow settings */
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#000000',

            /* Animation settings */
            'animation' => false,
            'animation_runs' => 'repeat-1',

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
        $_POST['youtube_channel_id'] = mb_substr(query_clean($_POST['youtube_channel_id']), 0, 64);
        $_POST['amount'] = in_array($_POST['amount'], range(1, 10)) ? (int) $_POST['amount'] : 3;
        $_POST['open_in_new_tab'] = (bool) isset($_POST['open_in_new_tab']);

        /* Style settings */
        $_POST['text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text_color']) ? '#000000' : $_POST['text_color'];
        $_POST['text_alignment'] = in_array($_POST['text_alignment'], ['center', 'left', 'right', 'justify']) ? $_POST['text_alignment'] : 'center';
        $_POST['background_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? '#ffffff' : $_POST['background_color'];
        
        /* Border settings */
        $_POST['border_width'] = in_array($_POST['border_width'], range(0, 5)) ? (int) $_POST['border_width'] : 0;
        $_POST['border_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['border_color']) ? '#000000' : $_POST['border_color'];
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? $_POST['border_radius'] : 'rounded';
        $_POST['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'outset', 'inset']) ? $_POST['border_style'] : 'solid';
        
        /* Border shadow settings */
        $_POST['border_shadow_offset_x'] = in_array($_POST['border_shadow_offset_x'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_x'] : 0;
        $_POST['border_shadow_offset_y'] = in_array($_POST['border_shadow_offset_y'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_y'] : 0;
        $_POST['border_shadow_blur'] = in_array($_POST['border_shadow_blur'], range(0, 20)) ? (int) $_POST['border_shadow_blur'] : 0;
        $_POST['border_shadow_spread'] = in_array($_POST['border_shadow_spread'], range(0, 10)) ? (int) $_POST['border_shadow_spread'] : 0;
        $_POST['border_shadow_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['border_shadow_color']) ? '#000000' : $_POST['border_shadow_color'];
        
        /* Animation settings */
        $animations = require APP_PATH . 'includes/microsite_animations.php';
        $_POST['animation'] = in_array($_POST['animation'], array_merge(['false'], $animations)) ? $_POST['animation'] : false;
        $_POST['animation_runs'] = in_array($_POST['animation_runs'], ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? $_POST['animation_runs'] : 'repeat-1';

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        /* Check for any errors */
        $required_fields = ['youtube_channel_id'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        $settings = json_encode([
            'youtube_channel_id' => $_POST['youtube_channel_id'],
            'amount' => $_POST['amount'],
            'open_in_new_tab' => $_POST['open_in_new_tab'],

            /* Style settings */
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'],
            'background_color' => $_POST['background_color'],

            /* Border settings */
            'border_width' => $_POST['border_width'],
            'border_color' => $_POST['border_color'],
            'border_radius' => $_POST['border_radius'],
            'border_style' => $_POST['border_style'],

            /* Border shadow settings */
            'border_shadow_offset_x' => $_POST['border_shadow_offset_x'],
            'border_shadow_offset_y' => $_POST['border_shadow_offset_y'],
            'border_shadow_blur' => $_POST['border_shadow_blur'],
            'border_shadow_spread' => $_POST['border_shadow_spread'],
            'border_shadow_color' => $_POST['border_shadow_color'],

            /* Animation settings */
            'animation' => $_POST['animation'],
            'animation_runs' => $_POST['animation_runs'],

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
