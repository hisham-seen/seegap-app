<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <?php if(isset($data->link->settings->items) && !empty($data->link->settings->items)): ?>
        <div class="link-faq" id="<?= 'accordion_' . $data->link->microsite_block_id ?>" >
            <?php foreach($data->link->settings->items as $key => $item): ?>
            <div class="card my-2 <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="<?= 'border-width: ' . ($data->link->settings->border_width ?? '1px') . ';' . 'border-color: ' . ($data->link->settings->border_color ?? '#dee2e6') . ';' . 'border-style: ' . ($data->link->settings->border_style ?? 'solid') . ';' . 'background: ' . ($data->link->settings->background_color ?? '#ffffff') . ';' . 'box-shadow: ' . ($data->link->settings->border_shadow_offset_x ?? '0') . 'px ' . ($data->link->settings->border_shadow_offset_y ?? '0') . 'px ' . ($data->link->settings->border_shadow_blur ?? '0') . 'px ' . ($data->link->settings->border_shadow_spread ?? '0') . 'px ' . ($data->link->settings->border_shadow_color ?? '#00000010') ?>">
                <div class="py-2 <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?> border-0" id="<?= 'accordion_' . $data->link->microsite_block_id . '_header_' . $key ?>" style="<?= 'background: ' . ($data->link->settings->background_color ?? '#ffffff') . ';' ?>">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-decoration-none <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="<?= 'color: ' . ($data->link->settings->text_color ?? '#333333') . ';' . 'text-align: ' . ($data->link->settings->text_alignment ?? 'left') . ';' ?>" type="button" data-toggle="collapse" data-target="#<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>" aria-expanded="true" aria-controls="<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>">
                            <?= $item->title ?? 'FAQ Item' ?>
                        </button>
                    </h2>
                </div>

                <div id="<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>" class="collapse" aria-labelledby="<?= 'accordion_' . $data->link->microsite_block_id . '_header_' . $key ?>" data-parent="#<?= 'accordion_' . $data->link->microsite_block_id ?>">
                    <div class="card-body" style="<?= 'color: ' . ($data->link->settings->text_color ?? '#333333') . ';' . 'text-align: ' . ($data->link->settings->text_alignment ?? 'left') . ';' ?>">
                        <?= nl2br($item->content ?? '') ?>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <!-- Minimalistic block-style empty state -->
        <div class="text-center py-4 px-3" style="border: 1px solid #e9ecef; border-radius: 4px; background-color: #f8f9fa;">
            <small class="text-muted">No FAQ items configured</small>
        </div>
    <?php endif ?>
</div>
