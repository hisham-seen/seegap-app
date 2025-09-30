<?php
// Debug script to test GS1 Digital Link parsing

// Include the necessary files
require_once 'app/init.php';

echo "=== GS1 Digital Link Debug Test ===\n\n";

// Test the parse_gs1_digital_link function
$test_url = '/01/05678901234567';
echo "Testing URL: $test_url\n";

// Check if function exists
if (function_exists('parse_gs1_digital_link')) {
    echo "✓ parse_gs1_digital_link function exists\n";
    
    // Test the function
    $result = parse_gs1_digital_link($test_url);
    echo "Function result: ";
    var_dump($result);
    
    if ($result) {
        echo "✓ GS1 Digital Link detected successfully!\n";
        echo "AI: " . $result['ai'] . "\n";
        echo "GTIN: " . $result['gtin'] . "\n";
    } else {
        echo "✗ GS1 Digital Link NOT detected\n";
    }
} else {
    echo "✗ parse_gs1_digital_link function does NOT exist\n";
}

echo "\n=== Testing with full URL ===\n";
$full_url = 'http://localhost/01/05678901234567';
echo "Testing full URL: $full_url\n";

if (function_exists('parse_gs1_digital_link')) {
    $result = parse_gs1_digital_link($full_url);
    echo "Function result: ";
    var_dump($result);
    
    if ($result) {
        echo "✓ GS1 Digital Link detected with full URL!\n";
    } else {
        echo "✗ GS1 Digital Link NOT detected with full URL\n";
    }
}

echo "\n=== Testing REQUEST_URI simulation ===\n";
$_SERVER['REQUEST_URI'] = '/01/05678901234567';
echo "Simulated REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";

if (function_exists('parse_gs1_digital_link')) {
    $result = parse_gs1_digital_link($_SERVER['REQUEST_URI']);
    echo "Function result: ";
    var_dump($result);
    
    if ($result) {
        echo "✓ GS1 Digital Link detected with REQUEST_URI!\n";
    } else {
        echo "✗ GS1 Digital Link NOT detected with REQUEST_URI\n";
    }
}

echo "\n=== End Debug Test ===\n";
