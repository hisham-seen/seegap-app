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
 * Cover Block Handler
 * 
 * Handles the creation and updating of cover microsite blocks.
 */
class CoverBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['cover'];
    }
    
    public function create($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['name'] = mb_substr(input_clean($_POST['name'] ?? ''), 0, 128);
        $_POST['background_type'] = in_array($_POST['background_type'] ?? 'image', ['image', 'video']) ? query_clean($_POST['background_type']) : 'image';
        $_POST['video_url'] = mb_substr(input_clean($_POST['video_url'] ?? ''), 0, 2048);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $type = 'cover';

        /* Background image upload */
        $db_background = $this->handle_file_upload('', 'background', 'background_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], 'backgrounds/', settings()->links->background_size_limit);

        /* Avatar image upload */
        $db_avatar = $this->handle_file_upload('', 'avatar', 'avatar_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], 'avatars/', settings()->links->avatar_size_limit);

        $settings = json_encode([
            'name' => $_POST['name'],
            'background_type' => $_POST['background_type'],
            'background' => $db_background,
            'video_url' => $_POST['video_url'],
            'avatar' => $db_avatar,
            'avatar_size' => 100,
            'background_alt' => '',
            'avatar_alt' => '',
            'object_fit' => 'cover',
            'border_radius' => 'rounded',
            'border_width' => 0,
            'border_style' => 'solid',
            'border_color' => '#ffffff',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000010',
            'video_controls' => 0,
            'video_autoplay' => 1,
            'video_loop' => 1,
            'video_muted' => 1,
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
        $_POST['name'] = mb_substr(input_clean($_POST['name'] ?? ''), 0, 128);
        $_POST['background_type'] = in_array($_POST['background_type'] ?? 'image', ['image', 'video']) ? query_clean($_POST['background_type']) : 'image';
        $_POST['video_url'] = mb_substr(input_clean($_POST['video_url'] ?? ''), 0, 2048);
        $_POST['avatar_size'] = in_array($_POST['avatar_size'] ?? 100, range(50, 200)) ? (int) $_POST['avatar_size'] : 100;
        $_POST['background_alt'] = mb_substr(input_clean($_POST['background_alt'] ?? ''), 0, 256);
        $_POST['avatar_alt'] = mb_substr(input_clean($_POST['avatar_alt'] ?? ''), 0, 256);
        $_POST['object_fit'] = in_array($_POST['object_fit'] ?? 'cover', ['cover', 'contain', 'fill']) ? query_clean($_POST['object_fit']) : 'cover';
        $_POST['border_radius'] = in_array($_POST['border_radius'] ?? 'rounded', ['straight', 'round', 'rounded']) ? query_clean($_POST['border_radius']) : 'rounded';
        $_POST['border_width'] = in_array($_POST['border_width'] ?? 0, [0, 1, 2, 3, 4, 5]) ? (int) $_POST['border_width'] : 0;
        $_POST['border_style'] = in_array($_POST['border_style'] ?? 'solid', ['solid', 'dashed', 'double', 'inset', 'outset']) ? query_clean($_POST['border_style']) : 'solid';
        $_POST['border_color'] = !verify_hex_color($_POST['border_color'] ?? '') ? '#ffffff' : $_POST['border_color'];
        $_POST['border_shadow_offset_x'] = in_array($_POST['border_shadow_offset_x'] ?? 0, range(-20, 20)) ? (int) $_POST['border_shadow_offset_x'] : 0;
        $_POST['border_shadow_offset_y'] = in_array($_POST['border_shadow_offset_y'] ?? 0, range(-20, 20)) ? (int) $_POST['border_shadow_offset_y'] : 0;
        $_POST['border_shadow_blur'] = in_array($_POST['border_shadow_blur'] ?? 0, range(0, 20)) ? (int) $_POST['border_shadow_blur'] : 0;
        $_POST['border_shadow_spread'] = in_array($_POST['border_shadow_spread'] ?? 0, range(0, 10)) ? (int) $_POST['border_shadow_spread'] : 0;
        $_POST['border_shadow_color'] = !verify_hex_color($_POST['border_shadow_color'] ?? '') ? '#00000010' : $_POST['border_shadow_color'];
        $_POST['video_controls'] = (int) ($_POST['video_controls'] ?? 0);
        $_POST['video_autoplay'] = (int) ($_POST['video_autoplay'] ?? 1);
        $_POST['video_loop'] = (int) ($_POST['video_loop'] ?? 1);
        $_POST['video_muted'] = (int) ($_POST['video_muted'] ?? 1);
        $_POST['open_in_new_tab'] = (bool) ($_POST['open_in_new_tab'] ?? false);

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        /* Check for any errors */
        $required_fields = ['name'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        /* Background image upload */
        $db_background = $this->handle_file_upload($microsite_block->settings->background ?? '', 'background', 'background_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], 'backgrounds/', settings()->links->background_size_limit);

        /* Avatar image upload */
        $db_avatar = $this->handle_file_upload($microsite_block->settings->avatar ?? '', 'avatar', 'avatar_remove', ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'], 'avatars/', settings()->links->avatar_size_limit);

        $background_url = $db_background ? \SeeGap\Uploads::get_full_url('backgrounds') . $db_background : null;
        $avatar_url = $db_avatar ? \SeeGap\Uploads::get_full_url('avatars') . $db_avatar : null;

        $settings = json_encode([
            'name' => $_POST['name'],
            'background_type' => $_POST['background_type'],
            'background' => $db_background,
            'video_url' => $_POST['video_url'],
            'avatar' => $db_avatar,
            'avatar_size' => $_POST['avatar_size'],
            'background_alt' => $_POST['background_alt'],
            'avatar_alt' => $_POST['avatar_alt'],
            'object_fit' => $_POST['object_fit'],
            'border_radius' => $_POST['border_radius'],
            'border_width' => $_POST['border_width'],
            'border_style' => $_POST['border_style'],
            'border_color' => $_POST['border_color'],
            'border_shadow_offset_x' => $_POST['border_shadow_offset_x'],
            'border_shadow_offset_y' => $_POST['border_shadow_offset_y'],
            'border_shadow_blur' => $_POST['border_shadow_blur'],
            'border_shadow_spread' => $_POST['border_shadow_spread'],
            'border_shadow_color' => $_POST['border_shadow_color'],
            'video_controls' => $_POST['video_controls'],
            'video_autoplay' => $_POST['video_autoplay'],
            'video_loop' => $_POST['video_loop'],
            'video_muted' => $_POST['video_muted'],
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
        db()->where('microsite_block_id', $_POST['microsite_block_id'])->update('microsites_blocks', [
            'location_url' => $_POST['location_url'] ?? null,
            'settings' => $settings,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);

        Response::json(l('global.success_message.update2'), 'success', ['images' => ['background' => $background_url, 'avatar' => $avatar_url]]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
