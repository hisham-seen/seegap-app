<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // Account
    'account.title' => 'Account',
    'account.header' => 'Account',
    'account.menu' => 'Account',
    'account.breadcrumb' => 'Account',
    'account.subheader' => 'Manage your account settings',

    // Account settings
    'account.personal' => 'Personal',
    'account.personal_header' => 'Personal information',
    'account.personal_subheader' => 'Update your personal details',
    'account.name' => 'Name',
    'account.name_placeholder' => 'John Doe',
    'account.email' => 'Email',
    'account.email_placeholder' => 'john@example.com',
    'account.email_help' => 'Your email address for login and notifications.',
    'account.timezone' => 'Timezone',
    'account.timezone_help' => 'Choose your timezone for accurate date/time display.',
    'account.language' => 'Language',
    'account.language_help' => 'Choose your preferred language.',
    'account.avatar' => 'Avatar',
    'account.avatar_help' => 'Upload a profile picture.',
    'account.avatar_remove' => 'Remove avatar',

    // Password
    'account.password' => 'Password',
    'account.password_header' => 'Change password',
    'account.password_subheader' => 'Update your account password',
    'account.current_password' => 'Current password',
    'account.current_password_placeholder' => 'Enter your current password',
    'account.new_password' => 'New password',
    'account.new_password_placeholder' => 'Enter your new password',
    'account.repeat_password' => 'Repeat password',
    'account.repeat_password_placeholder' => 'Repeat your new password',
    'account.password_requirements' => 'Password must be at least 6 characters long.',

    // Two-factor authentication
    'account.twofa' => 'Two-factor authentication',
    'account.twofa_header' => 'Two-factor authentication',
    'account.twofa_subheader' => 'Secure your account with 2FA',
    'account.twofa_status' => 'Status',
    'account.twofa_enabled' => 'Enabled',
    'account.twofa_disabled' => 'Disabled',
    'account.twofa_enable' => 'Enable 2FA',
    'account.twofa_disable' => 'Disable 2FA',
    'account.twofa_secret' => 'Secret key',
    'account.twofa_qr' => 'QR code',
    'account.twofa_qr_help' => 'Scan this QR code with your authenticator app.',
    'account.twofa_backup_codes' => 'Backup codes',
    'account.twofa_backup_codes_help' => 'Save these backup codes in a safe place.',
    'account.twofa_verify' => 'Verify',
    'account.twofa_verify_help' => 'Enter the 6-digit code from your authenticator app.',
    'account.twofa_token' => 'Authentication code',
    'account.twofa_token_placeholder' => '123456',

    // API
    'account.api' => 'API',
    'account.api_header' => 'API access',
    'account.api_subheader' => 'Manage your API keys',
    'account.api_key' => 'API key',
    'account.api_key_help' => 'Use this key to access the API.',
    'account.api_regenerate' => 'Regenerate API key',
    'account.api_regenerate_help' => 'Generate a new API key. This will invalidate the current key.',
    'account.api_documentation' => 'API documentation',

    // Preferences
    'account.preferences' => 'Preferences',
    'account.preferences_header' => 'Preferences',
    'account.preferences_subheader' => 'Customize your experience',
    'account.theme' => 'Theme',
    'account.theme_light' => 'Light',
    'account.theme_dark' => 'Dark',
    'account.theme_auto' => 'Auto',
    'account.notifications' => 'Notifications',
    'account.notifications_email' => 'Email notifications',
    'account.notifications_email_help' => 'Receive notifications via email.',
    'account.notifications_browser' => 'Browser notifications',
    'account.notifications_browser_help' => 'Receive notifications in your browser.',
    'account.newsletter' => 'Newsletter',
    'account.newsletter_help' => 'Subscribe to our newsletter for updates.',

    // Billing
    'account.billing' => 'Billing',
    'account.billing_header' => 'Billing information',
    'account.billing_subheader' => 'Manage your billing details',
    'account.billing_name' => 'Billing name',
    'account.billing_address' => 'Billing address',
    'account.billing_city' => 'City',
    'account.billing_state' => 'State',
    'account.billing_country' => 'Country',
    'account.billing_postal_code' => 'Postal code',
    'account.billing_tax_id' => 'Tax ID',
    'account.billing_tax_id_help' => 'Your tax identification number.',

    // Delete account
    'account.delete' => 'Delete account',
    'account.delete_header' => 'Delete account',
    'account.delete_subheader' => 'Permanently delete your account',
    'account.delete_warning' => 'This action cannot be undone. All your data will be permanently deleted.',
    'account.delete_confirmation' => 'I understand that this action cannot be undone.',
    'account.delete_password' => 'Enter your password to confirm',
    'account.delete_button' => 'Delete my account',

    // Success messages
    'account.success.personal_updated' => 'Personal information updated successfully.',
    'account.success.password_updated' => 'Password updated successfully.',
    'account.success.twofa_enabled' => 'Two-factor authentication enabled successfully.',
    'account.success.twofa_disabled' => 'Two-factor authentication disabled successfully.',
    'account.success.api_regenerated' => 'API key regenerated successfully.',
    'account.success.preferences_updated' => 'Preferences updated successfully.',
    'account.success.billing_updated' => 'Billing information updated successfully.',
    'account.success.account_deleted' => 'Account deleted successfully.',

    // Error messages
    'account.error.current_password_invalid' => 'Current password is incorrect.',
    'account.error.passwords_not_matching' => 'Passwords do not match.',
    'account.error.twofa_invalid' => 'Invalid authentication code.',
    'account.error.email_exists' => 'This email address is already in use.',
    'account.error.avatar_upload' => 'Failed to upload avatar.',
    'account.error.delete_confirmation' => 'Please confirm account deletion.',
];
