<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap;

use SeeGap\Traits\Paramsable;

defined('SEEGAP') || die();

class View {
    use Paramsable;

    public $view;
    public $view_path;

    public function __construct($view, Array $params = [], $is_full_path = false) {

        $this->view = $view;
        $this->view_path = $is_full_path ? $view . '.php' : THEME_PATH . 'views/' . $view . '.php';

        $this->add_params($params);

    }

    public function run($data = []) {

        $data = (object) $data;

        ob_start();

        require $this->view_path;

        return ob_get_clean();
    }

}
