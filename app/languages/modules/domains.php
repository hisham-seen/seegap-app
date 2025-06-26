<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // Domains
    'domains.title' => 'Domains',
    'domains.header' => 'Domains',
    'domains.menu' => 'Domains',
    'domains.breadcrumb' => 'Domains',
    'domains.subheader' => 'Manage your custom domains',
    'domains.create' => 'Add domain',
    'domains.no_data' => 'No domains found',
    'domains.no_data_help' => 'Add your first custom domain to get started.',

    // Domain create
    'domain_create.title' => 'Add domain',
    'domain_create.header' => 'Add domain',
    'domain_create.breadcrumb' => 'Add domain',
    'domain_create.subheader' => 'Add a custom domain for branded links',
    'domain_create.submit' => 'Add domain',
    'domain_create.success_message' => 'Domain has been successfully added.',

    // Domain update
    'domain_update.title' => 'Update domain',
    'domain_update.header' => 'Update domain',
    'domain_update.breadcrumb' => 'Update domain',
    'domain_update.subheader' => 'Update your domain settings',
    'domain_update.submit' => 'Update domain',
    'domain_update.success_message' => 'Domain has been successfully updated.',

    // Domain fields
    'domains.host' => 'Domain',
    'domains.host_placeholder' => 'short.example.com',
    'domains.host_help' => 'Your custom domain without http:// or https://',
    'domains.help' => 'Domain Help & Documentation',
    'domains.scheme' => 'Protocol',
    'domains.scheme.http' => 'HTTP',
    'domains.scheme.https' => 'HTTPS',
    'domains.scheme_help' => 'Choose the protocol for your domain.',
    'domains.custom_index_url' => 'Custom index URL',
    'domains.custom_index_url_placeholder' => 'https://example.com',
    'domains.custom_index_url_help' => 'Where to redirect when someone visits your domain root.',
    'domains.custom_not_found_url' => 'Custom 404 URL',
    'domains.custom_not_found_url_placeholder' => 'https://example.com/404',
    'domains.custom_not_found_url_help' => 'Where to redirect for non-existent short links.',

    // Domain status
    'domains.status' => 'Status',
    'domains.status.active' => 'Active',
    'domains.status.pending' => 'Pending verification',
    'domains.status.failed' => 'Verification failed',
    'domains.status.disabled' => 'Disabled',

    // Domain verification
    'domains.verification' => 'Domain verification',
    'domains.verification.title' => 'Verify your domain',
    'domains.verification.description' => 'To use your custom domain, you need to verify ownership by adding DNS records.',
    'domains.verification.method' => 'Verification method',
    'domains.verification.method.dns' => 'DNS record',
    'domains.verification.method.file' => 'File upload',
    'domains.verification.dns_record' => 'DNS record',
    'domains.verification.dns_type' => 'Type',
    'domains.verification.dns_name' => 'Name',
    'domains.verification.dns_value' => 'Value',
    'domains.verification.dns_ttl' => 'TTL',
    'domains.verification.file_upload' => 'File upload',
    'domains.verification.file_name' => 'File name',
    'domains.verification.file_content' => 'File content',
    'domains.verification.file_location' => 'Upload location',
    'domains.verification.verify' => 'Verify domain',
    'domains.verification.recheck' => 'Recheck verification',
    'domains.verification.pending' => 'Verification in progress...',
    'domains.verification.success' => 'Domain verified successfully!',
    'domains.verification.failed' => 'Domain verification failed.',

    // Domain setup
    'domains.setup' => 'Domain setup',
    'domains.setup.title' => 'Setup instructions',
    'domains.setup.description' => 'Follow these steps to configure your domain.',
    'domains.setup.step1' => 'Step 1: Add DNS records',
    'domains.setup.step1_description' => 'Add the following DNS records to your domain:',
    'domains.setup.step2' => 'Step 2: Verify domain',
    'domains.setup.step2_description' => 'Click the verify button to check your DNS configuration.',
    'domains.setup.step3' => 'Step 3: Start using',
    'domains.setup.step3_description' => 'Once verified, you can start creating links with your custom domain.',
    'domains.setup.dns_propagation' => 'DNS propagation can take up to 48 hours.',

    // Domain statistics
    'domains.statistics' => 'Statistics',
    'domains.statistics.links' => 'Links',
    'domains.statistics.clicks' => 'Clicks',
    'domains.statistics.unique_clicks' => 'Unique clicks',
    'domains.statistics.top_links' => 'Top links',
    'domains.statistics.recent_activity' => 'Recent activity',

    // Domain actions
    'domains.verify' => 'Verify domain',
    'domains.edit' => 'Edit domain',
    'domains.delete' => 'Delete domain',
    'domains.disable' => 'Disable domain',
    'domains.enable' => 'Enable domain',
    'domains.view_links' => 'View links',
    'domains.create_link' => 'Create link',

    // Domain errors
    'domains.error.invalid_domain' => 'Please enter a valid domain name.',
    'domains.error.domain_exists' => 'This domain is already added.',
    'domains.error.domain_reserved' => 'This domain is reserved and cannot be used.',
    'domains.error.verification_failed' => 'Domain verification failed. Please check your DNS records.',
    'domains.error.dns_not_found' => 'DNS record not found. Please add the required DNS record.',
    'domains.error.file_not_found' => 'Verification file not found. Please upload the file to the specified location.',
    'domains.error.ssl_required' => 'SSL certificate is required for HTTPS domains.',
    'domains.error.domain_not_accessible' => 'Domain is not accessible. Please check your DNS configuration.',

    // Domain help
    'domains.help.title' => 'Domain help',
    'domains.help.what_is_custom_domain' => 'What is a custom domain?',
    'domains.help.what_is_custom_domain_answer' => 'A custom domain allows you to use your own domain name for short links instead of our default domain.',
    'domains.help.how_to_add' => 'How to add a custom domain?',
    'domains.help.how_to_add_answer' => 'Enter your domain name, add the required DNS records, and verify ownership.',
    'domains.help.dns_records' => 'What DNS records do I need?',
    'domains.help.dns_records_answer' => 'You need to add a CNAME record pointing to our servers and a TXT record for verification.',
    'domains.help.ssl_certificate' => 'Do I need an SSL certificate?',
    'domains.help.ssl_certificate_answer' => 'We automatically provide SSL certificates for verified domains.',
    'domains.help.propagation_time' => 'How long does DNS propagation take?',
    'domains.help.propagation_time_answer' => 'DNS changes can take up to 48 hours to propagate worldwide.',

    // Domain limits
    'domains.limits.title' => 'Domain limits',
    'domains.limits.free_plan' => 'Free plan: No custom domains',
    'domains.limits.pro_plan' => 'Pro plan: 1 custom domain',
    'domains.limits.business_plan' => 'Business plan: 5 custom domains',
    'domains.limits.enterprise_plan' => 'Enterprise plan: Unlimited domains',
    'domains.limits.upgrade' => 'Upgrade your plan to add more domains.',
];
