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

                    <!-- Image Upload Section -->
                    <div class="form-group">
                        <label for="create_new_images">
                            <i class="fas fa-fw fa-images fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_slider.images') ?? 'Slider Images' ?>
                        </label>
                        <input
                            id="create_new_images"
                            name="new_images[]"
                            type="file"
                            multiple
                            accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_slider']['whitelisted_image_extensions']) ?>"
                            class="form-control-file"
                        />
                        <small class="form-text text-muted">
                            <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_slider']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?>
                            <br>Hold Ctrl/Cmd to select multiple images.
                        </small>
                    </div>

                    <!-- Basic Slider Settings -->
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

                    <div class="form-group">
                        <label for="create_autoplay_interval"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.autoplay_interval') ?></label>
                        <div class="input-group">
                            <input
                                id="create_autoplay_interval"
                                name="autoplay_interval"
                                type="number"
                                class="form-control"
                                value="5"
                                min="1"
                                max="30"
                            />
                            <div class="input-group-append">
                                <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#create_layout_settings_container" aria-expanded="false" aria-controls="create_layout_settings_container">
                        <i class="fas fa-fw fa-th-large fa-sm mr-1"></i> <?= l('microsite_image_slider.layout_settings') ?? 'Layout Settings' ?>
                        <i class="fas fa-chevron-down float-right"></i>
                    </button>

                    <div class="collapse" id="create_layout_settings_container">
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="create_slider_height"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.slider_height') ?></label>
                            <input
                                id="create_slider_height"
                                name="slider_height"
                                type="range"
                                class="form-control-range"
                                value="300"
                                min="200"
                                max="800"
                                step="10"
                            />
                        </div>

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

                        <div class="form-group">
                            <label for="create_slides_per_view"><i class="fas fa-fw fa-th fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.slides_per_view') ?></label>
                            <select id="create_slides_per_view" name="slides_per_view" class="custom-select">
                                <option value="1" selected><?= l('microsite_image_slider.slides_per_view_1') ?? '1' ?></option>
                                <option value="2"><?= l('microsite_image_slider.slides_per_view_2') ?? '2' ?></option>
                                <option value="3"><?= l('microsite_image_slider.slides_per_view_3') ?? '3' ?></option>
                                <option value="4"><?= l('microsite_image_slider.slides_per_view_4') ?? '4' ?></option>
                            </select>
                        </div>

                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="create_slide_gap"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.slide_gap') ?></label>
                            <input
                                id="create_slide_gap"
                                name="slide_gap"
                                type="range"
                                class="form-control-range"
                                value="0"
                                min="0"
                                max="50"
                                step="1"
                            />
                        </div>
                    </div>

                    <!-- Visual Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#create_visual_settings_container" aria-expanded="false" aria-controls="create_visual_settings_container">
                        <i class="fas fa-fw fa-eye fa-sm mr-1"></i> <?= l('microsite_image_slider.visual_settings') ?? 'Visual Settings' ?>
                        <i class="fas fa-chevron-down float-right"></i>
                    </button>

                    <div class="collapse" id="create_visual_settings_container">
                        <div class="form-group">
                            <label for="create_image_fit"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.image_fit') ?></label>
                            <select id="create_image_fit" name="image_fit" class="custom-select">
                                <option value="cover" selected><?= l('microsite_image_slider.image_fit_cover') ?></option>
                                <option value="contain"><?= l('microsite_image_slider.image_fit_contain') ?></option>
                                <option value="fill"><?= l('microsite_image_slider.image_fit_fill') ?></option>
                                <option value="scale-down"><?= l('microsite_image_slider.image_fit_scale_down') ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="create_transition_type"><i class="fas fa-fw fa-exchange-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.transition_type') ?></label>
                            <select id="create_transition_type" name="transition_type" class="custom-select">
                                <option value="slide" selected><?= l('microsite_image_slider.transition_type_slide') ?></option>
                                <option value="fade"><?= l('microsite_image_slider.transition_type_fade') ?></option>
                                <option value="loop"><?= l('microsite_image_slider.transition_type_loop') ?></option>
                            </select>
                        </div>

                        <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                            <label for="create_transition_speed"><i class="fas fa-fw fa-tachometer-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.transition_speed') ?></label>
                            <input
                                id="create_transition_speed"
                                name="transition_speed"
                                type="range"
                                class="form-control-range"
                                value="600"
                                min="200"
                                max="2000"
                                step="100"
                            />
                        </div>

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

                        <div class="form-group">
                            <label for="create_hover_effect"><i class="fas fa-fw fa-hand-pointer fa-sm text-muted mr-1"></i> <?= l('microsite_image_slider.hover_effect') ?? 'Hover Effect' ?></label>
                            <select id="create_hover_effect" name="hover_effect" class="custom-select">
                                <option value="none" selected><?= l('microsite_image_slider.hover_effect_none') ?? 'None' ?></option>
                                <option value="zoom"><?= l('microsite_image_slider.hover_effect_zoom') ?? 'Zoom' ?></option>
                                <option value="fade"><?= l('microsite_image_slider.hover_effect_fade') ?? 'Fade' ?></option>
                                <option value="lift"><?= l('microsite_image_slider.hover_effect_lift') ?? 'Lift' ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Background Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#create_background_settings_container" aria-expanded="false" aria-controls="create_background_settings_container">
                        <i class="fas fa-fw fa-fill-drip fa-sm mr-1"></i> <?= l('microsite_image_slider.background_settings') ?? 'Background Settings' ?>
                        <i class="fas fa-chevron-down float-right"></i>
                    </button>

                    <div class="collapse" id="create_background_settings_container">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Slider Background:</strong> These settings apply to the slider container background, visible behind or around your images.
                        </div>

                        <?php
                        $block_id = 'create';
                        $field_name = 'background_color';
                        $label = l('microsite_image_slider.background_color') ?? 'Background Color';
                        $icon = 'fas fa-fill';
                        $default_color = '#ffffff';
                        $current_color = '';
                        include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                        ?>

                        <div class="form-group">
                            <label for="create_background_gradient">
                                <i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> 
                                <?= l('microsite_image_slider.background_gradient') ?? 'Background Gradient' ?>
                            </label>
                            <input
                                id="create_background_gradient"
                                name="background_gradient"
                                type="text"
                                class="form-control"
                                placeholder="linear-gradient(45deg, #ff6b6b, #4ecdc4)"
                            />
                            <small class="form-text text-muted">
                                <?= l('microsite_image_slider.background_gradient_help') ?? 'CSS gradient (overrides background color if set)' ?>
                            </small>
                        </div>
                    </div>

                    <!-- Individual Image Border & Shadow Settings -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#create_individual_styling_container" aria-expanded="false" aria-controls="create_individual_styling_container">
                        <i class="fas fa-fw fa-border-all fa-sm mr-1"></i> <?= l('microsite_image_slider.individual_styling') ?? 'Individual Image Border & Shadow' ?>
                        <i class="fas fa-chevron-down float-right"></i>
                    </button>

                    <div class="collapse" id="create_individual_styling_container">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Individual Image Styling:</strong> These settings apply to each image in the slider individually, creating consistent styling across all slides.
                        </div>

                        <!-- Border Settings -->
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-fw fa-border-all fa-sm mr-1"></i>
                            <?= l('microsite_image_slider.border_settings') ?? 'Border Settings' ?>
                        </h6>

                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="create_border_width">
                                <i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> 
                                <?= l('microsite_image_slider.border_width') ?? 'Border Width' ?>
                            </label>
                            <input
                                id="create_border_width"
                                name="border_width"
                                type="range"
                                class="form-control-range"
                                value="0"
                                min="0"
                                max="20"
                                step="1"
                            />
                        </div>

                        <?php
                        $block_id = 'create';
                        $field_name = 'border_color';
                        $label = l('microsite_image_slider.border_color') ?? 'Border Color';
                        $icon = 'fas fa-palette';
                        $default_color = '#000000';
                        $current_color = '#000000';
                        include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                        ?>

                        <div class="form-group">
                            <label for="create_border_style">
                                <i class="fas fa-fw fa-minus fa-sm text-muted mr-1"></i> 
                                <?= l('microsite_image_slider.border_style') ?? 'Border Style' ?>
                            </label>
                            <select id="create_border_style" name="border_style" class="custom-select">
                                <option value="solid" selected><?= l('microsite_image_slider.border_style_solid') ?? 'Solid' ?></option>
                                <option value="dashed"><?= l('microsite_image_slider.border_style_dashed') ?? 'Dashed' ?></option>
                                <option value="dotted"><?= l('microsite_image_slider.border_style_dotted') ?? 'Dotted' ?></option>
                                <option value="double"><?= l('microsite_image_slider.border_style_double') ?? 'Double' ?></option>
                            </select>
                        </div>

                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="create_border_radius">
                                <i class="fas fa-fw fa-square fa-sm text-muted mr-1"></i> 
                                <?= l('microsite_image_slider.border_radius') ?? 'Border Radius' ?>
                            </label>
                            <input
                                id="create_border_radius"
                                name="border_radius"
                                type="range"
                                class="form-control-range"
                                value="0"
                                min="0"
                                max="50"
                                step="1"
                            />
                        </div>

                        <!-- Shadow Settings -->
                        <h6 class="text-muted mb-3 mt-4">
                            <i class="fas fa-fw fa-clone fa-sm mr-1"></i>
                            <?= l('microsite_image_slider.shadow_settings') ?? 'Shadow Settings' ?>
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                    <label for="create_shadow_offset_x">
                                        <i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> 
                                        <?= l('microsite_image_slider.shadow_offset_x') ?? 'Shadow Offset X' ?>
                                    </label>
                                    <input
                                        id="create_shadow_offset_x"
                                        name="shadow_offset_x"
                                        type="range"
                                        class="form-control-range"
                                        value="0"
                                        min="-20"
                                        max="20"
                                        step="1"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                    <label for="create_shadow_offset_y">
                                        <i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> 
                                        <?= l('microsite_image_slider.shadow_offset_y') ?? 'Shadow Offset Y' ?>
                                    </label>
                                    <input
                                        id="create_shadow_offset_y"
                                        name="shadow_offset_y"
                                        type="range"
                                        class="form-control-range"
                                        value="0"
                                        min="-20"
                                        max="20"
                                        step="1"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                    <label for="create_shadow_blur">
                                        <i class="fas fa-fw fa-circle fa-sm text-muted mr-1"></i> 
                                        <?= l('microsite_image_slider.shadow_blur') ?? 'Shadow Blur' ?>
                                    </label>
                                    <input
                                        id="create_shadow_blur"
                                        name="shadow_blur"
                                        type="range"
                                        class="form-control-range"
                                        value="0"
                                        min="0"
                                        max="50"
                                        step="1"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                    <label for="create_shadow_spread">
                                        <i class="fas fa-fw fa-expand-alt fa-sm text-muted mr-1"></i> 
                                        <?= l('microsite_image_slider.shadow_spread') ?? 'Shadow Spread' ?>
                                    </label>
                                    <input
                                        id="create_shadow_spread"
                                        name="shadow_spread"
                                        type="range"
                                        class="form-control-range"
                                        value="0"
                                        min="-20"
                                        max="20"
                                        step="1"
                                    />
                                </div>
                            </div>
                        </div>

                        <?php
                        $block_id = 'create';
                        $field_name = 'shadow_color';
                        $label = l('microsite_image_slider.shadow_color') ?? 'Shadow Color';
                        $icon = 'fas fa-palette';
                        $default_color = '#00000010';
                        $current_color = '#000000';
                        $include_opacity = true; // Shadow colors often need opacity
                        include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                        ?>
                    </div>

                    <?php include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; ?>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.create') ?></button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
