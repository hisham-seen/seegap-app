<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Models\Domain;

defined('SEEGAP') || die();

class Dashboard extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['is_enabled', 'type'], ['url', 'location_url'], ['link_id', 'last_datetime', 'datetime', 'clicks', 'url']));
        $filters->set_default_order_by($this->user->preferences->links_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = \SeeGap\Cache::cache_function_result('links_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('links', 'count(*)');
        });
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('links?' . $filters->get_get() . '&page=%d')));

        /* Get domains */
        $domains = (new Domain())->get_available_domains_by_user($this->user);

        /* Get the links list for the project */
        $links_result = database()->query("
            SELECT 
                *
            FROM 
                `links`
            WHERE 
                `user_id` = {$this->user->user_id}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the links */
        $links = [];

        while($row = $links_result->fetch_object()) {
            $row->full_url = $row->domain_id && isset($domains[$row->domain_id]) ? $domains[$row->domain_id]->scheme . $domains[$row->domain_id]->host . '/' . ($domains[$row->domain_id]->link_id == $row->link_id ? null : $row->url) : SITE_URL . $row->url;

            /* Static links need the / for proper asset pathing */
            if($row->type == 'static') {
                $row->full_url .= '/';
            }

            $row->settings = json_decode($row->settings);

            $links[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get statistics */
        if(count($links)) {
            $links_chart = [];
            $start_date_query = (new \DateTime())->modify('-' . (settings()->main->chart_days ?? 30) . ' day')->format('Y-m-d');
            $end_date_query = (new \DateTime())->modify('+1 day')->format('Y-m-d');

            $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

            $track_links_result_query = "
                SELECT
                    COUNT(`id`) AS `pageviews`,
                    SUM(`is_unique`) AS `visitors`,
                    DATE_FORMAT({$convert_tz_sql}, '%Y-%m-%d') AS `formatted_date`
                FROM
                    `track_links`
                WHERE   
                    `user_id` = {$this->user->user_id} 
                    AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                GROUP BY
                    `formatted_date`
                
                UNION ALL
                
                SELECT
                    COUNT(`id`) AS `pageviews`,
                    SUM(`is_unique`) AS `visitors`,
                    DATE_FORMAT({$convert_tz_sql}, '%Y-%m-%d') AS `formatted_date`
                FROM
                    `track_gs1_links`
                WHERE   
                    `user_id` = {$this->user->user_id} 
                    AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                GROUP BY
                    `formatted_date`
                ORDER BY
                    `formatted_date`
            ";

            $links_chart = \SeeGap\Cache::cache_function_result('track_links?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() use ($track_links_result_query) {
                $links_chart = [];

                $track_links_result = database()->query($track_links_result_query);

                /* Generate the raw chart data and save logs for later usage */
                while($row = $track_links_result->fetch_object()) {
                    $label = \SeeGap\Date::get($row->formatted_date, 5, \SeeGap\Date::$default_timezone);

                    $links_chart[$label] = [
                        'pageviews' => $row->pageviews,
                        'visitors' => $row->visitors
                    ];
                }

                return $links_chart;
            }, 60 * 60 * settings()->main->chart_cache ?? 12);

            $links_chart = get_chart_data($links_chart);
        }

        /* Some statistics for the widgets */
        if(settings()->links->shortener_is_enabled) {
            $link_links_total = \SeeGap\Cache::cache_function_result('link_links_total?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'link')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->files_is_enabled) {
            $file_links_total = \SeeGap\Cache::cache_function_result('file_links_total?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'file')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->microsites_is_enabled) {
            $microsite_links_total = \SeeGap\Cache::cache_function_result('microsite_links_total?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'microsite')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->events_is_enabled) {
            $event_links_total = \SeeGap\Cache::cache_function_result('event_links_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'event')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->static_is_enabled) {
            $static_links_total = \SeeGap\Cache::cache_function_result('static_links_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'static')->getValue('links', 'count(*)');
            });
        }

        /* GS1 Links statistics */
        $gs1_links_total = null;
        if(settings()->gs1_links->gs1_links_is_enabled ?? false) {
            $gs1_links_total = \SeeGap\Cache::cache_function_result('gs1_links_total?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() {
                return db()->where('user_id', $this->user->user_id)->getValue('gs1_links', 'count(*)');
            });
        }

        /* QR Codes statistics */
        $qr_codes_total = null;
        if(settings()->codes->qr_codes_is_enabled ?? false) {
            $qr_codes_total = \SeeGap\Cache::cache_function_result('qr_codes_total?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() {
                return db()->where('user_id', $this->user->user_id)->getValue('qr_codes', 'count(*)');
            });
        }

        /* Data submissions statistics */
        $data_submissions_total = null;
        if(settings()->links->microsites_is_enabled) {
            $data_submissions_total = \SeeGap\Cache::cache_function_result('data_submissions_total?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() {
                return db()->where('user_id', $this->user->user_id)->getValue('data', 'count(*)');
            });
        }

        /* Geographic and traffic analytics */
        $analytics_data = [];
        // Always try to get analytics data, not just when links exist
        if(true) {
            $start_date_query = (new \DateTime())->modify('-' . (settings()->main->chart_days ?? 30) . ' day')->format('Y-m-d');
            $end_date_query = (new \DateTime())->modify('+1 day')->format('Y-m-d');
            $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

            /* Top countries */
            $countries_query = "
                SELECT
                    `country_code`,
                    SUM(`count`) AS `count`
                FROM (
                    SELECT
                        `country_code`,
                        COUNT(*) AS `count`
                    FROM
                        `track_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `country_code` IS NOT NULL
                        AND `country_code` != ''
                    GROUP BY
                        `country_code`
                    
                    UNION ALL
                    
                    SELECT
                        `country_code`,
                        COUNT(*) AS `count`
                    FROM
                        `track_gs1_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `country_code` IS NOT NULL
                        AND `country_code` != ''
                    GROUP BY
                        `country_code`
                ) AS combined_data
                GROUP BY
                    `country_code`
                ORDER BY
                    `count` DESC
                LIMIT 10
            ";

            $analytics_data['countries'] = \SeeGap\Cache::cache_function_result('dashboard_countries?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() use ($countries_query) {
                $countries = [];
                $countries_result = database()->query($countries_query);
                while($row = $countries_result->fetch_object()) {
                    $countries[] = $row;
                }
                return $countries;
            }, 60 * 60);

            /* Top cities */
            $cities_query = "
                SELECT
                    `city_name`,
                    `country_code`,
                    SUM(`count`) AS `count`
                FROM (
                    SELECT
                        `city_name`,
                        `country_code`,
                        COUNT(*) AS `count`
                    FROM
                        `track_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `city_name` IS NOT NULL
                        AND `city_name` != ''
                    GROUP BY
                        `city_name`, `country_code`
                    
                    UNION ALL
                    
                    SELECT
                        `city_name`,
                        `country_code`,
                        COUNT(*) AS `count`
                    FROM
                        `track_gs1_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `city_name` IS NOT NULL
                        AND `city_name` != ''
                    GROUP BY
                        `city_name`, `country_code`
                ) AS combined_data
                GROUP BY
                    `city_name`, `country_code`
                ORDER BY
                    `count` DESC
                LIMIT 10
            ";

            $analytics_data['cities'] = \SeeGap\Cache::cache_function_result('dashboard_cities?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() use ($cities_query) {
                $cities = [];
                $cities_result = database()->query($cities_query);
                while($row = $cities_result->fetch_object()) {
                    $cities[] = $row;
                }
                return $cities;
            }, 60 * 60);

            /* Top referrers */
            $referrers_query = "
                SELECT
                    `referrer_host`,
                    SUM(`count`) AS `count`
                FROM (
                    SELECT
                        `referrer_host`,
                        COUNT(*) AS `count`
                    FROM
                        `track_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `referrer_host` IS NOT NULL
                        AND `referrer_host` != ''
                    GROUP BY
                        `referrer_host`
                    
                    UNION ALL
                    
                    SELECT
                        `referrer_host`,
                        COUNT(*) AS `count`
                    FROM
                        `track_gs1_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `referrer_host` IS NOT NULL
                        AND `referrer_host` != ''
                    GROUP BY
                        `referrer_host`
                ) AS combined_data
                GROUP BY
                    `referrer_host`
                ORDER BY
                    `count` DESC
                LIMIT 10
            ";

            $analytics_data['referrers'] = \SeeGap\Cache::cache_function_result('dashboard_referrers?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() use ($referrers_query) {
                $referrers = [];
                $referrers_result = database()->query($referrers_query);
                while($row = $referrers_result->fetch_object()) {
                    $referrers[] = $row;
                }
                return $referrers;
            }, 60 * 60);

            /* UTM sources */
            $utm_sources_query = "
                SELECT
                    `utm_source`,
                    SUM(`count`) AS `count`
                FROM (
                    SELECT
                        `utm_source`,
                        COUNT(*) AS `count`
                    FROM
                        `track_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `utm_source` IS NOT NULL
                        AND `utm_source` != ''
                    GROUP BY
                        `utm_source`
                    
                    UNION ALL
                    
                    SELECT
                        `utm_source`,
                        COUNT(*) AS `count`
                    FROM
                        `track_gs1_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `utm_source` IS NOT NULL
                        AND `utm_source` != ''
                    GROUP BY
                        `utm_source`
                ) AS combined_data
                GROUP BY
                    `utm_source`
                ORDER BY
                    `count` DESC
                LIMIT 10
            ";

            $analytics_data['utm_sources'] = \SeeGap\Cache::cache_function_result('dashboard_utm_sources?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() use ($utm_sources_query) {
                $utm_sources = [];
                $utm_sources_result = database()->query($utm_sources_query);
                while($row = $utm_sources_result->fetch_object()) {
                    $utm_sources[] = $row;
                }
                return $utm_sources;
            }, 60 * 60);

            /* UTM campaigns */
            $utm_campaigns_query = "
                SELECT
                    `utm_campaign`,
                    SUM(`count`) AS `count`
                FROM (
                    SELECT
                        `utm_campaign`,
                        COUNT(*) AS `count`
                    FROM
                        `track_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `utm_campaign` IS NOT NULL
                        AND `utm_campaign` != ''
                    GROUP BY
                        `utm_campaign`
                    
                    UNION ALL
                    
                    SELECT
                        `utm_campaign`,
                        COUNT(*) AS `count`
                    FROM
                        `track_gs1_links`
                    WHERE   
                        `user_id` = {$this->user->user_id} 
                        AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                        AND `utm_campaign` IS NOT NULL
                        AND `utm_campaign` != ''
                    GROUP BY
                        `utm_campaign`
                ) AS combined_data
                GROUP BY
                    `utm_campaign`
                ORDER BY
                    `count` DESC
                LIMIT 10
            ";

            $analytics_data['utm_campaigns'] = \SeeGap\Cache::cache_function_result('dashboard_utm_campaigns?user_id=' . $this->user->user_id, 'user_id=' . $this->user->user_id, function() use ($utm_campaigns_query) {
                $utm_campaigns = [];
                $utm_campaigns_result = database()->query($utm_campaigns_query);
                while($row = $utm_campaigns_result->fetch_object()) {
                    $utm_campaigns[] = $row;
                }
                return $utm_campaigns;
            }, 60 * 60);
        }

        /* Delete Modal */
        $view = new \SeeGap\View('links/link_delete_modal', (array) $this);
        \SeeGap\Event::add_content($view->run(), 'modals');

        /* Create Link Modal */
        $domains = (new Domain())->get_available_domains_by_user($this->user);
        $data = [
            'domains' => $domains
        ];

        $view = new \SeeGap\View('links/create_link_modals', (array) $this);
        \SeeGap\Event::add_content($view->run($data), 'modals');

        /* Existing projects */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Prepare the Links View */
        $data = [
            'links'             => $links,
            'pagination'        => $pagination,
            'filters'           => $filters,
            'projects'          => $projects,
            'links_types'       => require APP_PATH . 'includes/links_types.php',
        ];
        $view = new \SeeGap\View('links/links_content', (array) $this);
        $this->add_view_content('links_content', $view->run($data));

        /* Prepare the view */
        $data = [
            'links_chart'               => $links_chart ?? null,

            /* Widgets stats */
            'event_links_total'         => $event_links_total ?? null,
            'static_links_total'        => $static_links_total ?? null,
            'link_links_total'          => $link_links_total ?? null,
            'file_links_total'          => $file_links_total ?? null,
            'microsite_links_total'     => $microsite_links_total ?? null,
            'gs1_links_total'           => $gs1_links_total ?? null,
            'qr_codes_total'            => $qr_codes_total ?? null,
            'data_submissions_total'    => $data_submissions_total ?? null,

            /* Analytics data */
            'analytics_data'            => $analytics_data ?? [],
        ];

        $view = new \SeeGap\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
