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
 * Image Slider Block Handler
 * 
 * Handles the creation and updating of image slider biolink blocks.
 */
class ImageSliderBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['image_slider'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'image_slider';
        $settings = json_encode([
            'images' => [],
            'autoplay' => true,
            'autoplay_speed' => 3000,
            'show_dots' => true,
            'show_arrows' => true,
            'border_radius' => 'rounded',

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
            'location_url' => null,
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
        $_POST['autoplay'] = isset($_POST['autoplay']);
        $_POST['autoplay_speed'] = in_array($_POST['autoplay_speed'], range(1000, 10000, 500)) ? (int) $_POST['autoplay_speed'] : 3000;
        $_POST['show_dots'] = isset($_POST['show_dots']);
        $_POST['show_arrows'] = isset($_POST['show_arrows']);
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';

        /* Display settings */
        $this->process_display_settings();

        if(!$biolink_block = db()->where('biolink_block_id', $_POST['biolink_block_id'])->where('user_id', $this->user->user_id)->getOne('biolinks_blocks')) {
            die();
        }
        $biolink_block->settings = json_decode($biolink_block->settings ?? '');

        /* Process image uploads */
        $images = [];
        if(isset($_FILES['images'])) {
            foreach($_FILES['images']['name'] as $key => $value) {
                if(empty($value)) continue;
                if($key >= 10) continue;

                $file_name = $_FILES['images']['name'][$key];
                $file_extension = explode('.', $file_name);
                $file_extension = mb_strtolower(end($file_extension));
                $file_temp = $_FILES['images']['tmp_name'][$key];

                if($_FILES['images']['error'][$key] == UPLOAD_ERR_OK && $_FILES['images']['size'][$key] <= settings()->links->thumbnail_image_size_limit * 1000000) {
                    if(in_array($file_extension, \Altum\Uploads::get_whitelisted_file_extensions('images'))) {
                        $new_file_name = md5(time() . $file_name . $key) . '.' . $file_extension;
                        $full_path = \Altum\Uploads::get_full_path('block_thumbnail_images') . $new_file_name;

                        if(move_uploaded_file($file_temp, $full_path)) {
                            $images[] = [
                                'image' => $new_file_name,
                                'alt' => $_POST['image_alt'][$key] ?? '',
                                'url' => $_POST['image_url'][$key] ?? ''
                            ];
                        }
                    }
                }
            }
        }

        /* Keep existing images if no new ones uploaded */
        if(empty($images) && isset($biolink_block->settings->images)) {
            $images = $biolink_block->settings->images;
        }

        $settings = json_encode([
            'images' => $images,
            'autoplay' => $_POST['autoplay'],
            'autoplay_speed' => $_POST['autoplay_speed'],
            'show_dots' => $_POST['show_dots'],
            'show_arrows' => $_POST['show_arrows'],
            'border_radius' => $_POST['border_radius'],

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
            'settings' => $settings,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $biolink_block->link_id);

        Response::json(l('global.success_message.update2'), 'success');
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
