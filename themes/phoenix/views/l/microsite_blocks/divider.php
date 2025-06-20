<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 mt-<?= $data->link->settings->margin_top ?> mb-<?= $data->link->settings->margin_bottom ?>">
    <div class="d-flex justify-content-center align-items-center">
        <hr class="w-100" style="border-color: <?= $data->link->settings->background_color ?>;" />

        <span class="mx-4">
            <i class="<?= $data->link->settings->icon ?> fa-fw" style="color: <?= $data->link->settings->background_color ?>;"></i>
        </span>

        <hr class="w-100" style="border-color: <?= $data->link->settings->background_color ?>;" />
    </div>
</div>
