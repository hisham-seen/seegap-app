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

class Data extends Controller {

    public function index() {

        if(!settings()->links->microsites_is_enabled) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Check if we're viewing a specific form's submissions */
        $microsite_block_id = isset($_GET['microsite_block_id']) ? (int) $_GET['microsite_block_id'] : null;
        
        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['microsite_block_id', 'link_id', 'project_id', 'user_id', 'form_type'], [], ['form_submission_id', 'submitted_at']));
        $filters->set_default_order_by($this->user->preferences->data_default_order_by ?? 'submitted_at', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Ensure form_submissions table exists */
        $this->ensure_form_submissions_table();

        /* First, get all microsite blocks to get their names */
        $microsite_blocks_result = database()->query("
            SELECT mb.`microsite_block_id`, mb.`settings`, mb.`type`, mb.`link_id`, l.`user_id`
            FROM `microsites_blocks` mb
            LEFT JOIN `links` l ON mb.`link_id` = l.`link_id`
            WHERE l.`user_id` = {$this->user->user_id} AND mb.`type` = 'form'
        ");
        
        $microsite_blocks = [];
        while($block = $microsite_blocks_result->fetch_object()) {
            $block->settings = json_decode($block->settings);
            $microsite_blocks[$block->microsite_block_id] = $block;
        }
        
        /* If viewing a specific form's submissions */
        if($microsite_block_id) {
            /* Make sure the microsite block exists and belongs to the user */
            if(!isset($microsite_blocks[$microsite_block_id])) {
                redirect('data');
            }
            
            /* Add the microsite_block_id to the filters */
            $_GET['microsite_block_id'] = $microsite_block_id;
            // Add the microsite_block_id to the filters directly
            $filters->filters['microsite_block_id'] = $microsite_block_id;
            
            /* Prepare the paginator */
            $total_rows = database()->query("
                SELECT COUNT(*) AS `total` 
                FROM `form_submissions` fs
                LEFT JOIN `links` l ON fs.`link_id` = l.`link_id`
                WHERE l.`user_id` = {$this->user->user_id} AND fs.`microsite_block_id` = {$microsite_block_id}
            ")->fetch_object()->total ?? 0;
            $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('data?microsite_block_id=' . $microsite_block_id . '&' . $filters->get_get() . '&page=%d')));
            
            /* Get the submissions for this form */
            $submissions = [];
            $order_by = in_array($filters->order_by, ['form_submission_id', 'submitted_at']) ? $filters->order_by : 'submitted_at';
            $data_result = database()->query("
                SELECT fs.*, l.`project_id`
                FROM `form_submissions` fs
                LEFT JOIN `links` l ON fs.`link_id` = l.`link_id`
                WHERE l.`user_id` = {$this->user->user_id} AND fs.`microsite_block_id` = {$microsite_block_id}
                ORDER BY fs.`{$order_by}` {$filters->order_type}
                {$paginator->get_sql_limit()}
            ");
            
            while($row = $data_result->fetch_object()) {
                $row->responses = json_decode($row->responses);
                $row->metadata = json_decode($row->metadata ?? '{}');
                $submissions[] = $row;
            }
            
            /* Get the form details */
            $form = [
                'microsite_block_id' => $microsite_block_id,
                'type' => $microsite_blocks[$microsite_block_id]->type,
                'form_name' => $microsite_blocks[$microsite_block_id]->settings->name ?? 'Unknown Form',
                'submissions_count' => $total_rows,
                'submissions' => $submissions
            ];
            
            /* Export handler */
            // Prepare submissions for export by converting objects to arrays
            $export_submissions = [];
            foreach($submissions as $submission) {
                $export_submission = [
                    'form_submission_id' => $submission->form_submission_id,
                    'link_id' => $submission->link_id,
                    'project_id' => $submission->project_id ?? null,
                    'form_type' => $submission->form_type,
                    'responses' => json_encode($submission->responses),
                    'metadata' => json_encode($submission->metadata),
                    'ip' => $submission->ip,
                    'submitted_at' => $submission->submitted_at
                ];
                $export_submissions[] = $export_submission;
            }
            
            if(isset($_GET['export']) && $_GET['export'] == 'csv') {
                process_export_csv($export_submissions, 'include', ['form_submission_id', 'link_id', 'project_id', 'form_type', 'responses', 'metadata', 'ip', 'submitted_at'], sprintf(l('data.title') . ' - ' . $form['form_name']));
            }
            
            if(isset($_GET['export']) && $_GET['export'] == 'json') {
                process_export_json($export_submissions, 'include', ['form_submission_id', 'link_id', 'project_id', 'form_type', 'responses', 'metadata', 'ip', 'submitted_at'], sprintf(l('data.title') . ' - ' . $form['form_name']));
            }
            
            /* Prepare the pagination view */
            $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);
            
            /* Existing projects */
            $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);
            
            /* Prepare the view */
            $data = [
                'form' => $form,
                'microsite_blocks' => require APP_PATH . 'includes/microsite_blocks.php',
                'projects' => $projects,
                'pagination' => $pagination,
                'filters' => $filters,
            ];
            
            $view = new \SeeGap\View('data/form_submissions', (array) $this);
            
            $this->add_view_content('content', $view->run($data));
        }
        /* Main data view showing all forms */
        else {
            /* Debug: Log some information */
            error_log("Data Controller Debug - User ID: {$this->user->user_id}");
            error_log("Data Controller Debug - Microsite blocks found: " . count($microsite_blocks));
            
            /* Get all form submissions for this user */
            $data_result = database()->query("
                SELECT 
                    fs.`microsite_block_id`, 
                    fs.`form_type`, 
                    fs.`link_id`, 
                    l.`project_id`,
                    fs.`form_submission_id`,
                    fs.`submitted_at`
                FROM 
                    `form_submissions` fs
                LEFT JOIN `links` l ON fs.`link_id` = l.`link_id`
                WHERE 
                    l.`user_id` = {$this->user->user_id}
                ORDER BY 
                    fs.`submitted_at` DESC
            ");
            
            error_log("Data Controller Debug - Form submissions found: " . $data_result->num_rows);
            
            // Process the data to group by form
            $form_data = [];
            $form_submissions_count = [];
            $form_last_submission = [];
            
            if($data_result && $data_result->num_rows > 0) {
                while($row = $data_result->fetch_object()) {
                    error_log("Data Controller Debug - Processing submission: Block {$row->microsite_block_id}");
                    
                    // Get form name from microsite blocks
                    $form_name = isset($microsite_blocks[$row->microsite_block_id]) ? 
                        $microsite_blocks[$row->microsite_block_id]->settings->name ?? 'Unknown Form' : 
                        'Unknown Form';
                    
                    error_log("Data Controller Debug - Form name: {$form_name}");
                    
                    // Create a unique key for the form
                    $form_key = strtolower(trim($form_name));
                    
                    // Initialize form data if not exists
                    if(!isset($form_data[$form_key])) {
                        $form_data[$form_key] = [
                            'microsite_block_id' => $row->microsite_block_id,
                            'type' => 'form',
                            'link_id' => $row->link_id,
                            'project_id' => $row->project_id,
                            'form_name' => $form_name,
                            'instances' => []
                        ];
                    }
                    
                    // Add this instance if not already added
                    if(!in_array($row->microsite_block_id, $form_data[$form_key]['instances'])) {
                        $form_data[$form_key]['instances'][] = $row->microsite_block_id;
                    }
                    
                    // Count submissions for this form
                    if(!isset($form_submissions_count[$form_key])) {
                        $form_submissions_count[$form_key] = 0;
                    }
                    $form_submissions_count[$form_key]++;
                    
                    // Track the latest submission
                    if(!isset($form_last_submission[$form_key]) || strtotime($row->submitted_at) > strtotime($form_last_submission[$form_key])) {
                        $form_last_submission[$form_key] = $row->submitted_at;
                    }
                }
            }
            
            error_log("Data Controller Debug - Form data groups: " . count($form_data));
            
            // Create the forms array for display
            $forms = [];
            foreach($form_data as $form_key => $data) {
                $forms[] = (object) [
                    'microsite_block_id' => $data['microsite_block_id'],
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
            $paginator = (new \SeeGap\Paginator($total_forms, $filters->get_results_per_page(), $current_page, url('data?' . $filters->get_get() . '&page=%d')));
            
            /* Export handler */
            // Prepare forms for export by converting objects to arrays
            $export_forms = [];
            foreach($forms as $form) {
                $export_form = [
                    'microsite_block_id' => $form->microsite_block_id,
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
                process_export_csv($export_forms, 'include', ['microsite_block_id', 'type', 'link_id', 'project_id', 'submissions_count', 'last_submission_datetime', 'form_name'], sprintf(l('data.title')));
            }
            
            if(isset($_GET['export']) && $_GET['export'] == 'json') {
                process_export_json($export_forms, 'include', ['microsite_block_id', 'type', 'link_id', 'project_id', 'submissions_count', 'last_submission_datetime', 'form_name'], sprintf(l('data.title')));
            }
            
            /* Prepare the pagination view */
            $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);
            
            /* Existing projects */
            $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);
            
            /* Prepare the view */
            $data = [
                'forms' => $forms,
                'total_forms' => $total_forms,
                'projects' => $projects,
                'pagination' => $pagination,
                'filters' => $filters,
                'microsite_blocks' => require APP_PATH . 'includes/microsite_blocks.php',
            ];
            
            $view = new \SeeGap\View('data/index', (array) $this);
            
            $this->add_view_content('content', $view->run($data));
        }
    }

    public function bulk() {

        \SeeGap\Authentication::guard();

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

        //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.data')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('data');
                    }

                    foreach($_POST['selected'] as $form_submission_id) {
                        // Verify the submission belongs to the user before deleting
                        $submission = database()->query("
                            SELECT fs.form_submission_id 
                            FROM form_submissions fs
                            LEFT JOIN links l ON fs.link_id = l.link_id
                            WHERE fs.form_submission_id = {$form_submission_id} AND l.user_id = {$this->user->user_id}
                        ")->fetch_object();
                        
                        if($submission) {
                            db()->where('form_submission_id', $form_submission_id)->delete('form_submissions');
                        }
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('data');
    }

    public function delete() {

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.data')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('data');
        }

        if(empty($_POST)) {
            redirect('data');
        }

        $form_submission_id = (int) query_clean($_POST['form_submission_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        // Verify the submission belongs to the user
        if(!$submission = database()->query("
            SELECT fs.form_submission_id 
            FROM form_submissions fs
            LEFT JOIN links l ON fs.link_id = l.link_id
            WHERE fs.form_submission_id = {$form_submission_id} AND l.user_id = {$this->user->user_id}
        ")->fetch_object()) {
            redirect('data');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('form_submission_id', $form_submission_id)->delete('form_submissions');

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

            redirect('data');
        }

        redirect('data');
    }

    private function ensure_form_submissions_table() {
        // Check if table exists, if not create it
        $table_exists = database()->query("SHOW TABLES LIKE 'form_submissions'")->num_rows > 0;
        
        if(!$table_exists) {
            database()->query("
                CREATE TABLE `form_submissions` (
                    `form_submission_id` int(11) NOT NULL AUTO_INCREMENT,
                    `microsite_block_id` int(11) NOT NULL,
                    `link_id` int(11) NOT NULL,
                    `form_type` varchar(32) NOT NULL DEFAULT 'custom',
                    `responses` longtext,
                    `metadata` longtext,
                    `ip` varchar(64) DEFAULT NULL,
                    `user_agent` text,
                    `submitted_at` datetime NOT NULL,
                    PRIMARY KEY (`form_submission_id`),
                    KEY `microsite_block_id` (`microsite_block_id`),
                    KEY `link_id` (`link_id`),
                    KEY `submitted_at` (`submitted_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
    }
}
