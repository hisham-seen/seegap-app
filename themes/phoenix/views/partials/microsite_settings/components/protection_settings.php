<?php defined('SEEGAP') || die() ?>

<!-- Protection Settings Component -->
<div class="mb-4">    
    <div class="form-group mb-3">
        <label class="small mb-1"><i class="fas fa-fw fa-user-shield fa-sm text-muted mr-1"></i> <?= l('link.settings.protection_header') ?></label>

        <div <?= $this->user->plan_settings->password ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
            <div class="<?= $this->user->plan_settings->password ? null : 'container-disabled' ?>">
                <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                    <label for="qweasdzxc"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('global.password') ?></label>
                    <input id="qweasdzxc" type="password" class="form-control" name="qweasdzxc" value="<?= $data->link->settings->password ?>" autocomplete="new-password" <?= !$this->user->plan_settings->password ? 'disabled="disabled"': null ?> />
                    <small class="form-text text-muted"><?= l('link.settings.password_help') ?></small>
                </div>
            </div>
        </div>

        <div <?= $this->user->plan_settings->sensitive_content ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
            <div class="<?= $this->user->plan_settings->sensitive_content ? null : 'container-disabled' ?>">
                <div class="form-group custom-control custom-switch">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            id="sensitive_content"
                            name="sensitive_content"
                        <?= !$this->user->plan_settings->sensitive_content ? 'disabled="disabled"': null ?>
                        <?= $data->link->settings->sensitive_content ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="sensitive_content"><?= l('link.settings.sensitive_content') ?></label>
                    <small class="form-text text-muted"><?= l('link.settings.sensitive_content_help') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
