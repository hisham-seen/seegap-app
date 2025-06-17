<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="twitter_profile" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'twitter_profile_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_twitter_profile.location_url') ?></label>
        <input id="<?= 'twitter_profile_location_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="location_url" value="<?= $row->location_url ?>" maxlength="2048" placeholder="<?= l('microsite_twitter_profile.location_url_placeholder') ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="<?= 'twitter_profile_theme_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-sun fa-sm text-muted mr-1"></i> <?= l('microsite_twitter_profile.theme') ?></label>
        <select id="<?= 'twitter_profile_theme_' . $row->microsite_block_id ?>" name="theme" class="custom-select">
            <option value="light" <?= $row->settings->theme == 'light' ? 'selected="selected"' : null ?>><?= l('global.theme_style_light') ?></option>
            <option value="dark" <?= $row->settings->theme == 'dark' ? 'selected="selected"' : null ?>><?= l('global.theme_style_dark') ?></option>
        </select>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
