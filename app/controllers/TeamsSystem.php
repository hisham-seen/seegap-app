<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;


defined('SEEGAP') || die();

class TeamsSystem extends Controller {

    public function index() {

        if(!\SeeGap\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Get data about the teams */
        $total_teams = db()->where('user_id', $this->user->user_id)->getValue('teams', 'count(*)');
        $total_teams_member = db()->where('user_id', $this->user->user_id)->orWhere('user_email', $this->user->email)->getValue('teams_members', 'count(*)');

        /* Prepare the view */
        $data = [
            'total_teams' => $total_teams,
            'total_teams_member' => $total_teams_member,
        ];

        $view = new \SeeGap\View('teams-system/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
