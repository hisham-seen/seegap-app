<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings */
$all_styles = [];
$height_class = 'h-auto';
$width_class = 'w-100';
$animation_class = '';

// Handle height
if (isset($data->link->settings->image_height) && $data->link->settings->image_height !== null && $data->link->settings->image_height !== '') {
    $height_unit = $data->link->settings->image_height_unit ?? 'px';
    $all_styles[] = 'height: ' . $data->link->settings->image_height . $height_unit;
    $all_styles[] = 'object-fit: cover';
    $height_class = ''; // Remove h-auto class when custom height is set
}

// Handle width
if (isset($data->link->settings->image_width) && $data->link->settings->image_width !== null && $data->link->settings->image_width !== '') {
    $width_unit = $data->link->settings->image_width_unit ?? 'px';
    $all_styles[] = 'width: ' . $data->link->settings->image_width . $width_unit;
    $all_styles[] = 'object-fit: cover';
    $width_class = ''; // Remove w-100 class when custom width is set
}

// Handle background color
if (isset($image_settings->background_color) && $image_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $image_settings->background_color;
} else {
    $all_styles[] = 'background-color: #0000001A';
}

// Handle border
if (isset($data->link->settings->border_width) && $data->link->settings->border_width > 0) {
    $border_width = $data->link->settings->border_width;
    $border_color = $data->link->settings->border_color ?? '#ffffff';
    $border_style = $data->link->settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
}

// Handle border radius - with backward compatibility
if (isset($data->link->settings->border_radius)) {
    if (is_numeric($data->link->settings->border_radius)) {
        // New system: use pixel value
        if ($data->link->settings->border_radius > 0) {
            $all_styles[] = 'border-radius: ' . $data->link->settings->border_radius . 'px';
        }
    } else {
        // Old system: convert string to pixels for backward compatibility
        switch ($data->link->settings->border_radius) {
            case 'straight':
                $all_styles[] = 'border-radius: 0';
                break;
            case 'round':
                $all_styles[] = 'border-radius: 50%';
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
if (isset($data->link->settings->border_shadow_blur) && $data->link->settings->border_shadow_blur > 0) {
    $shadow_x = $data->link->settings->border_shadow_offset_x ?? 0;
    $shadow_y = $data->link->settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $data->link->settings->border_shadow_blur ?? 0;
    $shadow_spread = $data->link->settings->border_shadow_spread ?? 0;
    $shadow_color = $data->link->settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

// Handle animation
if (isset($data->link->settings->animation) && $data->link->settings->animation && $data->link->settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $data->link->settings->animation;
    if (isset($data->link->settings->animation_runs) && $data->link->settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $data->link->settings->animation_runs;
    }
    if (isset($data->link->settings->animation_delay) && $data->link->settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($data->link->settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

$style_attribute = !empty($all_styles) ? 'style="' . implode('; ', $all_styles) . ';"' : '';

/* Verified badge settings */
$verified_badge = $data->link->settings->verified_badge ?? (object)['enabled' => false];
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?> text-<?= $data->link->settings->text_alignment ?? 'center' ?>">
    <div class="image-block-container position-relative d-inline-block">
        <?php if($data->link->location_url): ?>
        <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="<?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
            <img src="<?= \SeeGap\Uploads::get_full_url('block_images') . $data->link->settings->image ?>" class="<?= $width_class ?> <?= $height_class ?> <?= $animation_class ?> <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" alt="<?= $data->link->settings->image_alt ?>" loading="lazy" <?= $style_attribute ?> data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-background-color />
        </a>
        <?php else: ?>
        <img src="<?= \SeeGap\Uploads::get_full_url('block_images') . $data->link->settings->image ?>" class="<?= $width_class ?> <?= $height_class ?> <?= $animation_class ?>" alt="<?= $data->link->settings->image_alt ?>" loading="lazy" <?= $style_attribute ?> data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-background-color />
        <?php endif ?>

        <!-- Verified Badge -->
        <?php if(($verified_badge->enabled ?? false)): ?>
        <div class="verified-badge 
                   <?= 'badge-style-' . ($verified_badge->style ?? 'checkmark') ?> 
                   <?= 'badge-position-' . ($verified_badge->position ?? 'bottom_right') ?> 
                   <?= 'badge-size-' . ($verified_badge->size ?? 'medium') ?>"
             style="color: <?= $verified_badge->color ?? '#1da1f2' ?>;">
            <?php 
            $badge_style = $verified_badge->style ?? 'checkmark';
            switch($badge_style) {
                case 'star':
                    echo '<i class="fas fa-star"></i>';
                    break;
                case 'crown':
                    echo '<i class="fas fa-crown"></i>';
                    break;
                case 'shield':
                    echo '<i class="fas fa-shield-alt"></i>';
                    break;
                case 'heart':
                    echo '<i class="fas fa-heart"></i>';
                    break;
                case 'diamond':
                    echo '<i class="fas fa-gem"></i>';
                    break;
                case 'medal':
                    echo '<i class="fas fa-medal"></i>';
                    break;
                case 'award':
                    echo '<i class="fas fa-award"></i>';
                    break;
                case 'checkmark':
                default:
                    echo '<i class="fas fa-check-circle"></i>';
                    break;
            }
            ?>
        </div>
        <?php endif ?>
    </div>
</div>

<style>
/* Verified Badge Styles for Image Block */
.image-block-container .verified-badge {
    position: absolute;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 10;
}

/* Badge Positions */
.image-block-container .badge-position-bottom_right {
    bottom: 0;
    right: 0;
    transform: translate(25%, 25%);
}

.image-block-container .badge-position-top_right {
    top: 0;
    right: 0;
    transform: translate(25%, -25%);
}

.image-block-container .badge-position-bottom_left {
    bottom: 0;
    left: 0;
    transform: translate(-25%, 25%);
}

.image-block-container .badge-position-center_bottom {
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 50%);
}

/* Badge Sizes */
.image-block-container .badge-size-small {
    width: 20px;
    height: 20px;
    font-size: 10px;
}

.image-block-container .badge-size-medium {
    width: 24px;
    height: 24px;
    font-size: 12px;
}

.image-block-container .badge-size-large {
    width: 28px;
    height: 28px;
    font-size: 14px;
}

/* Responsive Adjustments */
@media (max-width: 576px) {
    /* Slightly larger badges on mobile for better visibility */
    .image-block-container .badge-size-small { width: 22px; height: 22px; font-size: 11px; }
    .image-block-container .badge-size-medium { width: 26px; height: 26px; font-size: 13px; }
    .image-block-container .badge-size-large { width: 30px; height: 30px; font-size: 15px; }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .image-block-container .verified-badge {
        border: 1px solid #000;
    }
}

/* Print styles */
@media print {
    .image-block-container .verified-badge {
        background: #000 !important;
        color: #fff !important;
    }
}
</style>
