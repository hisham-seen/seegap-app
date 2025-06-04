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

class ProjectCreate extends Controller {

    public function index() {

        if(!settings()->links->projects_is_enabled) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.projects')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('projects');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `projects` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->projects_limit != -1 && $total_rows >= $this->user->plan_settings->projects_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('projects');
        }

        if(!empty($_POST)) {
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['color'] = !verify_hex_color($_POST['color']) ? '#000000' : $_POST['color'];

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name', 'color'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('projects', [
                    'user_id' => $this->user->user_id,
                    'name' => $_POST['name'],
                    'color' => $_POST['color'],
                    'datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('projects?user_id=' . $this->user->user_id);

                redirect('projects');
            }
        }

        $values = [
            'name' => $_POST['name'] ?? '',
            'color' => $_POST['color'] ?? '#000000',
        ];

        /* Prepare the view */
        $data = [
            'values' => $values
        ];

        $view = new \SeeGap\View('project-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
