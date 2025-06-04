<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\controllers;

use SeeGap\Alerts;
use SeeGap\Captcha;
use SeeGap\Logger;

defined('SEEGAP') || die();

class Maintenance extends Controller {

    public function index() {

        if(!settings()->main->maintenance_is_enabled) {
            redirect();
        }

        header('HTTP/1.1 503 Service Unavailable');
        header('Retry-After: 3600');

        /* Prepare the view */
        $data = [];

        $view = new \SeeGap\View('maintenance/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
