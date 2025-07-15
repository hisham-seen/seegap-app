<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    
    <?php if($data->link->settings->text_type == 'heading'): ?>
        <!-- Heading Type -->
        <<?= $data->link->settings->heading_type ?> class="<?= $data->link->settings->heading_type ?> m-0 text-break" style="color: <?= $data->link->settings->text_color ?>; text-align: <?= ($data->link->settings->text_alignment ?? 'center') ?>;" data-text data-text-color data-text-alignment>
        <?php if($data->microsite->is_verified && ($data->link->settings->verified_location ?? '') == 'left'): ?>
            <small class="link-verified-small" data-toggle="tooltip" title="<?= sprintf(l('link.microsite.verified_help'), settings()->main->title) ?>">
                <span class="fa-stack">
                    <i class="fa-solid fa-certificate fa-stack-2x"></i>
                    <i class="fas fa-check fa-stack-1x fa-inverse"></i>
                </span>
            </small>
        <?php endif ?>
            <?= $data->link->settings->text ?>
        <?php if($data->microsite->is_verified && ($data->link->settings->verified_location ?? '') == 'right'): ?>
            <small class="link-verified-small" data-toggle="tooltip" title="<?= sprintf(l('link.microsite.verified_help'), settings()->main->title) ?>">
                <span class="fa-stack">
                    <i class="fa-solid fa-certificate fa-stack-2x"></i>
                    <i class="fas fa-check fa-stack-1x fa-inverse"></i>
                </span>
            </small>
        <?php endif ?>
        </<?= $data->link->settings->heading_type ?>>
        
    <?php elseif($data->link->settings->text_type == 'paragraph'): ?>
        <!-- Paragraph Type -->
        <div class="card <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="<?= 'border-width: ' . ($data->link->settings->border_width ?? '1') . 'px;' . 'border-color: ' . ($data->link->settings->border_color ?? 'transparent') . ';' . 'border-style: ' . ($data->link->settings->border_style ?? 'solid') . ';' . 'background: ' . ($data->link->settings->background_color ?? 'transparent') . ';' . 'box-shadow: ' . ($data->link->settings->border_shadow_offset_x ?? '0') . 'px ' . ($data->link->settings->border_shadow_offset_y ?? '0') . 'px ' . ($data->link->settings->border_shadow_blur ?? '0') . 'px ' . ($data->link->settings->border_shadow_spread ?? '0') . 'px ' . ($data->link->settings->border_shadow_color ?? '#00000010') ?>" data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-background-color>
            <div class="<?= ($data->link->settings->border_width ?? 0) == 0 && in_array($data->link->settings->background_color ?? 'transparent', ['#00000000', '#FFFFFF00']) && in_array($data->link->settings->border_shadow_color ?? '#00000010', ['#00000000', '#FFFFFF00']) ? null : 'card-body' ?> text-break" style="color: <?= $data->link->settings->text_color ?? '#000000' ?>; text-align: <?= ($data->link->settings->text_alignment ?? 'center') ?>;" data-text data-text-color data-text-alignment>
                <?= nl2br($data->link->settings->text) ?>
            </div>
        </div>
        
    <?php elseif($data->link->settings->text_type == 'list'): ?>
        <!-- List Type -->
        <div class="card <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="<?= 'border-width: ' . ($data->link->settings->border_width ?? '0') . 'px;' . 'border-color: ' . ($data->link->settings->border_color ?? '#000000') . ';' . 'border-style: ' . ($data->link->settings->border_style ?? 'solid') . ';' . 'background: ' . ($data->link->settings->background_color ?? '#000000') . ';' . 'box-shadow: ' . ($data->link->settings->border_shadow_offset_x ?? '0') . 'px ' . ($data->link->settings->border_shadow_offset_y ?? '0') . 'px ' . ($data->link->settings->border_shadow_blur ?? '0') . 'px ' . ($data->link->settings->border_shadow_spread ?? '0') . 'px ' . ($data->link->settings->border_shadow_color ?? '#00000010') . ';' ?>">
            <div class="card-body" style="<?= 'color: ' . ($data->link->settings->text_color ?? '#ffffff') . ';' . 'text-align: ' . ($data->link->settings->text_alignment ?? 'left') . ';' ?>">
                <?php if(!empty($data->link->settings->list_items) && is_array($data->link->settings->list_items)): ?>
                    <?php if(($data->link->settings->list_type ?? 'unordered') == 'ordered'): ?>
                        <ol class="mb-0" style="<?= 'margin-top: ' . ($data->link->settings->margin_items_y ?? 2) . 'rem;' . 'margin-bottom: ' . ($data->link->settings->margin_items_y ?? 2) . 'rem;' ?>">
                            <?php foreach($data->link->settings->list_items as $item): ?>
                                <?php if(!empty(trim($item))): ?>
                                    <li class="<?= 'my-' . ($data->link->settings->margin_items_y ?? 2) ?>" style="<?= 'margin-left: ' . ($data->link->settings->margin_items_x ?? 1) . 'rem;' ?>">
                                        <?= htmlspecialchars($item) ?>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ol>
                    <?php else: ?>
                        <ul class="mb-0" style="<?= 'margin-top: ' . ($data->link->settings->margin_items_y ?? 2) . 'rem;' . 'margin-bottom: ' . ($data->link->settings->margin_items_y ?? 2) . 'rem;' ?>">
                            <?php foreach($data->link->settings->list_items as $item): ?>
                                <?php if(!empty(trim($item))): ?>
                                    <li class="<?= 'my-' . ($data->link->settings->margin_items_y ?? 2) ?>" style="<?= 'margin-left: ' . ($data->link->settings->margin_items_x ?? 1) . 'rem;' ?>">
                                        <?= htmlspecialchars($item) ?>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                <?php else: ?>
                    <p class="text-muted mb-0"><?= l('microsite_text.no_items') ?></p>
                <?php endif ?>
            </div>
        </div>
        
    <?php endif ?>
    
</div>
