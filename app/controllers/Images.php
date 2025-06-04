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

class Images extends Controller {

    public function index() {
        \SeeGap\Authentication::guard();

        if(!\SeeGap\Plugin::is_active('aix') || !settings()->aix->images_is_enabled) {
            redirect('not-found');
        }

        /* Check for exclusive personal API usage limitation */
        $api_key = 'openai_api_key';
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->{$api_key})) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.' . $api_key), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Images */
        $images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_images_current_month`');
        $available_images = $this->user->plan_settings->images_per_month_limit - $images_current_month;

        /* Prepare the filtering system */
        $filters = (new \SeeGap\Filters(['user_id', 'project_id', 'size', 'artist', 'lighting', 'style', 'mood'], ['name'], ['image_id', 'last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by($this->user->preferences->images_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `images` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \SeeGap\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('images?' . $filters->get_get() . '&page=%d')));

        /* Get the images */
        $images = [];
        $images_result = database()->query("
            SELECT
                *
            FROM
                `images`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");
        while($row = $images_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $row->image_url = $row->image ? \SeeGap\Uploads::get_full_url('images') . $row->image : null;
            $images[] = $row;
        }

        /* Export handler */
        process_export_csv($images, 'include', ['image_id', 'project_id', 'user_id', 'name', 'input', 'image', 'image_url', 'style', 'artist', 'lighting', 'mood', 'size', 'datetime', 'last_datetime'], sprintf(l('images.title')));
        process_export_json($images, 'include', ['image_id', 'project_id', 'user_id', 'name', 'input', 'image', 'image_url', 'style', 'artist', 'lighting', 'mood', 'size', 'settings', 'datetime', 'last_datetime'], sprintf(l('images.title')));

        /* Prepare the pagination view */
        $pagination = (new \SeeGap\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \SeeGap\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Available */
        $images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_images_current_month`');

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'images' => $images,
            'total_images' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'available_images' => $available_images,
            'images_current_month' => $images_current_month,
            'ai_images_lighting' => require \SeeGap\Plugin::get('aix')->path . 'includes/ai_images_lighting.php',
            'ai_images_styles' => require \SeeGap\Plugin::get('aix')->path . 'includes/ai_images_styles.php',
            'ai_images_moods' => require \SeeGap\Plugin::get('aix')->path . 'includes/ai_images_moods.php',
        ];

        $view = new \SeeGap\View(\SeeGap\Plugin::get('aix')->path . 'views/images/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function bulk() {

        \SeeGap\Authentication::guard();

        //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('images');
        }

        if(empty($_POST['selected'])) {
            redirect('images');
        }

        if(!isset($_POST['type'])) {
            redirect('images');
        }

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.images')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('images');
                    }

                    foreach($_POST['selected'] as $image_id) {
                        if($image = db()->where('image_id', $image_id)->where('user_id', $this->user->user_id)->getOne('images', ['image'])) {
                            /* Delete file */
                            \SeeGap\Uploads::delete_uploaded_file($image->image, 'images');

                            /* Delete the resource */
                            db()->where('image_id', $image_id)->delete('images');
                        }
                    }

                    break;

                case 'download':

                    $files = [];

                    foreach($_POST['selected'] as $image_id) {
                        if($image = db()->where('image_id', $image_id)->where('user_id', $this->user->user_id)->getOne('images', ['image'])) {
                            $files[$image->image] = \SeeGap\Uploads::get_path('images');
                        }
                    }

                    \SeeGap\Uploads::download_files_as_zip($files, l('global.download'));

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('images');
    }

    public function delete() {

        \SeeGap\Authentication::guard();

        if(!\SeeGap\Plugin::is_active('aix') || !settings()->aix->images_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('images');
        }

        if(empty($_POST)) {
            redirect('images');
        }

        $image_id = (int) query_clean($_POST['image_id']);

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('image_id', $image_id)->where('user_id', $this->user->user_id)->getOne('images', ['image_id', 'name', 'image'])) {
            redirect('images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \SeeGap\Uploads::delete_uploaded_file($image->image, 'images');

            /* Delete the resource */
            db()->where('image_id', $image_id)->delete('images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

            redirect('images');
        }

        redirect('images');
    }

}
