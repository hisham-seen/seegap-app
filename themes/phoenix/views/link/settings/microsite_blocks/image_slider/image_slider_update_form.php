<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="image_slider" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <?php
    // Define tabs for the Image Slider block
    $tabs = [
        [
            'id' => 'content',
            'title' => 'Content',
            'icon' => 'fas fa-images'
        ],
        [
            'id' => 'style',
            'title' => 'Style',
            'icon' => 'fas fa-palette'
        ],
        [
            'id' => 'display',
            'title' => 'Display',
            'icon' => 'fas fa-eye'
        ]
    ];

    // Set the block_id for the tab component
    $block_id = 'image-slider-' . $row->microsite_block_id;
    
    // Include the reusable tab navigation
    include THEME_PATH . 'views/partials/microsite_block_tabs.php';
    ?>

    <div class="tab-content" id="image-slider-<?= $row->microsite_block_id ?>-tabContent">
        
        <!-- Content Tab -->
        <div class="tab-pane fade show active" id="image-slider-<?= $row->microsite_block_id ?>-content" role="tabpanel" aria-labelledby="image-slider-<?= $row->microsite_block_id ?>-content-tab">
            
            <!-- Image Slider Manager (Upload and Management Only) -->
            <?php
            $block_id = $row->microsite_block_id;
            $settings = $row->settings;
            $whitelisted_extensions = $data->microsite_blocks['image_slider']['whitelisted_image_extensions'];
            $size_limit = settings()->links->image_size_limit;
            $uploads_file_key = 'block_images';
            $show_upload = true;
            $show_slider_settings = false; // Moved to Style tab
            $show_visual_settings = false; // Moved to Style tab
            $max_images = 50;
            include THEME_PATH . 'views/partials/microsite_block_components/image_slider_manager.php';
            ?>

            <!-- Basic Slider Settings -->
            <div class="form-group custom-control custom-switch mt-4">
                <input
                    id="autoplay_<?= $row->microsite_block_id ?>"
                    name="autoplay" 
                    type="checkbox"
                    class="custom-control-input"
                    <?= ($row->settings->autoplay ?? true) ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="autoplay_<?= $row->microsite_block_id ?>">
                    <i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_image_slider.autoplay') ?>
                </label>
            </div>

            <div class="form-group custom-control custom-switch">
                <input
                    id="display_arrows_<?= $row->microsite_block_id ?>"
                    name="display_arrows" 
                    type="checkbox"
                    class="custom-control-input"
                    <?= ($row->settings->display_arrows ?? true) ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="display_arrows_<?= $row->microsite_block_id ?>">
                    <i class="fas fa-fw fa-chevron-left fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_image_slider.display_arrows') ?>
                </label>
            </div>

            <div class="form-group custom-control custom-switch">
                <input
                    id="display_pagination_<?= $row->microsite_block_id ?>"
                    name="display_pagination" 
                    type="checkbox"
                    class="custom-control-input"
                    <?= ($row->settings->display_pagination ?? true) ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="display_pagination_<?= $row->microsite_block_id ?>">
                    <i class="fas fa-fw fa-circle fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_image_slider.display_pagination') ?>
                </label>
            </div>

            <div class="form-group">
                <label for="autoplay_interval_<?= $row->microsite_block_id ?>">
                    <i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_image_slider.autoplay_interval') ?>
                </label>
                <div class="input-group">
                    <input
                        id="autoplay_interval_<?= $row->microsite_block_id ?>"
                        name="autoplay_interval"
                        type="number"
                        class="form-control"
                        value="<?= $row->settings->autoplay_interval ?? 5 ?>"
                        min="1"
                        max="30"
                    />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                    </div>
                </div>
            </div>

            <!-- Open in New Tab Setting -->
            <div class="form-group custom-control custom-switch">
                <input
                    id="open_in_new_tab_<?= $row->microsite_block_id ?>"
                    name="open_in_new_tab" 
                    type="checkbox"
                    class="custom-control-input"
                    <?= ($row->settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="open_in_new_tab_<?= $row->microsite_block_id ?>">
                    <i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_link.open_in_new_tab') ?>
                </label>
                <small class="form-text text-muted">
                    <?= l('microsite_image_slider.open_in_new_tab_help') ?? 'Open linked images in a new tab/window' ?>
                </small>
            </div>

        </div>

        <!-- Style Tab -->
        <div class="tab-pane fade" id="image-slider-<?= $row->microsite_block_id ?>-style" role="tabpanel" aria-labelledby="image-slider-<?= $row->microsite_block_id ?>-style-tab">
            
            <?php
            // Define secondary tabs for the style section
            $style_tabs = [
                [
                    'id' => 'layout',
                    'title' => 'Layout',
                    'icon' => 'fas fa-th-large'
                ],
                [
                    'id' => 'visual',
                    'title' => 'Visual',
                    'icon' => 'fas fa-eye'
                ],
                [
                    'id' => 'background',
                    'title' => 'Background',
                    'icon' => 'fas fa-fill-drip'
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
            $secondary_block_id = 'image-slider-style-' . $row->microsite_block_id;
            $tabs = $style_tabs; // Use style tabs for the secondary navigation
            $block_id = $secondary_block_id; // Override block_id for secondary tabs
            
            // Include the reusable tab navigation for secondary tabs
            include THEME_PATH . 'views/partials/microsite_block_tabs.php';
            ?>

            <div class="tab-content" id="image-slider-style-<?= $row->microsite_block_id ?>-tabContent">
                
                <!-- Layout Sub-tab -->
                <div class="tab-pane fade show active" id="image-slider-style-<?= $row->microsite_block_id ?>-layout" role="tabpanel" aria-labelledby="image-slider-style-<?= $row->microsite_block_id ?>-layout-tab">
                    
                    <!-- Slider Height -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="slider_height_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.slider_height') ?>
                        </label>
                        <input
                            id="slider_height_<?= $row->microsite_block_id ?>"
                            name="slider_height"
                            type="range"
                            class="form-control-range"
                            value="<?= $row->settings->slider_height ?? 300 ?>"
                            min="200"
                            max="800"
                            step="10"
                            oninput="updateImageSliderPreview()"
                            onchange="updateImageSliderPreview()"
                        />
                    </div>

                    <!-- Aspect Ratio -->
                    <div class="form-group">
                        <label for="aspect_ratio_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.aspect_ratio') ?>
                        </label>
                        <select id="aspect_ratio_<?= $row->microsite_block_id ?>" name="aspect_ratio" class="custom-select" onchange="updateImageSliderPreview()">
                            <option value="custom" <?= ($row->settings->aspect_ratio ?? 'custom') == 'custom' ? 'selected' : '' ?>><?= l('microsite_image_slider.aspect_ratio_custom') ?></option>
                            <option value="16:9" <?= ($row->settings->aspect_ratio ?? '') == '16:9' ? 'selected' : '' ?>><?= l('microsite_image_slider.aspect_ratio_16_9') ?></option>
                            <option value="4:3" <?= ($row->settings->aspect_ratio ?? '') == '4:3' ? 'selected' : '' ?>><?= l('microsite_image_slider.aspect_ratio_4_3') ?></option>
                            <option value="1:1" <?= ($row->settings->aspect_ratio ?? '') == '1:1' ? 'selected' : '' ?>><?= l('microsite_image_slider.aspect_ratio_1_1') ?></option>
                            <option value="21:9" <?= ($row->settings->aspect_ratio ?? '') == '21:9' ? 'selected' : '' ?>><?= l('microsite_image_slider.aspect_ratio_21_9') ?></option>
                        </select>
                    </div>

                    <!-- Slides Per View -->
                    <div class="form-group">
                        <label for="slides_per_view_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-th fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.slides_per_view') ?>
                        </label>
                        <select id="slides_per_view_<?= $row->microsite_block_id ?>" name="slides_per_view" class="custom-select" onchange="updateImageSliderPreview()">
                            <option value="1" <?= ($row->settings->slides_per_view ?? 1) == 1 ? 'selected' : '' ?>><?= l('microsite_image_slider.slides_per_view_1') ?? '1' ?></option>
                            <option value="2" <?= ($row->settings->slides_per_view ?? 1) == 2 ? 'selected' : '' ?>><?= l('microsite_image_slider.slides_per_view_2') ?? '2' ?></option>
                            <option value="3" <?= ($row->settings->slides_per_view ?? 1) == 3 ? 'selected' : '' ?>><?= l('microsite_image_slider.slides_per_view_3') ?? '3' ?></option>
                            <option value="4" <?= ($row->settings->slides_per_view ?? 1) == 4 ? 'selected' : '' ?>><?= l('microsite_image_slider.slides_per_view_4') ?? '4' ?></option>
                        </select>
                    </div>

                    <!-- Slide Gap -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="slide_gap_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.slide_gap') ?>
                        </label>
                        <input
                            id="slide_gap_<?= $row->microsite_block_id ?>"
                            name="slide_gap"
                            type="range"
                            class="form-control-range"
                            value="<?= $row->settings->slide_gap ?? 0 ?>"
                            min="0"
                            max="50"
                            step="1"
                            oninput="updateImageSliderPreview()"
                            onchange="updateImageSliderPreview()"
                        />
                    </div>

                </div>

                <!-- Visual Sub-tab -->
                <div class="tab-pane fade" id="image-slider-style-<?= $row->microsite_block_id ?>-visual" role="tabpanel" aria-labelledby="image-slider-style-<?= $row->microsite_block_id ?>-visual-tab">
                    
                    <!-- Image Fit -->
                    <div class="form-group">
                        <label for="image_fit_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.image_fit') ?>
                        </label>
                        <select id="image_fit_<?= $row->microsite_block_id ?>" name="image_fit" class="custom-select" onchange="updateImageSliderPreview()">
                            <option value="cover" <?= ($row->settings->image_fit ?? 'cover') == 'cover' ? 'selected' : '' ?>><?= l('microsite_image_slider.image_fit_cover') ?></option>
                            <option value="contain" <?= ($row->settings->image_fit ?? '') == 'contain' ? 'selected' : '' ?>><?= l('microsite_image_slider.image_fit_contain') ?></option>
                            <option value="fill" <?= ($row->settings->image_fit ?? '') == 'fill' ? 'selected' : '' ?>><?= l('microsite_image_slider.image_fit_fill') ?></option>
                            <option value="scale-down" <?= ($row->settings->image_fit ?? '') == 'scale-down' ? 'selected' : '' ?>><?= l('microsite_image_slider.image_fit_scale_down') ?></option>
                        </select>
                    </div>

                    <!-- Transition Type -->
                    <div class="form-group">
                        <label for="transition_type_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-exchange-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.transition_type') ?>
                        </label>
                        <select id="transition_type_<?= $row->microsite_block_id ?>" name="transition_type" class="custom-select" onchange="updateImageSliderPreview()">
                            <option value="slide" <?= ($row->settings->transition_type ?? 'slide') == 'slide' ? 'selected' : '' ?>><?= l('microsite_image_slider.transition_type_slide') ?></option>
                            <option value="fade" <?= ($row->settings->transition_type ?? '') == 'fade' ? 'selected' : '' ?>><?= l('microsite_image_slider.transition_type_fade') ?></option>
                            <option value="loop" <?= ($row->settings->transition_type ?? '') == 'loop' ? 'selected' : '' ?>><?= l('microsite_image_slider.transition_type_loop') ?></option>
                        </select>
                    </div>

                    <!-- Transition Speed -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                        <label for="transition_speed_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-tachometer-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.transition_speed') ?>
                        </label>
                        <input
                            id="transition_speed_<?= $row->microsite_block_id ?>"
                            name="transition_speed"
                            type="range"
                            class="form-control-range"
                            value="<?= $row->settings->transition_speed ?? 600 ?>"
                            min="200"
                            max="2000"
                            step="100"
                            oninput="updateImageSliderPreview()"
                            onchange="updateImageSliderPreview()"
                        />
                    </div>

                    <!-- Pause on Hover -->
                    <div class="form-group custom-control custom-switch">
                        <input
                            id="pause_on_hover_<?= $row->microsite_block_id ?>"
                            name="pause_on_hover" 
                            type="checkbox"
                            class="custom-control-input"
                            <?= ($row->settings->pause_on_hover ?? true) ? 'checked="checked"' : null ?>
                        >
                        <label class="custom-control-label" for="pause_on_hover_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-pause fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.pause_on_hover') ?>
                        </label>
                    </div>

                    <!-- Infinite Loop -->
                    <div class="form-group custom-control custom-switch">
                        <input
                            id="infinite_loop_<?= $row->microsite_block_id ?>"
                            name="infinite_loop" 
                            type="checkbox"
                            class="custom-control-input"
                            <?= ($row->settings->infinite_loop ?? true) ? 'checked="checked"' : null ?>
                        >
                        <label class="custom-control-label" for="infinite_loop_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-sync fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.infinite_loop') ?>
                        </label>
                    </div>

                    <!-- Hover Effect -->
                    <div class="form-group">
                        <label for="hover_effect_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-hand-pointer fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.hover_effect') ?? 'Hover Effect' ?>
                        </label>
                        <select id="hover_effect_<?= $row->microsite_block_id ?>" name="hover_effect" class="custom-select" onchange="updateImageSliderHoverEffect()">
                            <option value="none" <?= ($row->settings->hover_effect ?? 'none') == 'none' ? 'selected' : '' ?>><?= l('microsite_image_slider.hover_effect_none') ?? 'None' ?></option>
                            <option value="zoom" <?= ($row->settings->hover_effect ?? '') == 'zoom' ? 'selected' : '' ?>><?= l('microsite_image_slider.hover_effect_zoom') ?? 'Zoom' ?></option>
                            <option value="fade" <?= ($row->settings->hover_effect ?? '') == 'fade' ? 'selected' : '' ?>><?= l('microsite_image_slider.hover_effect_fade') ?? 'Fade' ?></option>
                            <option value="lift" <?= ($row->settings->hover_effect ?? '') == 'lift' ? 'selected' : '' ?>><?= l('microsite_image_slider.hover_effect_lift') ?? 'Lift' ?></option>
                        </select>
                    </div>

                </div>

                <!-- Background Sub-tab -->
                <div class="tab-pane fade" id="image-slider-style-<?= $row->microsite_block_id ?>-background" role="tabpanel" aria-labelledby="image-slider-style-<?= $row->microsite_block_id ?>-background-tab">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Slider Background:</strong> These settings apply to the slider container background, visible behind or around your images.
                    </div>

                    <?php
                    $block_id = $row->microsite_block_id;
                    $field_name = 'background_color';
                    $label = l('microsite_image_slider.background_color') ?? 'Background Color';
                    $icon = 'fas fa-fill';
                    $default_color = '#ffffff';
                    $current_color = $row->settings->background_color ?? $default_color;
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                    <div class="form-group">
                        <label for="background_gradient_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.background_gradient') ?? 'Background Gradient' ?>
                        </label>
                        <input
                            id="background_gradient_<?= $row->microsite_block_id ?>"
                            name="background_gradient"
                            type="text"
                            class="form-control"
                            value="<?= $row->settings->background_gradient ?? '' ?>"
                            placeholder="linear-gradient(45deg, #ff6b6b, #4ecdc4)"
                            onchange="updateImageSliderBackgroundPreview()"
                        />
                        <small class="form-text text-muted">
                            <?= l('microsite_image_slider.background_gradient_help') ?? 'CSS gradient (overrides background color if set)' ?>
                        </small>
                    </div>

                </div>

                <!-- Border Sub-tab -->
                <div class="tab-pane fade" id="image-slider-style-<?= $row->microsite_block_id ?>-border" role="tabpanel" aria-labelledby="image-slider-style-<?= $row->microsite_block_id ?>-border-tab">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Individual Image Borders:</strong> These border settings apply to each individual image in the slider.
                    </div>

                    <!-- Border Width -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="border_width_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.border_width') ?? 'Border Width' ?>
                        </label>
                        <input
                            id="border_width_<?= $row->microsite_block_id ?>"
                            name="border_width"
                            type="range"
                            class="form-control-range"
                            value="<?= $row->settings->border_width ?? 0 ?>"
                            min="0"
                            max="20"
                            step="1"
                            oninput="updateImageSliderBorderPreview()"
                            onchange="updateImageSliderBorderPreview()"
                        />
                    </div>

                    <!-- Border Color -->
                    <?php
                    $block_id = $row->microsite_block_id;
                    $field_name = 'border_color';
                    $label = l('microsite_image_slider.border_color') ?? 'Border Color';
                    $icon = 'fas fa-palette';
                    $default_color = '#000000';
                    $current_color = $row->settings->border_color ?? $default_color;
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                    <!-- Border Style -->
                    <div class="form-group">
                        <label for="border_style_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-minus fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.border_style') ?? 'Border Style' ?>
                        </label>
                        <select id="border_style_<?= $row->microsite_block_id ?>" name="border_style" class="custom-select" onchange="updateImageSliderBorderPreview()">
                            <option value="solid" <?= ($row->settings->border_style ?? 'solid') == 'solid' ? 'selected' : '' ?>><?= l('microsite_image_slider.border_style_solid') ?? 'Solid' ?></option>
                            <option value="dashed" <?= ($row->settings->border_style ?? '') == 'dashed' ? 'selected' : '' ?>><?= l('microsite_image_slider.border_style_dashed') ?? 'Dashed' ?></option>
                            <option value="dotted" <?= ($row->settings->border_style ?? '') == 'dotted' ? 'selected' : '' ?>><?= l('microsite_image_slider.border_style_dotted') ?? 'Dotted' ?></option>
                            <option value="double" <?= ($row->settings->border_style ?? '') == 'double' ? 'selected' : '' ?>><?= l('microsite_image_slider.border_style_double') ?? 'Double' ?></option>
                        </select>
                    </div>

                    <!-- Border Radius -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="border_radius_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-square fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.border_radius') ?? 'Border Radius' ?>
                        </label>
                        <input
                            id="border_radius_<?= $row->microsite_block_id ?>"
                            name="border_radius"
                            type="range"
                            class="form-control-range"
                            value="<?= $row->settings->border_radius ?? 0 ?>"
                            min="0"
                            max="50"
                            step="1"
                            oninput="updateImageSliderBorderPreview()"
                            onchange="updateImageSliderBorderPreview()"
                        />
                    </div>

                </div>

                <!-- Shadow Sub-tab -->
                <div class="tab-pane fade" id="image-slider-style-<?= $row->microsite_block_id ?>-shadow" role="tabpanel" aria-labelledby="image-slider-style-<?= $row->microsite_block_id ?>-shadow-tab">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Individual Image Shadows:</strong> These shadow settings apply to each individual image in the slider.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="shadow_offset_x_<?= $row->microsite_block_id ?>">
                                    <i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> 
                                    <?= l('microsite_image_slider.shadow_offset_x') ?? 'Shadow Offset X' ?>
                                </label>
                                <input
                                    id="shadow_offset_x_<?= $row->microsite_block_id ?>"
                                    name="shadow_offset_x"
                                    type="range"
                                    class="form-control-range"
                                    value="<?= $row->settings->shadow_offset_x ?? 0 ?>"
                                    min="-20"
                                    max="20"
                                    step="1"
                                    oninput="updateImageSliderShadowPreview()"
                                    onchange="updateImageSliderShadowPreview()"
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="shadow_offset_y_<?= $row->microsite_block_id ?>">
                                    <i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> 
                                    <?= l('microsite_image_slider.shadow_offset_y') ?? 'Shadow Offset Y' ?>
                                </label>
                                <input
                                    id="shadow_offset_y_<?= $row->microsite_block_id ?>"
                                    name="shadow_offset_y"
                                    type="range"
                                    class="form-control-range"
                                    value="<?= $row->settings->shadow_offset_y ?? 0 ?>"
                                    min="-20"
                                    max="20"
                                    step="1"
                                    oninput="updateImageSliderShadowPreview()"
                                    onchange="updateImageSliderShadowPreview()"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="shadow_blur_<?= $row->microsite_block_id ?>">
                                    <i class="fas fa-fw fa-circle fa-sm text-muted mr-1"></i> 
                                    <?= l('microsite_image_slider.shadow_blur') ?? 'Shadow Blur' ?>
                                </label>
                                <input
                                    id="shadow_blur_<?= $row->microsite_block_id ?>"
                                    name="shadow_blur"
                                    type="range"
                                    class="form-control-range"
                                    value="<?= $row->settings->shadow_blur ?? 0 ?>"
                                    min="0"
                                    max="50"
                                    step="1"
                                    oninput="updateImageSliderShadowPreview()"
                                    onchange="updateImageSliderShadowPreview()"
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="shadow_spread_<?= $row->microsite_block_id ?>">
                                    <i class="fas fa-fw fa-expand-alt fa-sm text-muted mr-1"></i> 
                                    <?= l('microsite_image_slider.shadow_spread') ?? 'Shadow Spread' ?>
                                </label>
                                <input
                                    id="shadow_spread_<?= $row->microsite_block_id ?>"
                                    name="shadow_spread"
                                    type="range"
                                    class="form-control-range"
                                    value="<?= $row->settings->shadow_spread ?? 0 ?>"
                                    min="-20"
                                    max="20"
                                    step="1"
                                    oninput="updateImageSliderShadowPreview()"
                                    onchange="updateImageSliderShadowPreview()"
                                />
                            </div>
                        </div>
                    </div>

                    <?php
                    $block_id = $row->microsite_block_id;
                    $field_name = 'shadow_color';
                    $label = l('microsite_image_slider.shadow_color') ?? 'Shadow Color';
                    $icon = 'fas fa-palette';
                    $default_color = '#00000010';
                    $current_color = $row->settings->shadow_color ?? $default_color;
                    $include_opacity = true; // Shadow colors often need opacity
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                </div>

                <!-- Animation Sub-tab -->
                <div class="tab-pane fade" id="image-slider-style-<?= $row->microsite_block_id ?>-animation" role="tabpanel" aria-labelledby="image-slider-style-<?= $row->microsite_block_id ?>-animation-tab">
                    <?php
                    // Set up variables for animation component (without accordion)
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/animation_settings.php';
                    ?>
                </div>

            </div>

        </div>

        <!-- Display Tab -->
        <div class="tab-pane fade" id="image-slider-<?= $row->microsite_block_id ?>-display" role="tabpanel" aria-labelledby="image-slider-<?= $row->microsite_block_id ?>-display-tab">
            
            <?php include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; ?>

        </div>

    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<script>
// Custom real-time preview functions for Image Slider Block
// These functions specifically target individual images in the slider

function updateImageSliderPreview() {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const blockId = '<?= $row->microsite_block_id ?>';
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get current form values
            const sliderHeight = $(`#slider_height_${blockId}`).val() || 300;
            const aspectRatio = $(`#aspect_ratio_${blockId}`).val() || 'custom';
            const slidesPerView = parseInt($(`#slides_per_view_${blockId}`).val()) || 1;
            const slideGap = parseInt($(`#slide_gap_${blockId}`).val()) || 0;
            const imageFit = $(`#image_fit_${blockId}`).val() || 'cover';
            
            // Update slider container
            const sliderContainer = microsite_link.find('.splide');
            if (sliderContainer.length) {
                let calculatedHeight = sliderHeight + 'px';
                
                if (aspectRatio !== 'custom') {
                    switch(aspectRatio) {
                        case '16:9':
                            calculatedHeight = 'calc(100vw * 9 / 16)';
                            break;
                        case '4:3':
                            calculatedHeight = 'calc(100vw * 3 / 4)';
                            break;
                        case '1:1':
                            calculatedHeight = '100vw';
                            break;
                        case '21:9':
                            calculatedHeight = 'calc(100vw * 9 / 21)';
                            break;
                    }
                }
                
                // Update height and basic styling
                sliderContainer.css('height', calculatedHeight);
                sliderContainer.find('.splide__track, .splide__list').css('height', calculatedHeight);
                sliderContainer.find('.image-slider-wrapper').css('height', calculatedHeight);
                sliderContainer.find('.image-slider-image').css({
                    'height': calculatedHeight,
                    'object-fit': imageFit
                });
                
                // Try to update Splide instance if it exists
                try {
                    const splideInstance = sliderContainer[0]?.splide;
                    if (splideInstance && typeof splideInstance.options !== 'undefined') {
                        // Update Splide options
                        splideInstance.options.perPage = slidesPerView;
                        splideInstance.options.gap = slideGap + 'px';
                        
                        // Refresh the Splide instance to apply new settings
                        if (typeof splideInstance.refresh === 'function') {
                            splideInstance.refresh();
                        } else if (typeof splideInstance.destroy === 'function' && typeof splideInstance.mount === 'function') {
                            // Fallback: destroy and remount
                            splideInstance.destroy();
                            splideInstance.mount();
                        }
                    } else {
                        // Fallback: Create a new Splide instance with updated settings
                        const iframeWindow = iframe[0].contentWindow;
                        if (iframeWindow && iframeWindow.Splide) {
                            const newSplideConfig = {
                                perPage: slidesPerView,
                                gap: slideGap + 'px',
                                autoplay: true,
                                arrows: true,
                                pagination: false, // Always disable pagination to prevent dots
                                speed: 600,
                                pauseOnHover: true,
                                direction: 'ltr',
                                breakpoints: {
                                    768: {
                                        perPage: slidesPerView > 2 ? 2 : slidesPerView,
                                        gap: Math.max(0, slideGap - 10) + 'px',
                                    },
                                    480: {
                                        perPage: 1,
                                        gap: Math.max(0, slideGap - 15) + 'px',
                                    }
                                }
                            };
                            
                            const newSplide = new iframeWindow.Splide(sliderContainer[0], newSplideConfig);
                            newSplide.mount();
                        }
                    }
                } catch (e) {
                    console.log('Splide update not available in preview mode:', e);
                }
            }
        }
    }
}

function updateImageSliderBorderPreview() {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const blockId = '<?= $row->microsite_block_id ?>';
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const borderWidth = $(`#border_width_${blockId}`).val() || 0;
            const borderColor = $(`#border_color_${blockId}`).val() || '#000000';
            const borderStyle = $(`#border_style_${blockId}`).val() || 'solid';
            const borderRadius = $(`#border_radius_${blockId}`).val() || 0;
            
            const images = microsite_link.find('.image-slider-image');
            if (images.length) {
                if (borderWidth > 0) {
                    images.css({
                        'border': `${borderWidth}px ${borderStyle} ${borderColor}`,
                        'border-radius': `${borderRadius}px`
                    });
                } else {
                    images.css({
                        'border': 'none',
                        'border-radius': `${borderRadius}px`
                    });
                }
            }
        }
    }
}

function updateImageSliderShadowPreview() {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const blockId = '<?= $row->microsite_block_id ?>';
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const offsetX = $(`#shadow_offset_x_${blockId}`).val() || 0;
            const offsetY = $(`#shadow_offset_y_${blockId}`).val() || 0;
            const blur = $(`#shadow_blur_${blockId}`).val() || 0;
            const spread = $(`#shadow_spread_${blockId}`).val() || 0;
            const color = $(`#shadow_color_${blockId}`).val() || '#000000';
            
            // Apply shadow to the splide track element
            const splideTrack = microsite_link.find('.splide__track');
            if (splideTrack.length) {
                if (offsetX == 0 && offsetY == 0 && blur == 0 && spread == 0) {
                    splideTrack.css('box-shadow', 'none');
                } else {
                    const shadowCSS = `${offsetX}px ${offsetY}px ${blur}px ${spread}px ${color}`;
                    splideTrack.css('box-shadow', shadowCSS);
                }
            }
        }
    }
}

function updateImageSliderBackgroundPreview() {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const blockId = '<?= $row->microsite_block_id ?>';
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const backgroundColor = $(`#background_color_${blockId}`).val() || '';
            const backgroundGradient = $(`#background_gradient_${blockId}`).val() || '';
            
            const sliderContainer = microsite_link.find('.splide');
            if (sliderContainer.length) {
                if (backgroundGradient) {
                    sliderContainer.css('background', backgroundGradient);
                } else if (backgroundColor) {
                    sliderContainer.css('background-color', backgroundColor);
                } else {
                    sliderContainer.css('background', 'none');
                }
            }
        }
    }
}

function updateImageSliderHoverEffect() {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const blockId = '<?= $row->microsite_block_id ?>';
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const hoverEffect = $(`#hover_effect_${blockId}`).val() || 'none';
            
            const images = microsite_link.find('.image-slider-image');
            if (images.length) {
                // Remove existing hover effect classes
                images.removeClass('hover-effect-zoom hover-effect-fade hover-effect-lift');
                
                // Add new hover effect class
                if (hoverEffect !== 'none') {
                    images.addClass(`hover-effect-${hoverEffect}`);
                }
            }
        }
    }
}
</script>
