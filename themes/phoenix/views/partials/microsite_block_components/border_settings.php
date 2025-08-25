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
        <input id="<?= 'block_border_width_' . $block_id ?>" type="range" min="0" max="20" step="1" class="form-control-range" name="border_width" value="<?= $settings->border_width ?? '0' ?>" required="required" onchange="updateCanvasBorderWidth('<?= $block_id ?>', this.value)" oninput="updateCanvasBorderWidth('<?= $block_id ?>', this.value)" />
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
        <input id="<?= 'block_border_radius_' . $block_id ?>" type="range" min="0" max="50" step="1" class="form-control-range" name="border_radius" value="<?= is_numeric($settings->border_radius ?? 0) ? ($settings->border_radius ?? 0) : 0 ?>" required="required" onchange="updateCanvasBorderRadius('<?= $block_id ?>', this.value)" oninput="updateCanvasBorderRadius('<?= $block_id ?>', this.value)" />
        <small class="form-text text-muted">Set border radius in pixels. 0 = straight corners, higher values = more rounded corners.</small>
    </div>

    <div class="form-group">
        <label for="<?= 'block_border_style_' . $block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_style') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($settings->border_style ?? 'solid') == $border_style ? 'active' : '' ?>">
                        <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($settings->border_style ?? 'solid') == $border_style ? 'checked="checked"' : '' ?> onchange="updateCanvasBorderStyle('<?= $block_id ?>', this.value)" />
                        <?= l('microsite_link.border_style_' . $border_style) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

<script>
// Real-time canvas update function for border radius
function updateCanvasBorderRadius(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Find the target element - look for data attribute first, then fallback to common elements
            let targetElement = microsite_link.find('[data-border-radius]');
            if (!targetElement.length) {
                // Fallback for different block types
                targetElement = microsite_link.find('img, .card, .text-break, .form-control, .btn');
            }
            if (!targetElement.length) {
                // Final fallback to the block container itself
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                targetElement.css('border-radius', value + 'px');
            }
        }
    }
}

// Ultra-lightweight real-time canvas update function for border width (exactly matches border radius pattern)
function updateCanvasBorderWidth(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Use same targeting pattern as border radius function
            let targetElement = microsite_link.find('[data-border-radius]');
            if (!targetElement.length) {
                // Fallback for different block types
                targetElement = microsite_link.find('img, .card, .text-break, .form-control, .btn');
            }
            if (!targetElement.length) {
                // Final fallback to the block container itself
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                // Ultra-simple: exactly like border radius - just set the CSS property
                targetElement.css('border-width', value + 'px');
                
                // Only set border style and color if border width > 0 and they don't exist
                if (value > 0) {
                    const currentStyle = targetElement.css('border-style');
                    const currentColor = targetElement.css('border-color');
                    
                    if (!currentStyle || currentStyle === 'none') {
                        targetElement.css('border-style', 'solid');
                    }
                    if (!currentColor || currentColor === 'transparent' || currentColor === 'rgba(0, 0, 0, 0)') {
                        targetElement.css('border-color', '#cccccc');
                    }
                }
            }
        }
    }
}

// Ultra-lightweight real-time canvas update function for border style
function updateCanvasBorderStyle(blockId, value) {
    // Use the accordion block's own border update function if available
    if (typeof window.updateCanvasBorder === 'function') {
        window.updateCanvasBorder(blockId);
        return;
    }
    
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Find the target element - simplified targeting
            let targetElement = microsite_link.find('.card, .text-break, .form-control, .btn, img');
            if (!targetElement.length) {
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                // Ultra-simple: just update border style
                targetElement.css('border-style', value);
            }
        }
    }
}
</script>
