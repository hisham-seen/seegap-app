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
 * Instagram Media Block Handler
 * 
 * Handles the creation and updating of Instagram media microsite blocks.
 */
class InstagramMediaBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['instagram_media'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['instagram_media_url'] = get_url($_POST['instagram_media_url']);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'instagram_media';
        $settings = json_encode([
            'instagram_media_url' => $_POST['instagram_media_url'],
            'display_mode' => $_POST['display_mode'] ?? 'page',
            'button_text' => $_POST['button_text'] ?? 'View Instagram Post',
            'button_icon' => $_POST['button_icon'] ?? 'fab fa-instagram',
            'open_in_new_tab' => isset($_POST['open_in_new_tab']),
            
            /* Button styling settings */
            'name' => $_POST['name'] ?? 'View Instagram Post',
            'image' => null,
            'icon' => $_POST['icon'] ?? 'fab fa-instagram',
            'text_color' => $_POST['text_color'] ?? '#ffffff',
            'text_alignment' => $_POST['text_alignment'] ?? 'center',
            'background_color' => $_POST['background_color'] ?? '#E4405F',
            'animation' => $_POST['animation'] ?? 'false',
            'animation_runs' => $_POST['animation_runs'] ?? 'repeat-1',
            
            /* Border settings */
            'border_width' => (int) ($_POST['border_width'] ?? 0),
            'border_color' => $_POST['border_color'] ?? '#000000',
            'border_radius' => $_POST['border_radius'] ?? 'rounded',
            'border_style' => $_POST['border_style'] ?? 'solid',
            
            /* Border shadow settings */
            'border_shadow_offset_x' => (int) ($_POST['border_shadow_offset_x'] ?? 0),
            'border_shadow_offset_y' => (int) ($_POST['border_shadow_offset_y'] ?? 0),
            'border_shadow_blur' => (int) ($_POST['border_shadow_blur'] ?? 0),
            'border_shadow_spread' => (int) ($_POST['border_shadow_spread'] ?? 0),
            'border_shadow_color' => $_POST['border_shadow_color'] ?? '#000000',

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
        $_POST['instagram_media_url'] = get_url($_POST['instagram_media_url']);

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        /* Check for any errors */
        $required_fields = ['instagram_media_url'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        $settings = json_encode([
            'instagram_media_url' => $_POST['instagram_media_url'],
            'display_mode' => $_POST['display_mode'] ?? 'page',
            'button_text' => $_POST['button_text'] ?? 'View Instagram Post',
            'button_icon' => $_POST['button_icon'] ?? 'fab fa-instagram',
            'open_in_new_tab' => isset($_POST['open_in_new_tab']),
            
            /* Button styling settings */
            'name' => $_POST['name'] ?? 'View Instagram Post',
            'image' => null,
            'icon' => $_POST['icon'] ?? 'fab fa-instagram',
            'text_color' => $_POST['text_color'] ?? '#ffffff',
            'text_alignment' => $_POST['text_alignment'] ?? 'center',
            'background_color' => $_POST['background_color'] ?? '#E4405F',
            'animation' => $_POST['animation'] ?? 'false',
            'animation_runs' => $_POST['animation_runs'] ?? 'repeat-1',
            
            /* Border settings */
            'border_width' => (int) ($_POST['border_width'] ?? 0),
            'border_color' => $_POST['border_color'] ?? '#000000',
            'border_radius' => $_POST['border_radius'] ?? 'rounded',
            'border_style' => $_POST['border_style'] ?? 'solid',
            
            /* Border shadow settings */
            'border_shadow_offset_x' => (int) ($_POST['border_shadow_offset_x'] ?? 0),
            'border_shadow_offset_y' => (int) ($_POST['border_shadow_offset_y'] ?? 0),
            'border_shadow_blur' => (int) ($_POST['border_shadow_blur'] ?? 0),
            'border_shadow_spread' => (int) ($_POST['border_shadow_spread'] ?? 0),
            'border_shadow_color' => $_POST['border_shadow_color'] ?? '#000000',

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
