<?php
/**
 * Web-based Database Reset Script
 * 
 * This script resets the database using the same method as install.php:
 * 1. Splits the dump file by "-- SEPARATOR --"
 * 2. Executes each query separately
 * 3. Handles problematic queries gracefully
 * 
 * Access via: http://localhost/install/reset_db.php
 */

const SEEGAP = 66;
define('ROOT', realpath(__DIR__ . '/..') . '/');

// Load configuration if it exists
$config_file = ROOT . 'config.php';
if (file_exists($config_file)) {
    require_once $config_file;
} else {
    die('Configuration file not found. Please run the installation first.');
}

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');

// Check if this is a POST request (form submission)
$is_post = $_SERVER['REQUEST_METHOD'] === 'POST';
$action = $_POST['action'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SeeGap Database Reset</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .form-group {
            margin: 20px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .log {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
            margin: 20px 0;
        }
        .status-info { color: #0c5460; }
        .status-success { color: #155724; }
        .status-warning { color: #856404; }
        .status-error { color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 SeeGap Database Reset</h1>
        
        <?php if (!$is_post): ?>
            <!-- Initial form -->
            <div class="warning">
                <strong>⚠️ WARNING:</strong> This will completely reset your database!
                <ul>
                    <li>All existing data will be permanently lost</li>
                    <li>Database: <strong><?= htmlspecialchars(DATABASE_NAME) ?></strong></li>
                    <li>This action cannot be undone</li>
                </ul>
            </div>
            
            <div class="info">
                <strong>ℹ️ How it works:</strong>
                <ul>
                    <li>Drops all existing tables and views</li>
                    <li>Splits dump file by "-- SEPARATOR --" (same as install.php)</li>
                    <li>Executes each SQL segment separately</li>
                    <li>Handles problematic queries gracefully</li>
                </ul>
            </div>
            
            <div class="info">
                <strong>📊 Current Database Configuration:</strong>
                <ul>
                    <li>Host: <strong><?= htmlspecialchars(DATABASE_SERVER) ?></strong></li>
                    <li>User: <strong><?= htmlspecialchars(DATABASE_USERNAME) ?></strong></li>
                    <li>Database: <strong><?= htmlspecialchars(DATABASE_NAME) ?></strong></li>
                </ul>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="confirmation">Type "RESET DATABASE" to confirm:</label>
                    <input type="text" id="confirmation" name="confirmation" required 
                           placeholder="Type exactly: RESET DATABASE">
                </div>
                
                <button type="submit" name="action" value="reset" class="btn btn-danger">
                    🔄 Reset Database
                </button>
                
                <button type="submit" name="action" value="check" class="btn btn-secondary">
                    📊 Check Database Status
                </button>
            </form>
            
        <?php else: ?>
            <!-- Processing form submission -->
            <?php
            
            function log_message($message, $type = 'info') {
                $colors = [
                    'info' => 'status-info',
                    'success' => 'status-success', 
                    'warning' => 'status-warning',
                    'error' => 'status-error'
                ];
                $class = $colors[$type] ?? 'status-info';
                echo "<span class='$class'>[" . strtoupper($type) . "] $message</span>\n";
                flush();
            }
            
            function connect_database() {
                mysqli_report(MYSQLI_REPORT_OFF);
                
                try {
                    $database = new mysqli(
                        DATABASE_SERVER,
                        DATABASE_USERNAME,
                        DATABASE_PASSWORD,
                        DATABASE_NAME
                    );
                    
                    if ($database->connect_error) {
                        throw new Exception("Connection failed: " . $database->connect_error);
                    }
                    
                    $database->set_charset('utf8mb4');
                    return $database;
                    
                } catch (Exception $e) {
                    log_message("Database connection failed: " . $e->getMessage(), 'error');
                    return false;
                }
            }
            
            function check_database_status($database) {
                log_message("Checking database status...", 'info');
                
                // Count tables
                $result = $database->query("SHOW TABLES");
                $table_count = $result ? $result->num_rows : 0;
                log_message("Current number of tables: $table_count", 'info');
                
                if ($table_count > 0) {
                    log_message("Tables found:", 'info');
                    while ($row = $result->fetch_array()) {
                        log_message("  - " . $row[0], 'info');
                    }
                } else {
                    log_message("No tables found in database", 'warning');
                }
                
                return $table_count;
            }
            
            function drop_all_tables($database) {
                log_message("Dropping all existing tables and views...", 'info');
                
                // Get all tables
                $result = $database->query("SHOW TABLES");
                $tables = [];
                
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $tables[] = $row[0];
                    }
                }
                
                if (empty($tables)) {
                    log_message("No tables to drop", 'info');
                    return true;
                }
                
                // Disable foreign key checks
                $database->query("SET FOREIGN_KEY_CHECKS = 0");
                
                // Drop all tables
                foreach ($tables as $table) {
                    $database->query("DROP TABLE IF EXISTS `$table`");
                    if ($database->error) {
                        log_message("Could not drop table $table: " . $database->error, 'warning');
                    } else {
                        log_message("Dropped table: $table", 'success');
                    }
                }
                
                // Drop all views
                $result = $database->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $database->query("DROP VIEW IF EXISTS `{$row[0]}`");
                        log_message("Dropped view: {$row[0]}", 'success');
                    }
                }
                
                // Re-enable foreign key checks
                $database->query("SET FOREIGN_KEY_CHECKS = 1");
                
                // Verify tables are dropped
                $result = $database->query("SHOW TABLES");
                $remaining_tables = $result ? $result->num_rows : 0;
                log_message("Tables remaining after drop: $remaining_tables", 'info');
                
                return $remaining_tables == 0;
            }
            
            function restore_database($database) {
                log_message("Restoring database from installation dump...", 'info');
                
                $dump_file = ROOT . 'install/dump.sql';
                if (!file_exists($dump_file)) {
                    log_message("Dump file not found: $dump_file", 'error');
                    return false;
                }
                
                // Read and process dump file exactly like install.php
                $dump_content = file_get_contents($dump_file);
                if ($dump_content === false) {
                    log_message("Could not read dump file", 'error');
                    return false;
                }
                
                // Split by separator exactly like install.php
                $dump = array_filter(explode('-- SEPARATOR --', $dump_content));
                
                log_message("Found " . count($dump) . " SQL segments to execute", 'info');
                
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
                        log_message("Query " . ($index + 1) . " failed: " . $database->error, 'warning');
                        
                        // Log the problematic query for debugging (truncated)
                        if (strlen($query) > 100) {
                            log_message("Query preview: " . substr($query, 0, 100) . "...", 'warning');
                        }
                    } else {
                        $success_count++;
                        if ($success_count % 10 == 0) {
                            log_message("Executed $success_count queries so far...", 'info');
                        }
                    }
                }
                
                log_message("Executed $success_count queries successfully", 'success');
                if ($error_count > 0) {
                    log_message("$error_count queries failed", 'warning');
                }
                
                return $error_count == 0;
            }
            
            // Main processing
            echo '<div class="log">';
            
            if ($action === 'check') {
                log_message("=== Database Status Check ===", 'info');
                $database = connect_database();
                if ($database) {
                    check_database_status($database);
                    $database->close();
                }
                
            } elseif ($action === 'reset') {
                $confirmation = $_POST['confirmation'] ?? '';
                
                if ($confirmation !== 'RESET DATABASE') {
                    log_message("Invalid confirmation. Operation cancelled.", 'error');
                } else {
                    log_message("=== Starting Database Reset ===", 'info');
                    
                    $database = connect_database();
                    if (!$database) {
                        log_message("Cannot proceed without database connection", 'error');
                    } else {
                        log_message("Connected to database successfully", 'success');
                        
                        // Step 1: Drop all tables
                        if (!drop_all_tables($database)) {
                            log_message("Failed to drop all tables", 'error');
                        } else {
                            log_message("All tables dropped successfully", 'success');
                            
                            // Step 2: Restore from dump
                            if (!restore_database($database)) {
                                log_message("Database restoration completed with some errors", 'warning');
                            } else {
                                log_message("Database restoration completed successfully", 'success');
                            }
                            
                            // Step 3: Verify installation
                            log_message("=== Verifying Installation ===", 'info');
                            $table_count = check_database_status($database);
                            
                            if ($table_count > 0) {
                                log_message("✅ Database reset completed successfully!", 'success');
                                log_message("Your SeeGap installation should now work properly!", 'success');
                            } else {
                                log_message("❌ Database reset may have failed - no tables found!", 'error');
                            }
                        }
                        
                        $database->close();
                    }
                }
            }
            
            echo '</div>';
            ?>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="reset_db.php" class="btn">🔄 Reset Again</a>
                <a href="../" class="btn btn-secondary">🏠 Go to Application</a>
            </div>
            
        <?php endif; ?>
    </div>
</body>
</html>
