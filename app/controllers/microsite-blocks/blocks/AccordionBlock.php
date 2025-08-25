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
 * Accordion Block Handler
 * 
 * Handles the creation and updating of Accordion microsite blocks.
 */
class AccordionBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['accordion'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#333333' : $_POST['text_color'];
        $_POST['background_color'] = !verify_hex_color($_POST['background_color']) ? '#ffffff' : $_POST['background_color'];
        $_POST['border_color'] = !verify_hex_color($_POST['border_color']) ? '#ffffff' : $_POST['border_color'];
        $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color']) ? '#00000010' : $_POST['border_shadow_color'];

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        /* Accordion items */
        $items = [];
        if(isset($_POST['item_title']) && isset($_POST['item_content'])) {
            foreach($_POST['item_title'] as $key => $value) {
                if(empty(trim($value))) continue;
                if($key >= 20) continue;

                $items[] = [
                    'title' => mb_substr(input_clean($value), 0, 256),
                    'content' => $_POST['item_content'][$key], // Keep HTML content from WYSIWYG
                    'open_default' => isset($_POST['item_open_default'][$key]) ? true : false,
                ];
            }
        }

        $type = 'accordion';
        $settings = json_encode([
            'items' => $items,
            
            /* Accordion behavior */
            'accordion_mode' => $_POST['accordion_mode'] ?? 'single',
            'default_state' => $_POST['default_state'] ?? 'first_open',
            
            /* Text styling */
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'] ?? 'center',
            
            /* Background */
            'background_color' => $_POST['background_color'],
            
            /* Border */
            'border_width' => (int) ($_POST['border_width'] ?? 0),
            'border_color' => $_POST['border_color'],
            'border_radius' => (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 0,
            'border_style' => $_POST['border_style'] ?? 'solid',
            
            /* Shadow */
            'border_shadow_offset_x' => (int) ($_POST['border_shadow_offset_x'] ?? 0),
            'border_shadow_offset_y' => (int) ($_POST['border_shadow_offset_y'] ?? 0),
            'border_shadow_blur' => (int) ($_POST['border_shadow_blur'] ?? 0),
            'border_shadow_spread' => (int) ($_POST['border_shadow_spread'] ?? 0),
            'border_shadow_color' => $_POST['border_shadow_color'],
            
            /* Animation */
            'animation' => $_POST['animation'] ?? false,
            'animation_runs' => $_POST['animation_runs'] ?? 'repeat-1',
            'animation_delay' => (int) ($_POST['animation_delay'] ?? 0),

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
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#333333' : $_POST['text_color'];
        $_POST['background_color'] = !verify_hex_color($_POST['background_color']) ? '#ffffff' : $_POST['background_color'];
        $_POST['border_color'] = !verify_hex_color($_POST['border_color']) ? '#ffffff' : $_POST['border_color'];
        $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color']) ? '#00000010' : $_POST['border_shadow_color'];

        /* Accordion items */
        $items = [];
        if(isset($_POST['item_title']) && isset($_POST['item_content'])) {
            foreach($_POST['item_title'] as $key => $value) {
                if(empty(trim($value))) continue;
                if($key >= 20) continue;

                $items[] = [
                    'title' => mb_substr(input_clean($value), 0, 256),
                    'content' => $_POST['item_content'][$key], // Keep HTML content from WYSIWYG
                    'open_default' => isset($_POST['item_open_default'][$key]) ? true : false,
                ];
            }
        }

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        $settings = json_encode([
            'items' => $items,
            
            /* Accordion behavior */
            'accordion_mode' => $_POST['accordion_mode'] ?? 'single',
            'default_state' => $_POST['default_state'] ?? 'first_open',
            
            /* Text styling */
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'] ?? 'center',
            
            /* Background */
            'background_color' => $_POST['background_color'],
            
            /* Border */
            'border_width' => (int) ($_POST['border_width'] ?? 0),
            'border_color' => $_POST['border_color'],
            'border_radius' => (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 0,
            'border_style' => $_POST['border_style'] ?? 'solid',
            
            /* Shadow */
            'border_shadow_offset_x' => (int) ($_POST['border_shadow_offset_x'] ?? 0),
            'border_shadow_offset_y' => (int) ($_POST['border_shadow_offset_y'] ?? 0),
            'border_shadow_blur' => (int) ($_POST['border_shadow_blur'] ?? 0),
            'border_shadow_spread' => (int) ($_POST['border_shadow_spread'] ?? 0),
            'border_shadow_color' => $_POST['border_shadow_color'],
            
            /* Animation */
            'animation' => $_POST['animation'] ?? false,
            'animation_runs' => $_POST['animation_runs'] ?? 'repeat-1',
            'animation_delay' => (int) ($_POST['animation_delay'] ?? 0),

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
