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
        <input id="<?= 'block_border_shadow_offset_x_' . $block_id ?>" type="range" min="-25" max="25" class="form-control-range" name="border_shadow_offset_x" value="<?= $settings->border_shadow_offset_x ?? 0 ?>" required="required" onchange="updateCanvasShadowComplete('<?= $block_id ?>')" oninput="updateCanvasShadowOffsetX('<?= $block_id ?>', this.value)" />
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_shadow_offset_y_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
        <input id="<?= 'block_border_shadow_offset_y_' . $block_id ?>" type="range" min="-25" max="25" class="form-control-range" name="border_shadow_offset_y" value="<?= $settings->border_shadow_offset_y ?? 0 ?>" required="required" onchange="updateCanvasShadowComplete('<?= $block_id ?>')" oninput="updateCanvasShadowOffsetY('<?= $block_id ?>', this.value)" />
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_shadow_blur_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
        <input id="<?= 'block_border_shadow_blur_' . $block_id ?>" type="range" min="0" max="30" class="form-control-range" name="border_shadow_blur" value="<?= $settings->border_shadow_blur ?? 0 ?>" required="required" onchange="updateCanvasShadowComplete('<?= $block_id ?>')" oninput="updateCanvasShadowBlur('<?= $block_id ?>', this.value)" />
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'block_border_shadow_spread_' . $block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
        <input id="<?= 'block_border_shadow_spread_' . $block_id ?>" type="range" min="-15" max="15" class="form-control-range" name="border_shadow_spread" value="<?= $settings->border_shadow_spread ?? 0 ?>" required="required" onchange="updateCanvasShadowComplete('<?= $block_id ?>')" oninput="updateCanvasShadowSpread('<?= $block_id ?>', this.value)" />
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

<script>
// Ultra-lightweight shadow functions (exactly matches border radius performance pattern)

// Simple helper to get current form values (called only when needed)
function getShadowValues(blockId) {
    return {
        offsetX: $(`#block_border_shadow_offset_x_${blockId}`).val() || 0,
        offsetY: $(`#block_border_shadow_offset_y_${blockId}`).val() || 0,
        blur: $(`#block_border_shadow_blur_${blockId}`).val() || 0,
        spread: $(`#block_border_shadow_spread_${blockId}`).val() || 0,
        color: $(`input[name="border_shadow_color"]`).val() || '#00000010'
    };
}

// Ultra-simple shadow update functions (matches border radius pattern exactly)
function updateCanvasShadowOffsetX(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // More specific targeting - prioritize main card element, avoid card body
            let targetElement = microsite_link.find('.card').first();
            if (!targetElement.length) {
                targetElement = microsite_link.find('img, .btn').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link.find('.text-break').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                const values = getShadowValues(blockId);
                values.offsetX = value; // Use the new value
                const shadowCSS = `${values.offsetX}px ${values.offsetY}px ${values.blur}px ${values.spread}px ${values.color}`;
                targetElement.css('box-shadow', shadowCSS);
            }
        }
    }
}

function updateCanvasShadowOffsetY(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            let targetElement = microsite_link.find('.card').first();
            if (!targetElement.length) {
                targetElement = microsite_link.find('img, .btn').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link.find('.text-break').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                const values = getShadowValues(blockId);
                values.offsetY = value; // Use the new value
                const shadowCSS = `${values.offsetX}px ${values.offsetY}px ${values.blur}px ${values.spread}px ${values.color}`;
                targetElement.css('box-shadow', shadowCSS);
            }
        }
    }
}

function updateCanvasShadowBlur(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            let targetElement = microsite_link.find('.card').first();
            if (!targetElement.length) {
                targetElement = microsite_link.find('img, .btn').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link.find('.text-break').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                const values = getShadowValues(blockId);
                values.blur = value; // Use the new value
                const shadowCSS = `${values.offsetX}px ${values.offsetY}px ${values.blur}px ${values.spread}px ${values.color}`;
                targetElement.css('box-shadow', shadowCSS);
            }
        }
    }
}

function updateCanvasShadowSpread(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            let targetElement = microsite_link.find('.card').first();
            if (!targetElement.length) {
                targetElement = microsite_link.find('img, .btn').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link.find('.text-break').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                const values = getShadowValues(blockId);
                values.spread = value; // Use the new value
                const shadowCSS = `${values.offsetX}px ${values.offsetY}px ${values.blur}px ${values.spread}px ${values.color}`;
                targetElement.css('box-shadow', shadowCSS);
            }
        }
    }
}

// Complete shadow update function (for onchange events and color picker)
function updateCanvasShadowComplete(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get all current values from form inputs
            const offsetX = $(`#block_border_shadow_offset_x_${blockId}`).val() || 0;
            const offsetY = $(`#block_border_shadow_offset_y_${blockId}`).val() || 0;
            const blur = $(`#block_border_shadow_blur_${blockId}`).val() || 0;
            const spread = $(`#block_border_shadow_spread_${blockId}`).val() || 0;
            const color = $(`input[name="border_shadow_color"]`).val() || '#00000010';
            
            // Update cache (if needed for compatibility)
            if (!window.shadowCache) window.shadowCache = {};
            window.shadowCache[blockId] = { offsetX, offsetY, blur, spread, color };
            
            // Find target element using same specific targeting as individual functions
            let targetElement = microsite_link.find('.card').first();
            if (!targetElement.length) {
                targetElement = microsite_link.find('img, .btn').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link.find('.text-break').first();
            }
            if (!targetElement.length) {
                targetElement = microsite_link;
            }
            
            if (targetElement.length) {
                // Apply complete shadow
                if (offsetX == 0 && offsetY == 0 && blur == 0 && spread == 0) {
                    targetElement.css('box-shadow', 'none');
                } else {
                    const shadowCSS = `${offsetX}px ${offsetY}px ${blur}px ${spread}px ${color}`;
                    targetElement.css('box-shadow', shadowCSS);
                }
            }
        }
    }
}

// Backward compatibility
function updateCanvasShadow(blockId) {
    updateCanvasShadowComplete(blockId);
}
</script>
