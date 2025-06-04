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

class Gs1Links extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        /* Check if GS1 links feature is enabled */
        if(!settings()->gs1_links->gs1_links_is_enabled) {
            redirect('dashboard');
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('read.gs1_links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['is_enabled', 'project_id', 'domain_id'], ['gtin', 'target_url', 'title'], ['gs1_link_id', 'last_datetime', 'datetime', 'clicks', 'gtin']));
        $filters->set_default_order_by('gs1_link_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `gs1_links` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('gs1-links?' . $filters->get_get() . '&page=%d')));

        /* Get domains */
        $domains = (new Domain())->get_available_domains_by_user($this->user);

        /* Get the gs1 links list for the user */
        $gs1_links_result = database()->query("
            SELECT 
                *
            FROM 
                `gs1_links`
            WHERE 
                `user_id` = {$this->user->user_id} 
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the gs1 links */
        $gs1_links = [];

        while($row = $gs1_links_result->fetch_object()) {
            $row->full_url = $row->domain_id && isset($domains[$row->domain_id]) ? $domains[$row->domain_id]->scheme . $domains[$row->domain_id]->host . '/' . $row->gtin : SITE_URL . $row->gtin;
            $row->settings = json_decode($row->settings ?? '{}');
            $gs1_links[] = $row;
        }

        /* Export handler */
        process_export_csv($gs1_links, 'include', ['gs1_link_id', 'user_id', 'project_id', 'domain_id', 'gtin', 'target_url', 'title', 'description', 'clicks', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('gs1_links.title')));
        process_export_json($gs1_links, 'include', ['gs1_link_id', 'user_id', 'project_id', 'domain_id', 'gtin', 'target_url', 'title', 'description', 'settings', 'clicks', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('gs1_links.title')));

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \SeeGap\View('gs1-links/gs1_link_delete_modal', (array) $this);
        \SeeGap\Event::add_content($view->run(), 'modals');

        /* Existing projects */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Set a custom title */
        Title::set(l('gs1_links.title'));

        /* Prepare the GS1 Links Content View */
        $data = [
            'gs1_links'         => $gs1_links,
            'pagination'        => $pagination,
            'filters'           => $filters,
            'projects'          => $projects,
            'domains'           => $domains,
        ];

        $view = new \SeeGap\View('gs1-links/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \SeeGap\Authentication::guard();

        //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('gs1-links');
        }

        if(empty($_POST['selected'])) {
            redirect('gs1-links');
        }

        if(!isset($_POST['type'])) {
            redirect('gs1-links');
        }

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.gs1_links')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('gs1-links');
                    }

                    foreach($_POST['selected'] as $gs1_link_id) {
                        if($gs1_link = db()->where('gs1_link_id', $gs1_link_id)->where('user_id', $this->user->user_id)->getOne('gs1_links', ['gs1_link_id'])) {
                            /* Delete the resource */
                            (new \SeeGap\Models\Gs1Link())->delete($gs1_link->gs1_link_id);
                        }
                    }

                    break;

            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('gs1-links');
    }

    public function reset() {
        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.gs1_links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('gs1-links');
        }

        if(empty($_POST)) {
            redirect('gs1-links');
        }

        $gs1_link_id = (int) query_clean($_POST['gs1_link_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('gs1-links');
        }

        /* Make sure the gs1 link id is created by the logged in user */
        if(!$gs1_link = db()->where('gs1_link_id', $gs1_link_id)->where('user_id', $this->user->user_id)->getOne('gs1_links', ['gs1_link_id'])) {
            redirect('gs1-links');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Reset data */
            db()->where('gs1_link_id', $gs1_link_id)->update('gs1_links', [
                'clicks' => 0,
            ]);

            /* Remove data */
            db()->where('gs1_link_id', $gs1_link_id)->delete('track_gs1_links');

            /* Clear the cache */
            cache()->deleteItem('gs1_link?gs1_link_id=' . $gs1_link->gs1_link_id);
            cache()->deleteItemsByTag('gs1_link_id=' . $gs1_link->gs1_link_id);
            cache()->deleteItem('gs1_links?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.update2'));

            redirect('gs1-links');

        }

        redirect('gs1-links');
    }

}
