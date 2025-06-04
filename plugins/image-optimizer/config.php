<?php
defined('SEEGAP') || die();

return (object) [
    'plugin_id' => 'image-optimizer',
    'name' => 'Image optimizer',
    'description' => 'This plugin is used to compress and reduce the size of image file uploads for better performance & size reduction.',
    'version' => '2.0.0',
    'url' => 'https://Seegap.com/image-optimizer-plugin',
    'author' => 'SeeGap',
    'author_url' => 'https://Seegap.com/',
    'status' => 'inexistent',
    'actions'=> true,
    'settings_url' => url('admin/settings/image_optimizer'),
    'avatar_style' => 'background: #e0eafc; background: -webkit-linear-gradient(to right, #e0eafc, #cfdef3); background: linear-gradient(to right, #e0eafc, #cfdef3);',
    'icon' => '📸',
];
