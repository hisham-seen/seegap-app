<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Alerts;

defined('SEEGAP') || die();

class SplashPageCreate extends Controller {

    public function index() {

        if(!settings()->links->splash_page_is_enabled) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.splash_pages')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('splash-pages');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `splash_pages` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->splash_pages_limit != -1 && $total_rows >= $this->user->plan_settings->splash_pages_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('splash-pages');
        }

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['title'] = input_clean($_POST['title'], 256);
            $_POST['description'] = input_clean($_POST['description'], 2048);
            $_POST['secondary_button_name'] = input_clean($_POST['secondary_button_name'], 256);
            $_POST['secondary_button_url'] = input_clean($_POST['secondary_button_url'], 1024);
            $_POST['custom_css'] = mb_substr(trim($_POST['custom_css']), 0, 10000);
            $_POST['custom_js'] = mb_substr(trim($_POST['custom_js']), 0, 10000);
            $_POST['ads_header'] = mb_substr(trim($_POST['ads_header']), 0, 10000);
            $_POST['ads_footer'] = mb_substr(trim($_POST['ads_footer']), 0, 10000);
            $_POST['link_unlock_seconds'] = (int) $_POST['link_unlock_seconds'];
            $_POST['auto_redirect'] = (int) isset($_POST['auto_redirect']);

            /* Background and typography settings */
            $_POST['background_type'] = input_clean($_POST['background_type'] ?? 'preset', 32);
            $_POST['background'] = input_clean($_POST['background'] ?? 'ocean', 128);
            $_POST['background_color_one'] = input_clean($_POST['background_color_one'] ?? '#667eea', 16);
            $_POST['background_color_two'] = input_clean($_POST['background_color_two'] ?? '#764ba2', 16);
            $_POST['background_video_url'] = input_clean($_POST['background_video_url'] ?? '', 1024);
            $_POST['background_video_autoplay'] = (int) isset($_POST['background_video_autoplay']);
            $_POST['background_video_loop'] = (int) isset($_POST['background_video_loop']);
            $_POST['background_video_mute'] = (int) isset($_POST['background_video_mute']);
            $_POST['background_video_controls'] = (int) isset($_POST['background_video_controls']);
            $_POST['background_overlay_color'] = input_clean($_POST['background_overlay_color'] ?? '#000000', 16);
            $_POST['background_overlay_opacity'] = (int) ($_POST['background_overlay_opacity'] ?? 50);
            $_POST['background_size'] = input_clean($_POST['background_size'] ?? 'cover', 16);
            $_POST['background_position'] = input_clean($_POST['background_position'] ?? 'center', 32);
            
            /* Button settings */
            $_POST['primary_button_bg_color'] = input_clean($_POST['primary_button_bg_color'] ?? '#007bff', 16);
            $_POST['primary_button_text_color'] = input_clean($_POST['primary_button_text_color'] ?? '#ffffff', 16);
            $_POST['primary_button_border_color'] = input_clean($_POST['primary_button_border_color'] ?? '#007bff', 16);
            $_POST['primary_button_style'] = input_clean($_POST['primary_button_style'] ?? 'solid', 16);
            $_POST['primary_button_shape'] = input_clean($_POST['primary_button_shape'] ?? 'rounded', 16);
            $_POST['primary_button_size'] = input_clean($_POST['primary_button_size'] ?? 'medium', 16);
            $_POST['secondary_button_bg_color'] = input_clean($_POST['secondary_button_bg_color'] ?? '#6c757d', 16);
            $_POST['secondary_button_text_color'] = input_clean($_POST['secondary_button_text_color'] ?? '#ffffff', 16);
            $_POST['secondary_button_border_color'] = input_clean($_POST['secondary_button_border_color'] ?? '#6c757d', 16);
            $_POST['secondary_button_style'] = input_clean($_POST['secondary_button_style'] ?? 'outline', 16);
            $_POST['secondary_button_shape'] = input_clean($_POST['secondary_button_shape'] ?? 'rounded', 16);
            $_POST['secondary_button_size'] = input_clean($_POST['secondary_button_size'] ?? 'medium', 16);
            $_POST['secondary_use_primary_settings'] = (int) isset($_POST['secondary_use_primary_settings']);
            
            $_POST['font'] = input_clean($_POST['font'] ?? 'default', 32);
            $_POST['font_size'] = (int) ($_POST['font_size'] ?? 16);

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Image uploads */
            $logo = \SeeGap\Uploads::process_upload(null, 'splash_pages', 'logo', 'logo_remove', settings()->links->avatar_size_limit);
            $favicon = \SeeGap\Uploads::process_upload(null, 'splash_pages', 'favicon', 'favicon_remove', settings()->links->favicon_size_limit);
            $opengraph = \SeeGap\Uploads::process_upload(null, 'splash_pages', 'opengraph', 'opengraph_remove', settings()->links->seo_image_size_limit);
            $background_image = \SeeGap\Uploads::process_upload(null, 'backgrounds', 'background_image', 'background_image_remove', settings()->links->background_size_limit);

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = json_encode([
                    'logo' => $logo,
                    'favicon' => $favicon,
                    'opengraph' => $opengraph,
                    'secondary_button_name' => $_POST['secondary_button_name'],
                    'secondary_button_url' => $_POST['secondary_button_url'],
                    'custom_css' => $_POST['custom_css'],
                    'custom_js' => $_POST['custom_js'],
                    'ads_header' => $_POST['ads_header'],
                    'ads_footer' => $_POST['ads_footer'],
                    'background_type' => $_POST['background_type'],
                    'background' => $_POST['background_type'] == 'image' ? $background_image : $_POST['background'],
                    'background_color_one' => $_POST['background_color_one'],
                    'background_color_two' => $_POST['background_color_two'],
                    'background_video_url' => $_POST['background_video_url'],
                    'background_video_autoplay' => $_POST['background_video_autoplay'],
                    'background_video_loop' => $_POST['background_video_loop'],
                    'background_video_mute' => $_POST['background_video_mute'],
                    'background_video_controls' => $_POST['background_video_controls'],
                    'background_overlay_color' => $_POST['background_overlay_color'],
                    'background_overlay_opacity' => $_POST['background_overlay_opacity'],
                    'background_size' => $_POST['background_size'],
                    'background_position' => $_POST['background_position'],
                    'primary_button_bg_color' => $_POST['primary_button_bg_color'],
                    'primary_button_text_color' => $_POST['primary_button_text_color'],
                    'primary_button_border_color' => $_POST['primary_button_border_color'],
                    'primary_button_style' => $_POST['primary_button_style'],
                    'primary_button_shape' => $_POST['primary_button_shape'],
                    'primary_button_size' => $_POST['primary_button_size'],
                    'secondary_button_bg_color' => $_POST['secondary_button_bg_color'],
                    'secondary_button_text_color' => $_POST['secondary_button_text_color'],
                    'secondary_button_border_color' => $_POST['secondary_button_border_color'],
                    'secondary_button_style' => $_POST['secondary_button_style'],
                    'secondary_button_shape' => $_POST['secondary_button_shape'],
                    'secondary_button_size' => $_POST['secondary_button_size'],
                    'secondary_use_primary_settings' => $_POST['secondary_use_primary_settings'],
                    'font' => $_POST['font'],
                    'font_size' => $_POST['font_size'],
                ]);

                /* Database query */
                db()->insert('splash_pages', [
                    'user_id' => $this->user->user_id,
                    'name' => $_POST['name'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'link_unlock_seconds' => $_POST['link_unlock_seconds'],
                    'auto_redirect' => $_POST['auto_redirect'],
                    'settings' => $settings,
                    'datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('splash_pages?user_id=' . $this->user->user_id);

                redirect('splash-pages');
            }
        }

        $values = [
            'name' => $_POST['name'] ?? '',
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'secondary_button_name' => $_POST['secondary_button_name'] ?? '',
            'secondary_button_url' => $_POST['secondary_button_url'] ?? '',
            'link_unlock_seconds' => $_POST['link_unlock_seconds'] ?? 5,
            'auto_redirect' => $_POST['auto_redirect'] ?? false,
            'custom_css' => $_POST['custom_css'] ?? false,
            'custom_js' => $_POST['custom_js'] ?? false,
            'ads_header' => $_POST['ads_header'] ?? false,
            'ads_footer' => $_POST['ads_footer'] ?? false,
        ];

        /* Prepare the view */
        $data = [
            'values' => $values
        ];

        $view = new \SeeGap\View('splash-page-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
