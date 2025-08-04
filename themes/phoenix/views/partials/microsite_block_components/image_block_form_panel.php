<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Image Block Form Panel
 * 
 * This component provides the complete form structure for image blocks,
 * including primary tabs (Content, Style, Display) and all form functionality.
 * 
 * @param string $block_id - Unique identifier for the block (e.g., 'create' or actual block ID)
 * @param object $settings - Block settings object with default values
 * @param object $row - Block row data (for update form) or mock object (for create modal)
 * @param string $form_type - 'create' or 'update' to determine form behavior
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
        'image_height' => '',
        'image_height_unit' => 'px',
        'image_width' => '',
        'image_width_unit' => 'px',
        'location_url' => '',
        'open_in_new_tab' => false,
        'text_alignment' => 'center',
        'background_color' => '#00000000',
        'border_width' => 0,
        'border_color' => '#ffffff',
        'border_radius' => 'rounded',
        'border_style' => 'solid',
        'border_shadow_offset_x' => 0,
        'border_shadow_offset_y' => 0,
        'border_shadow_blur' => 0,
        'border_shadow_spread' => 0,
        'border_shadow_color' => '#00000010',
        'animation' => false,
        'animation_runs' => 'repeat-1',
        'animation_delay' => 0,
        'verified_badge' => (object) [
            'enabled' => false,
            'style' => 'checkmark',
            'position' => 'bottom_right',
            'size' => 'medium',
            'color' => '#1da1f2'
        ]
    ];
    
    // Merge with any provided settings
    foreach ($default_settings as $key => $value) {
        if (!isset($settings->$key)) {
            $settings->$key = $value;
        }
    }
    $row->settings = $settings;
}

// Generate unique IDs based on block_id
$unique_id = $form_type === 'create' ? 'create' : $row->microsite_block_id;
?>

<?php
// Define tabs for the image block
$tabs = [
    [
        'id' => 'content',
        'title' => 'Content',
        'icon' => 'fas fa-edit'
    ],
    [
        'id' => 'style',
        'title' => 'Style',
        'icon' => 'fas fa-palette'
    ],
    [
        'id' => 'badge',
        'title' => 'Badge',
        'icon' => 'fas fa-certificate'
    ],
    [
        'id' => 'display',
        'title' => 'Display',
        'icon' => 'fas fa-eye'
    ],
    [
        'id' => 'destination',
        'title' => 'Destination',
        'icon' => 'fas fa-link'
    ]
];

// Set the block_id for the tab component
$primary_tab_block_id = 'image-' . $unique_id;
$primary_tabs = $tabs; // Store primary tabs

// Temporarily set variables for primary tabs
$block_id = $primary_tab_block_id;
$tabs = $primary_tabs;

// Include the reusable tab navigation
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="image-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="image-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-content-tab">
        
        <?php if ($form_type === 'create'): ?>
            <!-- Simple Image Upload for Create Modal -->
            <div class="form-group">
                <label for="<?= 'image_image_' . $unique_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
                <input id="<?= 'image_image_' . $unique_id ?>" type="file" name="image" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']) ?>" class="form-control-file seegap-file-input" required="required" data-crop />
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
            </div>
        <?php else: ?>
            <!-- Advanced Image Upload for Update Form -->
            <?php
            $block_id = $unique_id;
            $field_name = 'image';
            $current_image = $row->settings->image ?? '';
            $accept_types = $data->microsite_blocks['image']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'];
            $label = l('global.image');
            $icon = 'fas fa-image';
            $uploads_file_key = 'block_images';
            $size_limit_setting = settings()->links->image_size_limit;
            $enable_crop = true;
            include THEME_PATH . 'views/partials/microsite_block_components/advanced_image_upload.php';
            ?>
        <?php endif; ?>

        <!-- Image Alt Text -->
        <div class="form-group">
            <label for="<?= 'image_image_alt_' . $unique_id ?>"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_alt') ?></label>
            <input id="<?= 'image_image_alt_' . $unique_id ?>" type="text" class="form-control" name="image_alt" value="<?= $row->settings->image_alt ?? '' ?>" maxlength="100" />
            <small class="form-text text-muted"><?= l('microsite_link.image_alt_help') ?></small>
        </div>

    </div>

    <!-- Destination Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-destination" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-destination-tab">
        
        <!-- Basic Destination URL -->
        <div class="form-group">
            <label for="<?= 'destination_location_url_' . $unique_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
            <input id="<?= 'destination_location_url_' . $unique_id ?>" type="url" class="form-control" name="location_url" value="<?= $row->settings->location_url ?? '' ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('microsite_link.location_url_help') ?? 'Enter the URL where users will be redirected when they click this block' ?></small>
        </div>

        <!-- Open in New Tab -->
        <div class="form-group custom-control custom-switch">
            <input
                id="<?= 'open_in_new_tab_' . $unique_id ?>"
                name="open_in_new_tab" 
                type="checkbox"
                class="custom-control-input"
                <?= ($row->settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>
            >
            <label class="custom-control-label" for="<?= 'open_in_new_tab_' . $unique_id ?>"><?= l('microsite_link.open_in_new_tab') ?></label>
            <small class="form-text text-muted"><?= l('microsite_link.open_in_new_tab_help') ?></small>
        </div>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-style-tab">
        
        <?php
        // Define secondary tabs for the style section
        $style_tabs = [
            [
                'id' => 'sizing',
                'title' => 'Sizing',
                'icon' => 'fas fa-expand-arrows-alt'
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
        $secondary_block_id = 'image-style-' . $unique_id;
        $tabs = $style_tabs; // Use style tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="image-style-<?= $unique_id ?>-tabContent">
            
            <!-- Sizing Sub-tab -->
            <div class="tab-pane fade show active" id="image-style-<?= $unique_id ?>-sizing" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-sizing-tab">
                <!-- Image Alignment -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $field_name = 'text_alignment';
                $label = l('microsite_link.text_alignment');
                $icon = 'fas fa-align-center';
                include THEME_PATH . 'views/partials/microsite_block_components/alignment.php';
                ?>

                <!-- Image Sizing Component -->
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $dimensions = ['height', 'width'];
                include THEME_PATH . 'views/partials/microsite_block_components/image_sizing.php';
                ?>
            </div>

            <!-- Background Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-background-tab">
                <?php
                // Set up variables for background component (without accordion)
                $block_id = $unique_id;
                $component_settings = $row->settings;
                $field_name = 'background_color';
                
                // Include background color picker directly
                $bg_field_name = $field_name;
                $bg_label = l('microsite_link.background_color');
                $bg_icon = 'fas fa-fill';
                $bg_default = '#00000000'; // Transparent default
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

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-border-tab">
                <?php
                // Set up variables for border component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-shadow-tab">
                <?php
                // Set up variables for shadow component (without accordion) - with improved ranges
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                ?>
                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="<?= 'block_border_shadow_offset_x_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_x') ?></label>
                    <input id="<?= 'block_border_shadow_offset_x_' . $block_id ?>" type="range" min="-25" max="25" class="form-control-range" name="border_shadow_offset_x" value="<?= $settings->border_shadow_offset_x ?? 0 ?>" required="required" />
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="<?= 'block_border_shadow_offset_y_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
                    <input id="<?= 'block_border_shadow_offset_y_' . $block_id ?>" type="range" min="-25" max="25" class="form-control-range" name="border_shadow_offset_y" value="<?= $settings->border_shadow_offset_y ?? 0 ?>" required="required" />
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="<?= 'block_border_shadow_blur_' . $block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
                    <input id="<?= 'block_border_shadow_blur_' . $block_id ?>" type="range" min="0" max="30" class="form-control-range" name="border_shadow_blur" value="<?= $settings->border_shadow_blur ?? 0 ?>" required="required" />
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="<?= 'block_border_shadow_spread_' . $block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
                    <input id="<?= 'block_border_shadow_spread_' . $block_id ?>" type="range" min="-15" max="15" class="form-control-range" name="border_shadow_spread" value="<?= $settings->border_shadow_spread ?? 0 ?>" required="required" />
                </div>

                <?php
                $field_name = 'border_shadow_color';
                $label = l('microsite_link.border_shadow_color');
                $icon = 'fas fa-fill';
                $default_color = '#00000010';
                $current_color = $settings->border_shadow_color ?? $default_color;
                $include_opacity = true; // Shadow colors often need opacity
                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>
            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-animation-tab">
                <?php
                // Set up variables for animation component (without accordion)
                $component_block_id = $unique_id;
                $component_settings = $row->settings;
                
                // Include animation settings directly without accordion wrapper
                ?>
                <div class="form-group">
                    <label for="<?= 'animation_' . $component_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
                    <select id="<?= 'animation_' . $component_block_id ?>" name="animation" class="form-control">
                        <option value="false" <?= (!isset($component_settings->animation) || !$component_settings->animation) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                        <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                            <option value="<?= $animation ?>" <?= (isset($component_settings->animation) && $component_settings->animation == $animation) ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="<?= 'animation_runs_' . $component_block_id ?>"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
                    <select id="<?= 'animation_runs_' . $component_block_id ?>" name="animation_runs" class="form-control">
                        <option value="repeat-1" <?= (!isset($component_settings->animation_runs) || $component_settings->animation_runs == 'repeat-1') ? 'selected="selected"' : null ?>>1</option>
                        <option value="repeat-2" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-2') ? 'selected="selected"' : null ?>>2</option>
                        <option value="repeat-3" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-3') ? 'selected="selected"' : null ?>>3</option>
                        <option value="infinite" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'infinite') ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
                    </select>
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                    <label for="<?= 'animation_delay_' . $component_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_delay') ?></label>
                    <input id="<?= 'animation_delay_' . $component_block_id ?>" type="range" min="0" max="5000" step="100" class="form-control-range" name="animation_delay" value="<?= $component_settings->animation_delay ?? 0 ?>" required="required" />
                </div>
            </div>

        </div>

    </div>

    <!-- Badge Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-badge" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-badge-tab">
        
        <!-- Verified Badge Settings using shared component -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $field_prefix = 'verified_badge';
        include THEME_PATH . 'views/partials/microsite_block_components/badge_selector.php';
        ?>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-display-tab">
        
        <?php 
        // Set up variables for display settings component
        if ($form_type === 'create') {
            $display_row = (object) [
                'microsite_block_id' => $unique_id,
                'settings' => (object) [
                    'display_continents' => [],
                    'display_countries' => [],
                    'display_cities' => [],
                    'display_devices' => [],
                    'display_languages' => [],
                    'display_operating_systems' => [],
                    'display_browsers' => []
                ],
                'start_date' => null,
                'end_date' => null
            ];
        } else {
            $display_row = $row;
        }
        
        // Temporarily set $row for the display settings component
        $original_row = isset($row) ? $row : null;
        $row = $display_row;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php';
        $row = $original_row; // Restore original $row
        ?>

    </div>

</div>
