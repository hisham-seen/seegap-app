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
 * Service Block Handler
 * 
 * Handles the creation and updating of service microsite blocks.
 */
class ServiceBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['service'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['title'] = mb_substr(input_clean($_POST['title']), 0, 128);
        $_POST['description'] = mb_substr(input_clean($_POST['description']), 0, 256);
        $_POST['price'] = mb_substr(query_clean($_POST['price']), 0, 32);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'service';
        $settings = json_encode([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'currency' => 'USD',
            'button_text' => 'Book Now',
            'button_url' => '',
            'image' => '',
            'text_color' => '#ffffff',
            'button_text_color' => '#ffffff',
            'button_background_color' => '#007bff',
            'background_color' => '#00000000',
            'border_radius' => 'rounded',
            'border_width' => 0,
            'border_style' => 'solid',
            'border_color' => '#ffffff',

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
        $_POST['title'] = mb_substr(input_clean($_POST['title']), 0, 128);
        $_POST['description'] = mb_substr(input_clean($_POST['description']), 0, 256);
        $_POST['price'] = mb_substr(query_clean($_POST['price']), 0, 32);
        $_POST['currency'] = mb_substr(query_clean($_POST['currency']), 0, 8);
        $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 64);
        $_POST['button_url'] = get_url($_POST['button_url']);
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#ffffff' : $_POST['text_color'];
        $_POST['button_text_color'] = !verify_hex_color($_POST['button_text_color']) ? '#ffffff' : $_POST['button_text_color'];
        $_POST['button_background_color'] = !verify_hex_color($_POST['button_background_color']) ? '#007bff' : $_POST['button_background_color'];
        $_POST['background_color'] = !verify_hex_color($_POST['background_color']) ? '#00000000' : $_POST['background_color'];
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
        $_POST['border_width'] = in_array($_POST['border_width'], [0, 1, 2, 3, 4, 5]) ? (int) $_POST['border_width'] : 0;
        $_POST['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
        $_POST['border_color'] = !verify_hex_color($_POST['border_color']) ? '#ffffff' : $_POST['border_color'];

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        /* Check for any errors */
        $required_fields = ['title'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        /* Image upload */
        $db_image = $this->handle_image_upload($microsite_block->settings->image, 'block_thumbnail_images/', settings()->links->thumbnail_image_size_limit);

        $image_url = $db_image ? \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $db_image : null;

        $settings = json_encode([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'currency' => $_POST['currency'],
            'button_text' => $_POST['button_text'],
            'button_url' => $_POST['button_url'],
            'image' => $db_image,
            'text_color' => $_POST['text_color'],
            'button_text_color' => $_POST['button_text_color'],
            'button_background_color' => $_POST['button_background_color'],
            'background_color' => $_POST['background_color'],
            'border_radius' => $_POST['border_radius'],
            'border_width' => $_POST['border_width'],
            'border_style' => $_POST['border_style'],
            'border_color' => $_POST['border_color'],

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

        Response::json(l('global.success_message.update2'), 'success', ['images' => ['image' => $image_url]]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
