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
            const input = document.getElementById('<?= $picker_id ?>');
            input.value = color.toHEXA().toString();
            // Dispatch input event to trigger live preview updates (consistent with range inputs)
            input.dispatchEvent(new Event('input', { bubbles: true }));
            <?= $field_name ?>_pickr_<?= $block_id ?>.hide();
        });
        
        // Also trigger live preview on color change (before save) - this enables real-time canvas updates
        <?= $field_name ?>_pickr_<?= $block_id ?>.on('change', (color, instance) => {
            const input = document.getElementById('<?= $picker_id ?>');
            input.value = color.toHEXA().toString();
            
            // Dispatch input event for BorderPreview system
            input.dispatchEvent(new Event('input', { bubbles: true }));
            
            // Real-time canvas update - target specific block only
            if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
                const colorValue = color.toHEXA().toString();
                const iframe = $('#microsite_preview_iframe');
                const iframeDoc = iframe.contents();
                
                // Get the specific block ID from the form context
                const blockId = '<?= $block_id ?>';
                const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
                
                if (microsite_link.length) {
                    // Update canvas based on field name - target specific block only
                    if ('<?= $field_name ?>' === 'border_color') {
                        let targetElement = microsite_link.find('[data-border-color]');
                        if (!targetElement.length) {
                            targetElement = microsite_link.find('img, .card, .text-break, .form-control, .btn');
                        }
                        if (targetElement.length) {
                            targetElement.css('border-color', colorValue);
                        }
                    }
                    if ('<?= $field_name ?>' === 'background_color') {
                        let targetElement = microsite_link.find('[data-background-color]');
                        if (!targetElement.length) {
                            targetElement = microsite_link.find('img, .card, .text-break, .form-control, .btn');
                        }
                        if (targetElement.length) {
                            targetElement.css('background-color', colorValue);
                        }
                    }
                    if ('<?= $field_name ?>' === 'text_color') {
                        let targetElement = microsite_link.find('[data-text-color]');
                        if (!targetElement.length) {
                            targetElement = microsite_link.find('.card-body, .text-break, .form-control, .btn');
                        }
                        if (targetElement.length) {
                            targetElement.css('color', colorValue);
                        }
                    }
                    if ('<?= $field_name ?>' === 'border_shadow_color' && typeof updateCanvasShadow === 'function') {
                        // For shadow color, trigger the complete shadow update function for this specific block
                        updateCanvasShadow(blockId);
                    }
                    // Handle review block color fields
                    if (['title_color', 'description_color', 'author_name_color', 'author_description_color', 'stars_color'].includes('<?= $field_name ?>')) {
                        // For review colors, trigger the complete color update function for this specific block
                        if (typeof updateCanvasColors === 'function') {
                            updateCanvasColors(blockId);
                        }
                        
                        // Also apply direct color updates for immediate feedback
                        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
                        if (microsite_link.length) {
                            if ('<?= $field_name ?>' === 'title_color') {
                                microsite_link.find('.review-title, .review-title h1, .review-title h2, .review-title h3, .review-title h4, .review-title h5, .review-title h6').css('color', colorValue);
                            }
                            if ('<?= $field_name ?>' === 'description_color') {
                                microsite_link.find('.review-description, .review-content').css('color', colorValue);
                            }
                            if ('<?= $field_name ?>' === 'author_name_color') {
                                microsite_link.find('.review-author-name, .author-name').css('color', colorValue);
                            }
                            if ('<?= $field_name ?>' === 'author_description_color') {
                                microsite_link.find('.review-author-description, .author-description').css('color', colorValue);
                            }
                            if ('<?= $field_name ?>' === 'stars_color') {
                                microsite_link.find('.review-stars .fas.fa-star, .stars .fas.fa-star').css('color', colorValue);
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
