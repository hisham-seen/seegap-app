<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="discord" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'discord_server_id_' . $row->microsite_block_id ?>"><i class="fab fa-fw fa-discord fa-sm text-muted mr-1"></i> <?= l('microsite_discord.server_id') ?></label>
        <input id="<?= 'discord_server_id_' . $row->microsite_block_id ?>" type="text" class="form-control" name="server_id" value="<?= $row->settings->server_id ?>" required="required" />
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
