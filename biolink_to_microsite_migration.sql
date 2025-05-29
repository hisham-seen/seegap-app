-- =====================================================
-- Biolink to Microsite Migration Script
-- =====================================================
-- This script renames all biolink-related database elements to microsite
-- Run this script with caution - make sure you have a backup!

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- 1. RENAME TABLES
-- =====================================================

-- Rename biolinks_themes table
RENAME TABLE `biolinks_themes` TO `microsites_themes`;

-- Rename biolinks_blocks table  
RENAME TABLE `biolinks_blocks` TO `microsites_blocks`;

-- Rename biolinks_templates table
RENAME TABLE `biolinks_templates` TO `microsites_templates`;

-- =====================================================
-- 2. RENAME COLUMNS IN EXISTING TABLES
-- =====================================================

-- Update links table
ALTER TABLE `links` CHANGE `biolink_theme_id` `microsite_theme_id` int DEFAULT NULL;

-- Update track_links table
ALTER TABLE `track_links` CHANGE `biolink_block_id` `microsite_block_id` int DEFAULT NULL;

-- Update data table
ALTER TABLE `data` CHANGE `biolink_block_id` `microsite_block_id` int DEFAULT NULL;

-- Update guests_payments table (if exists)
ALTER TABLE `guests_payments` CHANGE `biolink_block_id` `microsite_block_id` int DEFAULT NULL;

-- Update qr_codes table (if has biolink references)
-- ALTER TABLE `qr_codes` CHANGE `biolink_id` `microsite_id` int DEFAULT NULL;

-- =====================================================
-- 3. RENAME COLUMNS IN RENAMED TABLES
-- =====================================================

-- Update microsites_themes table
ALTER TABLE `microsites_themes` CHANGE `biolink_theme_id` `microsite_theme_id` int NOT NULL AUTO_INCREMENT;

-- Update microsites_blocks table
ALTER TABLE `microsites_blocks` CHANGE `biolink_block_id` `microsite_block_id` int NOT NULL AUTO_INCREMENT;

-- Update microsites_templates table
ALTER TABLE `microsites_templates` CHANGE `biolink_template_id` `microsite_template_id` bigint unsigned NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 4. UPDATE FOREIGN KEY CONSTRAINTS
-- =====================================================

-- Drop existing foreign keys
ALTER TABLE `links` DROP FOREIGN KEY `links_biolinks_themes_biolink_theme_id_fk`;
ALTER TABLE `microsites_blocks` DROP FOREIGN KEY `biolinks_blocks_ibfk_1`;
ALTER TABLE `microsites_blocks` DROP FOREIGN KEY `biolinks_blocks_ibfk_2`;
ALTER TABLE `microsites_templates` DROP FOREIGN KEY `biolinks_templates_ibfk_1`;
ALTER TABLE `track_links` DROP FOREIGN KEY IF EXISTS `track_links_biolinks_blocks_biolink_block_id_fk`;
ALTER TABLE `data` DROP FOREIGN KEY `data_ibfk_4`;

-- Add new foreign keys with updated names
ALTER TABLE `links` ADD CONSTRAINT `links_microsites_themes_microsite_theme_id_fk` FOREIGN KEY (`microsite_theme_id`) REFERENCES `microsites_themes` (`microsite_theme_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `microsites_blocks` ADD CONSTRAINT `microsites_blocks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `microsites_blocks` ADD CONSTRAINT `microsites_blocks_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `microsites_templates` ADD CONSTRAINT `microsites_templates_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `data` ADD CONSTRAINT `data_ibfk_4` FOREIGN KEY (`microsite_block_id`) REFERENCES `microsites_blocks` (`microsite_block_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- =====================================================
-- 5. UPDATE INDEXES
-- =====================================================

-- Update any indexes that reference old column names
-- (Most indexes should be automatically updated with column renames)

-- =====================================================
-- 6. UPDATE SETTINGS AND CONFIGURATION DATA
-- =====================================================

-- Update settings table values that contain 'biolink' references
UPDATE `settings` SET `value` = REPLACE(`value`, 'biolink', 'microsite') WHERE `value` LIKE '%biolink%';
UPDATE `settings` SET `value` = REPLACE(`value`, 'Biolink', 'Microsite') WHERE `value` LIKE '%Biolink%';
UPDATE `settings` SET `value` = REPLACE(`value`, 'BIOLINK', 'MICROSITE') WHERE `value` LIKE '%BIOLINK%';

-- Update settings keys that contain 'biolink'
UPDATE `settings` SET `key` = REPLACE(`key`, 'biolink', 'microsite') WHERE `key` LIKE '%biolink%';

-- Update plan settings in users table
UPDATE `users` SET `plan_settings` = REPLACE(`plan_settings`, 'biolink', 'microsite') WHERE `plan_settings` LIKE '%biolink%';
UPDATE `users` SET `plan_settings` = REPLACE(`plan_settings`, 'Biolink', 'Microsite') WHERE `plan_settings` LIKE '%Biolink%';

-- Update any other JSON fields that might contain biolink references
UPDATE `links` SET `settings` = REPLACE(`settings`, 'biolink', 'microsite') WHERE `settings` LIKE '%biolink%';
UPDATE `microsites_blocks` SET `settings` = REPLACE(`settings`, 'biolink', 'microsite') WHERE `settings` LIKE '%biolink%';
UPDATE `microsites_themes` SET `settings` = REPLACE(`settings`, 'biolink', 'microsite') WHERE `settings` LIKE '%biolink%';
UPDATE `microsites_templates` SET `settings` = REPLACE(`settings`, 'biolink', 'microsite') WHERE `settings` LIKE '%biolink%';

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================
-- Run these to verify the migration worked correctly:

-- SELECT COUNT(*) FROM microsites_themes;
-- SELECT COUNT(*) FROM microsites_blocks; 
-- SELECT COUNT(*) FROM microsites_templates;
-- SHOW CREATE TABLE microsites_themes;
-- SHOW CREATE TABLE microsites_blocks;
-- SHOW CREATE TABLE microsites_templates;
-- SELECT * FROM settings WHERE value LIKE '%biolink%';

-- =====================================================
-- END OF MIGRATION SCRIPT
-- =====================================================
