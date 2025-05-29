-- Disable foreign key checks to avoid constraint issues
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables
DROP TABLE IF EXISTS tools_ratings;
DROP TABLE IF EXISTS tools_usage;
DROP TABLE IF EXISTS data;
DROP TABLE IF EXISTS qr_codes;
DROP TABLE IF EXISTS gs1_links;
DROP TABLE IF EXISTS microsites_blocks;
DROP TABLE IF EXISTS track_links;
DROP TABLE IF EXISTS microsites_templates;
DROP TABLE IF EXISTS domains;
DROP TABLE IF EXISTS pixels;
DROP TABLE IF EXISTS links;
DROP TABLE IF EXISTS microsites_themes;
DROP TABLE IF EXISTS splash_pages;
DROP TABLE IF EXISTS blog_posts_ratings;
DROP TABLE IF EXISTS blog_posts;
DROP TABLE IF EXISTS blog_posts_categories;
DROP TABLE IF EXISTS pages;
DROP TABLE IF EXISTS pages_categories;
DROP TABLE IF EXISTS broadcasts_statistics;
DROP TABLE IF EXISTS broadcasts;
DROP TABLE IF EXISTS internal_notifications;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS users_logs;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS redeemed_codes;
DROP TABLE IF EXISTS codes;
DROP TABLE IF EXISTS taxes;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS plans;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
