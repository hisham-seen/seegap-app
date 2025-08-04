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
 * Socials Block Handler
 * 
 * Handles the creation and updating of socials microsite blocks.
 */
class SocialsBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['socials'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['color'] = !verify_hex_color($_POST['color'] ?? '') ? '#333333' : $_POST['color'];
        $_POST['background_color'] = !empty($_POST['background_color']) && verify_hex_color($_POST['background_color']) ? $_POST['background_color'] : '#FFFFFF00';
        $_POST['border_radius'] = (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 4;
        $_POST['size'] = (int) ($_POST['size'] ?? 24);
        $_POST['size'] = ($_POST['size'] >= 10 && $_POST['size'] <= 60) ? $_POST['size'] : 24;

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        /* Socials - store as associative array with platform keys */
        $socials = new \stdClass();
        if(isset($_POST['socials'])) {
            foreach($_POST['socials'] as $key => $social) {
                if(empty(trim($social))) continue;
                
                // Store with platform key for proper access in view
                $socials->{$key} = mb_substr(trim($social), 0, 1024);
            }
        }

        $type = 'socials';
        $settings = json_encode([
            'color' => $_POST['color'],
            'background_color' => $_POST['background_color'],
            'border_radius' => $_POST['border_radius'],
            'size' => $_POST['size'],
            'socials' => $socials,

            /* Border settings */
            'border_width' => 0,
            'border_style' => 'solid',
            'border_color' => '#000000',

            /* Shadow settings */
            'shadow_offset_x' => 0,
            'shadow_offset_y' => 0,
            'shadow_blur' => 0,
            'shadow_spread' => 0,
            'shadow_color' => '#000000',
            'shadow_inset' => false,

            /* Spacing settings */
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
            'padding_top' => 0,
            'padding_bottom' => 0,
            'padding_left' => 0,
            'padding_right' => 0,
            'internal_padding' => 0,
            'element_spacing' => 0,
            'content_margin' => 0,
            'block_gap' => 0,
            'section_spacing' => 0,

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
        $_POST['color'] = !verify_hex_color($_POST['color']) ? '#333333' : $_POST['color'];
        $_POST['background_color'] = !empty($_POST['background_color']) && verify_hex_color($_POST['background_color']) ? $_POST['background_color'] : '#FFFFFF00';
        $_POST['border_radius'] = (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 4;
        $_POST['size'] = (int) $_POST['size'];
        $_POST['size'] = ($_POST['size'] >= 10 && $_POST['size'] <= 60) ? $_POST['size'] : 24;

        /* Border settings */
        $_POST['border_width'] = isset($_POST['border_width']) ? (int) $_POST['border_width'] : 0;
        $_POST['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'dotted']) ? $_POST['border_style'] : 'solid';
        $_POST['border_color'] = !empty($_POST['border_color']) && verify_hex_color($_POST['border_color']) ? $_POST['border_color'] : '#000000';

        /* Shadow settings */
        $_POST['shadow_offset_x'] = isset($_POST['shadow_offset_x']) ? (int) $_POST['shadow_offset_x'] : 0;
        $_POST['shadow_offset_y'] = isset($_POST['shadow_offset_y']) ? (int) $_POST['shadow_offset_y'] : 0;
        $_POST['shadow_blur'] = isset($_POST['shadow_blur']) ? (int) $_POST['shadow_blur'] : 0;
        $_POST['shadow_spread'] = isset($_POST['shadow_spread']) ? (int) $_POST['shadow_spread'] : 0;
        $_POST['shadow_color'] = !empty($_POST['shadow_color']) && verify_hex_color($_POST['shadow_color']) ? $_POST['shadow_color'] : '#000000';
        $_POST['shadow_inset'] = isset($_POST['shadow_inset']) ? (bool) $_POST['shadow_inset'] : false;

        /* Spacing settings */
        $_POST['margin_top'] = isset($_POST['margin_top']) ? max(0, min(10, (int) $_POST['margin_top'])) : 0;
        $_POST['margin_bottom'] = isset($_POST['margin_bottom']) ? max(0, min(10, (int) $_POST['margin_bottom'])) : 0;
        $_POST['margin_left'] = isset($_POST['margin_left']) ? max(0, min(10, (int) $_POST['margin_left'])) : 0;
        $_POST['margin_right'] = isset($_POST['margin_right']) ? max(0, min(10, (int) $_POST['margin_right'])) : 0;
        $_POST['padding_top'] = isset($_POST['padding_top']) ? max(0, min(10, (int) $_POST['padding_top'])) : 0;
        $_POST['padding_bottom'] = isset($_POST['padding_bottom']) ? max(0, min(10, (int) $_POST['padding_bottom'])) : 0;
        $_POST['padding_left'] = isset($_POST['padding_left']) ? max(0, min(10, (int) $_POST['padding_left'])) : 0;
        $_POST['padding_right'] = isset($_POST['padding_right']) ? max(0, min(10, (int) $_POST['padding_right'])) : 0;
        $_POST['internal_padding'] = isset($_POST['internal_padding']) ? max(0, min(10, (int) $_POST['internal_padding'])) : 0;
        $_POST['element_spacing'] = isset($_POST['element_spacing']) ? max(0, min(10, (int) $_POST['element_spacing'])) : 0;
        $_POST['content_margin'] = isset($_POST['content_margin']) ? max(0, min(10, (int) $_POST['content_margin'])) : 0;
        $_POST['block_gap'] = isset($_POST['block_gap']) ? max(0, min(10, (int) $_POST['block_gap'])) : 0;
        $_POST['section_spacing'] = isset($_POST['section_spacing']) ? max(0, min(10, (int) $_POST['section_spacing'])) : 0;

        /* Socials - store as associative array with platform keys */
        $socials = new \stdClass();
        if(isset($_POST['socials'])) {
            foreach($_POST['socials'] as $key => $social) {
                if(empty(trim($social))) continue;
                
                // Store with platform key for proper access in view
                // Don't use get_url here as the view template handles URL formatting
                $socials->{$key} = mb_substr(trim($social), 0, 1024);
            }
        }

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        $settings = json_encode([
            'color' => $_POST['color'],
            'background_color' => $_POST['background_color'],
            'border_radius' => $_POST['border_radius'],
            'size' => $_POST['size'],
            'socials' => $socials,

            /* Border settings */
            'border_width' => $_POST['border_width'],
            'border_style' => $_POST['border_style'],
            'border_color' => $_POST['border_color'],

            /* Shadow settings */
            'shadow_offset_x' => $_POST['shadow_offset_x'],
            'shadow_offset_y' => $_POST['shadow_offset_y'],
            'shadow_blur' => $_POST['shadow_blur'],
            'shadow_spread' => $_POST['shadow_spread'],
            'shadow_color' => $_POST['shadow_color'],
            'shadow_inset' => $_POST['shadow_inset'],

            /* Spacing settings */
            'margin_top' => $_POST['margin_top'],
            'margin_bottom' => $_POST['margin_bottom'],
            'margin_left' => $_POST['margin_left'],
            'margin_right' => $_POST['margin_right'],
            'padding_top' => $_POST['padding_top'],
            'padding_bottom' => $_POST['padding_bottom'],
            'padding_left' => $_POST['padding_left'],
            'padding_right' => $_POST['padding_right'],
            'internal_padding' => $_POST['internal_padding'],
            'element_spacing' => $_POST['element_spacing'],
            'content_margin' => $_POST['content_margin'],
            'block_gap' => $_POST['block_gap'],
            'section_spacing' => $_POST['section_spacing'],

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
