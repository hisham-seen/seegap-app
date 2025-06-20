<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_image_slider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_image_slider.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_image_slider" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="image_slider" />

                    <div class="notification-container"></div>

                    <!-- Image Upload Section -->
                    <div class="form-group">
                        <label for="create_new_images">
                            <i class="fas fa-fw fa-images fa-sm text-muted mr-1"></i> <?= l('global.images') ?>
                        </label>
                        <input 
                            id="create_new_images" 
                            type="file" 
                            name="new_images[]" 
                            multiple 
                            accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_slider']['whitelisted_image_extensions']) ?>" 
                            class="form-control-file" 
                        />
                        <small class="form-text text-muted">
                            <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_slider']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?>
                            <br>Hold Ctrl/Cmd to select multiple images.
                        </small>
                    </div>

                    <!-- Basic Settings -->
                    <div class="form-group custom-control custom-switch">
                        <input
                            id="create_autoplay"
                            name="autoplay" 
                            type="checkbox"
                            class="custom-control-input"
                            checked="checked"
                        >
                        <label class="custom-control-label" for="create_autoplay"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.autoplay') ?></label>
                    </div>

                    <div class="form-group custom-control custom-switch">
                        <input
                            id="create_display_arrows"
                            name="display_arrows" 
                            type="checkbox"
                            class="custom-control-input"
                            checked="checked"
                        >
                        <label class="custom-control-label" for="create_display_arrows"><i class="fas fa-fw fa-chevron-left fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.display_arrows') ?></label>
                    </div>

                    <div class="form-group custom-control custom-switch">
                        <input
                            id="create_display_pagination"
                            name="display_pagination" 
                            type="checkbox"
                            class="custom-control-input"
                            checked="checked"
                        >
                        <label class="custom-control-label" for="create_display_pagination"><i class="fas fa-fw fa-circle fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.display_pagination') ?></label>
                    </div>

                    <div class="form-group custom-control custom-switch">
                        <input
                            id="create_open_in_new_tab"
                            name="open_in_new_tab" 
                            type="checkbox"
                            class="custom-control-input"
                        >
                        <label class="custom-control-label" for="create_open_in_new_tab"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.open_in_new_tab') ?></label>
                    </div>

                    <div class="form-group">
                        <label for="create_autoplay_interval"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.autoplay_interval') ?></label>
                        <div class="input-group">
                            <input 
                                id="create_autoplay_interval" 
                                type="number" 
                                min="1" 
                                max="20" 
                                name="autoplay_interval" 
                                class="form-control" 
                                value="5" 
                                required="required" 
                            />
                            <div class="input-group-append">
                                <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Visual & Layout Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#create_visual_settings_container" aria-expanded="false" aria-controls="create_visual_settings_container">
                        <i class="fas fa-fw fa-palette fa-sm mr-1"></i> <?= l('microsite_image_slider.visual_layout_settings') ?>
                    </button>

                    <div class="collapse" id="create_visual_settings_container">
                        <!-- Slider Height -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="create_slider_height"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.slider_height') ?></label>
                            <input 
                                id="create_slider_height" 
                                type="range" 
                                min="200" 
                                max="800" 
                                step="10"
                                name="slider_height" 
                                class="form-control-range" 
                                value="300" 
                                required="required"
                            />
                        </div>

                        <!-- Aspect Ratio -->
                        <div class="form-group">
                            <label for="create_aspect_ratio"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.aspect_ratio') ?></label>
                            <select id="create_aspect_ratio" name="aspect_ratio" class="custom-select">
                                <option value="custom" selected><?= l('microsite_image_slider.aspect_ratio_custom') ?></option>
                                <option value="16:9"><?= l('microsite_image_slider.aspect_ratio_16_9') ?></option>
                                <option value="4:3"><?= l('microsite_image_slider.aspect_ratio_4_3') ?></option>
                                <option value="1:1"><?= l('microsite_image_slider.aspect_ratio_1_1') ?></option>
                                <option value="21:9"><?= l('microsite_image_slider.aspect_ratio_21_9') ?></option>
                            </select>
                        </div>

                        <!-- Image Fit -->
                        <div class="form-group">
                            <label for="create_image_fit"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.image_fit') ?></label>
                            <select id="create_image_fit" name="image_fit" class="custom-select">
                                <option value="cover" selected><?= l('microsite_image_slider.image_fit_cover') ?></option>
                                <option value="contain"><?= l('microsite_image_slider.image_fit_contain') ?></option>
                                <option value="fill"><?= l('microsite_image_slider.image_fit_fill') ?></option>
                                <option value="scale-down"><?= l('microsite_image_slider.image_fit_scale_down') ?></option>
                            </select>
                        </div>

                        <!-- Border Radius -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="create_border_radius"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.border_radius') ?></label>
                            <input 
                                id="create_border_radius" 
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
                    </div>

                    <!-- Slider Behavior Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#create_behavior_settings_container" aria-expanded="false" aria-controls="create_behavior_settings_container">
                        <i class="fas fa-fw fa-cogs fa-sm mr-1"></i> <?= l('microsite_image_slider.slider_behavior_settings') ?>
                    </button>

                    <div class="collapse" id="create_behavior_settings_container">
                        <!-- Transition Type -->
                        <div class="form-group">
                            <label for="create_transition_type"><i class="fas fa-fw fa-exchange-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.transition_type') ?></label>
                            <select id="create_transition_type" name="transition_type" class="custom-select">
                                <option value="slide" selected><?= l('microsite_image_slider.transition_type_slide') ?></option>
                                <option value="fade"><?= l('microsite_image_slider.transition_type_fade') ?></option>
                                <option value="loop"><?= l('microsite_image_slider.transition_type_loop') ?></option>
                            </select>
                        </div>

                        <!-- Transition Speed -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                            <label for="create_transition_speed"><i class="fas fa-fw fa-tachometer-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.transition_speed') ?></label>
                            <input 
                                id="create_transition_speed" 
                                type="range" 
                                min="200" 
                                max="2000" 
                                step="100"
                                name="transition_speed" 
                                class="form-control-range" 
                                value="600" 
                                required="required"
                            />
                        </div>

                        <!-- Slides Per View -->
                        <div class="form-group">
                            <label for="create_slides_per_view"><i class="fas fa-fw fa-th fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.slides_per_view') ?></label>
                            <select id="create_slides_per_view" name="slides_per_view" class="custom-select">
                                <option value="1" selected><?= l('microsite_image_slider.slides_per_view_1') ?></option>
                                <option value="2"><?= l('microsite_image_slider.slides_per_view_2') ?></option>
                                <option value="3"><?= l('microsite_image_slider.slides_per_view_3') ?></option>
                                <option value="4"><?= l('microsite_image_slider.slides_per_view_4') ?></option>
                            </select>
                        </div>

                        <!-- Slide Gap -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="create_slide_gap"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.slide_gap') ?></label>
                            <input 
                                id="create_slide_gap" 
                                type="range" 
                                min="0" 
                                max="50" 
                                step="5"
                                name="slide_gap" 
                                class="form-control-range" 
                                value="0" 
                                required="required"
                            />
                        </div>

                        <!-- Behavior Toggles -->
                        <div class="form-group custom-control custom-switch">
                            <input
                                id="create_pause_on_hover"
                                name="pause_on_hover" 
                                type="checkbox"
                                class="custom-control-input"
                                checked="checked"
                            >
                            <label class="custom-control-label" for="create_pause_on_hover"><i class="fas fa-fw fa-pause fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.pause_on_hover') ?></label>
                        </div>

                        <div class="form-group custom-control custom-switch">
                            <input
                                id="create_infinite_loop"
                                name="infinite_loop" 
                                type="checkbox"
                                class="custom-control-input"
                                checked="checked"
                            >
                            <label class="custom-control-label" for="create_infinite_loop"><i class="fas fa-fw fa-sync fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.infinite_loop') ?></label>
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
