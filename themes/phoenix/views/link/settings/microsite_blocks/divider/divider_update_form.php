<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="divider" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group" data-range-counter>
        <label for="<?= 'divider_margin_top' . $row->microsite_block_id ?>"><?= l('microsite_divider.margin_top') ?></label>
        <input id="<?= 'divider_margin_top' . $row->microsite_block_id ?>" type="range" name="margin_top" min="0" max="7" step="1" value="<?= $row->settings->margin_top ?>" class="form-control-range" />
    </div>

    <div class="form-group" data-range-counter>
        <label for="<?= 'divider_margin_bottom' . $row->microsite_block_id ?>"><?= l('microsite_divider.margin_bottom') ?></label>
        <input id="<?= 'divider_margin_bottom' . $row->microsite_block_id ?>" type="range" name="margin_bottom" min="0" max="7" step="1" value="<?= $row->settings->margin_bottom ?>" class="form-control-range" />
    </div>

    <div class="form-group">
        <label for="<?= 'divider_background_color' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
        <input id="<?= 'divider_background_color' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
        <div class="background_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="<?= 'divider_icon' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
        <input id="<?= 'divider_icon' . $row->microsite_block_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" />
        <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
