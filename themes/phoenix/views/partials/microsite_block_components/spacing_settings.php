<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Simple Spacing Settings Component for Microsite Blocks
 * Provides clean margin top and bottom controls only
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param array $spacing_types - Types of spacing to show (default: ['margin_top', 'margin_bottom'])
 * @param int $min_value - Minimum spacing value (default: 0)
 * @param int $max_value - Maximum spacing value (default: 7)
 * @param bool $collapsed - Whether the section should be collapsed by default (default: false)
 * @param bool $show_title - Whether to show the section title (default: true)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$spacing_types = $spacing_types ?? ['margin_top', 'margin_bottom'];
$min_value = $min_value ?? 0;
$max_value = $max_value ?? 7;
$collapsed = $collapsed ?? false;
$show_title = $show_title ?? true;

// Only support margin_top and margin_bottom
$spacing_types = array_intersect($spacing_types, ['margin_top', 'margin_bottom']);
?>

<?php if($collapsed): ?>
<div class="card mb-3">
    <div class="card-header" data-toggle="collapse" data-target="#spacing_settings_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
        <h6 class="mb-0">
            <i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-2"></i>
            <?= l('global.spacing') ?? 'Spacing' ?>
            <i class="fas fa-chevron-down float-right"></i>
        </h6>
    </div>
    <div id="spacing_settings_<?= $block_id ?>" class="collapse">
        <div class="card-body">
<?php else: ?>
    <?php if($show_title): ?>
    <div class="mb-4">
        <h6 class="text-muted mb-3">
            <i class="fas fa-fw fa-arrows-alt-v fa-sm mr-2"></i>
            <?= l('global.spacing') ?? 'Spacing' ?>
        </h6>
    </div>
    <?php endif ?>
<?php endif ?>

            <div class="row">
                <?php if(in_array('margin_top', $spacing_types)): ?>
                <!-- Margin Top -->
                <div class="col-md-6">
                    <div class="form-group" data-range-counter data-range-counter-suffix="">
                        <label for="<?= 'margin_top_' . $block_id ?>">
                            <i class="fas fa-fw fa-arrow-up fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_divider.margin_top') ?? 'Top Margin' ?>
                        </label>
                        <input 
                            id="<?= 'margin_top_' . $block_id ?>" 
                            type="range" 
                            min="<?= $min_value ?>" 
                            max="<?= $max_value ?>" 
                            step="1" 
                            class="form-control-range" 
                            name="margin_top" 
                            value="<?= $settings->margin_top ?? 0 ?>" 
                            required="required" 
                        />
                        <small class="form-text text-muted">
                            <?= l('microsite_divider.margin_top_help') ?? 'Space above the block' ?>
                        </small>
                    </div>
                </div>
                <?php endif ?>

                <?php if(in_array('margin_bottom', $spacing_types)): ?>
                <!-- Margin Bottom -->
                <div class="col-md-6">
                    <div class="form-group" data-range-counter data-range-counter-suffix="">
                        <label for="<?= 'margin_bottom_' . $block_id ?>">
                            <i class="fas fa-fw fa-arrow-down fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_divider.margin_bottom') ?? 'Bottom Margin' ?>
                        </label>
                        <input 
                            id="<?= 'margin_bottom_' . $block_id ?>" 
                            type="range" 
                            min="<?= $min_value ?>" 
                            max="<?= $max_value ?>" 
                            step="1" 
                            class="form-control-range" 
                            name="margin_bottom" 
                            value="<?= $settings->margin_bottom ?? 0 ?>" 
                            required="required" 
                        />
                        <small class="form-text text-muted">
                            <?= l('microsite_divider.margin_bottom_help') ?? 'Space below the block' ?>
                        </small>
                    </div>
                </div>
                <?php endif ?>
            </div>

<?php if($collapsed): ?>
        </div>
    </div>
</div>
<?php else: ?>
    <?php if($show_title): ?>
    </div>
    <?php endif ?>
<?php endif ?>

<style>
/* Simple Spacing Controls Styling */
.spacing-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.spacing-section h6 {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 8px;
    margin-bottom: 15px;
}

/* Enhanced Range Slider Styling */
.spacing-section .form-control-range,
.card-body .form-control-range {
    background: linear-gradient(to right, #007bff 0%, #007bff var(--range-progress, 0%), #e9ecef var(--range-progress, 0%), #e9ecef 100%);
    border-radius: 8px;
    height: 8px;
    outline: none;
    -webkit-appearance: none;
    transition: all 0.2s ease;
}

.spacing-section .form-control-range:hover,
.card-body .form-control-range:hover {
    box-shadow: 0 2px 8px rgba(0,123,255,0.3);
}

.spacing-section .form-control-range::-webkit-slider-thumb,
.card-body .form-control-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #007bff;
    cursor: pointer;
    border: 3px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: all 0.2s ease;
}

.spacing-section .form-control-range::-webkit-slider-thumb:hover,
.card-body .form-control-range::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 10px rgba(0,123,255,0.4);
}

.spacing-section .form-control-range::-moz-range-thumb,
.card-body .form-control-range::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #007bff;
    cursor: pointer;
    border: 3px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: all 0.2s ease;
}

/* Spacing Preview */
.spacing-preview {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 15px;
    margin-top: 15px;
}

.spacing-preview-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 120px;
}

.spacing-preview-block {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 200px;
}

.spacing-preview-content {
    background: #007bff;
    color: white;
    padding: 12px 20px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,123,255,0.2);
}

.spacing-preview-margin {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(255, 193, 7, 0.1);
    border: 1px dashed #ffc107;
    border-radius: 3px;
    transition: all 0.3s ease;
    min-height: 8px;
}

.spacing-preview-margin-top {
    margin-bottom: 5px;
}

.spacing-preview-margin-bottom {
    margin-top: 5px;
}

.spacing-preview-label {
    font-size: 11px;
    color: #856404;
    font-weight: 600;
    padding: 2px 6px;
    background: rgba(255, 193, 7, 0.2);
    border-radius: 2px;
}

/* Dynamic height based on value */
.spacing-preview-margin[data-value="0"] { height: 8px; }
.spacing-preview-margin[data-value="1"] { height: 12px; }
.spacing-preview-margin[data-value="2"] { height: 16px; }
.spacing-preview-margin[data-value="3"] { height: 20px; }
.spacing-preview-margin[data-value="4"] { height: 24px; }
.spacing-preview-margin[data-value="5"] { height: 28px; }
.spacing-preview-margin[data-value="6"] { height: 32px; }
.spacing-preview-margin[data-value="7"] { height: 36px; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .spacing-section {
        padding: 15px;
    }
    
    .spacing-preview-block {
        width: 150px;
    }
    
    .spacing-preview-content {
        padding: 10px 15px;
        font-size: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize simple spacing controls
    function initializeSimpleSpacingControls() {
        const blockId = '<?= $block_id ?>';
        const marginTopInput = document.getElementById('margin_top_' + blockId);
        const marginBottomInput = document.getElementById('margin_bottom_' + blockId);
        
        // Update range value styling
        function updateSpacingValue(input) {
            const value = input.value;
            const max = input.max;
            const percentage = (value / max) * 100;
            
            // Update CSS custom property for progress
            input.style.setProperty('--range-progress', percentage + '%');
        }
        
        // Initialize margin top
        if (marginTopInput) {
            updateSpacingValue(marginTopInput);
            
            marginTopInput.addEventListener('input', function() {
                updateSpacingValue(this);
            });
        }
        
        // Initialize margin bottom
        if (marginBottomInput) {
            updateSpacingValue(marginBottomInput);
            
            marginBottomInput.addEventListener('input', function() {
                updateSpacingValue(this);
            });
        }
        
        // Add keyboard support
        [marginTopInput, marginBottomInput].forEach(input => {
            if (input) {
                input.addEventListener('keydown', function(e) {
                    let step = 1;
                    
                    if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.value = Math.max(this.min, parseInt(this.value) - step);
                        this.dispatchEvent(new Event('input'));
                    } else if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.value = Math.min(this.max, parseInt(this.value) + step);
                        this.dispatchEvent(new Event('input'));
                    }
                });
            }
        });
    }
    
    // Initialize the controls
    initializeSimpleSpacingControls();
    
    // Reinitialize when new content is loaded (for dynamic content)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.querySelector && node.querySelector('[id^="margin_top_"], [id^="margin_bottom_"]')) {
                            setTimeout(initializeSimpleSpacingControls, 100);
                        }
                    }
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
</script>
