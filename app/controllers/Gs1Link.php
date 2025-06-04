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
use SeeGap\Title;

defined('SEEGAP') || die();

class Gs1Link extends Controller {
    public $gs1_link;

    public function index() {

        \SeeGap\Authentication::guard();

        $gs1_link_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        $method = isset($this->params[1]) && in_array($this->params[1], ['settings', 'statistics']) ? $this->params[1] : 'settings';

        /* Make sure the GS1 link exists and is accessible to the user */
        if(!$this->gs1_link = db()->where('gs1_link_id', $gs1_link_id)->where('user_id', $this->user->user_id)->getOne('gs1_links')) {
            redirect('dashboard');
        }

        $this->gs1_link->settings = json_decode($this->gs1_link->settings ?? '');
        $this->gs1_link->pixels_ids = json_decode($this->gs1_link->pixels_ids ?? '[]');

        /* Get the current domain if needed */
        $this->gs1_link->domain = $this->gs1_link->domain_id ? (new Domain())->get_domain_by_domain_id($this->gs1_link->domain_id) : null;

        /* Determine the actual full url */
        $this->gs1_link->full_url = $this->gs1_link->domain ? $this->gs1_link->domain->url . '/' . $this->gs1_link->gtin : SITE_URL . $this->gs1_link->gtin;

        /* Set a custom title */
        Title::set(sprintf(l('gs1_link.title'), $this->gs1_link->gtin));

        /* Handle code for different parts of the page */
        switch($method) {
            case 'settings':

                /* Get the available domains to use */
                $domains = (new Domain())->get_available_domains_by_user($this->user);

                /* Existing projects */
                $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);

                /* Existing splash pages */
                $splash_pages = (new \SeeGap\Models\SplashPages())->get_splash_pages_by_user_id($this->user->user_id);

                /* Existing pixels */
                $pixels = (new \SeeGap\Models\Pixel())->get_pixels($this->user->user_id);

                /* Prepare variables for the view */
                $data = [
                    'gs1_link'          => $this->gs1_link,
                    'method'            => $method,
                    'domains'           => $domains,
                    'projects'          => $projects,
                    'splash_pages'      => $splash_pages,
                    'pixels'            => $pixels,
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
                            if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.gs1_links')) {
                                Alerts::add_info(l('global.info_message.team_no_access'));
                                redirect('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics');
                            }

                            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

                            if(!\SeeGap\Csrf::check()) {
                                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                                redirect('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics');
                            }

                            $datetime = \SeeGap\Date::get_start_end_dates_new($_POST['start_date'], $_POST['end_date']);

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

                $datetime = \SeeGap\Date::get_start_end_dates_new();

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
                        $filters = (new \SeeGap\Filters([], [], ['datetime']));
                        $filters->set_default_order_by('id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
                        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

                        /* Prepare the paginator */
                        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `track_gs1_links` WHERE `gs1_link_id` = {$this->gs1_link->gs1_link_id} AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}') {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
                        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('gs1-link/' . $this->gs1_link->gs1_link_id . '/statistics?type=' . $type . '&start_date=' . $datetime['start_date'] . '&end_date=' . $datetime['end_date'] . $filters->get_get() . '&page=%d')));

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

                    case 'referrer_host':
                    case 'continent_code':
                    case 'country':
                    case 'os':
                    case 'browser':
                    case 'device':
                    case 'language':

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

                        break;

                    case 'referrer_path':

                        $referrer_host = input_clean($_GET['referrer_host']);

                        $result = database()->query("
                            SELECT
                                `referrer_path`,
                                COUNT(*) AS `total`
                            FROM
                                 `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                AND `referrer_host` = '{$referrer_host}'
                                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                            GROUP BY
                                `referrer_path`
                            ORDER BY
                                `total` DESC
                            LIMIT 250
                        ");

                        break;

                    case 'city_name':

                        $country_code = isset($_GET['country_code']) ? input_clean($_GET['country_code']) : null;

                        $result = database()->query("
                            SELECT
                                `city_name`,
                                `country_code`,
                                COUNT(*) AS `total`
                            FROM
                                 `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                " . ($country_code ? "AND `country_code` = '{$country_code}'" : null) . "
                                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                            GROUP BY
                                `country_code`,
                                `city_name`
                            ORDER BY
                                `total` DESC
                            LIMIT 250
                        ");

                        break;

                    case 'utm_source':

                        $result = database()->query("
                            SELECT
                                `utm_source`,
                                COUNT(*) AS `total`
                            FROM
                                 `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                                AND `utm_source` IS NOT NULL
                            GROUP BY
                                `utm_source`
                            ORDER BY
                                `total` DESC
                            LIMIT 250
                        ");

                        break;

                    case 'utm_medium':

                        $utm_source = input_clean($_GET['utm_source']);

                        $result = database()->query("
                            SELECT
                                `utm_medium`,
                                COUNT(*) AS `total`
                            FROM
                                 `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                AND `utm_source` = '{$utm_source}'
                                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                            GROUP BY
                                `utm_medium`
                            ORDER BY
                                `total` DESC
                            LIMIT 250
                        ");

                        break;

                    case 'utm_campaign':

                        $utm_source = input_clean($_GET['utm_source']);
                        $utm_medium = input_clean($_GET['utm_medium']);

                        $result = database()->query("
                            SELECT
                                `utm_campaign`,
                                COUNT(*) AS `total`
                            FROM
                                 `track_gs1_links`
                            WHERE
                                `gs1_link_id` = {$this->gs1_link->gs1_link_id}
                                AND `utm_source` = '{$utm_source}'
                                AND `utm_medium` = '{$utm_medium}'
                                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                            GROUP BY
                                `utm_campaign`
                            ORDER BY
                                `total` DESC
                            LIMIT 250
                        ");

                        break;
                }

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
                        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

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

                    case 'referrer_host':
                    case 'continent_code':
                    case 'country':
                    case 'city_name':
                    case 'os':
                    case 'browser':
                    case 'device':
                    case 'language':
                    case 'referrer_path':
                    case 'utm_source':
                    case 'utm_medium':
                    case 'utm_campaign':

                        /* Store all the results from the database */
                        $statistics = [];
                        $statistics_total_sum = 0;

                        while($row = $result->fetch_object()) {
                            $statistics[] = $row;

                            $statistics_total_sum += $row->total;
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

                            'referrer_host' => $referrer_host ?? null,
                            'country_code' => $country_code ?? null,
                            'utm_source' => $utm_source ?? null,
                            'utm_medium' => $utm_medium ?? null,
                        ];

                        $has_data = count($statistics);

                        break;
                }

                /* Export handler */
                process_export_csv($statistics, 'basic');
                process_export_json($statistics, 'basic');

                $view = new \SeeGap\View('gs1-link/statistics/statistics_' . $type, (array) $this);
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
        $view = new \SeeGap\View('gs1-links/gs1_link_delete_modal', (array) $this);
        \SeeGap\Event::add_content($view->run(), 'modals');

        /* Prepare the method View */
        $view = new \SeeGap\View('gs1-link/' . $method, (array) $this);
        $this->add_view_content('method', $view->run($data));

        /* Prepare the view */
        $data = [
            'gs1_link' => $this->gs1_link,
            'method' => $method,
        ];

        $view = new \SeeGap\View('gs1-link/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

}
