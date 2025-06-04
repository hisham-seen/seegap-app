<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="embed-responsive embed-responsive-16by9 link-iframe-round">
        <iframe
                class="embed-responsive-item"
                scrolling="no"
                frameborder="no"
                allowfullscreen="true"
                src="https://player.kick.com/<?= $data->embed ?>"
        ></iframe>
    </div>
</div>

