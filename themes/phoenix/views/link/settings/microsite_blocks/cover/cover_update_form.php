<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="cover" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'cover_name_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
        <input id="<?= 'cover_name_' . $row->microsite_block_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?? '' ?>" maxlength="128" required="required" />
    </div>

    <div class="form-group">
        <label for="<?= 'cover_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
        <input id="<?= 'cover_location_url_' . $row->microsite_block_id ?>" type="url" name="location_url" class="form-control" value="<?= $row->location_url ?? '' ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        <small class="form-text text-muted"><?= l('microsite_link.location_url_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="<?= 'cover_open_in_new_tab_' . $row->microsite_block_id ?>" name="open_in_new_tab" type="checkbox" class="custom-control-input" <?= ($row->settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>>
        <label class="custom-control-label" for="<?= 'cover_open_in_new_tab_' . $row->microsite_block_id ?>"><?= l('microsite_link.open_in_new_tab') ?></label>
    </div>

    <div class="form-group">
        <label for="<?= 'cover_background_type_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_cover.background_type') ?></label>
        <select id="<?= 'cover_background_type_' . $row->microsite_block_id ?>" name="background_type" class="custom-select">
            <option value="image" <?= ($row->settings->background_type ?? 'image') == 'image' ? 'selected="selected"' : null ?>><?= l('microsite_cover.background_type_image') ?></option>
            <option value="video" <?= ($row->settings->background_type ?? 'image') == 'video' ? 'selected="selected"' : null ?>><?= l('microsite_cover.background_type_video') ?></option>
        </select>
    </div>

    <div data-background-type="image">
        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->background_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->background_size_limit) ?>">
            <label for="<?= 'cover_background_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_cover.background') ?></label>
            <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
                'id'=> 'cover_background_' . $row->microsite_block_id,
                'uploads_file_key' => 'backgrounds',
                'file_key' => 'background',
                'already_existing_image' => $row->settings->background,
                'image_container' => 'background',
                'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions']),
            ]) ?>
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->background_size_limit) ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'cover_background_alt_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-comment-alt fa-sm text-muted mr-1"></i> <?= l('microsite_cover.background_alt') ?></label>
            <input id="<?= 'cover_background_alt_' . $row->microsite_block_id ?>" type="text" name="background_alt" class="form-control" value="<?= $row->settings->background_alt ?? '' ?>" maxlength="256" />
            <small class="form-text text-muted"><?= l('microsite_cover.background_alt_help') ?></small>
        </div>
    </div>

    <div data-background-type="video">
        <div class="form-group">
            <label for="<?= 'cover_video_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> <?= l('microsite_cover.video_url') ?></label>
            <input id="<?= 'cover_video_url_' . $row->microsite_block_id ?>" type="url" name="video_url" class="form-control" value="<?= $row->settings->video_url ?? '' ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('microsite_cover.video_url_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="<?= 'cover_video_controls_' . $row->microsite_block_id ?>" name="video_controls" type="checkbox" class="custom-control-input" <?= ($row->settings->video_controls ?? 0) ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="<?= 'cover_video_controls_' . $row->microsite_block_id ?>"><?= l('microsite_cover.video_controls') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="<?= 'cover_video_autoplay_' . $row->microsite_block_id ?>" name="video_autoplay" type="checkbox" class="custom-control-input" <?= ($row->settings->video_autoplay ?? 1) ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="<?= 'cover_video_autoplay_' . $row->microsite_block_id ?>"><?= l('microsite_cover.video_autoplay') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="<?= 'cover_video_loop_' . $row->microsite_block_id ?>" name="video_loop" type="checkbox" class="custom-control-input" <?= ($row->settings->video_loop ?? 1) ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="<?= 'cover_video_loop_' . $row->microsite_block_id ?>"><?= l('microsite_cover.video_loop') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="<?= 'cover_video_muted_' . $row->microsite_block_id ?>" name="video_muted" type="checkbox" class="custom-control-input" <?= ($row->settings->video_muted ?? 1) ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="<?= 'cover_video_muted_' . $row->microsite_block_id ?>"><?= l('microsite_cover.video_muted') ?></label>
        </div>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->avatar_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->avatar_size_limit) ?>">
        <label for="<?= 'cover_avatar_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-user-circle fa-sm text-muted mr-1"></i> <?= l('microsite_cover.avatar') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
            'id'=> 'cover_avatar_' . $row->microsite_block_id,
            'uploads_file_key' => 'avatars',
            'file_key' => 'avatar',
            'already_existing_image' => $row->settings->avatar,
            'image_container' => 'avatar',
            'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions']),
        ]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'cover_avatar_alt_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-comment-alt fa-sm text-muted mr-1"></i> <?= l('microsite_cover.avatar_alt') ?></label>
        <input id="<?= 'cover_avatar_alt_' . $row->microsite_block_id ?>" type="text" name="avatar_alt" class="form-control" value="<?= $row->settings->avatar_alt ?? '' ?>" maxlength="256" />
        <small class="form-text text-muted"><?= l('microsite_cover.avatar_alt_help') ?></small>
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="<?= 'cover_avatar_size_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_cover.avatar_size') ?></label>
        <input id="<?= 'cover_avatar_size_' . $row->microsite_block_id ?>" type="range" min="50" max="200" class="form-control-range" name="avatar_size" value="<?= $row->settings->avatar_size ?? 100 ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="<?= 'cover_object_fit_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('microsite_cover.object_fit') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['cover', 'contain', 'fill'] as $object_fit): ?>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->object_fit ?? 'cover') == $object_fit ? 'active"' : null?>">
                        <input type="radio" name="object_fit" value="<?= $object_fit ?>" class="custom-control-input" <?= ($row->settings->object_fit ?? 'cover') == $object_fit ? 'checked="checked"' : null ?> />
                        <?= l('microsite_cover.object_fit_' . $object_fit) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="form-group">
        <label for="<?= 'cover_border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_radius') ?></label>
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

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_header') ?>
    </button>

    <div class="collapse" id="<?= 'border_container_' . $row->microsite_block_id ?>">
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'cover_border_width_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_width') ?></label>
            <input id="<?= 'cover_border_width_' . $row->microsite_block_id ?>" type="range" min="0" max="5" class="form-control-range" name="border_width" value="<?= $row->settings->border_width ?? 0 ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'cover_border_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_color') ?></label>
            <input id="<?= 'cover_border_color_' . $row->microsite_block_id ?>" type="hidden" name="border_color" class="form-control" value="<?= $row->settings->border_color ?? '#ffffff' ?>" required="required" />
            <div class="border_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'cover_border_style_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_style') ?></label>
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
            <label for="<?= 'cover_border_shadow_offset_x_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_x') ?></label>
            <input id="<?= 'cover_border_shadow_offset_x_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_x" value="<?= $row->settings->border_shadow_offset_x ?? 0 ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'cover_border_shadow_offset_y_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
            <input id="<?= 'cover_border_shadow_offset_y_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_y" value="<?= $row->settings->border_shadow_offset_y ?? 0 ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'cover_border_shadow_blur_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
            <input id="<?= 'cover_border_shadow_blur_' . $row->microsite_block_id ?>" type="range" min="0" max="20" class="form-control-range" name="border_shadow_blur" value="<?= $row->settings->border_shadow_blur ?? 0 ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'cover_border_shadow_spread_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
            <input id="<?= 'cover_border_shadow_spread_' . $row->microsite_block_id ?>" type="range" min="0" max="10" class="form-control-range" name="border_shadow_spread" value="<?= $row->settings->border_shadow_spread ?? 0 ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'cover_border_shadow_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_color') ?></label>
            <input id="<?= 'cover_border_shadow_color_' . $row->microsite_block_id ?>" type="hidden" name="border_shadow_color" class="form-control" value="<?= $row->settings->border_shadow_color ?? '#00000010' ?>" required="required" />
            <div class="border_shadow_color_pickr"></div>
        </div>
    </div>

    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<?php ob_start() ?>
<script>
    type_handler('<?= '#cover_background_type_' . $row->microsite_block_id ?>', 'data-background-type');
    document.querySelector('<?= '#cover_background_type_' . $row->microsite_block_id ?>') && document.querySelectorAll('<?= '#cover_background_type_' . $row->microsite_block_id ?>').forEach(element => element.addEventListener('change', () => { type_handler('<?= '#cover_background_type_' . $row->microsite_block_id ?>', 'data-background-type'); }));
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
