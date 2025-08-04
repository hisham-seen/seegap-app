<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings */
$all_styles = [];
$animation_class = '';

// Get review settings
$review_settings = $data->link->settings;

// Handle background color
if (isset($review_settings->background_color) && $review_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $review_settings->background_color . ' !important';
} else {
    $all_styles[] = 'background-color: #0000001A !important';
}

// Handle border
if (isset($review_settings->border_width) && $review_settings->border_width > 0) {
    $border_width = $review_settings->border_width;
    $border_color = $review_settings->border_color ?? '#dee2e6';
    $border_style = $review_settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
} else {
    $all_styles[] = 'border: 1px solid #dee2e6';
}

// Handle border radius - with backward compatibility
if (isset($review_settings->border_radius)) {
    if (is_numeric($review_settings->border_radius)) {
        // New system: use pixel value
        if ($review_settings->border_radius > 0) {
            $all_styles[] = 'border-radius: ' . $review_settings->border_radius . 'px';
        }
    } else {
        // Old system: convert string to pixels for backward compatibility
        switch ($review_settings->border_radius) {
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
} else {
    $all_styles[] = 'border-radius: 4px';
}

// Handle shadow
if (isset($review_settings->border_shadow_blur) && $review_settings->border_shadow_blur > 0) {
    $shadow_x = $review_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $review_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $review_settings->border_shadow_blur ?? 0;
    $shadow_spread = $review_settings->border_shadow_spread ?? 0;
    $shadow_color = $review_settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

// Handle animation
if (isset($review_settings->animation) && $review_settings->animation && $review_settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $review_settings->animation;
    if (isset($review_settings->animation_runs) && $review_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $review_settings->animation_runs;
    }
    if (isset($review_settings->animation_delay) && $review_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($review_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

$style_attribute = !empty($all_styles) ? 'style="' . implode('; ', $all_styles) . ';"' : '';

// Handle backward compatibility - convert old single review to array format
$reviews = [];
if (isset($review_settings->reviews)) {
    if (is_array($review_settings->reviews)) {
        $reviews = $review_settings->reviews;
    } else {
        // Convert object reviews to array format
        $reviews = [];
        foreach ($review_settings->reviews as $review) {
            if (is_object($review)) {
                $reviews[] = [
                    'title' => $review->title ?? '',
                    'description' => $review->description ?? '',
                    'author_name' => $review->author_name ?? '',
                    'author_description' => $review->author_description ?? '',
                    'stars' => $review->stars ?? 5,
                    'image' => $review->image ?? ''
                ];
            } else {
                $reviews[] = $review;
            }
        }
    }
} elseif (isset($review_settings->title) || isset($review_settings->author_name)) {
    // Convert old single review format to new array format
    $reviews = [[
        'title' => $review_settings->title ?? '',
        'description' => $review_settings->description ?? '',
        'author_name' => $review_settings->author_name ?? '',
        'author_description' => $review_settings->author_description ?? '',
        'stars' => $review_settings->stars ?? 5,
        'image' => $review_settings->image ?? ''
    ]];
}

if (empty($reviews)) {
    $reviews = [[
        'title' => '',
        'description' => '',
        'author_name' => 'Anonymous',
        'author_description' => '',
        'stars' => 5,
        'image' => ''
    ]];
}

// Ensure all reviews are arrays, not objects
foreach ($reviews as $key => $review) {
    if (is_object($review)) {
        $reviews[$key] = [
            'title' => $review->title ?? '',
            'description' => $review->description ?? '',
            'author_name' => $review->author_name ?? '',
            'author_description' => $review->author_description ?? '',
            'stars' => $review->stars ?? 5,
            'image' => $review->image ?? ''
        ];
    }
}

// Slider settings
$slider_mode = $review_settings->slider_mode ?? 'manual';
$auto_play = $review_settings->auto_play ?? false;
$slide_duration = $review_settings->slide_duration ?? 5;
$show_navigation = $review_settings->show_navigation ?? true;
$show_indicators = $review_settings->show_indicators ?? true;
$transition_effect = $review_settings->transition_effect ?? 'slide';

$unique_id = 'review_slider_' . $data->link->microsite_block_id;
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?> <?= $animation_class ?>">
    
    <!-- Reviews Slider using Splide (same as image slider) -->
    <section class="splide <?= 'splide_review_' . $data->link->microsite_block_id ?>">
        <div class="splide__track">
            <ul class="splide__list">
                <?php foreach($reviews as $key => $review): ?>
                    <li class="splide__slide">
                        <div class="card" <?= $style_attribute ?>>
                            <div class="card-body d-flex flex-column" style="text-align: <?= $review_settings->text_alignment ?? 'center' ?>;">
                                <?php if(!empty($review['title'])): ?>
                                    <span class="h6 mb-2 font-weight-bolder" style="color: <?= $review_settings->title_color ?? '#333333' ?>;"><?= htmlspecialchars($review['title']) ?></span>
                                <?php endif ?>

                                <div class="mb-3">
                                    <?php for($i = 1; $i <= ($review['stars'] ?? 5); $i++): ?>
                                    <i class="fas fa-fw fa-star mr-1" style="color: <?= $review_settings->stars_color ?? '#ffc107' ?>;"></i>
                                    <?php endfor ?>
                                </div>

                                <?php if(!empty($review['description'])): ?>
                                <span class="mb-4 font-size-small" style="color: <?= $review_settings->description_color ?? '#666666' ?>;"><?= nl2br(htmlspecialchars($review['description'])) ?></span>
                                <?php endif ?>

                                <?php
                                $justify_content_class = 'justify-content-center';
                                switch($review_settings->text_alignment ?? 'center') {
                                    case 'left':
                                    case 'justify':
                                        $justify_content_class = 'justify-content-start';
                                        break;
                                    case 'right':
                                        $justify_content_class = 'justify-content-end';
                                        break;
                                }
                                ?>
                                <div class="d-flex align-items-center <?= $justify_content_class ?>">
                                    <div class="mr-3">
                                        <?php if(!empty($review['image'])): ?>
                                        <img src="<?= \SeeGap\Uploads::get_full_url('block_images') . $review['image'] ?>" class="link-review-image" alt="<?= htmlspecialchars($review['author_name'] ?? 'Review Author') ?>" loading="lazy" />
                                        <?php else: ?>
                                            <div class="link-review-image"><?= mb_strtoupper(mb_substr($review['author_name'] ?? 'A', 0, 1)) ?></div>
                                        <?php endif ?>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold" style="color: <?= $review_settings->author_name_color ?? '#333333' ?>;"><?= htmlspecialchars($review['author_name'] ?? 'Anonymous') ?></span>
                                        <?php if(!empty($review['author_description'])): ?>
                                        <span class="small" style="color: <?= $review_settings->author_description_color ?? '#666666' ?>;"><?= htmlspecialchars($review['author_description']) ?></span>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </section>

</div>

<?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'review_slider')): ?>
    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/libraries/splide.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'head', 'review_slider') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/splide.min.js?v=' . PRODUCT_CODE ?>"></script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'review_slider') ?>
<?php endif ?>

<?php ob_start() ?>
<script>
    'use strict';
    document.addEventListener('DOMContentLoaded', () => {
        const splideConfig = {
            // Basic settings - handle transition type and infinite loop properly
            <?php if($transition_effect === 'fade'): ?>
            type: 'fade',
            rewind: true,
            <?php else: ?>
            type: 'loop',
            <?php endif ?>
            
            autoplay: <?= json_encode($auto_play) ?>,
            interval: <?= json_encode($slide_duration * 1000) ?>,
            arrows: <?= json_encode($show_navigation) ?>,
            pagination: <?= json_encode($show_indicators) ?>,
            
            // Review slider specific settings
            speed: 600,
            perPage: 1,
            gap: '0px',
            pauseOnHover: true,
            direction: '<?= l('direction') ?>',
            
            // Custom arrow icons using Font Awesome
            arrowPath: '',
            
            // Responsive behavior
            breakpoints: {
                768: {
                    perPage: 1,
                    gap: '0px',
                },
                480: {
                    perPage: 1,
                    gap: '0px',
                }
            }
        };
        
        let splide = new Splide('.<?= 'splide_review_' . $data->link->microsite_block_id ?>', splideConfig);
        
        // Custom Font Awesome arrows
        splide.on('mounted', function() {
            const prevArrow = document.querySelector('.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__arrow--prev');
            const nextArrow = document.querySelector('.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__arrow--next');
            
            if (prevArrow) {
                prevArrow.innerHTML = '<i class="fas fa-chevron-left"></i>';
            }
            if (nextArrow) {
                nextArrow.innerHTML = '<i class="fas fa-chevron-right"></i>';
            }
        });
        
        splide.mount();
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>

<style>
/* Custom styling for review slider to match image slider */
.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__arrow {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__arrow:hover {
    background: rgba(0, 0, 0, 0.8);
    opacity: 1;
}

.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__arrow i {
    color: white;
    font-size: 16px;
}

.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__pagination__page {
    background: rgba(0, 0, 0, 0.3);
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__pagination__page.is-active {
    background: <?= $review_settings->stars_color ?? '#ffc107' ?>;
    transform: scale(1.2);
}

.<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__pagination {
    bottom: -40px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__arrow {
        width: 35px;
        height: 35px;
    }
    
    .<?= 'splide_review_' . $data->link->microsite_block_id ?> .splide__arrow i {
        font-size: 14px;
    }
}
</style>

<?php
// Clean up the temporary template file
if (file_exists(__DIR__ . '/review_card_template.php')) {
    unlink(__DIR__ . '/review_card_template.php');
}
?>
