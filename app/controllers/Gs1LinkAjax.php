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
use SeeGap\Response;

defined('SEEGAP') || die();

class Gs1LinkAjax extends Controller {

    public function index() {
        \SeeGap\Authentication::guard();

        /* Check if GS1 links feature is enabled */
        if(!settings()->gs1_links->gs1_links_is_enabled) {
            die();
        }

        if(!empty($_POST) && (\SeeGap\Csrf::check('token') || \SeeGap\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

                /* Duplicate */
                case 'duplicate': $this->duplicate(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('update.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['gs1_link_id'] = (int) $_POST['gs1_link_id'];

        /* Get the current status */
        $gs1_link = db()->where('gs1_link_id', $_POST['gs1_link_id'])->where('user_id', $this->user->user_id)->getOne('gs1_links', ['gs1_link_id', 'is_enabled']);

        if($gs1_link) {
            $new_is_enabled = (int) !$gs1_link->is_enabled;

            db()->where('gs1_link_id', $gs1_link->gs1_link_id)->update('gs1_links', ['is_enabled' => $new_is_enabled]);

            /* Clear the cache */
            cache()->deleteItem('gs1_link?gs1_link_id=' . $_POST['gs1_link_id']);
            cache()->deleteItemsByTag('gs1_link_id=' . $_POST['gs1_link_id']);

            Response::json(l('global.success_message.create2'), 'success');
        }
    }

    private function delete() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('delete.gs1_links')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['gs1_link_id'] = (int) $_POST['gs1_link_id'];

        /* Check for possible errors */
        if(!$gs1_link = db()->where('gs1_link_id', $_POST['gs1_link_id'])->where('user_id', $this->user->user_id)->getOne('gs1_links', ['gs1_link_id'])) {
            die();
        }

        (new \SeeGap\Models\Gs1Link())->delete($gs1_link->gs1_link_id);

        Response::json(l('global.success_message.delete2'), 'success', ['url' => url('gs1-links')]);
    }

    public function duplicate() {
        /* Team checks */
        if(\SeeGap\Teams::is_delegated() && !\SeeGap\Teams::has_access('create.gs1_links')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('gs1-links');
        }

        $_POST['gs1_link_id'] = (int) $_POST['gs1_link_id'];

        //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\SeeGap\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('gs1-links');
        }

        /* Get the gs1_link data */
        $gs1_link = db()->where('gs1_link_id', $_POST['gs1_link_id'])->where('user_id', $this->user->user_id)->getOne('gs1_links');

        if(!$gs1_link) {
            redirect('gs1-links');
        }

        /* Make sure that the user didn't exceed the limit */
        $user_total_gs1_links = database()->query("SELECT COUNT(*) AS `total` FROM `gs1_links` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;
        if($this->user->plan_settings->gs1_links_limit != -1 && $user_total_gs1_links >= $this->user->plan_settings->gs1_links_limit) {
            Alerts::add_error(l('global.info_message.plan_feature_limit'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Duplicate the gs1_link */
            $gs1_link->settings = json_decode($gs1_link->settings ?? '');

            /* Generate a new GTIN by incrementing the last digit (keeping check digit valid) */
            $new_gtin = $gs1_link->gtin;
            $base_gtin = substr($new_gtin, 0, 12); // Get first 12 digits
            $sequence = (int) substr($base_gtin, -1); // Get last digit of base
            
            // Try incrementing sequence until we find an available GTIN
            $attempts = 0;
            do {
                $sequence = ($sequence + 1) % 10;
                $new_base = substr($base_gtin, 0, 11) . $sequence;
                $new_gtin = \SeeGap\Helpers\Gs1::calculate_gtin_check_digit($new_base);
                $attempts++;
                
                // If we've tried all possibilities, generate a random GTIN
                if($attempts >= 10) {
                    $new_gtin = \SeeGap\Helpers\Gs1::generate_random_gtin();
                    break;
                }
            } while (db()->where('gtin', $new_gtin)->where('domain_id', $gs1_link->domain_id)->getValue('gs1_links', 'gs1_link_id'));

            /* Database query */
            $gs1_link_id = db()->insert('gs1_links', [
                'user_id' => $this->user->user_id,
                'project_id' => $gs1_link->project_id,
                'domain_id' => $gs1_link->domain_id,
                'pixels_ids' => $gs1_link->pixels_ids,
                'gtin' => $new_gtin,
                'target_url' => $gs1_link->target_url,
                'title' => $gs1_link->title . ' (Copy)',
                'description' => $gs1_link->description,
                'settings' => json_encode($gs1_link->settings),
                'is_enabled' => $gs1_link->is_enabled,
                'datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItem('gs1_links_total?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.create2'));

            /* Redirect */
            redirect('gs1-link-manager/edit/' . $gs1_link_id);
        }

        redirect('gs1-links');
    }
}
