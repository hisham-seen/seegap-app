<?php defined('SEEGAP') || die() ?>

<?php
/* Generate styles for individual accordion items - not the container */
$accordion_settings = $data->link->settings;

// Generate individual card styles
$card_styles = [];

// Handle background color for individual cards
if (isset($accordion_settings->background_color) && $accordion_settings->background_color !== '#00000000') {
    $card_styles[] = 'background-color: ' . $accordion_settings->background_color;
}

// Handle border for individual cards
if (isset($accordion_settings->border_width) && $accordion_settings->border_width > 0) {
    $border_width = $accordion_settings->border_width;
    $border_color = $accordion_settings->border_color ?? '#ffffff';
    $border_style = $accordion_settings->border_style ?? 'solid';
    $card_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color . ' !important';
}

// Handle border radius - now using pixel values from slider
if (isset($accordion_settings->border_radius) && is_numeric($accordion_settings->border_radius) && $accordion_settings->border_radius > 0) {
    $card_styles[] = 'border-radius: ' . $accordion_settings->border_radius . 'px !important';
}

// Handle shadow for individual cards
if (isset($accordion_settings->border_shadow_blur) && $accordion_settings->border_shadow_blur > 0) {
    $shadow_x = $accordion_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $accordion_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $accordion_settings->border_shadow_blur ?? 0;
    $shadow_spread = $accordion_settings->border_shadow_spread ?? 0;
    $shadow_color = $accordion_settings->border_shadow_color ?? '#00000010';
    $card_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color . ' !important';
}

// Generate animation class for container
$animation_class = '';
if (isset($accordion_settings->animation) && $accordion_settings->animation && $accordion_settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $accordion_settings->animation;
    if (isset($accordion_settings->animation_runs) && $accordion_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $accordion_settings->animation_runs;
    }
    if (isset($accordion_settings->animation_delay) && $accordion_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($accordion_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

// Create style attribute for individual cards
$card_style_attribute = !empty($card_styles) ? 'style="' . implode('; ', $card_styles) . ';"' : '';

// Container only gets text alignment and transparent background
$container_styles = [
    'text-align: ' . ($accordion_settings->text_alignment ?? 'center'),
    'background-color: transparent' // Always transparent container
];
$container_style_attribute = 'style="' . implode('; ', $container_styles) . ';"';
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <?php if(isset($data->link->settings->items) && !empty($data->link->settings->items)): ?>
        <?php
        // Determine accordion behavior
        $accordion_mode = $accordion_settings->accordion_mode ?? 'single';
        $default_state = $accordion_settings->default_state ?? 'first_open';
        
        // Determine which items should be open by default
        $open_items = [];
        switch ($default_state) {
            case 'all_closed':
                // No items open
                break;
            case 'first_open':
                $open_items = [0]; // First item open
                break;
            case 'custom':
                // Use individual item settings
                foreach ($data->link->settings->items as $key => $item) {
                    if (isset($item->open_default) && $item->open_default) {
                        $open_items[] = $key;
                    }
                }
                break;
        }
        ?>
        
        <div class="accordion <?= $animation_class ?>" id="<?= 'accordion_' . $data->link->microsite_block_id ?>" <?= $container_style_attribute ?> data-accordion-mode="<?= $accordion_mode ?>">
            <?php foreach($data->link->settings->items as $key => $item): ?>
                <?php $is_open = in_array($key, $open_items); ?>
                <div class="card mb-2" <?= $card_style_attribute ?>>
                    <div class="card-header p-0" id="<?= 'accordion_' . $data->link->microsite_block_id . '_header_' . $key ?>">
                        <h2 class="mb-0">
                            <button 
                                class="btn btn-link btn-block text-left text-decoration-none p-3" 
                                type="button" 
                                data-toggle="collapse" 
                                data-target="#<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>" 
                                aria-expanded="<?= $is_open ? 'true' : 'false' ?>" 
                                aria-controls="<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>"
                                style="color: <?= $accordion_settings->text_color ?? '#333333' ?>; background-color: transparent; text-align: <?= $accordion_settings->text_alignment ?? 'center' ?>; border: none; outline: none; box-shadow: none;"
                            >
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($item->title ?? 'Accordion Item') ?></span>
                                    <i class="fas fa-chevron-down accordion-icon" style="transition: transform 0.2s ease;"></i>
                                </div>
                            </button>
                        </h2>
                    </div>

                    <div 
                        id="<?= 'accordion_' . $data->link->microsite_block_id . '_content_' . $key ?>" 
                        class="collapse <?= $is_open ? 'show' : '' ?>" 
                        aria-labelledby="<?= 'accordion_' . $data->link->microsite_block_id . '_header_' . $key ?>" 
                        <?= $accordion_mode === 'single' ? 'data-parent="#accordion_' . $data->link->microsite_block_id . '"' : '' ?>
                    >
                        <div class="card-body" style="color: <?= $accordion_settings->text_color ?? '#333333' ?>; text-align: <?= $accordion_settings->text_alignment ?? 'center' ?>; background-color: transparent;">
                            <?= $item->content ?? '' ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <!-- Empty state -->
        <div class="text-center py-4 px-3 <?= $animation_class ?>" <?= $container_style_attribute ?>>
            <i class="fas fa-list-ul fa-2x text-muted mb-2"></i>
            <p class="text-muted mb-0">No accordion items configured</p>
        </div>
    <?php endif ?>
</div>

<style>
/* Accordion icon rotation */
.accordion .card-header button[aria-expanded="true"] .accordion-icon {
    transform: rotate(180deg);
}

.accordion .card-header button[aria-expanded="false"] .accordion-icon {
    transform: rotate(0deg);
}

/* Hover effects */
.accordion .card-header button:hover {
    opacity: 0.8;
}

/* Remove default Bootstrap button focus styles */
.accordion .card-header button:focus {
    box-shadow: none !important;
    outline: none !important;
}

/* Ensure proper spacing for WYSIWYG content */
.accordion .card-body p {
    margin-bottom: 1rem;
}

.accordion .card-body p:last-child {
    margin-bottom: 0;
}

.accordion .card-body ul,
.accordion .card-body ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.accordion .card-body h1,
.accordion .card-body h2,
.accordion .card-body h3,
.accordion .card-body h4,
.accordion .card-body h5,
.accordion .card-body h6 {
    margin-bottom: 0.5rem;
    margin-top: 0;
}

.accordion .card-body a {
    color: inherit;
    text-decoration: underline;
}

.accordion .card-body a:hover {
    opacity: 0.8;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .accordion .card-header button {
        font-size: 0.9rem;
        padding: 1rem !important;
    }
    
    .accordion .card-body {
        padding: 1rem;
    }
}
</style>
