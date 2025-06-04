<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers\LinkHandlers;

use SeeGap\Response;
use SeeGap\Models\Domain;

defined('SEEGAP') || die();

/**
 * Base Link Handler
 * 
 * Provides shared functionality for all link handlers.
 */
abstract class BaseLinkHandler implements Interfaces\LinkHandlerInterface {
    
    public $user;
    public $links_types;
    
    /**
     * Function to bundle together all the checks of a custom url
     */
    protected function check_url($url) {
        if($url) {
            /* Make sure the url alias is not blocked by a route of the product */
            if(array_key_exists($url, \SeeGap\Router::$routes['']) || in_array($url, \SeeGap\Language::$active_languages) || file_exists(ROOT_PATH . $url)) {
                Response::json(l('link.error_message.blacklisted_url'), 'error');
            }

            /* Make sure the custom url is not blacklisted */
            if(in_array(mb_strtolower($url), settings()->links->blacklisted_keywords)) {
                Response::json(l('link.error_message.blacklisted_keyword'), 'error');
            }

            /* Make sure the custom url meets the requirements */
            if(mb_strlen($url) < ($this->user->plan_settings->url_minimum_characters ?? 1)) {
                Response::json(sprintf(l('link.error_message.url_minimum_characters'), $this->user->plan_settings->url_minimum_characters ?? 1), 'error');
            }

            if(mb_strlen($url) > ($this->user->plan_settings->url_maximum_characters ?? 64)) {
                Response::json(sprintf(l('link.error_message.url_maximum_characters'), $this->user->plan_settings->url_maximum_characters ?? 64), 'error');
            }
        }
    }

    /**
     * Function to bundle together all the checks of an url
     */
    protected function check_location_url($url, $can_be_empty = false) {
        if(empty(trim($url)) && $can_be_empty) {
            return;
        }

        if(empty(trim($url))) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        $url_details = parse_url($url);

        if(!isset($url_details['scheme'])) {
            Response::json(l('link.error_message.invalid_location_url'), 'error');
        }

        if(!$this->user->plan_settings->deep_links && !in_array($url_details['scheme'], ['http', 'https'])) {
            Response::json(l('link.error_message.invalid_location_url'), 'error');
        }

        /* Make sure the domain is not blacklisted */
        $domain = get_domain_from_url($url);

        if($domain && in_array($domain, settings()->links->blacklisted_domains)) {
            Response::json(l('link.error_message.blacklisted_domain'), 'error');
        }

        /* Check the url with google safe browsing to make sure it is a safe website */
        if(settings()->links->google_safe_browsing_is_enabled) {
            if(google_safe_browsing_check($url, settings()->links->google_safe_browsing_api_key)) {
                Response::json(l('link.error_message.blacklisted_location_url'), 'error');
            }
        }
    }

    /**
     * Check if custom domain is set and return the proper value
     */
    protected function get_domain_id($posted_domain_id) {
        $domain_id = 0;

        if(isset($posted_domain_id)) {
            $domain_id = (int) $posted_domain_id;

            /* Make sure the user has access to global additional domains */
            if($this->user->plan_settings->additional_domains) {
                $domain_id = database()->query("SELECT `domain_id` FROM `domains` WHERE `domain_id` = {$domain_id} AND (`user_id` = {$this->user->user_id} OR `type` = 1)")->fetch_object()->domain_id ?? 0;
            } else {
                $domain_id = database()->query("SELECT `domain_id` FROM `domains` WHERE `domain_id` = {$domain_id} AND `user_id` = {$this->user->user_id}")->fetch_object()->domain_id ?? 0;
            }
        }

        return $domain_id;
    }

    /**
     * Process main link domain settings
     */
    protected function process_is_main_link_domain($link, $domains) {
        /* Update custom domain if needed */
        if($_POST['is_main_link']) {

            /* If the main status page of a particular domain is changing, update the old domain as well to "free" it */
            if($_POST['domain_id'] != $link->domain_id) {
                /* Database query */
                db()->where('domain_id', $link->domain_id)->update('domains', [
                    'link_id' => null,
                    'last_datetime' => get_date(),
                ]);
            }

            /* Database query */
            db()->where('domain_id', $_POST['domain_id'])->update('domains', [
                'link_id' => $link->link_id,
                'last_datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItems([
                'domains?user_id=' . $this->user->user_id,
                'domain?domain_id=' . $link->domain_id,
                'domain?domain_id=' . $_POST['domain_id'],
                'domain?host=' . md5($domains[$link->domain_id]->host ?? ''),
                'domain?host=' . md5($domains[$_POST['domain_id']]->host ?? ''),
            ]);
            cache()->deleteItemsByTag('domains?user_id=' . $this->user->user_id);
        }

        /* Update old main custom domain if needed */
        if(!$_POST['is_main_link'] && $link->domain_id && $domains[$link->domain_id]->link_id == $link->link_id) {
            /* Database query */
            db()->where('domain_id', $link->domain_id)->update('domains', [
                'link_id' => null,
                'last_datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItems([
                'domains?user_id=' . $this->user->user_id,
                'domain?domain_id=' . $link->domain_id,
                'domain?domain_id=' . $_POST['domain_id'],
                'domain?host=' . md5($domains[$link->domain_id]->host ?? ''),
                'domain?host=' . md5($domains[$_POST['domain_id']]->host ?? ''),
            ]);
            cache()->deleteItemsByTag('domains?user_id=' . $this->user->user_id);
        }
    }

    /**
     * Validate required fields
     */
    protected function validate_required_fields($required_fields) {
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
                break 1;
            }
        }
    }

    /**
     * Generate random URL if not specified
     */
    protected function generate_random_url($domain_id, $length = null) {
        $length = $length ?? (settings()->links->random_url_length ?? 7);
        $url = mb_strtolower(string_generate($length));

        while(db()->where('url', $url)->where('domain_id', $domain_id)->getValue('links', 'link_id')) {
            $url = mb_strtolower(string_generate($length));
        }

        return $url;
    }

    /**
     * Clear link-related cache
     */
    protected function clear_link_cache($link_id, $type, $user_id) {
        cache()->deleteItem($type . '_links_total?user_id=' . $user_id);
        cache()->deleteItem('links_total?user_id=' . $user_id);
        cache()->deleteItem('links?user_id=' . $user_id);
        
        if($link_id) {
            cache()->deleteItem('microsite_blocks?link_id=' . $link_id);
            cache()->deleteItem('link?link_id=' . $link_id);
            cache()->deleteItemsByTag('link_id=' . $link_id);
        }
    }

    /**
     * Get available domains for user
     */
    protected function get_available_domains() {
        return (new Domain())->get_available_domains_by_user($this->user);
    }

    /**
     * Process common POST data for links
     */
    protected function process_common_post_data() {
        $_POST['project_id'] = empty($_POST['project_id']) ? null : (int) $_POST['project_id'];
        $_POST['url'] = !empty($_POST['url']) ? get_slug($_POST['url'], '-', false) : false;
        
        /* Domain validation */
        if(empty($_POST['domain_id']) && !settings()->links->main_domain_is_enabled && !\SeeGap\Authentication::is_admin()) {
            Response::json(l('create_link_modal.error_message.main_domain_is_disabled'), 'error');
        }
    }

    /**
     * Process scheduling data
     */
    protected function process_schedule_data() {
        $_POST['schedule'] = (int) isset($_POST['schedule']);
        if($_POST['schedule'] && !empty($_POST['start_date']) && !empty($_POST['end_date']) && \SeeGap\Date::validate($_POST['start_date'], 'Y-m-d H:i:s') && \SeeGap\Date::validate($_POST['end_date'], 'Y-m-d H:i:s')) {
            $_POST['start_date'] = (new \DateTime($_POST['start_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\SeeGap\Date::$default_timezone))->format('Y-m-d H:i:s');
            $_POST['end_date'] = (new \DateTime($_POST['end_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\SeeGap\Date::$default_timezone))->format('Y-m-d H:i:s');
        } else {
            $_POST['start_date'] = $_POST['end_date'] = null;
        }
    }

    /**
     * Process pixels data
     */
    protected function process_pixels_data() {
        $pixels = (new \SeeGap\Models\Pixel())->get_pixels($this->user->user_id);
        $_POST['pixels_ids'] = isset($_POST['pixels_ids']) ? array_map(
            function($pixel_id) {
                return (int) $pixel_id;
            },
            array_filter($_POST['pixels_ids'], function($pixel_id) use($pixels) {
                return array_key_exists($pixel_id, $pixels);
            })
        ) : [];
        $_POST['pixels_ids'] = json_encode($_POST['pixels_ids']);
    }

    /**
     * Process projects and splash pages data
     */
    protected function process_projects_and_splash_pages() {
        /* Existing projects */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;

        /* Existing splash pages */
        $splash_pages = (new \SeeGap\Models\SplashPages())->get_splash_pages_by_user_id($this->user->user_id);
        $_POST['splash_page_id'] = !empty($_POST['splash_page_id']) && array_key_exists($_POST['splash_page_id'], $splash_pages) ? (int) $_POST['splash_page_id'] : null;
    }

    /**
     * Check for duplicate URL
     */
    protected function check_duplicate_url($url, $domain_id, $exclude_link_id = null) {
        if($url) {
            $query = db()->where('url', $url)->where('domain_id', $domain_id);
            
            if($exclude_link_id) {
                $query->where('link_id', $exclude_link_id, '!=');
            }
            
            if($query->getValue('links', 'link_id')) {
                Response::json(l('link.error_message.url_exists'), 'error');
            }
        }
    }

    /**
     * Process password data
     */
    protected function process_password($existing_password = null) {
        return !empty($_POST['qweasdzxc']) ?
            ($_POST['qweasdzxc'] != $existing_password ? password_hash($_POST['qweasdzxc'], PASSWORD_DEFAULT) : $existing_password)
            : null;
    }
}
