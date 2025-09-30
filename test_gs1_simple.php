<?php
// Simple test for GS1 function
echo "Testing GS1 function directly...\n";

// Test the function directly without including init.php
function parse_gs1_digital_link($url) {
    // Remove domain and get path
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '';

    // Match GS1 Digital Link pattern: /01/{gtin}
    if (preg_match('/^\/01\/(\d+)(?:\/.*)?$/', $path, $matches)) {
        $gtin = preg_replace('/[^0-9]/', '', $matches[1]);

        if (!empty($gtin)) {
            return [
                'ai' => '01', // Application Identifier for GTIN
                'gtin' => $gtin,
                'query' => $parsed['query'] ?? null
            ];
        }
    }

    return false;
}

$test_url = '/01/05678901234567';
echo "Testing URL: $test_url\n";

$result = parse_gs1_digital_link($test_url);
echo "Result: ";
var_dump($result);

if ($result) {
    echo "✓ SUCCESS: GS1 Digital Link detected!\n";
    echo "AI: " . $result['ai'] . "\n";
    echo "GTIN: " . $result['gtin'] . "\n";
} else {
    echo "✗ FAILED: GS1 Digital Link NOT detected\n";
}

echo "\nTesting with REQUEST_URI format...\n";
$_SERVER['REQUEST_URI'] = '/01/05678901234567';
$result2 = parse_gs1_digital_link($_SERVER['REQUEST_URI']);
echo "Result: ";
var_dump($result2);

echo "\nDone.\n";
