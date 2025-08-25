<?php defined('SEEGAP') || die() ?>

<!-- PWA Settings Component -->
<?php if(\SeeGap\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
<div class="mb-4">
    <h6 class="text-muted mb-3">
        <i class="fas fa-fw fa-mobile-alt fa-sm mr-1"></i>
        PWA Settings
    </h6>
    
    <div class="form-group mb-3">
        <label class="small mb-1"><i class="fas fa-fw fa-mobile-alt fa-sm text-muted mr-1"></i> <?= l('link.settings.pwa_header') ?></label>
        <div class="alert alert-info">
            <i class="fas fa-fw fa-info-circle mr-1"></i> <?= l('link.settings.pwa_help') ?>
        </div>

        <div <?= !$this->user->plan_settings->custom_pwa_is_enabled ? 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' : null ?>>
            <div class="<?= !$this->user->plan_settings->custom_pwa_is_enabled ? 'container-disabled' : null ?>">

                <div class="form-group custom-control custom-switch">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            id="pwa_is_enabled"
                            name="pwa_is_enabled"
                        <?= $data->link->settings->pwa_is_enabled ? 'checked="checked"' : null ?>
                        <?= !$this->user->plan_settings->custom_pwa_is_enabled ? 'disabled="disabled"' : null ?>
                    >
                    <label class="custom-control-label" for="pwa_is_enabled"><?= l('link.settings.pwa_is_enabled') ?></label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            id="pwa_display_install_bar"
                            name="pwa_display_install_bar"
                        <?= $data->link->settings->pwa_display_install_bar ? 'checked="checked"' : null ?>
                        <?= !$this->user->plan_settings->custom_pwa_is_enabled ? 'disabled="disabled"' : null ?>
                    >
                    <label class="custom-control-label" for="pwa_display_install_bar"><?= l('link.settings.pwa_display_install_bar') ?></label>
                </div>

                <div class="form-group">
                    <label for="pwa_display_install_bar_delay"><i class="fas fa-fw fa-bars fa-sm text-muted mr-1"></i> <?= l('link.settings.pwa_display_install_bar_delay') ?></label>
                    <div class="input-group">
                        <input id="pwa_display_install_bar_delay" type="number" min="0" class="form-control" name="pwa_display_install_bar_delay" value="<?= $data->link->settings->pwa_display_install_bar_delay ?? 3 ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->pwa_icon_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->pwa_icon_size_limit) ?>">
                    <label for="pwa_icon"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.pwa_icon') ?></label>
                    <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', ['uploads_file_key' => 'app_icon', 'file_key' => 'pwa_icon', 'already_existing_image' => $data->link->settings->pwa_icon, 'image_container' => 'pwa_icon']) ?>
                    <?= \SeeGap\Alerts::output_field_error('pwa_icon') ?>
                    <small class="form-text text-muted"><?= l('link.settings.pwa_icon_help') ?><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('app_icon')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->pwa_icon_size_limit) ?></small>
                </div>

                <div class="form-group">
                    <label for="pwa_theme_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('link.settings.pwa_theme_color') ?></label>
                    <input type="hidden" id="pwa_theme_color" name="pwa_theme_color" class="form-control" value="<?= $data->link->settings->pwa_theme_color ?? '#000000' ?>" required="required" data-color-picker />
                    <div id="settings_pwa_theme_color_pickr"></div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php endif ?>
