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
    'biolink' => [
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
    'vcard' => [
        'icon' => 'fas fa-id-card',
        'color' => '#06b6d4',
        'fields' => [
            'first_name' => [
                'max_length' => 64,
            ],
            'last_name' => [
                'max_length' => 64,
            ],
            'email' => [
                'max_length' => 320,
            ],
            'url' => [
                'max_length' => 1024,
            ],
            'company' => [
                'max_length' => 64,
            ],
            'job_title' => [
                'max_length' => 64,
            ],
            'birthday' => [
                'max_length' => 16,
            ],
            'street' => [
                'max_length' => 128,
            ],
            'city' => [
                'max_length' => 64,
            ],
            'zip' => [
                'max_length' => 32,
            ],
            'region' => [
                'max_length' => 32,
            ],
            'country' => [
                'max_length' => 32,
            ],
            'note' => [
                'max_length' => 512,
            ],
            'phone_number_label' => [
                'max_length' => 32,
            ],
            'phone_number_value' => [
                'max_length' => 32,
            ],
            'social_label' => [
                'max_length' => 32
            ],
            'social_value' => [
                'max_length' => 1024
            ]
        ]
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
