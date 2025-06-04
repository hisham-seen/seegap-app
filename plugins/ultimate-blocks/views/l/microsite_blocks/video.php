<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <video class="w-100 link-round" title="<?= $data->link->settings->name ?>" poster="<?= $data->link->settings->poster_url ?? null ?>" <?= $data->link->settings->video_controls ? 'controls' : null ?> <?= $data->link->settings->video_autoplay ? 'autoplay' : null ?> <?= $data->link->settings->video_loop ? 'loop' : null ?> <?= $data->link->settings->video_muted ? 'muted' : null ?>>
        <source src="<?= \SeeGap\Uploads::get_full_url('files') . $data->link->settings->file ?>">
    </video>
</div>
