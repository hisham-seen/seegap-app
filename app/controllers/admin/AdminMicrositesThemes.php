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

class AdminMicrositesThemes extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name'], ['microsite_theme_id', 'datetime', 'last_datetime', 'name', 'order']));
        $filters->set_default_order_by('microsite_theme_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `microsites_themes` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/microsites-themes?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $microsites_themes = [];
        $microsites_themes_result = database()->query("
            SELECT
                `microsites_themes`.*,
                COUNT(`links`.`microsite_theme_id`) AS `total_usage`
            FROM
                `microsites_themes`
            LEFT JOIN `links` ON `microsites_themes`.`microsite_theme_id` = `links`.`microsite_theme_id`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
            GROUP BY `microsites_themes`.`microsite_theme_id`
                {$filters->get_sql_order_by()}
                {$paginator->get_sql_limit()}
        ");
        while($row = $microsites_themes_result->fetch_object()) {
            $microsites_themes[] = $row;
        }

        /* Export handler */
        process_export_csv($microsites_themes, 'include', ['microsite_theme_id', 'name', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_microsites_themes.title')));
        process_export_json($microsites_themes, 'include', ['microsite_theme_id', 'name', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_microsites_themes.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'microsites_themes' => $microsites_themes,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/microsites-themes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/microsites-themes');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/microsites-themes');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/microsites-themes');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $microsite_theme_id) {
                        $microsite_theme = db()->where('microsite_theme_id', $microsite_theme_id)->getOne('microsites_themes');

                        if(!$microsite_theme) {
                            continue;
                        }

                        $microsite_theme->settings = json_decode($microsite_theme->settings ?? '');

                        /* Offload deleting */
                        if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            if(!empty($microsite_theme->settings->microsite->background) && file_exists(UPLOADS_PATH . 'backgrounds' . '/' . $microsite_theme->settings->microsite->background)) {
                                $s3->deleteObject([
                                    'Bucket' => settings()->offload->storage_name,
                                    'Key' => 'uploads/backgrounds/' . $microsite_theme->settings->microsite->background,
                                ]);
                            }
                        }

                        /* Local deleting */
                        else {
                            if(!empty($microsite_theme->settings->microsite->background) && file_exists(UPLOADS_PATH . 'backgrounds/' . $microsite_theme->settings->microsite->background)) {
                                unlink(UPLOADS_PATH . 'backgrounds/' . $microsite_theme->settings->microsite->background);
                            }
                        }

                        /* Delete the resource */
                        db()->where('microsite_theme_id', $microsite_theme_id)->delete('microsites_themes');
                    }

                    /* Clear the cache */
                    cache()->deleteItem('microsites_themes');

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/microsites-themes');
    }

    public function duplicate() {

        if(empty($_POST)) {
            redirect('admin/microsites-themes');
        }

        $microsite_theme_id = (int) $_POST['microsite_theme_id'];

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$microsite_theme = db()->where('microsite_theme_id', $microsite_theme_id)->getOne('microsites_themes')) {
            redirect('admin/microsites-themes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Insert to database */
            $microsite_theme_id = db()->insert('microsites_themes', [
                'name' => string_truncate($microsite_theme->name . ' - ' . l('global.duplicated'), 64, null),
                'settings' => $microsite_theme->settings,
                'is_enabled' => $microsite_theme->is_enabled,
                'order' => $microsite_theme->order + 1,
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($microsite_theme->name) . '</strong>'));

            /* Redirect */
            redirect('admin/microsite-theme-update/' . $microsite_theme_id);

        }

        redirect('admin/microsites-themes');
    }

    public function delete() {

        $microsite_theme_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$microsite_theme = db()->where('microsite_theme_id', $microsite_theme_id)->getOne('microsites_themes')) {
            redirect('admin/microsites-themes');
        }

        $microsite_theme->settings = json_decode($microsite_theme->settings ?? '');

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Offload deleting */
            if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                if(!empty($microsite_theme->settings->microsite->background) && file_exists(UPLOADS_PATH . 'backgrounds' . '/' . $microsite_theme->settings->microsite->background)) {
                    $s3->deleteObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => 'uploads/backgrounds/' . $microsite_theme->settings->microsite->background,
                    ]);
                }
            }

            /* Local deleting */
            else {
                if(!empty($microsite_theme->settings->microsite->background) && file_exists(UPLOADS_PATH . 'backgrounds/' . $microsite_theme->settings->microsite->background)) {
                    unlink(UPLOADS_PATH . 'backgrounds/' . $microsite_theme->settings->microsite->background);
                }
            }

            /* Delete the resource */
            db()->where('microsite_theme_id', $microsite_theme->microsite_theme_id)->delete('microsites_themes');

            /* Clear the cache */
            cache()->deleteItem('microsites_themes');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $microsite_theme->name . '</strong>'));

        }

        redirect('admin/microsites-themes');
    }

}
