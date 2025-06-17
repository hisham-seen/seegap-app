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
 * Image Grid Block Handler
 * 
 * Advanced implementation for image grid microsite blocks with comprehensive image management.
 */
class ImageGridBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['image_grid'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['open_in_new_tab'] = isset($_POST['open_in_new_tab']);
        
        // Grid-specific settings with validation
        $_POST['columns'] = in_array($_POST['columns'], range(1, 6)) ? (int) $_POST['columns'] : 3;
        $_POST['grid_gap'] = in_array($_POST['grid_gap'], range(0, 50)) ? (int) $_POST['grid_gap'] : 10;
        $_POST['image_height'] = max(100, min(500, (int) ($_POST['image_height'] ?? 200)));
        $_POST['aspect_ratio'] = in_array($_POST['aspect_ratio'], ['16:9', '4:3', '1:1', '21:9', 'custom']) ? $_POST['aspect_ratio'] : '1:1';
        $_POST['image_fit'] = in_array($_POST['image_fit'], ['cover', 'contain', 'fill', 'scale-down']) ? $_POST['image_fit'] : 'cover';
        $_POST['border_radius'] = in_array($_POST['border_radius'], range(0, 50)) ? (int) $_POST['border_radius'] : 0;
        $_POST['hover_effect'] = in_array($_POST['hover_effect'], ['none', 'zoom', 'fade', 'lift']) ? $_POST['hover_effect'] : 'none';

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'image_grid';
        
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
            'open_in_new_tab' => $_POST['open_in_new_tab'],

            /* Grid-specific Visual & Layout Settings */
            'columns' => $_POST['columns'],
            'grid_gap' => $_POST['grid_gap'],
            'image_height' => $_POST['image_height'],
            'aspect_ratio' => $_POST['aspect_ratio'],
            'image_fit' => $_POST['image_fit'],
            'border_radius' => $_POST['border_radius'],
            'hover_effect' => $_POST['hover_effect'],

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
        $_POST['open_in_new_tab'] = isset($_POST['open_in_new_tab']);
        
        // Grid-specific settings with validation
        $_POST['columns'] = in_array($_POST['columns'], range(1, 6)) ? (int) $_POST['columns'] : 3;
        $_POST['grid_gap'] = in_array($_POST['grid_gap'], range(0, 50)) ? (int) $_POST['grid_gap'] : 10;
        $_POST['image_height'] = max(100, min(500, (int) ($_POST['image_height'] ?? 200)));
        $_POST['aspect_ratio'] = in_array($_POST['aspect_ratio'], ['16:9', '4:3', '1:1', '21:9', 'custom']) ? $_POST['aspect_ratio'] : '1:1';
        $_POST['image_fit'] = in_array($_POST['image_fit'], ['cover', 'contain', 'fill', 'scale-down']) ? $_POST['image_fit'] : 'cover';
        $_POST['border_radius'] = in_array($_POST['border_radius'], range(0, 50)) ? (int) $_POST['border_radius'] : 0;
        $_POST['hover_effect'] = in_array($_POST['hover_effect'], ['none', 'zoom', 'fade', 'lift']) ? $_POST['hover_effect'] : 'none';

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
            'open_in_new_tab' => $_POST['open_in_new_tab'],

            /* Grid-specific Visual & Layout Settings */
            'columns' => $_POST['columns'],
            'grid_gap' => $_POST['grid_gap'],
            'image_height' => $_POST['image_height'],
            'aspect_ratio' => $_POST['aspect_ratio'],
            'image_fit' => $_POST['image_fit'],
            'border_radius' => $_POST['border_radius'],
            'hover_effect' => $_POST['hover_effect'],

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
