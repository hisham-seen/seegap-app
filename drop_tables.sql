-- Drop all database tables for clean reinstallation
-- This script will remove all existing data and table structures

SET FOREIGN_KEY_CHECKS = 0;

-- Drop main application tables
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `gs1_links`;
DROP TABLE IF EXISTS `links`;
DROP TABLE IF EXISTS `qr_codes`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `splash_pages`;
DROP TABLE IF EXISTS `pixels`;
DROP TABLE IF EXISTS `domains`;
DROP TABLE IF EXISTS `data`;
DROP TABLE IF EXISTS `reports`;

-- Drop user and authentication tables
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `users_logs`;
DROP TABLE IF EXISTS `users_sessions`;
DROP TABLE IF EXISTS `users_redeemed_codes`;

-- Drop plan and payment tables
DROP TABLE IF EXISTS `plans`;
DROP TABLE IF EXISTS `codes`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `taxes`;

-- Drop team tables (if teams plugin is active)
DROP TABLE IF EXISTS `teams`;
DROP TABLE IF EXISTS `teams_members`;

-- Drop affiliate tables (if affiliate plugin is active)
DROP TABLE IF EXISTS `affiliates_commissions`;
DROP TABLE IF EXISTS `affiliates_withdrawals`;

-- Drop email signature tables (if email-signatures plugin is active)
DROP TABLE IF EXISTS `signatures`;

-- Drop AIX plugin tables (if aix plugin is active)
DROP TABLE IF EXISTS `templates_categories`;
DROP TABLE IF EXISTS `templates`;
DROP TABLE IF EXISTS `documents`;
DROP TABLE IF EXISTS `images`;
DROP TABLE IF EXISTS `transcriptions`;
DROP TABLE IF EXISTS `chats_assistants`;
DROP TABLE IF EXISTS `chats`;
DROP TABLE IF EXISTS `chats_messages`;
DROP TABLE IF EXISTS `syntheses`;

-- Drop payment processor tables (if payment-blocks plugin is active)
DROP TABLE IF EXISTS `payment_processors`;
DROP TABLE IF EXISTS `guests_payments`;

-- Drop push notification tables (if push-notifications plugin is active)
DROP TABLE IF EXISTS `push_subscribers`;
DROP TABLE IF EXISTS `push_notifications`;

-- Drop microsite tables
DROP TABLE IF EXISTS `microsites_themes`;
DROP TABLE IF EXISTS `microsites_templates`;
DROP TABLE IF EXISTS `microsites_blocks`;

-- Drop form submission tables
DROP TABLE IF EXISTS `form_submissions`;

-- Drop system tables
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `languages`;
DROP TABLE IF EXISTS `broadcasts`;
DROP TABLE IF EXISTS `internal_notifications`;
DROP TABLE IF EXISTS `statistics`;

-- Drop any remaining tables that might exist
DROP TABLE IF EXISTS `track_links`;
DROP TABLE IF EXISTS `biolinks_blocks`;
DROP TABLE IF EXISTS `biolinks_themes`;
DROP TABLE IF EXISTS `biolinks_templates`;

SET FOREIGN_KEY_CHECKS = 1;

-- Show remaining tables (should be empty)
SHOW TABLES;
