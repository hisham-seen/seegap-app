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

class TeamsMember extends Controller {

    public function index() {

        if(!\SeeGap\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        \SeeGap\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters([], ['name'], ['team_member_id', 'datetime', 'last_datetime']));
        $filters->set_default_order_by('team_member_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `teams_members` WHERE (`user_id` = {$this->user->user_id} OR `user_email` = '{$this->user->email}') {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('teams-member?' . $filters->get_get() . 'page=%d')));

        /* Get the teams list for the user */
        $teams_member = [];
        $teams_member_result = database()->query("
            SELECT `teams`.`name`, `teams_members`.*
            FROM `teams_members` 
            LEFT JOIN `teams` ON `teams`.`team_id` = `teams_members`.`team_id` 
            WHERE 
                  (`teams_members`.`user_id` = {$this->user->user_id} 
                  OR `teams_members`.`user_email` = '{$this->user->email}')
                  {$filters->get_sql_where('teams_members')} 
            {$filters->get_sql_order_by('teams_members')} 
            {$paginator->get_sql_limit()}
        ");
        while($row = $teams_member_result->fetch_object()) {
            $row->access = json_decode($row->access);
            $teams_member[] = $row;
        }

        /* Export handler */
        process_export_json($teams_member, 'include', ['team_member_id', 'team_id', 'user_id', 'name', 'status', 'access', 'datetime', 'last_datetime']);
        process_export_csv($teams_member, 'include', ['team_member_id', 'team_id', 'user_id', 'name', 'status', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'teams_member' => $teams_member,
            'total_teams' => $total_rows,
            'filters' => $filters,
            'pagination' => $pagination,
            'teams_access' => require APP_PATH . 'includes/teams_access.php',
        ];

        $view = new \SeeGap\View('teams-member/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {
        \SeeGap\Authentication::guard();

        if(empty($_POST)) {
            redirect('teams');
        }

        $team_id = (int) query_clean($_POST['team_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('teams');
        }

        /* Make sure the team id is created by the logged in user */
        if(!$team = db()->where('team_id', $team_id)->where('user_id', $this->user->user_id)->getOne('teams')) {
            redirect('teams');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the domain */
            db()->where('team_id', $team->team_id)->delete('teams');

            /* Clear the cache */
            cache()->deleteItemsByTag('team_id=' . $team->team_id);
            cache()->deleteItem('team?team_id=' . $team->team_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $team->name . '</strong>'));

            redirect('teams');

        }

        redirect('teams');
    }
}
