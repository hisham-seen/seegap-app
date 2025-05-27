<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('ALTUMCODE') || die();

/**
 * Validate GTIN (Global Trade Item Number) with check digit verification
 *
 * @param string $gtin The GTIN to validate
 * @return bool True if valid, false otherwise
 */
function validate_gtin($gtin) {
    // Remove any non-numeric characters
    $gtin = preg_replace('/[^0-9]/', '', $gtin);
    
    // Must be exactly 14 digits for GTIN-14, or 13 digits for GTIN-13 (will be padded)
    if (strlen($gtin) === 13) {
        $gtin = '0' . $gtin; // Pad GTIN-13 to GTIN-14
    }
    
    if (strlen($gtin) !== 14) {
        return false;
    }
    
    // Calculate check digit using modulo 10 algorithm
    $sum = 0;
    for ($i = 0; $i < 13; $i++) {
        $multiplier = ($i % 2 === 0) ? 1 : 3;
        $sum += (int)$gtin[$i] * $multiplier;
    }
    
    $checkDigit = (10 - ($sum % 10)) % 10;
    
    return $checkDigit == (int)$gtin[13];
}

/**
 * Format GTIN to standard 14-digit format
 *
 * @param string $gtin The GTIN to format
 * @return string|false Formatted GTIN or false if invalid
 */
function format_gtin($gtin) {
    // Remove any non-numeric characters
    $gtin = preg_replace('/[^0-9]/', '', $gtin);
    
    // Pad GTIN-13 to GTIN-14
    if (strlen($gtin) === 13) {
        $gtin = '0' . $gtin;
    }
    
    if (strlen($gtin) !== 14) {
        return false;
    }
    
    return $gtin;
}

/**
 * Calculate check digit for a GTIN
 *
 * @param string $gtin_without_check The first 13 digits of GTIN
 * @return int The check digit
 */
function calculate_gtin_check_digit($gtin_without_check) {
    $gtin_without_check = preg_replace('/[^0-9]/', '', $gtin_without_check);
    
    if (strlen($gtin_without_check) !== 13) {
        return false;
    }
    
    $sum = 0;
    for ($i = 0; $i < 13; $i++) {
        $multiplier = ($i % 2 === 0) ? 1 : 3;
        $sum += (int)$gtin_without_check[$i] * $multiplier;
    }
    
    return (10 - ($sum % 10)) % 10;
}

/**
 * Parse GS1 Digital Link URL
 *
 * @param string $url The URL to parse
 * @return array|false Array with parsed components or false if invalid
 */
function parse_gs1_digital_link($url) {
    // Remove domain and get path
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '';
    
    // Match GS1 Digital Link pattern: /01/{gtin}
    if (preg_match('/^\/01\/(\d{13,14})(?:\/.*)?$/', $path, $matches)) {
        $gtin = format_gtin($matches[1]);
        
        if ($gtin && validate_gtin($gtin)) {
            return [
                'ai' => '01', // Application Identifier for GTIN
                'gtin' => $gtin,
                'query' => $parsed['query'] ?? null
            ];
        }
    }
    
    return false;
}

/**
 * Generate GS1 Digital Link URL
 *
 * @param string $domain The domain to use
 * @param string $gtin The GTIN
 * @param array $query_params Optional query parameters
 * @return string The generated URL
 */
function generate_gs1_digital_link($domain, $gtin, $query_params = []) {
    $gtin = format_gtin($gtin);
    
    if (!$gtin || !validate_gtin($gtin)) {
        return false;
    }
    
    $url = rtrim($domain, '/') . '/01/' . $gtin;
    
    if (!empty($query_params)) {
        $url .= '?' . http_build_query($query_params);
    }
    
    return $url;
}

/**
 * Extract company prefix from GTIN
 *
 * @param string $gtin The GTIN
 * @return string|false The company prefix or false if invalid
 */
function get_gtin_company_prefix($gtin) {
    $gtin = format_gtin($gtin);
    
    if (!$gtin || !validate_gtin($gtin)) {
        return false;
    }
    
    // Company prefix length varies, but typically 7-10 digits after the indicator digit
    // For simplicity, we'll extract the first 7 digits after the indicator
    return substr($gtin, 1, 7);
}

/**
 * Get GTIN type description
 *
 * @param string $gtin The GTIN
 * @return string The type description
 */
function get_gtin_type($gtin) {
    $gtin = format_gtin($gtin);
    
    if (!$gtin) {
        return 'Invalid';
    }
    
    $indicator = $gtin[0];
    
    switch ($indicator) {
        case '0':
            return 'GTIN-13 (UPC-A/EAN-13)';
        case '1':
        case '2':
        case '3':
        case '4':
        case '5':
        case '6':
        case '7':
        case '8':
            return 'GTIN-14 (ITF-14)';
        case '9':
            return 'GTIN-14 (Coupon)';
        default:
            return 'GTIN-14';
    }
}
