<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <audio class="w-100" title="<?= $data->link->settings->name ?>" <?= $data->link->settings->audio_controls ? 'controls' : null ?> <?= $data->link->settings->audio_autoplay ? 'autoplay' : null ?> <?= $data->link->settings->audio_loop ? 'loop' : null ?> <?= $data->link->settings->audio_muted ? 'muted' : null ?>>
        <source src="<?= \SeeGap\Uploads::get_full_url('files') . $data->link->settings->file ?>">
    </audio>
</div>
