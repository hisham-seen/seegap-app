<?php
defined('SEEGAP') || die();

return (object) [
    'plugin_id' => 'aix',
    'name' => 'AIX - Your AI Assistant',
    'description' => 'This plugin implements the OpenAI API system for content writing, image generation, text to speech, speech to text & assistant chat functionality.',
    'version' => '10.0.0',
    'url' => 'https://Seegap.com/aix-plugin',
    'author' => 'SeeGap',
    'author_url' => 'https://Seegap.com/',
    'status' => 'inexistent',
    'actions'=> true,
    'settings_url' => url('admin/settings/aix'),
    'avatar_style' => 'background: #4CA1AF;background: -webkit-linear-gradient(to right, #C4E0E5, #4CA1AF); background: linear-gradient(to right, #C4E0E5, #4CA1AF);',
    'icon' => '🤖',
];
