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
use Altum\Models\Gs1Link as Gs1LinkModel;
use Altum\Title;

defined('ALTUMCODE') || die();

class Gs1Link extends Controller {
    public $gs1_link;

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('read.gs1_links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $gs1_link_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        $method = isset($this->params[1]) && in_array($this->params[1], ['settings', 'statistics']) ? $this->params[1] : 'settings';

        /* Make sure the GS1 link exists and is accessible to the user */
        $gs1_link_model = new Gs1LinkModel();
        if(!$this->gs1_link = $gs1_link_model->get_gs1_link_by_id($gs1_link_id, $this->user->user_id)) {
            redirect('gs1-links');
        }

        /* Set a custom title */
        Title::set(sprintf(l('gs1_link.title'), $this->gs1_link->gtin));

        /* Handle code for different parts of the page */
        switch($method) {
            case 'settings':

                /* Team checks */
                if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.gs1_links')) {
                    Alerts::add_info(l('global.info_message.team_no_access'));
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
                    $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
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
                        Alerts::add_field_error('gtin', l('gs1_link.error_message.invalid_gtin'));
                    }

                    /* Format GTIN */
                    $_POST['gtin'] = format_gtin($_POST['gtin']);

                    /* Validate target URL */
                    if(!filter_var($_POST['target_url'], FILTER_VALIDATE_URL)) {
                        Alerts::add_field_error('target_url', l('global.error_message.invalid_url'));
                    }

                    /* Check if GTIN already exists for this domain (excluding current link) */
                    if($_POST['gtin'] != $this->gs1_link->gtin || $_POST['domain_id'] != $this->gs1_link->domain_id) {
                        if($gs1_link_model->get_gs1_link_by_gtin($_POST['gtin'], $_POST['domain_id'])) {
                            Alerts::add_field_error('gtin', l('gs1_link.error_message.gtin_exists'));
                        }
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

                        /* Update the GS1 link */
                        $updated = $gs1_link_model->update_gs1_link($this->gs1_link->gs1_link_id, [
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
                            redirect('gs1-link/' . $this->gs1_link->gs1_link_id . '/settings');
                        } else {
                            Alerts::add_error(l('global.error_message.update'));
                        }
                    }
                }

                /* Prepare variables for the view */
                $data = [
                    'gs1_link' => $this->gs1_link,
                    'method' => $method,
                    'domains' => $domains,
                    'projects' => $projects,
                    'pixels' => $pixels,
                ];

                break;

            case 'statistics':

                if(!$this->user->plan_settings->statistics) {
                    Alerts::add_info(l('global.info_message.plan_feature_no_access'));
                    redirect('gs1-links');
                }

                $action = isset($this->params[2]) && in_array($this->params[2], ['reset']) ? $this->params[2] : null;

                if($action) {
                    switch($action) {
                        case 'reset':

                            if(empty($_POST)) {
                                redirect('gs1-links');
                            }

                            /* Team checks */
                            if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.gs1_links')) {
                                Alerts::add_info(l('global.info_message.team_no_access'));
                                redirect('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics');
                            }

                            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

                            if(!\Altum\Csrf::check()) {
                                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                                redirect('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics');
                            }

                            $datetime = \Altum\Date::get_start_end_dates_new($_POST['start_date'], $_POST['end_date']);

                            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                                /* Clear statistics data */
                                database()->query("DELETE FROM `track_gs1_links` WHERE `gs1_link_id` = {$this->gs1_link->gs1_link_id} AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')");

                                /* Set a nice success message */
                                Alerts::add_success(l('global.success_message.update2'));

                                redirect('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics');

                            }

                            redirect('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics');

                            break;
                    }
                }

                $type = isset($_GET['type']) && in_array($_GET['type'], ['overview', 'entries', 'referrer_host', 'referrer_path', 'continent_code', 'country', 'city_name', 'os', 'browser', 'device', 'language', 'utm_source', 'utm_medium', 'utm_campaign']) ? input_clean($_GET['type']) : 'overview';

                $datetime = \Altum\Date::get_start_end_dates_new();

                /* Get data based on what statistics are needed */
                switch($type) {
                    case 'overview':

                        /* Get the required statistics */
                        $pageviews = [];
                        $pageviews_chart = [];

                        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

                        $pageviews_result = database()->query("
                            SELECT
                                COUNT(`id`) AS `pageviews`,
                                SUM(`is_unique`) AS `visitors`,
                                DATE_FORMAT({$convert_tz_sql}, '{$datetime['query_date_format']}') AS `formatted_date`
                            FROM
                                 `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                AND ({$convert_tz_sql} BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                            GROUP BY
                                `formatted_date`
                            ORDER BY
                                `formatted_date`
                        ");

                        /* Generate the raw chart data and save pageviews for later usage */
                        while($row = $pageviews_result->fetch_object()) {
                            $pageviews[] = $row;

                            $row->formatted_date = $datetime['process']($row->formatted_date, true);

                            $pageviews_chart[$row->formatted_date] = [
                                'pageviews' => $row->pageviews,
                                'visitors' => $row->visitors
                            ];
                        }

                        $pageviews_chart = get_chart_data($pageviews_chart);

                        $limit = $this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page;
                        $result = database()->query("
                            SELECT
                                *
                            FROM
                                `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                            ORDER BY
                                `datetime` DESC
                            LIMIT {$limit}
                        ");

                        break;

                    case 'entries':

                        /* Prepare the filtering system */
                        $filters = (new \Altum\Filters([], [], ['datetime']));
                        $filters->set_default_order_by('id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
                        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

                        /* Prepare the paginator */
                        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `track_gs1_links` WHERE `gs1_link_id` = {$this->gs1_link->gs1_link_id} AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}') {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
                        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics?type=' . $type . '&start_date=' . $datetime['start_date'] . '&end_date=' . $datetime['end_date'] . $filters->get_get() . '&page=%d')));

                        $result = database()->query("
                            SELECT
                                *
                            FROM
                                `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                            {$filters->get_sql_where()}
                            {$filters->get_sql_order_by()}
                            {$paginator->get_sql_limit()}
                        ");

                        break;

                    default:
                        /* Handle other statistics types similar to regular links */
                        $columns = [
                            'referrer_host' => 'referrer_host',
                            'referrer_path' => 'referrer_path',
                            'continent_code' => 'continent_code',
                            'country' => 'country_code',
                            'city_name' => 'city_name',
                            'os' => 'os_name',
                            'browser' => 'browser_name',
                            'device' => 'device_type',
                            'language' => 'browser_language'
                        ];

                        if(array_key_exists($type, $columns)) {
                            $result = database()->query("
                                SELECT
                                    `{$columns[$type]}`,
                                    COUNT(*) AS `total`
                                FROM
                                     `track_gs1_links`
                                WHERE
                                    `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                    AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                                GROUP BY
                                    `{$columns[$type]}`
                                ORDER BY
                                    `total` DESC
                                LIMIT 250
                            ");
                        }

                        break;
                }

                /* Process statistics data similar to regular links */
                switch($type) {
                    case 'overview':

                        $statistics_keys = [
                            'continent_code',
                            'country_code',
                            'city_name',
                            'referrer_host',
                            'device_type',
                            'os_name',
                            'browser_name',
                            'browser_language'
                        ];

                        $latest = [];
                        $statistics = [];
                        foreach($statistics_keys as $key) {
                            $statistics[$key] = [];
                            $statistics[$key . '_total_sum'] = 0;
                        }

                        $has_data = $result->num_rows;

                        /* Start processing the rows from the database */
                        while($row = $result->fetch_object()) {
                            foreach($statistics_keys as $key) {
                                $statistics[$key][$row->{$key}] = isset($statistics[$key][$row->{$key}]) ? $statistics[$key][$row->{$key}] + 1 : 1;
                                $statistics[$key . '_total_sum']++;
                            }

                            $latest[] = $row;
                        }

                        foreach($statistics_keys as $key) {
                            arsort($statistics[$key]);
                        }

                        /* Prepare the statistics method View */
                        $data = [
                            'statistics' => $statistics,
                            'gs1_link' => $this->gs1_link,
                            'method' => $method,
                            'datetime' => $datetime,
                            'latest' => $latest,
                            'pageviews' => $pageviews,
                            'pageviews_chart' => $pageviews_chart,
                            'url' => 'gs1-link/' . $this->gs1_link->gs1_link_id,
                        ];

                        break;

                    case 'entries':

                        /* Store all the results from the database */
                        $statistics = [];

                        while($row = $result->fetch_object()) {
                            $statistics[] = $row;
                        }

                        /* Prepare the pagination view */
                        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

                        /* Prepare the statistics method View */
                        $data = [
                            'type' => $type,
                            'rows' => $statistics,
                            'gs1_link' => $this->gs1_link,
                            'method' => $method,
                            'datetime' => $datetime,
                            'pagination' => $pagination,
                            'filters' => $filters,
                            'url' => 'gs1-link/' . $this->gs1_link->gs1_link_id,
                        ];

                        $has_data = count($statistics);

                        break;

                    default:

                        /* Store all the results from the database */
                        $statistics = [];
                        $statistics_total_sum = 0;

                        if(isset($result)) {
                            while($row = $result->fetch_object()) {
                                $statistics[] = $row;
                                $statistics_total_sum += $row->total;
                            }
                        }

                        /* Prepare the statistics method View */
                        $data = [
                            'rows' => $statistics,
                            'total_sum' => $statistics_total_sum,
                            'gs1_link' => $this->gs1_link,
                            'method' => $method,
                            'datetime' => $datetime,
                            'type' => $type,
                            'url' => 'gs1-link/' . $this->gs1_link->gs1_link_id,
                        ];

                        $has_data = count($statistics);

                        break;
                }

                /* Export handler */
                process_export_csv($statistics, 'basic');
                process_export_json($statistics, 'basic');

                $view = new \Altum\View('gs1-link/statistics/statistics_' . $type, (array) $this);
                $this->add_view_content('statistics', $view->run($data));

                /* Prepare variables for the view */
                $data = [
                    'gs1_link' => $this->gs1_link,
                    'method' => $method,
                    'type' => $type,
                    'datetime' => $datetime,
                    'has_data' => $has_data,
                ];

                break;

        }

        /* Delete Modal */
        $view = new \Altum\View('gs1-links/gs1_link_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the method View */
        $view = new \Altum\View('gs1-link/' . $method, (array) $this);
        $this->add_view_content('method', $view->run($data));

        /* Prepare the view */
        $data = [
            'gs1_link' => $this->gs1_link,
            'method' => $method,
        ];

        $view = new \Altum\View('gs1-link/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

}
