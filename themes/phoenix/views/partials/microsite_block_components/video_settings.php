<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Video Settings Component for Microsite Blocks
 * Provides video URL and playback controls
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param string $field_prefix - Prefix for field names (default: 'video')
 * @param bool $collapsed - Whether the section should be collapsed by default (default: false)
 * @param bool $show_url_field - Whether to show the video URL field (default: true)
 * @param array $controls - Array of controls to show (default: all)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$field_prefix = $field_prefix ?? 'video';
$collapsed = $collapsed ?? false;
$show_url_field = $show_url_field ?? true;
$controls = $controls ?? ['controls', 'autoplay', 'loop', 'muted'];

// Define available video controls
$available_controls = [
    'controls' => [
        'label' => l('microsite_cover.video_controls') ?? 'Show Controls',
        'help' => l('microsite_cover.video_controls_help') ?? 'Display video playback controls',
        'default' => false
    ],
    'autoplay' => [
        'label' => l('microsite_cover.video_autoplay') ?? 'Autoplay',
        'help' => l('microsite_cover.video_autoplay_help') ?? 'Start playing automatically',
        'default' => true
    ],
    'loop' => [
        'label' => l('microsite_cover.video_loop') ?? 'Loop',
        'help' => l('microsite_cover.video_loop_help') ?? 'Repeat video when it ends',
        'default' => true
    ],
    'muted' => [
        'label' => l('microsite_cover.video_muted') ?? 'Muted',
        'help' => l('microsite_cover.video_muted_help') ?? 'Start with audio muted',
        'default' => true
    ]
];
?>

<?php if($collapsed): ?>
<div class="card mb-3">
    <div class="card-header" data-toggle="collapse" data-target="#video_settings_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
        <h6 class="mb-0">
            <i class="fas fa-fw fa-video fa-sm text-muted mr-2"></i>
            <?= l('microsite_cover.video_settings') ?? 'Video Settings' ?>
            <i class="fas fa-chevron-down float-right"></i>
        </h6>
    </div>
    <div id="video_settings_<?= $block_id ?>" class="collapse">
        <div class="card-body">
<?php endif ?>

            <?php if($show_url_field): ?>
                <!-- Video URL -->
                <div class="form-group">
                    <label for="<?= $field_prefix ?>_url_<?= $block_id ?>">
                        <i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_cover.video_url') ?? 'Video URL' ?>
                    </label>
                    <input 
                        id="<?= $field_prefix ?>_url_<?= $block_id ?>" 
                        type="url" 
                        name="<?= $field_prefix ?>_url" 
                        class="form-control" 
                        value="<?= $settings->{$field_prefix . '_url'} ?? '' ?>" 
                        maxlength="2048" 
                        placeholder="<?= l('global.url_placeholder') ?? 'https://example.com/video.mp4' ?>" 
                    />
                    <small class="form-text text-muted">
                        <?= l('microsite_cover.video_url_help') ?? 'Enter a direct video URL (MP4, WebM, OGV) or YouTube/Vimeo URL' ?>
                    </small>
                </div>
            <?php endif ?>

            <!-- Video Controls -->
            <?php foreach($controls as $control): ?>
                <?php if(isset($available_controls[$control])): ?>
                    <div class="form-group custom-control custom-switch">
                        <input 
                            id="<?= $field_prefix ?>_<?= $control ?>_<?= $block_id ?>" 
                            name="<?= $field_prefix ?>_<?= $control ?>" 
                            type="checkbox" 
                            class="custom-control-input" 
                            <?= ($settings->{$field_prefix . '_' . $control} ?? $available_controls[$control]['default']) ? 'checked="checked"' : null ?>
                        >
                        <label class="custom-control-label" for="<?= $field_prefix ?>_<?= $control ?>_<?= $block_id ?>">
                            <?= $available_controls[$control]['label'] ?>
                        </label>
                        <?php if(isset($available_controls[$control]['help'])): ?>
                            <small class="form-text text-muted"><?= $available_controls[$control]['help'] ?></small>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            <?php endforeach ?>

<?php if($collapsed): ?>
        </div>
    </div>
</div>
<?php endif ?>

<style>
.video-settings .custom-control {
    padding-left: 2rem;
}

.video-settings .custom-control-label {
    font-weight: 500;
}

.video-settings .form-text {
    margin-top: 0.25rem;
    margin-left: 2rem;
}

.video-settings .form-group:last-child {
    margin-bottom: 0;
}

@media (max-width: 576px) {
    .video-settings .custom-control {
        padding-left: 1.5rem;
    }
    
    .video-settings .form-text {
        margin-left: 1.5rem;
        font-size: 0.8rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Video URL validation
    const videoUrlInput = document.getElementById('<?= $field_prefix ?>_url_<?= $block_id ?>');
    
    if (videoUrlInput) {
        videoUrlInput.addEventListener('blur', function() {
            const url = this.value.trim();
            if (url && !isValidVideoUrl(url)) {
                this.classList.add('is-invalid');
                showVideoUrlError(this, 'Please enter a valid video URL');
            } else {
                this.classList.remove('is-invalid');
                hideVideoUrlError(this);
            }
        });
        
        videoUrlInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            hideVideoUrlError(this);
        });
    }
    
    function isValidVideoUrl(url) {
        // Basic URL validation
        const urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
        
        // Check for common video platforms and file extensions
        const videoPattern = /\.(mp4|webm|ogv|avi|mov|wmv|flv|mkv)$/i;
        const youtubePattern = /(youtube\.com|youtu\.be)/i;
        const vimeoPattern = /vimeo\.com/i;
        
        return urlPattern.test(url) && (videoPattern.test(url) || youtubePattern.test(url) || vimeoPattern.test(url));
    }
    
    function showVideoUrlError(input, message) {
        hideVideoUrlError(input);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.setAttribute('data-video-error', 'true');
        
        input.parentNode.appendChild(errorDiv);
    }
    
    function hideVideoUrlError(input) {
        const existingError = input.parentNode.querySelector('[data-video-error="true"]');
        if (existingError) {
            existingError.remove();
        }
    }
    
    // Auto-configure settings based on video type
    if (videoUrlInput) {
        videoUrlInput.addEventListener('change', function() {
            const url = this.value.trim();
            autoConfigureVideoSettings(url);
        });
    }
    
    function autoConfigureVideoSettings(url) {
        if (!url) return;
        
        const autoplayCheckbox = document.getElementById('<?= $field_prefix ?>_autoplay_<?= $block_id ?>');
        const mutedCheckbox = document.getElementById('<?= $field_prefix ?>_muted_<?= $block_id ?>');
        const controlsCheckbox = document.getElementById('<?= $field_prefix ?>_controls_<?= $block_id ?>');
        
        // YouTube/Vimeo videos - suggest different defaults
        if (/(youtube\.com|youtu\.be|vimeo\.com)/i.test(url)) {
            if (autoplayCheckbox) autoplayCheckbox.checked = false;
            if (mutedCheckbox) mutedCheckbox.checked = false;
            if (controlsCheckbox) controlsCheckbox.checked = true;
        }
        // Direct video files - suggest autoplay with muted
        else if (/\.(mp4|webm|ogv)$/i.test(url)) {
            if (autoplayCheckbox) autoplayCheckbox.checked = true;
            if (mutedCheckbox) mutedCheckbox.checked = true;
            if (controlsCheckbox) controlsCheckbox.checked = false;
        }
    }
});
</script>
