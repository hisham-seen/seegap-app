<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('SEEGAP') || die();

$enabled_microsite_blocks = [];

foreach(require APP_PATH . 'includes/microsite_blocks.php' as $type => $value) {
    if(settings()->links->available_microsite_blocks->{$type}) {
        $enabled_microsite_blocks[$type] = $value;
    }
}

return $enabled_microsite_blocks;
