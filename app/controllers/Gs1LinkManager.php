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
use SeeGap\Models\Domain;
use SeeGap\Models\Gs1Link;
use SeeGap\Title;

defined('SEEGAP') || die();

class Gs1LinkManager extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        /* Check if GS1 links feature is enabled */
        if(!settings()->gs1_links->gs1_links_is_enabled) {
            redirect('dashboard');
        }

        /* Detect mode from URL parameters */
        $mode = isset($this->params[0]) && in_array($this->params[0], ['create', 'edit']) ? $this->params[0] : 'create';
        $gs1_link_id = $mode === 'edit' && isset($this->params[1]) ? (int) $this->params[1] : null;
        $gs1_link = null;

        /* Team checks */
        if($mode === 'create') {
            if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.gs1_links')) {
                Alerts::add_info(l('global.info_message.team_no_access'));
                redirect('gs1-links');
            }

            /* Check for the plan limit */
            $total_rows = (new Gs1Link())->get_gs1_links_count_by_user_id($this->user->user_id);

            if($this->user->plan_settings->gs1_links_limit != -1 && $total_rows >= $this->user->plan_settings->gs1_links_limit) {
                Alerts::add_info(l('global.info_message.plan_feature_limit'));
                redirect('gs1-links');
            }
        } else {
            if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('read.gs1_links')) {
                Alerts::add_info(l('global.info_message.team_no_access'));
                redirect('dashboard');
            }

            /* Make sure the GS1 link exists and is accessible to the user */
            $gs1_link_model = new Gs1Link();
            if(!$gs1_link = $gs1_link_model->get_gs1_link_by_id($gs1_link_id, $this->user->user_id)) {
                redirect('gs1-links');
            }

            /* Team checks for editing */
            if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.gs1_links')) {
                Alerts::add_info(l('global.info_message.team_no_access'));
                redirect('gs1-links');
            }
        }

        /* Get available domains (only if enabled in admin settings) */
        $domains = [];
        if(settings()->gs1_links->domains_is_enabled) {
            $domains = (new Domain())->get_available_domains_by_user($this->user);
        }

        /* Get available projects (only if enabled in admin settings) */
        $projects = [];
        if(settings()->gs1_links->projects_is_enabled) {
            $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);
        }

        /* Get available pixels (only if enabled in admin settings) */
        $pixels = [];
        if(settings()->gs1_links->pixels_is_enabled) {
            $pixels = (new \SeeGap\Models\Pixel())->get_pixels($this->user->user_id);
        }

        /* Get available splash pages */
        $splash_pages = (new \SeeGap\Models\SplashPages())->get_splash_pages_by_user_id($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['gtin'] = input_clean($_POST['gtin']);
            $_POST['target_url'] = input_clean($_POST['target_url']);
            $_POST['title'] = input_clean($_POST['title'] ?? '', 256);
            $_POST['description'] = input_clean($_POST['description'] ?? '');
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
            $_POST['start_date'] = !empty($_POST['start_date']) ? \SeeGap\Date::get($_POST['start_date'], 2) : null;
            $_POST['end_date'] = !empty($_POST['end_date']) ? \SeeGap\Date::get($_POST['end_date'], 2) : null;
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

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['gtin'];
            
            /* Add target_url as required field if admin setting requires it */
            if(settings()->gs1_links->require_target_url) {
                $required_fields[] = 'target_url';
            }
            
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            /* Format and validate GTIN according to admin settings */
            $_POST['gtin'] = preg_replace('/[^0-9]/', '', $_POST['gtin']);
            
            /* Apply GTIN validation based on format validation setting */
            if(settings()->gs1_links->gtin_validation_is_enabled && settings()->gs1_links->gtin_format_validation !== 'disabled') {
                $gtin_length = strlen($_POST['gtin']);
                $valid_lengths = [8, 12, 13, 14];
                
                /* Length validation for both lenient and strict modes */
                if(!in_array($gtin_length, $valid_lengths)) {
                    Alerts::add_field_error('gtin', l('gs1_link_create.error_message.gtin_invalid_length'));
                } else {
                    /* Strict validation includes checksum validation */
                    if(settings()->gs1_links->gtin_format_validation === 'strict') {
                        if(!$this->validate_gtin_checksum($_POST['gtin'])) {
                            Alerts::add_field_error('gtin', l('gs1_link_create.error_message.gtin_invalid_checksum'));
                        }
                    }
                    /* Lenient mode only does length validation (already done above) */
                }
            }
            
            /* Apply additional validation rules if GTIN validation is enabled */
            if(settings()->gs1_links->gtin_validation_is_enabled) {
                /* Check blacklisted GTINs */
                if(!empty(settings()->gs1_links->blacklisted_gtins) && in_array($_POST['gtin'], settings()->gs1_links->blacklisted_gtins)) {
                    Alerts::add_field_error('gtin', l('gs1_link_create.error_message.gtin_blacklisted'));
                }
                
                /* Check allowed GTIN prefixes */
                if(!empty(settings()->gs1_links->allowed_gtin_prefixes)) {
                    $prefix_allowed = false;
                    foreach(settings()->gs1_links->allowed_gtin_prefixes as $prefix) {
                        if(str_starts_with($_POST['gtin'], $prefix)) {
                            $prefix_allowed = true;
                            break;
                        }
                    }
                    if(!$prefix_allowed) {
                        Alerts::add_field_error('gtin', l('gs1_link_create.error_message.gtin_prefix_not_allowed'));
                    }
                }
            }

            /* Apply default target URL if not provided and not required */
            if(empty($_POST['target_url']) && !settings()->gs1_links->require_target_url && !empty(settings()->gs1_links->default_target_url)) {
                $_POST['target_url'] = settings()->gs1_links->default_target_url;
            }
            
            /* Validate target URL if provided */
            if(!empty($_POST['target_url']) && !filter_var($_POST['target_url'], FILTER_VALIDATE_URL)) {
                Alerts::add_field_error('target_url', l('global.error_message.invalid_url'));
            }

            /* Check if GTIN already exists for this domain */
            $gs1_link_model = new Gs1Link();
            if($mode === 'create') {
                if($gs1_link_model->get_gs1_link_by_gtin($_POST['gtin'], $_POST['domain_id'])) {
                    Alerts::add_field_error('gtin', l('gs1_link_create.error_message.gtin_exists'));
                }
            } else {
                /* For edit, check if GTIN already exists for this domain (excluding current link) */
                if($_POST['gtin'] != $gs1_link->gtin || $_POST['domain_id'] != $gs1_link->domain_id) {
                    if($gs1_link_model->get_gs1_link_by_gtin($_POST['gtin'], $_POST['domain_id'])) {
                        Alerts::add_field_error('gtin', l('gs1_link.error_message.gtin_exists'));
                    }
                }
            }

            if(!\SeeGap\Csrf::check()) {
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

                if($mode === 'create') {
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
                        /* Auto-generate QR code if enabled in admin settings */
                        if(settings()->gs1_links->auto_generate_qr_codes) {
                            $this->auto_generate_qr_code($gs1_link_id, $_POST['gtin'], $_POST['domain_id']);
                        }
                        
                        /* Set a nice success message */
                        Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['gtin'] . '</strong>'));

                        /* Clear the cache */
                        cache()->deleteItem('gs1_links_total?user_id=' . $this->user->user_id);

                        redirect('gs1-link-manager/edit/' . $gs1_link_id);
                    } else {
                        Alerts::add_error(l('global.error_message.create'));
                    }
                } else {
                    /* Update the GS1 link */
                    $updated = $gs1_link_model->update_gs1_link($gs1_link->gs1_link_id, [
                        'gtin' => $_POST['gtin'],
                        'target_url' => $_POST['target_url'],
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'project_id' => $_POST['project_id'],
                        'domain_id' => $_POST['domain_id'],
                        'is_enabled' => $_POST['is_enabled'],
                        'settings' => $settings,
                        'pixels_ids' => $_POST['pixels_ids'],
                    ], $this->user->user_id);

                    if($updated) {
                        /* Set a nice success message */
                        Alerts::add_success(l('global.success_message.update2'));

                        /* Clear the cache */
                        cache()->deleteItem('gs1_links_total?user_id=' . $this->user->user_id);

                        /* Refresh the page */
                        redirect('gs1-link-manager/edit/' . $gs1_link->gs1_link_id);
                    } else {
                        Alerts::add_error(l('global.error_message.update'));
                    }
                }
            }
        }

        /* Set a custom title */
        if($mode === 'create') {
            Title::set(l('gs1_link_create.title'));
        } else {
            Title::set(sprintf(l('gs1_link.title'), $gs1_link->gtin));
        }

        /* Prepare default values */
        if($mode === 'create') {
            $values = [
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
            ];
        } else {
            $values = [
                'gtin' => $gs1_link->gtin,
                'target_url' => $gs1_link->target_url,
                'title' => $gs1_link->title,
                'description' => $gs1_link->description,
                'domain_id' => $gs1_link->domain_id,
                'project_id' => $gs1_link->project_id,
                'pixels_ids' => $gs1_link->pixels_ids ?? [],
                'is_enabled' => $gs1_link->is_enabled,
                'schedule' => $gs1_link->settings->schedule ?? 0,
                'start_date' => $gs1_link->settings->start_date ?? '',
                'end_date' => $gs1_link->settings->end_date ?? '',
                'clicks_limit' => $gs1_link->settings->clicks_limit ?? '',
                'expiration_url' => $gs1_link->settings->expiration_url ?? '',
                'targeting_type' => $gs1_link->settings->targeting_type ?? 'false',
                'utm_source' => $gs1_link->settings->utm->source ?? '',
                'utm_medium' => $gs1_link->settings->utm->medium ?? '',
                'utm_campaign' => $gs1_link->settings->utm->campaign ?? '',
                'cloaking_is_enabled' => $gs1_link->settings->cloaking_is_enabled ?? 0,
                'cloaking_title' => $gs1_link->settings->cloaking_title ?? '',
                'cloaking_meta_description' => $gs1_link->settings->cloaking_meta_description ?? '',
                'cloaking_custom_js' => $gs1_link->settings->cloaking_custom_js ?? '',
                'splash_page_id' => $gs1_link->settings->splash_page_id ?? '',
                'forward_query_parameters_is_enabled' => $gs1_link->settings->forward_query_parameters_is_enabled ?? 0,
            ];

            /* Load existing targeting data */
            if($gs1_link->settings->targeting_type && $gs1_link->settings->targeting_type !== 'false') {
                $targeting_key = 'targeting_' . $gs1_link->settings->targeting_type;
                if(isset($gs1_link->settings->{$targeting_key})) {
                    $values[$targeting_key] = $gs1_link->settings->{$targeting_key};
                }
            }
        }

        /* Prepare the View */
        $data = [
            'mode' => $mode,
            'gs1_link' => $gs1_link,
            'domains' => $domains,
            'projects' => $projects,
            'pixels' => $pixels,
            'splash_pages' => $splash_pages,
            'values' => $values,
        ];

        $view = new \SeeGap\View('gs1-link-manager/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    /**
     * Auto-generate QR code for GS1 link
     */
    private function auto_generate_qr_code($gs1_link_id, $gtin, $domain_id) {
        try {
            /* Get the GS1 link to generate the full URL */
            $gs1_link_model = new Gs1Link();
            $gs1_link = $gs1_link_model->get_gs1_link_by_id($gs1_link_id, $this->user->user_id);
            
            if (!$gs1_link) {
                return false;
            }
            
            /* Generate QR code data */
            $qr_code_data = [
                'user_id' => $this->user->user_id,
                'project_id' => $gs1_link->project_id,
                'name' => 'GS1 QR - ' . $gtin,
                'type' => 'text',
                'text' => $gs1_link->full_url,
                'settings' => json_encode([
                    'size' => 500,
                    'margin' => 1,
                    'ecc' => 'M',
                    'foreground_type' => 'color',
                    'foreground_color' => '#000000',
                    'background_color' => '#ffffff',
                    'custom_eyes_color' => false,
                    'eyes_inner_color' => '#000000',
                    'eyes_outer_color' => '#000000',
                    'qr_code_logo' => '',
                    'qr_code_logo_size' => 25,
                    'qr_code_background' => '',
                    'qr_code_foreground' => ''
                ]),
                'is_enabled' => 1,
                'datetime' => date('Y-m-d H:i:s')
            ];
            
            /* Insert QR code into database */
            $qr_code_id = database()->insert('qr_codes', $qr_code_data);
            
            return $qr_code_id;
        } catch (Exception $e) {
            /* Silently fail - QR code generation is optional */
            return false;
        }
    }
    
    /**
     * Validate GTIN checksum using the standard algorithm
     */
    private function validate_gtin_checksum($gtin) {
        $gtin = str_pad($gtin, 14, '0', STR_PAD_LEFT);
        
        if (strlen($gtin) !== 14) {
            return false;
        }
        
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $digit = (int) $gtin[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        
        $checksum = (10 - ($sum % 10)) % 10;
        return $checksum === (int) $gtin[13];
    }

}
