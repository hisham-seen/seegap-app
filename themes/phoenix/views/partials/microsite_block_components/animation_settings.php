<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Animation Settings Component for Microsite Blocks
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
?>
    <div class="form-group">
        <label for="<?= 'animation_' . $block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
        <select id="<?= 'animation_' . $block_id ?>" name="animation" class="form-control" onchange="updateCanvasAnimation('<?= $block_id ?>')">
            <option value="false" <?= (!isset($settings->animation) || !$settings->animation) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
            <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                <option value="<?= $animation ?>" <?= (isset($settings->animation) && $settings->animation == $animation) ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="form-group">
        <label for="<?= 'animation_runs_' . $block_id ?>"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
        <select id="<?= 'animation_runs_' . $block_id ?>" name="animation_runs" class="form-control" onchange="updateCanvasAnimation('<?= $block_id ?>')">
            <option value="repeat-1" <?= (!isset($settings->animation_runs) || $settings->animation_runs == 'repeat-1') ? 'selected="selected"' : null ?>>1</option>
            <option value="repeat-2" <?= (isset($settings->animation_runs) && $settings->animation_runs == 'repeat-2') ? 'selected="selected"' : null ?>>2</option>
            <option value="repeat-3" <?= (isset($settings->animation_runs) && $settings->animation_runs == 'repeat-3') ? 'selected="selected"' : null ?>>3</option>
            <option value="infinite" <?= (isset($settings->animation_runs) && $settings->animation_runs == 'infinite') ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
        </select>
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="ms">
        <label for="<?= 'animation_delay_' . $block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_delay') ?></label>
        <input id="<?= 'animation_delay_' . $block_id ?>" type="range" min="0" max="5000" step="100" class="form-control-range" name="animation_delay" value="<?= $settings->animation_delay ?? 0 ?>" required="required" onchange="updateCanvasAnimation('<?= $block_id ?>')" oninput="updateCanvasAnimation('<?= $block_id ?>')" />
    </div>

<script>
// Real-time canvas update function for animation properties
window.updateCanvasAnimation = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get animation values from the current form inputs with proper selectors
            const animation = $(`#animation_${blockId}`).val() || 'false';
            const runs = $(`#animation_runs_${blockId}`).val() || 'repeat-1';
            const delay = $(`#animation_delay_${blockId}`).val() || 0;
            
            // Find the element that gets animation classes - smart targeting for different block types
            let element = microsite_link.find('.card');
            if (!element.length) {
                element = microsite_link.find('.text-break, img, .btn, .form-control');
            }
            if (!element.length) {
                element = microsite_link; // fallback to the block itself
            }
            
            if (element.length) {
                // Remove all existing animate.css classes
                const animateClasses = [
                    'animate__animated', 'animate__bounce', 'animate__flash', 'animate__pulse', 
                    'animate__rubberBand', 'animate__shakeX', 'animate__shakeY', 'animate__headShake',
                    'animate__swing', 'animate__tada', 'animate__wobble', 'animate__jello',
                    'animate__heartBeat', 'animate__backInDown', 'animate__backInLeft',
                    'animate__backInRight', 'animate__backInUp', 'animate__bounceIn',
                    'animate__bounceInDown', 'animate__bounceInLeft', 'animate__bounceInRight',
                    'animate__bounceInUp', 'animate__fadeIn', 'animate__fadeInDown',
                    'animate__fadeInDownBig', 'animate__fadeInLeft', 'animate__fadeInLeftBig',
                    'animate__fadeInRight', 'animate__fadeInRightBig', 'animate__fadeInUp',
                    'animate__fadeInUpBig', 'animate__fadeInTopLeft', 'animate__fadeInTopRight',
                    'animate__fadeInBottomLeft', 'animate__fadeInBottomRight', 'animate__flip',
                    'animate__flipInX', 'animate__flipInY', 'animate__lightSpeedIn',
                    'animate__lightSpeedInRight', 'animate__lightSpeedInLeft', 'animate__rotateIn',
                    'animate__rotateInDownLeft', 'animate__rotateInDownRight', 'animate__rotateInUpLeft',
                    'animate__rotateInUpRight', 'animate__jackInTheBox', 'animate__rollIn',
                    'animate__zoomIn', 'animate__zoomInDown', 'animate__zoomInLeft',
                    'animate__zoomInRight', 'animate__zoomInUp', 'animate__slideInDown',
                    'animate__slideInLeft', 'animate__slideInRight', 'animate__slideInUp',
                    'animate__repeat-1', 'animate__repeat-2', 'animate__repeat-3', 'animate__infinite'
                ];
                
                element.removeClass(animateClasses.join(' '));
                
                if (animation !== 'false' && animation !== '') {
                    // Add new animation classes
                    element.addClass('animate__animated');
                    element.addClass(`animate__${animation}`);
                    
                    // Add repeat class
                    if (runs && runs !== 'repeat-1') {
                        element.addClass(`animate__${runs}`);
                    }
                    
                    // Apply delay - always set to ensure consistency
                    const delayMs = parseInt(delay) || 0;
                    element.css('animation-delay', `${delayMs}ms`);
                    
                    // Force animation restart by triggering reflow
                    element[0].offsetHeight; // trigger reflow
                    
                    // Remove and re-add animated class to restart animation
                    setTimeout(() => {
                        element.removeClass('animate__animated');
                        element[0].offsetHeight; // trigger reflow
                        setTimeout(() => {
                            element.addClass('animate__animated');
                        }, 50);
                    }, 50);
                }
            }
        }
    }
};
</script>
