<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Alerts;

defined('SEEGAP') || die();

class AdminChatAssistantCreate extends Controller {

    public function index() {

        if(!\SeeGap\Plugin::is_active('aix')) {
            redirect('not-found');
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['prompt'] = input_clean($_POST['prompt'], 5000);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            /* Translations */
            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    $_POST['translations'][$language_name][$key] = input_clean($value);
                }
                if(!array_key_exists($language_name, \SeeGap\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            /* Prepare settings JSON */
            $settings = json_encode([
                'translations' => $_POST['translations'],
            ]);

            $image = \SeeGap\Uploads::process_upload(null, 'chats_assistants', 'image', 'image_remove', null);

            //SEEGAP:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('chats_assistants', [
                    'name' => $_POST['name'],
                    'prompt' => $_POST['prompt'],
                    'settings' => $settings,
                    'image' => $image,
                    'order' => $_POST['order'],
                    'is_enabled' => $_POST['is_enabled'],
                    'datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('chats_assistants');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/chats-assistants');
            }
        }

        $values = [
            'name' => $_POST['name'] ?? null,
            'prompt' => $_POST['prompt'] ?? null,
            'translations' => $_POST['translations'] ?? null,
            'order' => $_POST['order'] ?? 0,
            'is_enabled' => $_POST['is_enabled'] ?? 1,
        ];

        /* Main View */
        $data = [
            'values' => $values,
        ];

        $view = new \SeeGap\View('admin/chat-assistant-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
