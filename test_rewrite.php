<?php
// Test file to check if mod_rewrite is working
echo "<h1>Rewrite Test</h1>";
echo "<p>If you can access this file via /test_rewrite (without .php), then mod_rewrite is working.</p>";
echo "<p>Current REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</p>";
echo "<p>Current GET parameters: " . print_r($_GET, true) . "</p>";

if (isset($_GET['seegap'])) {
    echo "<p style='color: green;'><strong>SUCCESS:</strong> The 'seegap' parameter is being passed correctly!</p>";
    echo "<p>seegap value: " . htmlspecialchars($_GET['seegap']) . "</p>";
} else {
    echo "<p style='color: red;'><strong>ISSUE:</strong> The 'seegap' parameter is not being passed. This indicates mod_rewrite might not be working.</p>";
}

// Check if mod_rewrite is loaded
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p style='color: green;'><strong>mod_rewrite is loaded</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>mod_rewrite is NOT loaded</strong></p>";
    }
} else {
    echo "<p style='color: orange;'><strong>Cannot check if mod_rewrite is loaded (apache_get_modules not available)</strong></p>";
}
?>
