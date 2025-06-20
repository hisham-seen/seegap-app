<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="audio" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'audio_file_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-file fa-sm text-muted mr-1"></i> <?= l('microsite_file.file') ?></label>
        <div class="row">
            <div class="col">
                <input id="<?= 'audio_file_' . $row->microsite_block_id ?>" type="file" name="file" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['audio']['whitelisted_file_extensions']) ?>" class="form-control-file seegap-file-input" />
            </div>

            <div class="col-3 <?= !empty($row->settings->file) ? null : 'd-none' ?>">
                <a href="<?= $row->settings->file ? \SeeGap\Uploads::get_full_url('files') . $row->settings->file : '#' ?>" target="_blank" data-toggle="tooltip" title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                    <div class="card h-100 d-flex justify-content-center align-items-center bg-gray-100">
                        <div class="card-body">
                            <i class="fas fa-fw fa-external-link"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['audio']['whitelisted_file_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->audio_size_limit) ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'audio_name_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
        <input id="<?= 'audio_name_' . $row->microsite_block_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?>" maxlength="128" required="required" />
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'audio_autoplay_' . $row->microsite_block_id ?>"
                name="audio_autoplay"
            <?= $row->settings->audio_autoplay ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'audio_autoplay_' . $row->microsite_block_id ?>"><?= l('microsite_audio.audio_autoplay') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'audio_controls_' . $row->microsite_block_id ?>"
                name="audio_controls"
            <?= $row->settings->audio_controls ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'audio_controls_' . $row->microsite_block_id ?>"><?= l('microsite_audio.audio_controls') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'audio_loop_' . $row->microsite_block_id ?>"
                name="audio_loop"
            <?= $row->settings->audio_loop ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'audio_loop_' . $row->microsite_block_id ?>"><?= l('microsite_audio.audio_loop') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="<?= 'audio_muted_' . $row->microsite_block_id ?>"
                name="audio_muted"
            <?= $row->settings->audio_muted ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'audio_muted_' . $row->microsite_block_id ?>"><?= l('microsite_audio.audio_muted') ?></label>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
