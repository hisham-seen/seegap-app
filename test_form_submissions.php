<?php
// Test script to verify form submissions are working
// Run this after setting up the form_submissions table

// Include the SeeGap initialization
require_once 'app/init.php';

echo "<h2>Form Submissions Test</h2>\n";

try {
    // Check if form_submissions table exists
    $table_check = db()->rawQuery("SHOW TABLES LIKE 'form_submissions'");
    
    if($table_check->num_rows > 0) {
        echo "✅ form_submissions table exists<br>\n";
        
        // Get table structure
        $structure = db()->rawQuery("DESCRIBE form_submissions");
        echo "<h3>Table Structure:</h3>\n";
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>\n";
        while($row = $structure->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "</tr>\n";
        }
        echo "</table><br>\n";
        
        // Count existing submissions
        $count = db()->getValue('form_submissions', 'COUNT(*)');
        echo "📊 Total form submissions in database: <strong>{$count}</strong><br>\n";
        
        if($count > 0) {
            echo "<h3>Recent Submissions:</h3>\n";
            $submissions = db()->rawQuery("
                SELECT 
                    form_submission_id,
                    microsite_block_id,
                    link_id,
                    form_type,
                    ip,
                    submitted_at,
                    JSON_LENGTH(responses) as response_count
                FROM form_submissions 
                ORDER BY submitted_at DESC 
                LIMIT 5
            ");
            
            echo "<table border='1' style='border-collapse: collapse;'>\n";
            echo "<tr><th>ID</th><th>Block ID</th><th>Link ID</th><th>Type</th><th>Responses</th><th>IP</th><th>Submitted</th></tr>\n";
            while($row = $submissions->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['form_submission_id']}</td>";
                echo "<td>{$row['microsite_block_id']}</td>";
                echo "<td>{$row['link_id']}</td>";
                echo "<td>{$row['form_type']}</td>";
                echo "<td>{$row['response_count']} questions</td>";
                echo "<td>{$row['ip']}</td>";
                echo "<td>{$row['submitted_at']}</td>";
                echo "</tr>\n";
            }
            echo "</table><br>\n";
            
            // Show detailed responses for the latest submission
            $latest = db()->getOne('form_submissions', null, 'submitted_at DESC');
            if($latest) {
                echo "<h3>Latest Submission Details:</h3>\n";
                echo "<strong>Submission ID:</strong> {$latest->form_submission_id}<br>\n";
                echo "<strong>Form Block ID:</strong> {$latest->microsite_block_id}<br>\n";
                echo "<strong>Submitted:</strong> {$latest->submitted_at}<br>\n";
                echo "<strong>IP:</strong> {$latest->ip}<br>\n";
                echo "<strong>User Agent:</strong> " . substr($latest->user_agent, 0, 50) . "...<br>\n";
                
                if($latest->responses) {
                    $responses = json_decode($latest->responses, true);
                    echo "<strong>Responses:</strong><br>\n";
                    echo "<ul>\n";
                    foreach($responses as $response) {
                        echo "<li><strong>{$response['question']}</strong> ({$response['type']}): {$response['response']}</li>\n";
                    }
                    echo "</ul>\n";
                }
                
                if($latest->metadata) {
                    $metadata = json_decode($latest->metadata, true);
                    echo "<strong>Metadata:</strong><br>\n";
                    echo "<pre>" . json_encode($metadata, JSON_PRETTY_PRINT) . "</pre>\n";
                }
            }
        }
        
        // Test the AJAX endpoint
        echo "<h3>Testing Form Submission Endpoint:</h3>\n";
        echo "📍 Form submission endpoint: <code>" . url('l/microsite-block-ajax') . "</code><br>\n";
        
        // Check if the controller file exists
        if(file_exists('app/controllers/l/MicrositeBlockAjax.php')) {
            echo "✅ MicrositeBlockAjax controller exists<br>\n";
        } else {
            echo "❌ MicrositeBlockAjax controller missing<br>\n";
        }
        
        // Check if form view exists
        if(file_exists('themes/phoenix/views/l/microsite_blocks/form.php')) {
            echo "✅ Form view template exists<br>\n";
        } else {
            echo "❌ Form view template missing<br>\n";
        }
        
        // Check for existing form blocks
        $form_blocks = db()->where('type', 'form')->get('microsites_blocks');
        echo "📋 Form blocks in database: <strong>" . count($form_blocks) . "</strong><br>\n";
        
        if(count($form_blocks) > 0) {
            echo "<h3>Available Form Blocks:</h3>\n";
            echo "<table border='1' style='border-collapse: collapse;'>\n";
            echo "<tr><th>Block ID</th><th>Link ID</th><th>User ID</th><th>Enabled</th><th>Created</th></tr>\n";
            foreach($form_blocks as $block) {
                echo "<tr>";
                echo "<td>{$block->microsite_block_id}</td>";
                echo "<td>{$block->link_id}</td>";
                echo "<td>{$block->user_id}</td>";
                echo "<td>" . ($block->is_enabled ? 'Yes' : 'No') . "</td>";
                echo "<td>{$block->datetime}</td>";
                echo "</tr>\n";
            }
            echo "</table><br>\n";
        }
        
        echo "<h3>Test Form Submission:</h3>\n";
        echo "<p>You can now test form submissions by visiting your microsite with a form block.</p>\n";
        echo "<p>Example URL: <code>http://localhost:8080/ixrew</code></p>\n";
        
    } else {
        echo "❌ form_submissions table does not exist<br>\n";
        echo "<p>Please run the setup_form_submissions_table.sql script first:</p>\n";
        echo "<code>mysql -u your_username -p your_database < setup_form_submissions_table.sql</code>\n";
    }
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";
echo "<p><strong>Next Steps:</strong></p>\n";
echo "<ol>\n";
echo "<li>If the table doesn't exist, run: <code>mysql -u username -p database_name < setup_form_submissions_table.sql</code></li>\n";
echo "<li>Visit your microsite with a form block (e.g., http://localhost:8080/ixrew)</li>\n";
echo "<li>Fill out and submit the form</li>\n";
echo "<li>Refresh this page to see the new submission</li>\n";
echo "</ol>\n";
?>
