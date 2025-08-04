<?php
require_once 'app/init.php';

echo "=== Direct Admin Test ===\n";

// Simulate admin access
$_GET['seegap'] = 'admin';
$_SERVER['HTTP_HOST'] = 'localhost:8080';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/admin';

// Set up a fake admin user session
$_SESSION = [];
$_SESSION['user_id'] = 1;
$_SESSION['type'] = 1; // Admin type

try {
    // Test if we can create the AdminIndex controller directly
    $admin_controller = new \SeeGap\Controllers\AdminIndex();
    echo "AdminIndex controller created successfully\n";
    
    // Try to call the index method
    ob_start();
    $admin_controller->index();
    $output = ob_get_clean();
    
    echo "AdminIndex->index() executed successfully\n";
    echo "Output length: " . strlen($output) . " characters\n";
    
    if (strlen($output) > 0) {
        echo "First 200 characters of output:\n";
        echo substr($output, 0, 200) . "...\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "=== Test Complete ===\n";
?>
