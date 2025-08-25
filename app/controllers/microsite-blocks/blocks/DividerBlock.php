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
 * Divider Block Handler
 * 
 * Handles the creation and updating of divider microsite blocks.
 */
class DividerBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['divider'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'divider';
        $settings = json_encode([
            'icon' => '',
            'show_icon' => false,
            'icon_size' => 20,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'divider_thickness' => 1,
            'divider_style' => 'solid',
            'divider_width' => 100,
            'divider_color' => '#e9ecef',

            /* Shadow settings */
            'border_shadow_color' => '#00000010',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,

            /* Animation settings */
            'animation' => false,
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0,

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
        $_POST['margin_top'] = in_array($_POST['margin_top'], range(0, 7)) ? (int) $_POST['margin_top'] : 0;
        $_POST['margin_bottom'] = in_array($_POST['margin_bottom'], range(0, 7)) ? (int) $_POST['margin_bottom'] : 0;
        $_POST['icon'] = query_clean($_POST['icon']);
        $_POST['show_icon'] = isset($_POST['show_icon']) ? true : false;
        $_POST['icon_size'] = in_array($_POST['icon_size'], range(12, 48)) ? (int) $_POST['icon_size'] : 20;
        $_POST['divider_thickness'] = in_array($_POST['divider_thickness'], range(1, 10)) ? (int) $_POST['divider_thickness'] : 1;
        $_POST['divider_style'] = in_array($_POST['divider_style'], ['solid', 'dashed', 'dotted']) ? $_POST['divider_style'] : 'solid';
        $_POST['divider_width'] = in_array($_POST['divider_width'], range(10, 100, 5)) ? (int) $_POST['divider_width'] : 100;
        $_POST['divider_color'] = !verify_hex_color($_POST['divider_color']) ? '#e9ecef' : $_POST['divider_color'];

        /* Shadow settings - updated to match settings components */
        $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color'] ?? '#00000010') ? '#00000010' : ($_POST['border_shadow_color'] ?? '#00000010');
        $_POST['border_shadow_offset_x'] = in_array(($_POST['border_shadow_offset_x'] ?? 0), range(-25, 25)) ? (int) ($_POST['border_shadow_offset_x'] ?? 0) : 0;
        $_POST['border_shadow_offset_y'] = in_array(($_POST['border_shadow_offset_y'] ?? 0), range(-25, 25)) ? (int) ($_POST['border_shadow_offset_y'] ?? 0) : 0;
        $_POST['border_shadow_blur'] = in_array(($_POST['border_shadow_blur'] ?? $_POST['border_shadow_blur_radius'] ?? 0), range(0, 30)) ? (int) ($_POST['border_shadow_blur'] ?? $_POST['border_shadow_blur_radius'] ?? 0) : 0;
        $_POST['border_shadow_spread'] = in_array(($_POST['border_shadow_spread'] ?? $_POST['border_shadow_spread_radius'] ?? 0), range(-15, 15)) ? (int) ($_POST['border_shadow_spread'] ?? $_POST['border_shadow_spread_radius'] ?? 0) : 0;

        /* Animation settings - updated to match settings components */
        $_POST['animation'] = $_POST['animation'] ?? $_POST['animation_type'] ?? false;
        if ($_POST['animation'] === 'false') $_POST['animation'] = false;
        $_POST['animation_runs'] = in_array($_POST['animation_runs'], ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? $_POST['animation_runs'] : 'repeat-1';
        $_POST['animation_delay'] = (int) ($_POST['animation_delay'] ?? 0);

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        $settings = json_encode([
            'icon' => $_POST['icon'],
            'show_icon' => $_POST['show_icon'],
            'icon_size' => $_POST['icon_size'],
            'margin_top' => $_POST['margin_top'],
            'margin_bottom' => $_POST['margin_bottom'],
            'divider_thickness' => $_POST['divider_thickness'],
            'divider_style' => $_POST['divider_style'],
            'divider_width' => $_POST['divider_width'],
            'divider_color' => $_POST['divider_color'],

            /* Shadow settings */
            'border_shadow_color' => $_POST['border_shadow_color'],
            'border_shadow_offset_x' => $_POST['border_shadow_offset_x'],
            'border_shadow_offset_y' => $_POST['border_shadow_offset_y'],
            'border_shadow_blur' => $_POST['border_shadow_blur'],
            'border_shadow_spread' => $_POST['border_shadow_spread'],

            /* Animation settings */
            'animation' => $_POST['animation'],
            'animation_runs' => $_POST['animation_runs'],
            'animation_delay' => $_POST['animation_delay'],

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
