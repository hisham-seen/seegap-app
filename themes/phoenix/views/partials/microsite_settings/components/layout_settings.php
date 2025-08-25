<?php defined('SEEGAP') || die() ?>

<!-- Layout Settings Component -->
<div class="form-group mb-2">
    <label for="settings_width" class="small mb-1"><i class="fas fa-fw fa-arrows-left-right fa-sm text-muted mr-1"></i> <?= l('link.settings.width') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <?php foreach(['6', '8', '10', '12'] as $key): ?>
            <div class="col-12 col-lg-4 p-2 h-100">
                <label class="btn btn-light btn-block text-truncate mb-0 <?= ($data->link->settings->width ?? '8') == $key ? 'active"' : null?>">
                    <input type="radio" name="width" value="<?= $key ?>" class="custom-control-input" <?= ($data->link->settings->width ?? '8') == $key ? 'checked="checked"' : null?> required="required" />
                    <?= l('link.settings.width.' . $key) ?>
                </label>
            </div>
        <?php endforeach ?>
    </div>
    <small class="form-text text-muted"><?= l('link.settings.width_help') ?></small>
</div>

<div class="form-group mb-2">
    <label for="settings_block_spacing" class="small mb-1"><i class="fas fa-fw fa-arrows-up-down fa-sm text-muted mr-1"></i> <?= l('link.settings.block_spacing') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <?php foreach(['1', '2', '3',] as $key): ?>
            <div class="col-12 col-lg-4 p-2 h-100">
                <label class="btn btn-light btn-block text-truncate mb-0 <?= ($data->link->settings->block_spacing ?? '2') == $key ? 'active"' : null?>">
                    <input type="radio" name="block_spacing" value="<?= $key ?>" class="custom-control-input" <?= ($data->link->settings->block_spacing ?? '2') == $key ? 'checked="checked"' : null?> required="required" />
                    <?= l('link.settings.block_spacing.' . $key) ?>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>

<div class="form-group mb-2">
    <label for="settings_hover_animation" class="small mb-1"><i class="fas fa-fw fa-arrow-pointer fa-sm text-muted mr-1"></i> <?= l('link.settings.hover_animation') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-12 col-lg-4 p-2 h-100">
            <label class="btn btn-light btn-block text-truncate mb-0 <?= ($data->link->settings->hover_animation ?? 'smooth') == 'false' ? 'active"' : null?>">
                <input type="radio" name="hover_animation" value="false" class="custom-control-input" <?= ($data->link->settings->hover_animation ?? 'smooth') == 'false' ? 'checked="checked"' : null?> required="required" />
                <?= l('global.none') ?>
            </label>
        </div>

        <?php foreach(['smooth', 'instant',] as $key): ?>
            <div class="col-12 col-lg-4 p-2 h-100">
                <label class="btn btn-light btn-block text-truncate mb-0 <?= ($data->link->settings->hover_animation ?? 'smooth') == $key ? 'active"' : null?>">
                    <input type="radio" name="hover_animation" value="<?= $key ?>" class="custom-control-input" <?= ($data->link->settings->hover_animation ?? 'smooth') == $key ? 'checked="checked"' : null?> required="required" />
                    <?= l('link.settings.hover_animation.' . $key) ?>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>
