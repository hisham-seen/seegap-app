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

class AdminMicrositeThemeUpdate extends Controller {

    public function index() {

        $microsite_theme_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$microsite_theme = db()->where('microsite_theme_id', $microsite_theme_id)->getOne('microsites_themes')) {
            redirect('admin/microsites-themes');
        }
        $microsite_theme->settings = json_decode($microsite_theme->settings ?? '');

        $microsite_fonts = require APP_PATH . 'includes/microsite_fonts.php';
        $microsite_backgrounds = require APP_PATH . 'includes/microsite_backgrounds.php';
        $links_types = require APP_PATH . 'includes/links_types.php';

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            $_POST['additional_custom_css'] = mb_substr(trim($_POST['additional_custom_css']), 0, 10000);
            $_POST['additional_custom_js'] = mb_substr(trim($_POST['additional_custom_js']), 0, 10000);

            /* Width */
            $_POST['width'] = isset($_POST['microsite_width']) && in_array($_POST['microsite_width'], [6, 8, 10, 12]) ? (int) $_POST['microsite_width'] : 8;

            /* Block spacing */
            $_POST['block_spacing'] = isset($_POST['microsite_block_spacing']) && in_array($_POST['microsite_block_spacing'], [1, 2, 3,]) ? (int) $_POST['microsite_block_spacing'] : 2;

            /* Link hover animation */
            $_POST['hover_animation'] = isset($_POST['microsite_hover_animation']) && in_array($_POST['microsite_hover_animation'], ['false', 'smooth', 'instant',]) ? input_clean($_POST['microsite_hover_animation']) : 'smooth';


            //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for errors & process  potential uploads */
            $background_new_name = $microsite_theme->settings->microsite->background_type == 'image' ? $microsite_theme->settings->microsite->background : null;

            if($_POST['microsite_background_type'] == 'image') {
                $background_new_name = \SeeGap\Uploads::process_upload($microsite_theme->settings->microsite->background, 'microsite_background', 'microsite_background_image', 'microsite_background_image_remove', null);
            }

            if($background_new_name && $_POST['microsite_background_type'] != 'image') {
                $background_new_name = null;
                \SeeGap\Uploads::delete_uploaded_file($background_new_name, 'microsite_background');
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $microsite_background = $background_new_name ?? $_POST['microsite_background'] ?? null;

                $settings = json_encode([
                    'additional' => [
                        'custom_css' => $_POST['additional_custom_css'] ?? null,
                        'custom_js' => $_POST['additional_custom_js'] ?? null,
                    ],

                    'microsite' => [
                        'background_type' => $_POST['microsite_background_type'],
                        'background' => $microsite_background,
                        'background_color_one' => $_POST['microsite_background_color_one'],
                        'background_color_two' => $_POST['microsite_background_color_two'],
                        'font' => $_POST['microsite_font'],
                        'font_size' => $_POST['microsite_font_size'],
                        'background_blur' => (int) $_POST['microsite_background_blur'],
                        'background_brightness' => (int) $_POST['microsite_background_brightness'],
                        'width' => $_POST['width'],
                        'block_spacing' => $_POST['block_spacing'],
                        'hover_animation' => $_POST['hover_animation'],
                    ],

                    'microsite_block' => [
                        'text_color' => $_POST['microsite_block_text_color'],
                        'description_color' => $_POST['microsite_block_description_color'],
                        'background_color' => $_POST['microsite_block_background_color'],
                        'border_width' => $_POST['microsite_block_border_width'],
                        'border_color' => $_POST['microsite_block_border_color'],
                        'border_radius' => $_POST['microsite_block_border_radius'],
                        'border_style' => $_POST['microsite_block_border_style'],
                        'border_shadow_offset_x' => $_POST['microsite_block_border_shadow_offset_x'],
                        'border_shadow_offset_y' => $_POST['microsite_block_border_shadow_offset_y'],
                        'border_shadow_blur' => $_POST['microsite_block_border_shadow_blur'],
                        'border_shadow_spread' => $_POST['microsite_block_border_shadow_spread'],
                        'border_shadow_color' => $_POST['microsite_block_border_shadow_color'],
                    ],

                    'microsite_block_socials' => [
                        'color' => $_POST['microsite_block_socials_color'],
                        'background_color' => $_POST['microsite_block_socials_background_color'],
                        'border_radius' => $_POST['microsite_block_socials_border_radius'],
                    ],

                    'microsite_block_paragraph' => [
                        'text_color' => $_POST['microsite_block_paragraph_text_color'],
                    ],

                    'microsite_block_heading' => [
                        'text_color' => $_POST['microsite_block_heading_text_color'],
                    ],
                ]);

                /* Database query */
                db()->where('microsite_theme_id', $microsite_theme_id)->update('microsites_themes', [
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'order' => $_POST['order'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('microsites_themes');

                /* Refresh the page */
                redirect('admin/microsite-theme-update/' . $microsite_theme_id);

            }

        }

        /* Main View */
        $data = [
            'microsite_theme_id' => $microsite_theme_id,
            'microsite_theme' => $microsite_theme,
            'microsite_backgrounds' => $microsite_backgrounds,
            'microsite_fonts' => $microsite_fonts,
            'links_types' => $links_types,
        ];

        $view = new \SeeGap\View('admin/microsite-theme-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
