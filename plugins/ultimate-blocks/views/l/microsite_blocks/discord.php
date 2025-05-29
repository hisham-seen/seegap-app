<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="link-iframe-round">
        <iframe class="embed-responsive-item" scrolling="no" frameborder="no" style="height: 300px;width:100%;overflow:hidden;background:transparent;" src="<?= 'https://discord.com/widget?id=' . $data->link->settings->server_id . '&theme=dark' ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen="allowfullscreen"></iframe>
    </div>
</div>
