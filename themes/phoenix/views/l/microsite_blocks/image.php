<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <?php if($data->link->location_url): ?>
    <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="<?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
        <img src="<?= \Altum\Uploads::get_full_url('block_images') . $data->link->settings->image ?>" class="w-100 h-auto rounded <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" alt="<?= $data->link->settings->image_alt ?>" loading="lazy" />
    </a>
    <?php else: ?>
    <img src="<?= \Altum\Uploads::get_full_url('block_images') . $data->link->settings->image ?>" class="w-100 h-auto rounded" alt="<?= $data->link->settings->image_alt ?>" loading="lazy" />
    <?php endif ?>
</div>

