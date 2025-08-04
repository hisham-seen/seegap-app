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

// Define primary tabs (removed style tab, added end of timer tab)
$primary_tabs = [
    ['id' => 'content', 'title' => 'Content', 'icon' => 'fas fa-clock'],
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
