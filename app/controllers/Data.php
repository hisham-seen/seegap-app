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

class Data extends Controller {

    public function index() {

        if(!settings()->links->biolinks_is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Check if we're viewing a specific form's submissions */
        $biolink_block_id = isset($_GET['biolink_block_id']) ? (int) $_GET['biolink_block_id'] : null;
        
        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['biolink_block_id', 'link_id', 'project_id', 'user_id', 'type', 'is_enabled'], [], ['datum_id', 'datetime']));
        $filters->set_default_order_by($this->user->preferences->data_default_order_by ?? 'datetime', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* First, get all biolink blocks to get their names */
        $biolink_blocks_result = database()->query("
            SELECT `biolink_block_id`, `settings`, `type` 
            FROM `biolinks_blocks` 
            WHERE `user_id` = {$this->user->user_id}
        ");
        
        $biolink_blocks = [];
        while($block = $biolink_blocks_result->fetch_object()) {
            $block->settings = json_decode($block->settings);
            $biolink_blocks[$block->biolink_block_id] = $block;
        }
        
        /* If viewing a specific form's submissions */
        if($biolink_block_id) {
            /* Make sure the biolink block exists and belongs to the user */
            if(!isset($biolink_blocks[$biolink_block_id])) {
                redirect('data');
            }
            
            /* Add the biolink_block_id to the filters */
            $_GET['biolink_block_id'] = $biolink_block_id;
            // Add the biolink_block_id to the filters directly
            $filters->filters['biolink_block_id'] = $biolink_block_id;
            
            /* Prepare the paginator */
            $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `data` WHERE `user_id` = {$this->user->user_id} AND `biolink_block_id` = {$biolink_block_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
            $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('data?biolink_block_id=' . $biolink_block_id . '&' . $filters->get_get() . '&page=%d')));
            
            /* Get the submissions for this form */
            $submissions = [];
            $data_result = database()->query("SELECT * FROM `data` WHERE `user_id` = {$this->user->user_id} AND `biolink_block_id` = {$biolink_block_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
            
            while($row = $data_result->fetch_object()) {
                $row->data = json_decode($row->data);
                $submissions[] = $row;
            }
            
            /* Get the form details */
            $form = [
                'biolink_block_id' => $biolink_block_id,
                'type' => $biolink_blocks[$biolink_block_id]->type,
                'form_name' => $biolink_blocks[$biolink_block_id]->settings->name ?? 'Unknown Form',
                'submissions_count' => $total_rows,
                'submissions' => $submissions
            ];
            
            /* Export handler */
            // Prepare submissions for export by converting objects to arrays
            $export_submissions = [];
            foreach($submissions as $submission) {
                $export_submission = [
                    'datum_id' => $submission->datum_id,
                    'link_id' => $submission->link_id,
                    'user_id' => $submission->user_id,
                    'project_id' => $submission->project_id,
                    'type' => $submission->type,
                    'datetime' => $submission->datetime,
                    'data' => json_encode($submission->data)
                ];
                $export_submissions[] = $export_submission;
            }
            
            if(isset($_GET['export']) && $_GET['export'] == 'csv') {
                process_export_csv($export_submissions, 'include', ['datum_id', 'link_id', 'user_id', 'project_id', 'type', 'data', 'datetime'], sprintf(l('data.title') . ' - ' . $form['form_name']));
            }
            
            if(isset($_GET['export']) && $_GET['export'] == 'json') {
                process_export_json($export_submissions, 'include', ['datum_id', 'link_id', 'user_id', 'project_id', 'type', 'data', 'datetime'], sprintf(l('data.title') . ' - ' . $form['form_name']));
            }
            
            /* Prepare the pagination view */
            $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);
            
            /* Existing projects */
            $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);
            
            /* Prepare the view */
            $data = [
                'form' => $form,
                'biolink_blocks' => require APP_PATH . 'includes/biolink_blocks.php',
                'projects' => $projects,
                'pagination' => $pagination,
                'filters' => $filters,
            ];
            
            $view = new \Altum\View('data/form_submissions', (array) $this);
            
            $this->add_view_content('content', $view->run($data));
        }
        /* Main data view showing all forms */
        else {
            /* Prepare the paginator for forms */
            $total_forms = database()->query("SELECT COUNT(DISTINCT `biolink_block_id`) AS `total` FROM `data` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
            $paginator = (new \Altum\Paginator($total_forms, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('data?' . $filters->get_get() . '&page=%d')));
            
            /* Get distinct biolink_block_ids with their latest submission and count */
            $forms = [];
            
            // Make sure we have a valid order_by field
            $order_by = in_array($filters->order_by, ['datum_id', 'datetime']) ? $filters->order_by : 'datetime';
            
            // Get all data entries for this user
            $data_result = database()->query("
                SELECT 
                    d.`biolink_block_id`, 
                    d.`type`, 
                    d.`link_id`, 
                    d.`project_id`,
                    d.`datum_id`,
                    d.`datetime`
                FROM 
                    `data` d
                WHERE 
                    d.`user_id` = {$this->user->user_id} 
                    {$filters->get_sql_where('d')}
                ORDER BY 
                    {$order_by} {$filters->order_type}
            ");
            
            // Process the data to group by form
            $form_data = [];
            $form_submissions_count = [];
            $form_last_submission = [];
            
            if($data_result) {
                while($row = $data_result->fetch_object()) {
                    // Get form name from biolink blocks
                    $form_name = isset($biolink_blocks[$row->biolink_block_id]) ? 
                        $biolink_blocks[$row->biolink_block_id]->settings->name ?? 'Unknown Form' : 
                        'Unknown Form';
                    
                    // Create a unique key for the form
                    $form_key = strtolower(trim($form_name));
                    
                    // Initialize form data if not exists
                    if(!isset($form_data[$form_key])) {
                        $form_data[$form_key] = [
                            'biolink_block_id' => $row->biolink_block_id,
                            'type' => $row->type,
                            'link_id' => $row->link_id,
                            'project_id' => $row->project_id,
                            'form_name' => $form_name,
                            'instances' => []
                        ];
                    }
                    
                    // Add this instance if not already added
                    if(!in_array($row->biolink_block_id, $form_data[$form_key]['instances'])) {
                        $form_data[$form_key]['instances'][] = $row->biolink_block_id;
                    }
                    
                    // Count submissions for this form
                    if(!isset($form_submissions_count[$form_key])) {
                        $form_submissions_count[$form_key] = 0;
                    }
                    $form_submissions_count[$form_key]++;
                    
                    // Track the latest submission
                    if(!isset($form_last_submission[$form_key]) || strtotime($row->datetime) > strtotime($form_last_submission[$form_key])) {
                        $form_last_submission[$form_key] = $row->datetime;
                    }
                }
            }
            
            // Create the forms array for display
            foreach($form_data as $form_key => $data) {
                $forms[] = (object) [
                    'biolink_block_id' => $data['biolink_block_id'],
                    'type' => $data['type'],
                    'link_id' => $data['link_id'],
                    'project_id' => $data['project_id'],
                    'form_name' => $data['form_name'],
                    'submissions_count' => $form_submissions_count[$form_key] ?? 0,
                    'last_submission_datetime' => $form_last_submission[$form_key] ?? null,
                    'instances' => $data['instances']
                ];
            }
            
            // Apply pagination manually since we're grouping forms
            $total_forms = count($forms);
            $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $current_page = $current_page < 1 ? 1 : $current_page;
            $forms = array_slice($forms, ($current_page - 1) * $filters->get_results_per_page(), $filters->get_results_per_page());
            
            // Create the paginator after slicing the array
            $paginator = (new \Altum\Paginator($total_forms, $filters->get_results_per_page(), $current_page, url('data?' . $filters->get_get() . '&page=%d')));
            
            /* Export handler */
            // Prepare forms for export by converting objects to arrays
            $export_forms = [];
            foreach($forms as $form) {
                $export_form = [
                    'biolink_block_id' => $form->biolink_block_id,
                    'type' => $form->type,
                    'link_id' => $form->link_id,
                    'project_id' => $form->project_id,
                    'submissions_count' => $form->submissions_count,
                    'last_submission_datetime' => $form->last_submission_datetime,
                    'form_name' => $form->form_name
                ];
                $export_forms[] = $export_form;
            }
            
            if(isset($_GET['export']) && $_GET['export'] == 'csv') {
                process_export_csv($export_forms, 'include', ['biolink_block_id', 'type', 'link_id', 'project_id', 'submissions_count', 'last_submission_datetime', 'form_name'], sprintf(l('data.title')));
            }
            
            if(isset($_GET['export']) && $_GET['export'] == 'json') {
                process_export_json($export_forms, 'include', ['biolink_block_id', 'type', 'link_id', 'project_id', 'submissions_count', 'last_submission_datetime', 'form_name'], sprintf(l('data.title')));
            }
            
            /* Prepare the pagination view */
            $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);
            
            /* Existing projects */
            $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);
            
            /* Prepare the view */
            $data = [
                'forms' => $forms,
                'total_forms' => $total_forms,
                'projects' => $projects,
                'pagination' => $pagination,
                'filters' => $filters,
                'biolink_blocks' => require APP_PATH . 'includes/biolink_blocks.php',
            ];
            
            $view = new \Altum\View('data/index', (array) $this);
            
            $this->add_view_content('content', $view->run($data));
        }
    }

    public function bulk() {

        \Altum\Authentication::guard();

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('data');
        }

        if(empty($_POST['selected'])) {
            redirect('data');
        }

        if(!isset($_POST['type'])) {
            redirect('data');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.data')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('data');
                    }

                    foreach($_POST['selected'] as $datum_id) {
                        db()->where('user_id', $this->user->user_id)->where('datum_id', $datum_id)->delete('data');
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('data');
    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.data')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('data');
        }

        if(empty($_POST)) {
            redirect('data');
        }

        $datum_id = (int) query_clean($_POST['datum_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$datum = db()->where('datum_id', $datum_id)->where('user_id', $this->user->user_id)->getOne('data', ['datum_id'])) {
            redirect('data');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('datum_id', $datum_id)->delete('data');

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

            redirect('data');
        }

        redirect('data');
    }
}
