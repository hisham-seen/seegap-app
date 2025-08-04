<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Shadow Settings Component for Microsite Blocks
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
?>
    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_shadow_offset_x_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_x') ?></label>
        <input id="<?= 'block_border_shadow_offset_x_' . $block_id ?>" type="range" min="-25" max="25" class="form-control-range" name="border_shadow_offset_x" value="<?= $settings->border_shadow_offset_x ?? 0 ?>" required="required" />
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_shadow_offset_y_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
        <input id="<?= 'block_border_shadow_offset_y_' . $block_id ?>" type="range" min="-25" max="25" class="form-control-range" name="border_shadow_offset_y" value="<?= $settings->border_shadow_offset_y ?? 0 ?>" required="required" />
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_shadow_blur_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
        <input id="<?= 'block_border_shadow_blur_' . $block_id ?>" type="range" min="0" max="30" class="form-control-range" name="border_shadow_blur" value="<?= $settings->border_shadow_blur ?? 0 ?>" required="required" />
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_shadow_spread_' . $block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
        <input id="<?= 'block_border_shadow_spread_' . $block_id ?>" type="range" min="-15" max="15" class="form-control-range" name="border_shadow_spread" value="<?= $settings->border_shadow_spread ?? 0 ?>" required="required" />
    </div>

    <?php
    $field_name = 'border_shadow_color';
    $label = l('microsite_link.border_shadow_color');
    $icon = 'fas fa-fill';
    $default_color = '#00000010';
    $current_color = $settings->border_shadow_color ?? $default_color;
    $include_opacity = true; // Shadow colors often need opacity
    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
    ?>
