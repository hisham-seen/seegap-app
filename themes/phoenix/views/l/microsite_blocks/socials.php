<?php defined('SEEGAP') || die() ?>

<?php
// Determine icon size based on settings
$size = 'fa-2x';
switch ($data->link->settings->size ?? 'l') {
    case 's':
        $size = '';
        break;

    case 'm':
        $size = 'fa-lg';
        break;

    case 'l':
        $size = 'fa-2x';
        break;

    case 'xl':
        $size = 'fa-3x';
        break;
}

// Load microsite socials configuration
$microsite_socials = require APP_PATH . 'includes/microsite_socials.php';

// Check if socials data exists and is not empty
$has_socials = isset($data->link->settings->socials) && !empty((array)$data->link->settings->socials);

// Debug: If no socials data found, check if it's stored differently
if (!$has_socials) {
    // Sometimes the data might be stored directly in settings
    if (isset($data->link->settings) && is_object($data->link->settings)) {
        foreach ($data->link->settings as $key => $value) {
            if (isset($microsite_socials[$key]) && !empty(trim($value))) {
                $has_socials = true;
                // Create socials array if it doesn't exist
                if (!isset($data->link->settings->socials)) {
                    $data->link->settings->socials = new stdClass();
                }
                $data->link->settings->socials->$key = $value;
            }
        }
    }
}

// Additional debug: Check if data is stored as array instead of object
if (!$has_socials && isset($data->link->settings) && is_array($data->link->settings)) {
    foreach ($data->link->settings as $key => $value) {
        if (isset($microsite_socials[$key]) && !empty(trim($value))) {
            $has_socials = true;
            // Create socials array if it doesn't exist
            if (!isset($data->link->settings['socials'])) {
                $data->link->settings['socials'] = [];
            }
            $data->link->settings['socials'][$key] = $value;
        }
    }
    // Convert to object for consistency
    if ($has_socials && is_array($data->link->settings)) {
        $data->link->settings = (object) $data->link->settings;
        if (isset($data->link->settings->socials) && is_array($data->link->settings->socials)) {
            $data->link->settings->socials = (object) $data->link->settings->socials;
        }
    }
}

// Final fallback: Show debug info in development
if (!$has_socials && defined('DEVELOPMENT') && DEVELOPMENT) {
    // This will help us see what data structure we're actually getting
    error_log('Social block debug - Link settings: ' . print_r($data->link->settings, true));
    error_log('Social block debug - Available microsite socials: ' . print_r(array_keys($microsite_socials), true));
}
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <?php if($has_socials): ?>
        <div class="d-flex flex-wrap justify-content-center">
            <?php foreach($data->link->settings->socials as $key => $value): ?>
                <?php if(!empty(trim($value)) && isset($microsite_socials[$key])): ?>
                    <div class="my-2 mx-2 p-2 <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="background: <?= $data->link->settings->background_color ?? '#FFFFFF00' ?>" data-toggle="tooltip" title="<?= l('microsite_socials.' . $key . '.name') ?>" data-border-radius data-background-color>
                        <a href="<?= sprintf($microsite_socials[$key]['format'], $value) ?>" target="_blank" rel="noreferrer" class="<?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
                            <i class="<?= $microsite_socials[$key]['icon'] ?> <?= $size ?> fa-fw" style="color: <?= $data->link->settings->color ?? '#ffffff' ?>; min-width: 1.25em; text-align: center; display: inline-block;" data-color></i>
                        </a>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <!-- Minimalistic block-style empty state -->
        <div class="text-center py-4 px-3" style="border: 1px solid #e9ecef; border-radius: 4px; background-color: #f8f9fa;">
            <small class="text-muted">No social links configured</small>
        </div>
    <?php endif ?>
</div>

<style>
/* Ensure FontAwesome icons display properly even during loading */
.fa-fw {
    width: 1.25em !important;
    text-align: center;
    display: inline-block;
}

/* Fallback for icon loading issues */
i[class*="fa-"]:before {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 5 Free", "Font Awesome 5 Brands", sans-serif;
    font-weight: 900;
}

/* Ensure brand icons use the correct font weight */
.fab:before {
    font-family: "Font Awesome 6 Brands", "Font Awesome 5 Brands", sans-serif;
    font-weight: 400;
}

/* Prevent layout shift during icon loading */
#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"] {
    opacity: 0;
    transition: opacity 0.3s ease;
}

#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"].fa-loaded {
    opacity: 1;
}
</style>

<script>
// Ensure FontAwesome icons are visible once loaded
document.addEventListener('DOMContentLoaded', function() {
    function checkFontAwesome() {
        const icons = document.querySelectorAll('#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"]');
        
        // Check if FontAwesome is loaded by testing a known icon
        const testIcon = document.createElement('i');
        testIcon.className = 'fas fa-home';
        testIcon.style.position = 'absolute';
        testIcon.style.left = '-9999px';
        document.body.appendChild(testIcon);
        
        const computedStyle = window.getComputedStyle(testIcon, ':before');
        const fontFamily = computedStyle.getPropertyValue('font-family');
        
        document.body.removeChild(testIcon);
        
        // If FontAwesome is loaded or after timeout, show icons
        if (fontFamily.includes('Font Awesome') || fontFamily.includes('FontAwesome')) {
            icons.forEach(icon => icon.classList.add('fa-loaded'));
        } else {
            // Retry after a short delay
            setTimeout(checkFontAwesome, 100);
        }
    }
    
    // Start checking immediately and set a fallback timeout
    checkFontAwesome();
    setTimeout(() => {
        const icons = document.querySelectorAll('#microsite_block_id_<?= $data->link->microsite_block_id ?> i[class*="fa-"]');
        icons.forEach(icon => icon.classList.add('fa-loaded'));
    }, 2000); // Fallback after 2 seconds
});
</script>
