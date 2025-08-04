<?php
/*
 * Debug script to check why the main data view is not showing form submissions
 */

// Include the application initialization
require_once 'app/init.php';

echo "Debug: Data View Form Submissions\n";
echo "=================================\n\n";

// Test 1: Check if form_submissions table exists and has data
echo "1. Checking form_submissions table...\n";
try {
    $table_check = database()->query("SHOW TABLES LIKE 'form_submissions'");
    if($table_check->num_rows > 0) {
        echo "✓ form_submissions table exists\n";
        
        // Check if there's any data
        $count_result = database()->query("SELECT COUNT(*) as total FROM form_submissions");
        $count = $count_result->fetch_object()->total;
        echo "✓ Total form submissions: {$count}\n";
        
        if($count > 0) {
            // Show sample data
            $sample_result = database()->query("
                SELECT fs.*, l.user_id, l.url 
                FROM form_submissions fs 
                LEFT JOIN links l ON fs.link_id = l.link_id 
                LIMIT 5
            ");
            echo "\nSample submissions:\n";
            while($row = $sample_result->fetch_object()) {
                echo "  - ID: {$row->form_submission_id}, Block: {$row->microsite_block_id}, User: {$row->user_id}, Date: {$row->submitted_at}\n";
            }
        }
    } else {
        echo "✗ form_submissions table does not exist\n";
    }
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check form blocks
echo "2. Checking form blocks...\n";
try {
    $blocks_result = database()->query("
        SELECT mb.microsite_block_id, mb.settings, mb.type, l.user_id, l.url
        FROM microsites_blocks mb
        LEFT JOIN links l ON mb.link_id = l.link_id
        WHERE mb.type = 'form'
        LIMIT 5
    ");
    
    if($blocks_result->num_rows > 0) {
        echo "✓ Found " . $blocks_result->num_rows . " form block(s)\n";
        while($block = $blocks_result->fetch_object()) {
            $settings = json_decode($block->settings);
            $form_name = $settings->name ?? 'Unnamed Form';
            echo "  - Block ID: {$block->microsite_block_id}, Name: '{$form_name}', User: {$block->user_id}\n";
        }
    } else {
        echo "✗ No form blocks found\n";
    }
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Simulate the Data controller query for a specific user
echo "3. Testing Data controller query...\n";
try {
    // Get the first user with form blocks
    $user_result = database()->query("
        SELECT DISTINCT l.user_id 
        FROM microsites_blocks mb
        LEFT JOIN links l ON mb.link_id = l.link_id
        WHERE mb.type = 'form' AND l.user_id IS NOT NULL
        LIMIT 1
    ");
    
    if($user_result->num_rows > 0) {
        $user = $user_result->fetch_object();
        $user_id = $user->user_id;
        echo "✓ Testing with user ID: {$user_id}\n";
        
        // Get microsite blocks for this user
        $microsite_blocks_result = database()->query("
            SELECT mb.`microsite_block_id`, mb.`settings`, mb.`type`, mb.`link_id`, l.`user_id`
            FROM `microsites_blocks` mb
            LEFT JOIN `links` l ON mb.`link_id` = l.`link_id`
            WHERE l.`user_id` = {$user_id} AND mb.`type` = 'form'
        ");
        
        $microsite_blocks = [];
        while($block = $microsite_blocks_result->fetch_object()) {
            $block->settings = json_decode($block->settings);
            $microsite_blocks[$block->microsite_block_id] = $block;
        }
        
        echo "✓ Found " . count($microsite_blocks) . " form blocks for user {$user_id}\n";
        
        // Get form submissions for this user
        $data_result = database()->query("
            SELECT 
                fs.`microsite_block_id`, 
                fs.`form_type`, 
                fs.`link_id`, 
                l.`project_id`,
                fs.`form_submission_id`,
                fs.`submitted_at`
            FROM 
                `form_submissions` fs
            LEFT JOIN `links` l ON fs.`link_id` = l.`link_id`
            WHERE 
                l.`user_id` = {$user_id}
            ORDER BY 
                fs.`submitted_at` DESC
        ");
        
        echo "✓ Found " . $data_result->num_rows . " form submissions for user {$user_id}\n";
        
        // Process the data to group by form (same logic as Data controller)
        $form_data = [];
        $form_submissions_count = [];
        $form_last_submission = [];
        
        if($data_result) {
            while($row = $data_result->fetch_object()) {
                // Get form name from microsite blocks
                $form_name = isset($microsite_blocks[$row->microsite_block_id]) ? 
                    $microsite_blocks[$row->microsite_block_id]->settings->name ?? 'Unknown Form' : 
                    'Unknown Form';
                
                // Create a unique key for the form
                $form_key = strtolower(trim($form_name));
                
                echo "  - Processing submission: Block {$row->microsite_block_id}, Form: '{$form_name}', Key: '{$form_key}'\n";
                
                // Initialize form data if not exists
                if(!isset($form_data[$form_key])) {
                    $form_data[$form_key] = [
                        'microsite_block_id' => $row->microsite_block_id,
                        'type' => 'form',
                        'link_id' => $row->link_id,
                        'project_id' => $row->project_id,
                        'form_name' => $form_name,
                        'instances' => []
                    ];
                }
                
                // Add this instance if not already added
                if(!in_array($row->microsite_block_id, $form_data[$form_key]['instances'])) {
                    $form_data[$form_key]['instances'][] = $row->microsite_block_id;
                }
                
                // Count submissions for this form
                if(!isset($form_submissions_count[$form_key])) {
                    $form_submissions_count[$form_key] = 0;
                }
                $form_submissions_count[$form_key]++;
                
                // Track the latest submission
                if(!isset($form_last_submission[$form_key]) || strtotime($row->submitted_at) > strtotime($form_last_submission[$form_key])) {
                    $form_last_submission[$form_key] = $row->submitted_at;
                }
            }
        }
        
        // Create the forms array for display
        $forms = [];
        foreach($form_data as $form_key => $data) {
            $forms[] = (object) [
                'microsite_block_id' => $data['microsite_block_id'],
                'type' => $data['type'],
                'link_id' => $data['link_id'],
                'project_id' => $data['project_id'],
                'form_name' => $data['form_name'],
                'submissions_count' => $form_submissions_count[$form_key] ?? 0,
                'last_submission_datetime' => $form_last_submission[$form_key] ?? null,
                'instances' => $data['instances']
            ];
        }
        
        echo "\n✓ Final forms array has " . count($forms) . " forms:\n";
        foreach($forms as $form) {
            echo "  - Form: '{$form->form_name}', Submissions: {$form->submissions_count}, Last: {$form->last_submission_datetime}\n";
        }
        
    } else {
        echo "✗ No users with form blocks found\n";
    }
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Check if there are any submissions that might not be linked properly
echo "4. Checking for orphaned submissions...\n";
try {
    $orphaned_result = database()->query("
        SELECT fs.*, l.user_id
        FROM form_submissions fs
        LEFT JOIN links l ON fs.link_id = l.link_id
        WHERE l.user_id IS NULL
    ");
    
    if($orphaned_result->num_rows > 0) {
        echo "⚠ Found " . $orphaned_result->num_rows . " orphaned submissions (no valid link):\n";
        while($row = $orphaned_result->fetch_object()) {
            echo "  - Submission ID: {$row->form_submission_id}, Link ID: {$row->link_id}\n";
        }
    } else {
        echo "✓ No orphaned submissions found\n";
    }
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\nDebug complete!\n";
?>
