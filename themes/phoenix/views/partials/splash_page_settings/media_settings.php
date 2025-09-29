<?php defined('SEEGAP') || die() ?>

<!-- Simple Splash Page Media Settings Component -->
<div class="form-group mb-3" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->avatar_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->avatar_size_limit) ?>">
    <label for="logo" class="small mb-2 font-weight-bold">
        <i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> 
        <?= l('splash_pages.logo') ?>
    </label>
    <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', [
        'uploads_file_key' => 'splash_pages', 
        'file_key' => 'logo', 
        'already_existing_image' => $data->splash_page->settings->logo ?? null, 
        'input_data' => 'data-crop data-aspect-ratio="2"'
    ]) ?>
    <small class="form-text text-muted"><?= l('splash_pages.logo_help') ?></small>
</div>
