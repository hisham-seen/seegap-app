<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\BiolinkBlocks;

use Altum\Response;
use Unirest\Request;

defined('ALTUMCODE') || die();

/**
 * Handler for embeddable biolink blocks
 * 
 * Handles 25 embeddable block types including social media, music/audio, video, and tools.
 */
class EmbeddableBlocksHandler extends BaseBlockHandler {
    
    /**
     * Get supported block types for this handler
     * 
     * @return array Array of supported block type names
     */
    public function getSupportedTypes() {
        return [
            'telegram', 'anchor', 'applemusic', 'soundcloud', 'threads', 'snapchat', 
            'spotify', 'tidal', 'mixcloud', 'kick', 'tiktok_video', 'typeform', 
            'calendly', 'tiktok_profile', 'twitch', 'twitter_tweet', 'twitter_video', 
            'twitter_profile', 'pinterest_profile', 'vimeo', 'youtube', 'instagram_media', 
            'facebook', 'reddit'
        ];
    }
    
    /**
     * Create a new embeddable block
     * 
     * @param string $type Block type to create
     * @return void
     */
    public function create($type) {
        $this->create_biolink_embeddable($type);
    }
    
    /**
     * Update an existing embeddable block
     * 
     * @param string $type Block type to update
     * @return void
     */
    public function update($type) {
        $this->update_biolink_embeddable($type);
    }
    
    /**
     * Create an embeddable biolink block
     * 
     * @param string $type The embeddable block type
     * @return void
     */
    private function create_biolink_embeddable($type) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = get_url($_POST['location_url']);
        $_POST['theme'] = isset($_POST['theme']) && in_array($_POST['theme'], ['light', 'dark']) ? query_clean($_POST['theme']) : null;

        $settings = [
            /* Display settings */
            'display_continents' => [],
            'display_countries' => [],
            'display_cities' => [],
            'display_devices' => [],
            'display_languages' => [],
            'display_operating_systems' => [],
            'display_browsers' => [],
        ];

        if($_POST['theme']) {
            $settings['theme'] = $_POST['theme'];
        }

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        /* Check for any errors */
        $required_fields = ['location_url'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        $this->check_location_url($_POST['location_url']);

        /* Make sure the location url is valid & get needed details */
        $host = parse_url($_POST['location_url'], PHP_URL_HOST);

        if(isset($this->biolink_blocks[$type]['whitelisted_hosts']) && !in_array($host, $this->biolink_blocks[$type]['whitelisted_hosts'])) {
            Response::json(l('link.error_message.invalid_location_url_embed'), 'error');
        }

        switch($type) {
            case 'reddit':
                $response = Request::get('https://www.reddit.com/oembed?url=' . $_POST['location_url']);

                if($response->code >= 400) {
                    Response::json(l('link.error_message.invalid_location_url_embed'), 'error');
                }

                $settings['content'] = $response->body->html;
                break;

            case 'youtube':

                $settings['video_autoplay'] = false;
                $settings['video_controls'] = true;
                $settings['video_loop'] = false;
                $settings['video_muted'] = false;

                break;

            case 'mixcloud':

                $settings['type'] = 'classic';

                break;
        }

        $settings = $this->process_biolink_theme_id_settings($link, $settings, $type);

        /* Database query */
        db()->insert('biolinks_blocks', [
            'user_id' => $this->user->user_id,
            'link_id' => $_POST['link_id'],
            'type' => $type,
            'location_url' => $_POST['location_url'],
            'settings' => json_encode($settings),
            'order' => settings()->links->biolinks_new_blocks_position == 'top' ? -$this->total_biolink_blocks : $this->total_biolink_blocks,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $_POST['link_id']);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=blocks')]);
    }

    /**
     * Update an embeddable biolink block
     * 
     * @param string $type The embeddable block type
     * @return void
     */
    private function update_biolink_embeddable($type) {
        $_POST['biolink_block_id'] = (int) $_POST['biolink_block_id'];
        $_POST['location_url'] = get_url($_POST['location_url']);
        $_POST['theme'] = isset($_POST['theme']) && in_array($_POST['theme'], ['light', 'dark']) ? query_clean($_POST['theme']) : null;

        /* Display settings */
        $this->process_display_settings();

        $settings = [
            /* Display settings */
            'display_continents' => $_POST['display_continents'],
            'display_countries' => $_POST['display_countries'],
            'display_cities' => $_POST['display_cities'],
            'display_devices' => $_POST['display_devices'],
            'display_languages' => $_POST['display_languages'],
            'display_operating_systems' => $_POST['display_operating_systems'],
            'display_browsers' => $_POST['display_browsers'],
        ];

        if($_POST['theme']) {
            $settings['theme'] = $_POST['theme'];
        }

        if(!$biolink_block = db()->where('biolink_block_id', $_POST['biolink_block_id'])->where('user_id', $this->user->user_id)->getOne('biolinks_blocks')) {
            die();
        }

        /* Check for any errors */
        $required_fields = ['location_url'];

        /* Check for any errors */
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }

        $this->check_location_url($_POST['location_url']);

        /* Make sure the location url is valid & get needed details */
        $host = parse_url($_POST['location_url'], PHP_URL_HOST);

        if(isset($this->biolink_blocks[$type]['whitelisted_hosts']) && !in_array($host, $this->biolink_blocks[$type]['whitelisted_hosts'])) {
            Response::json(l('link.error_message.invalid_location_url_embed'), 'error');
        }

        switch($type) {
            case 'reddit':
                $response = Request::get('https://www.reddit.com/oembed?url=' . $_POST['location_url']);

                if($response->code >= 400) {
                    Response::json(l('link.error_message.invalid_location_url_embed'), 'error');
                }

                $settings['content'] = $response->body->html;
                break;

            case 'youtube':

                $settings['video_autoplay'] = (int) isset($_POST['video_autoplay']);
                $settings['video_controls'] = (int) isset($_POST['video_controls']);
                $settings['video_loop'] = (int) isset($_POST['video_loop']);
                $settings['video_muted'] = (int) isset($_POST['video_muted']);

                break;

            case 'mixcloud':

                $settings['type'] = isset($_POST['type']) && in_array($_POST['type'], ['picture', 'classic', 'mini']) ? query_clean($_POST['type']) : 'classic';

                break;
        }

        /* Database query */
        db()->where('biolink_block_id', $_POST['biolink_block_id'])->update('biolinks_blocks', [
            'location_url' => $_POST['location_url'],
            'settings' => json_encode($settings),
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('biolink_blocks?link_id=' . $biolink_block->link_id);

        Response::json(l('global.success_message.update2'), 'success');
    }
}
