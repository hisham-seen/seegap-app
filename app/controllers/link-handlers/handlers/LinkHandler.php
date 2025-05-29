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

defined('ALTUMCODE') || die();

/**
 * Link Handler
 * 
 * Handles the creation and updating of standard URL shortener links.
 */
class LinkHandler extends BaseLinkHandler {
    
    public function getSupportedTypes() {
        return ['link'];
    }
    
    public function create($type) {
        if(!settings()->links->shortener_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $_POST['location_url'] = get_url($_POST['location_url']);
        $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url ? get_slug($_POST['url'], '-', false) : null;
        $_POST['sensitive_content'] = (int) isset($_POST['sensitive_content']);
        $type = 'link';

        $this->process_common_post_data();

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? false);

        if(empty($_POST['location_url'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        $this->check_url($_POST['url']);
        $this->check_location_url($_POST['location_url']);

        /* Make sure that the user didn't exceed the limit */
        $user_total_links = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'link'")->fetch_object()->total;
        if($this->user->plan_settings->links_limit != -1 && $user_total_links >= $this->user->plan_settings->links_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Check for duplicate url if needed */
        $this->check_duplicate_url($_POST['url'], $domain_id);

        $url = $_POST['url'] ?: $this->generate_random_url($domain_id);

        /* App linking processing */
        $app_linking = [
            'ios_location_url' => null,
            'android_location_url' => null,
            'app' => null,
        ];

        $supported_apps = require APP_PATH . 'includes/app_linking.php';
        foreach($supported_apps as $app_key => $app) {
            foreach($app['formats'] as $format => $targets) {
                if(preg_match('/' . $targets['regex'] . '/', $_POST['location_url'], $match)) {
                    if(
                        str_contains($_POST['location_url'], str_replace('%s', '', $format)) ||
                        str_contains($_POST['location_url'], preg_replace('/%s.*/', '', $format))
                    ) {
                        if(count($match) > 1) {
                            array_shift($match);
                            $app_linking['ios_location_url'] = vsprintf($targets['iOS'], $match);
                            $app_linking['android_location_url'] = vsprintf($targets['Android'], $match);
                            $app_linking['app'] = $app_key;
                        }
                        break 2;
                    }
                }
            }
        }

        $settings = json_encode([
            'http_status_code' => 301,
            'clicks_limit' => null,
            'expiration_url' => null,
            'password' => null,
            'sensitive_content' => false,
            'targeting_type' => null,
            'app_linking_is_enabled' => $this->user->plan_settings->app_linking_is_enabled,
            'app_linking' => $app_linking,
            'cloaking_is_enabled' => false,
            'cloaking_title' => null,
            'cloaking_meta_description' => null,
            'cloaking_custom_js' => null,
            'cloaking_favicon' => null,
            'cloaking_opengraph' => null,
            'forward_query_parameters_is_enabled' => false,
            'utm' => [
                'source' => null,
                'medium' => null,
                'campaign' => null,
            ]
        ]);

        /* Insert to database */
        $link_id = db()->insert('links', [
            'user_id' => $this->user->user_id,
            'domain_id' => $domain_id,
            'type' => $type,
            'url' => $url,
            'location_url' => $_POST['location_url'],
            'settings' => $settings,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        $this->clear_link_cache($link_id, $type, $this->user->user_id);

        Response::json(l('global.success_message.create2'), 'success', ['url' => url('link/' . $link_id)]);
    }
    
    public function update($type) {
        if(!settings()->links->shortener_is_enabled) {
            Response::json(l('global.error_message.basic'), 'error');
        }

        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = get_url($_POST['location_url']);
        $_POST['expiration_url'] = get_url($_POST['expiration_url']);
        $_POST['clicks_limit'] = empty($_POST['clicks_limit']) ? null : (int) $_POST['clicks_limit'];
        $_POST['sensitive_content'] = (int) isset($_POST['sensitive_content']);
        $_POST['app_linking_is_enabled'] = (int) isset($_POST['app_linking_is_enabled']);
        $_POST['cloaking_is_enabled'] = (int) isset($_POST['cloaking_is_enabled']);
        $_POST['cloaking_title'] = input_clean($_POST['cloaking_title'], 70);
        $_POST['cloaking_meta_description'] = input_clean($_POST['cloaking_meta_description'], 160);
        $_POST['cloaking_custom_js'] = mb_substr(trim($_POST['cloaking_custom_js']), 0, 10000);

        /* Query parameters forwarding */
        $_POST['forward_query_parameters_is_enabled'] = (int) isset($_POST['forward_query_parameters_is_enabled']);

        /* UTM */
        $_POST['utm_medium'] = input_clean($_POST['utm_medium'], 128);
        $_POST['utm_source'] = input_clean($_POST['utm_source'], 128);
        $_POST['utm_campaign'] = input_clean($_POST['utm_campaign'], 128);

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

        /* Check for any errors */
        $this->validate_required_fields(['location_url']);

        $this->check_url($_POST['url']);
        $this->check_location_url($_POST['location_url']);

        if(!$link = db()->where('link_id', $_POST['link_id'])->where('user_id', $this->user->user_id)->getOne('links')) {
            die();
        }
        $link->settings = json_decode($link->settings ?? '');

        /* Cloaking */
        $link->settings->cloaking_favicon = \Altum\Uploads::process_upload($link->settings->cloaking_favicon, 'favicons', 'cloaking_favicon', 'cloaking_favicon_remove', settings()->links->favicon_size_limit, 'json_error');
        $link->settings->cloaking_opengraph = \Altum\Uploads::process_upload($link->settings->cloaking_opengraph, 'microsite_seo_image', 'cloaking_opengraph', 'cloaking_opengraph_remove', settings()->links->seo_image_size_limit, 'json_error');

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

        /* App linking check */
        $app_linking = [
            'ios_location_url' => null,
            'android_location_url' => null,
            'app' => null,
        ];

        if($_POST['app_linking_is_enabled']) {
            $supported_apps = require APP_PATH . 'includes/app_linking.php';
            foreach($supported_apps as $app_key => $app) {
                foreach($app['formats'] as $format => $targets) {
                    if(preg_match('/' . $targets['regex'] . '/', $_POST['location_url'], $match)) {
                        if(
                            str_contains($_POST['location_url'], str_replace('%s', '', $format)) ||
                            str_contains($_POST['location_url'], preg_replace('/%s.*/', '', $format))
                        ) {
                            if(count($match) > 1) {
                                array_shift($match);
                                $app_linking['ios_location_url'] = vsprintf($targets['iOS'], $match);
                                $app_linking['android_location_url'] = vsprintf($targets['Android'], $match);
                                $app_linking['app'] = $app_key;
                            }
                            break 2;
                        }
                    }
                }
            }
        }

        /* Prepare the settings */
        $targeting_types = ['continent_code', 'country_code', 'city_name', 'device_type', 'browser_language', 'rotation', 'os_name', 'browser_name'];
        $_POST['targeting_type'] = in_array($_POST['targeting_type'], array_merge(['false'], $targeting_types)) ? query_clean($_POST['targeting_type']) : 'false';
        $_POST['http_status_code'] = in_array($_POST['http_status_code'], [301, 302, 307, 308]) ? (int) $_POST['http_status_code'] : 301;

        $settings = [
            'clicks_limit' => $_POST['clicks_limit'],
            'expiration_url' => $_POST['expiration_url'],
            'schedule' => $_POST['schedule'],
            'password' => $_POST['password'],
            'sensitive_content' => $_POST['sensitive_content'],
            'targeting_type' => $_POST['targeting_type'],
            'http_status_code' => $_POST['http_status_code'],

            /* Cloaking */
            'cloaking_is_enabled' => $_POST['cloaking_is_enabled'],
            'cloaking_title' => $_POST['cloaking_title'],
            'cloaking_meta_description' => $_POST['cloaking_meta_description'],
            'cloaking_custom_js' => $_POST['cloaking_custom_js'],
            'cloaking_favicon' => $link->settings->cloaking_favicon,
            'cloaking_opengraph' => $link->settings->cloaking_opengraph,

            /* App linking */
            'app_linking_is_enabled' => $_POST['app_linking_is_enabled'],
            'app_linking' => $app_linking,

            /* Forward query parameters */
            'forward_query_parameters_is_enabled' => $_POST['forward_query_parameters_is_enabled'],

            /* UTM */
            'utm' => [
                'source' => $_POST['utm_source'],
                'medium' => $_POST['utm_medium'],
                'campaign' => $_POST['utm_campaign'],
            ]
        ];

        /* Process the targeting */
        foreach($targeting_types as $targeting_type) {
            ${'targeting_' . $targeting_type} = [];

            if(isset($_POST['targeting_' . $targeting_type . '_key'])) {
                foreach($_POST['targeting_' . $targeting_type . '_key'] as $key => $value) {
                    if(empty(trim($_POST['targeting_' . $targeting_type . '_value'][$key]))) continue;

                    ${'targeting_' . $targeting_type}[] = [
                        'key' => trim(query_clean($value)),
                        'value' => get_url($_POST['targeting_' . $targeting_type . '_value'][$key]),
                    ];
                }

                $settings['targeting_' . $targeting_type] = ${'targeting_' . $targeting_type};
            }
        }

        $settings = json_encode($settings);

        db()->where('link_id', $_POST['link_id'])->update('links', [
            'project_id' => $_POST['project_id'],
            'splash_page_id' => $_POST['splash_page_id'],
            'domain_id' => $domain_id,
            'pixels_ids' => $_POST['pixels_ids'],
            'url' => $url,
            'location_url' => $_POST['location_url'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'settings' => $settings,
            'last_datetime' => get_date(),
        ]);

        $this->process_is_main_link_domain($link, $domains);

        $url = $domain_id && $_POST['is_main_link'] ? '' : $url;

        /* Clear the cache */
        $this->clear_link_cache($link->link_id, 'link', $this->user->user_id);

        Response::json(l('global.success_message.update2'), 'success', ['url' => $url, 'app_linking' => $app_linking]);
    }
    
    public function validate($type, $data = []) {
        return true;
    }
}
