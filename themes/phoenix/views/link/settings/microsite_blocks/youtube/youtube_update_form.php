<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="youtube" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'youtube_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_youtube.location_url') ?></label>
        <input id="<?= 'youtube_location_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="location_url" value="<?= $row->location_url ?>" maxlength="2048" placeholder="<?= l('microsite_youtube.location_url_placeholder') ?>" required="required" />
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'youtube_autoplay_' . $row->microsite_block_id ?>"
                name="video_autoplay"
            <?= $row->settings->video_autoplay ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'youtube_autoplay_' . $row->microsite_block_id ?>"><?= l('microsite_video.video_autoplay') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'youtube_controls_' . $row->microsite_block_id ?>"
                name="video_controls"
            <?= $row->settings->video_controls ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'youtube_controls_' . $row->microsite_block_id ?>"><?= l('microsite_video.video_controls') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'youtube_loop_' . $row->microsite_block_id ?>"
                name="video_loop"
            <?= $row->settings->video_loop ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'youtube_loop_' . $row->microsite_block_id ?>"><?= l('microsite_video.video_loop') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'youtube_muted_' . $row->microsite_block_id ?>"
                name="video_muted"
            <?= $row->settings->video_muted ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'youtube_muted_' . $row->microsite_block_id ?>"><?= l('microsite_video.video_muted') ?></label>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
