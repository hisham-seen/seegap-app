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

class AdminApiDocumentation extends Controller {

    public function index() {

        /* Prepare the view */
        $view = new \Altum\View('admin/api-documentation/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}


