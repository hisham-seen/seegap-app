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
use Altum\Date;
use Altum\Models\User;
use Altum\Response;
use Altum\Traits\Paramsable;

class AdminGs1Links extends \Altum\Controllers\Controller {
    use Paramsable;

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'user_id'], ['gtin', 'target_url', 'title'], ['last_datetime', 'datetime', 'gtin', 'clicks']));
        $filters->set_default_order_by('gs1_link_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `gs1_links` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/gs1-links?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $gs1_links = [];
        $gs1_links_result = database()->query("
            SELECT
                `gs1_links`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `gs1_links`
            LEFT JOIN
                `users` ON `gs1_links`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                {$paginator->get_sql_limit()}
        ");
        while($row = $gs1_links_result->fetch_object()) {
            $gs1_links[] = $row;
        }

        /* Export handler */
        process_export_csv($gs1_links, 'include', ['gs1_link_id', 'user_id', 'gtin', 'target_url', 'title', 'clicks', 'is_enabled', 'datetime'], sprintf(l('admin_gs1_links.title')));
        process_export_json($gs1_links, 'include', ['gs1_link_id', 'user_id', 'gtin', 'target_url', 'title', 'clicks', 'is_enabled', 'datetime'], sprintf(l('admin_gs1_links.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\View('admin/gs1-links/gs1_link_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'gs1_links' => $gs1_links,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/gs1-links/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.gs1_links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('admin/gs1-links');
        }

        if(empty($_POST)) {
            redirect('admin/gs1-links');
        }

        $gs1_link_id = (int) $_POST['gs1_link_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$gs1_link = database()->query("SELECT * FROM `gs1_links` WHERE `gs1_link_id` = {$gs1_link_id}")->fetch_object()) {
            redirect('admin/gs1-links');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the gs1_link */
            database()->query("DELETE FROM `gs1_links` WHERE `gs1_link_id` = {$gs1_link->gs1_link_id}");

            /* Clear cache */
            cache()->deleteItemsByTag('gs1_link_id=' . $gs1_link_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $gs1_link->gtin . '</strong>'));

        }

        redirect('admin/gs1-links');
    }

}
