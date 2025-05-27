<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\LinkHandlers\Handlers;

use Altum\Controllers\LinkHandlers\BaseLinkHandler;
use Altum\Response;
use Altum\Date;

defined('ALTUMCODE') || die();

/**
 * Event Handler
 * 
 * Handles the creation and updating of calendar event links.
 */
class EventHandler extends BaseLinkHandler {
    
    public function getSupportedTypes() {
        return ['event'];
    }
    
    public function create($type) {
        if(!settings()->links->events_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url ? get_slug($_POST['url'], '-', false) : null;

        $this->process_common_post_data();

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? false);

        /* Make sure that the user didn't exceed the limit */
        $user_total_events = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'event'")->fetch_object()->total;
        if($this->user->plan_settings->events_limit != -1 && $user_total_events >= $this->user->plan_settings->events_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Check for duplicate url if needed */
        $this->check_duplicate_url($_POST['url'], $domain_id);

        /* Start the creation process */
        $url = $_POST['url'] ?? $this->generate_random_url($domain_id);
        $type = 'event';
        $settings = json_encode([
            'password' => null,
            'sensitive_content' => false,
            'clicks_limit' => null,
            'expiration_url' => null,
            'event_name' => null,
            'event_note' => null,
            'event_url' => null,
            'event_location' => null,
            'event_start_datetime' => null,
            'event_end_datetime' => null,
            'event_first_alert_datetime' => null,
            'event_second_alert_datetime' => null,
            'event_timezone' => $this->user->timezone,
        ]);

        $this->check_url($_POST['url']);

        /* Insert to database */
        $link_id = db()->insert('links', [
            'user_id' => $this->user->user_id,
            'domain_id' => $domain_id,
            'type' => $type,
            'url' => $url,
            'settings' => $settings,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        $this->clear_link_cache($link_id, $type, $this->user->user_id);

        Response::json(l('global.success_message.create2'), 'success', ['url' => url('link/' . $link_id)]);
    }
    
    public function update($type) {
        if(!settings()->links->events_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['expiration_url'] = get_url($_POST['expiration_url']);
        $_POST['clicks_limit'] = empty($_POST['clicks_limit']) ? null : (int) $_POST['clicks_limit'];
        $_POST['sensitive_content'] = (int) isset($_POST['sensitive_content']);

        $this->process_common_post_data();
        $this->process_schedule_data();
        $this->process_pixels_data();
        $this->process_projects_and_splash_pages();

        $this->check_location_url($_POST['expiration_url'], true);

        /* Get domains */
        $domains = $this->get_available_domains();

        /* Check if custom domain is set */
        $domain_id = isset($domains[$_POST['domain_id']]) ? $_POST['domain_id'] : 0;

        /* Exclusivity check */
        $_POST['is_main_link'] = isset($_POST['is_main_link']) && $domain_id && $domains[$_POST['domain_id']]->type == 0;

        $this->check_url($_POST['url']);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }

        $link->settings = json_decode($link->settings ?? '');

        /* Check for a password set */
        $_POST['password'] = $this->process_password($link->settings->password);

        /* Check for duplicate url if needed */
        if($_POST['url'] && ($_POST['url'] != $link->url || $domain_id != $link->domain_id)) {
            $this->check_duplicate_url($_POST['url'], $domain_id, $link->link_id);
        }

        $url = $_POST['url'];
        if(empty($_POST['url'])) {
            $url = $this->generate_random_url($domain_id);
        }

        $settings = [
            'schedule' => $_POST['schedule'],
            'clicks_limit' => $_POST['clicks_limit'],
            'expiration_url' => $_POST['expiration_url'],
            'password' => $_POST['password'],
            'sensitive_content' => $_POST['sensitive_content'],
        ];

        /* Process event */
        $settings['event_name'] = $_POST['event_name'] = mb_substr(input_clean($_POST['event_name']), 0, $this->links_types['event']['fields']['name']['max_length']);
        $settings['event_location'] = $_POST['event_location'] = mb_substr(input_clean($_POST['event_location']), 0, $this->links_types['event']['fields']['location']['max_length']);
        $settings['event_url'] = $_POST['event_url'] = mb_substr(input_clean($_POST['event_url']), 0, $this->links_types['event']['fields']['url']['max_length']);
        $settings['event_note'] = $_POST['event_note'] = mb_substr(input_clean($_POST['event_note']), 0, $this->links_types['event']['fields']['note']['max_length']);
        $settings['event_timezone'] = $_POST['event_timezone'] = in_array($_POST['event_timezone'], \DateTimeZone::listIdentifiers()) ? input_clean($_POST['event_timezone']) : Date::$default_timezone;
        
        try {
            $settings['event_start_datetime'] = $_POST['event_start_datetime'] = (new \DateTime($_POST['event_start_datetime']))->format('Y-m-d\TH:i:s');
            $settings['event_end_datetime'] = $_POST['event_end_datetime'] = (new \DateTime($_POST['event_end_datetime']))->format('Y-m-d\TH:i:s');
            $settings['event_first_alert_datetime'] = $_POST['event_first_alert_datetime'] = (new \DateTime($_POST['event_first_alert_datetime']))->format('Y-m-d\TH:i:s');
            $settings['event_second_alert_datetime'] = $_POST['event_second_alert_datetime'] = (new \DateTime($_POST['event_second_alert_datetime']))->format('Y-m-d\TH:i:s');
        } catch (\Exception $exception) {
            /* :) */
        }

        $settings = json_encode($settings);

        db()->where('link_id', $_POST['link_id'])->update('links', [
            'project_id' => $_POST['project_id'],
            'splash_page_id' => $_POST['splash_page_id'],
            'domain_id' => $domain_id,
            'pixels_ids' => $_POST['pixels_ids'],
            'url' => $url,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'settings' => $settings,
            'last_datetime' => get_date(),
        ]);

        $this->process_is_main_link_domain($link, $domains);

        $url = $domain_id && $_POST['is_main_link'] ? '' : $url;

        /* Clear the cache */
        $this->clear_link_cache($link->link_id, 'event', $this->user->user_id);

        Response::json(l('global.success_message.update2'), 'success', ['url' => $url]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
