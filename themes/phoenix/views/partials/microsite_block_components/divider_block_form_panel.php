<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Divider Block Form Panel
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
        'icon' => '',
        'show_icon' => false,
        'icon_size' => 20,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'divider_thickness' => 1,
        'divider_style' => 'solid',
        'divider_width' => 100,
        'divider_color' => '#e9ecef',

        /* Shadow settings */
        'border_shadow_color' => '#000000',
        'border_shadow_offset_x' => 0,
        'border_shadow_offset_y' => 0,
        'border_shadow_blur_radius' => 20,
        'border_shadow_spread_radius' => 0,

        /* Animation settings */
        'animation_type' => '',
        'animation_runs' => 'repeat-1',
        'animation_delay' => 0,

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
    ['id' => 'display', 'title' => 'Display', 'icon' => 'fas fa-eye']
];
?>

<!-- Primary Tab Navigation -->
<?php
$block_id = 'divider-' . $unique_id;
$tabs = $primary_tabs;
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="divider-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="divider-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="divider-<?= $unique_id ?>-content-tab">
        
        <!-- Show Icon Toggle -->
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center">
                <label for="<?= 'divider_show_icon_' . $unique_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('microsite_divider.show_icon') ?></label>
                <div class="custom-control custom-switch">
                    <input id="<?= 'divider_show_icon_' . $unique_id ?>" name="show_icon" type="checkbox" class="custom-control-input" <?= ($row->settings->show_icon ?? false) ? 'checked="checked"' : '' ?>>
                    <label class="custom-control-label" for="<?= 'divider_show_icon_' . $unique_id ?>"></label>
                </div>
            </div>
        </div>

        <!-- Icon (only show when toggle is enabled) -->
        <div class="form-group" id="<?= 'divider_icon_container_' . $unique_id ?>" style="<?= ($row->settings->show_icon ?? false) ? '' : 'display: none;' ?>">
            <label for="<?= 'divider_icon_' . $unique_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('microsite_divider.icon') ?></label>
            <input id="<?= 'divider_icon_' . $unique_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="fas fa-star" />
            <small class="form-text text-muted"><?= l('microsite_divider.icon_help') ?></small>
        </div>

        <!-- Icon Size (only show when toggle is enabled) -->
        <div class="form-group" id="<?= 'divider_icon_size_container_' . $unique_id ?>" style="<?= ($row->settings->show_icon ?? false) ? '' : 'display: none;' ?>" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'divider_icon_size_' . $unique_id ?>"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_divider.icon_size') ?></label>
            <input id="<?= 'divider_icon_size_' . $unique_id ?>" type="range" min="12" max="48" class="form-control-range" name="icon_size" value="<?= $row->settings->icon_size ?? 20 ?>" required="required" />
        </div>

        <!-- Spacing Controls -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $spacing_types = ['margin_top', 'margin_bottom'];
        $min_value = 0;
        $max_value = 7;
        $collapsed = false;
        $show_title = false; // Don't show title since it's in the content tab
        include THEME_PATH . 'views/partials/microsite_block_components/spacing_settings.php';
        ?>
        
        <script>
        // Add canvas update functionality to spacing controls
        document.addEventListener('DOMContentLoaded', function() {
            const marginTopInput = document.getElementById('margin_top_<?= $unique_id ?>');
            const marginBottomInput = document.getElementById('margin_bottom_<?= $unique_id ?>');
            
            if (marginTopInput) {
                marginTopInput.addEventListener('input', function() {
                    updateCanvasSpacing('<?= $unique_id ?>');
                });
            }
            
            if (marginBottomInput) {
                marginBottomInput.addEventListener('input', function() {
                    updateCanvasSpacing('<?= $unique_id ?>');
                });
            }
        });
        
        // Real-time canvas update function for spacing properties
        function updateCanvasSpacing(blockId) {
            if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
                const iframe = $('#microsite_preview_iframe');
                const iframeDoc = iframe.contents();
                const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
                
                if (microsite_link.length) {
                    // Get current margin values
                    const marginTop = $(`#margin_top_${blockId}`).val() || 0;
                    const marginBottom = $(`#margin_bottom_${blockId}`).val() || 0;
                    
                    // Update the block's margin classes
                    // Remove existing margin classes
                    microsite_link.removeClass(function(index, className) {
                        return (className.match(/(^|\s)mt-\d+/g) || []).join(' ');
                    });
                    microsite_link.removeClass(function(index, className) {
                        return (className.match(/(^|\s)mb-\d+/g) || []).join(' ');
                    });
                    
                    // Add new margin classes
                    if (marginTop > 0) {
                        microsite_link.addClass(`mt-${marginTop}`);
                    }
                    if (marginBottom > 0) {
                        microsite_link.addClass(`mb-${marginBottom}`);
                    }
                }
            }
        }
        </script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showIconToggle = document.getElementById('<?= 'divider_show_icon_' . $unique_id ?>');
            const iconContainer = document.getElementById('<?= 'divider_icon_container_' . $unique_id ?>');
            const iconSizeContainer = document.getElementById('<?= 'divider_icon_size_container_' . $unique_id ?>');
            
            if (showIconToggle && iconContainer && iconSizeContainer) {
                showIconToggle.addEventListener('change', function() {
                    const display = this.checked ? '' : 'none';
                    iconContainer.style.display = display;
                    iconSizeContainer.style.display = display;
                });
            }
        });
        </script>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="divider-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="divider-<?= $unique_id ?>-style-tab">
        
        <?php
        // Define secondary tabs for the style section
        $style_tabs = [
            [
                'id' => 'divider-styling',
                'title' => 'Divider',
                'icon' => 'fas fa-minus'
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
        $secondary_block_id = 'divider-style-' . $unique_id;
        $tabs = $style_tabs; // Use style tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="divider-style-<?= $unique_id ?>-tabContent">
            
            <!-- Divider Styling Sub-tab -->
            <div class="tab-pane fade show active" id="divider-style-<?= $unique_id ?>-divider-styling" role="tabpanel" aria-labelledby="divider-style-<?= $unique_id ?>-divider-styling-tab">
                
                <!-- Divider Thickness -->
                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                    <label for="<?= 'divider_thickness_' . $unique_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_divider.thickness') ?></label>
                    <input id="<?= 'divider_thickness_' . $unique_id ?>" type="range" min="1" max="10" class="form-control-range" name="divider_thickness" value="<?= $row->settings->divider_thickness ?? 1 ?>" required="required" oninput="updateCanvasDivider('<?= $unique_id ?>')" />
                </div>

                <!-- Divider Style -->
                <div class="form-group">
                    <label for="<?= 'divider_style_' . $unique_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_divider.style') ?></label>
                    <div class="row btn-group-toggle" data-toggle="buttons">
                        <?php foreach(['solid', 'dashed', 'dotted'] as $divider_style): ?>
                            <div class="col-4">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->divider_style ?? 'solid') == $divider_style ? 'active' : '' ?>">
                                    <input type="radio" name="divider_style" value="<?= $divider_style ?>" class="custom-control-input" <?= ($row->settings->divider_style ?? 'solid') == $divider_style ? 'checked="checked"' : '' ?> onchange="updateCanvasDivider('<?= $unique_id ?>')" />
                                    <?= l('microsite_divider.style_' . $divider_style) ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- Divider Width -->
                <div class="form-group" data-range-counter data-range-counter-suffix="%">
                    <label for="<?= 'divider_width_' . $unique_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_divider.width') ?></label>
                    <input id="<?= 'divider_width_' . $unique_id ?>" type="range" min="10" max="100" step="5" class="form-control-range" name="divider_width" value="<?= $row->settings->divider_width ?? 100 ?>" required="required" oninput="updateCanvasDivider('<?= $unique_id ?>')" />
                </div>

                <!-- Divider Color -->
                <?php
                $block_id = $unique_id;
                $field_name = 'divider_color';
                $label = l('microsite_divider.color');
                $icon = 'fas fa-fill';
                $default_color = '#e9ecef';
                $current_color = $row->settings->divider_color ?? $default_color;
                $canvas_update_function = "updateCanvasDivider('$unique_id')";
                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>

                <script>
                // Real-time canvas update function for divider properties
                function updateCanvasDivider(blockId) {
                    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
                        const iframe = $('#microsite_preview_iframe');
                        const iframeDoc = iframe.contents();
                        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
                        
                        if (microsite_link.length) {
                            // Get current form values using jQuery
                            const thickness = $('input[name="divider_thickness"]').val() || 1;
                            const style = $('input[name="divider_style"]:checked').val() || 'solid';
                            const width = $('input[name="divider_width"]').val() || 100;
                            const color = $('input[name="divider_color"]').val() || '#e9ecef';
                            
                            // Find all hr elements within the block (there can be 1 or 2 depending on icon)
                            const hrElements = microsite_link.find('hr');
                            if (hrElements.length) {
                                // Update all hr elements with new divider styling
                                hrElements.each(function() {
                                    $(this).css({
                                        'border': 'none',
                                        'border-top': thickness + 'px ' + style + ' ' + color,
                                        'width': width + '%',
                                        'margin': '0'
                                    });
                                });
                                
                                // Also update icon color if present
                                const iconElement = microsite_link.find('i[class*="fa"]');
                                if (iconElement.length) {
                                    iconElement.css('color', color);
                                }
                            }
                        }
                    }
                }
                </script>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="divider-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="divider-style-<?= $unique_id ?>-shadow-tab">
                <?php
                // Set up variables for shadow component (without accordion)
                $component_block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>
                
                <script>
                // Real-time canvas update function for shadow properties
                function updateCanvasShadow(blockId) {
                    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
                        const iframe = $('#microsite_preview_iframe');
                        const iframeDoc = iframe.contents();
                        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
                        
                        if (microsite_link.length) {
                            // Get all shadow values from the form inputs using block-specific IDs
                            const offsetX = $(`#block_border_shadow_offset_x_${blockId}`).val() || 0;
                            const offsetY = $(`#block_border_shadow_offset_y_${blockId}`).val() || 0;
                            const blur = $(`#block_border_shadow_blur_${blockId}`).val() || 0;
                            const spread = $(`#block_border_shadow_spread_${blockId}`).val() || 0;
                            const color = $(`input[name="border_shadow_color"]`).val() || '#00000010';
                            
                            // Combine into box-shadow CSS property
                            const shadowCSS = `${offsetX}px ${offsetY}px ${blur}px ${spread}px ${color}`;
                            
                            // Apply shadow to the main block element
                            if (blur > 0) {
                                microsite_link.css('box-shadow', shadowCSS);
                            } else {
                                microsite_link.css('box-shadow', 'none');
                            }
                        }
                    }
                }
                </script>
            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="divider-style-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="divider-style-<?= $unique_id ?>-animation-tab">
                <?php
                // Set up variables for animation component (without accordion)
                $component_block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/animation_settings.php';
                ?>
                
                <script>
                // Real-time canvas update function for animation properties
                function updateCanvasAnimation(blockId) {
                    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
                        const iframe = $('#microsite_preview_iframe');
                        const iframeDoc = iframe.contents();
                        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
                        
                        if (microsite_link.length) {
                            // Get animation values from the current form inputs with proper selectors
                            const animation = $(`select[name="animation_type"]`).val() || 'false';
                            const runs = $(`select[name="animation_runs"]`).val() || 'repeat-1';
                            const delay = $(`input[name="animation_delay"]`).val() || 0;
                            
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
                            
                            microsite_link.removeClass(animateClasses.join(' '));
                            
                            if (animation !== 'false' && animation !== '') {
                                // Add new animation classes
                                microsite_link.addClass('animate__animated');
                                microsite_link.addClass(`animate__${animation}`);
                                
                                // Add repeat class
                                if (runs && runs !== 'repeat-1') {
                                    microsite_link.addClass(`animate__${runs}`);
                                }
                                
                                // Apply delay - always set to ensure consistency
                                const delayMs = parseInt(delay) || 0;
                                microsite_link.css('animation-delay', `${delayMs}ms`);
                                
                                // Force animation restart by triggering reflow
                                microsite_link[0].offsetHeight; // trigger reflow
                                
                                // Remove and re-add animated class to restart animation
                                setTimeout(() => {
                                    microsite_link.removeClass('animate__animated');
                                    microsite_link[0].offsetHeight; // trigger reflow
                                    setTimeout(() => {
                                        microsite_link.addClass('animate__animated');
                                    }, 50);
                                }, 50);
                            }
                        }
                    }
                }
                </script>
            </div>

        </div>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="divider-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="divider-<?= $unique_id ?>-display-tab">
        
        <?php 
        $block_id = $unique_id;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; 
        ?>

    </div>

</div>
