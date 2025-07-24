<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // Account Settings (Additional hooks not in account.php)
    'account.settings.header' => 'Account Settings',
    'account.settings.timezone' => 'Timezone',
    'account.settings.timezone_help' => 'Select your preferred timezone for date and time display.',
    'account.settings.anti_phishing_code' => 'Anti-Phishing Code',
    'account.settings.anti_phishing_code_help' => 'A unique code to help you identify legitimate emails from us.',

    // Two-Factor Authentication (Additional hooks not in account.php)
    'account.twofa.header' => 'Two-Factor Authentication',
    'account.twofa.is_enabled' => 'Two-Factor Authentication Status',

    // Change Password (Additional hooks not in account.php)
    'account.change_password.header' => 'Change Password',
    'account.change_password.current_password' => 'Current Password',
    'account.change_password.current_password_help' => 'Enter your current password to confirm changes.',
    'account.change_password.new_password' => 'New Password',
    'account.change_password.repeat_password' => 'Repeat New Password',

    // Login (Additional hooks not in auth.php)
    'login.classic.success' => 'Login successful. Welcome back!',

    // Account Logs
    'account_logs.header' => 'Account Activity Logs',
    'account_logs.subheader' => 'View your recent account activity and login history',
    'account_logs.menu' => 'Activity Logs',

    // Account Delete (Additional hooks not in account.php)
    'account_delete.menu' => 'Delete Account',
    'account_delete.header' => 'Delete Account',
    'account_delete.current_password' => 'Current Password',

    // Account API (Additional hooks not in account.php)
    'account_api.header' => 'API Management',
    'account_api.subheader' => 'Manage your API keys and access tokens',
    'account_api.api_key' => 'API Key',
    'account_api.success_message' => 'API settings updated successfully.',
    'account_api.button' => 'Generate New API Key',

    // Account Preferences (Additional hooks not in account.php)
    'account_preferences.header' => 'User Preferences',
    'account_preferences.subheader' => 'Customize your account preferences and default settings',
    'account_preferences.default_results_per_page' => 'Default Results Per Page',
    'account_preferences.default_order_type' => 'Default Order Type',
    'account_preferences.default_order_by_x' => 'Default Order By',

    // GS1 Link Settings
    'gs1_link.settings.header' => 'GS1 Link Settings',

    // GS1 Link Create
    'gs1_link_create.error_message.gtin_invalid_checksum' => 'Invalid GTIN checksum. Please verify the GTIN number.',

    // Create Link Modal (Additional hooks not in links.php)
    'create_link_modal.header' => 'Create New Link',
    'create_link_modal.input.location_url' => 'Destination URL',
    'create_link_modal.input.url' => 'Short URL',
    'create_link_modal.input.url_help' => 'Leave empty for auto-generated URL',
    'create_link_modal.input.submit' => 'Create Link',

    // QR Codes Input (Additional hooks not in qr-codes.php)
    'qr_codes.input.text' => 'Text Content',
    'qr_codes.input.is_bulk' => 'Bulk Generation',
    'qr_codes.input.is_bulk_help' => 'Generate multiple QR codes at once',
    'qr_codes.info' => 'QR Code Information',
    'qr_codes.is_readable' => 'QR Code is readable and scannable',
    'qr_codes.input.url_dynamic' => 'Dynamic URL',
    'qr_codes.input.url_dynamic_help' => 'Enable dynamic URL functionality for this QR code.',
    'qr_codes.input.url_dynamic_help2' => 'Dynamic URLs allow you to change the destination without regenerating the QR code.',
    'qr_codes.input.phone' => 'Phone Number',
    'qr_codes.input.sms' => 'SMS Message',
    'qr_codes.input.sms_body' => 'SMS Body',
    'qr_codes.input.email' => 'Email Address',
    'qr_codes.input.email_subject' => 'Email Subject',
    'qr_codes.input.email_body' => 'Email Body',
    'qr_codes.input.whatsapp' => 'WhatsApp Number',
    'qr_codes.input.whatsapp_body' => 'WhatsApp Message',
    'qr_codes.input.facetime' => 'FaceTime Contact',
    'qr_codes.input.location_latitude' => 'Latitude',
    'qr_codes.input.location_longitude' => 'Longitude',
    'qr_codes.input.wifi_ssid' => 'WiFi SSID',
    'qr_codes.input.wifi_encryption' => 'WiFi Encryption',
    'qr_codes.input.wifi_password' => 'WiFi Password',
    'qr_codes.input.wifi_is_hidden' => 'Hidden Network',
    'qr_codes.input.event' => 'Event',
    'qr_codes.input.event_location' => 'Event Location',
    'qr_codes.input.event_url' => 'Event URL',
    'qr_codes.input.event_note' => 'Event Note',
    'qr_codes.input.event_start_datetime' => 'Start Date & Time',
    'qr_codes.input.event_end_datetime' => 'End Date & Time',
    'qr_codes.input.event_first_alert_datetime' => 'First Alert',
    'qr_codes.input.event_second_alert_datetime' => 'Second Alert',
    'qr_codes.input.event_timezone' => 'Event Timezone',
    'qr_codes.input.crypto_coin' => 'Cryptocurrency',
    'qr_codes.input.crypto_address' => 'Crypto Address',
    'qr_codes.input.crypto_amount' => 'Crypto Amount',
    'qr_codes.input.paypal_type' => 'PayPal Type',
    'qr_codes.input.paypal_type_buy_now' => 'Buy Now',
    'qr_codes.input.paypal_type_add_to_cart' => 'Add to Cart',
    'qr_codes.input.paypal_type_donation' => 'Donation',
    'qr_codes.input.paypal_email' => 'PayPal Email',
    'qr_codes.input.paypal_title' => 'PayPal Title',
    'qr_codes.input.paypal_currency' => 'PayPal Currency',
    'qr_codes.input.paypal_price' => 'PayPal Price',
    'qr_codes.input.paypal_thank_you_url' => 'Thank You URL',
    'qr_codes.input.paypal_cancel_url' => 'Cancel URL',
    'qr_codes.input.style' => 'QR Code Style',
    'qr_codes.input.colors' => 'Color Settings',
    'qr_codes.input.frame' => 'Frame Options',
    'qr_codes.input.branding' => 'Branding Settings',
    'qr_codes.input.options' => 'Additional Options',
    'qr_codes.input.embedded_data' => 'Embedded Data',

    // Link Statistics (Additional hooks not in links.php)
    'link.statistics.referrer_direct' => 'Direct Traffic',
    'link.table.device' => 'Device',
    'link.table.os' => 'Operating System',
    'link.table.browser' => 'Browser',
    'link.statistics.browser' => 'Browser Statistics',

    // Microsite Blocks
    'link.microsite.blocks.accordion' => 'Accordion',
    'link.microsite.blocks.form' => 'Form',
    'link.microsite.blocks.cover' => 'Cover',

    // Microsite Cover
    'microsite_cover.subheader' => 'Add a cover section to your microsite',
    'microsite_cover.background_type' => 'Background Type',
    'microsite_cover.background_type_image' => 'Image',
    'microsite_cover.background_type_video' => 'Video',
    'microsite_cover.background_alt' => 'Background Alt Text',
    'microsite_cover.background_alt_help' => 'Alternative text for the background image for accessibility.',
    'microsite_cover.avatar' => 'Avatar',
    'microsite_cover.avatar_alt' => 'Avatar Alt Text',
    'microsite_cover.avatar_alt_help' => 'Alternative text for the avatar image for accessibility.',
    'microsite_cover.avatar_size' => 'Avatar Size',
    'microsite_cover.object_fit' => 'Object Fit',
    'microsite_cover.object_fit_cover' => 'Cover',
    'microsite_cover.object_fit_contain' => 'Contain',
    'microsite_cover.object_fit_fill' => 'Fill',

    // Microsite Review
    'microsite_review.title' => 'Review Title',
    'microsite_review.image' => 'Review Image',
    'microsite_review.author_name' => 'Author Name',
    'microsite_review.author_description' => 'Author Description',
    'microsite_review.stars' => 'Star Rating',
    'microsite_review.title_color' => 'Title Color',
    'microsite_review.author_name_color' => 'Author Name Color',
    'microsite_review.author_description_color' => 'Author Description Color',
    'microsite_review.stars_color' => 'Stars Color',

    // Microsite Alert
    'microsite_alert.display_close_button' => 'Display Close Button',
    'microsite_alert.alert_pause_after_closed' => 'Pause Alert After Closed',

    // Microsite Link
    'microsite_link.location_url_help' => 'The URL where visitors will be redirected when they click this link.',

    // Microsites
    'microsites.title' => 'Microsites',
    'microsites.header' => 'Microsites',
    'microsites.menu' => 'Microsites',
    'microsites.breadcrumb' => 'Microsites',
    'microsites.subheader' => 'Manage your microsites',
    'microsites.create' => 'Create microsite',
    'microsites.no_data' => 'No microsites found',
    'microsites.no_data_help' => 'Create your first microsite to get started.',

    // Microsite create
    'microsite_create.title' => 'Create microsite',
    'microsite_create.header' => 'Create microsite',
    'microsite_create.breadcrumb' => 'Create microsite',
    'microsite_create.subheader' => 'Create a new microsite',
    'microsite_create.submit' => 'Create microsite',
    'microsite_create.success_message' => 'Microsite has been successfully created.',

    // Microsite update
    'microsite_update.title' => 'Update microsite',
    'microsite_update.header' => 'Update microsite',
    'microsite_update.breadcrumb' => 'Update microsite',
    'microsite_update.subheader' => 'Update your microsite settings',
    'microsite_update.submit' => 'Update microsite',
    'microsite_update.success_message' => 'Microsite has been successfully updated.',

    // Microsite fields
    'microsites.name' => 'Name',
    'microsites.name_placeholder' => 'My Microsite',
    'microsites.name_help' => 'Internal name for your microsite.',
    'microsites.description' => 'Description',
    'microsites.description_placeholder' => 'Describe your microsite',
    'microsites.description_help' => 'Brief description of your microsite.',
    'microsites.url' => 'URL',
    'microsites.url_placeholder' => 'my-microsite',
    'microsites.url_help' => 'Custom URL for your microsite.',
    'microsites.domain' => 'Domain',
    'microsites.domain_help' => 'Choose the domain for your microsite.',
    'microsites.project' => 'Project',
    'microsites.project_help' => 'Organize your microsites into projects.',
    'microsites.theme' => 'Theme',
    'microsites.theme_help' => 'Choose a theme for your microsite.',
    'microsites.password' => 'Password protection',
    'microsites.password_placeholder' => 'Enter password',
    'microsites.password_help' => 'Protect your microsite with a password.',
    'microsites.sensitive_content' => 'Sensitive content',
    'microsites.sensitive_content_help' => 'Mark this microsite as containing sensitive content.',

    // Microsite blocks
    'microsite_blocks.title' => 'Microsite Blocks',
    'microsite_blocks.header' => 'Microsite Blocks',
    'microsite_blocks.subheader' => 'Add and manage blocks for your microsite',
    'microsite_blocks.add_block' => 'Add Block',
    'microsite_blocks.no_blocks' => 'No blocks found',
    'microsite_blocks.no_blocks_help' => 'Add your first block to get started.',
    'microsite_blocks.reorder' => 'Drag to reorder blocks',
    'microsite_blocks.enable_disable' => 'Enable/disable this block',

    // Microsite block types
    'microsite_block.type.text' => 'Text',
    'microsite_block.type.heading' => 'Heading',
    'microsite_block.type.link' => 'Link',
    'microsite_block.type.avatar' => 'Avatar',
    'microsite_block.type.image' => 'Image',
    'microsite_block.type.socials' => 'Social Links',
    'microsite_block.type.divider' => 'Divider',
    'microsite_block.type.cta' => 'Call to Action',
    'microsite_block.type.email_collector' => 'Email Collector',
    'microsite_block.type.custom_html' => 'Custom HTML',
    'microsite_block.type.alert' => 'Alert',
    'microsite_block.type.faq' => 'FAQ',
    'microsite_block.type.countdown' => 'Countdown',
    'microsite_block.type.share' => 'Share',
    'microsite_block.type.youtube_feed' => 'YouTube Feed',
    'microsite_block.type.review' => 'Review',
    'microsite_block.type.accordion' => 'Accordion',
    'microsite_block.type.form' => 'Form',
    'microsite_block.type.cover' => 'Cover',
    'microsite_block.type.header' => 'Header',

    // Microsite statistics
    'microsites.statistics' => 'Statistics',
    'microsites.statistics.views' => 'Views',
    'microsites.statistics.unique_views' => 'Unique views',
    'microsites.statistics.clicks' => 'Clicks',
    'microsites.statistics.conversions' => 'Conversions',

    // Microsite actions
    'microsites.copy' => 'Copy link',
    'microsites.copy_success' => 'Microsite link copied to clipboard!',
    'microsites.preview' => 'Preview microsite',
    'microsites.qr_code' => 'QR code',
    'microsites.analytics' => 'Analytics',
    'microsites.edit' => 'Edit microsite',
    'microsites.duplicate' => 'Duplicate microsite',
    'microsites.delete' => 'Delete microsite',
    'microsites.enable' => 'Enable microsite',
    'microsites.disable' => 'Disable microsite',

    // Microsite status
    'microsites.status' => 'Status',
    'microsites.status.active' => 'Active',
    'microsites.status.disabled' => 'Disabled',
    'microsites.status.draft' => 'Draft',

    // Microsite errors
    'microsites.error.invalid_url' => 'Please enter a valid URL.',
    'microsites.error.url_exists' => 'This URL is already taken.',
    'microsites.error.url_invalid' => 'URL contains invalid characters.',
    'microsites.error.name_required' => 'Name is required.',
    'microsites.error.description_too_long' => 'Description is too long.',
    'microsites.error.theme_not_found' => 'Theme not found.',
    'microsites.error.domain_not_found' => 'Domain not found.',
    'microsites.error.project_not_found' => 'Project not found.',
];
