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
 * Text Block Handler
 * 
 * Handles the creation and updating of unified text microsite blocks.
 * Combines functionality of heading, paragraph, and list blocks.
 */
class TextBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['text'];
    }
    
    /**
     * Sanitize HTML content while preserving WYSIWYG formatting
     * Allows safe HTML tags that Quill editor generates
     */
    private function sanitizeWysiwygContent($content) {
        // Remove dangerous attributes and scripts first
        $content = preg_replace('/on\w+="[^"]*"/i', '', $content); // Remove onclick, onload, etc.
        $content = preg_replace('/javascript:/i', '', $content); // Remove javascript: URLs
        $content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $content); // Remove script tags
        
        // Allow these HTML tags that Quill uses (preserve all attributes for formatting)
        $allowed_tags = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's',
            'ol', 'ul', 'li',
            'a', 'span', 'div',
            'blockquote'
        ];
        
        // Use strip_tags to allow only safe tags while preserving all attributes
        $content = strip_tags($content, '<' . implode('><', $allowed_tags) . '>');
        
        return $content;
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'text';
        
        // Simplified settings for text block with WYSIWYG content
        $settings = [
            'content' => isset($_POST['content']) ? $this->sanitizeWysiwygContent($_POST['content']) : '',
            'text_color' => '#ffffff',
            'text_alignment' => 'center',
            
            /* Animation settings */
            'animation' => false,
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0,
            
            /* Styling defaults (transparent/minimal) */
            'background_color' => '#00000000', // Transparent
            'border_width' => 0, // No border
            'border_color' => '#ffffff',
            'border_radius' => 4, // 4px default radius
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0, // No shadow
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000000', // Transparent shadow
            
            /* Display settings */
            'display_continents' => [],
            'display_countries' => [],
            'display_cities' => [],
            'display_devices' => [],
            'display_languages' => [],
            'display_operating_systems' => [],
            'display_browsers' => [],
        ];

        $settings = json_encode($settings);
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
        $_POST['text_color'] = !verify_hex_color($_POST['text_color']) ? '#ffffff' : $_POST['text_color'];
        $_POST['text_alignment'] = in_array($_POST['text_alignment'], ['center', 'justify', 'left', 'right']) ? query_clean($_POST['text_alignment']) : 'center';

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        // Simplified settings for text block with WYSIWYG content
        $settings = [
            'content' => isset($_POST['content']) ? $this->sanitizeWysiwygContent($_POST['content']) : '',
            'text_color' => $_POST['text_color'],
            'text_alignment' => $_POST['text_alignment'],
            
            /* Animation settings */
            'animation' => isset($_POST['animation']) && $_POST['animation'] !== 'false' ? query_clean($_POST['animation']) : false,
            'animation_runs' => in_array($_POST['animation_runs'] ?? 'repeat-1', ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? query_clean($_POST['animation_runs']) : 'repeat-1',
            'animation_delay' => (int) ($_POST['animation_delay'] ?? 0),
            
            /* Background, border, and shadow settings */
            'background_color' => !verify_hex_color($_POST['background_color']) ? '#00000000' : $_POST['background_color'],
            'border_width' => in_array($_POST['border_width'], range(0, 20)) ? (int) $_POST['border_width'] : 0,
            'border_color' => !verify_hex_color($_POST['border_color']) ? '#ffffff' : $_POST['border_color'],
            'border_radius' => (is_numeric($_POST['border_radius']) && $_POST['border_radius'] >= 0 && $_POST['border_radius'] <= 50) ? (int) $_POST['border_radius'] : 4,
            'border_style' => in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid',
            'border_shadow_offset_x' => in_array($_POST['border_shadow_offset_x'], range(-50, 50)) ? (int) $_POST['border_shadow_offset_x'] : 0,
            'border_shadow_offset_y' => in_array($_POST['border_shadow_offset_y'], range(-50, 50)) ? (int) $_POST['border_shadow_offset_y'] : 0,
            'border_shadow_blur' => in_array($_POST['border_shadow_blur'], range(0, 50)) ? (int) $_POST['border_shadow_blur'] : 0,
            'border_shadow_spread' => in_array($_POST['border_shadow_spread'], range(0, 20)) ? (int) $_POST['border_shadow_spread'] : 0,
            'border_shadow_color' => !verify_hex_color($_POST['border_shadow_color']) ? '#00000000' : $_POST['border_shadow_color'],
            
            /* Display settings */
            'display_continents' => $_POST['display_continents'],
            'display_countries' => $_POST['display_countries'],
            'display_cities' => $_POST['display_cities'],
            'display_devices' => $_POST['display_devices'],
            'display_languages' => $_POST['display_languages'],
            'display_operating_systems' => $_POST['display_operating_systems'],
            'display_browsers' => $_POST['display_browsers'],
        ];

        /* Database query */
        db()->where('microsite_block_id', $_POST['microsite_block_id'])->update('microsites_blocks', [
            'settings' => json_encode($settings),
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
