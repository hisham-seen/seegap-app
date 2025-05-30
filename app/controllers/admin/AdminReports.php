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

defined('ALTUMCODE') || die();

class AdminReports extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name'], ['report_id', 'last_datetime', 'name', 'datetime']));
        $filters->set_default_order_by('report_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Check if reports table exists */
        $total_rows = 0;
        $table_exists_result = database()->query("SHOW TABLES LIKE 'reports'");
        if($table_exists_result && $table_exists_result->num_rows > 0) {
            /* Prepare the paginator */
            $total_rows_result = database()->query("SELECT COUNT(*) AS `total` FROM `reports` WHERE 1 = 1 {$filters->get_sql_where()}");
            $total_rows = $total_rows_result ? ($total_rows_result->fetch_object()->total ?? 0) : 0;
        }
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/reports?' . $filters->get_get() . '&page=%d')));

        /* Get the reports list */
        $reports = [];
        if($table_exists_result && $table_exists_result->num_rows > 0) {
            $reports_result = database()->query("
                SELECT 
                    r.*,
                    u.name as user_name,
                    u.email as user_email
                FROM 
                    `reports` r
                LEFT JOIN 
                    `users` u ON r.user_id = u.user_id
                WHERE 1 = 1 {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}
            ");
            if($reports_result) {
                while($row = $reports_result->fetch_object()) {
                    $row->assigned_user_ids = json_decode($row->assigned_user_ids);
                    $reports[] = $row;
                }
            }
        }

        /* Export handler */
        process_export_csv($reports, 'include', ['report_id', 'user_id', 'name', 'description', 'datetime'], sprintf(l('admin_reports.title')));
        process_export_json($reports, 'include', ['report_id', 'user_id', 'name', 'description', 'datetime'], sprintf(l('admin_reports.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get all users for assignment dropdown */
        $users = [];
        $users_result = database()->query("SELECT `user_id`, `name`, `email` FROM `users` WHERE `status` = 1 ORDER BY `name` ASC");
        while($row = $users_result->fetch_object()) {
            $users[] = $row;
        }

        /* Prepare the view */
        $data = [
            'reports' => $reports,
            'total_reports' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'users' => $users,
        ];

        $view = new \Altum\View('admin/reports/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \Altum\Authentication::guard();

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/reports');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/reports');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/reports');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $report_id) {
                        if($report = db()->where('report_id', $report_id)->getOne('reports', ['report_id'])) {
                            db()->where('report_id', $report_id)->delete('reports');
                        }
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/reports');
    }

    public function create() {

        \Altum\Authentication::guard();

        if(empty($_POST)) {
            redirect('admin/reports');
        }

        /* Check for any errors */
        $required_fields = ['name', 'superset_domain', 'superset_embed_code'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Alerts::add_field_error($field, l('global.error_message.empty_field'));
            }
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        /* If there are no errors, continue */
        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['description'] = input_clean($_POST['description']);
            $_POST['superset_domain'] = input_clean($_POST['superset_domain']);
            $_POST['superset_embed_code'] = input_clean($_POST['superset_embed_code']);
            $_POST['assigned_user_ids'] = isset($_POST['assigned_user_ids']) ? json_encode(array_map('intval', $_POST['assigned_user_ids'])) : json_encode([]);

            /* Database query */
            db()->insert('reports', [
                'user_id' => $this->user->user_id,
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'superset_domain' => $_POST['superset_domain'],
                'superset_embed_code' => $_POST['superset_embed_code'],
                'assigned_user_ids' => $_POST['assigned_user_ids'],
                'datetime' => \Altum\Date::$date,
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

            redirect('admin/reports');
        }

        redirect('admin/reports');
    }

    public function update() {

        \Altum\Authentication::guard();

        $report_id = isset($this->params[0]) ? (int) $this->params[0] : (isset($_POST['report_id']) ? (int) $_POST['report_id'] : null);

        if(!$report = db()->where('report_id', $report_id)->getOne('reports')) {
            redirect('admin/reports');
        }

        if(empty($_POST)) {
            redirect('admin/reports');
        }

        /* Check for any errors */
        $required_fields = ['name', 'superset_domain', 'superset_embed_code'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Alerts::add_field_error($field, l('global.error_message.empty_field'));
            }
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        /* If there are no errors, continue */
        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['description'] = input_clean($_POST['description']);
            $_POST['superset_domain'] = input_clean($_POST['superset_domain']);
            $_POST['superset_embed_code'] = input_clean($_POST['superset_embed_code']);
            $_POST['assigned_user_ids'] = isset($_POST['assigned_user_ids']) ? json_encode(array_map('intval', $_POST['assigned_user_ids'])) : json_encode([]);
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            /* Database query */
            db()->where('report_id', $report->report_id)->update('reports', [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'superset_domain' => $_POST['superset_domain'],
                'superset_embed_code' => $_POST['superset_embed_code'],
                'assigned_user_ids' => $_POST['assigned_user_ids'],
                'is_enabled' => $_POST['is_enabled'],
                'last_datetime' => \Altum\Date::$date,
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

            redirect('admin/reports');
        }

        redirect('admin/reports');
    }

    public function delete() {

        \Altum\Authentication::guard();

        if(empty($_POST)) {
            redirect('admin/reports');
        }

        $report_id = (int) $_POST['report_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$report = db()->where('report_id', $report_id)->getOne('reports', ['report_id', 'name'])) {
            redirect('admin/reports');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('report_id', $report_id)->delete('reports');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $report->name . '</strong>'));

            redirect('admin/reports');
        }

        redirect('admin/reports');
    }

}
