-- Migration script to remove pages functionality
-- This script will drop the pages and pages_categories tables

-- Drop the pages table (has foreign key constraint to pages_categories)
DROP TABLE IF EXISTS `pages`;

-- Drop the pages_categories table
DROP TABLE IF EXISTS `pages_categories`;

-- Update settings to remove pages-related configuration
UPDATE `settings` SET `value` = JSON_REMOVE(`value`, '$.pages_is_enabled', '$.pages_share_is_enabled', '$.pages_popular_widget_is_enabled', '$.pages_views_is_enabled') WHERE `key` = 'content';
