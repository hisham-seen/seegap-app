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

class TeamUpdate extends Controller {

    public function index() {

        if(!\SeeGap\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$team = db()->where('team_id', $team_id)->where('user_id', $this->user->user_id)->getOne('teams')) {
            redirect('teams');
        }

        if(!empty($_POST)) {
            $_POST['name'] = trim(input_clean($_POST['name']));

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
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
                db()->where('team_id', $team->team_id)->update('teams', [
                    'name' => $_POST['name'],
                    'last_datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('team?team_id=' . $team->team_id);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('team-update/' . $team_id);
            }
        }

        /* Prepare the view */
        $data = [
            'team' => $team
        ];

        $view = new \SeeGap\View('team-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
