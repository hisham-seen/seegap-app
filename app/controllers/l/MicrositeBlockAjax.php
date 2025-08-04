<?php
defined('SEEGAP') || die();

class MicrositeBlockAjax extends \SeeGap\Controller {

    public function index() {
        // Don't require authentication for public form submissions
        // \SeeGap\Authentication::guard();

        if(!empty($_POST)) {
            // Handle both 'action' and 'request_type' for backward compatibility
            $request_type = $_POST['request_type'] ?? $_POST['action'] ?? null;
            
            switch($request_type) {
                case 'submit_form':
                    $this->submit_form();
                    break;
                
                default:
                    $this->response(['status' => 'error', 'message' => l('global.error_message.basic')]);
                    break;
            }
        }

        die();
    }

    private function submit_form() {
        // Get the microsite block
        $microsite_block_id = (int) ($_POST['microsite_block_id'] ?? 0);
        
        if(!$microsite_block_id) {
            $this->response(['status' => 'error', 'message' => l('global.error_message.basic')]);
        }

        // Get the microsite block from database
        $microsite_block = db()->where('microsite_block_id', $microsite_block_id)->getOne('microsite_blocks');
        
        if(!$microsite_block) {
            $this->response(['status' => 'error', 'message' => l('global.error_message.basic')]);
        }

        // Get the link that owns this microsite block
        $link = db()->where('link_id', $microsite_block->link_id)->getOne('links');
        
        if(!$link) {
            $this->response(['status' => 'error', 'message' => l('global.error_message.basic')]);
        }

        // Parse settings
        $settings = json_decode($microsite_block->settings);
        $form_type = $_POST['form_type'] ?? 'custom';

        // Collect form data
        $form_data = [];
        $form_data['microsite_block_id'] = $microsite_block_id;
        $form_data['link_id'] = $link->link_id;
        $form_data['form_type'] = $form_type;
        $form_data['submitted_at'] = \SeeGap\Date::$date;
        $form_data['ip'] = get_ip();
        $form_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // Process different form types
        if($form_type == 'custom') {
            // Handle custom form questions
            $responses = [];
            
            if(isset($settings->questions) && is_array($settings->questions)) {
                foreach($settings->questions as $index => $question) {
                    $field_name = 'question_' . $index;
                    
                    if(isset($_POST[$field_name])) {
                        $response_value = $_POST[$field_name];
                        
                        // Handle array responses (checkboxes)
                        if(is_array($response_value)) {
                            $response_value = implode(', ', $response_value);
                        }
                        
                        $responses[] = [
                            'question' => $question->question,
                            'type' => $question->type,
                            'response' => $response_value,
                            'required' => $question->required ?? false
                        ];
                    }
                }
            }
            
            $form_data['responses'] = json_encode($responses);
            
        } else {
            // Handle simple form types (email, phone, contact)
            $simple_data = [];
            
            foreach(['email', 'name', 'phone', 'message'] as $field) {
                if(isset($_POST[$field])) {
                    $simple_data[$field] = $_POST[$field];
                }
            }
            
            $form_data['responses'] = json_encode($simple_data);
        }

        // Collect metadata if enabled
        $metadata = [];
        foreach($_POST as $key => $value) {
            if(strpos($key, 'metadata_') === 0) {
                $metadata_key = str_replace('metadata_', '', $key);
                $metadata[$metadata_key] = $value;
            }
        }
        
        if(!empty($metadata)) {
            $form_data['metadata'] = json_encode($metadata);
        }

        // Insert form submission into database
        try {
            // Create form_submissions table if it doesn't exist
            $this->create_form_submissions_table();
            
            $form_submission_id = db()->insert('form_submissions', $form_data);
            
            if($form_submission_id) {
                // Send email notification if configured
                if(!empty($settings->email_notification)) {
                    $this->send_email_notification($settings, $form_data, $link);
                }
                
                // Send webhook if configured
                if(!empty($settings->webhook_url)) {
                    $this->send_webhook($settings, $form_data, $link);
                }
                
                $response_data = [
                    'status' => 'success',
                    'message' => $settings->success_text ?? l('global.success_message.basic')
                ];
                
                // Add thank you URL if set
                if(!empty($settings->thank_you_url)) {
                    $response_data['details'] = ['thank_you_url' => $settings->thank_you_url];
                }
                
                $this->response($response_data);
            } else {
                $this->response(['status' => 'error', 'message' => l('global.error_message.basic')]);
            }
            
        } catch(Exception $e) {
            $this->response(['status' => 'error', 'message' => l('global.error_message.basic')]);
        }
    }

    private function create_form_submissions_table() {
        // Check if table exists, if not create it
        $table_exists = db()->rawQuery("SHOW TABLES LIKE 'form_submissions'")->num_rows > 0;
        
        if(!$table_exists) {
            db()->rawQuery("
                CREATE TABLE `form_submissions` (
                    `form_submission_id` int(11) NOT NULL AUTO_INCREMENT,
                    `microsite_block_id` int(11) NOT NULL,
                    `link_id` int(11) NOT NULL,
                    `form_type` varchar(32) NOT NULL DEFAULT 'custom',
                    `responses` longtext,
                    `metadata` longtext,
                    `ip` varchar(64) DEFAULT NULL,
                    `user_agent` text,
                    `submitted_at` datetime NOT NULL,
                    PRIMARY KEY (`form_submission_id`),
                    KEY `microsite_block_id` (`microsite_block_id`),
                    KEY `link_id` (`link_id`),
                    KEY `submitted_at` (`submitted_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
    }

    private function send_email_notification($settings, $form_data, $link) {
        try {
            $responses = json_decode($form_data['responses'], true);
            
            $email_content = "New form submission received:\n\n";
            $email_content .= "Form: " . ($settings->name ?? 'Untitled Form') . "\n";
            $email_content .= "Link: " . $link->url . "\n";
            $email_content .= "Submitted: " . $form_data['submitted_at'] . "\n";
            $email_content .= "IP: " . $form_data['ip'] . "\n\n";
            
            if(is_array($responses)) {
                foreach($responses as $response) {
                    if(isset($response['question'])) {
                        $email_content .= $response['question'] . ": " . $response['response'] . "\n";
                    } else {
                        // Simple form data
                        foreach($response as $key => $value) {
                            $email_content .= ucfirst($key) . ": " . $value . "\n";
                        }
                    }
                }
            }
            
            // Send email using the system's mail function
            mail(
                $settings->email_notification,
                "New Form Submission - " . ($settings->name ?? 'Form'),
                $email_content,
                "From: " . settings()->main->title . " <" . settings()->main->email . ">"
            );
            
        } catch(Exception $e) {
            // Log error but don't fail the submission
            error_log("Form email notification failed: " . $e->getMessage());
        }
    }

    private function send_webhook($settings, $form_data, $link) {
        try {
            $webhook_data = [
                'form_submission_id' => $form_data['form_submission_id'] ?? null,
                'microsite_block_id' => $form_data['microsite_block_id'],
                'link_id' => $form_data['link_id'],
                'form_type' => $form_data['form_type'],
                'responses' => json_decode($form_data['responses'], true),
                'metadata' => json_decode($form_data['metadata'] ?? '{}', true),
                'submitted_at' => $form_data['submitted_at'],
                'ip' => $form_data['ip'],
                'link_url' => $link->url
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $settings->webhook_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhook_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'User-Agent: SeeGap Form Webhook'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            curl_exec($ch);
            curl_close($ch);
            
        } catch(Exception $e) {
            // Log error but don't fail the submission
            error_log("Form webhook failed: " . $e->getMessage());
        }
    }

    private function response($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
}
