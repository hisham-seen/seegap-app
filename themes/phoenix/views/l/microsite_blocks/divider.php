<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings - following Image Block pattern exactly */
$all_styles = [];
$animation_class = '';

// Get divider settings
$divider_settings = $data->link->settings;

// Get divider settings with defaults
$divider_color = $divider_settings->divider_color ?? $divider_settings->background_color ?? '#e9ecef';
$divider_thickness = $divider_settings->divider_thickness ?? 1;
$divider_style = $divider_settings->divider_style ?? 'solid';
$divider_width = $divider_settings->divider_width ?? 100;
$show_icon = $divider_settings->show_icon ?? false;
$icon = $divider_settings->icon ?? '';
$icon_size = $divider_settings->icon_size ?? 20;

// Handle background color
if (isset($divider_settings->background_color) && $divider_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $divider_settings->background_color;
} else {
    $all_styles[] = 'background-color: #0000001A';
}

// Handle border - following exact image block pattern
if (isset($divider_settings->border_width) && $divider_settings->border_width > 0) {
    $border_width = $divider_settings->border_width;
    $border_color = $divider_settings->border_color ?? '#ffffff';
    $border_style = $divider_settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
}

// Handle border radius - following exact image block pattern
if (isset($divider_settings->border_radius)) {
    switch ($divider_settings->border_radius) {
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

// Handle shadow - using correct field names
if (isset($divider_settings->border_shadow_blur_radius) && $divider_settings->border_shadow_blur_radius > 0) {
    $shadow_x = $divider_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $divider_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $divider_settings->border_shadow_blur_radius ?? 0;
    $shadow_spread = $divider_settings->border_shadow_spread_radius ?? 0;
    $shadow_color = $divider_settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

// Handle animation - using correct field names
if (isset($divider_settings->animation_type) && $divider_settings->animation_type && $divider_settings->animation_type !== 'false') {
    $animation_class = 'animate__animated animate__' . $divider_settings->animation_type;
    if (isset($divider_settings->animation_runs) && $divider_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $divider_settings->animation_runs;
    }
    if (isset($divider_settings->animation_delay) && $divider_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($divider_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

$style_attribute = !empty($all_styles) ? 'style="' . implode('; ', $all_styles) . ';"' : '';

// Build divider styles
$divider_styles = "border: none; border-top: {$divider_thickness}px {$divider_style} {$divider_color}; width: {$divider_width}%; margin: 0;";
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 mt-<?= $data->link->settings->margin_top ?> mb-<?= $data->link->settings->margin_bottom ?> <?= $animation_class ?>" <?= $style_attribute ?>>
    
    <?php if($show_icon && !empty($icon)): ?>
        <!-- Divider with center icon -->
        <div class="d-flex justify-content-center align-items-center">
            <hr style="<?= $divider_styles ?>" />
            <span class="mx-3">
                <i class="<?= $icon ?> fa-fw" style="color: <?= $divider_color ?>; font-size: <?= $icon_size ?>px;"></i>
            </span>
            <hr style="<?= $divider_styles ?>" />
        </div>
    <?php else: ?>
        <!-- Simple divider line -->
        <div class="d-flex justify-content-center">
            <hr style="<?= $divider_styles ?>" />
        </div>
    <?php endif ?>
    
</div>
