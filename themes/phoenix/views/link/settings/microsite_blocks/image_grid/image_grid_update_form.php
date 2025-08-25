<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="image_grid" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <?php
    // Define tabs for the Image Grid block
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
    $block_id = 'image-grid-' . $row->microsite_block_id;
    
    // Include the reusable tab navigation
    include THEME_PATH . 'views/partials/microsite_block_tabs.php';
    ?>

    <div class="tab-content" id="image-grid-<?= $row->microsite_block_id ?>-tabContent">
        
        <!-- Content Tab -->
        <div class="tab-pane fade show active" id="image-grid-<?= $row->microsite_block_id ?>-content" role="tabpanel" aria-labelledby="image-grid-<?= $row->microsite_block_id ?>-content-tab">
            
            <!-- Image Grid Manager (Upload and Management Only) -->
            <?php
            $block_id = $row->microsite_block_id;
            $settings = $row->settings;
            $whitelisted_extensions = $data->microsite_blocks['image_grid']['whitelisted_image_extensions'];
            $size_limit = settings()->links->image_size_limit;
            $uploads_file_key = 'block_images';
            $show_upload = true;
            $show_grid_settings = false; // Moved to Style tab
            $show_visual_settings = false; // Moved to Style tab
            $max_images = 50;
            include THEME_PATH . 'views/partials/microsite_block_components/image_grid_manager.php';
            ?>

            <!-- Open in New Tab Setting -->
            <div class="form-group custom-control custom-switch mt-4">
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
                    <?= l('microsite_image_grid.open_in_new_tab_help') ?? 'Open linked images in a new tab/window' ?>
                </small>
            </div>

        </div>

        <!-- Style Tab -->
        <div class="tab-pane fade" id="image-grid-<?= $row->microsite_block_id ?>-style" role="tabpanel" aria-labelledby="image-grid-<?= $row->microsite_block_id ?>-style-tab">
            
            <?php
            // Define secondary tabs for the style section
            $style_tabs = [
                [
                    'id' => 'layout',
                    'title' => 'Layout',
                    'icon' => 'fas fa-th'
                ],
                [
                    'id' => 'visual',
                    'title' => 'Visual',
                    'icon' => 'fas fa-palette'
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
            $secondary_block_id = 'image-grid-style-' . $row->microsite_block_id;
            $tabs = $style_tabs; // Use style tabs for the secondary navigation
            $block_id = $secondary_block_id; // Override block_id for secondary tabs
            
            // Include the reusable tab navigation for secondary tabs
            include THEME_PATH . 'views/partials/microsite_block_tabs.php';
            ?>

            <div class="tab-content" id="image-grid-style-<?= $row->microsite_block_id ?>-tabContent">
                
                <!-- Layout Sub-tab -->
                <div class="tab-pane fade show active" id="image-grid-style-<?= $row->microsite_block_id ?>-layout" role="tabpanel" aria-labelledby="image-grid-style-<?= $row->microsite_block_id ?>-layout-tab">
                    <!-- Grid Layout Settings -->
                    <div class="form-group">
                        <label for="columns_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-columns fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.columns') ?? 'Columns' ?>
                        </label>
                        <select id="columns_<?= $row->microsite_block_id ?>" name="columns" class="custom-select" onchange="updateImageGridColumns('<?= $row->microsite_block_id ?>', this.value)">
                            <option value="1" <?= ($row->settings->columns ?? 3) == 1 ? 'selected' : '' ?>>1 Column</option>
                            <option value="2" <?= ($row->settings->columns ?? 3) == 2 ? 'selected' : '' ?>>2 Columns</option>
                            <option value="3" <?= ($row->settings->columns ?? 3) == 3 ? 'selected' : '' ?>>3 Columns</option>
                            <option value="4" <?= ($row->settings->columns ?? 3) == 4 ? 'selected' : '' ?>>4 Columns</option>
                            <option value="5" <?= ($row->settings->columns ?? 3) == 5 ? 'selected' : '' ?>>5 Columns</option>
                            <option value="6" <?= ($row->settings->columns ?? 3) == 6 ? 'selected' : '' ?>>6 Columns</option>
                        </select>
                    </div>

                    <!-- Grid Gap -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="grid_gap_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.grid_gap') ?? 'Grid Gap' ?>
                        </label>
                        <input 
                            id="grid_gap_<?= $row->microsite_block_id ?>" 
                            type="range" 
                            min="0" 
                            max="50" 
                            step="5"
                            name="grid_gap" 
                            class="form-control-range" 
                            value="<?= $row->settings->grid_gap ?? 10 ?>" 
                            oninput="updateImageGridGap('<?= $row->microsite_block_id ?>', this.value)"
                        />
                    </div>
                </div>

                <!-- Visual Sub-tab -->
                <div class="tab-pane fade" id="image-grid-style-<?= $row->microsite_block_id ?>-visual" role="tabpanel" aria-labelledby="image-grid-style-<?= $row->microsite_block_id ?>-visual-tab">
                    <!-- Image Height -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="image_height_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.image_height') ?? 'Image Height' ?>
                        </label>
                        <input 
                            id="image_height_<?= $row->microsite_block_id ?>" 
                            type="range" 
                            min="100" 
                            max="500" 
                            step="10"
                            name="image_height" 
                            class="form-control-range" 
                            value="<?= $row->settings->image_height ?? 200 ?>" 
                            oninput="updateImageGridHeight('<?= $row->microsite_block_id ?>', this.value)"
                        />
                    </div>

                    <!-- Aspect Ratio -->
                    <div class="form-group">
                        <label for="aspect_ratio_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.aspect_ratio') ?? 'Aspect Ratio' ?>
                        </label>
                        <select id="aspect_ratio_<?= $row->microsite_block_id ?>" name="aspect_ratio" class="custom-select" onchange="updateImageGridAspectRatio('<?= $row->microsite_block_id ?>', this.value)">
                            <option value="custom" <?= ($row->settings->aspect_ratio ?? '1:1') == 'custom' ? 'selected' : '' ?>>Custom Height</option>
                            <option value="16:9" <?= ($row->settings->aspect_ratio ?? '') == '16:9' ? 'selected' : '' ?>>16:9 (Widescreen)</option>
                            <option value="4:3" <?= ($row->settings->aspect_ratio ?? '') == '4:3' ? 'selected' : '' ?>>4:3 (Standard)</option>
                            <option value="1:1" <?= ($row->settings->aspect_ratio ?? '1:1') == '1:1' ? 'selected' : '' ?>>1:1 (Square)</option>
                            <option value="21:9" <?= ($row->settings->aspect_ratio ?? '') == '21:9' ? 'selected' : '' ?>>21:9 (Ultrawide)</option>
                        </select>
                    </div>

                    <!-- Image Fit -->
                    <div class="form-group">
                        <label for="image_fit_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.image_fit') ?? 'Image Fit' ?>
                        </label>
                        <select id="image_fit_<?= $row->microsite_block_id ?>" name="image_fit" class="custom-select" onchange="updateImageGridFit('<?= $row->microsite_block_id ?>', this.value)">
                            <option value="cover" <?= ($row->settings->image_fit ?? 'cover') == 'cover' ? 'selected' : '' ?>>Cover (Fill & Crop)</option>
                            <option value="contain" <?= ($row->settings->image_fit ?? '') == 'contain' ? 'selected' : '' ?>>Contain (Fit Inside)</option>
                            <option value="fill" <?= ($row->settings->image_fit ?? '') == 'fill' ? 'selected' : '' ?>>Fill (Stretch)</option>
                            <option value="scale-down" <?= ($row->settings->image_fit ?? '') == 'scale-down' ? 'selected' : '' ?>>Scale Down</option>
                        </select>
                    </div>


                    <!-- Border Radius -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="border_radius_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.border_radius') ?? 'Border Radius' ?>
                        </label>
                        <input 
                            id="border_radius_<?= $row->microsite_block_id ?>" 
                            type="range" 
                            min="0" 
                            max="50" 
                            step="1"
                            name="border_radius" 
                            class="form-control-range" 
                            value="<?= $row->settings->border_radius ?? 0 ?>" 
                            oninput="updateImageGridBorderRadius('<?= $row->microsite_block_id ?>', this.value)"
                        />
                    </div>

                    <!-- Hover Effect -->
                    <div class="form-group">
                        <label for="hover_effect_<?= $row->microsite_block_id ?>">
                            <i class="fas fa-fw fa-magic fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.hover_effect') ?? 'Hover Effect' ?>
                        </label>
                        <select id="hover_effect_<?= $row->microsite_block_id ?>" name="hover_effect" class="custom-select" onchange="updateImageGridHoverEffect('<?= $row->microsite_block_id ?>', this.value)">
                            <option value="none" <?= ($row->settings->hover_effect ?? 'none') == 'none' ? 'selected' : '' ?>>None</option>
                            <option value="zoom" <?= ($row->settings->hover_effect ?? '') == 'zoom' ? 'selected' : '' ?>>Zoom In</option>
                            <option value="fade" <?= ($row->settings->hover_effect ?? '') == 'fade' ? 'selected' : '' ?>>Fade</option>
                            <option value="lift" <?= ($row->settings->hover_effect ?? '') == 'lift' ? 'selected' : '' ?>>Lift Up</option>
                        </select>
                    </div>
                </div>

                <!-- Background Sub-tab -->
                <div class="tab-pane fade" id="image-grid-style-<?= $row->microsite_block_id ?>-background" role="tabpanel" aria-labelledby="image-grid-style-<?= $row->microsite_block_id ?>-background-tab">
                    <?php
                    // Set up variables for background component (without accordion)
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
                    ?>
                </div>

                <!-- Border Sub-tab -->
                <div class="tab-pane fade" id="image-grid-style-<?= $row->microsite_block_id ?>-border" role="tabpanel" aria-labelledby="image-grid-style-<?= $row->microsite_block_id ?>-border-tab">
                    <?php
                    // Set up variables for border component (without accordion)
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                    ?>
                    
                    <!-- Additional Image Grid Border Info -->
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Individual Image Borders:</strong> These border settings apply to each individual image in the grid.
                    </div>
                </div>

                <!-- Shadow Sub-tab -->
                <div class="tab-pane fade" id="image-grid-style-<?= $row->microsite_block_id ?>-shadow" role="tabpanel" aria-labelledby="image-grid-style-<?= $row->microsite_block_id ?>-shadow-tab">
                    <?php
                    // Set up variables for shadow component (without accordion)
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                    ?>
                    
                    <!-- Additional Image Grid Shadow Info -->
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Individual Image Shadows:</strong> These shadow settings apply to each individual image in the grid.
                    </div>
                </div>

                <!-- Animation Sub-tab -->
                <div class="tab-pane fade" id="image-grid-style-<?= $row->microsite_block_id ?>-animation" role="tabpanel" aria-labelledby="image-grid-style-<?= $row->microsite_block_id ?>-animation-tab">
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
        <div class="tab-pane fade" id="image-grid-<?= $row->microsite_block_id ?>-display" role="tabpanel" aria-labelledby="image-grid-<?= $row->microsite_block_id ?>-display-tab">
            
            <?php include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; ?>

        </div>

    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<script>
// Custom real-time preview functions for Image Grid Block
// These functions specifically target individual images in the grid

function updateImageGridBorderWidth(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const images = microsite_link.find('.image-grid-image');
            if (images.length) {
                images.css('border-width', value + 'px');
                
                // Set border style and color if border width > 0 and they don't exist
                if (value > 0) {
                    const currentStyle = images.first().css('border-style');
                    const currentColor = images.first().css('border-color');
                    
                    if (!currentStyle || currentStyle === 'none') {
                        images.css('border-style', 'solid');
                    }
                    if (!currentColor || currentColor === 'transparent' || currentColor === 'rgba(0, 0, 0, 0)') {
                        images.css('border-color', '#cccccc');
                    }
                }
            }
        }
    }
}

function updateImageGridBorderStyle(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const images = microsite_link.find('.image-grid-image');
            if (images.length) {
                images.css('border-style', value);
                
                // Ensure border width and color are set if they don't exist
                const currentWidth = images.first().css('border-width');
                const currentColor = images.first().css('border-color');
                
                if (!currentWidth || currentWidth === '0px') {
                    const borderWidth = $(`#block_border_width_${blockId}`).val() || 1;
                    images.css('border-width', borderWidth + 'px');
                }
                if (!currentColor || currentColor === 'transparent' || currentColor === 'rgba(0, 0, 0, 0)') {
                    const borderColor = $(`input[name="border_color"]`).val() || '#cccccc';
                    images.css('border-color', borderColor);
                }
            }
        }
    }
}

function updateImageGridShadow(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get all current values from form inputs
            const offsetX = $(`#block_border_shadow_offset_x_${blockId}`).val() || 0;
            const offsetY = $(`#block_border_shadow_offset_y_${blockId}`).val() || 0;
            const blur = $(`#block_border_shadow_blur_${blockId}`).val() || 0;
            const spread = $(`#block_border_shadow_spread_${blockId}`).val() || 0;
            const color = $(`input[name="border_shadow_color"]`).val() || '#00000010';
            
            // Apply shadow to individual images for proper overflow
            const images = microsite_link.find('.image-grid-image');
            if (images.length) {
                if (offsetX == 0 && offsetY == 0 && blur == 0 && spread == 0) {
                    images.css('box-shadow', 'none');
                } else {
                    const shadowCSS = `${offsetX}px ${offsetY}px ${blur}px ${spread}px ${color}`;
                    images.css('box-shadow', shadowCSS);
                }
            }
        }
    }
}

// Override the default functions to use our custom image grid functions
function updateCanvasBorderWidth(blockId, value) {
    updateImageGridBorderWidth(blockId, value);
}

function updateCanvasBorderStyle(blockId, value) {
    updateImageGridBorderStyle(blockId, value);
}

function updateCanvasBorderRadius(blockId, value) {
    updateImageGridBorderRadius(blockId, value);
}

function updateCanvasShadowComplete(blockId) {
    updateImageGridShadow(blockId);
}

function updateCanvasShadowOffsetX(blockId, value) {
    updateImageGridShadow(blockId);
}

function updateCanvasShadowOffsetY(blockId, value) {
    updateImageGridShadow(blockId);
}

function updateCanvasShadowBlur(blockId, value) {
    updateImageGridShadow(blockId);
}

function updateCanvasShadowSpread(blockId, value) {
    updateImageGridShadow(blockId);
}

// Real-time preview functions for grid and visual settings
function updateImageGridColumns(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const gridContainer = microsite_link.find('.image-grid-container');
            if (gridContainer.length) {
                // Update CSS grid template columns directly
                gridContainer.css('grid-template-columns', `repeat(${value}, 1fr)`);
                
                // Update CSS custom property for responsive grid
                gridContainer.css('--grid-columns', value);
            }
        }
    }
}

function updateImageGridGap(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const gridContainer = microsite_link.find('.image-grid-container');
            if (gridContainer.length) {
                gridContainer.css('gap', value + 'px');
                
                // Update CSS custom property
                gridContainer.css('--grid-gap', value + 'px');
            }
        }
    }
}

function updateImageGridHeight(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Update wrapper height instead of image height
            const wrappers = microsite_link.find('.image-wrapper');
            if (wrappers.length) {
                wrappers.css('height', value + 'px');
            }
        }
    }
}

function updateImageGridAspectRatio(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const wrappers = microsite_link.find('.image-wrapper');
            if (wrappers.length) {
                if (value === 'custom') {
                    // Use custom height setting
                    const customHeight = $(`#image_height_${blockId}`).val() || 200;
                    wrappers.css('height', customHeight + 'px');
                } else {
                    // Calculate height based on aspect ratio
                    const columns = $(`#columns_${blockId}`).val() || 3;
                    let calculatedHeight;
                    
                    switch(value) {
                        case '16:9':
                            calculatedHeight = `calc(100vw / ${columns} * 9 / 16)`;
                            break;
                        case '4:3':
                            calculatedHeight = `calc(100vw / ${columns} * 3 / 4)`;
                            break;
                        case '1:1':
                            calculatedHeight = `calc(100vw / ${columns})`;
                            break;
                        case '21:9':
                            calculatedHeight = `calc(100vw / ${columns} * 9 / 21)`;
                            break;
                        default:
                            calculatedHeight = '200px';
                    }
                    
                    wrappers.css('height', calculatedHeight);
                }
            }
        }
    }
}

function updateImageGridFit(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const images = microsite_link.find('.image-grid-image');
            if (images.length) {
                images.css('object-fit', value);
            }
        }
    }
}

function updateImageGridBorderRadius(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const images = microsite_link.find('.image-grid-image');
            const wrappers = microsite_link.find('.image-wrapper');
            if (images.length) {
                images.css('border-radius', value + 'px');
                // Also update wrapper border radius for consistency
                wrappers.css('border-radius', value + 'px');
            }
        }
    }
}

function updateImageGridHoverEffect(blockId, value) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const images = microsite_link.find('.image-grid-image');
            if (images.length) {
                // Remove existing hover effect classes
                images.removeClass('hover-effect-zoom hover-effect-fade hover-effect-lift hover-effect-none');
                
                // Add new hover effect class
                images.addClass(`hover-effect-${value}`);
            }
        }
    }
}
</script>
