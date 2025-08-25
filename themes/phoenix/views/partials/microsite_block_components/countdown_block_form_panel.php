<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Countdown Block Form Panel
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object with default values
 * @param object $row - Block row data (for update) or mock object (for create)
 * @param string $form_type - 'create' or 'update'
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$form_type = $form_type ?? 'update';
$row = $row ?? (object)['microsite_block_id' => $block_id, 'settings' => $settings];

// Set up default settings for create form
if ($form_type === 'create') {
    $default_settings = (object) [
        'counter_end_date' => '',
        'style' => 'digital-led',
        'theme' => 'light',
        'text_color' => '#000000',
        'background_color' => '#ffffff',
        
        /* Animation settings */
        'animation' => false,
        'animation_runs' => 'repeat-1',
        'animation_delay' => 0,
        
        /* Display settings */
        'display_continents' => [],
        'display_countries' => [],
        'display_cities' => [],
        'display_devices' => [],
        'display_languages' => [],
        'display_operating_systems' => [],
        'display_browsers' => [],
    ];
    
    foreach ($default_settings as $key => $value) {
        if (!isset($settings->$key)) {
            $settings->$key = $value;
        }
    }
    $row->settings = $settings;
}

$unique_id = $form_type === 'create' ? 'create' : $row->microsite_block_id;

// Define primary tabs with new customization tab
$primary_tabs = [
    ['id' => 'content', 'title' => 'Content', 'icon' => 'fas fa-clock'],
    ['id' => 'customization', 'title' => 'Customization', 'icon' => 'fas fa-palette'],
    ['id' => 'end_timer', 'title' => 'End of Timer', 'icon' => 'fas fa-flag-checkered'],
    ['id' => 'display', 'title' => 'Display', 'icon' => 'fas fa-eye']
];
?>

<!-- Primary Tab Navigation -->
<?php
$block_id = 'countdown-' . $unique_id;
$tabs = $primary_tabs;
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="countdown-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="countdown-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="countdown-<?= $unique_id ?>-content-tab">
        
        <!-- Countdown Settings -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $collapsed = false;
        $show_date_picker = true;
        $show_style_selector = true;
        $show_theme_selector = true;
        $custom_styles = [];
        include THEME_PATH . 'views/partials/microsite_block_components/countdown_settings.php';
        ?>

    </div>

    <!-- Customization Tab -->
    <div class="tab-pane fade" id="countdown-<?= $unique_id ?>-customization" role="tabpanel" aria-labelledby="countdown-<?= $unique_id ?>-customization-tab">
        
        <?php
        // Define secondary tabs for the customization section
        $customization_tabs = [
            [
                'id' => 'background',
                'title' => 'Background',
                'icon' => 'fas fa-fill'
            ],
            [
                'id' => 'border',
                'title' => 'Border',
                'icon' => 'fas fa-border-style'
            ],
            [
                'id' => 'shadow',
                'title' => 'Shadow',
                'icon' => 'fas fa-clone'
            ],
            [
                'id' => 'animation',
                'title' => 'Animation',
                'icon' => 'fas fa-film'
            ]
        ];

        // Set the block_id for the secondary tab component
        $secondary_block_id = 'countdown-customization-' . $unique_id;
        $tabs = $customization_tabs; // Use customization tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="countdown-customization-<?= $unique_id ?>-tabContent">
            
            <!-- Background Sub-tab -->
            <div class="tab-pane fade show active" id="countdown-customization-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="countdown-customization-<?= $unique_id ?>-background-tab">
                <?php
                // Set up variables for background component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
                ?>
            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="countdown-customization-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="countdown-customization-<?= $unique_id ?>-border-tab">
                <?php
                // Set up variables for border component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="countdown-customization-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="countdown-customization-<?= $unique_id ?>-shadow-tab">
                <?php
                // Set up variables for shadow component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>
            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="countdown-customization-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="countdown-customization-<?= $unique_id ?>-animation-tab">
                <?php
                // Set up variables for animation component (without accordion)
                $component_block_id = $unique_id;
                $component_settings = $row->settings;
                
                // Include animation settings directly without accordion wrapper
                ?>
                <div class="form-group">
                    <label for="<?= 'animation_' . $component_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
                    <select id="<?= 'animation_' . $component_block_id ?>" name="animation" class="form-control" onchange="updateCanvasAnimation('<?= $component_block_id ?>')">
                        <option value="false" <?= (!isset($component_settings->animation) || !$component_settings->animation) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                        <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                            <option value="<?= $animation ?>" <?= (isset($component_settings->animation) && $component_settings->animation == $animation) ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="<?= 'animation_runs_' . $component_block_id ?>"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
                    <select id="<?= 'animation_runs_' . $component_block_id ?>" name="animation_runs" class="form-control" onchange="updateCanvasAnimation('<?= $component_block_id ?>')">
                        <option value="repeat-1" <?= (!isset($component_settings->animation_runs) || $component_settings->animation_runs == 'repeat-1') ? 'selected="selected"' : null ?>>1</option>
                        <option value="repeat-2" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-2') ? 'selected="selected"' : null ?>>2</option>
                        <option value="repeat-3" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-3') ? 'selected="selected"' : null ?>>3</option>
                        <option value="infinite" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'infinite') ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
                    </select>
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                    <label for="<?= 'animation_delay_' . $component_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_delay') ?></label>
                    <input id="<?= 'animation_delay_' . $component_block_id ?>" type="range" min="0" max="5000" step="100" class="form-control-range" name="animation_delay" value="<?= $component_settings->animation_delay ?? 0 ?>" required="required" onchange="updateCanvasAnimation('<?= $component_block_id ?>')" oninput="updateCanvasAnimation('<?= $component_block_id ?>')" />
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
            
            // Find the countdown element
            let element = microsite_link.find('.flipdown');
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
            </div>

        </div>

    </div>

    <!-- End of Timer Tab -->
    <div class="tab-pane fade" id="countdown-<?= $unique_id ?>-end_timer" role="tabpanel" aria-labelledby="countdown-<?= $unique_id ?>-end_timer-tab">
        
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $collapsed = false;
        $prefix = '';
        include THEME_PATH . 'views/partials/microsite_block_components/end_timer_settings.php';
        ?>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="countdown-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="countdown-<?= $unique_id ?>-display-tab">
        
        <?php
        $settings = $row->settings;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php';
        ?>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $unique_id ?>';
    
    // Date validation
    const datePicker = document.getElementById('countdown_counter_end_date_' + blockId);
    if (datePicker) {
        datePicker.addEventListener('change', function() {
            validateCountdownDate<?= $unique_id ?>(this);
        });
    }
    
    // End action settings toggle
    const endActionSelect = document.getElementById('end_action_' + blockId);
    if (endActionSelect) {
        endActionSelect.addEventListener('change', function() {
            toggleEndActionSettings<?= $unique_id ?>(this.value);
        });
        
        // Initialize on page load
        toggleEndActionSettings<?= $unique_id ?>(endActionSelect.value);
    }
    
    // File input label update
    const fileInputs = document.querySelectorAll('.custom-file-input');
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Choose file';
            const label = this.nextElementSibling;
            label.textContent = fileName;
        });
    });
});

function toggleEndActionSettings<?= $unique_id ?>(action) {
    const messageSettings = document.getElementById('end_message_settings_<?= $unique_id ?>');
    const redirectSettings = document.getElementById('end_redirect_settings_<?= $unique_id ?>');
    
    // Hide all settings first
    if (messageSettings) messageSettings.style.display = 'none';
    if (redirectSettings) redirectSettings.style.display = 'none';
    
    // Show relevant settings
    if (action === 'message' && messageSettings) {
        messageSettings.style.display = 'block';
    } else if (action === 'redirect' && redirectSettings) {
        redirectSettings.style.display = 'block';
    }
    // For 'hide' action, no additional settings are shown
}

function removeEndImage<?= $unique_id ?>() {
    if (confirm('Are you sure you want to remove this image?')) {
        // Add a hidden input to mark image for removal
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_end_image';
        hiddenInput.value = '1';
        form.appendChild(hiddenInput);
        
        // Hide the image preview
        const imageContainer = event.target.closest('.mt-2');
        if (imageContainer) {
            imageContainer.style.display = 'none';
        }
    }
}

function validateCountdownDate<?= $unique_id ?>(input) {
    const selectedDate = new Date(input.value);
    const currentDate = new Date();
    
    if (selectedDate <= currentDate) {
        input.classList.add('is-invalid');
        showCountdownDateError<?= $unique_id ?>(input, 'End date must be in the future');
    } else {
        input.classList.remove('is-invalid');
        hideCountdownDateError<?= $unique_id ?>(input);
    }
}

function showCountdownDateError<?= $unique_id ?>(input, message) {
    hideCountdownDateError<?= $unique_id ?>(input);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    errorDiv.setAttribute('data-countdown-error', 'true');
    
    input.parentNode.appendChild(errorDiv);
}

function hideCountdownDateError<?= $unique_id ?>(input) {
    const existingError = input.parentNode.querySelector('[data-countdown-error="true"]');
    if (existingError) {
        existingError.remove();
    }
}
</script>
