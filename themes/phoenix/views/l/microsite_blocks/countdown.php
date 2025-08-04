<?php defined('SEEGAP') || die() ?>

<?php
// Generate dynamic styles based on settings
$countdown_styles = [];

// Background color for container
if (isset($data->link->settings->background_color) && $data->link->settings->background_color !== '#ffffff' && $data->link->settings->background_color !== '#00000000') {
    $countdown_styles[] = 'background-color: ' . $data->link->settings->background_color;
    $countdown_styles[] = 'padding: 1rem';
    $countdown_styles[] = 'border-radius: 8px';
} else {
    $countdown_styles[] = 'background-color: #0000001A';
    $countdown_styles[] = 'padding: 1rem';
    $countdown_styles[] = 'border-radius: 8px';
}

// Animation classes
$animation_classes = '';
if (isset($data->link->settings->animation) && $data->link->settings->animation) {
    $animation_classes .= ' animate__animated animate__' . $data->link->settings->animation;
    
    if (isset($data->link->settings->animation_runs) && $data->link->settings->animation_runs !== 'repeat-1') {
        $animation_classes .= ' animate__' . $data->link->settings->animation_runs;
    }
    
    if (isset($data->link->settings->animation_delay) && $data->link->settings->animation_delay > 0) {
        $countdown_styles[] = 'animation-delay: ' . $data->link->settings->animation_delay . 's';
    }
}

$style_attribute = !empty($countdown_styles) ? 'style="' . implode('; ', $countdown_styles) . ';"' : '';

// No custom CSS - let the timer use default styling
$countdown_id = 'seegap_countdown_' . $data->link->microsite_block_id;
$custom_css = '';
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="d-flex align-items-center justify-content-center">
        <div id="<?= 'seegap_countdown_' . $data->link->microsite_block_id ?>" class="flipdown flipdown__theme-<?= $data->link->settings->theme ?? 'light' ?><?= $animation_classes ?>" <?= $style_attribute ?>></div>
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
