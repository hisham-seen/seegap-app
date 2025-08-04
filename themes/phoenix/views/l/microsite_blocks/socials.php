<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings - following Image Block pattern exactly */
$all_styles = [];
$animation_class = '';

// Get socials settings
$socials_settings = $data->link->settings;

// Get icon size from settings (numeric pixels only)
$icon_size_px = (int)($socials_settings->size ?? 24);

// Ensure size is within bounds
$icon_size_px = max(10, min(60, $icon_size_px));

// Handle background color
if (isset($socials_settings->background_color) && $socials_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $socials_settings->background_color;
} else {
    $all_styles[] = 'background-color: #0000001A';
}

// Handle border - following exact image block pattern
if (isset($socials_settings->border_width) && $socials_settings->border_width > 0) {
    $border_width = $socials_settings->border_width;
    $border_color = $socials_settings->border_color ?? '#ffffff';
    $border_style = $socials_settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
}

// Handle border radius - following exact image block pattern
if (isset($socials_settings->border_radius)) {
    switch ($socials_settings->border_radius) {
        case 'straight':
            $all_styles[] = 'border-radius: 0';
            break;
        case 'round':
            $all_styles[] = 'border-radius: 50px';
            break;
        case 'rounded':
            $all_styles[] = 'border-radius: 0.25rem';
            break;
        case 'rounded-sm':
            $all_styles[] = 'border-radius: 0.125rem';
            break;
        case 'rounded-lg':
            $all_styles[] = 'border-radius: 0.5rem';
            break;
        case 'rounded-xl':
            $all_styles[] = 'border-radius: 0.75rem';
            break;
        case 'rounded-2xl':
            $all_styles[] = 'border-radius: 1rem';
            break;
        case 'rounded-3xl':
            $all_styles[] = 'border-radius: 1.5rem';
            break;
        case 'rounded-full':
            $all_styles[] = 'border-radius: 9999px';
            break;
    }
}

// Handle shadow - following exact image block pattern
if (isset($socials_settings->border_shadow_blur) && $socials_settings->border_shadow_blur > 0) {
    $shadow_x = $socials_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $socials_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $socials_settings->border_shadow_blur ?? 0;
    $shadow_spread = $socials_settings->border_shadow_spread ?? 0;
    $shadow_color = $socials_settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

// Handle animation - following exact image block pattern
if (isset($socials_settings->animation) && $socials_settings->animation && $socials_settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $socials_settings->animation;
    if (isset($socials_settings->animation_runs) && $socials_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $socials_settings->animation_runs;
    }
    if (isset($socials_settings->animation_delay) && $socials_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($socials_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

$style_attribute = !empty($all_styles) ? 'style="' . implode('; ', $all_styles) . ';"' : '';

// Load microsite socials configuration
$microsite_socials = require APP_PATH . 'includes/microsite_socials.php';

// Check if socials data exists and is not empty
$has_socials = isset($data->link->settings->socials) && !empty((array)$data->link->settings->socials);

// Debug: If no socials data found, check if it's stored differently
if (!$has_socials) {
    // Sometimes the data might be stored directly in settings
    if (isset($data->link->settings) && is_object($data->link->settings)) {
        foreach ($data->link->settings as $key => $value) {
            if (isset($microsite_socials[$key]) && !empty(trim($value))) {
                $has_socials = true;
                // Create socials array if it doesn't exist
                if (!isset($data->link->settings->socials)) {
                    $data->link->settings->socials = new stdClass();
                }
                $data->link->settings->socials->$key = $value;
            }
        }
    }
}

// Additional debug: Check if data is stored as array instead of object
if (!$has_socials && isset($data->link->settings) && is_array($data->link->settings)) {
    foreach ($data->link->settings as $key => $value) {
        if (isset($microsite_socials[$key]) && !empty(trim($value))) {
            $has_socials = true;
            // Create socials array if it doesn't exist
            if (!isset($data->link->settings['socials'])) {
                $data->link->settings['socials'] = [];
            }
            $data->link->settings['socials'][$key] = $value;
        }
    }
    // Convert to object for consistency
    if ($has_socials && is_array($data->link->settings)) {
        $data->link->settings = (object) $data->link->settings;
        if (isset($data->link->settings->socials) && is_array($data->link->settings->socials)) {
            $data->link->settings->socials = (object) $data->link->settings->socials;
        }
    }
}

// Final fallback: Show debug info in development
if (!$has_socials && defined('DEVELOPMENT') && DEVELOPMENT) {
    // This will help us see what data structure we're actually getting
    error_log('Social block debug - Link settings: ' . print_r($data->link->settings, true));
    error_log('Social block debug - Available microsite socials: ' . print_r(array_keys($microsite_socials), true));
}

// Build spacing styles
$spacing_styles = '';
$container_spacing_styles = '';
$element_spacing_styles = '';

// Block-level margins (external spacing)
$margin_top = (int)($data->link->settings->margin_top ?? 0);
$margin_bottom = (int)($data->link->settings->margin_bottom ?? 0);
$margin_left = (int)($data->link->settings->margin_left ?? 0);
$margin_right = (int)($data->link->settings->margin_right ?? 0);

if ($margin_top > 0 || $margin_bottom > 0 || $margin_left > 0 || $margin_right > 0) {
    $container_spacing_styles .= "margin: {$margin_top}rem {$margin_right}rem {$margin_bottom}rem {$margin_left}rem;";
}

// Block-level padding (internal spacing)
$padding_top = (int)($data->link->settings->padding_top ?? 0);
$padding_bottom = (int)($data->link->settings->padding_bottom ?? 0);
$padding_left = (int)($data->link->settings->padding_left ?? 0);
$padding_right = (int)($data->link->settings->padding_right ?? 0);

if ($padding_top > 0 || $padding_bottom > 0 || $padding_left > 0 || $padding_right > 0) {
    $container_spacing_styles .= "padding: {$padding_top}rem {$padding_right}rem {$padding_bottom}rem {$padding_left}rem;";
}

// Internal content padding
$internal_padding = (int)($data->link->settings->internal_padding ?? 0);
if ($internal_padding > 0) {
    $spacing_styles .= "padding: {$internal_padding}rem;";
}

// Element spacing (space between social icons)
$element_spacing = (int)($data->link->settings->element_spacing ?? 0);
if ($element_spacing > 0) {
    $element_spacing_styles = "margin: {$element_spacing}rem;";
}

// Content margin
$content_margin = (int)($data->link->settings->content_margin ?? 0);
if ($content_margin > 0) {
    $spacing_styles .= "margin: {$content_margin}rem;";
}

// Block gap (spacing between this block and adjacent blocks)
$block_gap = (int)($data->link->settings->block_gap ?? 0);
$block_gap_class = $block_gap > 0 ? "my-{$block_gap}" : "my-" . ($data->microsite->settings->block_spacing ?? '2');
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 <?= $block_gap_class ?>" style="<?= $container_spacing_styles ?>">
    <?php if($has_socials): ?>
        <div class="d-flex flex-wrap justify-content-center" style="<?= $spacing_styles ?>">
            <?php foreach($data->link->settings->socials as $key => $value): ?>
                <?php if(!empty(trim($value)) && isset($microsite_socials[$key])): ?>
                    <?php 
                    // Calculate container size based on icon size plus padding
                    $container_size = $icon_size_px + 16; // 8px padding on each side
                    ?>
                    <div class="my-2 mx-2 <?= $animation_class ?>" <?= $style_attribute ?> style="width: <?= $container_size ?>px; height: <?= $container_size ?>px; display: inline-flex; align-items: center; justify-content: center; <?= $element_spacing_styles ?>" data-toggle="tooltip" title="<?= l('microsite_socials.' . $key . '.name') ?>">
                        <a href="<?= sprintf($microsite_socials[$key]['format'], $value) ?>" target="_blank" rel="noreferrer" class="<?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                            <i class="<?= $microsite_socials[$key]['icon'] ?> fa-fw" style="color: <?= $data->link->settings->color ?? '#333333' ?>; font-size: <?= $icon_size_px ?>px; width: <?= $icon_size_px ?>px; height: <?= $icon_size_px ?>px; line-height: <?= $icon_size_px ?>px; text-align: center; display: inline-block;" data-color></i>
                        </a>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <!-- Minimalistic block-style empty state -->
        <div class="text-center py-4 px-3" style="border: 1px solid #e9ecef; border-radius: 4px; background-color: #f8f9fa; <?= $spacing_styles ?>">
            <small class="text-muted">No social links configured</small>
        </div>
    <?php endif ?>
</div>

<style>
/* Ensure FontAwesome icons display properly even during loading */
.fa-fw {
    width: 1.25em !important;
    text-align: center;
    display: inline-block;
}

/* Fallback for icon loading issues */
i[class*="fa-"]:before {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 5 Free", "Font Awesome 5 Brands", sans-serif;
    font-weight: 900;
}

/* Ensure brand icons use the correct font weight */
.fab:before {
    font-family: "Font Awesome 6 Brands", "Font Awesome 5 Brands", sans-serif;
    font-weight: 400;
}

/* Prevent layout shift during icon loading */
#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"] {
    opacity: 0;
    transition: opacity 0.3s ease;
}

#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"].fa-loaded {
    opacity: 1;
}
</style>

<script>
// Ensure FontAwesome icons are visible once loaded
document.addEventListener('DOMContentLoaded', function() {
    function checkFontAwesome() {
        const icons = document.querySelectorAll('#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"]');
        
        // Check if FontAwesome is loaded by testing a known icon
        const testIcon = document.createElement('i');
        testIcon.className = 'fas fa-home';
        testIcon.style.position = 'absolute';
        testIcon.style.left = '-9999px';
        document.body.appendChild(testIcon);
        
        const computedStyle = window.getComputedStyle(testIcon, ':before');
        const fontFamily = computedStyle.getPropertyValue('font-family');
        
        document.body.removeChild(testIcon);
        
        // If FontAwesome is loaded or after timeout, show icons
        if (fontFamily.includes('Font Awesome') || fontFamily.includes('FontAwesome')) {
            icons.forEach(icon => icon.classList.add('fa-loaded'));
        } else {
            // Retry after a short delay
            setTimeout(checkFontAwesome, 100);
        }
    }
    
    // Start checking immediately and set a fallback timeout
    checkFontAwesome();
    setTimeout(() => {
        const icons = document.querySelectorAll('#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"]');
        icons.forEach(icon => icon.classList.add('fa-loaded'));
    }, 2000); // Fallback after 2 seconds
});
</script>
