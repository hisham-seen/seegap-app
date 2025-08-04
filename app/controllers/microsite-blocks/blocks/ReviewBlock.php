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
 * Review Block Handler
 * 
 * Handles the creation and updating of review microsite blocks.
 */
class ReviewBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['review'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        // Create default review item
        $default_review = [
            'title' => '',
            'description' => '',
            'author_name' => '',
            'author_description' => '',
            'stars' => 5,
            'image' => ''
        ];

        $type = 'review';
        $settings = json_encode([
            // Multiple reviews structure
            'reviews' => [$default_review],
            
            // Slider behavior settings
            'slider_mode' => 'manual', // 'manual', 'auto'
            'auto_play' => false,
            'slide_duration' => 5, // seconds
            'show_navigation' => true,
            'show_indicators' => true,
            'transition_effect' => 'slide', // 'slide', 'fade'
            
            // Global styling (applies to all reviews)
            'title_color' => '#333333',
            'description_color' => '#666666',
            'author_name_color' => '#333333',
            'author_description_color' => '#666666',
            'stars_color' => '#ffc107',
            'background_color' => '#ffffff',
            'text_alignment' => 'center',
            'border_radius' => 4, // Updated to pixel-based
            'border_width' => 1,
            'border_style' => 'solid',
            'border_color' => '#dee2e6',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000010',

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
        
        // Process slider behavior settings
        $_POST['slider_mode'] = in_array($_POST['slider_mode'] ?? 'manual', ['manual', 'auto']) ? query_clean($_POST['slider_mode']) : 'manual';
        $_POST['auto_play'] = (int) isset($_POST['auto_play']);
        $_POST['slide_duration'] = in_array($_POST['slide_duration'] ?? 5, range(3, 10)) ? (int) $_POST['slide_duration'] : 5;
        $_POST['show_navigation'] = (int) isset($_POST['show_navigation']);
        $_POST['show_indicators'] = (int) isset($_POST['show_indicators']);
        $_POST['transition_effect'] = in_array($_POST['transition_effect'] ?? 'slide', ['slide', 'fade']) ? query_clean($_POST['transition_effect']) : 'slide';
        
        // Process global styling settings
        $_POST['title_color'] = !verify_hex_color($_POST['title_color'] ?? '') ? '#333333' : $_POST['title_color'];
        $_POST['description_color'] = !verify_hex_color($_POST['description_color'] ?? '') ? '#666666' : $_POST['description_color'];
        $_POST['author_name_color'] = !verify_hex_color($_POST['author_name_color'] ?? '') ? '#333333' : $_POST['author_name_color'];
        $_POST['author_description_color'] = !verify_hex_color($_POST['author_description_color'] ?? '') ? '#666666' : $_POST['author_description_color'];
        $_POST['stars_color'] = !verify_hex_color($_POST['stars_color'] ?? '') ? '#ffc107' : $_POST['stars_color'];
        $_POST['background_color'] = !verify_hex_color($_POST['background_color'] ?? '') ? '#ffffff' : $_POST['background_color'];
        $_POST['text_alignment'] = in_array($_POST['text_alignment'] ?? 'center', ['center', 'left', 'right', 'justify']) ? query_clean($_POST['text_alignment']) : 'center';
        $_POST['border_radius'] = (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 4;
        $_POST['border_width'] = in_array($_POST['border_width'] ?? 1, range(0, 20)) ? (int) $_POST['border_width'] : 1;
        $_POST['border_style'] = in_array($_POST['border_style'] ?? 'solid', ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
        $_POST['border_color'] = !verify_hex_color($_POST['border_color'] ?? '') ? '#dee2e6' : $_POST['border_color'];
        $_POST['border_shadow_offset_x'] = in_array($_POST['border_shadow_offset_x'] ?? 0, range(-50, 50)) ? (int) $_POST['border_shadow_offset_x'] : 0;
        $_POST['border_shadow_offset_y'] = in_array($_POST['border_shadow_offset_y'] ?? 0, range(-50, 50)) ? (int) $_POST['border_shadow_offset_y'] : 0;
        $_POST['border_shadow_blur'] = in_array($_POST['border_shadow_blur'] ?? 0, range(0, 50)) ? (int) $_POST['border_shadow_blur'] : 0;
        $_POST['border_shadow_spread'] = in_array($_POST['border_shadow_spread'] ?? 0, range(0, 20)) ? (int) $_POST['border_shadow_spread'] : 0;
        $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color'] ?? '') ? '#00000010' : $_POST['border_shadow_color'];
        
        // Process animation settings
        $_POST['animation'] = isset($_POST['animation']) && $_POST['animation'] !== 'false' ? query_clean($_POST['animation']) : false;
        $_POST['animation_runs'] = in_array($_POST['animation_runs'] ?? 'repeat-1', ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? query_clean($_POST['animation_runs']) : 'repeat-1';
        $_POST['animation_delay'] = isset($_POST['animation_delay']) ? (int) $_POST['animation_delay'] : 0;

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        // Process multiple reviews
        $reviews = [];
        $image_urls = [];
        
        if(isset($_POST['review_title']) && is_array($_POST['review_title'])) {
            foreach($_POST['review_title'] as $key => $title) {
                if($key >= 20) continue; // Max 20 reviews
                
                $review = [
                    'title' => mb_substr(input_clean($title), 0, 128),
                    'description' => mb_substr(input_clean($_POST['review_description'][$key] ?? ''), 0, 1024),
                    'author_name' => mb_substr(query_clean($_POST['review_author_name'][$key] ?? ''), 0, 128),
                    'author_description' => mb_substr(query_clean($_POST['review_author_description'][$key] ?? ''), 0, 128),
                    'stars' => in_array($_POST['review_stars'][$key] ?? 5, [1, 2, 3, 4, 5]) ? (int) $_POST['review_stars'][$key] : 5,
                    'image' => ''
                ];
                
                // Handle image upload for each review
                if(isset($_FILES['review_image']['name'][$key]) && !empty($_FILES['review_image']['name'][$key])) {
                    // Create temporary $_FILES structure for individual image
                    $temp_files = [
                        'review_image' => [
                            'name' => $_FILES['review_image']['name'][$key],
                            'type' => $_FILES['review_image']['type'][$key],
                            'tmp_name' => $_FILES['review_image']['tmp_name'][$key],
                            'error' => $_FILES['review_image']['error'][$key],
                            'size' => $_FILES['review_image']['size'][$key]
                        ]
                    ];
                    
                    $old_files = $_FILES;
                    $_FILES = $temp_files;
                    
                    $existing_image = isset($microsite_block->settings->reviews[$key]->image) ? $microsite_block->settings->reviews[$key]->image : '';
                    $db_image = $this->handle_image_upload($existing_image, 'block_images/', settings()->links->image_size_limit);
                    $review['image'] = $db_image;
                    
                    if($db_image) {
                        $image_urls['review_image_' . $key] = \SeeGap\Uploads::get_full_url('block_images') . $db_image;
                    }
                    
                    $_FILES = $old_files;
                } else {
                    // Keep existing image if no new upload
                    $review['image'] = isset($microsite_block->settings->reviews[$key]->image) ? $microsite_block->settings->reviews[$key]->image : '';
                }
                
                // Only add review if author name is provided (required field)
                if(!empty($review['author_name'])) {
                    $reviews[] = $review;
                }
            }
        }
        
        // Ensure at least one review exists
        if(empty($reviews)) {
            $reviews[] = [
                'title' => '',
                'description' => '',
                'author_name' => 'Anonymous',
                'author_description' => '',
                'stars' => 5,
                'image' => ''
            ];
        }

        $settings = json_encode([
            // Multiple reviews structure
            'reviews' => $reviews,
            
            // Slider behavior settings
            'slider_mode' => $_POST['slider_mode'],
            'auto_play' => $_POST['auto_play'],
            'slide_duration' => $_POST['slide_duration'],
            'show_navigation' => $_POST['show_navigation'],
            'show_indicators' => $_POST['show_indicators'],
            'transition_effect' => $_POST['transition_effect'],
            
            // Global styling (applies to all reviews)
            'title_color' => $_POST['title_color'],
            'description_color' => $_POST['description_color'],
            'author_name_color' => $_POST['author_name_color'],
            'author_description_color' => $_POST['author_description_color'],
            'stars_color' => $_POST['stars_color'],
            'background_color' => $_POST['background_color'],
            'text_alignment' => $_POST['text_alignment'],
            'border_radius' => $_POST['border_radius'],
            'border_width' => $_POST['border_width'],
            'border_style' => $_POST['border_style'],
            'border_color' => $_POST['border_color'],
            'border_shadow_offset_x' => $_POST['border_shadow_offset_x'],
            'border_shadow_offset_y' => $_POST['border_shadow_offset_y'],
            'border_shadow_blur' => $_POST['border_shadow_blur'],
            'border_shadow_spread' => $_POST['border_shadow_spread'],
            'border_shadow_color' => $_POST['border_shadow_color'],

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

        Response::json(l('global.success_message.update2'), 'success', ['images' => $image_urls]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
