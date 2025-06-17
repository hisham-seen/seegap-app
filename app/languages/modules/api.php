<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // API Documentation
    'api_documentation.title' => 'API Documentation',
    'api_documentation.header' => 'API Documentation',
    'api_documentation.menu' => 'API',
    'api_documentation.breadcrumb' => 'API Documentation',
    'api_documentation.subheader' => 'Integrate our service with your applications',

    // API Overview
    'api.overview.title' => 'Overview',
    'api.overview.description' => 'Our REST API allows you to integrate our URL shortening service into your applications. Create, manage, and track your links programmatically.',
    'api.overview.base_url' => 'Base URL',
    'api.overview.version' => 'API Version',
    'api.overview.format' => 'Response Format',
    'api.overview.authentication' => 'Authentication',
    'api.overview.rate_limits' => 'Rate Limits',

    // Authentication
    'api.authentication.title' => 'Authentication',
    'api.authentication.description' => 'All API requests require authentication using your API key.',
    'api.authentication.api_key' => 'API Key',
    'api.authentication.header' => 'Authorization Header',
    'api.authentication.example' => 'Example',
    'api.authentication.get_key' => 'Get your API key',
    'api.authentication.regenerate' => 'Regenerate API key',

    // Rate Limits
    'api.rate_limits.title' => 'Rate Limits',
    'api.rate_limits.description' => 'API requests are rate limited to ensure fair usage.',
    'api.rate_limits.free_plan' => 'Free Plan',
    'api.rate_limits.pro_plan' => 'Pro Plan',
    'api.rate_limits.business_plan' => 'Business Plan',
    'api.rate_limits.headers' => 'Rate Limit Headers',
    'api.rate_limits.limit' => 'X-RateLimit-Limit',
    'api.rate_limits.remaining' => 'X-RateLimit-Remaining',
    'api.rate_limits.reset' => 'X-RateLimit-Reset',

    // Endpoints
    'api.endpoints.title' => 'Endpoints',
    'api.endpoints.links' => 'Links',
    'api.endpoints.qr_codes' => 'QR Codes',
    'api.endpoints.projects' => 'Projects',
    'api.endpoints.domains' => 'Domains',
    'api.endpoints.analytics' => 'Analytics',
    'api.endpoints.account' => 'Account',

    // Links API
    'api.links.title' => 'Links API',
    'api.links.description' => 'Create, retrieve, update, and delete short links.',
    'api.links.create' => 'Create Link',
    'api.links.get' => 'Get Link',
    'api.links.update' => 'Update Link',
    'api.links.delete' => 'Delete Link',
    'api.links.list' => 'List Links',

    // QR Codes API
    'api.qr_codes.title' => 'QR Codes API',
    'api.qr_codes.description' => 'Generate and manage QR codes.',
    'api.qr_codes.create' => 'Create QR Code',
    'api.qr_codes.get' => 'Get QR Code',
    'api.qr_codes.update' => 'Update QR Code',
    'api.qr_codes.delete' => 'Delete QR Code',
    'api.qr_codes.list' => 'List QR Codes',

    // Projects API
    'api.projects.title' => 'Projects API',
    'api.projects.description' => 'Organize your links and QR codes into projects.',
    'api.projects.create' => 'Create Project',
    'api.projects.get' => 'Get Project',
    'api.projects.update' => 'Update Project',
    'api.projects.delete' => 'Delete Project',
    'api.projects.list' => 'List Projects',

    // Analytics API
    'api.analytics.title' => 'Analytics API',
    'api.analytics.description' => 'Retrieve analytics data for your links and QR codes.',
    'api.analytics.link_stats' => 'Link Statistics',
    'api.analytics.qr_stats' => 'QR Code Statistics',
    'api.analytics.project_stats' => 'Project Statistics',
    'api.analytics.account_stats' => 'Account Statistics',

    // Request/Response
    'api.request.title' => 'Request',
    'api.request.method' => 'Method',
    'api.request.url' => 'URL',
    'api.request.headers' => 'Headers',
    'api.request.parameters' => 'Parameters',
    'api.request.body' => 'Request Body',
    'api.response.title' => 'Response',
    'api.response.status' => 'Status Code',
    'api.response.headers' => 'Headers',
    'api.response.body' => 'Response Body',

    // Parameters
    'api.parameters.required' => 'Required',
    'api.parameters.optional' => 'Optional',
    'api.parameters.type' => 'Type',
    'api.parameters.description' => 'Description',
    'api.parameters.example' => 'Example',
    'api.parameters.default' => 'Default',

    // Status Codes
    'api.status_codes.title' => 'Status Codes',
    'api.status_codes.200' => '200 OK - Request successful',
    'api.status_codes.201' => '201 Created - Resource created successfully',
    'api.status_codes.400' => '400 Bad Request - Invalid request parameters',
    'api.status_codes.401' => '401 Unauthorized - Invalid or missing API key',
    'api.status_codes.403' => '403 Forbidden - Access denied',
    'api.status_codes.404' => '404 Not Found - Resource not found',
    'api.status_codes.422' => '422 Unprocessable Entity - Validation errors',
    'api.status_codes.429' => '429 Too Many Requests - Rate limit exceeded',
    'api.status_codes.500' => '500 Internal Server Error - Server error',

    // Error Handling
    'api.errors.title' => 'Error Handling',
    'api.errors.description' => 'All errors return a JSON response with error details.',
    'api.errors.format' => 'Error Format',
    'api.errors.code' => 'Error Code',
    'api.errors.message' => 'Error Message',
    'api.errors.details' => 'Error Details',

    // Examples
    'api.examples.title' => 'Examples',
    'api.examples.curl' => 'cURL',
    'api.examples.javascript' => 'JavaScript',
    'api.examples.php' => 'PHP',
    'api.examples.python' => 'Python',
    'api.examples.request' => 'Request',
    'api.examples.response' => 'Response',

    // SDKs
    'api.sdks.title' => 'SDKs & Libraries',
    'api.sdks.description' => 'Official and community SDKs for popular programming languages.',
    'api.sdks.official' => 'Official SDKs',
    'api.sdks.community' => 'Community SDKs',
    'api.sdks.javascript' => 'JavaScript/Node.js',
    'api.sdks.php' => 'PHP',
    'api.sdks.python' => 'Python',
    'api.sdks.ruby' => 'Ruby',
    'api.sdks.go' => 'Go',
    'api.sdks.java' => 'Java',

    // Webhooks
    'api.webhooks.title' => 'Webhooks',
    'api.webhooks.description' => 'Receive real-time notifications when events occur.',
    'api.webhooks.setup' => 'Setup Webhooks',
    'api.webhooks.events' => 'Webhook Events',
    'api.webhooks.security' => 'Webhook Security',
    'api.webhooks.testing' => 'Testing Webhooks',

    // Changelog
    'api.changelog.title' => 'Changelog',
    'api.changelog.description' => 'Track changes and updates to our API.',
    'api.changelog.version' => 'Version',
    'api.changelog.date' => 'Date',
    'api.changelog.changes' => 'Changes',
    'api.changelog.breaking' => 'Breaking Changes',
    'api.changelog.deprecated' => 'Deprecated',
    'api.changelog.added' => 'Added',
    'api.changelog.fixed' => 'Fixed',

    // Support
    'api.support.title' => 'API Support',
    'api.support.description' => 'Get help with API integration and troubleshooting.',
    'api.support.contact' => 'Contact Support',
    'api.support.community' => 'Community Forum',
    'api.support.status' => 'API Status',
    'api.support.feedback' => 'API Feedback',

    // Testing
    'api.testing.title' => 'API Testing',
    'api.testing.description' => 'Test API endpoints directly from the documentation.',
    'api.testing.try_it' => 'Try it out',
    'api.testing.send_request' => 'Send Request',
    'api.testing.clear' => 'Clear',
    'api.testing.copy_curl' => 'Copy as cURL',
];
