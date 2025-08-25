<?php defined('SEEGAP') || die() ?>

<!-- URL Settings Component -->
<div class="form-group mb-2">
    <label for="url" class="small mb-1"><i class="fas fa-fw fa-bolt fa-sm text-muted mr-1"></i> <?= l('link.settings.url') ?></label>
    <div class="input-group input-group-sm">
        <div class="input-group-prepend">
            <?php if(count($data->domains)): ?>
                <select name="domain_id" class="appearance-none custom-select form-control input-group-text">
                    <?php if(settings()->links->main_domain_is_enabled || \SeeGap\Authentication::is_admin()): ?>
                        <option value="" <?= $data->link->domain ? 'selected="selected"' : null ?> data-full-url="<?= SITE_URL ?>"><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                    <?php endif ?>

                    <?php foreach($data->domains as $row): ?>
                        <option value="<?= $row->domain_id ?>" <?= $data->link->domain && $row->domain_id == $data->link->domain->domain_id ? 'selected="selected"' : null ?>  data-full-url="<?= $row->url ?>" data-type="<?= $row->type ?>"><?= remove_url_protocol_from_url($row->url) ?></option>
                    <?php endforeach ?>
                </select>
            <?php else: ?>
                <span class="input-group-text"><?= remove_url_protocol_from_url(SITE_URL) ?></span>
            <?php endif ?>
        </div>
        <input
                id="url"
                type="text"
                class="form-control form-control-sm"
                name="url"
                placeholder="<?= l('global.url_slug_placeholder') ?>"
                value="<?= $data->link->url ?>"
                maxlength="256"
            <?= !$this->user->plan_settings->custom_url ? 'readonly="readonly"' : null ?>
            <?= $this->user->plan_settings->custom_url ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>
        />
    </div>
    <small class="form-text text-muted"><?= l('link.settings.url_help') ?></small>
</div>
