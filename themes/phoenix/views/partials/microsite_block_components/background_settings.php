<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Background Settings Component for Microsite Blocks
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param string $field_name - Field name for background color (default: 'background_color')
 * @param bool $include_image - Whether to include background image upload (default: false)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$field_name = $field_name ?? 'background_color';
$include_image = $include_image ?? false;
?>

<!-- Background Color -->
<?php
// Create isolated variables for color picker to prevent conflicts
$bg_field_name = $field_name;
$bg_label = l('microsite_link.background_color');
$bg_icon = 'fas fa-fill';
$bg_default = '#00000000'; // Transparent default
$bg_current = $settings->$bg_field_name ?? $bg_default;
$bg_include_opacity = true; // Enable opacity for background colors

// Set variables for color picker component
$color_picker_field_name = $bg_field_name;
$color_picker_label = $bg_label;
$color_picker_icon = $bg_icon;
$color_picker_default_color = $bg_default;
$color_picker_current_color = $bg_current;
$color_picker_include_opacity = $bg_include_opacity;

// Temporarily override variables for color picker
$field_name = $color_picker_field_name;
$label = $color_picker_label;
$icon = $color_picker_icon;
$default_color = $color_picker_default_color;
$current_color = $color_picker_current_color;
$include_opacity = $color_picker_include_opacity;

include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';

// Restore original field_name
$field_name = $bg_field_name;
?>

<?php if($include_image): ?>
<!-- Background Image -->
<div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->thumbnail_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?>">
    <label for="<?= 'background_image_' . $block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_image') ?></label>
    <input id="<?= 'background_image_' . $block_id ?>" type="file" name="background_image" accept=".jpg,.jpeg,.png,.svg,.gif,.webp,.avif" class="form-control-file altum-file-input" />
    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), 'jpg, jpeg, png, svg, gif, webp, avif') . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
    <?php if(!empty($settings->background_image)): ?>
        <div class="row">
            <div class="m-1 col-6 col-xl-3">
                <div class="custom-control custom-checkbox">
                    <input id="background_image_remove_<?= $block_id ?>" name="background_image_remove" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="background_image_remove_<?= $block_id ?>">
                        <span class="text-muted"><?= l('global.delete_file') ?></span>
                    </label>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
<?php endif ?>
