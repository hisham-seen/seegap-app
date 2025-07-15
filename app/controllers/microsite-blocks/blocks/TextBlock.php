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
 * Text Block Handler
 * 
 * Handles the creation and updating of unified text microsite blocks.
 * Combines functionality of heading, paragraph, and list blocks.
 */
class TextBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['text'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['text_type'] = in_array($_POST['text_type'], ['heading', 'paragraph', 'list']) ? query_clean($_POST['text_type']) : 'paragraph';

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'text';
        
        // Base settings for all text types
        $settings = [
            'text_type' => $_POST['text_type'],
            'text_color' => '#ffffff',
            'text_alignment' => 'center',
            
            /* Display settings */
            'display_continents' => [],
            'display_countries' => [],
            'display_cities' => [],
            'display_devices' => [],
            'display_languages' => [],
            'display_operating_systems' => [],
            'display_browsers' => [],
        ];

        // Type-specific settings
        switch($_POST['text_type']) {
            case 'heading':
                $settings['text'] = isset($_POST['text']) ? mb_substr(query_clean($_POST['text']), 0, 256) : '';
                $settings['heading_type'] = in_array($_POST['heading_type'] ?? 'h1', ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? query_clean($_POST['heading_type']) : 'h1';
                $settings['verified_location'] = '';
                break;
                
            case 'paragraph':
                $settings['text'] = isset($_POST['text']) ? mb_substr(input_clean($_POST['text']), 0, 2048) : '';
                $settings['background_color'] = '#00000000';
                $settings['border_radius'] = 'rounded';
                $settings['border_shadow_offset_x'] = 0;
                $settings['border_shadow_offset_y'] = 0;
                $settings['border_shadow_blur'] = 20;
                $settings['border_shadow_spread'] = 0;
                $settings['border_shadow_color'] = '#00000010';
                $settings['border_width'] = 0;
                $settings['border_style'] = 'solid';
                $settings['border_color'] = '#ffffff';
                break;
                
            case 'list':
                $settings['list_type'] = in_array($_POST['list_type'] ?? 'unordered', ['ordered', 'unordered']) ? query_clean($_POST['list_type']) : 'unordered';
                $settings['text_alignment'] = 'left';
                $settings['background_color'] = '#00000000';
                $settings['border_width'] = 0;
                $settings['border_color'] = '#ffffff';
                $settings['border_radius'] = 'rounded';
                $settings['border_style'] = 'solid';
                $settings['border_shadow_offset_x'] = 0;
                $settings['border_shadow_offset_y'] = 0;
                $settings['border_shadow_blur'] = 0;
                $settings['border_shadow_spread'] = 0;
                $settings['border_shadow_color'] = '#00000010';
                $settings['margin_items_y'] = 2;
                $settings['margin_items_x'] = 1;
                
                // Process list items for create
                $list_items = [];
                if(isset($_POST['list_items'])) {
                    foreach($_POST['list_items'] as $key => $list_item) {
                        if(empty(trim($list_item))) continue;
                        if($key >= 100) continue;
                        $list_items[] = mb_substr(input_clean($list_item), 0, 256);
                    }
                }
                // Ensure at least one empty item for new lists
                if(empty($list_items)) {
                    $list_items = [''];
                }
                $settings['list_items'] = $list_items;
                break;
        }

        $settings = json_encode($settings);
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
        $_POST['text_type'] = in_array($_POST['text_type'], ['heading', 'paragraph', 'list']) ? query_clean($_POST['text_type']) : 'paragraph';
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#ffffff' : $_POST['text_color'];
        $_POST['text_alignment'] = in_array($_POST['text_alignment'], ['center', 'justify', 'left', 'right']) ? query_clean($_POST['text_alignment']) : 'center';

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        // Base settings for all text types
        $settings = [
            'text_type' => $_POST['text_type'],
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'],
            
            /* Display settings */
            'display_continents' => $_POST['display_continents'],
            'display_countries' => $_POST['display_countries'],
            'display_cities' => $_POST['display_cities'],
            'display_devices' => $_POST['display_devices'],
            'display_languages' => $_POST['display_languages'],
            'display_operating_systems' => $_POST['display_operating_systems'],
            'display_browsers' => $_POST['display_browsers'],
        ];

        // Type-specific settings
        switch($_POST['text_type']) {
            case 'heading':
                $settings['text'] = mb_substr(query_clean($_POST['text']), 0, 256);
                $settings['heading_type'] = in_array($_POST['heading_type'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? query_clean($_POST['heading_type']) : 'h1';
                $settings['verified_location'] = isset($_POST['verified_location']) && in_array($_POST['verified_location'], ['', 'left', 'right']) ? query_clean($_POST['verified_location']) : '';
                break;
                
            case 'paragraph':
                $settings['text'] = mb_substr(input_clean($_POST['text']), 0, 2048);
                $settings['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
                $settings['border_width'] = in_array($_POST['border_width'], [0, 1, 2, 3, 4, 5]) ? (int) $_POST['border_width'] : 0;
                $settings['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
                $settings['border_color'] = !verify_hex_color($_POST['border_color']) ? '#000000' : $_POST['border_color'];
                $settings['border_shadow_offset_x'] = in_array($_POST['border_shadow_offset_x'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_x'] : 0;
                $settings['border_shadow_offset_y'] = in_array($_POST['border_shadow_offset_y'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_y'] : 0;
                $settings['border_shadow_blur'] = in_array($_POST['border_shadow_blur'], range(0, 20)) ? (int) $_POST['border_shadow_blur'] : 0;
                $settings['border_shadow_spread'] = in_array($_POST['border_shadow_spread'], range(0, 10)) ? (int) $_POST['border_shadow_spread'] : 0;
                $settings['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color']) ? '#00000000' : $_POST['border_shadow_color'];
                $settings['background_color'] = !verify_hex_color($_POST['background_color']) ? '#ffffff' : $_POST['background_color'];
                break;
                
            case 'list':
                $settings['list_type'] = in_array($_POST['list_type'], ['ordered', 'unordered']) ? query_clean($_POST['list_type']) : 'unordered';
                $settings['background_color'] = !verify_hex_color($_POST['background_color']) ? '#000000' : $_POST['background_color'];
                $settings['border_width'] = isset($_POST['border_width']) ? (int) $_POST['border_width'] : 0;
                $settings['border_color'] = !verify_hex_color($_POST['border_color']) ? '#000000' : $_POST['border_color'];
                $settings['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
                $settings['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'outset', 'inset']) ? query_clean($_POST['border_style']) : 'solid';
                $settings['border_shadow_offset_x'] = isset($_POST['border_shadow_offset_x']) ? (int) $_POST['border_shadow_offset_x'] : 0;
                $settings['border_shadow_offset_y'] = isset($_POST['border_shadow_offset_y']) ? (int) $_POST['border_shadow_offset_y'] : 0;
                $settings['border_shadow_blur'] = isset($_POST['border_shadow_blur']) ? (int) $_POST['border_shadow_blur'] : 0;
                $settings['border_shadow_spread'] = isset($_POST['border_shadow_spread']) ? (int) $_POST['border_shadow_spread'] : 0;
                $settings['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color']) ? '#000000' : $_POST['border_shadow_color'];
                $settings['margin_items_y'] = isset($_POST['margin_items_y']) ? (int) $_POST['margin_items_y'] : 2;
                $settings['margin_items_x'] = isset($_POST['margin_items_x']) ? (int) $_POST['margin_items_x'] : 1;

                // Process list items
                $list_items = [];
                if(isset($_POST['list_items'])) {
                    foreach($_POST['list_items'] as $key => $list_item) {
                        if(empty(trim($list_item))) continue;
                        if($key >= 100) continue;
                        $list_items[] = mb_substr(input_clean($list_item), 0, 256);
                    }
                }
                $settings['list_items'] = $list_items;
                break;
        }

        /* Database query */
        db()->where('microsite_block_id', $_POST['microsite_block_id'])->update('microsites_blocks', [
            'settings' => json_encode($settings),
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
