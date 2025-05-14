<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" data-biolink-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <div class="embed-responsive embed-responsive-16by9 link-iframe-round">
        <iframe class="embed-responsive-item" scrolling="no" frameborder="no" src="https://vk.com/video_ext.php?oid=-<?= $data->embed_oid ?>&id=<?= $data->embed_id ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen="allowfullscreen"></iframe>
    </div>
</div>

