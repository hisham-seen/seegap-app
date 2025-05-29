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

class AdminMicrositesBlocks extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'user_id', 'link_id', 'type'], ['location_url'], ['microsite_block_id', 'order', 'last_datetime', 'datetime', 'location_url', 'clicks']));
        $filters->set_default_order_by('microsite_block_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `microsites_blocks` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/microsites-blocks?' . $filters->get_get() . '&page=%d')));

        /* Get the users */
        $microsites_blocks = [];
        $microsites_blocks_result = database()->query("
            SELECT
                `microsites_blocks`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `microsites_blocks`
            LEFT JOIN
                `users` ON `microsites_blocks`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('microsites_blocks')}
                {$filters->get_sql_order_by('microsites_blocks')}
            {$paginator->get_sql_limit()}
        ");
        while($row = $microsites_blocks_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $microsites_blocks[] = $row;
        }

        /* Export handler */
        process_export_csv($microsites_blocks, 'include', ['microsite_block_id', 'link_id', 'user_id', 'type', 'location_url', 'order', 'start_date', 'end_date', 'clicks', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_links.title')));
        process_export_json($microsites_blocks, 'include', ['microsite_block_id', 'link_id', 'user_id', 'type', 'location_url', 'order', 'settings', 'start_date', 'end_date', 'clicks', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_links.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'microsites_blocks' => $microsites_blocks,
            'filters' => $filters,
            'pagination' => $pagination,
            'microsite_blocks' => require APP_PATH . 'includes/microsite_blocks.php',
        ];

        $view = new \Altum\View('admin/microsites-blocks/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/microsites-blocks');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/microsites-blocks');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/microsites-blocks');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $microsite_block_id) {
                        (new \Altum\Models\MicrositeBlock())->delete($microsite_block_id);
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/microsites-blocks');
    }

    public function delete() {

        $microsite_block_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$link = db()->where('microsite_block_id', $microsite_block_id)->getOne('microsites_blocks', ['microsite_block_id'])) {
            redirect('admin/microsites-blocks');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new \Altum\Models\MicrositeBlock())->delete($link->microsite_block_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $link->url . '</strong>'));

        }

        redirect('admin/microsites-blocks');
    }

}
