<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?> d-flex justify-content-center">
    <blockquote class="text-post-media" data-text-post-permalink="<?= $data->link->location_url ?>"></blockquote>

    <?php if(!\Altum\Event::exists_content_type_key('javascript', 'threads')): ?>
        <?php ob_start() ?>
        <script async src="https://www.threads.net/embed.js"></script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'threads') ?>
    <?php endif ?>
</div>
