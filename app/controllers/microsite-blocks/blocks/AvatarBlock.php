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
 * Avatar Block Handler
 * 
 * Handles the creation and updating of avatar microsite blocks.
 */
class AvatarBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['avatar'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['template'] = in_array($_POST['template'], ['classic', 'gradient_ring', 'professional', 'creative', 'minimalist', 'neon_glow', 'double_ring', 'status_ring']) ? $_POST['template'] : 'classic';
        $_POST['size'] = in_array($_POST['size'], [64, 80, 96, 100, 120, 128, 140, 160]) ? (int) $_POST['size'] : 100;
        $_POST['avatar_shape'] = in_array($_POST['avatar_shape'] ?? 'circle', ['circle', 'square']) ? $_POST['avatar_shape'] : 'circle';
        $_POST['square_border_radius'] = (int) ($_POST['square_border_radius'] ?? 8);
        $_POST['open_in_new_tab'] = isset($_POST['open_in_new_tab']);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'avatar';
        
        /* Handle avatar image upload - make it optional for creation */
        $db_image = '';
        if (!empty($_FILES['image']['name'])) {
            $db_image = $this->handle_image_upload(null, 'avatars/', settings()->links->avatar_size_limit);
        }

        /* Handle cover image upload */
        $db_cover_image = '';
        if (!empty($_FILES['cover_image']['name'])) {
            $db_cover_image = $this->handle_file_upload(null, 'cover_image', 'cover_image_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], 'backgrounds/', settings()->links->background_size_limit ?? 10);
        }

        $settings = json_encode([
            'image' => $db_image,
            'template' => $_POST['template'],
            'size' => $_POST['size'],
            'image_alt' => $_POST['image_alt'] ?? '',
            'open_in_new_tab' => $_POST['open_in_new_tab'],
            
            /* Avatar shape and styling */
            'avatar_shape' => $_POST['avatar_shape'],
            'square_border_radius' => $_POST['square_border_radius'],
            'background_color' => $_POST['background_color'] ?? '#ffffff',
            
            /* Cover image settings */
            'cover_image' => $db_cover_image,
            'cover_position' => $_POST['cover_position'] ?? 'center',
            'cover_blur' => (int) ($_POST['cover_blur'] ?? 0),
            'cover_overlay_color' => $_POST['cover_overlay_color'] ?? '#000000',
            'cover_overlay_opacity' => (int) ($_POST['cover_overlay_opacity'] ?? 0),
            
            /* Mobile-optimized defaults */
            'mobile_size' => $_POST['size'],
            'tablet_size' => min($_POST['size'] + 20, 160),
            'desktop_size' => min($_POST['size'] + 40, 200),
            
            /* Template-based styling */
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_width' => 0,
            'border_color' => '#ffffff',
            'hover_effect' => 'none',
            
            /* Verified badge system */
            'verified_badge' => [
                'enabled' => false,
                'style' => 'checkmark',
                'position' => 'bottom_right',
                'color' => '#1da1f2',
                'size' => 'medium'
            ],
            
            /* Mobile optimizations */
            'mobile_optimizations' => [
                'touch_feedback' => true,
                'high_contrast_mode' => false,
                'reduced_motion' => false
            ],

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
            'location_url' => $_POST['location_url'] ?? null,
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
        $_POST['template'] = in_array($_POST['template'] ?? 'classic', ['classic', 'gradient_ring', 'professional', 'creative', 'minimalist', 'neon_glow', 'double_ring', 'status_ring']) ? $_POST['template'] : 'classic';
        $_POST['size'] = in_array((int) ($_POST['size'] ?? 100), [64, 80, 96, 100, 120, 128, 140, 160]) ? (int) $_POST['size'] : 100;
        $_POST['avatar_shape'] = in_array($_POST['avatar_shape'] ?? 'circle', ['circle', 'square']) ? $_POST['avatar_shape'] : 'circle';
        $_POST['square_border_radius'] = (int) ($_POST['square_border_radius'] ?? 8);
        $_POST['border_radius'] = in_array($_POST['border_radius'] ?? 'rounded', ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
        $_POST['hover_effect'] = in_array($_POST['hover_effect'] ?? 'none', ['none', 'zoom', 'glow', 'rotate', 'bounce']) ? $_POST['hover_effect'] : 'none';
        $_POST['cover_position'] = in_array($_POST['cover_position'] ?? 'center', ['center', 'top-left', 'top-right', 'bottom-left', 'bottom-right']) ? $_POST['cover_position'] : 'center';
        $_POST['cover_blur'] = (int) ($_POST['cover_blur'] ?? 0);
        $_POST['cover_overlay_opacity'] = (int) ($_POST['cover_overlay_opacity'] ?? 0);
        $_POST['open_in_new_tab'] = isset($_POST['open_in_new_tab']);
        
        /* Verified badge settings */
        $_POST['verified_badge_enabled'] = isset($_POST['verified_badge_enabled']);
        $_POST['verified_badge_style'] = in_array($_POST['verified_badge_style'] ?? 'checkmark', ['checkmark', 'star', 'crown', 'shield']) ? $_POST['verified_badge_style'] : 'checkmark';
        $_POST['verified_badge_position'] = in_array($_POST['verified_badge_position'] ?? 'bottom_right', ['bottom_right', 'top_right', 'bottom_left', 'center_bottom']) ? $_POST['verified_badge_position'] : 'bottom_right';
        $_POST['verified_badge_size'] = in_array($_POST['verified_badge_size'] ?? 'medium', ['small', 'medium', 'large']) ? $_POST['verified_badge_size'] : 'medium';
        $_POST['verified_badge_color'] = !empty($_POST['verified_badge_color']) ? $_POST['verified_badge_color'] : '#1da1f2';

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        /* Avatar image upload */
        $db_image = $this->handle_image_upload($microsite_block->settings->image ?? '', 'avatars/', settings()->links->avatar_size_limit);

        /* Cover image upload */
        $db_cover_image = $this->handle_file_upload($microsite_block->settings->cover_image ?? '', 'cover_image', 'cover_image_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], 'backgrounds/', settings()->links->background_size_limit ?? 10);

        $image_url = $db_image ? \SeeGap\Uploads::get_full_url('avatars') . $db_image : null;
        $cover_image_url = $db_cover_image ? \SeeGap\Uploads::get_full_url('backgrounds') . $db_cover_image : null;

        $settings = json_encode([
            'image' => $db_image,
            'template' => $_POST['template'],
            'size' => $_POST['size'],
            'image_alt' => $_POST['image_alt'] ?? ($microsite_block->settings->image_alt ?? ''),
            'open_in_new_tab' => $_POST['open_in_new_tab'],
            
            /* Avatar shape and styling */
            'avatar_shape' => $_POST['avatar_shape'],
            'square_border_radius' => $_POST['square_border_radius'],
            'background_color' => $_POST['background_color'] ?? ($microsite_block->settings->background_color ?? '#ffffff'),
            
            /* Cover image settings */
            'cover_image' => $db_cover_image,
            'cover_position' => $_POST['cover_position'],
            'cover_blur' => $_POST['cover_blur'],
            'cover_overlay_color' => $_POST['cover_overlay_color'] ?? ($microsite_block->settings->cover_overlay_color ?? '#000000'),
            'cover_overlay_opacity' => $_POST['cover_overlay_opacity'],
            
            /* Mobile-optimized sizes */
            'mobile_size' => $_POST['size'],
            'tablet_size' => min($_POST['size'] + 20, 160),
            'desktop_size' => min($_POST['size'] + 40, 200),
            
            /* Template-based styling */
            'border_radius' => $_POST['border_radius'],
            'border_style' => $_POST['border_style'] ?? 'solid',
            'border_width' => (int) ($_POST['border_width'] ?? 0),
            'border_color' => $_POST['border_color'] ?? '#ffffff',
            'hover_effect' => $_POST['hover_effect'],
            
            /* Verified badge system */
            'verified_badge' => [
                'enabled' => $_POST['verified_badge_enabled'],
                'style' => $_POST['verified_badge_style'],
                'position' => $_POST['verified_badge_position'],
                'color' => $_POST['verified_badge_color'],
                'size' => $_POST['verified_badge_size']
            ],
            
            /* Mobile optimizations */
            'mobile_optimizations' => [
                'touch_feedback' => isset($_POST['touch_feedback']) ? $_POST['touch_feedback'] : true,
                'high_contrast_mode' => isset($_POST['high_contrast_mode']) ? $_POST['high_contrast_mode'] : false,
                'reduced_motion' => isset($_POST['reduced_motion']) ? $_POST['reduced_motion'] : false
            ],

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
            'location_url' => $_POST['location_url'] ?? null,
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
