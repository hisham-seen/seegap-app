<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // QR Codes
    'qr_codes.title' => 'QR Codes',
    'qr_codes.header' => 'QR Codes',
    'qr_codes.menu' => 'QR Codes',
    'qr_codes.breadcrumb' => 'QR Codes',
    'qr_codes.subheader' => 'Manage your QR codes',
    'qr_codes.create' => 'Create QR code',
    'qr_codes.no_data' => 'No QR codes found',
    'qr_codes.no_data_help' => 'Create your first QR code to get started.',

    // QR Code create
    'qr_code_create.title' => 'Create QR code',
    'qr_code_create.header' => 'Create QR code',
    'qr_code_create.breadcrumb' => 'Create QR code',
    'qr_code_create.subheader' => 'Generate a new QR code',
    'qr_code_create.submit' => 'Create QR code',
    'qr_code_create.success_message' => 'QR code has been successfully created.',

    // QR Code update
    'qr_code_update.title' => 'Update QR code',
    'qr_code_update.header' => 'Update QR code',
    'qr_code_update.breadcrumb' => 'Update QR code',
    'qr_code_update.subheader' => 'Update your QR code settings',
    'qr_code_update.submit' => 'Update QR code',
    'qr_code_update.success_message' => 'QR code has been successfully updated.',

    // QR Code types
    'qr_codes.type' => 'Type',
    'qr_codes.type.url' => 'URL',
    'qr_codes.type.text' => 'Text',
    'qr_codes.type.email' => 'Email',
    'qr_codes.type.phone' => 'Phone',
    'qr_codes.type.sms' => 'SMS',
    'qr_codes.type.wifi' => 'WiFi',
    'qr_codes.type.vcard' => 'vCard',
    'qr_codes.type.event' => 'Event',
    'qr_codes.type.crypto' => 'Crypto',
    'qr_codes.type.paypal' => 'PayPal',
    'qr_codes.type.whatsapp' => 'WhatsApp',
    'qr_codes.type.facetime' => 'FaceTime',
    'qr_codes.type.upi' => 'UPI Payment',
    'qr_codes.type.epc' => 'EPC Payment',
    'qr_codes.type.pix' => 'PIX Payment',
    'qr_codes.type.location' => 'Location',

    // QR Code fields
    'qr_codes.name' => 'Name',
    'qr_codes.name_placeholder' => 'My QR Code',
    'qr_codes.name_help' => 'Internal name for your QR code.',
    'qr_codes.project' => 'Project',
    'qr_codes.project_help' => 'Organize your QR codes into projects.',

    // URL type
    'qr_codes.url' => 'URL',
    'qr_codes.url_placeholder' => 'https://example.com',
    'qr_codes.url_help' => 'The URL to encode in the QR code.',

    // Dynamic URL
    'qr_codes.input.url_dynamic' => 'Dynamic URL',
    'qr_codes.input.url_dynamic_help' => 'Enable dynamic URL functionality for this QR code.',
    'qr_codes.input.url_dynamic_help2' => 'Dynamic URLs allow you to change the destination without regenerating the QR code.',

    // Text type
    'qr_codes.text' => 'Text',
    'qr_codes.text_placeholder' => 'Enter your text here',
    'qr_codes.text_help' => 'The text to encode in the QR code.',

    // Email type
    'qr_codes.email' => 'Email',
    'qr_codes.email_placeholder' => 'contact@example.com',
    'qr_codes.email_help' => 'Email address for the QR code.',
    'qr_codes.email_subject' => 'Subject',
    'qr_codes.email_subject_placeholder' => 'Email subject',
    'qr_codes.email_body' => 'Body',
    'qr_codes.email_body_placeholder' => 'Email message',

    // Phone type
    'qr_codes.phone' => 'Phone',
    'qr_codes.phone_placeholder' => '+1234567890',
    'qr_codes.phone_help' => 'Phone number for the QR code.',

    // SMS type
    'qr_codes.sms_phone' => 'Phone number',
    'qr_codes.sms_phone_placeholder' => '+1234567890',
    'qr_codes.sms_message' => 'Message',
    'qr_codes.sms_message_placeholder' => 'SMS message',

    // WiFi type
    'qr_codes.wifi_ssid' => 'Network name (SSID)',
    'qr_codes.wifi_ssid_placeholder' => 'MyWiFiNetwork',
    'qr_codes.wifi_password' => 'Password',
    'qr_codes.wifi_password_placeholder' => 'WiFi password',
    'qr_codes.wifi_security' => 'Security',
    'qr_codes.wifi_security.none' => 'None',
    'qr_codes.wifi_security.wep' => 'WEP',
    'qr_codes.wifi_security.wpa' => 'WPA/WPA2',
    'qr_codes.wifi_hidden' => 'Hidden network',

    // vCard type
    'qr_codes.vcard_first_name' => 'First name',
    'qr_codes.vcard_last_name' => 'Last name',
    'qr_codes.vcard_phone' => 'Phone',
    'qr_codes.vcard_email' => 'Email',
    'qr_codes.vcard_url' => 'Website',
    'qr_codes.vcard_company' => 'Company',
    'qr_codes.vcard_job_title' => 'Job title',
    'qr_codes.vcard_address' => 'Address',
    'qr_codes.vcard_note' => 'Note',

    // Event type
    'qr_codes.event_title' => 'Event title',
    'qr_codes.event_description' => 'Description',
    'qr_codes.event_location' => 'Location',
    'qr_codes.event_start' => 'Start date',
    'qr_codes.event_end' => 'End date',
    'qr_codes.event_timezone' => 'Timezone',

    // Crypto type
    'qr_codes.crypto_coin' => 'Cryptocurrency',
    'qr_codes.crypto_address' => 'Wallet address',
    'qr_codes.crypto_amount' => 'Amount',
    'qr_codes.crypto_message' => 'Message',

    // PayPal type
    'qr_codes.paypal_email' => 'PayPal email',
    'qr_codes.paypal_amount' => 'Amount',
    'qr_codes.paypal_currency' => 'Currency',
    'qr_codes.paypal_item_name' => 'Item name',

    // WhatsApp type
    'qr_codes.whatsapp_phone' => 'Phone number',
    'qr_codes.whatsapp_message' => 'Message',

    // FaceTime type
    'qr_codes.facetime_phone' => 'Phone number',
    'qr_codes.facetime_email' => 'Email',

    // QR Code Information and Data
    'qr_codes.info' => 'QR Code Information',
    'qr_codes.embedded_data' => 'Embedded Data',
    'qr_codes.is_readable' => 'QR Code is readable',

    // Input fields for QR Code customization
    'qr_codes.input.style' => 'QR Code Style',
    'qr_codes.input.inner_eye_style' => 'Inner Eye Style',
    'qr_codes.input.outer_eye_style' => 'Outer Eye Style',
    'qr_codes.input.colors' => 'Colors',
    'qr_codes.input.foreground_type' => 'Foreground Type',
    'qr_codes.input.foreground_type_color' => 'Solid Color',
    'qr_codes.input.foreground_type_gradient' => 'Gradient',
    'qr_codes.input.foreground_color' => 'Foreground Color',
    'qr_codes.input.background_color' => 'Background Color',
    'qr_codes.input.background_color_transparency' => 'Background Transparency',
    'qr_codes.input.custom_eyes_color' => 'Custom Eyes Color',
    'qr_codes.input.frame' => 'Frame',
    'qr_codes.input.frame_text' => 'Frame Text',
    'qr_codes.input.frame_text_size' => 'Frame Text Size',
    'qr_codes.input.frame_text_font' => 'Frame Text Font',
    'qr_codes.input.frame_custom_colors' => 'Frame Custom Colors',
    'qr_codes.input.branding' => 'Branding',
    'qr_codes.input.qr_code_logo' => 'QR Code Logo',
    'qr_codes.input.qr_code_logo_size' => 'Logo Size',
    'qr_codes.input.qr_code_background' => 'QR Code Background',
    'qr_codes.input.qr_code_background_transparency' => 'Background Transparency',
    'qr_codes.input.qr_code_foreground' => 'QR Code Foreground',
    'qr_codes.input.qr_code_foreground_transparency' => 'Foreground Transparency',
    'qr_codes.input.options' => 'Options',
    'qr_codes.input.size' => 'Size',
    'qr_codes.input.margin' => 'Margin',
    'qr_codes.input.ecc' => 'Error Correction Level',
    'qr_codes.input.ecc_l' => 'Low (~7%)',
    'qr_codes.input.ecc_m' => 'Medium (~15%)',
    'qr_codes.input.ecc_q' => 'Quartile (~25%)',
    'qr_codes.input.ecc_h' => 'High (~30%)',
    'qr_codes.input.encoding' => 'Encoding',
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
    'qr_codes.input.is_bulk' => 'Bulk Generation',
    'qr_codes.input.is_bulk_help' => 'Generate multiple QR codes at once',
    'qr_codes.input.wifi_encryption' => 'WiFi Encryption',
    'qr_codes.input.wifi_password' => 'WiFi Password',
    'qr_codes.input.wifi_is_hidden' => 'Hidden Network',

    // Event input fields
    'qr_codes.input.event' => 'Event',
    'qr_codes.input.event_location' => 'Event Location',
    'qr_codes.input.event_url' => 'Event URL',
    'qr_codes.input.event_note' => 'Event Note',
    'qr_codes.input.event_start_datetime' => 'Start Date & Time',
    'qr_codes.input.event_end_datetime' => 'End Date & Time',
    'qr_codes.input.event_first_alert_datetime' => 'First Alert',
    'qr_codes.input.event_second_alert_datetime' => 'Second Alert',
    'qr_codes.input.event_timezone' => 'Event Timezone',

    // Crypto input fields
    'qr_codes.input.crypto_coin' => 'Cryptocurrency',
    'qr_codes.input.crypto_address' => 'Crypto Address',
    'qr_codes.input.crypto_amount' => 'Crypto Amount',

    // PayPal input fields
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

    // UPI input fields
    'qr_codes.input.upi_payee_id' => 'UPI Payee ID',
    'qr_codes.input.upi_payee_name' => 'UPI Payee Name',
    'qr_codes.input.upi_amount' => 'UPI Amount',
    'qr_codes.input.upi_currency' => 'UPI Currency',
    'qr_codes.input.upi_transaction_id' => 'UPI Transaction ID',
    'qr_codes.input.upi_transaction_reference' => 'UPI Transaction Reference',
    'qr_codes.input.upi_transaction_note' => 'UPI Transaction Note',
    'qr_codes.input.upi_thank_you_url' => 'UPI Thank You URL',

    // EPC input fields
    'qr_codes.input.epc_iban' => 'EPC IBAN',
    'qr_codes.input.epc_payee_name' => 'EPC Payee Name',
    'qr_codes.input.epc_amount' => 'EPC Amount',
    'qr_codes.input.epc_currency' => 'EPC Currency',
    'qr_codes.input.epc_bic' => 'EPC BIC',
    'qr_codes.input.epc_remittance_reference' => 'EPC Remittance Reference',
    'qr_codes.input.epc_remittance_text' => 'EPC Remittance Text',
    'qr_codes.input.epc_information' => 'EPC Information',

    // PIX input fields
    'qr_codes.input.pix_payee_key' => 'PIX Payee Key',
    'qr_codes.input.pix_payee_key_help' => 'Enter the PIX key for the payment recipient',
    'qr_codes.input.pix_payee_name' => 'PIX Payee Name',
    'qr_codes.input.pix_amount' => 'PIX Amount',
    'qr_codes.input.pix_currency' => 'PIX Currency',
    'qr_codes.input.pix_city' => 'PIX City',
    'qr_codes.input.pix_transaction_id' => 'PIX Transaction ID',
    'qr_codes.input.pix_description' => 'PIX Description',

    // Design settings
    'qr_codes.design' => 'Design',
    'qr_codes.design.foreground_color' => 'Foreground color',
    'qr_codes.design.background_color' => 'Background color',
    'qr_codes.design.custom_eyes_color' => 'Custom eyes color',
    'qr_codes.design.gradient' => 'Gradient',
    'qr_codes.design.gradient_style' => 'Gradient style',
    'qr_codes.design.gradient_one' => 'Gradient color 1',
    'qr_codes.design.gradient_two' => 'Gradient color 2',
    'qr_codes.design.background_color_transparency' => 'Background transparency',
    'qr_codes.design.custom_eyes' => 'Custom eyes',
    'qr_codes.design.qr_code_logo' => 'Logo',
    'qr_codes.design.qr_code_logo_help' => 'Upload a logo to display in the center of the QR code.',
    'qr_codes.design.qr_code_logo_size' => 'Logo size',
    'qr_codes.design.size' => 'Size',
    'qr_codes.design.margin' => 'Margin',
    'qr_codes.design.ecc' => 'Error correction level',
    'qr_codes.design.ecc.l' => 'Low (~7%)',
    'qr_codes.design.ecc.m' => 'Medium (~15%)',
    'qr_codes.design.ecc.q' => 'Quartile (~25%)',
    'qr_codes.design.ecc.h' => 'High (~30%)',

    // QR Code actions
    'qr_codes.download' => 'Download',
    'qr_codes.download_svg' => 'Download SVG',
    'qr_codes.download_png' => 'Download PNG',
    'qr_codes.download_jpg' => 'Download JPG',
    'qr_codes.download_pdf' => 'Download PDF',
    'qr_codes.embed' => 'Embed',
    'qr_codes.print' => 'Print',
    'qr_codes.share' => 'Share',
    'qr_codes.duplicate' => 'Duplicate',
    'qr_codes.edit' => 'Edit',
    'qr_codes.delete' => 'Delete',
    'qr_codes.analytics' => 'Analytics',

    // QR Code statistics
    'qr_codes.statistics' => 'Statistics',
    'qr_codes.statistics.scans' => 'Scans',
    'qr_codes.statistics.unique_scans' => 'Unique scans',
    'qr_codes.statistics.impressions' => 'Impressions',
    'qr_codes.statistics.ctr' => 'Scan rate',

    // QR Code status
    'qr_codes.status' => 'Status',
    'qr_codes.status.active' => 'Active',
    'qr_codes.status.disabled' => 'Disabled',
    'qr_codes.status.expired' => 'Expired',

    // QR Code errors
    'qr_codes.error.invalid_url' => 'Please enter a valid URL.',
    'qr_codes.error.invalid_email' => 'Please enter a valid email address.',
    'qr_codes.error.invalid_phone' => 'Please enter a valid phone number.',
    'qr_codes.error.missing_required_fields' => 'Please fill in all required fields.',
    'qr_codes.error.logo_upload_failed' => 'Failed to upload logo.',
    'qr_codes.error.logo_too_large' => 'Logo file is too large.',
    'qr_codes.error.invalid_logo_format' => 'Invalid logo format. Please use PNG, JPG, or SVG.',
];
