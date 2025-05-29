-- Migration script to remove blog functionality
-- This script will drop the blog-related tables

-- Drop the blog_posts_ratings table (has foreign key constraints)
DROP TABLE IF EXISTS `blog_posts_ratings`;

-- Drop the blog_posts table (has foreign key constraint to blog_posts_categories)
DROP TABLE IF EXISTS `blog_posts`;

-- Drop the blog_posts_categories table
DROP TABLE IF EXISTS `blog_posts_categories`;

-- Update settings to remove blog-related configuration
UPDATE `settings` SET `value` = JSON_REMOVE(`value`, '$.blog_is_enabled', '$.blog_share_is_enabled', '$.blog_search_widget_is_enabled', '$.blog_categories_widget_is_enabled', '$.blog_popular_widget_is_enabled', '$.blog_views_is_enabled', '$.blog_ratings_is_enabled') WHERE `key` = 'content';
