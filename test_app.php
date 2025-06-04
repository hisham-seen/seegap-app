<?php
// Simple test to check if the application loads without errors

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>SeeGap Application Test</h1>";

try {
    // Test basic constants and includes
    if (!defined('DEBUG')) define('DEBUG', 0);
    if (!defined('MYSQL_DEBUG')) define('MYSQL_DEBUG', 0);
    if (!defined('LOGGING')) define('LOGGING', 1);
    if (!defined('CACHE')) define('CACHE', 1);
    if (!defined('SEEGAP')) define('SEEGAP', 66);

    echo "<p style='color: green;'>✓ Constants defined successfully</p>";

    // Test app initialization
    require_once realpath(__DIR__) . '/app/init.php';
    echo "<p style='color: green;'>✓ App initialization successful</p>";

    // Test basic routing
    $_GET['seegap'] = 'index';
    echo "<p style='color: green;'>✓ Routing parameter set</p>";

    // Test App instantiation
    $App = new SeeGap\App();
    echo "<p style='color: green;'>✓ SeeGap App instantiated successfully</p>";

    echo "<h2 style='color: green;'>SUCCESS: Application is working!</h2>";
    echo "<p>You can now access your application at:</p>";
    echo "<ul>";
    echo "<li><a href='index.php?seegap=dashboard'>Dashboard (with parameter)</a></li>";
    echo "<li><a href='dashboard'>Dashboard (pretty URL - if mod_rewrite works)</a></li>";
    echo "<li><a href='index.php?seegap=login'>Login</a></li>";
    echo "<li><a href='index.php?seegap=register'>Register</a></li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "<p style='color: red;'><strong>ERROR:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<p style='color: red;'><strong>FATAL ERROR:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
