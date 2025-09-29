<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // Login
    'login.menu' => 'Sign in',
    'login.title' => 'Sign in',
    'login.header' => 'Sign in with Email',
    'login.subheader' => 'Enter your email address and we\'ll send you a secure login link.',
    'login.email_placeholder' => 'Enter your email address',
    'login.send_login_link' => 'Send Login Link',
    'login.register' => 'Don\'t have an account? %s',
    'login.register_help' => 'Register',
    'login.resend_activation' => 'Resend activation',
    'login.info_message.logged_in' => 'Welcome back, %s.',
    'login.success_message.email_sent' => 'Login link sent! Check your email and click the link to sign in.',
    'login.error_message.user_not_active' => 'Your account is not confirmed or banned.',
    'login.error_message.invalid_login_link' => 'This login link is invalid or has already been used.',
    'login.error_message.expired_login_link' => 'This login link has expired. Please request a new one.',
    'login.error_message.too_many_attempts' => 'Too many login attempts. Please try again in %d minutes.',

    // Email sent confirmation
    'login.email_sent.title' => 'Check Your Email',
    'login.email_sent.header' => 'Check Your Email',
    'login.email_sent.subheader' => 'We\'ve sent you a secure login link.',
    'login.email_sent.instructions' => 'Click the link in your email to sign in. The link will expire in 15 minutes for security.',
    'login.email_sent.expiry_notice' => 'Login link expires in 15 minutes',
    'login.email_sent.back_to_login' => 'Back to Login',
    'login.email_sent.troubleshooting.header' => 'Didn\'t receive the email?',
    'login.email_sent.troubleshooting.check_spam' => 'Check your spam/junk folder',
    'login.email_sent.troubleshooting.check_email' => 'Make sure you entered the correct email',
    'login.email_sent.troubleshooting.wait_minutes' => 'Wait a few minutes for delivery',

    // Lost password
    'lost_password.title' => 'Lost password',
    'lost_password.header' => 'Lost password',
    'lost_password.subheader' => 'We will send you an email with a magic recovery link to reset your password.',
    'lost_password.return' => 'Return to Login',
    'lost_password.submit' => 'Send me a recovery link',
    'lost_password.success_message' => 'We\'ve emailed you the password reset link.',

    // Resend activation
    'resend_activation.title' => 'Resend activation',
    'resend_activation.header' => 'Resend activation email',
    'resend_activation.subheader' => 'Mails can get lost, but we can send you another activation email for your account.',
    'resend_activation.return' => 'Return to Login',
    'resend_activation.submit' => 'Send me the activation email',
    'resend_activation.success_message' => 'We\'ve emailed you the activation link.',

    // Reset password
    'reset_password.title' => 'Set a new password',
    'reset_password.header' => 'Set a new password',
    'reset_password.subheader' => 'For better security, make sure your new password is strong.',
    'reset_password.return' => 'Return to Login',
    'reset_password.new_password' => 'New password',
    'reset_password.repeat_password' => 'Repeat your new password',
    'reset_password.submit' => 'Set password',
    'reset_password.success_message' => 'Your new password is set.',

    // Activate user
    'activate_user.user_activation' => 'Your account has been confirmed and is now active.',
    'activate_user.user_pending_email' => 'Your new email address has been confirmed.',

    // Register
    'register.title' => 'Sign up',
    'register.menu' => 'Sign up',
    'register.header' => 'Sign up',
    'register.repeat_password' => 'Repeat Password',
    'register.accept' => 'I confirm that I have read and understood the %1$s and %2$s of the site.',
    'register.is_newsletter_subscribed' => 'I agree receive a few emails per month from the newsletter. You can unsubscribe at any time.',
    'register.register' => 'Register',
    'register.login' => 'Already have an account? %s',
    'register.login_help' => 'Sign in',
    'register.error_message.name_length' => 'Name must be between 1 and 64 characters.',
    'register.error_message.email_exists' => 'This email address is already in use.',
    'register.error_message.blacklisted_domain' => 'This email domain has been blacklisted.',
    'register.error_message.blacklisted_country' => 'Your country has been blacklisted.',
    'register.success_message.registration' => 'We\'ve emailed you the activation link.',
    'register.success_message.login' => 'Welcome to our platform, we are grateful to have you here.',

    // Logout
    'logout' => 'Logout',
];
