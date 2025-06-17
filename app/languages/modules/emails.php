<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // Email common elements
    'global.emails.copyright' => 'Copyright © %1$s %2$s.',
    'global.emails.anti_phishing_code' => 'Anti phishing code: %s',
    'global.emails.is_broadcast' => 'You are receiving this email because you have subscribed to our newsletter broadcasts. You can %sUnsubscribe here%s at any point in time.',

    // User welcome email
    'global.emails.user_welcome.subject' => 'Welcome to {{WEBSITE_TITLE}}',
    'global.emails.user_welcome.body' => 'Hello, <strong>{{NAME}}</strong>!<br /><br />Welcome to our website community, we\'re excited to have you on board!<br /><br />Get started by visiting our <a href="{{URL}}">homepage</a> or your <a href="{{DASHBOARD_LINK}}">dashboard</a>.<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // User activation email
    'global.emails.user_activation.subject' => 'Confirm your new account - {{WEBSITE_TITLE}}',
    'global.emails.user_activation.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />We are glad you joined us! <br /><br />One more step and your account is ready,<br /><br />Click the link below in order to join <strong>{{WEBSITE_TITLE}}</strong><br /><br /><a href="{{ACTIVATION_LINK}}" class="cta">Activate account</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // User pending email
    'global.emails.user_pending_email.subject' => 'Confirm your email address change - {{WEBSITE_TITLE}}',
    'global.emails.user_pending_email.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You have requested to change your email address from {{CURRENT_EMAIL}} to {{NEW_EMAIL}}. <br /><br />Please click on the link below to confirm your new email address. <br /><br /><a href="{{ACTIVATION_LINK}}" class="cta">Confirm email address change</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Lost password email
    'global.emails.user_lost_password.subject' => 'Reset your password - {{WEBSITE_TITLE}}',
    'global.emails.user_lost_password.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is your reset password link:<br /><br /><a href="{{LOST_PASSWORD_LINK}}" class="cta">Reset password</a><br /><br />If you did not request this, you can ignore it.<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Payment confirmation email
    'global.emails.user_payment.subject' => 'Payment received - {{WEBSITE_TITLE}}',
    'global.emails.user_payment.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is a confirmation that your payment has been received by us.<br /><br />You\'ve paid for the <strong>{{PLAN_NAME}}</strong> plan until <strong>{{PLAN_EXPIRATION_DATE}}</strong>.<br /><br />You can also check all the <a href="{{USER_PAYMENTS_LINK}}">payments</a> you made, <a href="{{USER_PLAN_LINK}}">change your plan</a> or <a href="{{USER_PLAN_LINK}}">cancel it</a>.<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Data collected emails
    'global.emails.user_data_collected.subject' => 'New data for {{BLOCK_TITLE}} - {{WEBSITE_TITLE}}',
    'global.emails.user_data_collected_phone_collector.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You just got a new <strong>Phone Collector</strong> data submission from one of your visitors.<br /><br /><strong>Phone:</strong> {{DATA_PHONE}}<br /><strong>Name:</strong> {{DATA_NAME}}<br /><br /><a href="{{DATA_LINK}}">View all collected data</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',
    'global.emails.user_data_collected_email_collector.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You just got a new <strong>Email Collector</strong> data submission from one of your visitors.<br /><br /><strong>Email:</strong> {{DATA_EMAIL}}<br /><strong>Name:</strong> {{DATA_NAME}}<br /><br /><a href="{{DATA_LINK}}">View all collected data</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',
    'global.emails.user_data_collected_contact_collector.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You just got a new <strong>Contact Form</strong> data submission from one of your visitors.<br /><br /><strong>Email:</strong> {{DATA_EMAIL}}<br /><strong>Phone:</strong> {{DATA_PHONE}}<br /><strong>Name:</strong> {{DATA_NAME}}<br /><strong>Message:</strong> {{DATA_MESSAGE}}<br /><br /><a href="{{DATA_LINK}}">View all collected data</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',
    'global.emails.user_data_collected_feedback_collector.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You just got a new <strong>Feedback Form</strong> data submission from one of your visitors.<br /><br /><strong>Email:</strong> {{DATA_EMAIL}}<br /><strong>Phone:</strong> {{DATA_PHONE}}<br /><strong>Name:</strong> {{DATA_NAME}}<br /><strong>Message:</strong> {{DATA_MESSAGE}}<br /><br /><a href="{{DATA_LINK}}">View all collected data</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Guest payment emails
    'global.emails.user_guest_payment_donation.subject' => '{{TOTAL_AMOUNT}} {{CURRENCY}} donation for {{DONATION_TITLE}} - {{WEBSITE_TITLE}}',
    'global.emails.user_guest_payment_donation.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You just got a new donation from one of your visitors.<br /><br /><strong>Email:</strong> {{EMAIL}}<br /><strong>Name:</strong> {{NAME}}<br /><strong>Total amount:</strong> {{TOTAL_AMOUNT}}<br /><strong>Currency:</strong> {{CURRENCY}}<br /><strong>Processor:</strong> {{PROCESSOR}}<br /><strong>Donation:</strong> {{DONATION_TITLE}}<br /><strong>Message:</strong> {{MESSAGE}}<br /><br /><a href="{{GUESTS_PAYMENTS_LINK}}">View all payments</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    'global.emails.user_guest_payment_product.subject' => '{{PRODUCT_TITLE}} sold for {{TOTAL_AMOUNT}} {{CURRENCY}} - {{WEBSITE_TITLE}}',
    'global.emails.user_guest_payment_product.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You just got a new payment from one of your visitors, for one of your products.<br /><br /><strong>Email:</strong> {{EMAIL}}<br /><strong>Name:</strong> {{NAME}}<br /><strong>Total amount:</strong> {{TOTAL_AMOUNT}}<br /><strong>Currency:</strong> {{CURRENCY}}<br /><strong>Processor:</strong> {{PROCESSOR}}<br /><strong>Product:</strong> {{PRODUCT_TITLE}}<br /><br /><a href="{{GUESTS_PAYMENTS_LINK}}">View all payments</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    'global.emails.guest_guest_payment_product.subject' => 'You purchased {{PRODUCT_TITLE}} - {{WEBSITE_TITLE}}',
    'global.emails.guest_guest_payment_product.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is the official email notice that your payment is successful. You can use the download link below to download the product you purchased.<br /><br /><strong>Download link:</strong> {{DOWNLOAD_LINK}}<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    'global.emails.user_guest_payment_service.subject' => '{{SERVICE_TITLE}} sold for {{TOTAL_AMOUNT}} {{CURRENCY}} - {{WEBSITE_TITLE}}',
    'global.emails.user_guest_payment_service.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />You just got a new payment from one of your visitors, for one of your services.<br /><br /><strong>Email:</strong> {{EMAIL}}<br /><strong>Name:</strong> {{NAME}}<br /><strong>Total amount:</strong> {{TOTAL_AMOUNT}}<br /><strong>Currency:</strong> {{CURRENCY}}<br /><strong>Processor:</strong> {{PROCESSOR}}<br /><strong>Service:</strong> {{SERVICE_TITLE}}<br /><strong>Message:</strong> {{MESSAGE}}<br /><br /><a href="{{GUESTS_PAYMENTS_LINK}}">View all payments</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    'global.emails.guest_guest_payment_service.subject' => 'You paid for {{SERVICE_TITLE}} - {{WEBSITE_TITLE}}',
    'global.emails.guest_guest_payment_service.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is the official email notice that your payment is successful. You will get contacted by the provider of the service as soon as possible.<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Plan expiry reminder
    'global.emails.user_plan_expiry_reminder.subject' => 'Your plan is expiring in {{DAYS_UNTIL_EXPIRATION}} days - {{WEBSITE_TITLE}}',
    'global.emails.user_plan_expiry_reminder.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is a simple email to remind you that your <strong>{{PLAN_NAME}} plan</strong> is going to expire in <strong>{{DAYS_UNTIL_EXPIRATION}} days</strong>.<br /><br />You must renew your plan if you wish to continue using our website with all the features you have.<br /><br /><a href="{{USER_PLAN_RENEW_LINK}}" class="cta">Renew plan</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // User deletion reminder
    'global.emails.user_deletion_reminder.subject' => 'Your account will be deleted in {{DAYS_UNTIL_DELETION}} days - {{WEBSITE_TITLE}}',
    'global.emails.user_deletion_reminder.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is a simple email to remind you that your account is going to be deleted in {{DAYS_UNTIL_DELETION}} days because your account has been inactive.<br /><br />If you wish to cancel this deletion, simply login with your account and the deletion process will be stopped.<br /><br /><a href="{{LOGIN_LINK}}">Login & stop deletion</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Auto delete inactive users
    'global.emails.auto_delete_inactive_users.subject' => 'Your account has been deleted - {{WEBSITE_TITLE}}',
    'global.emails.auto_delete_inactive_users.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is a simple email to let you know that your account has been deleted because of being inactive for more than {{INACTIVITY_DAYS}} days.<br /><br />If you wish to re-gain access, you would need to sign-up another account with us.<br /><br /><a href="{{REGISTER_LINK}}" class="cta">Register again</a><br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Affiliate withdrawal approved
    'global.emails.user_affiliate_withdrawal_approved.subject' => 'Affiliate withdrawal approved - {{WEBSITE_TITLE}}',
    'global.emails.user_affiliate_withdrawal_approved.body' => 'Hello, <strong>{{NAME}}</strong>,<br /><br />This is a confirmation that your recent affiliate withdrawal of <strong>{{AMOUNT}} {{CURRENCY}}</strong> has been approved and your payment was sent for processing.<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Team member create
    'global.emails.team_member_create.subject' => 'You\'ve been invited to \'{{TEAM:NAME}}\' team - {{WEBSITE_TITLE}}',
    'global.emails.team_member_create.body_login' => 'Hello,<br /><br />You have been invited by <strong>{{USER:NAME}}</strong> ({{USER:EMAIL}}) to join the <strong>{{TEAM:NAME}}</strong> team on {{WEBSITE_TITLE}}.<br /><br />Click here to <a href="{{LOGIN_LINK}}">login and accept the invitation</a>.<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',
    'global.emails.team_member_create.body_register' => 'Hello,<br /><br />You have been invited by <strong>{{USER:NAME}}</strong> ({{USER:EMAIL}}) to join the <strong>{{TEAM:NAME}}</strong> team on {{WEBSITE_TITLE}}.<br /><br />Click here to <a href="{{REGISTER_LINK}}">register and accept the invitation</a>.<br /><br />Regards,<br />The {{WEBSITE_TITLE}} team.',

    // Admin notification emails
    'global.emails.admin_new_user_notification.subject' => 'New user registered - {{WEBSITE_TITLE}}',
    'global.emails.admin_new_user_notification.body' => 'Welcome to <strong>{{NAME}}</strong> ({{EMAIL}}) to your website!',

    'global.emails.admin_delete_user_notification.subject' => 'User deleted his account - {{WEBSITE_TITLE}}',
    'global.emails.admin_delete_user_notification.body' => 'All data of <strong>{{NAME}}</strong> ({{EMAIL}}) has been deleted.',

    'global.emails.admin_new_payment_notification.subject' => 'New payment via {{PROCESSOR}} of {{TOTAL_AMOUNT}} {{CURRENCY}} - {{WEBSITE_TITLE}}',
    'global.emails.admin_new_payment_notification.body' => '<strong>{{NAME}}</strong> ({{EMAIL}}) user just paid <strong>{{TOTAL_AMOUNT}} {{CURRENCY}}</strong> to your website!<br /><br />Here\'s to more earnings!',

    'global.emails.admin_new_affiliate_withdrawal_notification.subject' => 'New affiliate withdrawal request for {{TOTAL_AMOUNT}} {{CURRENCY}} - {{WEBSITE_TITLE}}',
    'global.emails.admin_new_affiliate_withdrawal_notification.body' => '<strong>{{NAME}}</strong> ({{EMAIL}}) user just submitted an affiliate withdrawal request for <strong>{{TOTAL_AMOUNT}} {{CURRENCY}}</strong> with the following note: "{{AFFILIATE_WITHDRAWAL_NOTE}}".<br /><br /><a href="{{ADMIN_AFFILIATE_WITHDRAWAL_LINK}}">View affiliate withdrawal</a>',

    'global.emails.admin_new_domain_notification.subject' => 'New custom domain is pending approval - {{WEBSITE_TITLE}}',
    'global.emails.admin_new_domain_notification.body' => '<strong>{{NAME}}</strong> ({{EMAIL}}) user\'s custom domain ({{DOMAIN_HOST}}) is now pending approval.<br /><br /> <a href="{{ADMIN_DOMAIN_UPDATE_LINK}}">View domain</a>',

    'global.emails.admin_contact.subject' => '{{SUBJECT}} - {{NAME}} - {{WEBSITE_TITLE}}',
    'global.emails.admin_contact.body' => '<strong>{{NAME}}</strong> - {{EMAIL}} has sent you the following message:<br /><br />{{MESSAGE}}',
];
