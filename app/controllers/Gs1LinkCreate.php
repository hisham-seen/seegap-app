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

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['gtin', 'target_url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            /* Validate GTIN */
            if(!validate_gtin($_POST['gtin'])) {
                Alerts::add_field_error('gtin', l('gs1_link_create.error_message.invalid_gtin'));
            }

            /* Format GTIN */
            $_POST['gtin'] = format_gtin($_POST['gtin']);

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
                    ]
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

        /* Main View */
        $data = [
            'domains' => $domains,
            'projects' => $projects,
            'pixels' => $pixels,
        ];

        $view = new \Altum\View('gs1-link-create/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

}
