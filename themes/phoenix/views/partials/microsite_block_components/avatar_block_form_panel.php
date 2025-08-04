<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Avatar Block Form Panel
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
        'image' => '',
        'image_alt' => '',
        'template' => 'classic',
        'avatar_shape' => 'circle',
        'size' => '100',
        'background_color' => '#ffffff',
        'cover_image' => '',
        'cover_position' => 'center',
        'cover_blur' => 0,
        'cover_overlay_color' => '#000000',
        'cover_overlay_opacity' => 0,
        'verified_badge' => (object)[
            'enabled' => false,
            'style' => 'checkmark',
            'position' => 'bottom_right',
            'size' => 'medium',
            'color' => '#1da1f2'
        ],
        'hover_effect' => 'none',
        'location_url' => '',
        'open_in_new_tab' => false,
        'text_alignment' => 'center',
        'margin_top' => 0,
        'margin_bottom' => 0,
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

// Define primary tabs
$primary_tabs = [
    ['id' => 'content', 'title' => 'Content', 'icon' => 'fas fa-edit'],
    ['id' => 'style', 'title' => 'Style', 'icon' => 'fas fa-palette'],
    ['id' => 'badge', 'title' => 'Badge', 'icon' => 'fas fa-certificate'],
    ['id' => 'display', 'title' => 'Display', 'icon' => 'fas fa-eye'],
    ['id' => 'destination', 'title' => 'Destination', 'icon' => 'fas fa-link']
];
?>

<!-- Primary Tab Navigation -->
<?php
$block_id = 'avatar-' . $unique_id;
$tabs = $primary_tabs;
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="avatar-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="avatar-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="avatar-<?= $unique_id ?>-content-tab">
        
        <!-- Intelligent Templates & Shape Selection -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $block_type = 'avatar';
        include THEME_PATH . 'views/partials/microsite_block_components/templates.php';
        ?>

        <!-- Advanced Image Upload -->
        <?php
        $block_id = $unique_id;
        $field_name = 'image';
        $current_image = $row->settings->image ?? '';
        $accept_types = $data->microsite_blocks['avatar']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $label = l('microsite_avatar.image');
        $icon = 'fas fa-image';
        $uploads_file_key = 'avatars';
        $size_limit_setting = settings()->links->avatar_size_limit;
        $enable_crop = true;
        include THEME_PATH . 'views/partials/microsite_block_components/advanced_image_upload.php';
        ?>

        <!-- Background Color for Transparent Images -->
        <div class="form-group" id="background-color-container-<?= $unique_id ?>" style="display: <?= (!empty($row->settings->image) && (pathinfo($row->settings->image, PATHINFO_EXTENSION) == 'png' || pathinfo($row->settings->image, PATHINFO_EXTENSION) == 'svg')) ? 'block' : 'none' ?>;">
            <label for="<?= 'background_color_' . $unique_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.background_color') ?></label>
            <input id="<?= 'background_color_' . $unique_id ?>" type="color" class="form-control" name="background_color" value="<?= $row->settings->background_color ?? '#ffffff' ?>" />
            <small class="form-text text-muted"><?= l('microsite_avatar.background_color_help') ?></small>
        </div>

        <!-- Alt Text -->
        <div class="form-group">
            <label for="<?= 'avatar_image_alt_' . $unique_id ?>"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.image_alt') ?></label>
            <input id="<?= 'avatar_image_alt_' . $unique_id ?>" type="text" class="form-control" name="image_alt" value="<?= $row->settings->image_alt ?? '' ?>" maxlength="100" />
            <small class="form-text text-muted"><?= l('microsite_avatar.image_alt_help') ?></small>
        </div>

        <!-- Avatar Size Slider -->
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'avatar_size_' . $unique_id ?>"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.size') ?></label>
            <input id="<?= 'avatar_size_' . $unique_id ?>" type="range" min="60" max="200" class="form-control-range" name="size" value="<?= $row->settings->size ?? 100 ?>" />
            <small class="form-text text-muted"><?= l('microsite_avatar.size_help') ?></small>
        </div>

        <!-- Spacing Controls -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $spacing_types = ['margin_top', 'margin_bottom'];
        $min_value = 0;
        $max_value = 7;
        $collapsed = false;
        include THEME_PATH . 'views/partials/microsite_block_components/spacing_settings.php';
        ?>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="avatar-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="avatar-<?= $unique_id ?>-style-tab">
        
        <?php
        // Define secondary tabs for the style section
        $style_tabs = [
            [
                'id' => 'cover',
                'title' => 'Cover',
                'icon' => 'fas fa-image'
            ],
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
                'id' => 'alignment',
                'title' => 'Alignment',
                'icon' => 'fas fa-align-center'
            ],
            [
                'id' => 'shadow',
                'title' => 'Shadow',
                'icon' => 'fas fa-clone'
            ],
            [
                'id' => 'animation',
                'title' => 'Animation',
                'icon' => 'fas fa-magic'
            ]
        ];

        // Set the block_id for the secondary tab component
        $secondary_block_id = 'avatar-style-' . $unique_id;
        $tabs = $style_tabs; // Use style tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="avatar-style-<?= $unique_id ?>-tabContent">
            
            <!-- Cover Sub-tab -->
            <div class="tab-pane fade show active" id="avatar-style-<?= $unique_id ?>-cover" role="tabpanel" aria-labelledby="avatar-style-<?= $unique_id ?>-cover-tab">
                
                <!-- Cover Image Settings -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/cover_image_settings.php';
                ?>

            </div>
            
            <!-- Background Sub-tab -->
            <div class="tab-pane fade" id="avatar-style-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="avatar-style-<?= $unique_id ?>-background-tab">
                
                <!-- Background Settings -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $field_name = 'block_background_color'; // Use different field name for block background
                include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
                ?>

            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="avatar-style-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="avatar-style-<?= $unique_id ?>-border-tab">
                
                <!-- Border Settings -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>

            </div>

            <!-- Alignment Sub-tab -->
            <div class="tab-pane fade" id="avatar-style-<?= $unique_id ?>-alignment" role="tabpanel" aria-labelledby="avatar-style-<?= $unique_id ?>-alignment-tab">
                
                <!-- Text Alignment -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $field_name = 'text_alignment';
                $default_alignment = 'center';
                include THEME_PATH . 'views/partials/microsite_block_components/alignment.php';
                ?>

            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="avatar-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="avatar-style-<?= $unique_id ?>-shadow-tab">
                
                <!-- Shadow Settings -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>

            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="avatar-style-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="avatar-style-<?= $unique_id ?>-animation-tab">
                
                <!-- Animation Settings -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/animation_settings.php';
                ?>

            </div>

        </div>

    </div>

    <!-- Badge Tab -->
    <div class="tab-pane fade" id="avatar-<?= $unique_id ?>-badge" role="tabpanel" aria-labelledby="avatar-<?= $unique_id ?>-badge-tab">
        
        <!-- Verified Badge Settings using shared component -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $field_prefix = 'verified_badge';
        include THEME_PATH . 'views/partials/microsite_block_components/badge_selector.php';
        ?>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="avatar-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="avatar-<?= $unique_id ?>-display-tab">
        
        <?php 
        $block_id = $unique_id;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; 
        ?>

    </div>

    <!-- Destination Tab -->
    <div class="tab-pane fade" id="avatar-<?= $unique_id ?>-destination" role="tabpanel" aria-labelledby="avatar-<?= $unique_id ?>-destination-tab">
        
        <!-- Link Settings -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        include THEME_PATH . 'views/partials/microsite_block_components/link_settings.php';
        ?>

    </div>

</div>

<style>
/* Shape Selection Styles */
.shape-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.shape-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}


.shape-option input:checked + .shape-preview,
.shape-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.shape-demo {
    width: 40px;
    height: 40px;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.circle-shape .shape-demo {
    border-radius: 50%;
}

.square-shape .shape-demo {
    border-radius: 8px;
}

.shape-preview small {
    font-weight: 500;
    color: #495057;
}

/* Size Selection Styles */
.size-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.size-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px 10px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}


.size-option input:checked + .size-preview,
.size-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.size-circle {
    border-radius: 50%;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.size-80 .size-circle { width: 20px; height: 20px; }
.size-100 .size-circle { width: 25px; height: 25px; }
.size-120 .size-circle { width: 30px; height: 30px; }
.size-140 .size-circle { width: 35px; height: 35px; }

.size-preview small {
    font-weight: 500;
    color: #495057;
    line-height: 1.2;
}

/* Badge Style Selection */
.badge-style-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.badge-style-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px 10px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}


.badge-style-option input:checked + .badge-style-preview,
.badge-style-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.badge-style-preview i {
    font-size: 1.5rem;
    margin-bottom: 5px;
    color: #007bff;
}

.badge-style-preview small {
    font-weight: 500;
    color: #495057;
}

/* Mobile Optimizations */
@media (max-width: 576px) {
    .shape-preview,
    .size-preview,
    .badge-style-preview {
        padding: 10px 5px;
    }
    
    .shape-demo {
        width: 30px;
        height: 30px;
    }
    
    .badge-style-preview i {
        font-size: 1.2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uniqueId = '<?= $unique_id ?>';
    
    // Shape selection handling
    const shapeOptions = document.querySelectorAll('input[name="avatar_shape"]');
    const shapePreviews = document.querySelectorAll('.shape-preview');
    const squareBorderRadiusContainer = document.getElementById('square-border-radius-container');
    
    // Add click handlers to shape previews
    shapePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="avatar_shape"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all shape previews
                shapePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected shape
                this.classList.add('active');
                
                // Show/hide square border radius controls
                if (radioInput.value === 'square' && squareBorderRadiusContainer) {
                    squareBorderRadiusContainer.style.display = 'block';
                } else if (squareBorderRadiusContainer) {
                    squareBorderRadiusContainer.style.display = 'none';
                }
            }
        });
    });
    
    // Also handle radio button changes directly
    shapeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all shape previews
            shapePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected shape
            const selectedPreview = this.parentElement.querySelector('.shape-preview');
            if (selectedPreview) {
                selectedPreview.classList.add('active');
            }
            
            // Show/hide square border radius controls
            if (this.value === 'square' && squareBorderRadiusContainer) {
                squareBorderRadiusContainer.style.display = 'block';
            } else if (squareBorderRadiusContainer) {
                squareBorderRadiusContainer.style.display = 'none';
            }
        });
    });

    // Size selection handling
    const sizeOptions = document.querySelectorAll('input[name="size"]');
    const sizePreviews = document.querySelectorAll('.size-preview');
    
    // Add click handlers to size previews
    sizePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="size"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all size previews
                sizePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected size
                this.classList.add('active');
            }
        });
    });
    
    // Also handle radio button changes directly
    sizeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all size previews
            sizePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected size
            const selectedPreview = this.parentElement.querySelector('.size-preview');
            if (selectedPreview) {
                selectedPreview.classList.add('active');
            }
        });
    });
    
    // Badge style selection handling
    const badgeStyleOptions = document.querySelectorAll('input[name="verified_badge_style"]');
    const badgeStylePreviews = document.querySelectorAll('.badge-style-preview');
    
    // Add click handlers to badge style previews
    badgeStylePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="verified_badge_style"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all badge style previews
                badgeStylePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected badge style
                this.classList.add('active');
            }
        });
    });
    
    // Also handle radio button changes directly
    badgeStyleOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all badge style previews
            badgeStylePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected badge style
            const selectedPreview = this.parentElement.querySelector('.badge-style-preview');
            if (selectedPreview) {
                selectedPreview.classList.add('active');
            }
        });
    });

    // File upload handling for background color detection
    const fileInput = document.getElementById('image_' + uniqueId);
    const backgroundColorContainer = document.getElementById('background-color-container-' + uniqueId);
    
    if (fileInput && backgroundColorContainer) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const fileName = file.name.toLowerCase();
                const fileExtension = fileName.split('.').pop();
                
                // Show background color picker for transparent image formats
                if (fileExtension === 'png' || fileExtension === 'svg') {
                    backgroundColorContainer.style.display = 'block';
                } else {
                    backgroundColorContainer.style.display = 'none';
                }
            } else {
                // Hide background color picker when no file is selected
                backgroundColorContainer.style.display = 'none';
            }
        });
    }
});
</script>
