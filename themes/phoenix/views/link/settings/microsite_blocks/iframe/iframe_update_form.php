<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="iframe" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'iframe_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('global.url') ?></label>
        <input id="<?= 'iframe_location_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="location_url" value="<?= $row->location_url ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="<?= 'iframe_height_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_iframe.height') ?> (px)</label>
        <input id="<?= 'iframe_height_' . $row->microsite_block_id ?>" type="number" class="form-control" name="height" value="<?= $row->settings->height ?? 400 ?>" min="100" max="1000" placeholder="400" />
        <small class="form-text text-muted"><?= l('microsite_iframe.height_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'iframe_display_mode_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('microsite_iframe.display_mode') ?></label>
        <select id="<?= 'iframe_display_mode_' . $row->microsite_block_id ?>" name="display_mode" class="form-control">
            <option value="page" <?= ($row->settings->display_mode ?? 'page') == 'page' ? 'selected' : '' ?>><?= l('microsite_iframe.display_mode_page') ?></option>
            <option value="modal" <?= ($row->settings->display_mode ?? 'page') == 'modal' ? 'selected' : '' ?>><?= l('microsite_iframe.display_mode_modal') ?></option>
        </select>
        <small class="form-text text-muted"><?= l('microsite_iframe.display_mode_help') ?></small>
    </div>

    <div id="<?= 'modal_settings_' . $row->microsite_block_id ?>" style="<?= ($row->settings->display_mode ?? 'page') == 'modal' ? '' : 'display: none;' ?>">
        <div class="form-group custom-control custom-switch">
            <input
                    id="<?= 'iframe_open_in_new_tab_' . $row->microsite_block_id ?>"
                    name="open_in_new_tab" type="checkbox"
                    class="custom-control-input"
                <?= $row->settings->open_in_new_tab ? 'checked="checked"' : null ?>
            >
            <label class="custom-control-label" for="<?= 'iframe_open_in_new_tab_' . $row->microsite_block_id ?>"><?= l('microsite_link.open_in_new_tab') ?></label>
        </div>

        <div class="form-group">
            <label for="<?= 'iframe_name_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
            <input id="<?= 'iframe_name_' . $row->microsite_block_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?? 'View Content' ?>" maxlength="128" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'iframe_icon_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
            <input id="<?= 'iframe_icon_' . $row->microsite_block_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?? 'fas fa-external-link-alt' ?>" placeholder="<?= l('global.icon_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'iframe_text_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_color') ?></label>
            <input id="<?= 'iframe_text_color_' . $row->microsite_block_id ?>" type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?? '#ffffff' ?>" required="required" />
            <div class="text_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'iframe_text_alignment_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                    <div class="col-6">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'active"' : null?>">
                            <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'checked="checked"' : null ?> />
                            <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'iframe_background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
            <input id="<?= 'iframe_background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?? '#007bff' ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'iframe_animation_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
            <select id="<?= 'iframe_animation_' . $row->microsite_block_id ?>" name="animation" class="custom-select">
                <option value="false" <?= ($row->settings->animation ?? 'false') == 'false' ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                    <option value="<?= $animation ?>" <?= ($row->settings->animation ?? 'false') == $animation ? 'selected="selected"' : null ?>><?= $animation ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group" data-animation="<?= implode(',', require APP_PATH . 'includes/microsite_animations.php') ?>">
            <label for="<?= 'iframe_animation_runs_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-play-circle fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
            <select id="<?= 'iframe_animation_runs_' . $row->microsite_block_id ?>" name="animation_runs" class="custom-select">
                <option value="repeat-1" <?= ($row->settings->animation_runs ?? 'repeat-1') == 'repeat-1' ? 'selected="selected"' : null ?>>1</option>
                <option value="repeat-2" <?= ($row->settings->animation_runs ?? 'repeat-1') == 'repeat-2' ? 'selected="selected"' : null ?>>2</option>
                <option value="repeat-3" <?= ($row->settings->animation_runs ?? 'repeat-1') == 'repeat-3' ? 'selected="selected"' : null ?>>3</option>
                <option value="infinite" <?= ($row->settings->animation_runs ?? 'repeat-1') == 'infinite' ? 'selected="selected"' : null ?>><?= l('microsite_link.animation_runs_infinite') ?></option>
            </select>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_container_' . $row->microsite_block_id ?>">
            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_header') ?>
        </button>

        <div class="collapse" id="<?= 'border_container_' . $row->microsite_block_id ?>">
            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'iframe_border_width_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_width') ?></label>
                <input id="<?= 'iframe_border_width_' . $row->microsite_block_id ?>" type="range" min="0" max="5" class="form-control-range" name="border_width" value="<?= $row->settings->border_width ?? 0 ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="<?= 'iframe_border_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_color') ?></label>
                <input id="<?= 'iframe_border_color_' . $row->microsite_block_id ?>" type="hidden" name="border_color" class="form-control" value="<?= $row->settings->border_color ?? '#000000' ?>" required="required" />
                <div class="border_color_pickr"></div>
            </div>

            <div class="form-group">
                <label for="<?= 'iframe_border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_radius') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius ?? 'rounded') == 'straight' ? 'active"' : null?>">
                            <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius ?? 'rounded') == 'straight' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_radius_straight') ?>
                        </label>
                    </div>
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius ?? 'rounded') == 'round' ? 'active' : null?>">
                            <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius ?? 'rounded') == 'round' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('microsite_link.border_radius_round') ?>
                        </label>
                    </div>
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius ?? 'rounded') == 'rounded' ? 'active' : null?>">
                            <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius ?? 'rounded') == 'rounded' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('microsite_link.border_radius_rounded') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="<?= 'iframe_border_style_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_style') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                        <div class="col-4">
                            <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_style ?? 'solid') == $border_style ? 'active"' : null?>">
                                <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($row->settings->border_style ?? 'solid') == $border_style ? 'checked="checked"' : null?> />
                                <?= l('microsite_link.border_style_' . $border_style) ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_shadow_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_shadow_container_' . $row->microsite_block_id ?>">
            <i class="fas fa-fw fa-cloud fa-sm mr-1"></i> <?= l('microsite_link.border_shadow_header') ?>
        </button>

        <div class="collapse" id="<?= 'border_shadow_container_' . $row->microsite_block_id ?>">
            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'iframe_border_shadow_offset_x_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_x') ?></label>
                <input id="<?= 'iframe_border_shadow_offset_x_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_x" value="<?= $row->settings->border_shadow_offset_x ?? 0 ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'iframe_border_shadow_offset_y_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
                <input id="<?= 'iframe_border_shadow_offset_y_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_y" value="<?= $row->settings->border_shadow_offset_y ?? 0 ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'iframe_border_shadow_blur_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
                <input id="<?= 'iframe_border_shadow_blur_' . $row->microsite_block_id ?>" type="range" min="0" max="20" class="form-control-range" name="border_shadow_blur" value="<?= $row->settings->border_shadow_blur ?? 0 ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'iframe_border_shadow_spread_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
                <input id="<?= 'iframe_border_shadow_spread_' . $row->microsite_block_id ?>" type="range" min="0" max="10" class="form-control-range" name="border_shadow_spread" value="<?= $row->settings->border_shadow_spread ?? 0 ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="<?= 'iframe_border_shadow_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_color') ?></label>
                <input id="<?= 'iframe_border_shadow_color_' . $row->microsite_block_id ?>" type="hidden" name="border_shadow_color" class="form-control" value="<?= $row->settings->border_shadow_color ?? '#000000' ?>" required="required" />
                <div class="border_shadow_color_pickr"></div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('<?= 'iframe_display_mode_' . $row->microsite_block_id ?>').addEventListener('change', function() {
        const modalSettings = document.getElementById('<?= 'modal_settings_' . $row->microsite_block_id ?>');
        if (this.value === 'modal') {
            modalSettings.style.display = 'block';
        } else {
            modalSettings.style.display = 'none';
        }
    });
    </script>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
