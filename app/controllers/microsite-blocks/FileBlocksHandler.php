<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers\MicrositeBlocks;

use SeeGap\Response;

defined('SEEGAP') || die();

/**
 * Handler for file-based microsite blocks
 * 
 * Handles 6 file-based block types including audio, video, documents, and presentations.
 */
class FileBlocksHandler extends BaseBlockHandler {
    
    /**
     * Get supported block types for this handler
     * 
     * @return array Array of supported block type names
     */
    public function getSupportedTypes() {
        return [
            'audio', 'video', 'file', 'pdf_document', 'powerpoint_presentation', 'excel_spreadsheet'
        ];
    }
    
    /**
     * Create a new file block
     * 
     * @param string $type Block type to create
     * @return void
     */
    public function create($type) {
        $this->create_microsite_file($type);
    }
    
    /**
     * Update an existing file block
     * 
     * @param string $type Block type to update
     * @return void
     */
    public function update($type) {
        $this->update_microsite_file($type);
    }
    
    /**
     * Create a file-based microsite block
     * 
     * @param string $type The file block type
     * @return void
     */
    private function create_microsite_file($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['name'] = mb_substr(query_clean($_POST['name']), 0, 128);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        /* File upload */
        $size_limit = in_array($type, ['file', 'pdf_document', 'powerpoint_presentation', 'excel_spreadsheet']) ? settings()->links->file_size_limit : settings()->links->{$type . '_size_limit'};
        $db_file = $this->handle_file_upload(null, 'file', 'file_remove', $this->microsite_blocks[$type]['whitelisted_file_extensions'], 'files/', $size_limit);

        $settings = [
            'file' => $db_file,
            'name' => $_POST['name'],

            /* Display settings */
            'display_continents' => [],
            'display_countries' => [],
            'display_cities' => [],
            'display_devices' => [],
            'display_languages' => [],
            'display_operating_systems' => [],
            'display_browsers' => [],
        ];

        if($type == 'video') {
            $settings['poster_url'] = get_url($_POST['poster_url'] ?? null);
            $settings['video_autoplay'] = false; //(int) isset($_POST['video_autoplay']);
            $settings['video_controls'] = true; //(int) isset($_POST['video_controls']);
            $settings['video_loop'] = false; //(int) isset($_POST['video_loop']);
            $settings['video_muted'] = true; //(int) isset($_POST['video_muted']);
        }

        if($type == 'audio') {
            $settings['audio_autoplay'] = false; //(int) isset($_POST['audio_autoplay']);
            $settings['audio_controls'] = true; //(int) isset($_POST['audio_controls']);
            $settings['audio_loop'] = false; //(int) isset($_POST['audio_loop']);
            $settings['audio_muted'] = true; //(int) isset($_POST['audio_muted']);
        }

        if(in_array($type, ['file', 'pdf_document', 'powerpoint_presentation', 'excel_spreadsheet'])) {
            $settings = array_merge($settings, [
                'text_color' => 'black',
                'text_alignment' => 'center',
                'background_color' => 'white',
                'border_shadow_offset_x' => 0,
                'border_shadow_offset_y' => 0,
                'border_shadow_blur' => 20,
                'border_shadow_spread' => 0,
                'border_shadow_color' => '#00000010',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => 'white',
                'border_radius' => 'rounded',
                'animation' => false,
                'animation_runs' => 'repeat-1',
                'icon' => '',
                'image' => '',
                'open_in_new_tab' => true,
            ]);
        }

        $settings = json_encode($settings);

        $settings = $this->process_microsite_theme_id_settings($link, $settings, $type);

        /* Database query */
        db()->insert('microsites_blocks', [
            'user_id' => $this->user->user_id,
            'link_id' => $_POST['link_id'],
            'type' => $type,
            'settings' => $settings,
            'order' => settings()->links->microsites_new_blocks_position == 'top' ? -$this->total_microsite_blocks : $this->total_microsite_blocks,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('microsite_blocks?link_id=' . $_POST['link_id']);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=blocks')]);
    }

    /**
     * Update a file-based microsite block
     * 
     * @param string $type The file block type
     * @return void
     */
    private function update_microsite_file($type) {
        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];
        $_POST['name'] = mb_substr(query_clean($_POST['name']), 0, 128);
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
        $_POST['border_width'] = in_array($_POST['border_width'], [0, 1, 2, 3, 4, 5]) ? (int) $_POST['border_width'] : 0;
        $_POST['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
        $_POST['border_color'] = !verify_hex_color($_POST['border_color']) ? '#000000' : $_POST['border_color'];
        $_POST['border_shadow_offset_x'] = in_array($_POST['border_shadow_offset_x'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_x'] : 0;
        $_POST['border_shadow_offset_y'] = in_array($_POST['border_shadow_offset_y'], range(-20, 20)) ? (int) $_POST['border_shadow_offset_y'] : 0;
        $_POST['border_shadow_blur'] = in_array($_POST['border_shadow_blur'], range(0, 20)) ? (int) $_POST['border_shadow_blur'] : 0;
        $_POST['border_shadow_spread'] = in_array($_POST['border_shadow_spread'], range(0, 10)) ? (int) $_POST['border_shadow_spread'] : 0;
        $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color']) ? '#000000' : $_POST['border_shadow_color'];
        $_POST['animation'] = in_array($_POST['animation'], require APP_PATH . 'includes/microsite_animations.php') || $_POST['animation'] == 'false' ? query_clean($_POST['animation']) : false;
        $_POST['animation_runs'] = isset($_POST['animation_runs']) && in_array($_POST['animation_runs'], ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? query_clean($_POST['animation_runs']) : false;
        $_POST['icon'] = query_clean($_POST['icon']);
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#000000' : $_POST['text_color'];
        $_POST['text_alignment'] = in_array($_POST['text_alignment'], ['center', 'left', 'right', 'justify']) ? query_clean($_POST['text_alignment']) : 'center';
        $_POST['background_color'] = !verify_hex_color($_POST['background_color']) ? '#ffffff' : $_POST['background_color'];
        $_POST['open_in_new_tab'] = (int) isset($_POST['open_in_new_tab']);

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        /* File upload */
        $size_limit = in_array($type, ['file', 'pdf_document', 'powerpoint_presentation', 'excel_spreadsheet']) ? settings()->links->file_size_limit : settings()->links->{$type . '_size_limit'};
        $db_file = $this->handle_file_upload($microsite_block->settings->file, 'file', 'file_remove', $this->microsite_blocks[$type]['whitelisted_file_extensions'], 'files/', $size_limit);

        $settings = [
            'file' => $db_file,
            'name' => $_POST['name'],

            /* Display settings */
            'display_continents' => $_POST['display_continents'],
            'display_countries' => $_POST['display_countries'],
            'display_cities' => $_POST['display_cities'],
            'display_devices' => $_POST['display_devices'],
            'display_languages' => $_POST['display_languages'],
            'display_operating_systems' => $_POST['display_operating_systems'],
            'display_browsers' => $_POST['display_browsers'],
        ];

        if(in_array($type, ['file', 'pdf_document', 'powerpoint_presentation', 'excel_spreadsheet'])) {
            /* Image upload */
            $db_image = $this->handle_image_upload($microsite_block->settings->image, 'block_thumbnail_images/', settings()->links->thumbnail_image_size_limit);

            $settings = array_merge($settings, [
                'text_color' => $_POST['text_color'],
                'text_alignment' => $_POST['text_alignment'],
                'background_color' => $_POST['background_color'],
                'border_radius' => $_POST['border_radius'],
                'border_width' => $_POST['border_width'],
                'border_style' => $_POST['border_style'],
                'border_color' => $_POST['border_color'],
                'border_shadow_offset_x' => $_POST['border_shadow_offset_x'],
                'border_shadow_offset_y' => $_POST['border_shadow_offset_y'],
                'border_shadow_blur' => $_POST['border_shadow_blur'],
                'border_shadow_spread' => $_POST['border_shadow_spread'],
                'border_shadow_color' => $_POST['border_shadow_color'],
                'animation' => $_POST['animation'],
                'animation_runs' => $_POST['animation_runs'],
                'icon' => $_POST['icon'],
                'image' => $db_image,
                'open_in_new_tab' => $_POST['open_in_new_tab'],
            ]);
        }

        if($type == 'video') {
            $settings['poster_url'] = get_url($_POST['poster_url'] ?? null);
            $settings['video_autoplay'] = (int) isset($_POST['video_autoplay']);
            $settings['video_controls'] = (int) isset($_POST['video_controls']);
            $settings['video_loop'] = (int) isset($_POST['video_loop']);
            $settings['video_muted'] = (int) isset($_POST['video_muted']);
        }

        if($type == 'audio') {
            $settings['audio_autoplay'] = (int) isset($_POST['audio_autoplay']);
            $settings['audio_controls'] = (int) isset($_POST['audio_controls']);
            $settings['audio_loop'] = (int) isset($_POST['audio_loop']);
            $settings['audio_muted'] = (int) isset($_POST['audio_muted']);
        }

        $settings = json_encode($settings);

        /* Database query */
        db()->where('microsite_block_id', $_POST['microsite_block_id'])->update('microsites_blocks', [
            'settings' => $settings,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

        Response::json(l('global.success_message.update2'), 'success', ['images' => ['image' => $image_url ?? null]]);
    }
}
