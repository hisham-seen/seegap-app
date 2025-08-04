<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings - simplified for WYSIWYG content */
$all_styles = [];
$animation_class = '';

// Get text settings
$text_settings = $data->link->settings;

// Handle background color
if (isset($text_settings->background_color) && $text_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $text_settings->background_color;
}

// Handle text color
if (isset($text_settings->text_color)) {
    $all_styles[] = 'color: ' . $text_settings->text_color;
}

// Handle border
if (isset($text_settings->border_width) && $text_settings->border_width > 0) {
    $border_width = $text_settings->border_width;
    $border_color = $text_settings->border_color ?? '#ffffff';
    $border_style = $text_settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
}

// Handle border radius
if (isset($text_settings->border_radius)) {
    if (is_numeric($text_settings->border_radius)) {
        // New system: use pixel value
        if ($text_settings->border_radius > 0) {
            $all_styles[] = 'border-radius: ' . $text_settings->border_radius . 'px';
        }
    } else {
        // Old system: convert string to pixels for backward compatibility
        switch ($text_settings->border_radius) {
            case 'straight':
                $all_styles[] = 'border-radius: 0';
                break;
            case 'round':
                $all_styles[] = 'border-radius: 25px';
                break;
            case 'rounded':
                $all_styles[] = 'border-radius: 4px';
                break;
            case 'rounded-sm':
                $all_styles[] = 'border-radius: 2px';
                break;
            case 'rounded-lg':
                $all_styles[] = 'border-radius: 8px';
                break;
            case 'rounded-xl':
                $all_styles[] = 'border-radius: 12px';
                break;
            case 'rounded-2xl':
                $all_styles[] = 'border-radius: 16px';
                break;
            case 'rounded-3xl':
                $all_styles[] = 'border-radius: 24px';
                break;
            case 'rounded-full':
                $all_styles[] = 'border-radius: 50px';
                break;
        }
    }
}

// Handle shadow
if (isset($text_settings->border_shadow_blur) && $text_settings->border_shadow_blur > 0) {
    $shadow_x = $text_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $text_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $text_settings->border_shadow_blur ?? 0;
    $shadow_spread = $text_settings->border_shadow_spread ?? 0;
    $shadow_color = $text_settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

// Handle animation
if (isset($text_settings->animation) && $text_settings->animation && $text_settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $text_settings->animation;
    if (isset($text_settings->animation_runs) && $text_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $text_settings->animation_runs;
    }
    if (isset($text_settings->animation_delay) && $text_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($text_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

// Add text alignment
$all_styles[] = 'text-align: ' . ($text_settings->text_alignment ?? 'center');

$style_attribute = !empty($all_styles) ? 'style="' . implode('; ', $all_styles) . ';"' : '';

// Determine if we need card styling (has background, border, or shadow)
$has_styling = (
    (isset($text_settings->background_color) && $text_settings->background_color !== '#00000000') ||
    (isset($text_settings->border_width) && $text_settings->border_width > 0) ||
    (isset($text_settings->border_shadow_blur) && $text_settings->border_shadow_blur > 0)
);
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    
    <?php if ($has_styling): ?>
        <!-- Text block with styling (card wrapper) -->
        <div class="card <?= $animation_class ?>" <?= $style_attribute ?> data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-background-color>
            <div class="card-body text-break" data-text data-text-color data-text-alignment>
                <?= $data->link->settings->content ?? '' ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Text block without styling (direct content) -->
        <div class="text-break <?= $animation_class ?>" <?= $style_attribute ?> data-text data-text-color data-text-alignment>
            <?= $data->link->settings->content ?? '' ?>
        </div>
    <?php endif ?>
    
</div>
