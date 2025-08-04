<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Cover Image Settings Component
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param bool $use_accordion - Whether to wrap in accordion (default: true)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$use_accordion = $use_accordion ?? true;
?>

<?php if ($use_accordion): ?>
<div class="card">
    <div class="card-header bg-white p-3 position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="h6 m-0"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_cover.title') ?></span>
                <div><small class="text-muted"><?= l('microsite_cover.description') ?></small></div>
            </div>
            <button type="button" class="btn btn-sm btn-block-settings" data-toggle="collapse" data-target="#<?= 'cover_image_settings_container_' . $block_id ?>" aria-expanded="false" aria-controls="<?= 'cover_image_settings_container_' . $block_id ?>">
                <i class="fas fa-fw fa-angle-down"></i>
            </button>
        </div>
    </div>
    <div class="collapse" id="<?= 'cover_image_settings_container_' . $block_id ?>">
        <div class="card-body">
<?php endif; ?>

<!-- Cover Image Upload -->
<?php
$field_name = 'cover_image';
$current_image = $settings->cover_image ?? '';
$accept_types = ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'];
$label = l('microsite_cover.image');
$icon = 'fas fa-image';
$uploads_file_key = 'backgrounds';
$size_limit_setting = settings()->links->background_size_limit ?? 10;
$enable_crop = false;
include THEME_PATH . 'views/partials/microsite_block_components/advanced_image_upload.php';
?>

<!-- Cover Position -->
<div class="form-group">
    <label for="<?= 'cover_position_' . $block_id ?>"><i class="fas fa-fw fa-crosshairs fa-sm text-muted mr-1"></i> <?= l('microsite_cover.position') ?></label>
    <select id="<?= 'cover_position_' . $block_id ?>" name="cover_position" class="custom-select">
        <option value="center" <?= ($settings->cover_position ?? 'center') == 'center' ? 'selected' : '' ?>><?= l('microsite_cover.position_center') ?></option>
        <option value="top-left" <?= ($settings->cover_position ?? '') == 'top-left' ? 'selected' : '' ?>><?= l('microsite_cover.position_top_left') ?></option>
        <option value="top-right" <?= ($settings->cover_position ?? '') == 'top-right' ? 'selected' : '' ?>><?= l('microsite_cover.position_top_right') ?></option>
        <option value="bottom-left" <?= ($settings->cover_position ?? '') == 'bottom-left' ? 'selected' : '' ?>><?= l('microsite_cover.position_bottom_left') ?></option>
        <option value="bottom-right" <?= ($settings->cover_position ?? '') == 'bottom-right' ? 'selected' : '' ?>><?= l('microsite_cover.position_bottom_right') ?></option>
    </select>
    <small class="form-text text-muted"><?= l('microsite_cover.position_help') ?></small>
</div>

<!-- Cover Blur -->
<div class="form-group" data-range-counter data-range-counter-suffix="px">
    <label for="<?= 'cover_blur_' . $block_id ?>"><i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> <?= l('microsite_cover.blur') ?></label>
    <input id="<?= 'cover_blur_' . $block_id ?>" type="range" min="0" max="10" class="form-control-range" name="cover_blur" value="<?= $settings->cover_blur ?? 0 ?>" />
    <small class="form-text text-muted"><?= l('microsite_cover.blur_help') ?></small>
</div>

<!-- Cover Overlay Color -->
<div class="form-group">
    <label for="<?= 'cover_overlay_color_' . $block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_cover.overlay_color') ?></label>
    <input id="<?= 'cover_overlay_color_' . $block_id ?>" type="color" class="form-control" name="cover_overlay_color" value="<?= $settings->cover_overlay_color ?? '#000000' ?>" />
    <small class="form-text text-muted"><?= l('microsite_cover.overlay_color_help') ?></small>
</div>

<!-- Cover Overlay Opacity -->
<div class="form-group" data-range-counter data-range-counter-suffix="%">
    <label for="<?= 'cover_overlay_opacity_' . $block_id ?>"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('microsite_cover.overlay_opacity') ?></label>
    <input id="<?= 'cover_overlay_opacity_' . $block_id ?>" type="range" min="0" max="80" class="form-control-range" name="cover_overlay_opacity" value="<?= $settings->cover_overlay_opacity ?? 0 ?>" />
    <small class="form-text text-muted"><?= l('microsite_cover.overlay_opacity_help') ?></small>
</div>

<?php if ($use_accordion): ?>
        </div>
    </div>
</div>
<?php endif; ?>
