<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Traits;

defined('SEEGAP') || die();

trait Paramsable {

    /* Function used by the base model, controller and view */
    public function add_params(Array $params = []) {

        /* Make the params available to the Controller */
        foreach($params as $key => $value) {

            /* Make sure they are not main keys */
            if(in_array($key, ['view', 'view_path'])) continue;

            @$this->{$key} = $value;
        }

    }

}
