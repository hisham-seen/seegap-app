<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('ALTUMCODE') || die();

return array_merge(
    require APP_PATH . 'includes/tools/checker_tools.php',
    require APP_PATH . 'includes/tools/text_tools.php',
    require APP_PATH . 'includes/tools/converter_tools.php',
    require APP_PATH . 'includes/tools/generator_tools.php',
    require APP_PATH . 'includes/tools/developer_tools.php',
    require APP_PATH . 'includes/tools/image_manipulation_tools.php',
    require APP_PATH . 'includes/tools/misc_tools.php',
    require APP_PATH . 'includes/tools/time_converter_tools.php',
);

