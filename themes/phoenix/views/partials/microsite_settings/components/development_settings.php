<?php defined('SEEGAP') || die() ?>

<!-- Advanced Development Settings Component -->
<div class="mb-4">    
    <div <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="form-group <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'container-disabled' ?>" data-character-counter="textarea">
            <label for="custom_css" class="d-flex justify-content-between align-items-center">
                <span><i class="fab fa-fw fa-sm fa-css3 text-muted mr-1"></i> <?= l('global.custom_css') ?></span>
                <small class="text-muted" data-character-counter-wrapper></small>
            </label>
            <textarea id="custom_css" class="form-control" name="custom_css" maxlength="10000" placeholder="<?= l('global.custom_css_placeholder') ?>"><?= $data->link->settings->custom_css ?></textarea>
            <small class="form-text text-muted"><?= l('global.custom_css_help') ?></small>
        </div>
    </div>

    <div <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="form-group <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'container-disabled' ?>" data-character-counter="textarea">
            <label for="custom_js" class="d-flex justify-content-between align-items-center">
                <span><i class="fab fa-fw fa-sm fa-js-square text-muted mr-1"></i> <?= l('global.custom_js') ?></span>
                <small class="text-muted" data-character-counter-wrapper></small>
            </label>
            <textarea id="custom_js" class="form-control" name="custom_js" maxlength="10000" placeholder="<?= l('global.custom_js_placeholder') ?>"><?= $data->link->settings->custom_js ?></textarea>
            <small class="form-text text-muted"><?= l('global.custom_js_help') ?></small>
        </div>
    </div>
</div>
