<?php
// Simple routing test without full app initialization
const SEEGAP = 66;

// Mock the input_clean function
function input_clean($input) {
    return trim($input);
}

// Test URL parsing logic
$_GET['seegap'] = 'admin';
$params = explode('/', input_clean(rtrim($_GET['seegap'], '/')));

echo "<h2>Simple Routing Test</h2>";
echo "Original seegap parameter: '" . $_GET['seegap'] . "'<br>";
echo "After rtrim: '" . rtrim($_GET['seegap'], '/') . "'<br>";
echo "After input_clean: '" . input_clean(rtrim($_GET['seegap'], '/')) . "'<br>";
echo "After explode: " . print_r($params, true) . "<br>";

// Test the admin path detection logic
$test_params = $params;
$path = '';

if(!empty($test_params[0])) {
    echo "First param is not empty: '" . $test_params[0] . "'<br>";
    
    if(in_array($test_params[0], ['admin', 'admin-api', 'l', 'api'])) {
        $path = $test_params[0];
        echo "Path set to: '" . $path . "'<br>";
        
        unset($test_params[0]);
        $test_params = array_values($test_params);
        echo "Params after removing path: " . print_r($test_params, true) . "<br>";
    } else {
        echo "First param is not in admin paths<br>";
    }
} else {
    echo "First param is empty<br>";
}

echo "Final path: '" . $path . "'<br>";
echo "Final params: " . print_r($test_params, true) . "<br>";
?>
