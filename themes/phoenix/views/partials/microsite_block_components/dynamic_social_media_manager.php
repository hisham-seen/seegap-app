<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Dynamic Social Media Manager Component for Microsite Blocks
 * Provides add/remove functionality for social media platforms with platform selection
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object containing socials data
 * @param string $container_id - ID for the social media container
 * @param int $max_platforms - Maximum number of platforms allowed (default: 20)
 * @param string $field_prefix - Prefix for field names (default: 'socials')
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$container_id = $container_id ?? 'social_media_container_' . $block_id;
$max_platforms = $max_platforms ?? 20;
$field_prefix = $field_prefix ?? 'socials';

// Load social media platforms configuration
$microsite_socials = require APP_PATH . 'includes/microsite_socials.php';

// Get existing social media entries
$existing_socials = [];
if (isset($settings->socials)) {
    foreach ($settings->socials as $platform => $value) {
        if (!empty($value)) {
            $existing_socials[] = [
                'platform' => $platform,
                'value' => $value
            ];
        }
    }
}
?>

<div class="form-group">
    <label><i class="fas fa-fw fa-share-alt fa-sm text-muted mr-1"></i> <?= l('microsite_socials.header') ?? 'Social Media Links' ?></label>
    <div id="<?= $container_id ?>" class="social-media-accordion">
        <?php if(!empty($existing_socials)): ?>
            <?php foreach($existing_socials as $index => $social): ?>
