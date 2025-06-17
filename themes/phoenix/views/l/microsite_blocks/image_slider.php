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
        $safe_height = max(200, (int)$slider_height);
        $calculated_height = $safe_height . 'px';
    }
    ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <section class="splide <?= 'splide_' . $data->link->microsite_block_id ?>" style="border-radius: <?= $border_radius ?>px; overflow: hidden;">
        <div class="splide__track">
            <ul class="splide__list">
                <?php foreach($items_to_display as $key => $item): ?>
                    <li class="splide__slide">
                        <?php 
                        // Handle both object and array formats
                        $image_file = is_object($item) ? $item->image : $item['image'];
                        $image_alt = is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? '');
                        $location_url = is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '');
                        ?>
                        <?php if($location_url): ?>
                            <a href="<?= $location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="<?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
                                <img 
                                    src="<?= \SeeGap\Uploads::get_full_url('block_images') . $image_file ?>" 
                                    class="link-image-slider-image <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" 
                                    style="object-fit: <?= $image_fit ?>; height: <?= $calculated_height ?>; border-radius: <?= $border_radius ?>px; width: 100%;" 
                                    alt="<?= $image_alt ?>" 
                                    loading="lazy" 
                                />
                            </a>
                        <?php else: ?>
                            <img 
                                src="<?= \SeeGap\Uploads::get_full_url('block_images') . $image_file ?>" 
                                class="link-image-slider-image <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" 
                                style="object-fit: <?= $image_fit ?>; height: <?= $calculated_height ?>; border-radius: <?= $border_radius ?>px; width: 100%;" 
                                alt="<?= $image_alt ?>" 
                                loading="lazy" 
                            />
                        <?php endif ?>
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
            pagination: <?= json_encode($data->link->settings->display_pagination ?? true) ?>,
            
            // Phase 1: Advanced customization settings
            speed: <?= $transition_speed ?>,
            perPage: <?= $slides_per_view ?>,
            gap: '<?= $slide_gap ?>px',
            pauseOnHover: <?= $pause_on_hover ? 'true' : 'false' ?>,
            direction: '<?= l('direction') ?>',
            
            // Responsive behavior
            breakpoints: {
                768: {
                    perPage: <?= $slides_per_view > 2 ? 2 : $slides_per_view ?>,
                    gap: '<?= max(0, $slide_gap - 10) ?>px',
                },
                480: {
                    perPage: 1,
                    gap: '<?= max(0, $slide_gap - 15) ?>px',
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
        
        let splide = new Splide('.<?= 'splide_' . $data->link->microsite_block_id ?>', splideConfig);
        splide.mount();
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
