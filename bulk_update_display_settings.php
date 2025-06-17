<?php
/**
 * Bulk Update Display Settings Script
 * 
 * This script replaces duplicated display settings HTML in all microsite block forms
 * with a single include statement to the unified display_settings.php partial.
 * 
 * Usage: php bulk_update_display_settings.php
 */

// Configuration
$blocksDir = 'themes/phoenix/views/link/settings/microsite_blocks';
$backupDir = 'backup_display_settings_' . date('Y-m-d_H-i-s');
$logFile = 'bulk_update_log.txt';

// Initialize log
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    echo "$message\n";
}

// Create backup directory
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
    logMessage("Created backup directory: $backupDir");
}

// Find all update form files
$pattern = $blocksDir . '/*/*_update_form.php';
$files = glob($pattern);

if (empty($files)) {
    logMessage("ERROR: No files found matching pattern: $pattern");
    exit(1);
}

logMessage("Found " . count($files) . " files to process");

// Statistics
$stats = [
    'processed' => 0,
    'updated' => 0,
    'skipped' => 0,
    'errors' => 0
];

foreach ($files as $file) {
    $stats['processed']++;
    $filename = basename($file);
    $blockName = basename(dirname($file));
    
    logMessage("Processing: $blockName/$filename");
    
    // Read original content
    $originalContent = file_get_contents($file);
    if ($originalContent === false) {
        logMessage("ERROR: Could not read file: $file");
        $stats['errors']++;
        continue;
    }
    
    // Create backup
    $backupFile = $backupDir . '/' . $blockName . '_' . $filename;
    if (!file_put_contents($backupFile, $originalContent)) {
        logMessage("ERROR: Could not create backup: $backupFile");
        $stats['errors']++;
        continue;
    }
    
    // Check if file contains display settings
    if (strpos($originalContent, 'display_settings_container_') === false) {
        logMessage("SKIP: No display settings found in $blockName/$filename");
        $stats['skipped']++;
        continue;
    }
    
    // Define the pattern to match the display settings section
    // This matches from the button through all nested divs to the final closing div
    $pattern = '/\s*<button[^>]*data-target="#[^"]*display_settings_container_[^"]*"[^>]*>.*?<\/div>\s*<\/div>\s*(?=\s*<div class="mt-4">)/s';
    
    // Replacement include statement
    $replacement = "\n    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>\n";
    
    // Perform replacement
    $newContent = preg_replace($pattern, $replacement, $originalContent);
    
    // Check if replacement was successful
    if ($newContent === null) {
        logMessage("ERROR: Regex replacement failed for $blockName/$filename");
        $stats['errors']++;
        continue;
    }
    
    if ($newContent === $originalContent) {
        logMessage("SKIP: No changes made to $blockName/$filename (pattern not matched)");
        $stats['skipped']++;
        continue;
    }
    
    // Validate PHP syntax
    $tempFile = tempnam(sys_get_temp_dir(), 'php_syntax_check');
    file_put_contents($tempFile, $newContent);
    
    $output = [];
    $returnCode = 0;
    exec("php -l $tempFile 2>&1", $output, $returnCode);
    unlink($tempFile);
    
    if ($returnCode !== 0) {
        logMessage("ERROR: PHP syntax error in processed $blockName/$filename");
        logMessage("Syntax check output: " . implode("\n", $output));
        $stats['errors']++;
        continue;
    }
    
    // Write updated content
    if (file_put_contents($file, $newContent) === false) {
        logMessage("ERROR: Could not write updated file: $file");
        $stats['errors']++;
        continue;
    }
    
    logMessage("SUCCESS: Updated $blockName/$filename");
    $stats['updated']++;
}

// Final report
logMessage("\n=== BULK UPDATE COMPLETE ===");
logMessage("Files processed: " . $stats['processed']);
logMessage("Files updated: " . $stats['updated']);
logMessage("Files skipped: " . $stats['skipped']);
logMessage("Errors: " . $stats['errors']);
logMessage("Backup directory: $backupDir");

if ($stats['errors'] > 0) {
    logMessage("\nWARNING: Some files had errors. Check the log and backups.");
    exit(1);
} else {
    logMessage("\nSUCCESS: All files processed successfully!");
    exit(0);
}
?>
