<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="link-faq" id="<?= 'accordion_' . $data->link->microsite_block_id ?>" >
        <?php foreach($data->link->settings->items as $key => $item): ?>
        <div class="card my-2 <?= 'link-btn-' . $data->link->settings->border_radius ?>" style="<?= 'border-width: ' . $data->link->settings->border_width . ';' . 'border-color: ' . $data->link->settings->border_color . ';' . 'border-style: ' . $data->link->settings->border_style . ';' . 'background: ' . $data->link->settings->background_color . ';' . 'box-shadow: ' . ($data->link->settings->border_shadow_offset_x ?? '0') . 'px ' . ($data->link->settings->border_shadow_offset_y ?? '0') . 'px ' . ($data->link->settings->border_shadow_blur ?? '0') . 'px ' . ($data->link->settings->border_shadow_spread ?? '0') . 'px ' . ($data->link->settings->border_shadow_color ?? '#00000010') ?>">
            <div class="py-2 <?= 'link-btn-' . $data->link->settings->border_radius ?> border-0" id="<?= 'accordion_' . $data->link->microsite_block_id . '_header_' . $key ?>" style="<?= 'background: ' . $data->link->settings->background_color . ';' ?>">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-decoration-none <?= 'link-btn-' . $data->link->settings->border_radius ?>" style="<?= 'color: ' . $data->link->settings->text_color . ';' . 'text-align: ' . $data->link->settings->text_alignment . ';' ?>" type="button" data-toggle="collapse" data-target="#<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>" aria-expanded="true" aria-controls="<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>">
                        <?= $item->title ?>
                    </button>
                </h2>
            </div>

            <div id="<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>" class="collapse" aria-labelledby="<?= 'accordion_' . $data->link->microsite_block_id . '_header_' . $key ?>" data-parent="#<?= 'accordion_' . $data->link->microsite_block_id ?>">
                <div class="card-body" style="<?= 'color: ' . $data->link->settings->text_color . ';' . 'text-align: ' . $data->link->settings->text_alignment . ';' ?>">
                    <?= nl2br($item->content) ?>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>
