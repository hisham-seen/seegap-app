<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap;

defined('SEEGAP') || die();

class SocialMediaEmbedBlock extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Plugin::is_active('teams') && !$this->team_member->team_member_id) {
            redirect('teams-system');
        }

        $microsite_block_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$microsite_block = db()->where('microsite_block_id', $microsite_block_id)->where('user_id', $this->user->user_id)->getOne('microsite_blocks')) {
            redirect('dashboard');
        }

        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        /* Get the link */
        $link = db()->where('link_id', $microsite_block->link_id)->where('user_id', $this->user->user_id)->getOne('links', ['link_id', 'domain_id', 'type', 'url', 'settings']);

        if(!$link) {
            redirect('dashboard');
        }

        $link->settings = json_decode($link->settings ?? '');

        /* Handle form submission */
        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['name'] = mb_substr(input_clean($_POST['name']), 0, 128);
            $_POST['platform'] = mb_substr(input_clean($_POST['platform']), 0, 32);
            $_POST['embed_type'] = mb_substr(input_clean($_POST['embed_type']), 0, 32);
            
            /* Process embed data */
            $embed_data = [];
            if(isset($_POST['embed_data']) && is_array($_POST['embed_data'])) {
                foreach($_POST['embed_data'] as $key => $value) {
                    $embed_data[input_clean($key)] = input_clean($value);
                }
            }

            /* Process settings */
            $settings = [
                'name' => $_POST['name'],
                'platform' => $_POST['platform'],
                'embed_type' => $_POST['embed_type'],
                'embed_data' => (object) $embed_data,
                'open_in_new_tab' => isset($_POST['open_in_new_tab']),
                'responsive' => isset($_POST['responsive']),
            ];

            /* Background settings */
            if(isset($_POST['background_color'])) {
                $settings['background_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? '#000000' : $_POST['background_color'];
            }

            /* Border settings */
            if(isset($_POST['border_width'])) {
                $settings['border_width'] = (int) $_POST['border_width'];
                $settings['border_style'] = in_array($_POST['border_style'], ['solid', 'dashed', 'double', 'inset', 'outset']) ? $_POST['border_style'] : 'solid';
                $settings['border_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['border_color']) ? '#000000' : $_POST['border_color'];
                $settings['border_radius'] = (int) $_POST['border_radius'];
            }

            /* Shadow settings */
            if(isset($_POST['border_shadow_offset_x'])) {
                $settings['border_shadow_offset_x'] = max(-25, min(25, (int) $_POST['border_shadow_offset_x']));
                $settings['border_shadow_offset_y'] = max(-25, min(25, (int) $_POST['border_shadow_offset_y']));
                $settings['border_shadow_blur'] = max(0, min(30, (int) $_POST['border_shadow_blur']));
                $settings['border_shadow_spread'] = max(-15, min(15, (int) $_POST['border_shadow_spread']));
                $settings['border_shadow_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['border_shadow_color']) ? '#000000' : $_POST['border_shadow_color'];
                $settings['border_shadow'] = $settings['border_shadow_offset_x'] . 'px ' . $settings['border_shadow_offset_y'] . 'px ' . $settings['border_shadow_blur'] . 'px ' . $settings['border_shadow_spread'] . 'px ' . $settings['border_shadow_color'];
            }

            /* Animation settings */
            if(isset($_POST['animation'])) {
                $settings['animation'] = in_array($_POST['animation'], require APP_PATH . 'includes/animations.php') || $_POST['animation'] == 'false' ? input_clean($_POST['animation']) : false;
                $settings['animation_runs'] = isset($_POST['animation_runs']) && in_array($_POST['animation_runs'], ['repeat-1', 'repeat-2', 'repeat-3', 'infinite']) ? $_POST['animation_runs'] : 'repeat-1';
            }

            /* Display settings */
            $settings['display_continents'] = array_filter($_POST['display_continents'] ?? [], function($continent) {
                return in_array($continent, get_continents_array());
            });
            $settings['display_countries'] = array_filter($_POST['display_countries'] ?? [], function($country) {
                return array_key_exists($country, get_countries_array());
            });
            $settings['display_devices'] = array_filter($_POST['display_devices'] ?? [], function($device) {
                return in_array($device, ['desktop', 'tablet', 'mobile']);
            });
            $settings['display_languages'] = array_filter($_POST['display_languages'] ?? [], function($language) {
                return array_key_exists($language, get_locale_languages_array());
            });
            $settings['display_operating_systems'] = array_filter($_POST['display_operating_systems'] ?? [], function($os) {
                return in_array($os, ['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS']);
            });
            $settings['display_browsers'] = array_filter($_POST['display_browsers'] ?? [], function($browser) {
                return in_array($browser, ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet']);
            });

            $settings = json_encode($settings);

            switch($_POST['request_type']) {

                case 'create':

                    $microsite_block_id = db()->insert('microsite_blocks', [
                        'user_id' => $this->user->user_id,
                        'link_id' => $_POST['link_id'],
                        'type' => 'social_media_embed',
                        'settings' => $settings,
                        'start_date' => !empty($_POST['start_date']) ? Date::get($_POST['start_date'], null) : null,
                        'end_date' => !empty($_POST['end_date']) ? Date::get($_POST['end_date'], null) : null,
                        'datetime' => Date::$date,
                    ]);

                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . ($_POST['name'] ? input_clean($_POST['name']) : l('microsite_social_embed.name')) . '</strong>'));

                    redirect('link/' . $_POST['link_id'] . '?tab=blocks');

                    break;

                case 'update':

                    db()->where('microsite_block_id', $microsite_block->microsite_block_id)->update('microsite_blocks', [
                        'settings' => $settings,
                        'start_date' => !empty($_POST['start_date']) ? Date::get($_POST['start_date'], null) : null,
                        'end_date' => !empty($_POST['end_date']) ? Date::get($_POST['end_date'], null) : null,
                        'last_datetime' => Date::$date,
                    ]);

                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . ($_POST['name'] ? input_clean($_POST['name']) : l('microsite_social_embed.name')) . '</strong>'));

                    redirect('link/' . $link->link_id . '?tab=blocks');

                    break;

                case 'duplicate':

                    db()->insert('microsite_blocks', [
                        'user_id' => $this->user->user_id,
                        'link_id' => $microsite_block->link_id,
                        'type' => $microsite_block->type,
                        'settings' => $microsite_block->settings,
                        'start_date' => $microsite_block->start_date,
                        'end_date' => $microsite_block->end_date,
                        'datetime' => Date::$date,
                    ]);

                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . (json_decode($microsite_block->settings)->name ?? l('microsite_social_embed.name')) . '</strong>'));

                    redirect('link/' . $link->link_id . '?tab=blocks');

                    break;

            }

        }

        /* Prepare the View */
        $data = [
            'microsite_block' => $microsite_block,
            'link' => $link
        ];

        $view = new \SeeGap\View('microsite-blocks/social_media_embed_block', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
