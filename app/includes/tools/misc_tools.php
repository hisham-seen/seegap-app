<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('ALTUMCODE') || die();

return [
    'youtube_thumbnail_downloader' => [
        'icon' => 'fab fa-youtube'
    ],

    'qr_code_reader' => [
        'icon' => 'fas fa-qrcode',
        'similar' => [
            'barcode_reader',
        ]
    ],

    'barcode_reader' => [
        'icon' => 'fas fa-barcode',
        'similar' => [
            'qr_code_reader',
        ]
    ],

    'exif_reader' => [
        'icon' => 'fas fa-camera',
        'similar' => [
            'qr_code_reader',
        ]
    ],

    'color_picker' => [
        'icon' => 'fas fa-palette'
    ],
];
