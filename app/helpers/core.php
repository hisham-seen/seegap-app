<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('SEEGAP') || die();

function settings() {
    if(!\SeeGap\Settings::$settings) {
        \SeeGap\Settings::initialize();
    }

    return \SeeGap\Settings::$settings;
}

function get_settings_custom_head_js($key = 'head_js') {
    $head_js = settings()->custom->{$key};

    /* Dynamic variables processing */
    $replacers = [
        '{{WEBSITE_TITLE}}' => settings()->main->title,
        '{{USER:NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->name : '',
        '{{USER:EMAIL}}' => is_logged_in() ? \SeeGap\Authentication::$user->email : '',
        '{{USER:CONTINENT_NAME}}' => is_logged_in() ? get_continent_from_continent_code(\SeeGap\Authentication::$user->continent_code) : '',
        '{{USER:COUNTRY_NAME}}' => is_logged_in() ? get_country_from_country_code(\SeeGap\Authentication::$user->country) : '',
        '{{USER:CITY_NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->city_name : '',
        '{{USER:DEVICE_TYPE}}' => is_logged_in() ? l('global.device.' . \SeeGap\Authentication::$user->device_type) : '',
        '{{USER:OS_NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->os_name : '',
        '{{USER:BROWSER_NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->browser_name : '',
        '{{USER:BROWSER_LANGUAGE}}' => is_logged_in() ? get_language_from_locale(\SeeGap\Authentication::$user->browser_language) : '',
        '{{USER:USER_ID}}' => json_encode(is_logged_in() ? \SeeGap\Authentication::$user->user_id : ''),
        '{{USER:PLAN_ID}}' => json_encode(is_logged_in() ? \SeeGap\Authentication::$user->plan_id : ''),
    ];

    $head_js = str_replace(
        array_keys($replacers),
        array_values($replacers),
        $head_js
    );

    return $head_js;
}

function db() {
    if(!\SeeGap\Database::$db) {
        \SeeGap\Database::initialize();
    }

    return \SeeGap\Database::$db;
}

function database() {
    if(!\SeeGap\Database::$database) {
        \SeeGap\Database::initialize();
    }

    return \SeeGap\Database::$database;
}

function language($language = null) {
    return \SeeGap\Language::get($language);
}

function l($key, $language = null, $null_coalesce = false) {
    return \SeeGap\Language::get($language)[$key] ?? \SeeGap\Language::get(\SeeGap\Language::$main_name)[$key] ?? ($null_coalesce ? null : $key);
}

function currency() {
    if(!\SeeGap\Currency::$currency) {
        \SeeGap\Currency::initialize();
    }

    return \SeeGap\Currency::$currency;
}

function cache($adapter = 'adapter') {
    return \SeeGap\Cache::${$adapter};
}

function get_date($format = 'Y-m-d H:i:s') {
    return date($format);
}

function is_logged_in() {
    return \SeeGap\Authentication::check();
}

function user() {
    return \SeeGap\Authentication::$user;
}
