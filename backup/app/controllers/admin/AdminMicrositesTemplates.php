<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class AdminMicrositesTemplates extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name'], ['microsite_template_id', 'datetime', 'last_datetime', 'name', 'order', 'total_usage']));
        $filters->set_default_order_by('microsite_template_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `microsites_templates` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/microsites-templates?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $microsites_templates = [];
        $microsites_templates_result = database()->query("
            SELECT
                `microsites_templates`.*
            FROM
                `microsites_templates`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");
        while($row = $microsites_templates_result->fetch_object()) {
            $microsites_templates[] = $row;
        }

        /* Export handler */
        process_export_csv($microsites_templates, 'include', ['microsite_template_id', 'name', 'is_enabled', 'total_usage', 'last_datetime', 'datetime'], sprintf(l('admin_microsites_templates.title')));
        process_export_json($microsites_templates, 'include', ['microsite_template_id', 'name', 'settings', 'is_enabled', 'total_usage', 'last_datetime', 'datetime'], sprintf(l('admin_microsites_templates.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'microsites_templates' => $microsites_templates,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/microsites-templates/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/microsites-templates');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/microsites-templates');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/microsites-templates');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $microsite_template_id) {
                        $microsite_template = db()->where('microsite_template_id', $microsite_template_id)->getOne('microsites_templates');

                        if(!$microsite_template) {
                            continue;
                        }

                        /* Delete the resource */
                        db()->where('microsite_template_id', $microsite_template_id)->delete('microsites_templates');
                    }

                    /* Clear the cache */
                    cache()->deleteItem('microsites_templates');

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/microsites-templates');
    }

    public function delete() {

        $microsite_template_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$microsite_template = db()->where('microsite_template_id', $microsite_template_id)->getOne('microsites_templates')) {
            redirect('admin/microsites-templates');
        }

        $microsite_template->settings = json_decode($microsite_template->settings ?? '');

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('microsite_template_id', $microsite_template->microsite_template_id)->delete('microsites_templates');

            /* Clear the cache */
            cache()->deleteItem('microsites_templates');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $microsite_template->name . '</strong>'));

        }

        redirect('admin/microsites-templates');
    }

}
