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

defined('SEEGAP') || die();

class Reports extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters([], ['name'], ['report_id', 'last_datetime', 'name', 'datetime']));
        $filters->set_default_order_by($this->user->preferences->reports_default_order_by ?? 'report_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Check if reports table exists */
        $total_rows = 0;
        $table_exists_result = database()->query("SHOW TABLES LIKE 'reports'");
        if($table_exists_result && $table_exists_result->num_rows > 0) {
            /* Prepare the paginator */
            $total_rows_result = database()->query("
                SELECT COUNT(*) AS `total` 
                FROM `reports` 
                WHERE (`user_id` = {$this->user->user_id} OR JSON_CONTAINS(`assigned_user_ids`, '{$this->user->user_id}', '$'))
                    AND `is_enabled` = 1
                {$filters->get_sql_where()}
            ");
            $total_rows = $total_rows_result ? ($total_rows_result->fetch_object()->total ?? 0) : 0;
        }
        
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('reports?' . $filters->get_get() . '&page=%d')));

        /* Get the reports list for the user */
        $reports = [];
        if($table_exists_result && $table_exists_result->num_rows > 0) {
            $reports_result = database()->query("
                SELECT * 
                FROM `reports` 
                WHERE (`user_id` = {$this->user->user_id} OR JSON_CONTAINS(`assigned_user_ids`, '{$this->user->user_id}', '$'))
                    AND `is_enabled` = 1
                {$filters->get_sql_where()} 
                {$filters->get_sql_order_by()} 
                {$paginator->get_sql_limit()}
            ");
            if($reports_result) {
                while($row = $reports_result->fetch_object()) {
                    $row->assigned_user_ids = json_decode($row->assigned_user_ids);
                    $reports[] = $row;
                }
            }
        }

        /* Export handler */
        process_export_csv($reports, 'include', ['report_id', 'user_id', 'name', 'description', 'datetime'], sprintf(l('reports.title')));
        process_export_json($reports, 'include', ['report_id', 'user_id', 'name', 'description', 'datetime'], sprintf(l('reports.title')));

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'reports' => $reports,
            'total_reports' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \SeeGap\View('reports/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
