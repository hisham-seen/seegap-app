<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <<?= $data->link->settings->heading_type ?> class="<?= $data->link->settings->heading_type ?> m-0 text-break" style="color: <?= $data->link->settings->text_color ?>; text-align: <?= ($data->link->settings->text_alignment ?? 'center') ?>;" data-text data-text-color data-text-alignment>
    <?php if($data->microsite->is_verified && $data->link->settings->verified_location == 'left'): ?>
        <small class="link-verified-small" data-toggle="tooltip" title="<?= sprintf(l('link.microsite.verified_help'), settings()->main->title) ?>">
            <span class="fa-stack">
                <i class="fa-solid fa-certificate fa-stack-2x"></i>
                <i class="fas fa-check fa-stack-1x fa-inverse"></i>
            </span>
        </small>
    <?php endif ?>
        <?= $data->link->settings->text ?>
    <?php if($data->microsite->is_verified && $data->link->settings->verified_location == 'right'): ?>
        <small class="link-verified-small" data-toggle="tooltip" title="<?= sprintf(l('link.microsite.verified_help'), settings()->main->title) ?>">
            <span class="fa-stack">
                <i class="fa-solid fa-certificate fa-stack-2x"></i>
                <i class="fas fa-check fa-stack-1x fa-inverse"></i>
            </span>
        </small>
    <?php endif ?>
    </<?= $data->link->settings->heading_type ?>>
</div>

