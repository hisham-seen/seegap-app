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

class AccountApi extends Controller {

    public function index() {

        \SeeGap\Authentication::guard();

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $api_key = md5($this->user->email . microtime() . microtime());

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('user_id', $this->user->user_id)->update('users', ['api_key' => $api_key]);

                /* Set a nice success message */
                Alerts::add_success(l('account_api.success_message'));

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                redirect('account-api');
            }

        }

        /* Get the account header menu */
        $menu = new \SeeGap\View('partials/account_header_menu', (array) $this);
        $this->add_view_content('account_header_menu', $menu->run());

        /* Prepare the view */
        $view = new \SeeGap\View('account-api/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}
