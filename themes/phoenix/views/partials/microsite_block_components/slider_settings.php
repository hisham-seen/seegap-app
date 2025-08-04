<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Slider Settings Component for Microsite Blocks
 * Provides comprehensive slider controls including autoplay, transitions, and behavior settings
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param bool $collapsed - Whether the section should be collapsed by default (default: false)
 * @param bool $show_autoplay - Whether to show autoplay controls (default: true)
 * @param bool $show_navigation - Whether to show navigation controls (default: true)
 * @param bool $show_visual_settings - Whether to show visual settings (default: true)
 * @param bool $show_behavior_settings - Whether to show behavior settings (default: true)
 * @param array $custom_transitions - Custom transition types (optional)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$collapsed = $collapsed ?? false;
$show_autoplay = $show_autoplay ?? true;
$show_navigation = $show_navigation ?? true;
$show_visual_settings = $show_visual_settings ?? true;
$show_behavior_settings = $show_behavior_settings ?? true;
$custom_transitions = $custom_transitions ?? [];

// Define transition types
$transition_types = [
    'slide' => l('microsite_image_slider.transition_type_slide') ?? 'Slide',
    'fade' => l('microsite_image_slider.transition_type_fade') ?? 'Fade',
    'loop' => l('microsite_image_slider.transition_type_loop') ?? 'Loop'
];

// Merge custom transitions if provided
if (!empty($custom_transitions)) {
    $transition_types = array_merge($transition_types, $custom_transitions);
}

// Define aspect ratios
$aspect_ratios = [
    'custom' => l('microsite_image_slider.aspect_ratio_custom') ?? 'Custom Height',
    '16:9' => l('microsite_image_slider.aspect_ratio_16_9') ?? '16:9 (Widescreen)',
    '4:3' => l('microsite_image_slider.aspect_ratio_4_3') ?? '4:3 (Standard)',
    '1:1' => l('microsite_image_slider.aspect_ratio_1_1') ?? '1:1 (Square)',
    '21:9' => l('microsite_image_slider.aspect_ratio_21_9') ?? '21:9 (Ultrawide)'
];

// Define image fit options
$image_fit_options = [
    'cover' => l('microsite_image_slider.image_fit_cover') ?? 'Cover (Fill & Crop)',
    'contain' => l('microsite_image_slider.image_fit_contain') ?? 'Contain (Fit Inside)',
    'fill' => l('microsite_image_slider.image_fit_fill') ?? 'Fill (Stretch)',
    'scale-down' => l('microsite_image_slider.image_fit_scale_down') ?? 'Scale Down'
];
?>

<?php if($collapsed): ?>
<div class="card mb-3">
    <div class="card-header" data-toggle="collapse" data-target="#slider_settings_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
        <h6 class="mb-0">
            <i class="fas fa-fw fa-sliders-h fa-sm text-muted mr-2"></i>
            <?= l('microsite_image_slider.slider_settings') ?? 'Slider Settings' ?>
            <i class="fas fa-chevron-down float-right"></i>
        </h6>
    </div>
    <div id="slider_settings_<?= $block_id ?>" class="collapse">
        <div class="card-body">
<?php endif ?>

            <?php if($show_autoplay): ?>
                <!-- Autoplay Settings -->
                <div class="form-group custom-control custom-switch">
                    <input
                        id="autoplay_<?= $block_id ?>"
                        name="autoplay" 
                        type="checkbox"
                        class="custom-control-input"
                        <?= ($settings->autoplay ?? true) ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="autoplay_<?= $block_id ?>">
                        <i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.autoplay') ?? 'Autoplay' ?>
                    </label>
                    <small class="form-text text-muted">
                        <?= l('microsite_image_slider.autoplay_help') ?? 'Automatically advance slides' ?>
                    </small>
                </div>

                <!-- Autoplay Interval -->
                <div class="form-group">
                    <label for="autoplay_interval_<?= $block_id ?>">
                        <i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.autoplay_interval') ?? 'Autoplay Interval' ?>
                    </label>
                    <div class="input-group">
                        <input 
                            id="autoplay_interval_<?= $block_id ?>" 
                            type="number" 
                            min="1" 
                            max="20" 
                            name="autoplay_interval" 
                            class="form-control" 
                            value="<?= $settings->autoplay_interval ?? 5 ?>" 
                        />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.seconds') ?? 'seconds' ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        <?= l('microsite_image_slider.autoplay_interval_help') ?? 'Time between slide transitions' ?>
                    </small>
                </div>
            <?php endif ?>

            <?php if($show_navigation): ?>
                <!-- Navigation Controls -->
                <div class="form-group custom-control custom-switch">
                    <input
                        id="display_arrows_<?= $block_id ?>"
                        name="display_arrows" 
                        type="checkbox"
                        class="custom-control-input"
                        <?= ($settings->display_arrows ?? true) ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="display_arrows_<?= $block_id ?>">
                        <i class="fas fa-fw fa-chevron-left fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.display_arrows') ?? 'Show Navigation Arrows' ?>
                    </label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input
                        id="display_pagination_<?= $block_id ?>"
                        name="display_pagination" 
                        type="checkbox"
                        class="custom-control-input"
                        <?= ($settings->display_pagination ?? true) ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="display_pagination_<?= $block_id ?>">
                        <i class="fas fa-fw fa-circle fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.display_pagination') ?? 'Show Pagination Dots' ?>
                    </label>
                </div>
            <?php endif ?>

            <?php if($show_visual_settings): ?>
                <!-- Visual Settings -->
                <hr class="my-4">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-fw fa-palette fa-sm mr-1"></i>
                    <?= l('microsite_image_slider.visual_settings') ?? 'Visual Settings' ?>
                </h6>

                <!-- Slider Height -->
                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="slider_height_<?= $block_id ?>">
                        <i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.slider_height') ?? 'Slider Height' ?>
                    </label>
                    <input 
                        id="slider_height_<?= $block_id ?>" 
                        type="range" 
                        min="200" 
                        max="800" 
                        step="10"
                        name="slider_height" 
                        class="form-control-range" 
                        value="<?= $settings->slider_height ?? 300 ?>" 
                    />
                </div>

                <!-- Aspect Ratio -->
                <div class="form-group">
                    <label for="aspect_ratio_<?= $block_id ?>">
                        <i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.aspect_ratio') ?? 'Aspect Ratio' ?>
                    </label>
                    <select id="aspect_ratio_<?= $block_id ?>" name="aspect_ratio" class="custom-select">
                        <?php foreach($aspect_ratios as $value => $label): ?>
                            <option value="<?= $value ?>" <?= ($settings->aspect_ratio ?? 'custom') == $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Image Fit -->
                <div class="form-group">
                    <label for="image_fit_<?= $block_id ?>">
                        <i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.image_fit') ?? 'Image Fit' ?>
                    </label>
                    <select id="image_fit_<?= $block_id ?>" name="image_fit" class="custom-select">
                        <?php foreach($image_fit_options as $value => $label): ?>
                            <option value="<?= $value ?>" <?= ($settings->image_fit ?? 'cover') == $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Border Radius -->
                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="border_radius_<?= $block_id ?>">
                        <i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.border_radius') ?? 'Border Radius' ?>
                    </label>
                    <input 
                        id="border_radius_<?= $block_id ?>" 
                        type="range" 
                        min="0" 
                        max="50" 
                        step="1"
                        name="border_radius" 
                        class="form-control-range" 
                        value="<?= $settings->border_radius ?? 0 ?>" 
                    />
                </div>
            <?php endif ?>

            <?php if($show_behavior_settings): ?>
                <!-- Behavior Settings -->
                <hr class="my-4">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-fw fa-cogs fa-sm mr-1"></i>
                    <?= l('microsite_image_slider.behavior_settings') ?? 'Behavior Settings' ?>
                </h6>

                <!-- Transition Type -->
                <div class="form-group">
                    <label for="transition_type_<?= $block_id ?>">
                        <i class="fas fa-fw fa-exchange-alt fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.transition_type') ?? 'Transition Type' ?>
                    </label>
                    <select id="transition_type_<?= $block_id ?>" name="transition_type" class="custom-select">
                        <?php foreach($transition_types as $value => $label): ?>
                            <option value="<?= $value ?>" <?= ($settings->transition_type ?? 'slide') == $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Transition Speed -->
                <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                    <label for="transition_speed_<?= $block_id ?>">
                        <i class="fas fa-fw fa-tachometer-alt fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.transition_speed') ?? 'Transition Speed' ?>
                    </label>
                    <input 
                        id="transition_speed_<?= $block_id ?>" 
                        type="range" 
                        min="200" 
                        max="2000" 
                        step="100"
                        name="transition_speed" 
                        class="form-control-range" 
                        value="<?= $settings->transition_speed ?? 600 ?>" 
                    />
                </div>

                <!-- Slides Per View -->
                <div class="form-group">
                    <label for="slides_per_view_<?= $block_id ?>">
                        <i class="fas fa-fw fa-th fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.slides_per_view') ?? 'Slides Per View' ?>
                    </label>
                    <select id="slides_per_view_<?= $block_id ?>" name="slides_per_view" class="custom-select">
                        <option value="1" <?= ($settings->slides_per_view ?? 1) == 1 ? 'selected' : '' ?>>1 Slide</option>
                        <option value="2" <?= ($settings->slides_per_view ?? 1) == 2 ? 'selected' : '' ?>>2 Slides</option>
                        <option value="3" <?= ($settings->slides_per_view ?? 1) == 3 ? 'selected' : '' ?>>3 Slides</option>
                        <option value="4" <?= ($settings->slides_per_view ?? 1) == 4 ? 'selected' : '' ?>>4 Slides</option>
                    </select>
                </div>

                <!-- Slide Gap -->
                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="slide_gap_<?= $block_id ?>">
                        <i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.slide_gap') ?? 'Slide Gap' ?>
                    </label>
                    <input 
                        id="slide_gap_<?= $block_id ?>" 
                        type="range" 
                        min="0" 
                        max="50" 
                        step="5"
                        name="slide_gap" 
                        class="form-control-range" 
                        value="<?= $settings->slide_gap ?? 0 ?>" 
                    />
                </div>

                <!-- Behavior Toggles -->
                <div class="form-group custom-control custom-switch">
                    <input
                        id="pause_on_hover_<?= $block_id ?>"
                        name="pause_on_hover" 
                        type="checkbox"
                        class="custom-control-input"
                        <?= ($settings->pause_on_hover ?? true) ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="pause_on_hover_<?= $block_id ?>">
                        <i class="fas fa-fw fa-pause fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.pause_on_hover') ?? 'Pause on Hover' ?>
                    </label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input
                        id="infinite_loop_<?= $block_id ?>"
                        name="infinite_loop" 
                        type="checkbox"
                        class="custom-control-input"
                        <?= ($settings->infinite_loop ?? true) ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="infinite_loop_<?= $block_id ?>">
                        <i class="fas fa-fw fa-sync fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_image_slider.infinite_loop') ?? 'Infinite Loop' ?>
                    </label>
                </div>
            <?php endif ?>

<?php if($collapsed): ?>
        </div>
    </div>
</div>
<?php endif ?>

<style>
.slider-settings .custom-control {
    margin-bottom: 1rem;
}

.slider-settings .custom-control-label {
    font-weight: 500;
}

.slider-settings .form-text {
    margin-top: 0.25rem;
}

.slider-settings .form-group:last-child {
    margin-bottom: 0;
}

.slider-settings hr {
    border-color: #dee2e6;
}

.slider-settings h6 {
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 576px) {
    .slider-settings .input-group {
        flex-direction: column;
    }
    
    .slider-settings .input-group-append {
        margin-left: 0;
        margin-top: 0.5rem;
    }
    
    .slider-settings .input-group-text {
        border-radius: 0.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $block_id ?>';
    
    // Autoplay dependency handling
    const autoplayCheckbox = document.getElementById('autoplay_' + blockId);
    const autoplayInterval = document.getElementById('autoplay_interval_' + blockId);
    
    if (autoplayCheckbox && autoplayInterval) {
        function toggleAutoplayInterval() {
            const intervalGroup = autoplayInterval.closest('.form-group');
            if (intervalGroup) {
                intervalGroup.style.display = autoplayCheckbox.checked ? 'block' : 'none';
            }
        }
        
        // Initial state
        toggleAutoplayInterval();
        
        // Handle changes
        autoplayCheckbox.addEventListener('change', toggleAutoplayInterval);
    }
    
    // Aspect ratio and height dependency
    const aspectRatioSelect = document.getElementById('aspect_ratio_' + blockId);
    const sliderHeight = document.getElementById('slider_height_' + blockId);
    
    if (aspectRatioSelect && sliderHeight) {
        function toggleHeightControl() {
            const heightGroup = sliderHeight.closest('.form-group');
            if (heightGroup) {
                heightGroup.style.display = aspectRatioSelect.value === 'custom' ? 'block' : 'none';
            }
        }
        
        // Initial state
        toggleHeightControl();
        
        // Handle changes
        aspectRatioSelect.addEventListener('change', toggleHeightControl);
    }
    
    // Slides per view and gap dependency
    const slidesPerViewSelect = document.getElementById('slides_per_view_' + blockId);
    const slideGap = document.getElementById('slide_gap_' + blockId);
    
    if (slidesPerViewSelect && slideGap) {
        function toggleGapControl() {
            const gapGroup = slideGap.closest('.form-group');
            if (gapGroup) {
                gapGroup.style.display = parseInt(slidesPerViewSelect.value) > 1 ? 'block' : 'none';
            }
        }
        
        // Initial state
        toggleGapControl();
        
        // Handle changes
        slidesPerViewSelect.addEventListener('change', toggleGapControl);
    }
});
</script>
