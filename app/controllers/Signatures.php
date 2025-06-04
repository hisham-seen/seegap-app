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

class Signatures extends Controller {

    public function index() {

        if(!\SeeGap\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['user_id', 'project_id'], ['name'], ['signature_id', 'last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by($this->user->preferences->signatures_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `signatures` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('signatures?' . $filters->get_get() . '&page=%d')));

        /* Get the signatures */
        $signatures = [];
        $signatures_result = database()->query("
            SELECT
                *
            FROM
                `signatures`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");
        while($row = $signatures_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $signatures[] = $row;
        }

        /* Export handler */
        process_export_csv($signatures, 'include', ['signature_id', 'project_id', 'user_id', 'name', 'datetime', 'last_datetime'], sprintf(l('signatures.title')));
        process_export_json($signatures, 'include', ['signature_id', 'project_id', 'user_id', 'name', 'settings', 'datetime', 'last_datetime'], sprintf(l('signatures.title')));

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Signature templates */
        $signature_templates = require \SeeGap\Plugin::get('email-signatures')->path . 'includes/signature_templates.php';

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'signatures' => $signatures,
            'total_signatures' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'signature_templates' => $signature_templates,
        ];

        $view = new \SeeGap\View(\SeeGap\Plugin::get('email-signatures')->path . 'views/signatures/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function duplicate() {

        if(!\SeeGap\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.signatures')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('signatures');
        }

        if(empty($_POST)) {
            redirect('signatures');
        }

        /* Make sure that the user didn't exceed the limit */
        $total_rows = db()->where('user_id', $this->user->user_id)->getValue('signatures', 'COUNT(*)') ?? 0;
        if($this->user->plan_settings->signatures_limit != -1 && $total_rows >= $this->user->plan_settings->signatures_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('signatures');
        }

        $signature_id = (int) $_POST['signature_id'];

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('signatures');
        }

        /* Verify the main resource */
        if(!$signature = db()->where('signature_id', $signature_id)->where('user_id', $this->user->user_id)->getOne('signatures')) {
            redirect('signatures');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Parse settings */
            $signature->settings = json_decode($signature->settings ?? '');

            /* Insert to database */
            $signature_id = db()->insert('signatures', [
                'user_id' => $this->user->user_id,
                'project_id' => $signature->project_id,
                'name' => string_truncate($signature->name . ' - ' . l('global.duplicated'), 64, null),
                'template' => $signature->template,
                'settings' => json_encode($signature->settings),
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($signature->name) . '</strong>'));

            /* Redirect */
            redirect('signature-update/' . $signature_id);

        }

        redirect('signatures');
    }

    public function bulk() {

        \SeeGap\Authentication::guard();

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('signatures');
        }

        if(empty($_POST['selected'])) {
            redirect('signatures');
        }

        if(!isset($_POST['type'])) {
            redirect('signatures');
        }

        //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.signatures')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('signatures');
                    }

                    foreach($_POST['selected'] as $signature_id) {
                        db()->where('user_id', $this->user->user_id)->where('signature_id', $signature_id)->delete('signatures');
                    }

                    break;
            }

            /* Clear the cache */
            cache()->deleteItem('signatures?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('signatures');
    }

    public function delete() {

        if(!\SeeGap\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.signatures')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('signatures');
        }

        if(empty($_POST)) {
            redirect('signatures');
        }

        $signature_id = (int) query_clean($_POST['signature_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$signature = db()->where('signature_id', $signature_id)->where('user_id', $this->user->user_id)->getOne('signatures', ['signature_id', 'name'])) {
            redirect('signatures');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('signature_id', $signature_id)->delete('signatures');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $signature->name . '</strong>'));

            /* Clear the cache */
            cache()->deleteItem('signatures?user_id=' . $this->user->user_id);

            redirect('signatures');
        }

        redirect('signatures');
    }

}
