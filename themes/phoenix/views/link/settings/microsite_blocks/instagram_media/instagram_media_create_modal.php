<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_instagram_media" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_instagram_media.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <form name="create_microsite_instagram_media" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="instagram_media" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="instagram_media_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_instagram_media.location_url') ?></label>
                        <input id="instagram_media_url" type="url" class="form-control" name="instagram_media_url" required="required" maxlength="2048" placeholder="<?= l('microsite_instagram_media.location_url_placeholder') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="instagram_display_mode"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('microsite_iframe.display_mode') ?></label>
                        <select id="instagram_display_mode" name="display_mode" class="form-control">
                            <option value="page" selected><?= l('microsite_iframe.display_mode_page') ?></option>
                            <option value="modal"><?= l('microsite_iframe.display_mode_modal') ?></option>
                        </select>
                        <small class="form-text text-muted"><?= l('microsite_iframe.display_mode_help') ?></small>
                    </div>

                    <div id="modal_settings" style="display: none;">
                        <div class="form-group custom-control custom-switch">
                            <input id="instagram_open_in_new_tab" name="open_in_new_tab" type="checkbox" class="custom-control-input">
                            <label class="custom-control-label" for="instagram_open_in_new_tab"><?= l('microsite_link.open_in_new_tab') ?></label>
                        </div>

                        <div class="form-group">
                            <label for="instagram_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
                            <input id="instagram_name" type="text" name="name" class="form-control" value="View Instagram Post" maxlength="128" required="required" />
                        </div>

                        <div class="form-group">
                            <label for="instagram_icon"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
                            <input id="instagram_icon" type="text" name="icon" class="form-control" value="fab fa-instagram" placeholder="<?= l('global.icon_placeholder') ?>" />
                            <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
                        </div>

                        <div class="form-group">
                            <label for="instagram_text_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_color') ?></label>
                            <input id="instagram_text_color" type="hidden" name="text_color" class="form-control" value="#ffffff" required="required" />
                            <div class="text_color_pickr"></div>
                        </div>

                        <div class="form-group">
                            <label for="instagram_text_alignment"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
                            <div class="row btn-group-toggle" data-toggle="buttons">
                                <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                                    <div class="col-6">
                                        <label class="btn btn-light btn-block text-truncate <?= $text_alignment == 'center' ? 'active' : '' ?>">
                                            <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= $text_alignment == 'center' ? 'checked="checked"' : '' ?> />
                                            <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                                        </label>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="instagram_background_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
                            <input id="instagram_background_color" type="hidden" name="background_color" class="form-control" value="#E4405F" required="required" />
                            <div class="background_color_pickr"></div>
                        </div>

                        <div class="form-group">
                            <label for="instagram_animation"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
                            <select id="instagram_animation" name="animation" class="custom-select">
                                <option value="false" selected><?= l('global.none') ?></option>
                                <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                                    <option value="<?= $animation ?>"><?= $animation ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group" data-animation="<?= implode(',', require APP_PATH . 'includes/microsite_animations.php') ?>">
                            <label for="instagram_animation_runs"><i class="fas fa-fw fa-play-circle fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
                            <select id="instagram_animation_runs" name="animation_runs" class="custom-select">
                                <option value="repeat-1" selected>1</option>
                                <option value="repeat-2">2</option>
                                <option value="repeat-3">3</option>
                                <option value="infinite"><?= l('microsite_link.animation_runs_infinite') ?></option>
                            </select>
                        </div>

                        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#border_container" aria-expanded="false" aria-controls="border_container">
                            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_header') ?>
                        </button>

                        <div class="collapse" id="border_container">
                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="instagram_border_width"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_width') ?></label>
                                <input id="instagram_border_width" type="range" min="0" max="5" class="form-control-range" name="border_width" value="0" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="instagram_border_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_color') ?></label>
                                <input id="instagram_border_color" type="hidden" name="border_color" class="form-control" value="#000000" required="required" />
                                <div class="border_color_pickr"></div>
                            </div>

                            <div class="form-group">
                                <label for="instagram_border_radius"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_radius') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate">
                                            <input type="radio" name="border_radius" value="straight" class="custom-control-input" />
                                            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_radius_straight') ?>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate">
                                            <input type="radio" name="border_radius" value="round" class="custom-control-input" />
                                            <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('microsite_link.border_radius_round') ?>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate active">
                                            <input type="radio" name="border_radius" value="rounded" class="custom-control-input" checked="checked" />
                                            <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('microsite_link.border_radius_rounded') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="instagram_border_style"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_style') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                                        <div class="col-4">
                                            <label class="btn btn-light btn-block text-truncate <?= $border_style == 'solid' ? 'active' : '' ?>">
                                                <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= $border_style == 'solid' ? 'checked="checked"' : '' ?> />
                                                <?= l('microsite_link.border_style_' . $border_style) ?>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#border_shadow_container" aria-expanded="false" aria-controls="border_shadow_container">
                            <i class="fas fa-fw fa-cloud fa-sm mr-1"></i> <?= l('microsite_link.border_shadow_header') ?>
                        </button>

                        <div class="collapse" id="border_shadow_container">
                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="instagram_border_shadow_offset_x"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_x') ?></label>
                                <input id="instagram_border_shadow_offset_x" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_x" value="0" required="required" />
                            </div>

                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="instagram_border_shadow_offset_y"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
                                <input id="instagram_border_shadow_offset_y" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_y" value="0" required="required" />
                            </div>

                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="instagram_border_shadow_blur"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
                                <input id="instagram_border_shadow_blur" type="range" min="0" max="20" class="form-control-range" name="border_shadow_blur" value="0" required="required" />
                            </div>

                            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                <label for="instagram_border_shadow_spread"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
                                <input id="instagram_border_shadow_spread" type="range" min="0" max="10" class="form-control-range" name="border_shadow_spread" value="0" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="instagram_border_shadow_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_color') ?></label>
                                <input id="instagram_border_shadow_color" type="hidden" name="border_shadow_color" class="form-control" value="#000000" required="required" />
                                <div class="border_shadow_color_pickr"></div>
                            </div>
                        </div>
                    </div>

                    <script>
                    document.getElementById('instagram_display_mode').addEventListener('change', function() {
                        const modalSettings = document.getElementById('modal_settings');
                        if (this.value === 'modal') {
                            modalSettings.style.display = 'block';
                        } else {
                            modalSettings.style.display = 'none';
                        }
                    });
                    </script>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
