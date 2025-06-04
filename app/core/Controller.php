<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;


use SeeGap\Traits\Paramsable;

defined('SEEGAP') || die();

class Controller {
    use Paramsable;

    public $views = [];

    public function __construct(Array $params = []) {

        $this->add_params($params);

    }

    public function add_view_content($name, $data) {

        $this->views[$name] = $data;

    }

    public function run() {

        /* Do we need to show something? */
        if(!\SeeGap\Router::$controller_settings['has_view']) {
            return;
        }

        /* Dynamic OG images plugin */
        if(\SeeGap\Plugin::is_active('dynamic-og-images') && settings()->dynamic_og_images->is_enabled) {
            \SeeGap\Plugin\DynamicOgImages::process();
        }

        if(\SeeGap\Router::$path == '') {
            $meta_content = [];
            if(!settings()->main->se_indexing) $meta_content[] = 'noindex';
            if(!settings()->main->ai_scraping_is_allowed) $meta_content[] = 'noarchive';
            \SeeGap\Meta::set_robots(implode(', ', $meta_content));

            /* Get the top menu custom pages */
            $top_pages = [];

            /* Get the footer pages */
            $bottom_pages = [];

            /* Custom wrapper condition for plan pages */
            if(in_array(\SeeGap\Router::$controller_key, ['plan', 'contact', 'affiliate', 'api-documentation'])) {
                \SeeGap\Router::$controller_settings['wrapper'] = is_logged_in() ? 'app_wrapper' : 'wrapper';
            }

            /* Custom wrapper conditions */
            if(in_array(\SeeGap\Router::$controller_key, ['tools'])) {
                \SeeGap\Router::$controller_settings['wrapper'] = is_logged_in() ? 'app_wrapper' : 'wrapper';
            }

            /* Normal wrapper ( not logged in ) */
            if(\SeeGap\Router::$controller_settings['wrapper'] == 'wrapper') {

                /* Establish the menu view */
                $menu = new \SeeGap\View('partials/menu', (array) $this);
                $this->add_view_content('menu', $menu->run(['pages' => $top_pages]));

                /* Establish the footer view */
                $footer = new \SeeGap\View('partials/footer', (array) $this);
                $this->add_view_content('footer', $footer->run(['pages' => $bottom_pages]));
            }

            /* App wrapper logged in users */
            if(\SeeGap\Router::$controller_settings['wrapper'] == 'app_wrapper') {

                $sidebar = new \SeeGap\View('partials/app_sidebar', (array) $this);
                $this->add_view_content('app_sidebar', $sidebar->run(['pages' => $top_pages]));

                $menu = new \SeeGap\View('partials/app_menu', (array) $this);
                $this->add_view_content('app_menu', $menu->run(['pages' => $top_pages]));

                /* Establish the footer view */
                $footer = new \SeeGap\View('partials/footer', (array) $this);
                $this->add_view_content('footer', $footer->run(['pages' => $bottom_pages]));

            }

            $wrapper = new \SeeGap\View(\SeeGap\Router::$controller_settings['wrapper'], (array) $this);
        }


        if(\SeeGap\Router::$path == 'admin') {
            /* Establish the side menu view */
            $sidebar = new \SeeGap\View('admin/partials/admin_sidebar', (array) $this);
            $this->add_view_content('admin_sidebar', $sidebar->run());

            /* Establish the top menu view */
            $menu = new \SeeGap\View('admin/partials/admin_menu', (array) $this);
            $this->add_view_content('admin_menu', $menu->run());

            /* Establish the footer view */
            $footer = new \SeeGap\View('admin/partials/footer', (array) $this);
            $this->add_view_content('footer', $footer->run());

            $wrapper = new \SeeGap\View('admin/wrapper', (array) $this);
        }

        if(isset($wrapper)) {
            echo $wrapper->run();
        }
    }


}
