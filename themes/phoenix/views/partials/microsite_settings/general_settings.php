<?php defined('SEEGAP') || die() ?>

<!-- General Settings Component -->
<div class="card-body mb-4">
    <!-- URL Settings -->
    <div class="form-group mb-3">
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

    <!-- Domain Settings -->
    <?php if(count($data->domains)): ?>
        <div class="form-group custom-control custom-switch mb-2">
            <input id="is_main_link" name="is_main_link" type="checkbox" class="custom-control-input" <?= $data->link->domain_id && $data->domains[$data->link->domain_id]->link_id == $data->link->link_id ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="is_main_link"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('link.settings.is_main_link') ?></label>
            <small class="form-text text-muted"><?= l('link.settings.is_main_link_help') ?></small>
        </div>
    <?php endif ?>
</div>
