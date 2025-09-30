<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

defined('SEEGAP') || die();

/**
 * Parse GS1 Digital Link from URL
 * 
 * @param string $url The URL to parse
 * @return array|false Returns array with 'ai' and 'gtin' if valid GS1 Digital Link, false otherwise
 */
function parse_gs1_digital_link($url) {
    // Remove query parameters and fragments
    $path = parse_url($url, PHP_URL_PATH);
    
    // Remove leading slash
    $path = ltrim($path, '/');
    
    // Split into segments
    $segments = explode('/', $path);
    
    // Check if we have at least 2 segments (AI and value)
    if (count($segments) < 2) {
        return false;
    }
    
    $ai = $segments[0];
    $value = $segments[1];
    
    // Validate GS1 Application Identifiers
    $valid_ais = [
        '01',   // GTIN
        '414',  // GLN
        '00',   // SSCC
        '8003', // GRAI
        '8004', // GIAI
        '8018', // GSRN
        '253',  // GDTI
        '255',  // GCN
        '401',  // GINC
        '402',  // GSIN
    ];
    
    if (!in_array($ai, $valid_ais)) {
        return false;
    }
    
    // Basic validation for GTIN (most common case)
    if ($ai === '01') {
        // GTIN should be 8, 12, 13, or 14 digits
        if (!preg_match('/^\d{8}$|^\d{12}$|^\d{13}$|^\d{14}$/', $value)) {
            return false;
        }
    }
    
    return [
        'ai' => $ai,
        'gtin' => $value, // Keep as 'gtin' for backward compatibility, even for other AIs
        'value' => $value
    ];
}
