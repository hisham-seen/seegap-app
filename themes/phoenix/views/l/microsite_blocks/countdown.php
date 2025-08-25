<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings */
$all_styles = [];
$animation_class = '';

// Get countdown settings
$countdown_settings = $data->link->settings;

// Handle background color
if (isset($countdown_settings->background_color) && $countdown_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $countdown_settings->background_color;
}

// Handle border
if (isset($countdown_settings->border_width) && $countdown_settings->border_width > 0) {
    $border_width = $countdown_settings->border_width;
    $border_color = $countdown_settings->border_color ?? '#ffffff';
    $border_style = $countdown_settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
}

// Handle border radius
if (isset($countdown_settings->border_radius)) {
    if (is_numeric($countdown_settings->border_radius)) {
        // New system: use pixel value
        if ($countdown_settings->border_radius > 0) {
            $all_styles[] = 'border-radius: ' . $countdown_settings->border_radius . 'px';
        }
    } else {
        // Old system: convert string to pixels for backward compatibility
        switch ($countdown_settings->border_radius) {
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
if (isset($countdown_settings->border_shadow_blur) && $countdown_settings->border_shadow_blur > 0) {
    $shadow_x = $countdown_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $countdown_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $countdown_settings->border_shadow_blur ?? 0;
    $shadow_spread = $countdown_settings->border_shadow_spread ?? 0;
    $shadow_color = $countdown_settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

// Handle animation
if (isset($countdown_settings->animation) && $countdown_settings->animation && $countdown_settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $countdown_settings->animation;
    if (isset($countdown_settings->animation_runs) && $countdown_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $countdown_settings->animation_runs;
    }
    if (isset($countdown_settings->animation_delay) && $countdown_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($countdown_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

// Add padding if we have styling
if (!empty($all_styles)) {
    $all_styles[] = 'padding: 1rem';
}

$style_attribute = !empty($all_styles) ? 'style="' . implode('; ', $all_styles) . ';"' : '';

// Determine if we need styling wrapper (has background, border, or shadow)
$has_styling = (
    (isset($countdown_settings->background_color) && $countdown_settings->background_color !== '#00000000') ||
    (isset($countdown_settings->border_width) && $countdown_settings->border_width > 0) ||
    (isset($countdown_settings->border_shadow_blur) && $countdown_settings->border_shadow_blur > 0)
);

$countdown_id = 'seegap_countdown_' . $data->link->microsite_block_id;

// Custom CSS for countdown styling - override flipdown.min.css
$custom_css = '
<style>
    .flipdown .rotor-group {
        padding-right: 9px !important;
    }
    .flipdown .rotor {
        font-size: 2.0rem !important;
    }
</style>
';
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="d-flex justify-content-center">
        <?php if ($has_styling): ?>
            <!-- Countdown with styling wrapper -->
            <div class="<?= $animation_class ?>" <?= $style_attribute ?> data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-background-color>
                <div id="<?= 'seegap_countdown_' . $data->link->microsite_block_id ?>" class="flipdown flipdown__theme-<?= $countdown_settings->theme ?? 'light' ?>"></div>
            </div>
        <?php else: ?>
            <!-- Countdown without styling wrapper -->
            <div id="<?= 'seegap_countdown_' . $data->link->microsite_block_id ?>" class="flipdown flipdown__theme-<?= $countdown_settings->theme ?? 'light' ?> <?= $animation_class ?>" <?= $style_attribute ?>></div>
        <?php endif ?>
    </div>
</div>

<?php if (!empty($custom_css)): ?>
    <?php ob_start() ?>
    <?= $custom_css ?>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'head') ?>
<?php endif ?>

<?php if(!\SeeGap\Event::exists_content_type_key('css', 'flipdown_countdown')): ?>
    <?php ob_start() ?>
    <link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/libraries/flipdown.min.css?v=' . PRODUCT_CODE ?>">
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'head', 'flipdown_countdown') ?>
<?php endif ?>

<?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'flipdown_countdown')): ?>
    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/flipdown.min.js?v=' . PRODUCT_CODE ?>"></script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'flipdown_countdown') ?>
<?php endif ?>

<?php ob_start() ?>
<script>
    'use strict';
    
    // Wait for both DOM and FlipDown library to be ready
    function initCountdown() {
        const countdownContainer = document.getElementById('<?= 'seegap_countdown_' . $data->link->microsite_block_id ?>');
        const endTimestamp = <?= (new DateTime($data->link->settings->counter_end_date))->getTimestamp() ?>;
        const theme = <?= json_encode($data->link->settings->theme ?? 'light') ?>;
        
        if (!countdownContainer) {
            console.error('Countdown container not found');
            return;
        }
        
        // Check if FlipDown is available
        if (typeof FlipDown === 'undefined') {
            console.log('FlipDown library not yet loaded, retrying...');
            setTimeout(initCountdown, 100);
            return;
        }
        
        try {
            console.log('Initializing FlipDown with timestamp:', endTimestamp, 'and theme:', theme);
            
            // Initialize FlipDown using the element ID string (not the element object)
            const flipdown = new FlipDown(endTimestamp, '<?= 'seegap_countdown_' . $data->link->microsite_block_id ?>', {
                headings: [
                    <?= json_encode(l('global.date.days')) ?>, 
                    <?= json_encode(l('global.date.hours')) ?>, 
                    <?= json_encode(l('global.date.minutes')) ?>, 
                    <?= json_encode(l('global.date.seconds')) ?>
                ]
            })
            
            // Start the countdown
            .start()
            
            // Handle countdown completion
            .ifEnded(() => {
                console.log('Countdown completed!');
                countdownContainer.innerHTML = '<div>Time\'s Up!</div>';
            });
            
            console.log('FlipDown initialized successfully');
            
        } catch (error) {
            console.error('FlipDown initialization error:', error);
            console.error('Error details:', error.message, error.stack);
            
            // Fallback to simple countdown
            countdownContainer.innerHTML = '<div>Initializing countdown...</div>';
            
            // Simple fallback countdown
            function updateSimpleCountdown() {
                const now = new Date().getTime();
                const distance = (endTimestamp * 1000) - now;
                
                if (distance < 0) {
                    countdownContainer.innerHTML = '<div>Time\'s Up!</div>';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                countdownContainer.innerHTML = `
                    <div style="display: flex; gap: 1rem; justify-content: center; font-family: monospace;">
                        <div style="text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold;">${String(days).padStart(2, '0')}</div>
                            <div style="font-size: 0.8rem;">Days</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold;">${String(hours).padStart(2, '0')}</div>
                            <div style="font-size: 0.8rem;">Hours</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold;">${String(minutes).padStart(2, '0')}</div>
                            <div style="font-size: 0.8rem;">Minutes</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold;">${String(seconds).padStart(2, '0')}</div>
                            <div style="font-size: 0.8rem;">Seconds</div>
                        </div>
                    </div>
                `;
            }
            
            updateSimpleCountdown();
            setInterval(updateSimpleCountdown, 1000);
        }
    }
    
    // Start initialization when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCountdown);
    } else {
        initCountdown();
    }
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
