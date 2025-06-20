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

                    <!-- Grid Layout Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#grid_layout_container_create" aria-expanded="false" aria-controls="grid_layout_container_create">
                        <i class="fas fa-fw fa-th fa-sm mr-1"></i> <?= l('microsite_image_grid.grid_layout_settings') ?>
                    </button>

                    <div class="collapse" id="grid_layout_container_create">
                        <!-- Columns -->
                        <div class="form-group">
                            <label for="columns_create"><i class="fas fa-fw fa-columns fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.columns') ?></label>
                            <select id="columns_create" name="columns" class="custom-select">
                                <option value="1"><?= l('microsite_image_grid.columns_1') ?></option>
                                <option value="2"><?= l('microsite_image_grid.columns_2') ?></option>
                                <option value="3" selected><?= l('microsite_image_grid.columns_3') ?></option>
                                <option value="4"><?= l('microsite_image_grid.columns_4') ?></option>
                                <option value="5"><?= l('microsite_image_grid.columns_5') ?></option>
                                <option value="6"><?= l('microsite_image_grid.columns_6') ?></option>
                            </select>
                        </div>

                        <!-- Grid Gap -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="grid_gap_create"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.grid_gap') ?></label>
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

                    <!-- Visual & Layout Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#visual_settings_container_create" aria-expanded="false" aria-controls="visual_settings_container_create">
                        <i class="fas fa-fw fa-palette fa-sm mr-1"></i> <?= l('microsite_image_grid.visual_layout_settings') ?>
                    </button>

                    <div class="collapse" id="visual_settings_container_create">
                        <!-- Image Height -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="image_height_create"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.image_height') ?></label>
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
                            <label for="aspect_ratio_create"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.aspect_ratio') ?></label>
                            <select id="aspect_ratio_create" name="aspect_ratio" class="custom-select">
                                <option value="custom"><?= l('microsite_image_grid.aspect_ratio_custom') ?></option>
                                <option value="16:9"><?= l('microsite_image_grid.aspect_ratio_16_9') ?></option>
                                <option value="4:3"><?= l('microsite_image_grid.aspect_ratio_4_3') ?></option>
                                <option value="1:1" selected><?= l('microsite_image_grid.aspect_ratio_1_1') ?></option>
                                <option value="21:9"><?= l('microsite_image_grid.aspect_ratio_21_9') ?></option>
                            </select>
                        </div>

                        <!-- Image Fit -->
                        <div class="form-group">
                            <label for="image_fit_create"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.image_fit') ?></label>
                            <select id="image_fit_create" name="image_fit" class="custom-select">
                                <option value="cover" selected><?= l('microsite_image_grid.image_fit_cover') ?></option>
                                <option value="contain"><?= l('microsite_image_grid.image_fit_contain') ?></option>
                                <option value="fill"><?= l('microsite_image_grid.image_fit_fill') ?></option>
                                <option value="scale-down"><?= l('microsite_image_grid.image_fit_scale_down') ?></option>
                            </select>
                        </div>

                        <!-- Border Radius -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="border_radius_create"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.border_radius') ?></label>
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
                            <label for="hover_effect_create"><i class="fas fa-fw fa-magic fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.hover_effect') ?></label>
                            <select id="hover_effect_create" name="hover_effect" class="custom-select">
                                <option value="none" selected><?= l('microsite_image_grid.hover_effect_none') ?></option>
                                <option value="zoom"><?= l('microsite_image_grid.hover_effect_zoom') ?></option>
                                <option value="fade"><?= l('microsite_image_grid.hover_effect_fade') ?></option>
                                <option value="lift"><?= l('microsite_image_grid.hover_effect_lift') ?></option>
                            </select>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
