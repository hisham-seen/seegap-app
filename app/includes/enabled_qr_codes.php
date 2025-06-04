<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('SEEGAP') || die();

$enabled_qr_codes = [];

foreach(require APP_PATH . 'includes/qr_codes.php' as $type => $value) {
    if(settings()->codes->available_qr_codes->{$type}) {
        $enabled_qr_codes[$type] = $value;
    }
}

return $enabled_qr_codes;
