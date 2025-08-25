<?php defined('SEEGAP') || die() ?>

<!-- UTM Parameters Settings Component -->
<div class="form-group mb-3">
    <label class="small mb-1"><i class="fas fa-fw fa-keyboard fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_header') ?></label>
    <div <?= $this->user->plan_settings->utm ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="<?= $this->user->plan_settings->utm ? null : 'container-disabled' ?>">
            <div class="form-group mb-2">
                <label for="utm_source" class="small mb-1"><i class="fas fa-fw fa-sitemap fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_source') ?></label>
                <input id="utm_source" type="text" class="form-control" name="utm_source" value="<?= $data->link->settings->utm->source ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_source_placeholder') ?>" />
            </div>

            <div class="form-group mb-2">
                <label for="utm_medium" class="small mb-1"><i class="fas fa-fw fa-inbox fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_medium') ?></label>
                <input id="utm_medium" type="text" class="form-control" name="utm_medium" value="<?= $data->link->settings->utm->medium ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_medium_placeholder') ?>" />
            </div>

            <div class="form-group mb-2">
                <label for="utm_campaign" class="small mb-1"><i class="fas fa-fw fa-bullhorn fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_campaign') ?></label>
                <input id="utm_campaign" type="text" class="form-control" name="utm_campaign" value="<?= l('link.settings.utm_campaign_placeholder_automatic') ?>" maxlength="128" readonly="readonly" />
            </div>

            <div class="form-group">
                <label for="utm_preview"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_preview') ?></label>
                <input id="utm_preview" type="text" class="form-control-plaintext" name="utm_preview" readonly="readonly" />
                <small class="form-text text-muted"><?= l('link.settings.utm_preview_help') ?></small>
            </div>
        </div>
    </div>
</div>
