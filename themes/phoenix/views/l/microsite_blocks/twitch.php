<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="embed-responsive embed-responsive-16by9 link-iframe-round">
        <?php if($data->embed_type == 'clip'): ?>
            <iframe
                    class="embed-responsive-item"
                    scrolling="no"
                    frameborder="no"
                    src="https://clips.twitch.tv/embed?clip=<?= $data->embed ?>&parent=<?= query_clean($_SERVER['HTTP_HOST']) ?>"
            ></iframe>
        <?php else: ?>
            <iframe
                    class="embed-responsive-item"
                    scrolling="no"
                    frameborder="no"
                    src="https://player.twitch.tv/?<?= $data->embed_type ?>=<?= $data->embed ?>&autoplay=false&parent=<?= query_clean($_SERVER['HTTP_HOST']) ?>"
            ></iframe>
        <?php endif; ?>
    </div>
</div>
