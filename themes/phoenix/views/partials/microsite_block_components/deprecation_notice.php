<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Deprecation Notice Component for Microsite Blocks
 * Shows a clean deprecation message for old blocks
 * 
 * @param string $block_name - Name of the deprecated block
 * @param string $block_icon - FontAwesome icon class for the block
 * @param string $platform_name - Name of the platform (for social media blocks)
 * @param string $description - Additional description text (optional)
 */

$block_name = $block_name ?? 'Block';
$block_icon = $block_icon ?? 'fas fa-cube';
$platform_name = $platform_name ?? '';
$description = $description ?? '';
?>

<div class="text-center py-5">
    <div class="mb-4">
        <i class="<?= $block_icon ?> fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Block Deprecated</h4>
    </div>
    
    <div class="alert alert-warning mx-auto" style="max-width: 500px;">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>This <?= $block_name ?> block has been deprecated</strong>
        <br><br>
        This block has been replaced with the new unified <strong>"Social Media Embed"</strong> block which supports <?= $platform_name ? $platform_name . ' and ' : '' ?>all other social media platforms in one powerful interface.
        <?php if($description): ?>
            <br><br>
            <?= $description ?>
        <?php endif ?>
        <br><br>
        <strong>Benefits of the new block:</strong>
        <ul class="text-left mt-3 mb-3">
            <li>✅ Support for all major social platforms</li>
            <li>✅ Better user interface and experience</li>
            <li>✅ More customization options</li>
            <li>✅ Improved responsive design</li>
            <li>✅ Regular updates and new features</li>
        </ul>
    </div>

    <div class="mt-4">
        <button type="button" class="btn btn-primary btn-lg" onclick="addSocialMediaEmbedBlock()">
            <i class="fas fa-plus mr-2"></i>
            Add Social Media Embed Block
        </button>
    </div>

    <div class="mt-3">
        <small class="text-muted">
            The new Social Media Embed block supports <?= $platform_name ? $platform_name . ', ' : '' ?>YouTube, Instagram, Twitter, TikTok, Facebook, Threads, Telegram and more!
        </small>
    </div>
</div>

<script>
function addSocialMediaEmbedBlock() {
    // Close current modal
    if (typeof $.fn.modal !== 'undefined') {
        $('.modal').modal('hide');
    }
    
    // Show success message
    if (typeof show_notification !== 'undefined') {
        show_notification('Please add a new "Social Media Embed" block from the block selector.', 'info');
    } else {
        alert('Please add a new "Social Media Embed" block from the block selector.');
    }
    
    // Optionally trigger block selector if available
    if (typeof window.openBlockSelector === 'function') {
        setTimeout(() => {
            window.openBlockSelector();
        }, 500);
    }
}
</script>

<style>
.deprecation-notice .alert ul {
    margin-bottom: 0;
    padding-left: 1.2rem;
}

.deprecation-notice .alert li {
    margin-bottom: 0.25rem;
}

.deprecation-notice .fa-4x {
    opacity: 0.3;
}

@media (max-width: 576px) {
    .deprecation-notice .alert {
        margin-left: 1rem;
        margin-right: 1rem;
    }
    
    .deprecation-notice .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
}
</style>
