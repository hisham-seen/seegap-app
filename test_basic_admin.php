<?php
// Basic test without full initialization
echo "=== Basic Admin Test ===\n";

// Check if AdminIndex class exists
if (file_exists('app/controllers/admin/AdminIndex.php')) {
    echo "AdminIndex.php file exists\n";
    
    // Try to include it
    require_once 'app/init.php';
    
    if (class_exists('SeeGap\Controllers\AdminIndex')) {
        echo "AdminIndex class loaded successfully\n";
        
        // Check if the index method exists
        $reflection = new ReflectionClass('SeeGap\Controllers\AdminIndex');
        if ($reflection->hasMethod('index')) {
            echo "AdminIndex->index() method exists\n";
        } else {
            echo "AdminIndex->index() method NOT found\n";
        }
        
    } else {
        echo "AdminIndex class NOT found\n";
    }
} else {
    echo "AdminIndex.php file NOT found\n";
}

// Check if admin view exists
if (file_exists('themes/phoenix/views/admin/index/index.php')) {
    echo "Admin index view exists\n";
} else {
    echo "Admin index view NOT found\n";
}

echo "=== Test Complete ===\n";
?>
