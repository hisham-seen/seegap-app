<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="reddit" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'reddit_location_url_' . $row->microsite_block_id ?>"><i class="fab fa-fw fa-reddit fa-sm text-muted mr-1"></i> <?= l('microsite_reddit.location_url') ?></label>
        <input id="<?= 'reddit_location_url_' . $row->microsite_block_id ?>" type="url" class="form-control" name="location_url" value="<?= $row->location_url ?>" required="required" />
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
