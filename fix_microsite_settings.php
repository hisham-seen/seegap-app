<?php
require_once 'app/init.php';

echo "Checking microsite block settings...\n";

// Get current settings
$settings = settings();

// Check if we have the old biolink settings
if (isset($settings->links->available_biolink_blocks)) {
    echo "Found old biolink_blocks settings, migrating to microsite_blocks...\n";
    
    // Get the old settings
    $old_settings = $settings->links->available_biolink_blocks;
    
    // Update the database to use microsite_blocks instead
    $new_settings = json_encode($old_settings);
    
    // Update the database
    database()->query("UPDATE `settings` SET `value` = JSON_SET(`value`, '$.available_microsite_blocks', JSON_EXTRACT(`value`, '$.available_biolink_blocks')) WHERE `key` = 'links'");
    database()->query("UPDATE `settings` SET `value` = JSON_REMOVE(`value`, '$.available_biolink_blocks') WHERE `key` = 'links'");
    
    echo "Settings migrated successfully!\n";
    
    // Clear cache
    cache()->clear();
    echo "Cache cleared!\n";
} else if (isset($settings->links->available_microsite_blocks)) {
    echo "Microsite block settings already exist and are correct.\n";
} else {
    echo "No microsite block settings found. This might be the issue.\n";
    
    // Create default microsite block settings
    $default_blocks = [
        'link' => true,
        'heading' => true,
        'paragraph' => true,
        'avatar' => true,
        'image' => true,
        'socials' => true,
        'email_collector' => true,
        'phone_collector' => true,
        'contact_collector' => true,
        'feedback_collector' => true,
        'paypal' => true,
    ];
    
    $default_settings = json_encode($default_blocks);
    database()->query("UPDATE `settings` SET `value` = JSON_SET(`value`, '$.available_microsite_blocks', CAST('{$default_settings}' AS JSON)) WHERE `key` = 'links'");
    
    echo "Default microsite block settings created!\n";
    
    // Clear cache
    cache()->clear();
    echo "Cache cleared!\n";
}

echo "Done!\n";
?>
