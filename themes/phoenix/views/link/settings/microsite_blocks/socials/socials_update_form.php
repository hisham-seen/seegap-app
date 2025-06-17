<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="socials" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'socials_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_socials.color') ?></label>
        <input type="hidden" id="<?= 'socials_color_' . $row->microsite_block_id ?>" name="color" class="form-control" value="<?= $row->settings->color ?>" required="required" />
        <div class="color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="<?= 'socials_background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
        <input id="<?= 'socials_background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
        <div class="background_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="<?= 'block_border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_radius') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'active"' : null?>">
                    <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'checked="checked"' : null?> />
                    <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_radius_straight') ?>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'round' ? 'active' : null?>">
                    <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'round' ? 'checked="checked"' : null?> />
                    <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('microsite_link.border_radius_round') ?>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'active' : null?>">
                    <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'checked="checked"' : null?> />
                    <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('microsite_link.border_radius_rounded') ?>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="<?= 'socials_size_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand-alt fa-sm text-muted mr-1"></i> <?= l('microsite_socials.size') ?></label>
        <select id="<?= 'socials_size_' . $row->microsite_block_id ?>" name="size" class="custom-select">
            <option value="s" <?= $row->settings->size == 's' ? 'selected="selected"' : null ?>><?= l('microsite_socials.size.s') ?></option>
            <option value="m" <?= $row->settings->size == 'm' ? 'selected="selected"' : null ?>><?= l('microsite_socials.size.m') ?></option>
            <option value="l" <?= $row->settings->size == 'l' ? 'selected="selected"' : null ?>><?= l('microsite_socials.size.l') ?></option>
            <option value="xl" <?= $row->settings->size == 'xl' ? 'selected="selected"' : null ?>><?= l('microsite_socials.size.xl') ?></option>
        </select>
    </div>

    <?php $microsite_socials = require APP_PATH . 'includes/microsite_socials.php'; ?>
    <?php foreach($microsite_socials as $key => $value): ?>
        <?php if($value['input_group']): ?>
            <div class="form-group">
                <label for="<?= 'socials_' . $key . '_' . $row->microsite_block_id ?>"><i class="<?= $value['icon'] ?> fa-fw fa-sm text-muted mr-1"></i> <?= l('microsite_socials.' . $key . '.name') ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= remove_url_protocol_from_url(str_replace('%s', '', $value['format'])) ?></span>
                    </div>
                    <input id="<?= 'socials_' . $key . '_' . $row->microsite_block_id ?>" type="text" class="form-control" name="socials[<?= $key ?>]" placeholder="<?= l('microsite_socials.' . $key . '.placeholder') ?>" value="<?= $row->settings->socials->{$key} ?? '' ?>" maxlength="<?= $value['max_length'] ?>" />
                </div>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label for="<?= 'socials_' . $key . '_' . $row->microsite_block_id ?>"><i class="<?= $value['icon'] ?> fa-fw fa-sm text-muted mr-1"></i> <?= l('microsite_socials.' . $key . '.name') ?></label>
                <input id="<?= 'socials_' . $key . '_' . $row->microsite_block_id ?>" type="text" class="form-control" name="socials[<?= $key ?>]" placeholder="<?= l('microsite_socials.' . $key . '.placeholder') ?>" value="<?= $row->settings->socials->{$key} ?? '' ?>" maxlength="<?= $value['max_length'] ?>" />
            </div>
        <?php endif ?>
    <?php endforeach ?>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
