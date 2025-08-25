<?php defined('SEEGAP') || die() ?>

<!-- Typography Settings Component -->
<div class="form-group mb-3" <?= $this->user->plan_settings->fonts ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
    <div class="<?= $this->user->plan_settings->fonts ? null : 'container-disabled' ?>">
        <?php $microsite_fonts = require APP_PATH . 'includes/microsite_fonts.php'; ?>
        <?php foreach($microsite_fonts as $font_key => $font): ?>
            <?php if($font['font_css_url']): ?>
                <?php ob_start() ?>
                <link href="<?= $font['font_css_url'] ?>" rel="stylesheet">
                <?php \SeeGap\Event::add_content(ob_get_clean(), 'head') ?>
            <?php endif ?>
        <?php endforeach ?>

        <label for="settings_font" class="small mb-1"><i class="fas fa-fw fa-pen-nib fa-sm text-muted mr-1"></i> <?= l('link.settings.font') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach($microsite_fonts as $font_key => $font): ?>
                <div class="col-6 col-lg-4 p-2 h-100">
                    <label class="btn btn-light btn-block text-truncate mb-0 <?= ($data->link->settings->font ?? 'default') == $font_key ? 'active"' : null?>" style="font-family: <?= $font['font-family'] ?> !important;">
                        <input type="radio" name="font" value="<?= $font_key ?>" class="custom-control-input" <?= ($data->link->settings->font ?? 'default') == $font_key ? 'checked="checked"' : null?> required="required" data-font-family="<?= $font['font-family'] ?>" data-font-css-url="<?= $font['font_css_url'] ?>" />
                        <?= $font['name'] ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<div class="form-group mb-2" <?= $this->user->plan_settings->fonts ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
    <div class="<?= $this->user->plan_settings->fonts ? null : 'container-disabled' ?>">
        <label for="settings_font_size" class="small mb-1"><i class="fas fa-fw fa-font fa-sm text-muted mr-1"></i> <?= l('link.settings.font_size') ?></label>
        <div class="input-group">
            <input id="settings_font_size" type="number" min="14" max="22" name="font_size" class="form-control" value="<?= $data->link->settings->font_size ?>" />
            <div class="input-group-append">
                <span class="input-group-text">px</span>
            </div>
        </div>
    </div>
</div>
