<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Models\Domain;
use Altum\Models\Gs1Link;
use Altum\Title;

defined('ALTUMCODE') || die();

class Gs1LinkCreate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.gs1_links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('gs1-links');
        }

        /* Check for the plan limit */
        $total_rows = (new Gs1Link())->get_gs1_links_count_by_user_id($this->user->user_id);

        if($this->user->plan_settings->gs1_links_limit != -1 && $total_rows >= $this->user->plan_settings->gs1_links_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('gs1-links');
        }

        /* Get available domains */
        $domains = (new Domain())->get_available_domains_by_user($this->user);

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get available pixels */
        $pixels = (new \Altum\Models\Pixel())->get_pixels($this->user->user_id);

        /* Get available splash pages */
        $splash_pages = (new \Altum\Models\SplashPages())->get_splash_pages_by_user_id($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['gtin'] = input_clean($_POST['gtin']);
            $_POST['target_url'] = input_clean($_POST['target_url']);
            $_POST['title'] = input_clean($_POST['title'], 256);
            $_POST['description'] = input_clean($_POST['description']);
            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
            $_POST['domain_id'] = isset($_POST['domain_id']) && array_key_exists($_POST['domain_id'], $domains) ? (int) $_POST['domain_id'] : 0;
            $_POST['pixels_ids'] = array_map(
                function($pixel_id) {
                    return (int) $pixel_id;
                },
                array_filter($_POST['pixels_ids'] ?? [], function($pixel_id) use($pixels) {
                    return array_key_exists($pixel_id, $pixels);
                })
            );

            /* Process advanced settings */
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
            $_POST['schedule'] = (int) isset($_POST['schedule']);
            $_POST['start_date'] = !empty($_POST['start_date']) ? \Altum\Date::get($_POST['start_date'], 2) : null;
            $_POST['end_date'] = !empty($_POST['end_date']) ? \Altum\Date::get($_POST['end_date'], 2) : null;
            $_POST['clicks_limit'] = !empty($_POST['clicks_limit']) ? (int) $_POST['clicks_limit'] : null;
            $_POST['expiration_url'] = input_clean($_POST['expiration_url']);
            $_POST['targeting_type'] = input_clean($_POST['targeting_type']);
            $_POST['utm_source'] = input_clean($_POST['utm_source'], 128);
            $_POST['utm_medium'] = input_clean($_POST['utm_medium'], 128);
            $_POST['utm_campaign'] = input_clean($_POST['utm_campaign'], 128);
            $_POST['cloaking_is_enabled'] = (int) isset($_POST['cloaking_is_enabled']);
            $_POST['cloaking_title'] = input_clean($_POST['cloaking_title'], 70);
            $_POST['cloaking_meta_description'] = input_clean($_POST['cloaking_meta_description'], 160);
            $_POST['cloaking_custom_js'] = input_clean($_POST['cloaking_custom_js'], 10000);
            $_POST['splash_page_id'] = !empty($_POST['splash_page_id']) && array_key_exists($_POST['splash_page_id'], $splash_pages) ? (int) $_POST['splash_page_id'] : null;
            $_POST['forward_query_parameters_is_enabled'] = (int) isset($_POST['forward_query_parameters_is_enabled']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['gtin', 'target_url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            /* Format GTIN - just clean it up */
            $_POST['gtin'] = preg_replace('/[^0-9]/', '', $_POST['gtin']);

            /* Validate target URL */
            if(!filter_var($_POST['target_url'], FILTER_VALIDATE_URL)) {
                Alerts::add_field_error('target_url', l('global.error_message.invalid_url'));
            }

            /* Check if GTIN already exists for this domain */
            $gs1_link_model = new Gs1Link();
            if($gs1_link_model->get_gs1_link_by_gtin($_POST['gtin'], $_POST['domain_id'])) {
                Alerts::add_field_error('gtin', l('gs1_link_create.error_message.gtin_exists'));
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Process targeting settings */
            $targeting = [];
            if($_POST['targeting_type'] != 'false') {
                switch($_POST['targeting_type']) {
                    case 'continent_code':
                    case 'country_code':
                    case 'city_name':
                    case 'device_type':
                    case 'os_name':
                    case 'browser_name':
                    case 'browser_language':
                    case 'rotation':
                        $targeting_key = 'targeting_' . $_POST['targeting_type'] . '_key';
                        $targeting_value = 'targeting_' . $_POST['targeting_type'] . '_value';
                        
                        if(isset($_POST[$targeting_key]) && isset($_POST[$targeting_value])) {
                            foreach($_POST[$targeting_key] as $key => $value) {
                                if(!empty($value) && !empty($_POST[$targeting_value][$key])) {
                                    $targeting[] = [
                                        'key' => input_clean($value),
                                        'value' => input_clean($_POST[$targeting_value][$key])
                                    ];
                                }
                            }
                        }
                        break;
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Prepare settings */
                $settings = [
                    'seo' => [
                        'title' => $_POST['title'] ?? '',
                        'meta_description' => $_POST['description'] ?? '',
                    ],
                    'utm' => [
                        'source' => $_POST['utm_source'] ?? '',
                        'medium' => $_POST['utm_medium'] ?? '',
                        'campaign' => $_POST['utm_campaign'] ?? '',
                    ],
                    'schedule' => $_POST['schedule'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'clicks_limit' => $_POST['clicks_limit'],
                    'expiration_url' => $_POST['expiration_url'],
                    'targeting_type' => $_POST['targeting_type'],
                    'targeting_' . $_POST['targeting_type'] => $targeting,
                    'cloaking_is_enabled' => $_POST['cloaking_is_enabled'],
                    'cloaking_title' => $_POST['cloaking_title'],
                    'cloaking_meta_description' => $_POST['cloaking_meta_description'],
                    'cloaking_custom_js' => $_POST['cloaking_custom_js'],
                    'splash_page_id' => $_POST['splash_page_id'],
                    'forward_query_parameters_is_enabled' => $_POST['forward_query_parameters_is_enabled'],
                ];

                /* Create the GS1 link */
                $gs1_link_id = $gs1_link_model->create_gs1_link([
                    'user_id' => $this->user->user_id,
                    'project_id' => $_POST['project_id'],
                    'domain_id' => $_POST['domain_id'],
                    'gtin' => $_POST['gtin'],
                    'target_url' => $_POST['target_url'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'settings' => $settings,
                    'pixels_ids' => $_POST['pixels_ids'],
                    'is_enabled' => $_POST['is_enabled'],
                ]);

                if($gs1_link_id) {
                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['gtin'] . '</strong>'));

                    /* Clear the cache */
                    cache()->deleteItem('gs1_links_total?user_id=' . $this->user->user_id);

                    redirect('gs1-link/' . $gs1_link_id);
                } else {
                    Alerts::add_error(l('global.error_message.create'));
                }
            }
        }

        /* Set a custom title */
        Title::set(l('gs1_link_create.title'));

        /* Prepare the View */
        $data = [
            'domains' => $domains,
            'projects' => $projects,
            'pixels' => $pixels,
            'splash_pages' => $splash_pages,
            'values' => [
                'gtin' => $_POST['gtin'] ?? '',
                'target_url' => $_POST['target_url'] ?? '',
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'domain_id' => $_POST['domain_id'] ?? '',
                'project_id' => $_POST['project_id'] ?? '',
                'pixels_ids' => $_POST['pixels_ids'] ?? [],
                'is_enabled' => $_POST['is_enabled'] ?? 1,
                'schedule' => $_POST['schedule'] ?? 0,
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'clicks_limit' => $_POST['clicks_limit'] ?? '',
                'expiration_url' => $_POST['expiration_url'] ?? '',
                'targeting_type' => $_POST['targeting_type'] ?? 'false',
                'utm_source' => $_POST['utm_source'] ?? '',
                'utm_medium' => $_POST['utm_medium'] ?? '',
                'utm_campaign' => $_POST['utm_campaign'] ?? '',
                'cloaking_is_enabled' => $_POST['cloaking_is_enabled'] ?? 0,
                'cloaking_title' => $_POST['cloaking_title'] ?? '',
                'cloaking_meta_description' => $_POST['cloaking_meta_description'] ?? '',
                'cloaking_custom_js' => $_POST['cloaking_custom_js'] ?? '',
                'splash_page_id' => $_POST['splash_page_id'] ?? '',
                'forward_query_parameters_is_enabled' => $_POST['forward_query_parameters_is_enabled'] ?? 0,
                'http_status_code' => '302', // Default for GS1 links
            ]
        ];

        $view = new \Altum\View('gs1-link-create/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

}
