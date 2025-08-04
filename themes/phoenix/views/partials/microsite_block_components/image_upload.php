<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Image Upload Component for Microsite Blocks
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $field_name - Field name for the image (default: 'image')
 * @param string $current_image - Current image value
 * @param array $accept_types - Accepted file extensions
 * @param string $label - Label for the field (default: 'Image')
 * @param string $icon - Icon class for the label (default: 'fas fa-image')
 */

$block_id = $block_id ?? 'default';
$field_name = $field_name ?? 'image';
$current_image = $current_image ?? '';
$accept_types = $accept_types ?? ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'];
$label = $label ?? l('microsite_link.image');
$icon = $icon ?? 'fas fa-image';
$accept_string = is_array($accept_types) ? '.' . implode(',.', $accept_types) : $accept_types;
$accept_display = is_array($accept_types) ? implode(', ', $accept_types) : $accept_types;
?>

<div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->thumbnail_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?>">
    <label for="<?= $field_name . '_' . $block_id ?>"><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <input id="<?= $field_name . '_' . $block_id ?>" type="file" name="<?= $field_name ?>" accept="<?= $accept_string ?>" class="form-control-file altum-file-input" />
    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), $accept_display) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
    <?php if(!empty($current_image)): ?>
        <div class="row">
            <div class="m-1 col-6 col-xl-3">
                <div class="custom-control custom-checkbox">
                    <input id="<?= $field_name ?>_remove_<?= $block_id ?>" name="<?= $field_name ?>_remove" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="<?= $field_name ?>_remove_<?= $block_id ?>">
                        <span class="text-muted"><?= l('global.delete_file') ?></span>
                    </label>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
