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

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'socials';
        $settings = json_encode([
            'color' => '#ffffff',
            'background_color' => '#FFFFFF00',
            'border_radius' => 'rounded',
            'size' => 'l',
            'socials' => new \stdClass(),

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
        $_POST['color'] = !verify_hex_color($_POST['color']) ? '#ffffff' : $_POST['color'];
        $_POST['background_color'] = !empty($_POST['background_color']) && verify_hex_color($_POST['background_color']) ? $_POST['background_color'] : '#FFFFFF00';
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? $_POST['border_radius'] : 'rounded';
        $_POST['size'] = in_array($_POST['size'], ['s', 'm', 'l', 'xl']) ? $_POST['size'] : 'l';

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
