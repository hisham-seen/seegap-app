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
 * Heading Block Handler
 * 
 * Handles the creation and updating of heading microsite blocks.
 */
class HeadingBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['heading'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['text'] = mb_substr(query_clean($_POST['text']), 0, 256);
        $_POST['heading_type'] = in_array($_POST['heading_type'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? query_clean($_POST['heading_type']) : 'h1';

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'heading';
        $settings = json_encode([
            'heading_type' => $_POST['heading_type'],
            'text' => $_POST['text'],
            'text_color' => '#ffffff',
            'text_alignment' => 'center',
            'verified_location' => '',

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
        $_POST['heading_type'] = in_array($_POST['heading_type'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? query_clean($_POST['heading_type']) : 'h1';
        $_POST['text'] = mb_substr(query_clean($_POST['text']), 0, 256);
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#ffffff' : $_POST['text_color'];
        $_POST['verified_location'] = isset($_POST['verified_location']) && in_array($_POST['verified_location'], ['', 'left', 'right']) ? query_clean($_POST['verified_location']) : '';

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        $settings = json_encode([
            'heading_type' => $_POST['heading_type'],
            'text' => $_POST['text'],
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'],
            'verified_location' => $_POST['verified_location'],

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
