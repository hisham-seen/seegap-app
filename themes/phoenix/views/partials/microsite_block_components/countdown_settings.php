<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Countdown Settings Component for Microsite Blocks
 * Provides countdown-specific controls including date picker, style selector, and theme selector
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param bool $collapsed - Whether the section should be collapsed by default (default: false)
 * @param bool $show_date_picker - Whether to show the date picker (default: true)
 * @param bool $show_style_selector - Whether to show the style selector (default: true)
 * @param bool $show_theme_selector - Whether to show the theme selector (default: true)
 * @param array $custom_styles - Custom styles to include (optional)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$collapsed = $collapsed ?? false;
$show_date_picker = $show_date_picker ?? true;
$show_style_selector = $show_style_selector ?? true;
$show_theme_selector = $show_theme_selector ?? true;
$custom_styles = $custom_styles ?? [];

// Define countdown styles
$countdown_styles = [
    'Digital Styles' => [
        'digital-led' => 'LED Display',
        'digital-lcd' => 'LCD Display',
        'neon-style' => 'Neon Style',
        'matrix-style' => 'Matrix Style'
    ],
    'Analog/Visual Styles' => [
        'circular-progress' => 'Circular Progress',
        'gauge-style' => 'Gauge Style',
        'card-flip' => 'Card Flip',
        'slide-animation' => 'Slide Animation'
    ],
    'Modern Styles' => [
        'glassmorphism' => 'Glassmorphism',
        'neumorphism' => 'Neumorphism',
        'gradient' => 'Gradient',
        'minimalist' => 'Minimalist'
    ]
];

// Merge custom styles if provided
if (!empty($custom_styles)) {
    $countdown_styles = array_merge($countdown_styles, $custom_styles);
}

// Define theme options
$theme_options = [
    'light' => l('global.theme_style_light') ?? 'Light',
    'dark' => l('global.theme_style_dark') ?? 'Dark'
];
?>

<?php if($collapsed): ?>
<div class="card mb-3">
    <div class="card-header" data-toggle="collapse" data-target="#countdown_settings_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
        <h6 class="mb-0">
            <i class="fas fa-fw fa-clock fa-sm text-muted mr-2"></i>
            <?= l('microsite_countdown.settings') ?? 'Countdown Settings' ?>
            <i class="fas fa-chevron-down float-right"></i>
        </h6>
    </div>
    <div id="countdown_settings_<?= $block_id ?>" class="collapse">
        <div class="card-body">
<?php endif ?>

            <?php if($show_date_picker): ?>
                <!-- End Date Picker -->
                <div class="form-group">
                    <label for="countdown_counter_end_date_<?= $block_id ?>">
                        <i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_countdown.end_date') ?? 'End Date' ?>
                    </label>
                    <input
                        id="countdown_counter_end_date_<?= $block_id ?>"
                        type="text"
                        class="form-control countdown-date-picker"
                        name="counter_end_date"
                        value="<?= \SeeGap\Date::get($settings->counter_end_date ?? '', 1) ?>"
                        autocomplete="off"
                        data-daterangepicker
                        placeholder="<?= l('microsite_countdown.end_date_placeholder') ?? 'Select end date and time' ?>"
                        required
                    />
                    <small class="form-text text-muted">
                        <?= l('microsite_countdown.end_date_help') ?? 'Select the date and time when the countdown should end' ?>
                    </small>
                </div>
            <?php endif ?>

            <!-- Theme Selector -->
            <div class="form-group">
                <label for="countdown_theme_<?= $block_id ?>">
                    <i class="fas fa-fw fa-sun fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_countdown.theme') ?? 'Theme' ?>
                </label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-6">
                        <label class="btn btn-light btn-block text-truncate <?= ($settings->theme ?? 'light') == 'light' ? 'active' : null ?>">
                            <input 
                                type="radio" 
                                name="theme" 
                                value="light" 
                                class="custom-control-input countdown-theme-selector" 
                                <?= ($settings->theme ?? 'light') == 'light' ? 'checked="checked"' : null ?> 
                            />
                            <i class="fas fa-fw fa-sun fa-sm mr-1"></i>
                            <?= l('global.theme_style_light') ?? 'Light' ?>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="btn btn-light btn-block text-truncate <?= ($settings->theme ?? 'light') == 'dark' ? 'active' : null ?>">
                            <input 
                                type="radio" 
                                name="theme" 
                                value="dark" 
                                class="custom-control-input countdown-theme-selector" 
                                <?= ($settings->theme ?? 'light') == 'dark' ? 'checked="checked"' : null ?> 
                            />
                            <i class="fas fa-fw fa-moon fa-sm mr-1"></i>
                            <?= l('global.theme_style_dark') ?? 'Dark' ?>
                        </label>
                    </div>
                </div>
                <small class="form-text text-muted">
                    <?= l('microsite_countdown.theme_help') ?? 'Choose between light and dark theme for the countdown' ?>
                </small>
            </div>


<?php if($collapsed): ?>
        </div>
    </div>
</div>
<?php endif ?>

<style>
.countdown-style-preview {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    max-width: 289px;
    width: 289px;
    margin: 0 auto;
}

.preview-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.preview-countdown {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.seegap-countdown-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 60px;
}

.seegap-countdown-number {
    font-size: 1.5rem;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.seegap-countdown-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.8;
}

/* Style-specific previews - using same classes as public view */
.preview-countdown[data-style="digital-led"] .seegap-countdown-number {
    background: #000;
    color: #00ff00;
    padding: 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
}

.preview-countdown[data-style="digital-lcd"] .seegap-countdown-number {
    background: #2d3748;
    color: #00d4ff;
    padding: 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
}

.preview-countdown[data-style="neon-style"] .seegap-countdown-number {
    color: #ff006e;
    text-shadow: 0 0 10px #ff006e;
    font-weight: 900;
}

.preview-countdown[data-style="matrix-style"] .seegap-countdown-number {
    background: #000;
    color: #00ff41;
    padding: 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    text-shadow: 0 0 5px #00ff41;
}

.preview-countdown[data-style="circular-progress"] .seegap-countdown-unit {
    position: relative;
}

.preview-countdown[data-style="circular-progress"] .seegap-countdown-number {
    border: 3px solid #007bff;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.preview-countdown[data-style="card-flip"] .seegap-countdown-number {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-countdown[data-style="glassmorphism"] .seegap-countdown-number {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 0.5rem;
}

.preview-countdown[data-style="neumorphism"] .seegap-countdown-number {
    background: #e0e5ec;
    border-radius: 12px;
    padding: 0.5rem;
    box-shadow: 9px 9px 16px #a3b1c6, -9px -9px 16px #ffffff;
}

.preview-countdown[data-style="gradient"] .seegap-countdown-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    padding: 0.5rem;
}

.preview-countdown[data-style="minimalist"] .seegap-countdown-number {
    border-bottom: 2px solid #007bff;
    padding: 0.25rem 0;
    background: transparent;
}

.countdown-date-picker:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

@media (max-width: 576px) {
    .preview-countdown {
        gap: 0.5rem;
    }
    
    .seegap-countdown-unit {
        min-width: 50px;
    }
    
    .seegap-countdown-number {
        font-size: 1.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $block_id ?>';
    
    // Style selector change handler
    const styleSelector = document.getElementById('countdown_style_' + blockId);
    const preview = document.querySelector('#countdown_preview_' + blockId + ' .preview-countdown');
    
    if (styleSelector && preview) {
        styleSelector.addEventListener('change', function() {
            const selectedStyle = this.value;
            preview.setAttribute('data-style', selectedStyle);
            
            // Add animation effect
            preview.style.transform = 'scale(0.95)';
            preview.style.transition = 'transform 0.2s ease';
            
            setTimeout(() => {
                preview.style.transform = 'scale(1)';
            }, 100);
        });
    }
    
    // Theme selector change handler
    const themeSelectors = document.querySelectorAll('.countdown-theme-selector');
    
    themeSelectors.forEach(function(selector) {
        selector.addEventListener('change', function() {
            const selectedTheme = this.value;
            const previewContainer = document.querySelector('#countdown_preview_' + blockId);
            
            if (previewContainer) {
                previewContainer.setAttribute('data-theme', selectedTheme);
                
                // Update preview background based on theme
                if (selectedTheme === 'dark') {
                    previewContainer.style.background = '#2d3748';
                    previewContainer.style.color = '#fff';
                } else {
                    previewContainer.style.background = '#f8f9fa';
                    previewContainer.style.color = '#333';
                }
            }
        });
    });
    
    // Date picker validation
    const datePicker = document.getElementById('countdown_counter_end_date_' + blockId);
    
    if (datePicker) {
        datePicker.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const currentDate = new Date();
            
            if (selectedDate <= currentDate) {
                this.classList.add('is-invalid');
                showDateError(this, 'End date must be in the future');
            } else {
                this.classList.remove('is-invalid');
                hideDateError(this);
            }
        });
    }
    
    function showDateError(input, message) {
        hideDateError(input);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.setAttribute('data-countdown-error', 'true');
        
        input.parentNode.appendChild(errorDiv);
    }
    
    function hideDateError(input) {
        const existingError = input.parentNode.querySelector('[data-countdown-error="true"]');
        if (existingError) {
            existingError.remove();
        }
    }
});
</script>
