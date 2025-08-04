<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Social Links Block Form Panel
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
        'socials' => (object)[],
        'color' => '#333333',
        'background_color' => '#ffffff',
        'border_radius' => 'rounded',
        'size' => 24,
        'animation' => false,
        'animation_runs' => 'repeat-1',
        'animation_delay' => 0
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
    ['id' => 'display', 'title' => 'Display', 'icon' => 'fas fa-eye']
];
?>

<!-- Primary Tab Navigation -->
<?php
$block_id = 'socials-' . $unique_id;
$tabs = $primary_tabs;
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="socials-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="socials-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="socials-<?= $unique_id ?>-content-tab">
        
        <!-- Dynamic Social Media Manager -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $container_id = 'social_media_container_' . $unique_id;
        $max_platforms = 20;
        $field_prefix = 'socials';
        include THEME_PATH . 'views/partials/microsite_block_components/social_media_manager.php';
        ?>

        <!-- Icon Size Slider -->
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'socials_size_' . $unique_id ?>"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_socials.size') ?></label>
            <input id="<?= 'socials_size_' . $unique_id ?>" type="range" min="10" max="60" class="form-control-range" name="size" value="<?= $row->settings->size ?? 24 ?>" />
            <small class="form-text text-muted"><?= l('microsite_socials.size_help') ?? 'Adjust the social icon size from 10px to 60px using the slider' ?></small>
        </div>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="socials-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="socials-<?= $unique_id ?>-style-tab">
        
        <?php
        // Define secondary tabs for the style section
        $style_tabs = [
            [
                'id' => 'templates',
                'title' => 'Templates',
                'icon' => 'fas fa-layer-group'
            ],
            [
                'id' => 'icon-styling',
                'title' => 'Icon',
                'icon' => 'fas fa-palette'
            ],
            [
                'id' => 'background',
                'title' => 'Background',
                'icon' => 'fas fa-fill'
            ],
            [
                'id' => 'spacing',
                'title' => 'Spacing',
                'icon' => 'fas fa-arrows-alt'
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
            ]
        ];

        // Set the block_id for the secondary tab component
        $secondary_block_id = 'socials-style-' . $unique_id;
        $tabs = $style_tabs; // Use style tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="socials-style-<?= $unique_id ?>-tabContent">
            
            <!-- Templates Sub-tab -->
            <div class="tab-pane fade show active" id="socials-style-<?= $unique_id ?>-templates" role="tabpanel" aria-labelledby="socials-style-<?= $unique_id ?>-templates-tab">
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $block_type = 'socials';
                include THEME_PATH . 'views/partials/microsite_block_components/templates.php';
                ?>
            </div>

            <!-- Icon Styling Sub-tab -->
            <div class="tab-pane fade" id="socials-style-<?= $unique_id ?>-icon-styling" role="tabpanel" aria-labelledby="socials-style-<?= $unique_id ?>-icon-styling-tab">
                <?php
                // Set up variables for shared components
                $block_id = $unique_id;
                $component_settings = $row->settings;
                
                // Icon Color
                $field_name = 'color';
                $label = l('microsite_socials.color');
                $icon = 'fas fa-paint-brush';
                $default_color = '#333333';
                $current_color = $component_settings->color ?? $default_color;
                $include_opacity = false;
                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>
            </div>

            <!-- Background Sub-tab -->
            <div class="tab-pane fade" id="socials-style-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="socials-style-<?= $unique_id ?>-background-tab">
                <?php
                // Set up variables for background component
                $block_id = $unique_id;
                $component_settings = $row->settings;
                $field_name = 'background_color';
                
                // Include background color picker directly
                $bg_field_name = $field_name;
                $bg_label = l('microsite_link.background_color');
                $bg_icon = 'fas fa-fill';
                $bg_default = '#ffffff';
                $bg_current = $component_settings->$field_name ?? $bg_default;
                $bg_include_opacity = true; // Enable opacity for background colors

                // Set variables for color picker component
                $field_name = $bg_field_name;
                $label = $bg_label;
                $icon = $bg_icon;
                $default_color = $bg_default;
                $current_color = $bg_current;
                $include_opacity = $bg_include_opacity;

                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>
            </div>

            <!-- Spacing Sub-tab -->
            <div class="tab-pane fade" id="socials-style-<?= $unique_id ?>-spacing" role="tabpanel" aria-labelledby="socials-style-<?= $unique_id ?>-spacing-tab">
                <?php
                // Set up variables for spacing component
                $block_id = $unique_id;
                $settings = $row->settings;
                $spacing_types = [
                    'margin_top', 'margin_bottom', 'margin_left', 'margin_right',
                    'padding_top', 'padding_bottom', 'padding_left', 'padding_right',
                    'internal_padding', 'element_spacing', 'block_gap'
                ];
                $min_value = 0;
                $max_value = 10;
                $collapsed = false; // Don't use collapsed mode when in tabs
                $show_presets = true;
                $grouped_layout = true;
                
                include THEME_PATH . 'views/partials/microsite_block_components/spacing_settings.php';
                ?>
            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="socials-style-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="socials-style-<?= $unique_id ?>-border-tab">
                <?php
                // Set up variables for border component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="socials-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="socials-style-<?= $unique_id ?>-shadow-tab">
                <?php
                // Set up variables for shadow component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>
            </div>

        </div>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="socials-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="socials-<?= $unique_id ?>-display-tab">
        
        <?php include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; ?>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uniqueId = '<?= $unique_id ?>';
    
    // Initialize any additional JavaScript functionality specific to socials block
    console.log('Socials block form panel initialized for: ' + uniqueId);
});
</script>
