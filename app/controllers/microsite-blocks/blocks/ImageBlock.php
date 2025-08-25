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
 * Image Block Handler
 * 
 * Handles the creation and updating of image microsite blocks.
 */
class ImageBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['image'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = get_url($_POST['location_url']);
        
        // Process new flexible sizing fields
        $_POST['image_height'] = !empty($_POST['image_height']) ? (float) $_POST['image_height'] : null;
        $_POST['image_height_unit'] = in_array($_POST['image_height_unit'] ?? 'px', ['px', 'em', 'rem', '%', 'vw', 'vh']) ? $_POST['image_height_unit'] : 'px';
        $_POST['image_width'] = !empty($_POST['image_width']) ? (float) $_POST['image_width'] : null;
        $_POST['image_width_unit'] = in_array($_POST['image_width_unit'] ?? 'px', ['px', 'em', 'rem', '%', 'vw', 'vh']) ? $_POST['image_width_unit'] : 'px';

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $this->check_location_url($_POST['location_url'], true);

        /* Image upload */
        $db_image = $this->handle_image_upload(null, 'block_images/', settings()->links->image_size_limit);

        $type = 'image';
        $settings = json_encode([
            'image' => $db_image,
            'image_alt' => null,
            'open_in_new_tab' => false,
            'text_alignment' => $_POST['text_alignment'] ?? 'center',
            'image_height' => $_POST['image_height'],
            'image_height_unit' => $_POST['image_height_unit'],
            'image_width' => $_POST['image_width'],
            'image_width_unit' => $_POST['image_width_unit'],
            
            /* Style settings */
            'background_color' => !verify_hex_color($_POST['background_color'] ?? '#00000000') ? '#00000000' : $_POST['background_color'],
            'border_width' => in_array($_POST['border_width'] ?? 0, range(0, 20)) ? (int) $_POST['border_width'] : 0,
            'border_color' => !verify_hex_color($_POST['border_color'] ?? '#ffffff') ? '#ffffff' : $_POST['border_color'],
            'border_radius' => (is_numeric($_POST['border_radius'] ?? 4) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 4,
            'border_style' => in_array($_POST['border_style'] ?? 'solid', ['solid', 'dashed', 'double', 'inset', 'outset']) ? $_POST['border_style'] : 'solid',
            'border_shadow_offset_x' => in_array($_POST['border_shadow_offset_x'] ?? 0, range(-25, 25)) ? (int) $_POST['border_shadow_offset_x'] : 0,
            'border_shadow_offset_y' => in_array($_POST['border_shadow_offset_y'] ?? 0, range(-25, 25)) ? (int) $_POST['border_shadow_offset_y'] : 0,
            'border_shadow_blur' => in_array($_POST['border_shadow_blur'] ?? 0, range(0, 30)) ? (int) $_POST['border_shadow_blur'] : 0,
            'border_shadow_spread' => in_array($_POST['border_shadow_spread'] ?? 0, range(-15, 15)) ? (int) $_POST['border_shadow_spread'] : 0,
            'border_shadow_color' => !verify_hex_color($_POST['border_shadow_color'] ?? '#00000010') ? '#00000010' : $_POST['border_shadow_color'],
            'animation' => in_array($_POST['animation'] ?? 'false', array_merge(['false'], require APP_PATH . 'includes/microsite_animations.php')) ? $_POST['animation'] : false,
            'animation_runs' => in_array($_POST['animation_runs'] ?? 'repeat-1', ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? $_POST['animation_runs'] : 'repeat-1',
            'animation_delay' => (int) ($_POST['animation_delay'] ?? 0),

            /* Verified badge settings */
            'verified_badge' => [
                'enabled' => (int) isset($_POST['verified_badge_enabled']),
                'style' => $_POST['verified_badge_style'] ?? 'checkmark',
                'position' => $_POST['verified_badge_position'] ?? 'bottom_right',
                'size' => $_POST['verified_badge_size'] ?? 'medium',
                'color' => $_POST['verified_badge_color'] ?? '#1da1f2'
            ],

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
            'location_url' => $_POST['location_url'],
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
        $_POST['location_url'] = get_url($_POST['location_url']);
        $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
        $_POST['open_in_new_tab'] = (int) isset($_POST['open_in_new_tab']);
        
        // Process new flexible sizing fields
        $_POST['image_height'] = !empty($_POST['image_height']) ? (float) $_POST['image_height'] : null;
        $_POST['image_height_unit'] = in_array($_POST['image_height_unit'] ?? 'px', ['px', 'em', 'rem', '%', 'vw', 'vh']) ? $_POST['image_height_unit'] : 'px';
        $_POST['image_width'] = !empty($_POST['image_width']) ? (float) $_POST['image_width'] : null;
        $_POST['image_width_unit'] = in_array($_POST['image_width_unit'] ?? 'px', ['px', 'em', 'rem', '%', 'vw', 'vh']) ? $_POST['image_width_unit'] : 'px';

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        $this->check_location_url($_POST['location_url'], true);

        /* Image upload */
        $db_image = $this->handle_image_upload($microsite_block->settings->image, 'block_images/', settings()->links->image_size_limit);

        $image_url = $db_image ? \SeeGap\Uploads::get_full_url('block_images') . $db_image : null;

        $settings = json_encode([
            'image' => $db_image,
            'image_alt' => $_POST['image_alt'],
            'open_in_new_tab' => $_POST['open_in_new_tab'],
            'text_alignment' => $_POST['text_alignment'] ?? 'center',
            'image_height' => $_POST['image_height'],
            'image_height_unit' => $_POST['image_height_unit'],
            'image_width' => $_POST['image_width'],
            'image_width_unit' => $_POST['image_width_unit'],
            
            /* Style settings */
            'background_color' => !verify_hex_color($_POST['background_color'] ?? '#00000000') ? '#00000000' : $_POST['background_color'],
            'border_width' => in_array($_POST['border_width'] ?? 0, range(0, 20)) ? (int) $_POST['border_width'] : 0,
            'border_color' => !verify_hex_color($_POST['border_color'] ?? '#ffffff') ? '#ffffff' : $_POST['border_color'],
            'border_radius' => (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 4,
            'border_style' => in_array($_POST['border_style'] ?? 'solid', ['solid', 'dashed', 'double', 'inset', 'outset']) ? $_POST['border_style'] : 'solid',
            'border_shadow_offset_x' => in_array($_POST['border_shadow_offset_x'] ?? 0, range(-25, 25)) ? (int) $_POST['border_shadow_offset_x'] : 0,
            'border_shadow_offset_y' => in_array($_POST['border_shadow_offset_y'] ?? 0, range(-25, 25)) ? (int) $_POST['border_shadow_offset_y'] : 0,
            'border_shadow_blur' => in_array($_POST['border_shadow_blur'] ?? 0, range(0, 30)) ? (int) $_POST['border_shadow_blur'] : 0,
            'border_shadow_spread' => in_array($_POST['border_shadow_spread'] ?? 0, range(-15, 15)) ? (int) $_POST['border_shadow_spread'] : 0,
            'border_shadow_color' => !verify_hex_color($_POST['border_shadow_color'] ?? '#00000010') ? '#00000010' : $_POST['border_shadow_color'],
            'animation' => in_array($_POST['animation'] ?? 'false', array_merge(['false'], require APP_PATH . 'includes/microsite_animations.php')) ? $_POST['animation'] : false,
            'animation_runs' => in_array($_POST['animation_runs'] ?? 'repeat-1', ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? $_POST['animation_runs'] : 'repeat-1',
            'animation_delay' => (int) ($_POST['animation_delay'] ?? 0),

            /* Verified badge settings */
            'verified_badge' => [
                'enabled' => (int) isset($_POST['verified_badge_enabled']),
                'style' => $_POST['verified_badge_style'] ?? 'checkmark',
                'position' => $_POST['verified_badge_position'] ?? 'bottom_right',
                'size' => $_POST['verified_badge_size'] ?? 'medium',
                'color' => $_POST['verified_badge_color'] ?? '#1da1f2'
            ],

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
            'location_url' => $_POST['location_url'],
            'settings' => $settings,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

        Response::json(l('global.success_message.update2'), 'success', ['images' => ['image' => $image_url]]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
