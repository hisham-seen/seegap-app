<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Verified Badge Component for Microsite Blocks
 * Provides complete verified badge settings including enable/disable, style, position, size, and color
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param string $field_prefix - Field name prefix (default: 'verified_badge')
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$field_prefix = $field_prefix ?? 'verified_badge';

// Extract verified badge settings
$verified_badge_enabled = $settings->verified_badge->enabled ?? false;
$verified_badge_style = $settings->verified_badge->style ?? 'checkmark';
$verified_badge_position = $settings->verified_badge->position ?? 'bottom_right';
$verified_badge_size = $settings->verified_badge->size ?? 'medium';
$verified_badge_color = $settings->verified_badge->color ?? '#1da1f2';

// Define available badge styles (8 total for 2 rows)
$badge_styles = [
    'checkmark' => ['icon' => 'fas fa-check-circle'],
    'star' => ['icon' => 'fas fa-star'],
    'crown' => ['icon' => 'fas fa-crown'],
    'shield' => ['icon' => 'fas fa-shield-alt'],
    'heart' => ['icon' => 'fas fa-heart'],
    'diamond' => ['icon' => 'fas fa-gem'],
    'medal' => ['icon' => 'fas fa-medal'],
    'award' => ['icon' => 'fas fa-award']
];
?>

<!-- Enable Verified Badge -->
<div class="form-group">
    <div class="custom-control custom-switch">
        <input id="<?= $field_prefix . '_enabled_' . $block_id ?>" name="<?= $field_prefix ?>_enabled" type="checkbox" class="custom-control-input" <?= $verified_badge_enabled ? 'checked="checked"' : '' ?>>
        <label class="custom-control-label" for="<?= $field_prefix . '_enabled_' . $block_id ?>">
            <i class="fas fa-certificate fa-fw fa-sm text-muted mr-1"></i>
            <?= l('microsite_avatar.verified_badge_enabled') ?>
        </label>
    </div>
    <small class="form-text text-muted"><?= l('microsite_avatar.verified_badge_enabled_help') ?></small>
</div>

<div id="<?= $field_prefix . '_settings_' . $block_id ?>" class="<?= !$verified_badge_enabled ? 'd-none' : '' ?>">
    
    <!-- Badge Style -->
    <div class="form-group">
        <label><i class="fas fa-shapes fa-fw fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.verified_badge_style') ?></label>
        <div class="row">
            <?php foreach($badge_styles as $value => $config): ?>
                <div class="col-6 col-md-3">
                    <label class="badge-style-option">
                        <input type="radio" name="<?= $field_prefix ?>_style" value="<?= $value ?>" <?= $value === $verified_badge_style ? 'checked' : '' ?> class="d-none">
                        <div class="badge-style-preview <?= $value ?>-style <?= $value === $verified_badge_style ? 'active' : '' ?>">
                            <i class="<?= $config['icon'] ?>"></i>
                        </div>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- Badge Position -->
    <div class="form-group">
        <label for="<?= $field_prefix . '_position_' . $block_id ?>"><i class="fas fa-fw fa-crosshairs fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.verified_badge_position') ?></label>
        <select id="<?= $field_prefix . '_position_' . $block_id ?>" name="<?= $field_prefix ?>_position" class="custom-select">
            <option value="bottom_right" <?= $verified_badge_position == 'bottom_right' ? 'selected' : '' ?>><?= l('microsite_avatar.badge_position_bottom_right') ?></option>
            <option value="top_right" <?= $verified_badge_position == 'top_right' ? 'selected' : '' ?>><?= l('microsite_avatar.badge_position_top_right') ?></option>
            <option value="bottom_left" <?= $verified_badge_position == 'bottom_left' ? 'selected' : '' ?>><?= l('microsite_avatar.badge_position_bottom_left') ?></option>
            <option value="center_bottom" <?= $verified_badge_position == 'center_bottom' ? 'selected' : '' ?>><?= l('microsite_avatar.badge_position_center_bottom') ?></option>
        </select>
    </div>

    <!-- Badge Size -->
    <div class="form-group">
        <label><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.verified_badge_size') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-block text-truncate <?= $verified_badge_size == 'small' ? 'active' : '' ?>">
                    <input type="radio" name="<?= $field_prefix ?>_size" value="small" class="custom-control-input" <?= $verified_badge_size == 'small' ? 'checked="checked"' : '' ?> />
                    <?= l('common.size_small') ?>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-block text-truncate <?= $verified_badge_size == 'medium' ? 'active' : '' ?>">
                    <input type="radio" name="<?= $field_prefix ?>_size" value="medium" class="custom-control-input" <?= $verified_badge_size == 'medium' ? 'checked="checked"' : '' ?> />
                    <?= l('common.size_medium') ?>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-block text-truncate <?= $verified_badge_size == 'large' ? 'active' : '' ?>">
                    <input type="radio" name="<?= $field_prefix ?>_size" value="large" class="custom-control-input" <?= $verified_badge_size == 'large' ? 'checked="checked"' : '' ?> />
                    <?= l('common.size_large') ?>
                </label>
            </div>
        </div>
    </div>

    <!-- Badge Color -->
    <?php
    $field_name = $field_prefix . '_color';
    $label = l('microsite_avatar.verified_badge_color');
    $icon = 'fas fa-palette';
    $default_color = '#1da1f2';
    $current_color = $verified_badge_color;
    $include_opacity = false;
    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
    ?>

</div>

<style>
.badge-style-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.badge-style-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px 10px;
    text-align: center;
    background: #f8f9fa;
}

.badge-style-option input:checked + .badge-style-preview,
.badge-style-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.badge-style-preview i {
    font-size: 1.8rem;
    color: #007bff;
}

/* Badge style specific colors */
.checkmark-style.active i { color: #28a745; }
.star-style.active i { color: #ffc107; }
.crown-style.active i { color: #fd7e14; }
.shield-style.active i { color: #6f42c1; }
.heart-style.active i { color: #e91e63; }
.diamond-style.active i { color: #00bcd4; }
.medal-style.active i { color: #ff9800; }
.award-style.active i { color: #9c27b0; }

@media (max-width: 576px) {
    .badge-style-preview {
        padding: 10px 5px;
    }
    
    .badge-style-preview i {
        font-size: 1.2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $block_id ?>';
    const fieldPrefix = '<?= $field_prefix ?>';
    
    // Badge enable/disable toggle
    const badgeEnabledToggle = document.getElementById(fieldPrefix + '_enabled_' + blockId);
    const badgeSettings = document.getElementById(fieldPrefix + '_settings_' + blockId);
    
    if (badgeEnabledToggle && badgeSettings) {
        badgeEnabledToggle.addEventListener('change', function() {
            if (this.checked) {
                badgeSettings.classList.remove('d-none');
            } else {
                badgeSettings.classList.add('d-none');
            }
        });
    }
    
    // Badge style selection
    const badgeStyleOptions = document.querySelectorAll('input[name="' + fieldPrefix + '_style"]');
    const badgeStylePreviews = document.querySelectorAll('.badge-style-preview');
    
    // Add click handlers to badge style previews
    badgeStylePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="' + fieldPrefix + '_style"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all badge style previews
                badgeStylePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected badge style
                this.classList.add('active');
                
                // Trigger change event for external listeners
                radioInput.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Handle radio button changes directly
    badgeStyleOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all badge style previews
            badgeStylePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected badge style
            const selectedPreview = this.parentElement.querySelector('.badge-style-preview');
            if (selectedPreview) {
                selectedPreview.classList.add('active');
            }
        });
    });
});
</script>
