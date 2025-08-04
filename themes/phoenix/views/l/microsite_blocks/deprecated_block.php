<?php defined('SEEGAP') || die() ?>

<?php
// Determine block information
$block_type = $data->link->type ?? 'unknown';
$block_name = ucfirst(str_replace('_', ' ', $block_type));

// Define deprecated social media blocks and their platform info
$deprecated_social_blocks = [
    'youtube_feed' => ['name' => 'YouTube Feed', 'icon' => 'fab fa-youtube', 'platform' => 'YouTube'],
    'youtube' => ['name' => 'YouTube', 'icon' => 'fab fa-youtube', 'platform' => 'YouTube'],
    'instagram_media' => ['name' => 'Instagram Media', 'icon' => 'fab fa-instagram', 'platform' => 'Instagram'],
    'twitter_tweet' => ['name' => 'Twitter Tweet', 'icon' => 'fab fa-twitter', 'platform' => 'Twitter'],
    'twitter_profile' => ['name' => 'Twitter Profile', 'icon' => 'fab fa-twitter', 'platform' => 'Twitter'],
    'twitter_video' => ['name' => 'Twitter Video', 'icon' => 'fab fa-twitter', 'platform' => 'Twitter'],
    'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook', 'platform' => 'Facebook'],
    'tiktok_profile' => ['name' => 'TikTok Profile', 'icon' => 'fab fa-tiktok', 'platform' => 'TikTok'],
    'tiktok_video' => ['name' => 'TikTok Video', 'icon' => 'fab fa-tiktok', 'platform' => 'TikTok'],
    'threads' => ['name' => 'Threads', 'icon' => 'fas fa-at', 'platform' => 'Threads'],
    'telegram' => ['name' => 'Telegram', 'icon' => 'fab fa-telegram', 'platform' => 'Telegram']
];

// Check if this is a deprecated social media block
$is_social_block = isset($deprecated_social_blocks[$block_type]);
$block_info = $is_social_block ? $deprecated_social_blocks[$block_type] : null;

// Set display information
$display_name = $block_info ? $block_info['name'] : $block_name;
$display_icon = $block_info ? $block_info['icon'] : 'fas fa-cube';
$platform_name = $block_info ? $block_info['platform'] : '';
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    
    <div class="card border-warning">
        <div class="card-body text-center py-4">
            
            <!-- Block Icon -->
            <div class="mb-3">
                <i class="<?= $display_icon ?> fa-3x text-warning mb-2"></i>
                <h5 class="text-warning mb-0">Block Deprecated</h5>
            </div>
            
            <!-- Deprecation Message -->
            <div class="alert alert-warning mb-3">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>This <?= $display_name ?> block is no longer supported</strong>
                <br><br>
                <?php if($is_social_block): ?>
                    This block has been replaced with the new unified <strong>"Social Media Embed"</strong> block which supports <?= $platform_name ?> and all other social media platforms.
                <?php else: ?>
                    This block type is no longer available or has been replaced with newer functionality.
                <?php endif ?>
            </div>
            
            <!-- Replacement Information -->
            <?php if($is_social_block): ?>
                <div class="mb-3">
                    <small class="text-muted">
                        <strong>Recommended replacement:</strong> Social Media Embed block<br>
                        Supports <?= $platform_name ?>, YouTube, Instagram, Twitter, TikTok, Facebook, Threads, Telegram and more!
                    </small>
                </div>
            <?php endif ?>
            
            <!-- Admin Notice (only visible to block owner) -->
            <?php if(isset($data->user) && $data->user): ?>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Admin Notice:</strong> Please edit your microsite and replace this block with a supported alternative.
                    </small>
                </div>
            <?php endif ?>
            
        </div>
    </div>
    
</div>

<style>
.deprecated-block-notice {
    border: 2px dashed #ffc107;
    background-color: #fff3cd;
    border-radius: 0.5rem;
    padding: 2rem;
    margin: 1rem 0;
}

.deprecated-block-notice .fa-3x {
    opacity: 0.7;
}

.deprecated-block-notice .alert {
    border: none;
    background-color: rgba(255, 193, 7, 0.1);
}

@media (max-width: 576px) {
    .deprecated-block-notice {
        padding: 1.5rem 1rem;
    }
    
    .deprecated-block-notice .fa-3x {
        font-size: 2rem;
    }
}
</style>
