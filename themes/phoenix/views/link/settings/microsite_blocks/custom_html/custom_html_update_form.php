<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="custom_html" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'custom_html_html_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-code fa-sm text-muted mr-1"></i> <?= l('microsite_custom_html.html') ?></label>
        <textarea id="<?= 'custom_html_html_' . $row->microsite_block_id ?>" name="html" class="form-control" maxlength="<?= $data->microsite_blocks['custom_html']['max_length'] ?>"><?= $row->settings->html ?></textarea>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
