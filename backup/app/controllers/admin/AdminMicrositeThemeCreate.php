<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class AdminMicrositeThemeCreate extends Controller {

    public function index() {

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

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for errors & process potential uploads */
            $background_new_name = \Altum\Uploads::process_upload(null, 'microsite_background', 'microsite_background_image', 'background_remove', null);

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $settings = json_encode([
                    'additional' => [
                        'custom_css' => $_POST['additional_custom_css'] ?? null,
                        'custom_js' => $_POST['additional_custom_js'] ?? null,
                    ],

                    'microsite' => [
                        'background_type' => $_POST['microsite_background_type'] ?? 'preset',
                        'background' => $background_new_name ?? $_POST['microsite_background'] ?? 'one',
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
                db()->insert('microsites_themes', [
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'order' => $_POST['order'],
                    'datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('microsites_themes');

                redirect('admin/microsites-themes');
            }
        }

        $values = [
            'name' => $_POST['name'] ?? null,
            'order' => $_POST['order'] ?? 0,
            'is_enabled' => $_POST['is_enabled'] ?? 1,
            'additional_custom_css' => $_POST['additional_custom_css'] ?? null,
            'additional_custom_js' => $_POST['additional_custom_js'] ?? null,
            'microsite_width' => $_POST['microsite_width'] ?? 8,
            'microsite_block_spacing' => $_POST['microsite_block_spacing'] ?? 2,
            'microsite_hover_animation' => $_POST['microsite_hover_animation'] ?? 'smooth',
            'microsite_background_type' => $_POST['microsite_background_type'] ?? null,
            'microsite_background' => $_POST['microsite_background'] ?? null,
            'microsite_background_color_one' => $_POST['microsite_background_color_one'] ?? null,
            'microsite_background_color_two' => $_POST['microsite_background_color_two'] ?? null,
            'microsite_font' => $_POST['microsite_font'] ?? null,
            'microsite_font_size' => $_POST['microsite_font_size'] ?? 16,
            'microsite_background_blur' => $_POST['microsite_background_blur'] ?? 0,
            'microsite_background_brightness' => $_POST['microsite_background_brightness'] ?? 100,

            'microsite_block_text_color' => $_POST['microsite_block_text_color'] ?? '#ffffff',
            'microsite_block_description_color' => $_POST['microsite_block_description_color'] ?? '#ffffff',
            'microsite_block_background_color' => $_POST['microsite_block_background_color'] ?? '#000000',
            'microsite_block_border_width' => $_POST['microsite_block_border_width'] ?? 0,
            'microsite_block_border_color' => $_POST['microsite_block_border_color'] ?? null,
            'microsite_block_border_radius' => $_POST['microsite_block_border_radius'] ?? null,
            'microsite_block_border_style' => $_POST['microsite_block_border_style'] ?? null,
            'microsite_block_border_shadow_offset_x' => $_POST['microsite_block_border_shadow_offset_x'] ?? 0,
            'microsite_block_border_shadow_offset_y' => $_POST['microsite_block_border_shadow_offset_y'] ?? 0,
            'microsite_block_border_shadow_blur' => $_POST['microsite_block_border_shadow_blur'] ?? 20,
            'microsite_block_border_shadow_spread' => $_POST['microsite_block_border_shadow_spread'] ?? 0,
            'microsite_block_border_shadow_color' => $_POST['microsite_block_border_shadow_color'] ?? '#00000010',

            'microsite_block_socials_color' => $_POST['microsite_block_socials_color'] ?? '#ffffff',
            'microsite_block_socials_background_color' => $_POST['microsite_block_socials_background_color'] ?? '#00000000',
            'microsite_block_socials_border_radius' => $_POST['microsite_block_socials_border_radius'] ?? 'rounded',

            'microsite_block_paragraph_text_color' => $_POST['microsite_block_paragraph_text_color'] ?? '#000000',
            'microsite_block_heading_text_color' => $_POST['microsite_block_heading_text_color'] ?? '#000000',
        ];

        /* Main View */
        $data = [
            'values' => $values,
            'microsite_backgrounds' => $microsite_backgrounds,
            'microsite_fonts' => $microsite_fonts,
            'links_types' => $links_types,
        ];

        $view = new \Altum\View('admin/microsite-theme-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
