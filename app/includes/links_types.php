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
    'link' => [
        'icon' => 'fas fa-link',
        'color' => '#14b8a6',
    ],
    'microsite' => [
        'icon' => 'fas fa-fw fa-hashtag',
        'color' => '#3b82f6',
    ],
    'file' => [
        'icon' => 'fas fa-file',
        'color' => '#10b981',
    ],
    'static' => [
        'icon' => 'fas fa-file-code',
        'color' => '#fb972e',
    ],
    'event' => [
        'icon' => 'fas fa-calendar-alt',
        'color' => '#6366f1',
        'fields' => [
            'name' => [
                'max_length' => 128,
            ],
            'note' => [
                'max_length' => 512,
            ],
            'url' => [
                'max_length' => 1024,
            ],
            'location' => [
                'max_length' => 128,
            ],
            'start_datetime' => [],
            'end_datetime' => [],
            'timezone' => [],
        ]
    ],
];
