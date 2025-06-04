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

defined('SEEGAP') || die();

class AdminDomains extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['is_enabled', 'user_id', 'type'], ['host'], ['domain_id', 'last_datetime', 'datetime', 'host']));
        $filters->set_default_order_by($this->user->preferences->domains_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `domains` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/domains?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $domains = [];
        $domains_result = database()->query("
            SELECT
                `domains`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `domains`
            LEFT JOIN
                `users` ON `domains`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('domains')}
                {$filters->get_sql_order_by('domains')}
            
            {$paginator->get_sql_limit()}
        ");
        while($row = $domains_result->fetch_object()) {
            $domains[] = $row;
        }

        /* Export handler */
        process_export_csv($domains, 'include', ['domain_id', 'user_id', 'scheme', 'host', 'custom_index_url', 'custom_not_found_url', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_domains.title')));
        process_export_json($domains, 'include', ['domain_id', 'user_id', 'scheme', 'host', 'custom_index_url', 'custom_not_found_url', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_domains.title')));

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'domains' => $domains,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \SeeGap\View('admin/domains/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/domains');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/domains');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/domains');
        }

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $domain_id) {
                        (new Domain())->delete($domain_id);
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/domains');
    }

    public function delete() {

        $domain_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\SeeGap\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$domain = db()->where('domain_id', $domain_id)->getOne('domains')) {
            redirect('admin/domains');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new Domain())->delete($domain->domain_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $domain->host . '</strong>'));

        }

        redirect('admin/domains');
    }

}
