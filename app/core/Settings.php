<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap;

defined('SEEGAP') || die();

class Settings {
    public static $settings = null;

    public static function initialize() {

        self::$settings = (new \SeeGap\Models\Settings())->get();

    }

    public static function get() {
        return self::$settings;
    }
}
