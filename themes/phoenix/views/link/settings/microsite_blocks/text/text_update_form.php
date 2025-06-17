<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="text" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'text_title_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('microsite_text.title') ?></label>
        <input id="<?= 'text_title_' . $row->microsite_block_id ?>" type="text" class="form-control" name="title" value="<?= $row->settings->title ?>" />
    </div>

    <div class="form-group">
        <label for="<?= 'text_description_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('microsite_text.description') ?></label>
        <textarea id="<?= 'text_description_' . $row->microsite_block_id ?>" name="description" class="form-control"><?= $row->settings->description ?></textarea>
    </div>

    <div class="form-group">
        <label><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_text.title_text_color') ?></label>
        <input type="hidden" name="title_text_color" class="form-control" value="<?= $row->settings->title_text_color ?>" required="required" />
        <div class="title_text_color_pickr"></div>
    </div>

    <div class="form-group">
        <label><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_text.description_color') ?></label>
        <input type="hidden" name="description_color" class="form-control" value="<?= $row->settings->description_color ?>" required="required" />
        <div class="description_color_pickr"></div>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
