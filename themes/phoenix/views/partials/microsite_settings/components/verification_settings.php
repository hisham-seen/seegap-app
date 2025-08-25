<?php defined('SEEGAP') || die() ?>

<!-- Verification Badge Settings Component -->
<div class="form-group mb-3">
    <label class="small mb-1"><i class="fas fa-fw fa-check-circle fa-sm text-muted mr-1"></i> <?= l('link.settings.verified_header') ?></label>
    <?php if(!$data->link->is_verified): ?>
        <div class="alert alert-info">
            <i class="fas fa-fw fa-info-circle mr-1"></i>
            <?php if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)): ?>
                <?= sprintf(l('link.settings.verified_help'), '<a href="' . url('contact') . '" class="font-weight-bold" target="_blank">', '</a>') ?>
            <?php else: ?>
                <?= sprintf(l('link.settings.verified_help'), '', '') ?>
            <?php endif ?>
        </div>
    <?php endif ?>

    <div <?= $data->link->is_verified ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="<?= $data->link->is_verified ? null : 'container-disabled' ?>">
            <div class="form-group">
                <label for="settings_verified_location"><i class="fas fa-fw fa-check-circle fa-sm text-muted mr-1"></i> <?= l('link.settings.verified_location') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-12 col-lg-4 p-2 h-100">
                        <label class="btn btn-light btn-block text-truncate mb-0 <?= $data->link->settings->verified_location == '' ? 'active"' : null?>">
                            <input type="radio" name="verified_location" value="" class="custom-control-input" <?= $data->link->settings->verified_location == 'false' ? 'checked="checked"' : null?> />
                            <?= l('global.none') ?>
                        </label>
                    </div>

                    <?php foreach(['top', 'bottom',] as $key): ?>
                        <div class="col-12 col-lg-4 p-2 h-100">
                            <label class="btn btn-light btn-block text-truncate mb-0 <?= $data->link->settings->verified_location == $key ? 'active"' : null?>">
                                <input type="radio" name="verified_location" value="<?= $key ?>" class="custom-control-input" <?= $data->link->settings->verified_location == $key ? 'checked="checked"' : null?> />
                                <?= l('link.settings.verified_location.' . $key) ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>
