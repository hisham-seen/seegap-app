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
use SeeGap\Captcha;

defined('SEEGAP') || die();

class Feedback extends Controller {

    public function index() {

        if(!settings()->email_notifications->feedback || empty(settings()->email_notifications->emails)) {
            redirect('not-found');
        }

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['email'] = mb_substr(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 0, 320);
            $_POST['subject'] = input_clean($_POST['subject'], 128);
            $_POST['message'] = input_clean($_POST['message'], 2048);

            //SEEGAP:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name', 'email', 'subject', 'message'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\SeeGap\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(settings()->captcha->feedback_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Prepare the email */
                $email_template = get_email_template(
                    [
                        '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                        '{{SUBJECT}}' => $_POST['subject'],
                    ],
                    l('global.emails.admin_feedback.subject'),
                    [
                        '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                        '{{EMAIL}}' => $_POST['email'],
                        '{{MESSAGE}}' => $_POST['message'],
                    ],
                    l('global.emails.admin_feedback.body')
                );

                send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body, [], $_POST['email']);

                /* Send webhook notification if needed */
                if(settings()->webhooks->feedback) {
                    fire_and_forget('post', settings()->webhooks->feedback, [
                        'name' => $_POST['name'],
                        'email' => $_POST['email'],
                        'subject' => $_POST['subject'],
                        'message' => $_POST['message'],
                        'datetime' => get_date(),
                    ]);
                }

                /* Set a nice success message */
                Alerts::add_success(l('feedback.success_message'));

                redirect('feedback');
            }
        }

        $values = [
            'name' => is_logged_in() ? $this->user->name : ($_POST['name'] ??  ''),
            'email' => is_logged_in() ? $this->user->email : ($_POST['email'] ??  ''),
            'subject' => $_POST['subject'] ?? $_GET['subject'] ?? '',
            'message' => $_POST['message'] ?? $_GET['message'] ?? '',
        ];

        /* Prepare the view */
        $data = [
            'captcha' => $captcha,
            'values' => $values,
        ];

        $view = new \SeeGap\View('feedback/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}