<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_image_grid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_image_grid.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_image_grid" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="image_grid" />

                    <div class="notification-container"></div>

                    <!-- Image Upload Section -->
                    <div class="form-group">
                        <label for="new_images_create">
                            <i class="fas fa-fw fa-images fa-sm text-muted mr-1"></i> <?= l('global.images') ?>
                        </label>
                        <input 
                            id="new_images_create" 
                            type="file" 
                            name="new_images[]" 
                            multiple 
                            accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_grid']['whitelisted_image_extensions']) ?>" 
                            class="form-control-file" 
                            required="required"
                        />
                        <small class="form-text text-muted">
                            <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_grid']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?>
                            <br>Hold Ctrl/Cmd to select multiple images.
                        </small>
                    </div>

                    <!-- Basic Settings -->
                    <div class="form-group custom-control custom-switch">
                        <input
                            id="open_in_new_tab_create"
                            name="open_in_new_tab" 
                            type="checkbox"
                            class="custom-control-input"
                        >
                        <label class="custom-control-label" for="open_in_new_tab_create"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.open_in_new_tab') ?></label>
                    </div>

                    <!-- Layout Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#layout_settings_container_create" aria-expanded="false" aria-controls="layout_settings_container_create">
                        <i class="fas fa-fw fa-th fa-sm mr-1"></i> <?= l('microsite_image_grid.layout_settings') ?? 'Layout Settings' ?>
                    </button>

                    <div class="collapse" id="layout_settings_container_create">
                        <!-- Columns -->
                        <div class="form-group">
                            <label for="columns_create"><i class="fas fa-fw fa-columns fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.columns') ?? 'Columns' ?></label>
                            <select id="columns_create" name="columns" class="custom-select">
                                <option value="1">1 Column</option>
                                <option value="2">2 Columns</option>
                                <option value="3" selected>3 Columns</option>
                                <option value="4">4 Columns</option>
                                <option value="5">5 Columns</option>
                                <option value="6">6 Columns</option>
                            </select>
                        </div>

                        <!-- Grid Gap -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="grid_gap_create"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.grid_gap') ?? 'Grid Gap' ?></label>
                            <input 
                                id="grid_gap_create" 
                                type="range" 
                                min="0" 
                                max="50" 
                                step="5"
                                name="grid_gap" 
                                class="form-control-range" 
                                value="10" 
                                required="required"
                            />
                        </div>
                    </div>

                    <!-- Visual Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#visual_settings_container_create" aria-expanded="false" aria-controls="visual_settings_container_create">
                        <i class="fas fa-fw fa-palette fa-sm mr-1"></i> <?= l('microsite_image_grid.visual_settings') ?? 'Visual Settings' ?>
                    </button>

                    <div class="collapse" id="visual_settings_container_create">
                        <!-- Image Height -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="image_height_create"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.image_height') ?? 'Image Height' ?></label>
                            <input 
                                id="image_height_create" 
                                type="range" 
                                min="100" 
                                max="500" 
                                step="10"
                                name="image_height" 
                                class="form-control-range" 
                                value="200" 
                                required="required"
                            />
                        </div>

                        <!-- Aspect Ratio -->
                        <div class="form-group">
                            <label for="aspect_ratio_create"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.aspect_ratio') ?? 'Aspect Ratio' ?></label>
                            <select id="aspect_ratio_create" name="aspect_ratio" class="custom-select">
                                <option value="custom">Custom Height</option>
                                <option value="16:9">16:9 (Widescreen)</option>
                                <option value="4:3">4:3 (Standard)</option>
                                <option value="1:1" selected>1:1 (Square)</option>
                                <option value="21:9">21:9 (Ultrawide)</option>
                            </select>
                        </div>

                        <!-- Image Fit -->
                        <div class="form-group">
                            <label for="image_fit_create"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.image_fit') ?? 'Image Fit' ?></label>
                            <select id="image_fit_create" name="image_fit" class="custom-select">
                                <option value="cover" selected>Cover (Fill & Crop)</option>
                                <option value="contain">Contain (Fit Inside)</option>
                                <option value="fill">Fill (Stretch)</option>
                                <option value="scale-down">Scale Down</option>
                            </select>
                        </div>

                        <!-- Border Radius -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="border_radius_create"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.border_radius') ?? 'Border Radius' ?></label>
                            <input 
                                id="border_radius_create" 
                                type="range" 
                                min="0" 
                                max="50" 
                                step="1"
                                name="border_radius" 
                                class="form-control-range" 
                                value="0" 
                                required="required"
                            />
                        </div>

                        <!-- Hover Effect -->
                        <div class="form-group">
                            <label for="hover_effect_create"><i class="fas fa-fw fa-magic fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.hover_effect') ?? 'Hover Effect' ?></label>
                            <select id="hover_effect_create" name="hover_effect" class="custom-select">
                                <option value="none" selected>None</option>
                                <option value="zoom">Zoom In</option>
                                <option value="fade">Fade</option>
                                <option value="lift">Lift Up</option>
                            </select>
                        </div>
                    </div>

                    <!-- Individual Image Border & Shadow -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#individual_styling_container_create" aria-expanded="false" aria-controls="individual_styling_container_create">
                        <i class="fas fa-fw fa-paint-brush fa-sm mr-1"></i> Individual Image Border & Shadow
                    </button>

                    <div class="collapse" id="individual_styling_container_create">
                        
                        <!-- Border Settings -->
                        <div class="form-group">
                            <label><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> Border Settings</label>
                            <small class="form-text text-muted">These settings apply to each individual image in the grid.</small>
                        </div>

                        <!-- Border Width -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="border_width_create">Border Width</label>
                            <input 
                                id="border_width_create" 
                                type="range" 
                                min="0" 
                                max="10" 
                                step="1"
                                name="border_width" 
                                class="form-control-range" 
                                value="0" 
                            />
                        </div>

                        <!-- Border Color -->
                        <?php
                        $block_id = 'create';
                        $field_name = 'border_color';
                        $label = 'Border Color';
                        $icon = 'fas fa-fill';
                        $default_color = '#ffffff';
                        $current_color = '#ffffff';
                        include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                        ?>

                        <!-- Border Style -->
                        <div class="form-group">
                            <label for="border_style_create">Border Style</label>
                            <select id="border_style_create" name="border_style" class="custom-select">
                                <option value="solid" selected>Solid</option>
                                <option value="dashed">Dashed</option>
                                <option value="dotted">Dotted</option>
                                <option value="double">Double</option>
                            </select>
                        </div>

                        <!-- Shadow Settings -->
                        <div class="form-group mt-4">
                            <label><i class="fas fa-fw fa-clone fa-sm text-muted mr-1"></i> Shadow Settings</label>
                            <small class="form-text text-muted">Shadows will display outside each individual image container.</small>
                        </div>

                        <!-- Shadow Offset X -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="border_shadow_offset_x_create">Shadow Offset X</label>
                            <input 
                                id="border_shadow_offset_x_create" 
                                type="range" 
                                min="-20" 
                                max="20" 
                                step="1"
                                name="border_shadow_offset_x" 
                                class="form-control-range" 
                                value="0" 
                            />
                        </div>

                        <!-- Shadow Offset Y -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="border_shadow_offset_y_create">Shadow Offset Y</label>
                            <input 
                                id="border_shadow_offset_y_create" 
                                type="range" 
                                min="-20" 
                                max="20" 
                                step="1"
                                name="border_shadow_offset_y" 
                                class="form-control-range" 
                                value="0" 
                            />
                        </div>

                        <!-- Shadow Blur -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="border_shadow_blur_create">Shadow Blur</label>
                            <input 
                                id="border_shadow_blur_create" 
                                type="range" 
                                min="0" 
                                max="50" 
                                step="1"
                                name="border_shadow_blur" 
                                class="form-control-range" 
                                value="0" 
                            />
                        </div>

                        <!-- Shadow Spread -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="border_shadow_spread_create">Shadow Spread</label>
                            <input 
                                id="border_shadow_spread_create" 
                                type="range" 
                                min="-20" 
                                max="20" 
                                step="1"
                                name="border_shadow_spread" 
                                class="form-control-range" 
                                value="0" 
                            />
                        </div>

                        <!-- Shadow Color -->
                        <?php
                        $block_id = 'create';
                        $field_name = 'border_shadow_color';
                        $label = 'Shadow Color';
                        $icon = 'fas fa-fill';
                        $default_color = '#00000010';
                        $current_color = '#00000010';
                        $include_opacity = true;
                        include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                        ?>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
// Custom real-time preview functions for Image Grid Block Create Modal
// These functions specifically target individual images in the grid

function updateImageGridBorderWidthCreate(value) {
    // For create modal, we don't have a live preview yet, but we can prepare the functions
    // The real-time preview will work once the block is created and being edited
    console.log('Image Grid Border Width:', value + 'px');
}

function updateImageGridBorderStyleCreate(value) {
    console.log('Image Grid Border Style:', value);
}

function updateImageGridShadowCreate() {
    const offsetX = $('#border_shadow_offset_x_create').val() || 0;
    const offsetY = $('#border_shadow_offset_y_create').val() || 0;
    const blur = $('#border_shadow_blur_create').val() || 0;
    const spread = $('#border_shadow_spread_create').val() || 0;
    const color = $('#border_shadow_color_create').val() || '#00000010';
    
    console.log('Image Grid Shadow:', `${offsetX}px ${offsetY}px ${blur}px ${spread}px ${color}`);
}

// Add event listeners for the create modal form elements
$(document).ready(function() {
    // Border width slider
    $('#border_width_create').on('input change', function() {
        updateImageGridBorderWidthCreate(this.value);
    });
    
    // Border style radio buttons
    $('input[name="border_style"]').on('change', function() {
        updateImageGridBorderStyleCreate(this.value);
    });
    
    // Shadow sliders
    $('#border_shadow_offset_x_create, #border_shadow_offset_y_create, #border_shadow_blur_create, #border_shadow_spread_create').on('input change', function() {
        updateImageGridShadowCreate();
    });
    
    // Shadow color picker
    $('#border_shadow_color_create').on('change', function() {
        updateImageGridShadowCreate();
    });
});
</script>
