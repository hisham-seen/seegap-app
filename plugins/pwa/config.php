<?php
defined('SEEGAP') || die();

return (object) [
    'plugin_id' => 'pwa',
    'name' => 'PWA system',
    'description' => 'The PWA plugin makes your whole site basic PWA compatible.',
    'version' => '4.0.0',
    'url' => 'https://Seegap.com/pwa-plugin',
    'author' => 'SeeGap',
    'author_url' => 'https://Seegap.com/',
    'status' => 'inexistent',
    'actions'=> true,
    'settings_url' => url('admin/settings/pwa'),
    'avatar_style' => 'background: #ff936a;background: -webkit-linear-gradient(top right, #ff936a, #ff15c1);background: -moz-linear-gradient(top right, #ff936a, #ff15c1);background: linear-gradient(to bottom left, #ff936a, #ff15c1);',
    'icon' => '🚀',
];
