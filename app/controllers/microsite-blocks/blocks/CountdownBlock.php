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
 * Countdown Block Handler
 * 
 * Handles the creation and updating of countdown microsite blocks.
 */
class CountdownBlock extends BaseBlockHandler {
    
    public function getSupportedTypes() {
        return ['countdown'];
    }
    
    public function create($type) {
        /* Enhanced debug logging */
        error_log("=== CountdownBlock CREATE START ===");
        error_log("CountdownBlock create called with type: " . $type);
        error_log("Raw POST data: " . json_encode($_POST));
        error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
        error_log("User ID: " . ($this->user->user_id ?? 'unknown'));
        
        $_POST['link_id'] = (int) $_POST['link_id'];
        error_log("Processed link_id: " . $_POST['link_id']);
        
        // Validate required fields
        if(empty($_POST['counter_end_date'])) {
            error_log("CountdownBlock create failed: empty counter_end_date");
            error_log("POST data: " . json_encode($_POST));
            Response::json(l('global.error_message.empty_fields'), 'error');
            return;
        }
        
        error_log("counter_end_date received: " . $_POST['counter_end_date']);
        
        // Validate and format date
        try {
            $end_date = new \DateTime($_POST['counter_end_date']);
            $current_date = new \DateTime();
            
            error_log("Date validation:");
            error_log("- Raw date: " . $_POST['counter_end_date']);
            error_log("- Parsed date: " . $end_date->format('Y-m-d H:i:s'));
            error_log("- Current date: " . $current_date->format('Y-m-d H:i:s'));
            error_log("- Is future: " . ($end_date > $current_date ? 'YES' : 'NO'));
            
            if($end_date <= $current_date) {
                error_log("CountdownBlock create failed: date not in future");
                Response::json('End date must be in the future', 'error');
                return;
            }
            
            $_POST['counter_end_date'] = $end_date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            error_log("CountdownBlock create failed: invalid date format");
            error_log("Exception: " . $e->getMessage());
            Response::json('Invalid date format', 'error');
            return;
        }
        
        // Validate theme
        $_POST['theme'] = in_array($_POST['theme'] ?? '', ['light', 'dark']) ? query_clean($_POST['theme']) : 'light';
        error_log("Theme validated: " . $_POST['theme']);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            error_log("CountdownBlock create failed: link not found");
            error_log("Link ID: " . $_POST['link_id'] . ", User ID: " . $this->user->user_id);
            die();
        }
        
        error_log("Link found successfully:");
        error_log("- Link ID: " . $link->link_id);
        error_log("- Link URL: " . ($link->url ?? 'unknown'));

        $type = 'countdown';
        $settings_array = [
            'counter_end_date' => $_POST['counter_end_date'],
            'style' => 'digital-led', // Single countdown style
            'theme' => $_POST['theme'],
            'text_color' => $_POST['theme'] === 'dark' ? '#ffffff' : '#000000', // Auto-set based on theme
            'background_color' => $_POST['theme'] === 'dark' ? '#2d3748' : '#ffffff', // Auto-set based on theme

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
        ];
        
        error_log("Settings array created: " . json_encode($settings_array));
        
        $settings = json_encode($settings_array);
        $settings = $this->process_microsite_theme_id_settings($link, $settings, $type);
        
        error_log("Final settings after processing: " . $settings);

        $insert_data = [
            'user_id' => $this->user->user_id,
            'link_id' => $_POST['link_id'],
            'type' => $type,
            'location_url' => null,
            'settings' => $settings,
            'order' => settings()->links->microsites_new_blocks_position == 'top' ? -$this->total_microsite_blocks : $this->total_microsite_blocks,
            'datetime' => get_date(),
        ];
        
        error_log("Insert data prepared: " . json_encode($insert_data));

        /* Database query */
        try {
            $insert_result = db()->insert('microsites_blocks', $insert_data);
            
            error_log("Database insert result: " . ($insert_result ? 'SUCCESS' : 'FAILED'));
            error_log("Insert ID: " . db()->getInsertId());
            error_log("Affected rows: " . db()->count);
        } catch (\Exception $e) {
            error_log("Database insert exception: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            Response::json('Database error occurred', 'error');
            return;
        }

        /* Clear the cache */
        error_log("Clearing cache for link_id: " . $_POST['link_id']);
        cache()->deleteItem('microsite_blocks?link_id=' . $_POST['link_id']);
        
        error_log("=== SENDING SUCCESS RESPONSE ===");
        error_log("Redirect URL: " . url('link/' . $_POST['link_id'] . '?tab=blocks'));
        error_log("=== CountdownBlock CREATE END ===");

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=blocks')]);
    }
    
    public function update($type) {
        $_POST['microsite_block_id'] = (int) $_POST['microsite_block_id'];
        
        // Validate required fields
        if(empty($_POST['counter_end_date'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
            return;
        }
        
        // Validate and format date
        try {
            $end_date = new \DateTime($_POST['counter_end_date']);
            $current_date = new \DateTime();
            
            if($end_date <= $current_date) {
                Response::json('End date must be in the future', 'error');
                return;
            }
            
            $_POST['counter_end_date'] = $end_date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Response::json('Invalid date format', 'error');
            return;
        }
        
        // Validate theme
        $_POST['theme'] = in_array($_POST['theme'] ?? '', ['light', 'dark']) ? query_clean($_POST['theme']) : 'light';

        /* Display settings */
        $this->process_display_settings();

        if(!$microsite_block = db()->where('microsite_block_id', $_POST['microsite_block_id'])->where('user_id', $this->user->user_id)->getOne('microsites_blocks')) {
            die();
        }

        $settings = json_encode([
            'counter_end_date' => $_POST['counter_end_date'],
            'style' => 'digital-led', // Single countdown style
            'theme' => $_POST['theme'],
            'text_color' => $_POST['theme'] === 'dark' ? '#ffffff' : '#000000', // Auto-set based on theme
            'background_color' => $_POST['theme'] === 'dark' ? '#2d3748' : '#ffffff', // Auto-set based on theme

            /* Animation settings */
            'animation' => false,
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0,

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
