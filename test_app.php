<?php
// Test the SeeGap application routing specifically for admin
const DEBUG = 1;
const MYSQL_DEBUG = 0;
const LOGGING = 1;
const CACHE = 1;
const SEEGAP = 66;

require_once realpath(__DIR__) . '/app/init.php';

echo "<h2>SeeGap Application Test</h2>";

// Test URL parsing
echo "<h3>URL Parsing Test</h3>";
$_GET['seegap'] = 'admin';
$_SERVER['HTTP_HOST'] = 'localhost:8080'; // Set HTTP_HOST to avoid warning
\SeeGap\Router::$params = [];
$params = \SeeGap\Router::parse_url();
echo "Parsed params for 'admin': " . print_r($params, true) . "<br>";
echo "Params after parsing: " . print_r(\SeeGap\Router::$params, true) . "<br>";

// Test language parsing
\SeeGap\Router::parse_language();
echo "Language code: " . \SeeGap\Router::$language_code . "<br>";
echo "Params after language parsing: " . print_r(\SeeGap\Router::$params, true) . "<br>";

// Test controller parsing
\SeeGap\Router::parse_controller();
echo "Path: " . \SeeGap\Router::$path . "<br>";
echo "Controller key: " . \SeeGap\Router::$controller_key . "<br>";
echo "Controller: " . \SeeGap\Router::$controller . "<br>";
echo "Params after controller parsing: " . print_r(\SeeGap\Router::$params, true) . "<br>";

// Check if admin routes exist
echo "<h3>Admin Routes Check</h3>";
if (isset(\SeeGap\Router::$routes['admin'])) {
    echo "Admin routes found: " . count(\SeeGap\Router::$routes['admin']) . " routes<br>";
    echo "Available admin routes: " . implode(', ', array_keys(\SeeGap\Router::$routes['admin'])) . "<br>";
} else {
    echo "ERROR: Admin routes not found!<br>";
}

// Check if AdminIndex controller exists
echo "<h3>Controller File Check</h3>";
$admin_index_path = APP_PATH . 'controllers/admin/AdminIndex.php';
if (file_exists($admin_index_path)) {
    echo "AdminIndex controller exists at: " . $admin_index_path . "<br>";
    echo "File is readable: " . (is_readable($admin_index_path) ? 'Yes' : 'No') . "<br>";
} else {
    echo "ERROR: AdminIndex controller not found at: " . $admin_index_path . "<br>";
}

// Test controller instantiation
echo "<h3>Controller Instantiation Test</h3>";
try {
    $controller = \SeeGap\Router::get_controller('AdminIndex', 'admin');
    echo "AdminIndex controller instantiated successfully<br>";
    echo "Controller class: " . get_class($controller) . "<br>";
    
    // Check if index method exists
    if (method_exists($controller, 'index')) {
        echo "Index method exists in controller<br>";
    } else {
        echo "ERROR: Index method not found in controller<br>";
    }
} catch (Exception $e) {
    echo "ERROR instantiating controller: " . $e->getMessage() . "<br>";
}

echo "<h3>Authentication Check</h3>";
echo "Authentication check disabled for routes: " . (\SeeGap\Router::$controller_settings['no_authentication_check'] ? 'Yes' : 'No') . "<br>";
echo "Required authentication level: " . (\SeeGap\Router::$controller_settings['authentication'] ?? 'None') . "<br>";
?>
