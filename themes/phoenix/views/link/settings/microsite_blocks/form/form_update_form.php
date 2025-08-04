<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />
    <input type="hidden" name="block_type" value="form" />

    <div class="notification-container"></div>

    <?php
    $block_id = $row->microsite_block_id;
    $settings = $row->settings;
    $form_type = 'update';
    include THEME_PATH . 'views/partials/microsite_block_components/form_block_form_panel.php';
    ?>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
