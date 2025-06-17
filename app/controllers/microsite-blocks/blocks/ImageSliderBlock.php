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
 * Image Slider Block Handler
 * 
 * Simple, clean implementation for image slider microsite blocks.
 */
class ImageSliderBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['image_slider'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['autoplay'] = isset($_POST['autoplay']);
        $_POST['display_arrows'] = isset($_POST['display_arrows']);
        $_POST['display_pagination'] = isset($_POST['display_pagination']);
        $_POST['open_in_new_tab'] = isset($_POST['open_in_new_tab']);
        $_POST['autoplay_interval'] = (int) ($_POST['autoplay_interval'] ?? 5);
        
        // Phase 1: Core Visual & Layout Settings - ensure minimum height
        $_POST['slider_height'] = max(200, min(800, (int) ($_POST['slider_height'] ?? 300)));
        $_POST['aspect_ratio'] = in_array($_POST['aspect_ratio'], ['16:9', '4:3', '1:1', '21:9', 'custom']) ? $_POST['aspect_ratio'] : 'custom';
        $_POST['image_fit'] = in_array($_POST['image_fit'], ['cover', 'contain', 'fill', 'scale-down']) ? $_POST['image_fit'] : 'cover';
        $_POST['border_radius'] = in_array($_POST['border_radius'], range(0, 50)) ? (int) $_POST['border_radius'] : 0;
        $_POST['transition_type'] = in_array($_POST['transition_type'], ['slide', 'fade', 'loop']) ? $_POST['transition_type'] : 'slide';
        $_POST['transition_speed'] = in_array($_POST['transition_speed'], range(200, 2000)) ? (int) $_POST['transition_speed'] : 600;
        $_POST['slides_per_view'] = in_array($_POST['slides_per_view'], range(1, 4)) ? (int) $_POST['slides_per_view'] : 1;
        $_POST['slide_gap'] = in_array($_POST['slide_gap'], range(0, 50)) ? (int) $_POST['slide_gap'] : 0;
        $_POST['pause_on_hover'] = isset($_POST['pause_on_hover']);
        $_POST['infinite_loop'] = isset($_POST['infinite_loop']);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'image_slider';
        
        // Handle initial image uploads using the system method
        $items = [];
        if(isset($_FILES['new_images']) && is_array($_FILES['new_images']['name'])) {
            foreach($_FILES['new_images']['name'] as $key => $file_name) {
                if(empty($file_name) || $_FILES['new_images']['error'][$key] != UPLOAD_ERR_OK) {
                    continue;
                }
                
                // Temporarily set the single file for the system upload handler
                $_FILES['image'] = [
                    'name' => $_FILES['new_images']['name'][$key],
                    'type' => $_FILES['new_images']['type'][$key],
                    'tmp_name' => $_FILES['new_images']['tmp_name'][$key],
                    'error' => $_FILES['new_images']['error'][$key],
                    'size' => $_FILES['new_images']['size'][$key]
                ];
                
                $db_image = $this->handle_image_upload(null, 'block_images/', settings()->links->image_size_limit);
                
                if($db_image) {
                    $items[] = [
                        'image' => $db_image,
                        'image_alt' => '',
                        'location_url' => ''
                    ];
                }
            }
            
            // Clean up the temporary $_FILES entry
            unset($_FILES['image']);
        }
        
        $settings = json_encode([
            'items' => $items,
            'autoplay' => $_POST['autoplay'],
            'autoplay_interval' => $_POST['autoplay_interval'],
            'display_arrows' => $_POST['display_arrows'],
            'display_pagination' => $_POST['display_pagination'],
            'open_in_new_tab' => $_POST['open_in_new_tab'],

            /* Phase 1: Core Visual & Layout Settings */
            'slider_height' => $_POST['slider_height'],
            'aspect_ratio' => $_POST['aspect_ratio'],
            'image_fit' => $_POST['image_fit'],
            'border_radius' => $_POST['border_radius'],
            'transition_type' => $_POST['transition_type'],
            'transition_speed' => $_POST['transition_speed'],
            'slides_per_view' => $_POST['slides_per_view'],
            'slide_gap' => $_POST['slide_gap'],
            'pause_on_hover' => $_POST['pause_on_hover'],
            'infinite_loop' => $_POST['infinite_loop'],

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
        $_POST['autoplay'] = isset($_POST['autoplay']);
        $_POST['display_arrows'] = isset($_POST['display_arrows']);
        $_POST['display_pagination'] = isset($_POST['display_pagination']);
        $_POST['open_in_new_tab'] = isset($_POST['open_in_new_tab']);
        $_POST['autoplay_interval'] = (int) ($_POST['autoplay_interval'] ?? 5);
        
        // Phase 1: Core Visual & Layout Settings - ensure minimum height
        $_POST['slider_height'] = max(200, min(800, (int) ($_POST['slider_height'] ?? 300)));
        $_POST['aspect_ratio'] = in_array($_POST['aspect_ratio'], ['16:9', '4:3', '1:1', '21:9', 'custom']) ? $_POST['aspect_ratio'] : 'custom';
        $_POST['image_fit'] = in_array($_POST['image_fit'], ['cover', 'contain', 'fill', 'scale-down']) ? $_POST['image_fit'] : 'cover';
        $_POST['border_radius'] = in_array($_POST['border_radius'], range(0, 50)) ? (int) $_POST['border_radius'] : 0;
        $_POST['transition_type'] = in_array($_POST['transition_type'], ['slide', 'fade', 'loop']) ? $_POST['transition_type'] : 'slide';
        $_POST['transition_speed'] = in_array($_POST['transition_speed'], range(200, 2000)) ? (int) $_POST['transition_speed'] : 600;
        $_POST['slides_per_view'] = in_array($_POST['slides_per_view'], range(1, 4)) ? (int) $_POST['slides_per_view'] : 1;
        $_POST['slide_gap'] = in_array($_POST['slide_gap'], range(0, 50)) ? (int) $_POST['slide_gap'] : 0;
        $_POST['pause_on_hover'] = isset($_POST['pause_on_hover']);
        $_POST['infinite_loop'] = isset($_POST['infinite_loop']);

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            Response::json('Block not found', 'error');
            return;
        }
        
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');
        
        // Handle image management (reordering, editing, removing)
        $items = [];
        
        // Check if we have updated images data from the form
        if(isset($_POST['images_data']) && !empty($_POST['images_data'])) {
            $updated_images_data = json_decode($_POST['images_data'], true);
            if(is_array($updated_images_data)) {
                foreach($updated_images_data as $item) {
                    $items[] = [
                        'image' => is_array($item) ? $item['image'] : $item->image,
                        'image_alt' => is_array($item) ? ($item['image_alt'] ?? '') : ($item->image_alt ?? ''),
                        'location_url' => is_array($item) ? ($item['location_url'] ?? '') : ($item->location_url ?? '')
                    ];
                }
            }
        } else {
            // Fallback to existing items if no updated data
            if(isset($microsite_block->settings->items) && is_array($microsite_block->settings->items)) {
                foreach($microsite_block->settings->items as $item) {
                    $items[] = [
                        'image' => is_object($item) ? $item->image : $item['image'],
                        'image_alt' => is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? ''),
                        'location_url' => is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '')
                    ];
                }
            }
        }
        
        // Apply reordering if specified
        if(isset($_POST['image_order']) && !empty($_POST['image_order'])) {
            $order = explode(',', $_POST['image_order']);
            $reordered_items = [];
            foreach($order as $index) {
                if(isset($items[$index])) {
                    $reordered_items[] = $items[$index];
                }
            }
            $items = $reordered_items;
        }
        
        // Handle new image uploads using the system method
        if(isset($_FILES['new_images']) && is_array($_FILES['new_images']['name'])) {
            foreach($_FILES['new_images']['name'] as $key => $file_name) {
                if(empty($file_name) || $_FILES['new_images']['error'][$key] != UPLOAD_ERR_OK) {
                    continue;
                }
                
                // Temporarily set the single file for the system upload handler
                $_FILES['image'] = [
                    'name' => $_FILES['new_images']['name'][$key],
                    'type' => $_FILES['new_images']['type'][$key],
                    'tmp_name' => $_FILES['new_images']['tmp_name'][$key],
                    'error' => $_FILES['new_images']['error'][$key],
                    'size' => $_FILES['new_images']['size'][$key]
                ];
                
                $db_image = $this->handle_image_upload(null, 'block_images/', settings()->links->image_size_limit);
                
                if($db_image) {
                    $items[] = [
                        'image' => $db_image,
                        'image_alt' => '',
                        'location_url' => ''
                    ];
                }
            }
            
            // Clean up the temporary $_FILES entry
            unset($_FILES['image']);
        }

        $settings = json_encode([
            'items' => $items,
            'autoplay' => $_POST['autoplay'],
            'autoplay_interval' => $_POST['autoplay_interval'],
            'display_arrows' => $_POST['display_arrows'],
            'display_pagination' => $_POST['display_pagination'],
            'open_in_new_tab' => $_POST['open_in_new_tab'],

            /* Phase 1: Core Visual & Layout Settings */
            'slider_height' => $_POST['slider_height'],
            'aspect_ratio' => $_POST['aspect_ratio'],
            'image_fit' => $_POST['image_fit'],
            'border_radius' => $_POST['border_radius'],
            'transition_type' => $_POST['transition_type'],
            'transition_speed' => $_POST['transition_speed'],
            'slides_per_view' => $_POST['slides_per_view'],
            'slide_gap' => $_POST['slide_gap'],
            'pause_on_hover' => $_POST['pause_on_hover'],
            'infinite_loop' => $_POST['infinite_loop'],

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
