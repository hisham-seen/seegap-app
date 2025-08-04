<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Text Styling Component for Microsite Blocks
 * Wraps text styling options in an accordion format
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param bool $collapsed - Whether the section should be collapsed by default (default: true)
 * @param bool $include_alignment - Whether to include text alignment options (default: true)
 * @param string $text_color_field - Field name for text color (default: 'text_color')
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$collapsed = $collapsed ?? true;
$include_alignment = $include_alignment ?? true;
$text_color_field = $text_color_field ?? 'text_color';
?>

<button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'text_styling_container_' . $block_id ?>" aria-expanded="<?= $collapsed ? 'false' : 'true' ?>" aria-controls="<?= 'text_styling_container_' . $block_id ?>">
    <i class="fas fa-fw fa-font fa-sm mr-1"></i> <?= l('microsite_link.text_styling_header') ?>
</button>

<div class="<?= $collapsed ? 'collapse' : '' ?>" id="<?= 'text_styling_container_' . $block_id ?>">
    <!-- Text Color -->
    <?php
    $field_name = $text_color_field;
    $label = l('microsite_link.text_color');
    $icon = 'fas fa-paint-brush';
    $default_color = '#ffffff';
    $current_color = $settings->$text_color_field ?? $default_color;
    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
    ?>

    <?php if($include_alignment): ?>
    <!-- Text Alignment -->
    <div class="form-group">
        <label for="<?= 'block_text_alignment_' . $block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                <div class="col-6">
                    <label class="btn btn-light btn-block text-truncate <?= ($settings->text_alignment ?? 'center') == $text_alignment ? 'active' : '' ?>">
                        <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($settings->text_alignment ?? 'center') == $text_alignment ? 'checked="checked"' : '' ?> />
                        <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <?php endif ?>
</div>
