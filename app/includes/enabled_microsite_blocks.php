<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('SEEGAP') || die();

// Return the enabled microsite blocks
$microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php';
return $microsite_blocks;
