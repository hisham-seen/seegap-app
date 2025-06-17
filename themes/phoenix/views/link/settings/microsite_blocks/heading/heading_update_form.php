<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="heading" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'heading_heading_type_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('global.type') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $heading_type): ?>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->heading_type  ?? null) == $heading_type ? 'active"' : null?>">
                        <input type="radio" name="heading_type" value="<?= $heading_type ?>" class="custom-control-input" <?= ($row->settings->heading_type  ?? null) == $heading_type ? 'checked="checked"' : null ?> />
                        <?= strtoupper($heading_type) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="form-group">
        <label for="<?= 'heading_text_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.text') ?></label>
        <input id="<?= 'heading_text_' . $row->microsite_block_id ?>" type="text" class="form-control" name="text" value="<?= $row->settings->text ?>" maxlength="256" />
    </div>

    <div class="form-group">
        <label><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_color') ?></label>
        <input type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
        <div class="text_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="<?= 'block_text_alignment_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                <div class="col-6">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'active"' : null?>">
                        <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'checked="checked"' : null ?> />
                        <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#verified_container" aria-expanded="false" aria-controls="verified_container">
        <i class="fas fa-fw fa-check-circle fa-sm mr-1"></i> <?= l('link.settings.verified_header') ?>
    </button>

    <div class="collapse" id="verified_container">
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
                    <label for="<?= 'link_verified_location_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-check-circle fa-sm text-muted mr-1"></i> <?= l('link.settings.verified_location') ?></label>
                    <div class="row btn-group-toggle" data-toggle="buttons">
                        <div class="col-12 col-lg-4 p-2 h-100">
                            <label class="btn btn-light btn-block text-truncate <?= ($row->settings->verified_location ?? '') == '' ? 'active"' : null?>">
                                <input type="radio" name="verified_location" value="" class="custom-control-input" <?= ($row->settings->verified_location ?? '') == '' ? 'checked="checked"' : null?> />
                                <?= l('global.none') ?>
                            </label>
                        </div>

                        <?php foreach(['left', 'right',] as $key): ?>
                            <div class="col-12 col-lg-4 p-2 h-100">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->verified_location ?? '') == $key ? 'active"' : null?>">
                                    <input type="radio" name="verified_location" value="<?= $key ?>" class="custom-control-input" <?= ($row->settings->verified_location ?? '') == $key ? 'checked="checked"' : null?> />
                                    <?= l('link.settings.verified_location.' . $key) ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
