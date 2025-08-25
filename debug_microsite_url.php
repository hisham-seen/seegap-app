<?php
// Debug script to test URL preservation logic

// Simulate the URL preservation logic from MicrositeHandler
function test_url_preservation($post_url, $existing_url) {
    echo "POST URL: " . var_export($post_url, true) . "\n";
    echo "Existing URL: " . var_export($existing_url, true) . "\n";
    
    // This is the logic from MicrositeHandler.php lines 108-114
    if($post_url == $existing_url) {
        $url = $existing_url; // Keep the original URL unchanged
        echo "URLs match - preserving original: " . var_export($url, true) . "\n";
    } else {
        echo "URLs don't match - would process new URL\n";
        if($post_url) {
            $url = $post_url; // In real code, this would go through validation
            echo "Using new URL: " . var_export($url, true) . "\n";
        } else {
            echo "Would generate random URL\n";
        }
    }
    
    return $url ?? 'random-url';
}

// Test cases
echo "=== Test Case 1: Identical URLs ===\n";
test_url_preservation('my-microsite', 'my-microsite');

echo "\n=== Test Case 2: Different URLs ===\n";
test_url_preservation('my-new-microsite', 'my-microsite');

echo "\n=== Test Case 3: Empty POST URL ===\n";
test_url_preservation('', 'my-microsite');

echo "\n=== Test Case 4: URL with special characters ===\n";
test_url_preservation('my-microsite!@#', 'my-microsite');

echo "\n=== Test Case 5: URL with spaces ===\n";
test_url_preservation('my microsite', 'my-microsite');
?>
