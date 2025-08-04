-- Database cleanup script for removed microsite blocks
-- This script removes all data related to the following blocks:
-- alert, facebook, heading, instagram_media, paragraph, telegram, threads, 
-- tiktok_profile, tiktok_video, twitter_profile, twitter_tweet, twitter_video, youtube, youtube_feed,
-- cover, big_link

-- WARNING: This will permanently delete all data for these block types
-- Make sure to backup your database before running this script

-- Remove all microsite blocks of the deprecated types
DELETE FROM microsites_blocks 
WHERE type IN (
    'alert', 'facebook', 'heading', 'instagram_media', 'paragraph', 
    'telegram', 'threads', 'tiktok_profile', 'tiktok_video', 
    'twitter_profile', 'twitter_tweet', 'twitter_video', 'youtube', 'youtube_feed',
    'cover', 'big_link'
);

-- Remove tracking data for these blocks (if any orphaned records exist)
DELETE FROM track_links 
WHERE microsite_block_id IN (
    SELECT microsite_block_id FROM microsites_blocks 
    WHERE type IN (
        'alert', 'facebook', 'heading', 'instagram_media', 'paragraph', 
        'telegram', 'threads', 'tiktok_profile', 'tiktok_video', 
        'twitter_profile', 'twitter_tweet', 'twitter_video', 'youtube', 'youtube_feed'
    )
);

-- Remove any data table entries related to these blocks
DELETE FROM data 
WHERE microsite_block_id IN (
    SELECT microsite_block_id FROM microsites_blocks 
    WHERE type IN (
        'alert', 'facebook', 'heading', 'instagram_media', 'paragraph', 
        'telegram', 'threads', 'tiktok_profile', 'tiktok_video', 
        'twitter_profile', 'twitter_tweet', 'twitter_video', 'youtube', 'youtube_feed'
    )
);

-- Update user plan settings to remove these blocks from enabled lists
UPDATE users 
SET plan_settings = JSON_REMOVE(
    plan_settings,
    '$.enabled_microsite_blocks.alert',
    '$.enabled_microsite_blocks.facebook',
    '$.enabled_microsite_blocks.heading',
    '$.enabled_microsite_blocks.instagram_media',
    '$.enabled_microsite_blocks.paragraph',
    '$.enabled_microsite_blocks.telegram',
    '$.enabled_microsite_blocks.threads',
    '$.enabled_microsite_blocks.tiktok_profile',
    '$.enabled_microsite_blocks.tiktok_video',
    '$.enabled_microsite_blocks.twitter_profile',
    '$.enabled_microsite_blocks.twitter_tweet',
    '$.enabled_microsite_blocks.twitter_video',
    '$.enabled_microsite_blocks.youtube',
    '$.enabled_microsite_blocks.youtube_feed'
)
WHERE plan_settings IS NOT NULL;

-- Update settings table to remove these blocks from default configurations
UPDATE settings 
SET value = JSON_REMOVE(
    value,
    '$.available_microsite_blocks.alert',
    '$.available_microsite_blocks.facebook',
    '$.available_microsite_blocks.heading',
    '$.available_microsite_blocks.instagram_media',
    '$.available_microsite_blocks.paragraph',
    '$.available_microsite_blocks.telegram',
    '$.available_microsite_blocks.threads',
    '$.available_microsite_blocks.tiktok_profile',
    '$.available_microsite_blocks.tiktok_video',
    '$.available_microsite_blocks.twitter_profile',
    '$.available_microsite_blocks.twitter_tweet',
    '$.available_microsite_blocks.twitter_video',
    '$.available_microsite_blocks.youtube',
    '$.available_microsite_blocks.youtube_feed'
)
WHERE `key` = 'links' AND value IS NOT NULL;

-- Update plan configurations to remove these blocks
UPDATE settings 
SET value = JSON_REMOVE(
    value,
    '$.settings.enabled_microsite_blocks.alert',
    '$.settings.enabled_microsite_blocks.facebook',
    '$.settings.enabled_microsite_blocks.heading',
    '$.settings.enabled_microsite_blocks.instagram_media',
    '$.settings.enabled_microsite_blocks.paragraph',
    '$.settings.enabled_microsite_blocks.telegram',
    '$.settings.enabled_microsite_blocks.threads',
    '$.settings.enabled_microsite_blocks.tiktok_profile',
    '$.settings.enabled_microsite_blocks.tiktok_video',
    '$.settings.enabled_microsite_blocks.twitter_profile',
    '$.settings.enabled_microsite_blocks.twitter_tweet',
    '$.settings.enabled_microsite_blocks.twitter_video',
    '$.settings.enabled_microsite_blocks.youtube',
    '$.settings.enabled_microsite_blocks.youtube_feed'
)
WHERE `key` IN ('plan_free', 'plan_custom') AND value IS NOT NULL;

-- Clean up any remaining references in plans table
UPDATE plans 
SET settings = JSON_REMOVE(
    settings,
    '$.enabled_microsite_blocks.alert',
    '$.enabled_microsite_blocks.facebook',
    '$.enabled_microsite_blocks.heading',
    '$.enabled_microsite_blocks.instagram_media',
    '$.enabled_microsite_blocks.paragraph',
    '$.enabled_microsite_blocks.telegram',
    '$.enabled_microsite_blocks.threads',
    '$.enabled_microsite_blocks.tiktok_profile',
    '$.enabled_microsite_blocks.tiktok_video',
    '$.enabled_microsite_blocks.twitter_profile',
    '$.enabled_microsite_blocks.twitter_tweet',
    '$.enabled_microsite_blocks.twitter_video',
    '$.enabled_microsite_blocks.youtube',
    '$.enabled_microsite_blocks.youtube_feed'
)
WHERE settings IS NOT NULL;

-- Display cleanup summary
SELECT 
    'Cleanup completed' as status,
    (SELECT COUNT(*) FROM microsites_blocks WHERE type IN ('alert', 'facebook', 'heading', 'instagram_media', 'paragraph', 'telegram', 'threads', 'tiktok_profile', 'tiktok_video', 'twitter_profile', 'twitter_tweet', 'twitter_video', 'youtube', 'youtube_feed')) as remaining_blocks,
    'All references to removed blocks have been cleaned from the database' as message;
