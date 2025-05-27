<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\BiolinkBlocks\Blocks;

use Altum\Controllers\BiolinkBlocks\BaseBlockHandler;
use Altum\Response;

defined('ALTUMCODE') || die();

/**
 * Image Block Handler
 * 
 * Handles the creation and updating of image biolink blocks.
 */
class ImageBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['image'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = get_url($_POST['location_url']);

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

            /* Display settings */
            'display_continents' => [],
            'display_countries' => [],
            'display_cities' => [],
            'display_devices' => [],
            'display_languages' => [],
            'display_operating_systems' => [],
            'display_browsers' => [],
        ]);

        $settings = $this->process_biolink_theme_id_settings($link, $settings, $type);

        /* Database query */
        db()->insert('biolinks_blocks', [
            'user_id' => $this->user->user_id,
            'link_id' => $_POST['link_id'],
            'type' => $type,
            'location_url' => $_POST['location_url'],
            'settings' => $settings,
            'order' => settings()->links->biolinks_new_blocks_position == 'top' ? -$this->total_biolink_blocks : $this->total_biolink_blocks,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $_POST['link_id']);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=blocks')]);
    }
    
    public function update($type) {
        $_POST['biolink_block_id'] = (int) $_POST['biolink_block_id'];
        $_POST['location_url'] = get_url($_POST['location_url']);
        $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
        $_POST['open_in_new_tab'] = (int) isset($_POST['open_in_new_tab']);

        /* Display settings */
        $this->process_display_settings();

        if(!$biolink_block = db()->where('biolink_block_id', $_POST['biolink_block_id'])->where('user_id', $this->user->user_id)->getOne('biolinks_blocks')) {
            die();
        }
        $biolink_block->settings = json_decode($biolink_block->settings ?? '');

        $this->check_location_url($_POST['location_url'], true);

        /* Image upload */
        $db_image = $this->handle_image_upload($biolink_block->settings->image, 'block_images/', settings()->links->image_size_limit);

        $image_url = $db_image ? \Altum\Uploads::get_full_url('block_images') . $db_image : null;

        $settings = json_encode([
            'image' => $db_image,
            'image_alt' => $_POST['image_alt'],
            'open_in_new_tab' => $_POST['open_in_new_tab'],

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
        db()->where('biolink_block_id', $_POST['biolink_block_id'])->update('biolinks_blocks', [
            'location_url' => $_POST['location_url'],
            'settings' => $settings,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $biolink_block->link_id);

        Response::json(l('global.success_message.update2'), 'success', ['images' => ['image' => $image_url]]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
