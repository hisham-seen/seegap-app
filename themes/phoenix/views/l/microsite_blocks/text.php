<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <h2 class="h4 text-break" style="color: <?= $data->link->settings->title_text_color ?>"><?= $data->link->settings->title ?></h2>
    <p class="text-break" style="color: <?= $data->link->settings->description_color ?>"><?= nl2br($data->link->settings->description) ?></p>
</div>

