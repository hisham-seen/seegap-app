<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Border Settings Component for Microsite Blocks
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
?>
    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_width_' . $block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_width') ?></label>
        <input id="<?= 'block_border_width_' . $block_id ?>" type="range" min="0" max="20" step="1" class="form-control-range" name="border_width" value="<?= $settings->border_width ?? '0' ?>" required="required" />
    </div>

    <?php
    $field_name = 'border_color';
    $label = l('microsite_link.border_color');
    $icon = 'fas fa-fill';
    $default_color = '#ffffff';
    $current_color = $settings->border_color ?? $default_color;
    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
    ?>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_radius_' . $block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_radius') ?></label>
        <input id="<?= 'block_border_radius_' . $block_id ?>" type="range" min="0" max="50" step="1" class="form-control-range" name="border_radius" value="<?= is_numeric($settings->border_radius ?? 0) ? ($settings->border_radius ?? 0) : 0 ?>" required="required" />
        <small class="form-text text-muted">Set border radius in pixels. 0 = straight corners, higher values = more rounded corners.</small>
    </div>

    <div class="form-group">
        <label for="<?= 'block_border_style_' . $block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_style') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($settings->border_style ?? 'solid') == $border_style ? 'active' : '' ?>">
                        <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($settings->border_style ?? 'solid') == $border_style ? 'checked="checked"' : '' ?> />
                        <?= l('microsite_link.border_style_' . $border_style) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>
