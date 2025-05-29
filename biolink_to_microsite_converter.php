<?php
/**
 * Biolink to Microsite Bulk Converter
 * 
 * This script performs comprehensive renaming of all biolink references to microsite
 * including files, directories, and content within files.
 * 
 * IMPORTANT: Make sure you have a complete backup before running this script!
 */

class BiolinkToMicrositeConverter {
    
    private $rootPath;
    private $logFile;
    private $dryRun;
    private $renamedFiles = [];
    private $renamedDirs = [];
    private $modifiedFiles = [];
    
    public function __construct($rootPath = '.', $dryRun = false) {
        $this->rootPath = realpath($rootPath);
        $this->dryRun = $dryRun;
        $this->logFile = $this->rootPath . '/biolink_to_microsite_conversion.log';
        
        // Clear log file
        file_put_contents($this->logFile, "Biolink to Microsite Conversion Log\n");
        file_put_contents($this->logFile, "Started: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents($this->logFile, "Dry Run: " . ($this->dryRun ? 'YES' : 'NO') . "\n\n", FILE_APPEND);
    }
    
    public function convert() {
        $this->log("Starting conversion process...");
        
        // Step 1: Rename files and directories
        $this->log("\n=== STEP 1: Renaming Files and Directories ===");
        $this->renameFilesAndDirectories();
        
        // Step 2: Update file contents
        $this->log("\n=== STEP 2: Updating File Contents ===");
        $this->updateFileContents();
        
        // Step 3: Generate summary
        $this->log("\n=== CONVERSION SUMMARY ===");
        $this->log("Files renamed: " . count($this->renamedFiles));
        $this->log("Directories renamed: " . count($this->renamedDirs));
        $this->log("Files modified: " . count($this->modifiedFiles));
        
        $this->log("\nConversion completed: " . date('Y-m-d H:i:s'));
        
        echo "Conversion completed! Check the log file: " . $this->logFile . "\n";
    }
    
    private function renameFilesAndDirectories() {
        // Get all files and directories that need renaming
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        $itemsToRename = [];
        
        foreach ($iterator as $item) {
            $path = $item->getPathname();
            $name = $item->getFilename();
            
            // Skip our own files
            if (strpos($path, 'biolink_to_microsite') !== false) {
                continue;
            }
            
            // Check if name contains biolink
            if (stripos($name, 'biolink') !== false) {
                $itemsToRename[] = [
                    'path' => $path,
                    'name' => $name,
                    'isDir' => $item->isDir()
                ];
            }
        }
        
        // Sort by depth (deepest first) to avoid path conflicts
        usort($itemsToRename, function($a, $b) {
            return substr_count($b['path'], '/') - substr_count($a['path'], '/');
        });
        
        foreach ($itemsToRename as $item) {
            $this->renameItem($item['path'], $item['name'], $item['isDir']);
        }
    }
    
    private function renameItem($oldPath, $oldName, $isDir) {
        $newName = $this->convertBiolinkToMicrosite($oldName);
        
        if ($newName === $oldName) {
            return; // No change needed
        }
        
        $newPath = dirname($oldPath) . '/' . $newName;
        
        $this->log(($isDir ? "DIR: " : "FILE: ") . $oldPath . " -> " . $newPath);
        
        if (!$this->dryRun) {
            if (rename($oldPath, $newPath)) {
                if ($isDir) {
                    $this->renamedDirs[] = $oldPath . ' -> ' . $newPath;
                } else {
                    $this->renamedFiles[] = $oldPath . ' -> ' . $newPath;
                }
            } else {
                $this->log("ERROR: Failed to rename " . $oldPath);
            }
        }
    }
    
    private function updateFileContents() {
        // Get all PHP, JS, CSS, SQL, and other text files
        $extensions = ['php', 'js', 'css', 'sql', 'html', 'htm', 'json', 'xml', 'md', 'txt'];
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }
            
            $path = $file->getPathname();
            $extension = strtolower($file->getExtension());
            
            // Skip our own files and binary files
            if (strpos($path, 'biolink_to_microsite') !== false || 
                !in_array($extension, $extensions)) {
                continue;
            }
            
            $this->updateFileContent($path);
        }
    }
    
    private function updateFileContent($filePath) {
        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        // Define replacement patterns
        $replacements = [
            // Exact case matches
            'biolinks_themes' => 'microsites_themes',
            'biolinks_blocks' => 'microsites_blocks', 
            'biolinks_templates' => 'microsites_templates',
            'biolink_theme_id' => 'microsite_theme_id',
            'biolink_block_id' => 'microsite_block_id',
            'biolink_template_id' => 'microsite_template_id',
            
            // Class names and identifiers
            'BiolinkBlock' => 'MicrositeBlock',
            'BiolinkBlockAjax' => 'MicrositeBlockAjax',
            'BiolinksTemplates' => 'MicrositesTemplates',
            'BiolinksThemes' => 'MicrositesThemes',
            
            // Method and function names
            'get_biolink' => 'get_microsite',
            'process_biolink' => 'process_microsite',
            'biolink_wrapper' => 'microsite_wrapper',
            'biolink_blocks' => 'microsite_blocks',
            'biolink_themes' => 'microsite_themes',
            'biolink_templates' => 'microsite_templates',
            
            // Variables and properties
            '$biolink' => '$microsite',
            '$biolinks' => '$microsites',
            '$biolink_' => '$microsite_',
            '$biolinks_' => '$microsites_',
            
            // CSS classes and IDs
            '.biolink-' => '.microsite-',
            '#biolink_' => '#microsite_',
            '#biolink-' => '#microsite-',
            'biolink_block_' => 'microsite_block_',
            'biolink-block-' => 'microsite-block-',
            
            // Language keys and routes
            'biolink.' => 'microsite.',
            'biolinks.' => 'microsites.',
            '/biolink-' => '/microsite-',
            '/biolinks-' => '/microsites-',
            
            // General word replacements
            'biolink' => 'microsite',
            'biolinks' => 'microsites',
            'Biolink' => 'Microsite',
            'Biolinks' => 'Microsites',
            'BIOLINK' => 'MICROSITE',
            'BIOLINKS' => 'MICROSITES',
            
            // HTML data attributes
            'data-biolink-' => 'data-microsite-',
            'data-biolinks-' => 'data-microsites-',
            
            // JavaScript variables
            'biolink_' => 'microsite_',
            'biolinks_' => 'microsites_',
            
            // File paths and includes
            'biolink_blocks/' => 'microsite_blocks/',
            'biolinks_blocks/' => 'microsites_blocks/',
            'biolink_themes/' => 'microsite_themes/',
            'biolinks_themes/' => 'microsites_themes/',
            
            // Configuration keys
            'enabled_biolink_blocks' => 'enabled_microsite_blocks',
            'biolinks_is_enabled' => 'microsites_is_enabled',
            'biolinks_templates_is_enabled' => 'microsites_templates_is_enabled',
            'biolinks_themes_is_enabled' => 'microsites_themes_is_enabled',
            'biolinks_limit' => 'microsites_limit',
            'biolink_blocks_limit' => 'microsite_blocks_limit',
        ];
        
        // Apply replacements
        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        
        // Special regex replacements for more complex patterns
        $regexReplacements = [
            // Function calls with biolink
            '/\bbiolink([A-Z][a-zA-Z]*)\b/' => 'microsite$1',
            '/\bBiolink([A-Z][a-zA-Z]*)\b/' => 'Microsite$1',
            
            // Array keys with biolink
            '/\'biolink([_a-zA-Z0-9]*)\'\s*=>/' => '\'microsite$1\' =>',
            '/"biolink([_a-zA-Z0-9]*)"\s*:/' => '"microsite$1":',
            
            // Comments and documentation
            '/\/\*\*?\s*.*?biolink.*?\*\//is' => function($matches) {
                return str_ireplace(['biolink', 'Biolink', 'BIOLINK'], ['microsite', 'Microsite', 'MICROSITE'], $matches[0]);
            },
            '/\/\/.*biolink.*/i' => function($matches) {
                return str_ireplace(['biolink', 'Biolink', 'BIOLINK'], ['microsite', 'Microsite', 'MICROSITE'], $matches[0]);
            },
        ];
        
        foreach ($regexReplacements as $pattern => $replacement) {
            if (is_callable($replacement)) {
                $content = preg_replace_callback($pattern, $replacement, $content);
            } else {
                $content = preg_replace($pattern, $replacement, $content);
            }
        }
        
        // Check if content was modified
        if ($content !== $originalContent) {
            $this->log("MODIFIED: " . $filePath);
            
            if (!$this->dryRun) {
                if (file_put_contents($filePath, $content) !== false) {
                    $this->modifiedFiles[] = $filePath;
                } else {
                    $this->log("ERROR: Failed to write " . $filePath);
                }
            }
        }
    }
    
    private function convertBiolinkToMicrosite($name) {
        $replacements = [
            'biolinks_themes' => 'microsites_themes',
            'biolinks_blocks' => 'microsites_blocks',
            'biolinks_templates' => 'microsites_templates',
            'biolink_blocks' => 'microsite_blocks',
            'biolink_themes' => 'microsite_themes',
            'biolink_templates' => 'microsite_templates',
            'BiolinkBlock' => 'MicrositeBlock',
            'BiolinkBlockAjax' => 'MicrositeBlockAjax',
            'BiolinksTemplates' => 'MicrositesTemplates',
            'biolinks-templates' => 'microsites-templates',
            'biolink-block' => 'microsite-block',
            'biolinks' => 'microsites',
            'biolink' => 'microsite',
            'Biolinks' => 'Microsites',
            'Biolink' => 'Microsite',
            'BIOLINKS' => 'MICROSITES',
            'BIOLINK' => 'MICROSITE',
        ];
        
        foreach ($replacements as $search => $replace) {
            $name = str_replace($search, $replace, $name);
        }
        
        return $name;
    }
    
    private function log($message) {
        echo $message . "\n";
        file_put_contents($this->logFile, $message . "\n", FILE_APPEND);
    }
}

// Usage
if (php_sapi_name() === 'cli') {
    $dryRun = isset($argv[1]) && $argv[1] === '--dry-run';
    
    echo "Biolink to Microsite Converter\n";
    echo "==============================\n";
    
    if ($dryRun) {
        echo "DRY RUN MODE - No changes will be made\n";
    } else {
        echo "WARNING: This will make permanent changes to your files!\n";
        echo "Make sure you have a backup before proceeding.\n";
        echo "Press Enter to continue or Ctrl+C to cancel...\n";
        fgets(STDIN);
    }
    
    $converter = new BiolinkToMicrositeConverter('.', $dryRun);
    $converter->convert();
} else {
    echo "This script must be run from the command line.\n";
    echo "Usage: php biolink_to_microsite_converter.php [--dry-run]\n";
}
?>
