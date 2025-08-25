<?php defined('SEEGAP') || die() ?>

<!-- Branding Settings Component -->
<div class="form-group mb-3">
    <label class="small mb-1"><i class="fas fa-fw fa-random fa-sm text-muted mr-1"></i> <?= l('link.settings.branding_header') ?></label>
    <div <?= $this->user->plan_settings->removable_branding ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="<?= $this->user->plan_settings->removable_branding ? null : 'container-disabled' ?>">
            <div class="form-group custom-control custom-switch">
                <input
                        type="checkbox"
                        class="custom-control-input"
                        id="display_branding"
                        name="display_branding"
                    <?= !$this->user->plan_settings->removable_branding ? 'disabled="disabled"': null ?>
                    <?= $data->link->settings->display_branding ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="display_branding"><?= l('link.settings.display_branding') ?></label>
            </div>
        </div>
    </div>

    <div <?= $this->user->plan_settings->custom_branding ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="<?= $this->user->plan_settings->custom_branding ? null : 'container-disabled' ?>">
            <div class="form-group">
                <label for="branding_name"><i class="fas fa-fw fa-random fa-sm text-muted mr-1"></i> <?= l('link.settings.branding.name') ?></label>
                <input id="branding_name" type="text" class="form-control" name="branding_name" value="<?= $data->link->settings->branding->name ?? '' ?>" maxlength="128" />
                <small class="form-text text-muted"><?= l('link.settings.branding.name_help') ?></small>
            </div>

            <div id="branding_url_text_color" class="<?= $data->link->settings->branding->name ? null : 'container-disabled' ?>">
                <div class="form-group">
                    <label for="branding_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('link.settings.branding.url') ?></label>
                    <input id="branding_url" type="text" class="form-control" name="branding_url" value="<?= $data->link->settings->branding->url ?? '' ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                </div>

                <div class="form-group">
                    <label for="settings_text_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('link.settings.text_color') ?></label>
                    <input type="hidden" id="settings_text_color" name="text_color" class="form-control" value="<?= $data->link->settings->text_color ?>" required="required" />
                    <div id="settings_text_color_pickr"></div>
                </div>
            </div>
        </div>
    </div>
</div>
