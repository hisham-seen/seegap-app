<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\Api;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Response;
use Altum\Traits\Apiable;

class ApiGs1Links extends \Altum\Controllers\Controller {
    use Apiable;

    public function index() {

        Authentication::guard('api');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('read.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $user = $this->get_user();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'project_id'], ['gtin', 'target_url', 'title'], ['last_datetime', 'datetime', 'gtin', 'clicks']));
        $filters->set_default_order_by('gs1_link_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `gs1_links` WHERE `user_id` = {$user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/gs1-links?' . $filters->get_get() . '&page=%d')));

        /* Get the gs1_links list for the user */
        $gs1_links = [];
        $gs1_links_result = Database::$database->query("
            SELECT
                *
            FROM
                `gs1_links`
            WHERE
                `user_id` = {$user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $gs1_links_result->fetch_object()) {

            /* Generate the gs1 digital link */
            $row->gs1_digital_link = (new \Altum\Models\Domain())->get_domain_url($row->domain_id) . '01/' . $row->gtin;

            /* Parse the settings */
            $row->settings = json_decode($row->settings ?? '');
            $row->pixels_ids = json_decode($row->pixels_ids ?? '[]');

            $gs1_links[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        $data = [
            'gs1_links' => $gs1_links,
            'meta' => $meta
        ];

        Response::json($data);
    }

    public function post() {

        Authentication::guard('api');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $user = $this->get_user();

        /* Check for the plan limit */
        $user_total_gs1_links = Database::$database->query("SELECT COUNT(*) AS `total` FROM `gs1_links` WHERE `user_id` = {$user->user_id}")->fetch_object()->total ?? 0;

        if($user->plan_settings->gs1_links_limit != -1 && $user_total_gs1_links >= $user->plan_settings->gs1_links_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Check for any errors */
        $required_fields = ['gtin', 'target_url'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        $_POST['gtin'] = input_clean($_POST['gtin']);
        $_POST['target_url'] = input_clean($_POST['target_url']);
        $_POST['title'] = input_clean($_POST['title'] ?? '');
        $_POST['description'] = input_clean($_POST['description'] ?? '');
        $_POST['domain_id'] = isset($_POST['domain_id']) && is_numeric($_POST['domain_id']) ? (int) $_POST['domain_id'] : 0;
        $_POST['project_id'] = !empty($_POST['project_id']) && is_numeric($_POST['project_id']) ? (int) $_POST['project_id'] : null;
        $_POST['pixels_ids'] = array_map(
            function($pixel_id) {
                return (int) $pixel_id;
            },
            array_filter($_POST['pixels_ids'] ?? [], function($pixel_id) {
                return is_numeric($pixel_id);
            })
        );
        $_POST['utm_source'] = input_clean($_POST['utm_source'] ?? '');
        $_POST['utm_medium'] = input_clean($_POST['utm_medium'] ?? '');
        $_POST['utm_campaign'] = input_clean($_POST['utm_campaign'] ?? '');
        $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

        /* Validate GTIN */
        if(!validate_gtin($_POST['gtin'])) {
            Response::json(l('gs1_links.error_message.invalid_gtin'), 'error');
        }

        /* Format GTIN to 14 digits */
        $_POST['gtin'] = format_gtin($_POST['gtin']);

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? 0);

        /* Existing GTIN check */
        if(Database::$database->query("SELECT `gs1_link_id` FROM `gs1_links` WHERE `gtin` = '{$_POST['gtin']}' AND `domain_id` = {$domain_id} AND `user_id` = {$user->user_id}")->num_rows) {
            Response::json(l('gs1_links.error_message.gtin_exists'), 'error');
        }

        /* Check for duplicate pixels */
        $_POST['pixels_ids'] = array_unique($_POST['pixels_ids']);

        /* Check pixels limit */
        if(count($_POST['pixels_ids']) > $user->plan_settings->pixels_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Verify pixels ownership */
        foreach($_POST['pixels_ids'] as $pixel_id) {
            if(!$pixel = Database::$database->query("SELECT `pixel_id` FROM `pixels` WHERE `pixel_id` = {$pixel_id} AND `user_id` = {$user->user_id}")->fetch_object()) {
                Response::json(l('global.error_message.invalid_credentials'), 'error');
            }
        }

        /* Project check */
        if($_POST['project_id'] && !$project = Database::$database->query("SELECT `project_id` FROM `projects` WHERE `project_id` = {$_POST['project_id']} AND `user_id` = {$user->user_id}")->fetch_object()) {
            $_POST['project_id'] = null;
        }

        /* Prepare the settings */
        $utm = [];
        if(!empty($_POST['utm_source'])) $utm['source'] = $_POST['utm_source'];
        if(!empty($_POST['utm_medium'])) $utm['medium'] = $_POST['utm_medium'];
        if(!empty($_POST['utm_campaign'])) $utm['campaign'] = $_POST['utm_campaign'];

        $settings = [
            'utm' => $utm,
        ];

        $settings = json_encode($settings);
        $pixels_ids = json_encode($_POST['pixels_ids']);

        /* Database query */
        $gs1_link_id = Database::$database->insert('gs1_links', [
            'user_id' => $user->user_id,
            'project_id' => $_POST['project_id'],
            'domain_id' => $domain_id,
            'pixels_ids' => $pixels_ids,
            'gtin' => $_POST['gtin'],
            'target_url' => $_POST['target_url'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'settings' => $settings,
            'is_enabled' => $_POST['is_enabled'],
            'datetime' => \Altum\Date::$date,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('gs1_link_id=' . $gs1_link_id);

        /* Prepare the data */
        $data = [
            'id' => $gs1_link_id
        ];

        Response::json($data, 'success', $_POST);
    }

    public function patch() {

        Authentication::guard('api');

        $gs1_link_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $gs1_link = Database::$database->query("SELECT * FROM `gs1_links` WHERE `gs1_link_id` = {$gs1_link_id}")->fetch_object();

        /* Check if the resource exists */
        if(!$gs1_link) {
            Response::json(l('global.error_message.invalid_id'), 'error');
        }

        $user = (new User())->get_user_by_user_id($gs1_link->user_id);

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Check if the user owns the resource */
        if($gs1_link->user_id != $user->user_id) {
            Response::json(l('global.error_message.invalid_credentials'), 'error');
        }

        $_POST['gtin'] = input_clean($_POST['gtin'] ?? $gs1_link->gtin);
        $_POST['target_url'] = input_clean($_POST['target_url'] ?? $gs1_link->target_url);
        $_POST['title'] = input_clean($_POST['title'] ?? $gs1_link->title);
        $_POST['description'] = input_clean($_POST['description'] ?? $gs1_link->description);
        $_POST['domain_id'] = isset($_POST['domain_id']) && is_numeric($_POST['domain_id']) ? (int) $_POST['domain_id'] : $gs1_link->domain_id;
        $_POST['project_id'] = isset($_POST['project_id']) && is_numeric($_POST['project_id']) ? (int) $_POST['project_id'] : $gs1_link->project_id;
        $_POST['pixels_ids'] = isset($_POST['pixels_ids']) ? array_map(
            function($pixel_id) {
                return (int) $pixel_id;
            },
            array_filter($_POST['pixels_ids'], function($pixel_id) {
                return is_numeric($pixel_id);
            })
        ) : json_decode($gs1_link->pixels_ids ?? '[]');
        $_POST['utm_source'] = input_clean($_POST['utm_source'] ?? '');
        $_POST['utm_medium'] = input_clean($_POST['utm_medium'] ?? '');
        $_POST['utm_campaign'] = input_clean($_POST['utm_campaign'] ?? '');
        $_POST['is_enabled'] = isset($_POST['is_enabled']) ? (int) $_POST['is_enabled'] : $gs1_link->is_enabled;

        /* Validate GTIN */
        if(!validate_gtin($_POST['gtin'])) {
            Response::json(l('gs1_links.error_message.invalid_gtin'), 'error');
        }

        /* Format GTIN to 14 digits */
        $_POST['gtin'] = format_gtin($_POST['gtin']);

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? 0);

        /* Existing GTIN check */
        if($_POST['gtin'] != $gs1_link->gtin && Database::$database->query("SELECT `gs1_link_id` FROM `gs1_links` WHERE `gtin` = '{$_POST['gtin']}' AND `domain_id` = {$domain_id} AND `user_id` = {$user->user_id}")->num_rows) {
            Response::json(l('gs1_links.error_message.gtin_exists'), 'error');
        }

        /* Check for duplicate pixels */
        $_POST['pixels_ids'] = array_unique($_POST['pixels_ids']);

        /* Check pixels limit */
        if(count($_POST['pixels_ids']) > $user->plan_settings->pixels_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Verify pixels ownership */
        foreach($_POST['pixels_ids'] as $pixel_id) {
            if(!$pixel = Database::$database->query("SELECT `pixel_id` FROM `pixels` WHERE `pixel_id` = {$pixel_id} AND `user_id` = {$user->user_id}")->fetch_object()) {
                Response::json(l('global.error_message.invalid_credentials'), 'error');
            }
        }

        /* Project check */
        if($_POST['project_id'] && !$project = Database::$database->query("SELECT `project_id` FROM `projects` WHERE `project_id` = {$_POST['project_id']} AND `user_id` = {$user->user_id}")->fetch_object()) {
            $_POST['project_id'] = null;
        }

        /* Parse current settings */
        $gs1_link->settings = json_decode($gs1_link->settings ?? '');

        /* Prepare the settings */
        $utm = [];
        if(!empty($_POST['utm_source'])) $utm['source'] = $_POST['utm_source'];
        if(!empty($_POST['utm_medium'])) $utm['medium'] = $_POST['utm_medium'];
        if(!empty($_POST['utm_campaign'])) $utm['campaign'] = $_POST['utm_campaign'];

        $settings = [
            'utm' => $utm,
        ];

        $settings = json_encode($settings);
        $pixels_ids = json_encode($_POST['pixels_ids']);

        /* Database query */
        Database::$database->query("UPDATE `gs1_links` SET `project_id` = {$_POST['project_id']}, `domain_id` = {$domain_id}, `pixels_ids` = '{$pixels_ids}', `gtin` = '{$_POST['gtin']}', `target_url` = '{$_POST['target_url']}', `title` = '{$_POST['title']}', `description` = '{$_POST['description']}', `settings` = '{$settings}', `is_enabled` = {$_POST['is_enabled']}, `last_datetime` = '{$gs1_link->last_datetime}' WHERE `gs1_link_id` = {$gs1_link->gs1_link_id}");

        /* Clear the cache */
        cache()->deleteItemsByTag('gs1_link_id=' . $gs1_link_id);

        /* Prepare the data */
        $data = [
            'id' => $gs1_link->gs1_link_id
        ];

        Response::json($data, 'success', $_POST);
    }

    public function delete() {

        Authentication::guard('api');

        $gs1_link_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $gs1_link = Database::$database->query("SELECT * FROM `gs1_links` WHERE `gs1_link_id` = {$gs1_link_id}")->fetch_object();

        /* Check if the resource exists */
        if(!$gs1_link) {
            Response::json(l('global.error_message.invalid_id'), 'error');
        }

        $user = (new User())->get_user_by_user_id($gs1_link->user_id);

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Check if the user owns the resource */
        if($gs1_link->user_id != $user->user_id) {
            Response::json(l('global.error_message.invalid_credentials'), 'error');
        }

        /* Delete the gs1_link */
        Database::$database->query("DELETE FROM `gs1_links` WHERE `gs1_link_id` = {$gs1_link->gs1_link_id}");

        /* Clear cache */
        cache()->deleteItemsByTag('gs1_link_id=' . $gs1_link_id);

        Response::json('', 'success');

    }

    private function get_domain_id($domain_id) {
        $user = $this->get_user();

        if($domain_id == 0) {
            return 0;
        }

        $domain = Database::$database->query("SELECT `domain_id` FROM `domains` WHERE `domain_id` = {$domain_id} AND `user_id` = {$user->user_id} AND `is_enabled` = 1")->fetch_object();

        return $domain ? $domain->domain_id : 0;
    }

}
