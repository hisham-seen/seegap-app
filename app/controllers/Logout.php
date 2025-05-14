<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers;


defined('ALTUMCODE') || die();

class Logout extends Controller {

    public function index() {

        /* Exit admin impersonation */
        if(isset($_GET['admin_impersonate_user'])) {
            $admin_user_id = $_SESSION['admin_user_id'];

            /* Logout of the current users */
            \Altum\Authentication::logout(false);

            $admin_user = db()->where('user_id', $admin_user_id)->getOne('users', ['user_id', 'password']);

            if($admin_user) {
                /* Login as the admin */
                session_start();
                $_SESSION['user_id'] = $admin_user_id;
                $_SESSION['user_password_hash'] = md5($admin_user->password);
            }

            redirect('admin/users');
        }

        /* Exit team delegated access */
        else if(isset($_GET['team'])) {
            unset($_SESSION['team_id']);
            redirect('teams-member');
        }

        /* Normal logout */
        else {
            \Altum\Authentication::logout();
        }

    }

}
