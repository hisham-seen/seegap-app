<?php
/**
 * Database Reset Script (PHP Version)
 * 
 * This script resets the database using the same method as install.php:
 * 1. Splits the dump file by "-- SEPARATOR --"
 * 2. Executes each query separately
 * 3. Handles problematic queries gracefully
 */

// Load configuration
require_once 'config.php';

// Colors for CLI output
class Colors {
    const RED = "\033[0;31m";
    const GREEN = "\033[0;32m";
    const YELLOW = "\033[1;33m";
    const BLUE = "\033[0;34m";
    const NC = "\033[0m"; // No Color
}

function print_status($message) {
    echo Colors::BLUE . "[INFO]" . Colors::NC . " $message\n";
}

function print_success($message) {
    echo Colors::GREEN . "[SUCCESS]" . Colors::NC . " $message\n";
}

function print_warning($message) {
    echo Colors::YELLOW . "[WARNING]" . Colors::NC . " $message\n";
}

function print_error($message) {
    echo Colors::RED . "[ERROR]" . Colors::NC . " $message\n";
}

function confirm_reset() {
    echo "\n";
    print_warning("⚠️  WARNING: This will completely reset your database!");
    print_warning("⚠️  All existing data will be permanently lost!");
    print_warning("⚠️  Database: " . DATABASE_NAME);
    print_warning("⚠️  This action cannot be undone!");
    echo "\n";
    
    echo "Are you absolutely sure you want to reset the database? Type 'RESET DATABASE' to confirm: ";
    $confirmation = trim(fgets(STDIN));
    
    if ($confirmation !== 'RESET DATABASE') {
        print_status("Operation cancelled by user.");
        exit(0);
    }
}

function connect_database() {
    mysqli_report(MYSQLI_REPORT_OFF);
    
    // Try different hosts for database connection
    $hosts = [DATABASE_SERVER, 'localhost', '127.0.0.1'];
    
    foreach ($hosts as $host) {
        try {
            $database = new mysqli(
                $host,
                DATABASE_USERNAME,
                DATABASE_PASSWORD,
                DATABASE_NAME
            );
            
            if (!$database->connect_error) {
                $database->set_charset('utf8mb4');
                print_status("Connected to database at: $host");
                return $database;
            }
        } catch (Exception $e) {
            // Continue to next host
            continue;
        }
    }
    
    print_error("Database connection failed to all hosts: " . implode(', ', $hosts));
    print_error("Make sure MySQL is running and accessible");
    print_status("If using Docker, try running this script inside the PHP container:");
    print_status("  docker exec -it appseegapcom-php-1 php reset_database_php.php --force");
    exit(1);
}

function drop_all_tables($database) {
    print_status("Dropping all existing tables and views...");
    
    // Get all tables
    $result = $database->query("SHOW TABLES");
    $tables = [];
    
    if ($result) {
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
    }
    
    // Disable foreign key checks
    $database->query("SET FOREIGN_KEY_CHECKS = 0");
    
    // Drop all tables
    foreach ($tables as $table) {
        $database->query("DROP TABLE IF EXISTS `$table`");
        if ($database->error) {
            print_warning("Could not drop table $table: " . $database->error);
        }
    }
    
    // Drop all views
    $result = $database->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
    if ($result) {
        while ($row = $result->fetch_array()) {
            $database->query("DROP VIEW IF EXISTS `{$row[0]}`");
        }
    }
    
    // Re-enable foreign key checks
    $database->query("SET FOREIGN_KEY_CHECKS = 1");
    
    // Verify tables are dropped
    $result = $database->query("SHOW TABLES");
    $remaining_tables = $result ? $result->num_rows : 0;
    print_status("Tables remaining after drop: $remaining_tables");
    
    return $remaining_tables == 0;
}

function restore_database($database) {
    print_status("Restoring database from installation dump...");
    
    $dump_file = 'install/dump.sql';
    if (!file_exists($dump_file)) {
        print_error("Dump file not found: $dump_file");
        return false;
    }
    
    // Read and process dump file exactly like install.php
    $dump_content = file_get_contents($dump_file);
    if ($dump_content === false) {
        print_error("Could not read dump file");
        return false;
    }
    
    // Split by separator exactly like install.php
    $dump = array_filter(explode('-- SEPARATOR --', $dump_content));
    
    print_status("Found " . count($dump) . " SQL segments to execute");
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($dump as $index => $query) {
        $query = trim($query);
        if (empty($query)) {
            continue;
        }
        
        // Execute query
        $result = $database->query($query);
        
        if ($database->error) {
            $error_count++;
            print_warning("Query " . ($index + 1) . " failed: " . $database->error);
            
            // Log the problematic query for debugging
            if (strlen($query) > 200) {
                print_warning("Query preview: " . substr($query, 0, 200) . "...");
            } else {
                print_warning("Query: $query");
            }
        } else {
            $success_count++;
        }
    }
    
    print_status("Executed $success_count queries successfully");
    if ($error_count > 0) {
        print_warning("$error_count queries failed");
    }
    
    return $error_count == 0;
}

function verify_installation($database) {
    print_status("Verifying database installation...");
    
    // Count tables
    $result = $database->query("SHOW TABLES");
    $table_count = $result ? $result->num_rows : 0;
    
    print_status("Final number of tables: $table_count");
    
    if ($table_count > 0) {
        // Show some key tables
        print_status("Key tables found:");
        $key_tables = ['users', 'links', 'products', 'settings'];
        
        foreach ($key_tables as $table) {
            $result = $database->query("SHOW TABLES LIKE '$table'");
            if ($result && $result->num_rows > 0) {
                print_success("  ✓ $table");
            } else {
                print_warning("  ✗ $table (missing)");
            }
        }
        
        return true;
    }
    
    return false;
}

// Main execution
function main() {
    echo "=============================================================================\n";
    echo "                    SeeGap Database Reset Script (PHP)\n";
    echo "=============================================================================\n\n";
    
    print_status("This script will reset your database using the same method as install.php:");
    print_status("1. Drop all existing tables and views");
    print_status("2. Split dump file by '-- SEPARATOR --'");
    print_status("3. Execute each SQL segment separately");
    print_status("4. Handle problematic queries gracefully");
    echo "\n";
    
    print_status("Database Configuration:");
    print_status("  Host: " . DATABASE_SERVER);
    print_status("  User: " . DATABASE_USERNAME);
    print_status("  Database: " . DATABASE_NAME);
    echo "\n";
    
    // Check if running from command line
    if (php_sapi_name() !== 'cli') {
        print_error("This script must be run from the command line!");
        exit(1);
    }
    
    // Check for force flag
    $force = in_array('--force', $GLOBALS['argv'] ?? []);
    
    if (!$force) {
        confirm_reset();
    } else {
        print_warning("Running in force mode - skipping confirmation");
    }
    
    echo "\n";
    print_status("Starting database reset process...");
    
    // Connect to database
    $database = connect_database();
    print_success("Connected to database successfully");
    
    // Step 1: Drop all tables
    if (!drop_all_tables($database)) {
        print_error("Failed to drop all tables");
        exit(1);
    }
    print_success("All tables dropped successfully");
    
    // Step 2: Restore from dump
    if (!restore_database($database)) {
        print_warning("Database restoration completed with some errors");
    } else {
        print_success("Database restoration completed successfully");
    }
    
    // Step 3: Verify installation
    if (verify_installation($database)) {
        echo "\n";
        print_success("✅ Database reset completed successfully!");
        print_status("Your SeeGap installation should now work properly!");
        echo "\n";
        print_status("Next steps:");
        print_status("  1. Visit your SeeGap installation URL");
        print_status("  2. Log in or set up your admin account");
        print_status("  3. Configure your application settings");
    } else {
        print_error("❌ Database reset may have failed - verification failed!");
        exit(1);
    }
    
    $database->close();
}

// Handle command line arguments
if (php_sapi_name() === 'cli') {
    $args = $GLOBALS['argv'] ?? [];
    
    if (in_array('-h', $args) || in_array('--help', $args)) {
        echo "Usage: php reset_database_php.php [OPTIONS]\n\n";
        echo "Options:\n";
        echo "  -h, --help    Show this help message\n";
        echo "  --force       Skip confirmation prompts\n\n";
        echo "This script resets the database using the same method as install.php\n";
        exit(0);
    }
    
    main();
} else {
    print_error("This script must be run from the command line!");
    echo "Usage: php reset_database_php.php\n";
}
