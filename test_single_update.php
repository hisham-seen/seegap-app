<?php
/**
 * Test Single File Update Script
 * 
 * This script tests the display settings replacement on a single file first.
 */

// Configuration
$testFile = 'themes/phoenix/views/link/settings/microsite_blocks/paragraph/paragraph_update_form.php';
$backupDir = 'test_backup_' . date('Y-m-d_H-i-s');
$logFile = 'test_update_log.txt';

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

if (!file_exists($testFile)) {
    logMessage("ERROR: Test file not found: $testFile");
    exit(1);
}

logMessage("Testing single file update on: $testFile");

// Read original content
$originalContent = file_get_contents($testFile);
if ($originalContent === false) {
    logMessage("ERROR: Could not read file: $testFile");
    exit(1);
}

// Create backup
$backupFile = $backupDir . '/paragraph_update_form.php';
if (!file_put_contents($backupFile, $originalContent)) {
    logMessage("ERROR: Could not create backup: $backupFile");
    exit(1);
}

logMessage("Backup created: $backupFile");

// Check if file contains display settings
if (strpos($originalContent, 'display_settings_container_') === false) {
    logMessage("ERROR: No display settings found in test file");
    exit(1);
}

logMessage("Display settings found in file");

// Define the pattern to match the display settings section
$pattern = '/\s*<button[^>]*data-target="#[^"]*display_settings_container_[^"]*"[^>]*>.*?<\/div>\s*<\/div>\s*(?=\s*<div class="mt-4">)/s';

// Replacement include statement
$replacement = "\n    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>\n";

// Show what we're looking for
logMessage("Looking for pattern starting with: <button...display_settings_container_");

// Perform replacement
$newContent = preg_replace($pattern, $replacement, $originalContent);

// Check if replacement was successful
if ($newContent === null) {
    logMessage("ERROR: Regex replacement failed");
    exit(1);
}

if ($newContent === $originalContent) {
    logMessage("WARNING: No changes made (pattern not matched)");
    
    // Let's try to find the exact section manually
    $pos = strpos($originalContent, 'display_settings_container_');
    if ($pos !== false) {
        $context = substr($originalContent, max(0, $pos - 100), 200);
        logMessage("Context around display_settings_container_:");
        logMessage($context);
    }
    exit(1);
}

logMessage("Replacement successful - content changed");

// Validate PHP syntax
$tempFile = tempnam(sys_get_temp_dir(), 'php_syntax_check');
file_put_contents($tempFile, $newContent);

$output = [];
$returnCode = 0;
exec("php -l $tempFile 2>&1", $output, $returnCode);
unlink($tempFile);

if ($returnCode !== 0) {
    logMessage("ERROR: PHP syntax error in processed file");
    logMessage("Syntax check output: " . implode("\n", $output));
    exit(1);
}

logMessage("PHP syntax validation passed");

// Show a preview of the changes
$originalLines = explode("\n", $originalContent);
$newLines = explode("\n", $newContent);

logMessage("Original file lines: " . count($originalLines));
logMessage("New file lines: " . count($newLines));

// Write updated content
if (file_put_contents($testFile, $newContent) === false) {
    logMessage("ERROR: Could not write updated file: $testFile");
    exit(1);
}

logMessage("SUCCESS: Test file updated successfully!");
logMessage("Backup available at: $backupFile");
logMessage("To restore: cp $backupFile $testFile");

exit(0);
?>
