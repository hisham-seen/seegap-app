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

class AdminMicrositeTemplateUpdate extends Controller {

    public function index() {

        $microsite_template_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$microsite_template = db()->where('microsite_template_id', $microsite_template_id)->getOne('microsites_templates')) {
            redirect('admin/microsites-templates');
        }
        $microsite_template->settings = json_decode($microsite_template->settings ?? '');

        $microsites = [];
        $result = database()->query("
            SELECT 
                `links`.`link_id`, `links`.`domain_id`, `links`.`url`,
                `domains`.`scheme`, `domains`.`host`, `domains`.`link_id` as `domain_link_id`
            FROM 
                `links` 
            LEFT JOIN `users` ON `users`.`user_id` = `links`.`user_id`
            LEFT JOIN `domains` ON `links`.`domain_id` = `domains`.`domain_id`
            WHERE 
                `users`.`type` = 1
                AND `links`.`type` = 'microsite'
        ");

        while($row = $result->fetch_object()) {
            $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . ($row->domain_link_id == $row->link_id ? null : $row->url) : SITE_URL . $row->url;
            $microsites[$row->link_id] = $row;
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['link_id'] = (int) $_POST['link_id'];
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
            $_POST['url'] = $microsites[$_POST['link_id']]->full_url ?? '';

            //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!db()->where('link_id', $_POST['link_id'])->where('type', 'microsite')->getOne('links')) {
                Alerts::add_field_error('link_id', l('admin_microsites_templates.error_message.link_id'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $settings = json_encode([]);

                /* Database query */
                db()->where('microsite_template_id', $microsite_template_id)->update('microsites_templates', [
                    'link_id' => $_POST['link_id'],
                    'name' => $_POST['name'],
                    'url' => $_POST['url'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'order' => $_POST['order'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('microsites_templates');

                /* Refresh the page */
                redirect('admin/microsite-template-update/' . $microsite_template_id);

            }

        }

        /* Main View */
        $data = [
            'microsite_template' => $microsite_template,
            'microsites' => $microsites,
        ];

        $view = new \SeeGap\View('admin/microsite-template-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
