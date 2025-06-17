<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <iframe src="<?= $data->link->location_url ?>" style="width: 100%; height: 500px; object-fit: contain; border:0;" class="w-100 rounded" loading="lazy"></iframe>
</div>
