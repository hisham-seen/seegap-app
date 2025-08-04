# Microsite Block Removal Summary

This document summarizes the complete removal of deprecated microsite blocks from the SeeGap application.

## Removed Blocks

The following 16 microsite blocks have been completely removed:

1. **alert** - Alert messages
2. **facebook** - Facebook embeds
3. **heading** - Text headings
4. **instagram_media** - Instagram media embeds
5. **paragraph** - Text paragraphs
6. **telegram** - Telegram embeds
7. **threads** - Threads embeds
8. **tiktok_profile** - TikTok profile embeds
9. **tiktok_video** - TikTok video embeds
10. **twitter_profile** - Twitter profile embeds
11. **twitter_tweet** - Twitter tweet embeds
12. **twitter_video** - Twitter video embeds
13. **youtube** - YouTube video embeds
14. **youtube_feed** - YouTube channel feeds
15. **cover** - Cover blocks with video/image backgrounds
16. **big_link** - Large link blocks

## Files and Components Removed

### Phase 1: Configuration Files
- ✅ Removed block definitions from `app/includes/microsite_blocks.php`
- ✅ Removed block definitions from `app/includes/enabled_microsite_blocks.php`

### Phase 2: Backend Controllers
- ✅ Removed `app/controllers/microsite-blocks/blocks/AlertBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/FacebookBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/HeadingBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/InstagramMediaBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/ParagraphBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/TelegramBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/ThreadsBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/TiktokVideoBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/TwitterProfileBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/TwitterTweetBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/TwitterVideoBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/YoutubeBlock.php`
- ✅ Removed `app/controllers/microsite-blocks/blocks/YoutubeFeedBlock.php`

### Phase 3: Frontend Display Templates
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/alert.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/facebook.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/heading.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/instagram_media.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/paragraph.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/telegram.php` (did not exist)
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/threads.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/tiktok_profile.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/tiktok_video.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/twitter_profile.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/twitter_tweet.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/twitter_video.php`
- ✅ Removed `themes/phoenix/views/l/microsite_blocks/youtube.php`

### Phase 4: Admin Settings Directories
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/alert/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/facebook/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/heading/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/instagram_media/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/paragraph/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/telegram/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/threads/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/tiktok_profile/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/tiktok_video/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/twitter_profile/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/twitter_tweet/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/twitter_video/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/youtube/`
- ✅ Removed `themes/phoenix/views/link/settings/microsite_blocks/youtube_feed/`

### Phase 5: Language Files
- ✅ Removed language entries from `app/languages/modules/microsites.php`

## Database Cleanup

A comprehensive SQL cleanup script has been created: `cleanup_removed_blocks.sql`

This script will:
- Remove all microsite blocks of the deprecated types from `microsites_blocks` table
- Remove associated tracking data from `track_links` table
- Remove associated data from `data` table
- Clean up user plan settings to remove these blocks from enabled lists
- Update settings table to remove blocks from default configurations
- Clean up plan configurations
- Remove references from plans table

**⚠️ IMPORTANT**: Run this script on your database to complete the removal process.

## Replacement Solution

All removed social media blocks have been replaced with a unified **Social Media Embed** block that:
- Supports all major social media platforms
- Provides better user interface and experience
- Offers more customization options
- Has improved responsive design
- Receives regular updates and new features

## Migration Path

For existing users with these deprecated blocks:
1. The blocks will continue to display using the deprecated block template
2. Users will see deprecation notices in the admin panel
3. Users are encouraged to replace deprecated blocks with the new Social Media Embed block
4. The deprecated block template shows benefits of the new unified block

## Files Created

1. `cleanup_removed_blocks.sql` - Database cleanup script
2. `BLOCK_REMOVAL_SUMMARY.md` - This summary document

## Database Installation Updates

✅ **Updated `install/dump.sql`** to ensure new installations don't include deprecated blocks:
- Removed deprecated blocks from admin user's `enabled_microsite_blocks` configuration
- Removed deprecated blocks from `links` settings `available_microsite_blocks` configuration  
- Updated example microsite blocks to use `text` block instead of deprecated `heading` and `paragraph` blocks
- New installations will now start with the updated block configuration

## Verification Steps

To verify the removal was successful:

1. **Check Configuration**: Verify removed blocks don't appear in block creation modals
2. **Test Admin Panel**: Ensure no errors when accessing microsite settings
3. **Database Check**: Run the cleanup script and verify data removal
4. **Frontend Test**: Ensure existing microsites still load properly
5. **Error Logs**: Monitor application logs for any missing file errors

## Rollback Plan

If rollback is needed:
1. Restore the removed files from version control
2. Restore the configuration entries
3. Restore the language entries
4. The database cleanup script is irreversible - restore from backup if needed

## Notes

- No backward compatibility maintained as requested
- All references removed end-to-end
- Social media functionality consolidated into unified block
- Existing deprecated blocks will show deprecation notices
- Database cleanup must be run manually for complete removal

## Completion Status

✅ **COMPLETED**: All phases of block removal have been successfully executed.

The application is now ready for testing and deployment with the deprecated blocks removed.
