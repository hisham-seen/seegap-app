<?php
/*
 * Database Update Script
 * This script imports the updated dump.sql file to add AIX tables
 */

// Include configuration
require_once 'config.php';

echo "Starting database update...\n";

try {
    // Connect to MySQL (using localhost for Docker environment)
    $pdo = new PDO(
        'mysql:host=localhost;dbname=' . DATABASE_NAME . ';charset=utf8mb4',
        DATABASE_USERNAME,
        DATABASE_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

    echo "Connected to database successfully.\n";

    // Read the dump file
    $dumpFile = 'install/dump.sql';
    if (!file_exists($dumpFile)) {
        throw new Exception("Dump file not found: $dumpFile");
    }

    $sql = file_get_contents($dumpFile);
    if ($sql === false) {
        throw new Exception("Could not read dump file");
    }

    echo "Dump file loaded successfully.\n";

    // Split SQL by separator
    $statements = explode('-- SEPARATOR --', $sql);
    $successCount = 0;
    $errorCount = 0;

    echo "Processing " . count($statements) . " SQL statements...\n";

    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        
        // Skip empty statements
        if (empty($statement)) {
            continue;
        }

        // Skip comments
        if (strpos($statement, '--') === 0) {
            continue;
        }

        try {
            // Execute the statement
            $pdo->exec($statement);
            $successCount++;
            
            // Show progress for every 10 statements
            if (($index + 1) % 10 === 0) {
                echo "Processed " . ($index + 1) . " statements...\n";
            }
        } catch (PDOException $e) {
            $errorCount++;
            
            // Check if it's a "table already exists" error - these are expected
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "Table already exists (skipping): " . substr($statement, 0, 50) . "...\n";
            } else {
                echo "Error executing statement " . ($index + 1) . ": " . $e->getMessage() . "\n";
                echo "Statement: " . substr($statement, 0, 100) . "...\n";
            }
        }
    }

    echo "\nDatabase update completed!\n";
    echo "Successful statements: $successCount\n";
    echo "Errors/Skipped: $errorCount\n";

    // Verify AIX tables were created
    echo "\nVerifying AIX tables...\n";
    $aixTables = [
        'templates_categories',
        'templates', 
        'documents',
        'images',
        'chats_assistants',
        'chats'
    ];

    foreach ($aixTables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $result = $stmt->fetch();
            echo "✓ Table '$table' exists with {$result['count']} records\n";
        } catch (PDOException $e) {
            echo "✗ Table '$table' not found or error: " . $e->getMessage() . "\n";
        }
    }

    // Check AIX settings
    try {
        $stmt = $pdo->prepare("SELECT value FROM settings WHERE `key` = 'aix'");
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            echo "✓ AIX settings found in database\n";
        } else {
            echo "✗ AIX settings not found\n";
        }
    } catch (PDOException $e) {
        echo "✗ Error checking AIX settings: " . $e->getMessage() . "\n";
    }

    echo "\nDatabase update process completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
