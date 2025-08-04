-- Create form_submissions table manually for testing
-- Run this script to set up the table in your existing database

-- Create the form_submissions table
CREATE TABLE IF NOT EXISTS `form_submissions` (
  `form_submission_id` int NOT NULL AUTO_INCREMENT,
  `microsite_block_id` int NOT NULL,
  `link_id` int NOT NULL,
  `form_type` varchar(32) NOT NULL DEFAULT 'custom',
  `responses` longtext,
  `metadata` longtext,
  `ip` varchar(64) DEFAULT NULL,
  `user_agent` text,
  `submitted_at` datetime NOT NULL,
  PRIMARY KEY (`form_submission_id`),
  KEY `microsite_block_id` (`microsite_block_id`),
  KEY `link_id` (`link_id`),
  KEY `submitted_at` (`submitted_at`),
  CONSTRAINT `form_submissions_ibfk_1` FOREIGN KEY (`microsite_block_id`) REFERENCES `microsites_blocks` (`microsite_block_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `form_submissions_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some test data to verify the table works
INSERT INTO `form_submissions` (
  `microsite_block_id`, 
  `link_id`, 
  `form_type`, 
  `responses`, 
  `metadata`, 
  `ip`, 
  `user_agent`, 
  `submitted_at`
) VALUES 
(
  43, -- This should match an existing microsite_block_id from your form
  1,  -- This should match an existing link_id
  'custom',
  '[{"question":"Test Text Input","type":"radio","response":"Option 1","required":false},{"question":"Feedback","type":"rating_star","response":"4","required":false},{"question":"Text area","type":"rating_number","response":"3","required":false},{"question":"Text area","type":"rating_number","response":"5","required":false}]',
  '{"utm_source":"test","utm_campaign":"manual_test"}',
  '127.0.0.1',
  'Mozilla/5.0 (Test Browser)',
  NOW()
),
(
  43, -- Same form block
  1,  -- Same link
  'custom',
  '[{"question":"Test Text Input","type":"radio","response":"Option 2","required":false},{"question":"Feedback","type":"rating_star","response":"5","required":false},{"question":"Text area","type":"rating_number","response":"4","required":false},{"question":"Text area","type":"rating_number","response":"4","required":false}]',
  '{"utm_source":"direct","referrer":"https://example.com"}',
  '192.168.1.100',
  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
  DATE_SUB(NOW(), INTERVAL 1 HOUR)
);

-- Verify the table was created and data inserted
SELECT 'Table created successfully!' as status;
SELECT COUNT(*) as test_records_inserted FROM form_submissions;

-- Show the test data
SELECT 
  form_submission_id,
  microsite_block_id,
  link_id,
  form_type,
  JSON_PRETTY(responses) as formatted_responses,
  ip,
  submitted_at
FROM form_submissions 
ORDER BY submitted_at DESC;
