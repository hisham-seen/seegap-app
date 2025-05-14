<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('ALTUMCODE') || die();

$enabled_biolink_blocks = [];

foreach(require APP_PATH . 'includes/biolink_blocks.php' as $type => $value) {
    if(settings()->links->available_biolink_blocks->{$type}) {
        $enabled_biolink_blocks[$type] = $value;
    }
}

return $enabled_biolink_blocks;
