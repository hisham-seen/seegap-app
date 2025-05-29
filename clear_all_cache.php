<?php
require_once 'app/init.php';

echo "Clearing all application cache...\n";

try {
    // Clear the application cache
    cache()->clear();
    echo "✓ Application cache cleared\n";
    
    // Clear file-based cache
    $cache_files_deleted = 0;
    
    // Clear uploads cache
    $uploads_cache_dir = 'uploads/cache';
    if (is_dir($uploads_cache_dir)) {
        $files = glob($uploads_cache_dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $cache_files_deleted++;
            }
        }
    }
    
    // Clear language cache
    $lang_cache_dir = 'app/languages/cache';
    if (is_dir($lang_cache_dir)) {
        $files = glob($lang_cache_dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $cache_files_deleted++;
            }
        }
    }
    
    echo "✓ File cache cleared ($cache_files_deleted files deleted)\n";
    
    echo "Cache clearing complete!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
