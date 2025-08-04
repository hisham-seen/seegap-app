<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Color Picker Component for Microsite Blocks
 * Provides standardized color picker with Pickr integration
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $field_name - Field name for the color
 * @param string $label - Label for the field
 * @param string $icon - Icon class for the label (default: 'fas fa-paint-brush')
 * @param string $default_color - Default color value
 * @param string $current_color - Current color value
 * @param bool $include_opacity - Whether to include opacity controls (default: true)
 */

$block_id = $block_id ?? 'default';
$field_name = $field_name ?? 'color';
$label = $label ?? 'Color';
$icon = $icon ?? 'fas fa-paint-brush';
$default_color = $default_color ?? '#333333';
$current_color = $current_color ?? $default_color;
$include_opacity = $include_opacity ?? true;

$picker_id = $field_name . '_' . $block_id;
$picker_class = $field_name . '_pickr_' . $block_id;
?>

<div class="form-group">
    <label for="<?= $picker_id ?>"><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <input id="<?= $picker_id ?>" type="hidden" name="<?= $field_name ?>" class="form-control" value="<?= $current_color ?>" required="required" />
    <div class="<?= $picker_class ?>"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Pickr !== 'undefined') {
        let <?= $field_name ?>_pickr_<?= $block_id ?> = Pickr.create({
            el: '.<?= $picker_class ?>',
            theme: 'classic',
            default: '<?= $current_color ?>',
            components: {
                preview: true,
                <?= $include_opacity ? 'opacity: true,' : '' ?>
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    hsla: true,
                    hsva: true,
                    cmyk: true,
                    input: true,
                    clear: true,
                    save: true
                }
            }
        });
        
        <?= $field_name ?>_pickr_<?= $block_id ?>.on('save', (color, instance) => {
            document.getElementById('<?= $picker_id ?>').value = color.toHEXA().toString();
            <?= $field_name ?>_pickr_<?= $block_id ?>.hide();
        });
    }
});
</script>
