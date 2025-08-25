<?php defined('SEEGAP') || die() ?>

<?php 
// Get items from settings, handle both new (items) and legacy (images) data structures
$items_to_display = [];
if(!empty($data->link->settings->items)) {
    $items_to_display = (array) $data->link->settings->items;
} elseif(!empty($data->link->settings->images)) {
    $items_to_display = (array) $data->link->settings->images;
}
?>

<?php if(count($items_to_display)): ?>
    <?php
    // Get all customization settings with defaults
    $slider_height = $data->link->settings->slider_height ?? 300;
    $aspect_ratio = $data->link->settings->aspect_ratio ?? 'custom';
    $image_fit = $data->link->settings->image_fit ?? 'cover';
    $border_radius = $data->link->settings->border_radius ?? 0;
    $transition_type = $data->link->settings->transition_type ?? 'slide';
    $transition_speed = $data->link->settings->transition_speed ?? 600;
    $slides_per_view = $data->link->settings->slides_per_view ?? 1;
    $slide_gap = $data->link->settings->slide_gap ?? 0;
    $pause_on_hover = $data->link->settings->pause_on_hover ?? true;
    $infinite_loop = $data->link->settings->infinite_loop ?? true;
    $hover_effect = $data->link->settings->hover_effect ?? 'none';
    
    // Individual Image Border Settings
    $border_width = $data->link->settings->border_width ?? 0;
    $border_color = $data->link->settings->border_color ?? '#000000';
    $border_style = $data->link->settings->border_style ?? 'solid';
    $individual_border_radius = $data->link->settings->border_radius ?? 0;
    
    // Background Settings
    $background_color = $data->link->settings->background_color ?? '';
    $background_gradient = $data->link->settings->background_gradient ?? '';
    
    // Generate individual image styling
    $individual_image_styles = [];
    if($border_width > 0) {
        $individual_image_styles[] = "border: {$border_width}px {$border_style} {$border_color}";
    }
    if($individual_border_radius > 0) {
        $individual_image_styles[] = "border-radius: {$individual_border_radius}px";
    }
    $individual_image_style = !empty($individual_image_styles) ? implode('; ', $individual_image_styles) : '';
    
    // Generate background styling for slider container
    $background_styles = [];
    if(!empty($background_gradient)) {
        $background_styles[] = "background: {$background_gradient}";
    } elseif(!empty($background_color)) {
        $background_styles[] = "background-color: {$background_color}";
    }
    $background_style = !empty($background_styles) ? implode('; ', $background_styles) : '';
    
    // Calculate height based on aspect ratio with minimum height validation
    $calculated_height = $slider_height;
    if($aspect_ratio !== 'custom') {
        switch($aspect_ratio) {
            case '16:9':
                $calculated_height = 'calc(100vw * 9 / 16)';
                break;
            case '4:3':
                $calculated_height = 'calc(100vw * 3 / 4)';
                break;
            case '1:1':
                $calculated_height = '100vw';
                break;
            case '21:9':
                $calculated_height = 'calc(100vw * 9 / 21)';
                break;
        }
    } else {
        // Ensure minimum height of 200px for custom height
        // Fix: Handle both numeric and string values, ensure proper fallback
        $height_value = is_numeric($slider_height) ? (int)$slider_height : (int)($slider_height ?? 300);
        $safe_height = max(200, $height_value);
        $calculated_height = $safe_height . 'px';
    }
    ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <section class="splide <?= 'splide_' . $data->link->microsite_block_id ?>" style="border-radius: <?= $border_radius ?>px; height: <?= $calculated_height ?>; min-height: <?= $calculated_height ?>; <?= $background_style ?>;">
        <div class="splide__track" style="height: <?= $calculated_height ?>;">
            <ul class="splide__list" style="height: <?= $calculated_height ?>;">
                <?php foreach($items_to_display as $key => $item): ?>
                    <li class="splide__slide" style="overflow: visible;">
                        <div class="image-slider-wrapper" style="height: <?= $calculated_height ?>; position: relative; padding: 25px; margin: -25px;">
                            <?php 
                            // Handle both object and array formats
                            $image_file = is_object($item) ? $item->image : $item['image'];
                            $image_alt = is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? '');
                            $location_url = is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '');
                            ?>
                            <?php if($location_url): ?>
                                <a href="<?= $location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="image-slider-link <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
                                    <img 
                                        src="<?= \SeeGap\Uploads::get_full_url('block_images') . $image_file ?>" 
                                        class="image-slider-image hover-effect-<?= $hover_effect ?> <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" 
                                        style="object-fit: <?= $image_fit ?>; height: <?= $calculated_height ?>; width: 100%; <?= $individual_image_style ?>; transition: all 0.3s ease;" 
                                        alt="<?= $image_alt ?>" 
                                        loading="lazy" 
                                    />
                                </a>
                            <?php else: ?>
                                <img 
                                    src="<?= \SeeGap\Uploads::get_full_url('block_images') . $image_file ?>" 
                                    class="image-slider-image hover-effect-<?= $hover_effect ?> <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" 
                                    style="object-fit: <?= $image_fit ?>; height: <?= $calculated_height ?>; width: 100%; <?= $individual_image_style ?>; transition: all 0.3s ease;" 
                                    alt="<?= $image_alt ?>" 
                                    loading="lazy" 
                                />
                            <?php endif ?>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </section>
</div>

<?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'image_slider')): ?>
    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/libraries/splide.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'head', 'image_slider') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/splide.min.js?v=' . PRODUCT_CODE ?>"></script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'image_slider') ?>
<?php endif ?>

<?php ob_start() ?>
<script>
    'use strict';
    document.addEventListener('DOMContentLoaded', () => {
        const sliderId = '<?= 'splide_' . $data->link->microsite_block_id ?>';
        const sliderElement = document.querySelector('.' + sliderId);
        const blockId = '<?= $data->link->microsite_block_id ?>';
        
        // Prevent multiple initializations and check if we're in editing mode
        if (!sliderElement || sliderElement.hasAttribute('data-splide-initialized')) {
            return;
        }
        
        // Check if we're in editing/preview mode and prevent re-initialization
        let isEditing = false;
        try {
            // Check for common editing indicators
            isEditing = window.location.href.includes('/link/') || 
                       document.querySelector('.microsite-editor') !== null ||
                       document.querySelector('[data-microsite-editing]') !== null ||
                       window.parent !== window; // In iframe
        } catch (e) {
            // Ignore errors
        }
        
        // Simple initialization function
        function initializeSlider() {
            // More comprehensive cleanup - look for pagination elements anywhere in the document
            // that might be related to this slider
            const allPagination = document.querySelectorAll('.splide__pagination');
            allPagination.forEach(pagination => {
                // Check if this pagination belongs to our slider
                const parentSlider = pagination.closest('.splide');
                if (parentSlider && parentSlider.classList.contains(sliderId)) {
                    pagination.remove();
                }
            });
            
            // Clean up existing arrows
            const existingArrows = sliderElement.querySelectorAll('.splide__arrows, .splide__arrow');
            existingArrows.forEach(arrow => arrow.remove());
            
            // Also clean up any orphaned pagination elements that might be outside the slider
            const orphanedPagination = document.querySelectorAll('[class*="splide"][class*="pagination"]');
            orphanedPagination.forEach(element => {
                if (element.innerHTML.includes('splide' + blockId.replace(/[^0-9]/g, ''))) {
                    element.remove();
                }
            });
            
            // Destroy existing instance if it exists
            if (window['splide_' + blockId]) {
                try {
                    window['splide_' + blockId].destroy();
                    delete window['splide_' + blockId];
                } catch (e) {
                    // Ignore errors during destruction
                }
            }
            
            // Reset any splide-related classes on the slider element
            sliderElement.classList.remove('is-initialized', 'is-active', 'splide--slide', 'splide--loop', 'splide--fade');
            sliderElement.removeAttribute('data-splide-initialized');
            
            const splideConfig = {
                // Basic settings - handle transition type and infinite loop properly
                <?php if($transition_type === 'fade'): ?>
                type: 'fade',
                rewind: <?= $infinite_loop ? 'true' : 'false' ?>,
                <?php else: ?>
                type: <?= $infinite_loop ? "'loop'" : "'slide'" ?>,
                <?php endif ?>
                
                autoplay: <?= json_encode($data->link->settings->autoplay ?? true) ?>,
                interval: <?= json_encode(($data->link->settings->autoplay_interval ?? 5) * 1000) ?>,
                arrows: <?= json_encode($data->link->settings->display_arrows ?? true) ?>,
                // Temporarily disable pagination completely to prevent dot accumulation
                pagination: false,
                
                // Phase 1: Advanced customization settings
                speed: <?= $transition_speed ?>,
                perPage: <?= $slides_per_view ?>,
                gap: '<?= $slide_gap ?>px',
                pauseOnHover: <?= $pause_on_hover ? 'true' : 'false' ?>,
                direction: '<?= l('direction') ?>',
                
                // Responsive behavior - maintain slides per view setting across all devices
                breakpoints: {
                    768: {
                        perPage: <?= $slides_per_view ?>,
                        gap: '<?= max(0, $slide_gap - 5) ?>px',
                    },
                    480: {
                        perPage: <?= $slides_per_view ?>,
                        gap: '<?= max(0, $slide_gap - 10) ?>px',
                    }
                },
                
                // Cover mode for proper image fitting
                cover: <?= $image_fit === 'cover' ? 'true' : 'false' ?>,
                
                // Height ratio (only used when cover is true)
                <?php if($aspect_ratio !== 'custom'): ?>
                heightRatio: <?php 
                    switch($aspect_ratio) {
                        case '16:9': echo '0.5625'; break;
                        case '4:3': echo '0.75'; break;
                        case '1:1': echo '1'; break;
                        case '21:9': echo '0.4286'; break;
                        default: echo '0.5625';
                    }
                ?>,
                <?php endif ?>
                
                // Legacy support for existing settings
                autoWidth: <?= json_encode($data->link->settings->display_multiple ?? false) ?>
            };
            
            // Create new instance and store reference
            const splide = new Splide('.' + sliderId, splideConfig);
            window['splide_' + blockId] = splide;
            splide.mount();
            
            // Mark as initialized
            sliderElement.setAttribute('data-splide-initialized', 'true');
        }
        
        // Initialize the slider
        initializeSlider();
        
        // Add continuous cleanup to prevent pagination accumulation
        setInterval(() => {
            // Clean up any duplicate pagination elements for this slider
            const allPagination = document.querySelectorAll('.splide__pagination');
            let paginationCount = 0;
            allPagination.forEach(pagination => {
                const parentSlider = pagination.closest('.splide');
                if (parentSlider && parentSlider.classList.contains(sliderId)) {
                    paginationCount++;
                    // If we have more than one pagination for this slider, remove the extras
                    if (paginationCount > 1) {
                        pagination.remove();
                    }
                }
            });
        }, 500); // Check every 500ms
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>

<!-- CSS Styles for Image Slider Hover Effects -->
<?php 
$slider_id = 'splide_' . $data->link->microsite_block_id;
?>
<?php ob_start() ?>
<style>
/* Image Slider Link Styling */
.<?= $slider_id ?> .image-slider-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
}

.<?= $slider_id ?> .image-slider-link:hover {
    text-decoration: none;
}

/* Hover Effects for Image Slider */
<?php if($hover_effect === 'zoom'): ?>
.<?= $slider_id ?> .hover-effect-zoom:hover {
    transform: scale(1.05);
}
<?php elseif($hover_effect === 'fade'): ?>
.<?= $slider_id ?> .hover-effect-fade:hover {
    opacity: 0.8;
}
<?php elseif($hover_effect === 'lift'): ?>
.<?= $slider_id ?> .image-slider-wrapper:has(.hover-effect-lift:hover) {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Fallback for browsers that don't support :has() */
.<?= $slider_id ?> .hover-effect-lift:hover {
    /* Image hover effect as fallback */
}

.<?= $slider_id ?> .image-slider-wrapper:hover .hover-effect-lift {
    /* Trigger wrapper transform via child hover */
}
<?php endif ?>
</style>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'head') ?>
<?php endif ?>
