<?php defined('SEEGAP') || die() ?>

<!-- SEO Settings Component -->
<div class="mb-4">    
    <div class="form-group mb-3">
        <label class="small mb-1"><i class="fas fa-fw fa-search-plus fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_header') ?></label>
        <div <?= $this->user->plan_settings->seo ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
            <div class="<?= $this->user->plan_settings->seo ? null : 'container-disabled' ?>">
                <div class="form-group custom-control custom-switch">
                    <input id="seo_block" name="seo_block" type="checkbox" class="custom-control-input" <?= $data->link->settings->seo->block ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="seo_block"><?= l('link.settings.seo_block') ?></label>
                    <small class="form-text text-muted"><?= l('link.settings.seo_block_help') ?></small>
                </div>

                <div class="form-group mb-2">
                    <label for="seo_title" class="small mb-1"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_title') ?></label>
                    <input id="seo_title" type="text" class="form-control" name="seo_title" value="<?= $data->link->settings->seo->title ?? '' ?>" maxlength="70" />
                    <small class="form-text text-muted"><?= l('link.settings.seo_title_help') ?></small>
                </div>

                <div class="form-group mb-2">
                    <label for="seo_meta_description" class="small mb-1"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_meta_description') ?></label>
                    <input id="seo_meta_description" type="text" class="form-control" name="seo_meta_description" value="<?= $data->link->settings->seo->meta_description ?? '' ?>" maxlength="160" />
                    <small class="form-text text-muted"><?= l('link.settings.seo_meta_description_help') ?></small>
                </div>

                <div class="form-group mb-2">
                    <label for="seo_meta_keywords" class="small mb-1"><i class="fas fa-fw fa-file-word fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_meta_keywords') ?></label>
                    <input id="seo_meta_keywords" type="text" class="form-control" name="seo_meta_keywords" value="<?= $data->link->settings->seo->meta_keywords ?? '' ?>" maxlength="160" />
                </div>

                <div class="form-group mb-2" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->seo_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->seo_image_size_limit) ?>">
                    <label for="seo_image" class="small mb-1"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_image') ?></label>
                    <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', ['uploads_file_key' => 'microsite_seo_image', 'file_key' => 'seo_image', 'already_existing_image' => $data->link->settings->seo->image, 'image_container' => 'seo_image', 'input_data' => 'data-crop data-aspect-ratio="1.91"']) ?>
                    <?= \SeeGap\Alerts::output_field_error('seo_image') ?>
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('microsite_seo_image')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->seo_image_size_limit) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
