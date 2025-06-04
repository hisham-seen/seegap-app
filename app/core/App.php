<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap;

use SeeGap\Models\Plan;
use SeeGap\Models\User;


defined('SEEGAP') || die();

class App {

    protected $database;

    public function __construct() {

        /* Connect to the database */
        //Database::initialize();

        /* Initialize caching system */
        Cache::initialize();

        /* Initiate the plugin system */
        Plugin::initialize();

        /* Initiate the Language system */
        Language::initialize();

        /* Parse the URL parameters */
        \SeeGap\Router::parse_url();

        /* Parse the potential language url */
        \SeeGap\Router::parse_language();

        /* Handle the controller */
        \SeeGap\Router::parse_controller();

        /* Create a new instance of the controller */
        $controller = \SeeGap\Router::get_controller(\SeeGap\Router::$controller, \SeeGap\Router::$path);

        /* Process the method and get it */
        $method = \SeeGap\Router::parse_method($controller);

        /* Get the remaining params */
        $params = \SeeGap\Router::get_params();

        if(!\SeeGap\Router::$controller_settings['allow_indexing']) {
            header('X-Robots-Tag: noindex');
        }

        /* Iframe embedding */
        settings()->main->iframe_embedding = settings()->main->iframe_embedding ?? 'all';
        $iframe_embedding = match(settings()->main->iframe_embedding) {
            'all' => '*',
            'none' => "'none'",
            default => implode(' ', explode(',', settings()->main->iframe_embedding))
        };
        header("Content-Security-Policy: frame-ancestors $iframe_embedding;");

        /* HSTS */
        if(string_starts_with('https://', SITE_URL)) {
            header("Strict-Transport-Security: max-age=31536000; preload");
        }

        /* Check for Preflight requests for the tracking of submissions from microsite pages */
        if(in_array(\SeeGap\Router::$controller, ['Link'])) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');
            header('Access-Control-Max-Age: 7200');

            /* Check if preflight request */
            if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') die();
        }

        /* Initiate the Language system with the default language */
        Language::set_default_by_name(settings()->main->default_language);

        /* Set the default theme style */
        ThemeStyle::set_default(settings()->main->default_theme_style);

        /* Set the date timezone */
        date_default_timezone_set(Date::$default_timezone);
        Date::$timezone = date_default_timezone_get();

        /* Setting the datetime for backend usages ( insertions in database..etc ) */
        Date::$date = Date::get();

        /* Check if the team is set and do not allow access for certain routes */
        if(isset($_SESSION['team_id']) && \SeeGap\Plugin::is_active('teams') && !is_null(\SeeGap\Router::$controller_settings['allow_team_access'])) {
            if(!\SeeGap\Router::$controller_settings['allow_team_access']) {
                Alerts::add_info(l('global.info_message.team_limit'));
                redirect();
            }
        }

        /* Affiliate check */
        Affiliate::initiate();

        /* Full URL for ease of use */
        settings()->main->logo_light_full_url = \SeeGap\Uploads::get_full_url('logo_light') . settings()->main->logo_light;
        settings()->main->logo_dark_full_url = \SeeGap\Uploads::get_full_url('logo_dark') . settings()->main->logo_dark;
        settings()->main->favicon_full_url = \SeeGap\Uploads::get_full_url('favicon') . settings()->main->favicon;

        /* Check for a potential logged in account and do some extra checks */
        if(is_logged_in()) {

            $user = \SeeGap\Authentication::$user;

            if(!$user) {
                \SeeGap\Authentication::logout();
            }

            /* Teams initialization */
            Teams::initialize();

            /* Delegate access if needed */
            if(Teams::delegate_access()) {
                $user = Teams::$team_user;
            }

            /* Determine if the current plan is expired or disabled */
            $user->plan_is_expired = false;

            /* Get current plan proper details */
            $user->plan = (new Plan())->get_plan_by_id($user->plan_id);

            if(!$user->plan || ($user->plan && ((new \DateTime()) > (new \DateTime($user->plan_expiration_date)) && $user->plan_id != 'free') || !$user->plan->status)) {
                $user->plan_is_expired = true;

                /* Switch the user to the default plan */
                db()->where('user_id', $user->user_id)->update('users', [
                    'plan_id' => 'free',
                    'plan_settings' => json_encode(settings()->plan_free->settings),
                    'payment_subscription_id' => ''
                ]);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' .  \SeeGap\Authentication::$user_id);

                /* Make sure to redirect the person to the payment page and only let the person access the following pages */
                if(!in_array(\SeeGap\Router::$controller_key, ['index', 'blog', 'affiliate', 'contact', 'page', 'pages', 'plan', 'pay', 'pay-billing', 'pay-thank-you', 'account', 'account-plan', 'account-payments', 'invoice', 'account-logs', 'account-preferences',  'account-delete', 'referrals', 'account-api', 'account-redeem-code', 'logout', 'register', 'teams-system', 'teams-member', 'teams-members']) && \SeeGap\Router::$path != 'admin') {
                    redirect('plan/new');
                }
            }

            /* Update last activity */
            /* Do not update if user is impersonated by an admin */
            if(!$user->last_activity || (new \DateTime($user->last_activity))->modify('+15 minutes') < (new \DateTime()) && !isset($_SESSION['admin_user_id'])) {
                (new User())->update_last_activity(\SeeGap\Authentication::$user_id);
            }

            if(!isset($_COOKIE['set_language'])) {
                /* Update the language of the site for next page use if the current language (default) is different than the one the user has */
                if(Language::$name != $user->language) {
                    /* Make sure the language of the user still exists & is active */
                    if(array_key_exists($user->language, Language::$active_languages)) {
                        //Language::set_by_name($user->language);
                    } else {
                        db()->where('user_id', \SeeGap\Authentication::$user_id)->update('users', ['language' => Language::$default_name]);

                        /* Clear the cache */
                        cache()->deleteItemsByTag('user_id=' . \SeeGap\Authentication::$user_id);
                    }
                }
            }

            /* Update the language of the user if needed */
            if(isset($_COOKIE['set_language']) && array_key_exists($_COOKIE['set_language'], Language::$active_languages) && Language::$name != $user->language) {
                db()->where('user_id', \SeeGap\Authentication::$user_id)->update('users', ['language' => $_COOKIE['set_language']]);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . \SeeGap\Authentication::$user_id);

                /* Remove cookie */
                setcookie('set_language', '', time()-30, COOKIE_PATH);

                /* Set the language */
                Language::set_by_name($_COOKIE['set_language']);
            }

            /* Update the currency of the user if needed */
            if(isset($_COOKIE['set_currency']) && array_key_exists($_COOKIE['set_currency'], (array) settings()->payment->currencies) && $_COOKIE['set_currency'] != $user->currency) {
                db()->where('user_id', \SeeGap\Authentication::$user_id)->update('users', ['currency' => $_COOKIE['set_currency']]);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . \SeeGap\Authentication::$user_id);

                /* Remove cookie */
                setcookie('set_currency', '', time()-30, COOKIE_PATH);

                /* Set the currency */
                \SeeGap\Currency::$currency = $_COOKIE['set_currency'];
            }

            /* Set the timezone to be used for displaying */
            Date::$timezone = $user->timezone;

            /* Store all the details of the user in the Authentication static class as well */
            \SeeGap\Authentication::$user = $user;

            /* White label */
            if((settings()->main->white_labeling_is_enabled ?? false) && ($user->plan_settings->white_labeling_is_enabled ?? false) && \SeeGap\Router::$controller_key != 'invoice' && \SeeGap\Router::$path != 'admin') {
                if($user->preferences->white_label_title) settings()->main->title = $user->preferences->white_label_title;

                if($user->preferences->white_label_logo_light) {
                    settings()->main->logo_light = $user->preferences->white_label_logo_light;
                    settings()->main->logo_light_full_url = \SeeGap\Uploads::get_full_url('users') . settings()->main->logo_light;
                }

                if($user->preferences->white_label_logo_dark) {
                    settings()->main->logo_dark = $user->preferences->white_label_logo_dark;
                    settings()->main->logo_dark_full_url = \SeeGap\Uploads::get_full_url('users') . settings()->main->logo_dark;
                }

                if($user->preferences->white_label_favicon) {
                    settings()->main->favicon = $user->preferences->white_label_favicon;
                    settings()->main->favicon_full_url = \SeeGap\Uploads::get_full_url('users') . settings()->main->favicon;
                }
            }
        }

        /* Maintenance mode */
        if((settings()->main->maintenance_is_enabled ?? false) && (!is_logged_in() || $user->type != 1) && !in_array(\SeeGap\Router::$controller_key, ['maintenance', 'login', 'lost-password', 'reset-password'])) {
            header('HTTP/1.1 503 Service Unavailable');
            header('Retry-After: 3600');
            header('Location: ' . url('maintenance'));
            exit();
        }

        /* Initiate the Title system */
        Title::initialize(settings()->main->title);
        Meta::initialize();

        /* Set a CSRF Token */
        \SeeGap\Csrf::set('token');
        \SeeGap\Csrf::set('global_token');

        /* If the language code is the default one, redirect to index */
        // Temporarily disabled to prevent redirect loops
        // if(\SeeGap\Router::$language_code == Language::$default_code) {
        //     redirect(\SeeGap\Router::$original_request . (\SeeGap\Router::$original_request_query ? '?' . \SeeGap\Router::$original_request_query : null));
        // }

        /* Redirect based on browser language if needed */
        $browser_language_code = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        if(settings()->main->auto_language_detection_is_enabled && \SeeGap\Router::$controller_settings['no_browser_language_detection'] == false && !\SeeGap\Router::$language_code && !is_logged_in() && $browser_language_code && Language::$default_code != $browser_language_code && array_search($browser_language_code, Language::$active_languages)) {
            if(!isset($_SERVER['HTTP_REFERER']) || (isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'])['host'] != parse_url(SITE_URL)['host'])) {
                header('Location: ' . SITE_URL . $browser_language_code . '/' . \SeeGap\Router::$original_request . (\SeeGap\Router::$original_request_query ? '?' . \SeeGap\Router::$original_request_query : null));
            }
        }

        /* Force HTTPS is needed */
        if(settings()->main->force_https_is_enabled && ($_SERVER['HTTPS'] ?? '') != 'on' && php_sapi_name() != 'cli' && string_starts_with('https://', SITE_URL)) {
            header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301); die();
        }

        /* Add main vars inside of the controller */
        $controller->add_params([
            /* Extra params available from the URL */
            'params' => $params,

            /* Potential logged in user */
            'user' => \SeeGap\Authentication::$user
        ]);

        /* Check for authentication checks */
        if(!is_null(\SeeGap\Router::$controller_settings['authentication'])) {
            \SeeGap\Authentication::guard(\SeeGap\Router::$controller_settings['authentication']);
        }

        /* Call the controller method */
        call_user_func_array([ $controller, $method ], []);

        /* Render and output everything */
        $controller->run();

        /* Close database */
        Database::close();
    }

}
