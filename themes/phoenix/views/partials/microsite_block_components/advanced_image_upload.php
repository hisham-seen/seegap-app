<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Advanced Image Upload Component for Microsite Blocks
 * This handles the more complex image upload with cropping functionality
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $field_name - Field name for the image (default: 'image')
 * @param string $current_image - Current image value
 * @param array $accept_types - Accepted file extensions
 * @param string $label - Label for the field
 * @param string $icon - Icon class for the label
 * @param string $uploads_file_key - Upload key for file handling
 * @param string $size_limit_setting - Settings key for size limit
 * @param bool $enable_crop - Whether to enable cropping functionality
 */

$block_id = $block_id ?? 'default';
$field_name = $field_name ?? 'image';
$current_image = $current_image ?? '';
$accept_types = $accept_types ?? ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'];
$label = $label ?? l('global.image');
$icon = $icon ?? 'fas fa-image';
$uploads_file_key = $uploads_file_key ?? 'block_images';
$size_limit_setting = $size_limit_setting ?? settings()->links->image_size_limit;
$enable_crop = $enable_crop ?? false;

$accept_string = is_array($accept_types) ? \SeeGap\Uploads::array_to_list_format($accept_types) : $accept_types;
$accept_display = is_array($accept_types) ? \SeeGap\Uploads::array_to_list_format($accept_types) : $accept_types;
?>

<div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= $size_limit_setting ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), $size_limit_setting) ?>">
    <label for="<?= $field_name . '_' . $block_id ?>"><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
        'id'=> $field_name . '_' . $block_id,
        'uploads_file_key' => $uploads_file_key,
        'file_key' => $field_name,
        'already_existing_image' => $current_image,
        'image_container' => $field_name,
        'accept' => $accept_string,
        'input_data' => $enable_crop ? 'data-crop' : ''
    ]) ?>
    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), $accept_display) . ' ' . sprintf(l('global.accessibility.file_size_limit'), $size_limit_setting) ?></small>
</div>
