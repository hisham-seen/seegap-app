<?php
/**
 * Migration Verification Script
 * 
 * This script helps verify that the biolink to microsite migration was successful
 * by checking for any remaining biolink references and testing database integrity.
 */

class MigrationVerifier {
    
    private $rootPath;
    private $dbConfig;
    
    public function __construct($rootPath = '.') {
        $this->rootPath = realpath($rootPath);
    }
    
    public function verify() {
        echo "Migration Verification Report\n";
        echo "============================\n\n";
        
        // Check for remaining biolink references in files
        $this->checkFileReferences();
        
        // Check database if config is available
        $this->checkDatabase();
        
        // Check for renamed files
        $this->checkRenamedFiles();
        
        echo "\nVerification completed!\n";
    }
    
    private function checkFileReferences() {
        echo "1. Checking for remaining 'biolink' references in files...\n";
        echo "--------------------------------------------------------\n";
        
        $extensions = ['php', 'js', 'css', 'html', 'htm', 'json', 'xml', 'md', 'txt'];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        $foundReferences = [];
        $excludePatterns = [
            'biolink_to_microsite',
            'backup',
            'vendor',
            'node_modules',
            '.git'
        ];
        
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }
            
            $path = $file->getPathname();
            $extension = strtolower($file->getExtension());
            
            // Skip excluded directories and files
            $skip = false;
            foreach ($excludePatterns as $pattern) {
                if (strpos($path, $pattern) !== false) {
                    $skip = true;
                    break;
                }
            }
            
            if ($skip || !in_array($extension, $extensions)) {
                continue;
            }
            
            $content = file_get_contents($path);
            if (stripos($content, 'biolink') !== false) {
                // Count occurrences
                $count = substr_count(strtolower($content), 'biolink');
                $foundReferences[] = [
                    'file' => $path,
                    'count' => $count
                ];
            }
        }
        
        if (empty($foundReferences)) {
            echo "✅ No biolink references found in files!\n";
        } else {
            echo "❌ Found biolink references in " . count($foundReferences) . " files:\n";
            foreach ($foundReferences as $ref) {
                echo "   - {$ref['file']} ({$ref['count']} occurrences)\n";
            }
        }
        echo "\n";
    }
    
    private function checkDatabase() {
        echo "2. Checking database structure...\n";
        echo "--------------------------------\n";
        
        // Try to load database config
        $configFile = $this->rootPath . '/config.php';
        if (!file_exists($configFile)) {
            echo "⚠️  Database config not found. Skipping database checks.\n\n";
            return;
        }
        
        try {
            // Include config to get database settings
            include $configFile;
            
            if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USERNAME')) {
                echo "⚠️  Database configuration incomplete. Skipping database checks.\n\n";
                return;
            }
            
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USERNAME,
                defined('DB_PASSWORD') ? DB_PASSWORD : '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Check for microsite tables
            $tables = ['microsites_themes', 'microsites_blocks', 'microsites_templates'];
            foreach ($tables as $table) {
                $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() > 0) {
                    $countStmt = $pdo->query("SELECT COUNT(*) FROM $table");
                    $count = $countStmt->fetchColumn();
                    echo "✅ Table '$table' exists with $count records\n";
                } else {
                    echo "❌ Table '$table' not found\n";
                }
            }
            
            // Check for remaining biolink tables
            $stmt = $pdo->query("SHOW TABLES LIKE '%biolink%'");
            $biolinkTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($biolinkTables)) {
                echo "✅ No biolink tables found\n";
            } else {
                echo "❌ Found remaining biolink tables:\n";
                foreach ($biolinkTables as $table) {
                    echo "   - $table\n";
                }
            }
            
            // Check settings table for biolink references
            $stmt = $pdo->query("SELECT COUNT(*) FROM settings WHERE `key` LIKE '%biolink%' OR `value` LIKE '%biolink%'");
            $biolinkSettings = $stmt->fetchColumn();
            
            if ($biolinkSettings == 0) {
                echo "✅ No biolink references in settings\n";
            } else {
                echo "❌ Found $biolinkSettings biolink references in settings table\n";
            }
            
            // Check links table for microsite type
            $stmt = $pdo->query("SELECT COUNT(*) FROM links WHERE type = 'microsite'");
            $micrositeCount = $stmt->fetchColumn();
            echo "ℹ️  Found $micrositeCount microsite links\n";
            
        } catch (Exception $e) {
            echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function checkRenamedFiles() {
        echo "3. Checking for properly renamed files...\n";
        echo "----------------------------------------\n";
        
        $expectedFiles = [
            'app/controllers/MicrositeBlock.php',
            'app/controllers/MicrositeBlockAjax.php', 
            'app/controllers/MicrositesTemplates.php',
            'app/includes/enabled_microsite_blocks.php',
            'app/includes/microsite_backgrounds.php',
            'app/includes/microsite_blocks.php',
            'app/includes/microsite_fonts.php',
            'app/includes/microsite_socials.php',
            'MICROSITE_BLOCKS_ARCHITECTURE.md'
        ];
        
        $foundFiles = 0;
        foreach ($expectedFiles as $file) {
            $fullPath = $this->rootPath . '/' . $file;
            if (file_exists($fullPath)) {
                echo "✅ $file\n";
                $foundFiles++;
            } else {
                echo "❌ $file (not found)\n";
            }
        }
        
        echo "\nFound $foundFiles out of " . count($expectedFiles) . " expected files.\n";
        
        // Check for remaining biolink files
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        $biolinkFiles = [];
        foreach ($iterator as $file) {
            $path = $file->getPathname();
            $name = $file->getFilename();
            
            // Skip our migration files and backups
            if (strpos($path, 'biolink_to_microsite') !== false || 
                strpos($path, 'backup') !== false) {
                continue;
            }
            
            if (stripos($name, 'biolink') !== false) {
                $biolinkFiles[] = $path;
            }
        }
        
        if (empty($biolinkFiles)) {
            echo "✅ No files with 'biolink' in filename found\n";
        } else {
            echo "❌ Found files with 'biolink' in filename:\n";
            foreach ($biolinkFiles as $file) {
                echo "   - $file\n";
            }
        }
        
        echo "\n";
    }
}

// Run verification
if (php_sapi_name() === 'cli') {
    $verifier = new MigrationVerifier('.');
    $verifier->verify();
} else {
    echo "This script must be run from the command line.\n";
    echo "Usage: php verify_migration.php\n";
}
?>
