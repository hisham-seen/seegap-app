<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="markdown" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'markdown_text_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('microsite_link.text') ?></label>
        <textarea id="<?= 'markdown_text_' . $row->microsite_block_id ?>" class="form-control" name="text" maxlength="2048"><?= $row->settings->text ?></textarea>
    </div>

    <div class="form-group">
        <label><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_color') ?></label>
        <input type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
        <div class="text_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="<?= 'markdown_background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
        <input id="<?= 'markdown_background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
        <div class="background_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="<?= 'block_text_alignment_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                <div class="col-6">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'active"' : null?>">
                        <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'checked="checked"' : null ?> />
                        <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_header') ?>
    </button>

    <div class="collapse" id="<?= 'border_container_' . $row->microsite_block_id ?>">
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_width_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_width') ?></label>
            <input id="<?= 'block_border_width_' . $row->microsite_block_id ?>" type="range" min="0" max="5" class="form-control-range" name="border_width" value="<?= $row->settings->border_width ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_color') ?></label>
            <input id="<?= 'block_border_color_' . $row->microsite_block_id ?>" type="hidden" name="border_color" class="form-control" value="<?= $row->settings->border_color ?>" required="required" />
            <div class="border_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_radius') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'active"' : null?>">
                        <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_radius_straight') ?>
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'round' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'round' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('microsite_link.border_radius_round') ?>
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('microsite_link.border_radius_rounded') ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_style_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_style') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_style  ?? null) == $border_style ? 'active"' : null?>">
                            <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($row->settings->border_style  ?? null) == $border_style ? 'checked="checked"' : null?> />
                            <?= l('microsite_link.border_style_' . $border_style) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_shadow_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_shadow_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-cloud fa-sm mr-1"></i> <?= l('microsite_link.border_shadow_header') ?>
    </button>

    <div class="collapse" id="<?= 'border_shadow_container_' . $row->microsite_block_id ?>">
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_offset_x_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_x') ?></label>
            <input id="<?= 'block_border_shadow_offset_x_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_x" value="<?= $row->settings->border_shadow_offset_x ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_offset_y_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
            <input id="<?= 'block_border_shadow_offset_y_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_y" value="<?= $row->settings->border_shadow_offset_y ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_blur_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
            <input id="<?= 'block_border_shadow_blur_' . $row->microsite_block_id ?>" type="range" min="0" max="20" class="form-control-range" name="border_shadow_blur" value="<?= $row->settings->border_shadow_blur ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_spread_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
            <input id="<?= 'block_border_shadow_spread_' . $row->microsite_block_id ?>" type="range" min="0" max="10" class="form-control-range" name="border_shadow_spread" value="<?= $row->settings->border_shadow_spread ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_shadow_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_color') ?></label>
            <input id="<?= 'block_border_shadow_color_' . $row->microsite_block_id ?>" type="hidden" name="border_shadow_color" class="form-control" value="<?= $row->settings->border_shadow_color ?>" required="required" />
            <div class="border_shadow_color_pickr"></div>
        </div>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
