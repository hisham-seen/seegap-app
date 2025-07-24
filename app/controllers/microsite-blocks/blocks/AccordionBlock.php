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
                    'content' => mb_substr(input_clean($_POST['item_content'][$key]), 0, 2048),
                ];
            }
        }

        $type = 'accordion';
        $settings = json_encode([
            'items' => $items,
            'text_color' => '#ffffff',
            'background_color' => '#00000000',

            /* Default styling settings */
            'text_alignment' => 'center',
            'border_width' => 0,
            'border_color' => '#000000',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#000000',

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
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#ffffff' : $_POST['text_color'];
        $_POST['background_color'] = !verify_hex_color($_POST['background_color']) ? '#00000000' : $_POST['background_color'];

        /* Accordion items */
        $items = [];
        if(isset($_POST['item_title']) && isset($_POST['item_content'])) {
            foreach($_POST['item_title'] as $key => $value) {
                if(empty(trim($value))) continue;
                if($key >= 20) continue;

                $items[] = [
                    'title' => mb_substr(input_clean($value), 0, 256),
                    'content' => mb_substr(input_clean($_POST['item_content'][$key]), 0, 2048),
                ];
            }
        }

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        /* Get existing settings to preserve fields not in the form */
        $existing_settings = json_decode($microsite_block->settings ?? '{}');

        $settings = json_encode([
            'items' => $items,
            'text_color' => $_POST['text_color'],
            'background_color' => $_POST['background_color'],

            /* Preserve existing styling settings */
            'text_alignment' => $existing_settings->text_alignment ?? 'center',
            'border_width' => $existing_settings->border_width ?? 0,
            'border_color' => $existing_settings->border_color ?? '#000000',
            'border_radius' => $existing_settings->border_radius ?? 'rounded',
            'border_style' => $existing_settings->border_style ?? 'solid',
            'border_shadow_offset_x' => $existing_settings->border_shadow_offset_x ?? 0,
            'border_shadow_offset_y' => $existing_settings->border_shadow_offset_y ?? 0,
            'border_shadow_blur' => $existing_settings->border_shadow_blur ?? 0,
            'border_shadow_spread' => $existing_settings->border_shadow_spread ?? 0,
            'border_shadow_color' => $existing_settings->border_shadow_color ?? '#000000',

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
