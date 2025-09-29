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
use SeeGap\Logger;
use SeeGap\Models\User;

defined('SEEGAP') || die();

class Login extends Controller {

    public function index() {

        $method = (isset($this->params[0])) ? $this->params[0] : null;
        $redirect = process_and_get_redirect_params() ?? 'dashboard';
        $redirect_append = $redirect ? '?redirect=' . $redirect : null;

        if($method !== 'email-login') {
            \SeeGap\Authentication::guard('guest');
        }

        /* Default values */
        $values = [
            'email' => isset($_GET['email']) ? query_clean($_GET['email']) : '',
        ];

        /* Initiate captcha */
        $captcha = new Captcha();

        /* Email login link handler */
        if($method == 'email-login') {
            $login_token = isset($this->params[1]) ? query_clean($this->params[1]) : null;

            if(empty($login_token)) {
                Alerts::add_error(l('login.error_message.invalid_login_link'));
                redirect('login' . $redirect_append);
            }

            /* Try to get the user from the database */
            $user = db()->where('login_token', $login_token)->getOne('users', ['user_id', 'name', 'status', 'language', 'login_token_expiry', 'login_token_ip']);

            if(!$user) {
                Alerts::add_error(l('login.error_message.invalid_login_link'));
                redirect('login' . $redirect_append);
            }

            /* Check if token has expired */
            if($user->login_token_expiry && $user->login_token_expiry < get_date()) {
                Alerts::add_error(l('login.error_message.expired_login_link'));
                redirect('login' . $redirect_append);
            }

            /* Optional: Check IP address for extra security */
            if($user->login_token_ip && $user->login_token_ip !== get_ip()) {
                Logger::users($user->user_id, 'login.suspicious_ip_attempt');
                Alerts::add_error(l('login.error_message.invalid_login_link'));
                redirect('login' . $redirect_append);
            }

            if($user->status != 1) {
                Alerts::add_error(l('login.error_message.user_not_active'));
                redirect('login' . $redirect_append);
            }

            /* Get full user data for proper authentication */
            $full_user = (new User())->get_user_by_user_id($user->user_id);
            
            if(!$full_user) {
                Alerts::add_error(l('login.error_message.invalid_login_link'));
                redirect('login' . $redirect_append);
            }

            /* Login the user - set required session variables for Authentication::check() */
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['user_password_hash'] = md5($full_user->password ?? '');
            
            /* Optional: Keep email authenticated flag for compatibility */
            $_SESSION['user_email_authenticated'] = true;

            /* Ensure session data is written immediately */
            session_write_close();
            session_start();

            (new User())->login_aftermath_update($user->user_id);

            /* Clear the login token (one-time use) */
            db()->where('user_id', $user->user_id)->update('users', [
                'login_token' => null,
                'login_token_expiry' => null,
                'login_token_ip' => null
            ]);

            /* Log successful login */
            Logger::users($user->user_id, 'login.email_link_success');

            /* Set a welcome message */
            Alerts::add_info(sprintf(l('login.info_message.logged_in'), $user->name));

            /* Check to see if the user has a custom language set */
            if(\SeeGap\Language::$name == $user->language) {
                redirect($redirect);
            } else {
                redirect((\SeeGap\Language::$active_languages[$user->language] ? \SeeGap\Language::$active_languages[$user->language] . '/' : null) . $redirect, true);
            }
        }

        /* Handle email submission for magic link */
        if(!empty($_POST)) {
            /* Clean email */
            $_POST['email'] = query_clean($_POST['email']);
            $values['email'] = $_POST['email'];

            /* Check for any errors */
            if(!isset($_POST['email']) || empty($_POST['email'])) {
                Alerts::add_field_error('email', l('global.error_message.empty_field'));
            }

            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                Alerts::add_field_error('email', l('global.error_message.invalid_email'));
            }

            if(settings()->captcha->login_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
            }

            /* Rate limiting for login attempts */
            if(settings()->users->login_lockout_is_enabled) {
                $minutes_ago_datetime = (new \DateTime())->modify('-' . settings()->users->login_lockout_time . ' minutes')->format('Y-m-d H:i:s');

                $recent_attempts = db()->where('ip', get_ip())->where('type', 'login.email_attempt')->where('datetime', $minutes_ago_datetime, '>=')->getValue('users_logs', 'COUNT(*)');

                if($recent_attempts >= settings()->users->login_lockout_max_retries) {
                    Alerts::add_error(sprintf(l('global.error_message.limit_try_again'), settings()->users->login_lockout_time, l('global.date.minutes')));
                    setcookie('login_lockout', 'true', time()+60*settings()->users->login_lockout_time, COOKIE_PATH);
                    $_COOKIE['login_lockout'] = 'true';
                }
            }

            /* Try to get the user from the database */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $user = db()->where('email', $_POST['email'])->getOne('users', ['user_id', 'email', 'name', 'status', 'language', 'login_attempts', 'login_attempts_datetime']);

                if(!$user) {
                    /* Don't reveal if email exists or not for security */
                    Logger::users(null, 'login.email_not_found', ['email' => $_POST['email']]);
                } else {
                    if($user->status != 1) {
                        Alerts::add_error(l('login.error_message.user_not_active'));
                    } else {
                        /* Check user-specific rate limiting */
                        $user_rate_limit_time = 60; // 1 hour in minutes
                        $user_rate_limit_attempts = 5;
                        
                        if($user->login_attempts_datetime) {
                            $last_attempt_time = new \DateTime($user->login_attempts_datetime);
                            $current_time = new \DateTime();
                            $time_diff = $current_time->getTimestamp() - $last_attempt_time->getTimestamp();
                            
                            if($time_diff < ($user_rate_limit_time * 60) && $user->login_attempts >= $user_rate_limit_attempts) {
                                $remaining_minutes = ceil(($user_rate_limit_time * 60 - $time_diff) / 60);
                                Alerts::add_error(sprintf(l('login.error_message.too_many_attempts'), $remaining_minutes));
                            }
                        }
                    }
                }
            }

            /* Generate and send login link */
            if(!Alerts::has_field_errors() && !Alerts::has_errors() && $user && $user->status == 1) {
                /* Generate secure login token */
                $login_token = bin2hex(random_bytes(32));
                $login_token_expiry = (new \DateTime())->modify('+15 minutes')->format('Y-m-d H:i:s');
                $current_ip = get_ip();

                /* Update user with login token */
                db()->where('user_id', $user->user_id)->update('users', [
                    'login_token' => $login_token,
                    'login_token_expiry' => $login_token_expiry,
                    'login_token_ip' => $current_ip,
                    'login_attempts' => ($user->login_attempts ?? 0) + 1,
                    'login_attempts_datetime' => get_date()
                ]);

                /* Generate login URL */
                $login_url = SITE_URL . 'login/email-login/' . $login_token;

                /* Get user's browser and device info for security */
                $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
                $browser_info = $this->parse_user_agent($user_agent);

                /* Prepare email template with customizable templates */
                $template_variables = [
                    '{{USER_NAME}}' => $user->name,
                    '{{USER_EMAIL}}' => $user->email,
                    '{{USER_IP}}' => $current_ip,
                    '{{USER_DEVICE}}' => $browser_info['browser'] . ' on ' . $browser_info['device'],
                    '{{SITE_TITLE}}' => settings()->main->title,
                    '{{SITE_URL}}' => SITE_URL,
                    '{{LOGIN_LINK}}' => $login_url,
                    '{{SECURITY_CODE}}' => $user->anti_phishing_code ?? strtoupper(substr(md5($user->email . time()), 0, 6)),
                ];

                /* Use custom email templates if available, otherwise fall back to defaults */
                $email_subject = settings()->email_templates->login_subject ?? l('emails.login.subject');
                $email_body = settings()->email_templates->login_body ?? l('emails.login.body');

                /* Replace template variables */
                foreach($template_variables as $variable => $value) {
                    $email_subject = str_replace($variable, $value, $email_subject);
                    $email_body = str_replace($variable, $value, $email_body);
                }

                $email_template = (object) [
                    'subject' => $email_subject,
                    'body' => $email_body
                ];

                /* Send the email */
                send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code ?? '', 'language' => $user->language]);

                /* Log the attempt */
                Logger::users($user->user_id, 'login.email_attempt');

                /* Show success message (same for existing and non-existing emails for security) */
                Alerts::add_success(l('login.success_message.email_sent'));
            } else if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                /* Show the same success message even if email doesn't exist (security) */
                Alerts::add_success(l('login.success_message.email_sent'));
            }
        }

        /* Prepare the view */
        $data = [
            'captcha' => $captcha,
            'values' => $values,
            'redirect_append' => $redirect_append,
        ];

        $view = new \SeeGap\View('login/index', (array) $this);

        $this->add_view_content('content', $view->run($data));
    }

    /* Parse user agent for security information */
    private function parse_user_agent($user_agent) {
        $browser = 'Unknown Browser';
        $device = 'Unknown Device';

        /* Simple browser detection */
        if (strpos($user_agent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($user_agent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($user_agent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($user_agent, 'Edge') !== false) {
            $browser = 'Edge';
        }

        /* Simple device detection */
        if (strpos($user_agent, 'Mobile') !== false || strpos($user_agent, 'Android') !== false) {
            $device = 'Mobile Device';
        } elseif (strpos($user_agent, 'iPad') !== false || strpos($user_agent, 'Tablet') !== false) {
            $device = 'Tablet';
        } else {
            $device = 'Desktop Computer';
        }

        return [
            'browser' => $browser,
            'device' => $device
        ];
    }
}
