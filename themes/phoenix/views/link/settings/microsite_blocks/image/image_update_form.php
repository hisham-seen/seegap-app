<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="image" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->image_size_limit) ?>">
        <label for="<?= 'image_image_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
            'id'=> 'image_image_' . $row->microsite_block_id,
            'uploads_file_key' => 'block_images',
            'file_key' => 'image',
            'already_existing_image' => $row->settings->image,
            'image_container' => 'image',
            'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions']),
            'input_data' => 'data-crop'
        ]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'image_image_alt_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_alt') ?></label>
        <input id="<?= 'image_image_alt_' . $row->microsite_block_id ?>" type="text" class="form-control" name="image_alt" value="<?= $row->settings->image_alt ?>" maxlength="100" />
        <small class="form-text text-muted"><?= l('microsite_link.image_alt_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'image_height_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_image.height') ?? 'Image Height' ?></label>
        <select id="<?= 'image_height_' . $row->microsite_block_id ?>" name="image_height" class="form-control">
            <option value="auto" <?= ($row->settings->image_height ?? 'auto') == 'auto' ? 'selected' : '' ?>><?= l('microsite_image.height_auto') ?? 'Auto (Original)' ?></option>
            <option value="small" <?= ($row->settings->image_height ?? 'auto') == 'small' ? 'selected' : '' ?>><?= l('microsite_image.height_small') ?? 'Small (200px)' ?></option>
            <option value="medium" <?= ($row->settings->image_height ?? 'auto') == 'medium' ? 'selected' : '' ?>><?= l('microsite_image.height_medium') ?? 'Medium (400px)' ?></option>
            <option value="large" <?= ($row->settings->image_height ?? 'auto') == 'large' ? 'selected' : '' ?>><?= l('microsite_image.height_large') ?? 'Large (600px)' ?></option>
            <option value="custom" <?= ($row->settings->image_height ?? 'auto') == 'custom' ? 'selected' : '' ?>><?= l('microsite_image.height_custom') ?? 'Custom' ?></option>
        </select>
    </div>

    <div class="form-group" id="<?= 'image_height_custom_container_' . $row->microsite_block_id ?>" style="display: <?= ($row->settings->image_height ?? 'auto') == 'custom' ? 'block' : 'none' ?>;">
        <label for="<?= 'image_height_custom_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-ruler fa-sm text-muted mr-1"></i> <?= l('microsite_image.height_custom_value') ?? 'Custom Height (px)' ?></label>
        <input id="<?= 'image_height_custom_' . $row->microsite_block_id ?>" type="number" class="form-control" name="image_height_custom" min="50" max="1000" value="<?= $row->settings->image_height_custom ?? 200 ?>" placeholder="200" />
        <small class="form-text text-muted"><?= l('microsite_image.height_custom_help') ?? 'Enter height in pixels (50-1000)' ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'image_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
        <input id="<?= 'image_location_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="location_url" value="<?= $row->location_url ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                id="<?= 'image_open_in_new_tab_' . $row->microsite_block_id ?>"
                name="open_in_new_tab" type="checkbox"
                class="custom-control-input"
            <?= $row->settings->open_in_new_tab ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'image_open_in_new_tab_' . $row->microsite_block_id ?>"><?= l('microsite_link.open_in_new_tab') ?></label>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heightSelect = document.getElementById('<?= 'image_height_' . $row->microsite_block_id ?>');
    const customContainer = document.getElementById('<?= 'image_height_custom_container_' . $row->microsite_block_id ?>');
    
    if (heightSelect && customContainer) {
        heightSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customContainer.style.display = 'block';
            } else {
                customContainer.style.display = 'none';
            }
        });
    }
});
</script>
