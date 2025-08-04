<?php
// Simple test to debug form submission
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing form submission debugging...\n";

// Check if we can access the database
try {
    // Simulate the form submission data
    $test_data = [
        'microsite_block_id' => 1, // Replace with actual ID
        'name' => 'Test Form',
        'button_text' => 'Submit Test',
        'text_color' => '#000000',
        'background_color' => '#ffffff'
    ];
    
    echo "Test data prepared:\n";
    print_r($test_data);
    
    // Check if the FormBlock class can be loaded
    if (file_exists('app/controllers/microsite-blocks/blocks/FormBlock.php')) {
        echo "FormBlock.php file exists\n";
    } else {
        echo "FormBlock.php file NOT found\n";
    }
    
    // Check if the microsite_blocks table exists
    // You would need to add database connection here
    echo "Database connection test would go here\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
?>
