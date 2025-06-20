<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="header" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="header_background_type_<?= $row->microsite_block_id ?>"><i class="fas fa-fw fa-sm fa-images text-muted mr-1"></i> <?= l('microsite_header.background_type') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-12 col-lg-6">
                <label class="btn btn-light btn-block text-truncate <?= $row->settings->background_type == 'image' ? 'active"' : null?>">
                    <input type="radio" name="background_type" value="image" class="custom-control-input" <?= $row->settings->background_type == 'image' ? 'checked="checked"' : null?> required="required" />
                    <i class="fas fa-fill fa-fw fa-sm mr-1"></i> <?= l('global.image') ?>
                </label>
            </div>

            <div class="col-12 col-lg-6">
                <label class="btn btn-light btn-block text-truncate <?= $row->settings->background_type == 'video' ? 'active"' : null?>">
                    <input type="radio" name="background_type" value="video" class="custom-control-input" <?= $row->settings->background_type == 'video' ? 'checked="checked"' : null?> required="required" />
                    <i class="fas fa-video fa-fw fa-sm mr-1"></i> <?= l('microsite_header.video') ?>
                </label>
            </div>
        </div>
    </div>

    <div data-background-type="image" class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->background_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->background_size_limit) ?>">
        <label for="<?= 'header_background_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_header.background') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
            'id'=> 'header_background_' . $row->microsite_block_id,
            'uploads_file_key' => 'backgrounds',
            'file_key' => 'background',
            'already_existing_image' => $row->settings->background,
            'image_container' => 'background',
            'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['header']['whitelisted_image_extensions']),
            'input_data' => 'data-crop'
        ]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['header']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
    </div>

    <div data-background-type="image" class="form-group">
        <label for="<?= 'header_background_alt_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_alt') ?></label>
        <input id="<?= 'header_background_alt_' . $row->microsite_block_id ?>" type="text" class="form-control" name="background_alt" value="<?= $row->settings->background_alt ?>" maxlength="100" />
        <small class="form-text text-muted"><?= l('microsite_link.image_alt_help') ?></small>
    </div>

    <div data-background-type="video" class="form-group">
        <label for="<?= 'header_video_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> <?= l('microsite_header.video_url') ?></label>
        <input id="<?= 'header_video_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="video_url" value="<?= $row->settings->video_url ?>" maxlength="2048" placeholder="<?= l('microsite_header.video_url_placeholder') ?>" required="required" />
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->avatar_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->avatar_size_limit) ?>">
        <label for="<?= 'header_avatar_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_header.avatar') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
            'id'=> 'header_avatar_' . $row->microsite_block_id,
            'uploads_file_key' => 'avatars',
            'file_key' => 'avatar',
            'already_existing_image' => $row->settings->avatar,
            'image_container' => 'avatar',
            'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['header']['whitelisted_image_extensions']),
            'input_data' => 'data-crop data-aspect-ratio="1"'
        ]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['header']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'header_avatar_alt_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_alt') ?></label>
        <input id="<?= 'header_avatar_alt_' . $row->microsite_block_id ?>" type="text" class="form-control" name="avatar_alt" value="<?= $row->settings->avatar_alt ?>" maxlength="100" />
        <small class="form-text text-muted"><?= l('microsite_link.image_alt_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'header_avatar_size_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.size') ?></label>
        <select id="<?= 'header_avatar_size_' . $row->microsite_block_id ?>" name="avatar_size" class="custom-select">
            <option value="75" <?= $row->settings->avatar_size == '75' ? 'selected="selected"' : null ?>>75x75px</option>
            <option value="100" <?= $row->settings->avatar_size == '100' ? 'selected="selected"' : null ?>>100x100px</option>
        </select>
    </div>

    <div class="form-group">
        <label for="<?= 'block_object_fit_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-print fa-sm text-muted mr-1"></i> <?= l('microsite_link.object_fit') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['cover', 'contain', 'fill'] as $object_fit): ?>
            <div class="col-4">
                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->object_fit  ?? null) == $object_fit ? 'active"' : null?>">
                    <input type="radio" name="object_fit" value="<?= $object_fit ?>" class="custom-control-input" <?= ($row->settings->object_fit  ?? null) == $object_fit ? 'checked="checked"' : null?> />
                    <?= l('microsite_link.object_fit.' . $object_fit) ?>
                </label>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="form-group">
        <label for="<?= 'block_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
        <input id="<?= 'block_location_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="location_url" value="<?= $row->location_url ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                id="<?= 'block_open_in_new_tab_' . $row->microsite_block_id ?>"
                name="open_in_new_tab" type="checkbox"
                class="custom-control-input"
            <?= $row->settings->open_in_new_tab ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'block_open_in_new_tab_' . $row->microsite_block_id ?>"><?= l('microsite_link.open_in_new_tab') ?></label>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_header') ?>
    </button>

    <div class="collapse" id="<?= 'border_container_' . $row->microsite_block_id ?>">
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_width_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_width') ?></label>
            <input id="<?= 'block_border_width_' . $row->microsite_block_id ?>" type="range" min="0" max="5" class="form-control-range" name="border_width" value="<?= $row->settings->border_width ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_color') ?></label>
            <input id="<?= 'block_border_color_' . $row->microsite_block_id ?>" type="hidden" name="border_color" class="form-control" value="<?= $row->settings->border_color ?>" required="required" />
            <div class="border_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_radius') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'active"' : null?>">
                        <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('microsite_link.border_radius_straight') ?>
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'round' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'round' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('microsite_link.border_radius_round') ?>
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('microsite_link.border_radius_rounded') ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_style_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_style') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_style  ?? null) == $border_style ? 'active"' : null?>">
                            <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($row->settings->border_style  ?? null) == $border_style ? 'checked="checked"' : null?> />
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
            <label for="<?= 'block_border_shadow_offset_x_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_x') ?></label>
            <input id="<?= 'block_border_shadow_offset_x_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_x" value="<?= $row->settings->border_shadow_offset_x ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_offset_y_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_offset_y') ?></label>
            <input id="<?= 'block_border_shadow_offset_y_' . $row->microsite_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_y" value="<?= $row->settings->border_shadow_offset_y ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_blur_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_blur') ?></label>
            <input id="<?= 'block_border_shadow_blur_' . $row->microsite_block_id ?>" type="range" min="0" max="20" class="form-control-range" name="border_shadow_blur" value="<?= $row->settings->border_shadow_blur ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_spread_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_spread') ?></label>
            <input id="<?= 'block_border_shadow_spread_' . $row->microsite_block_id ?>" type="range" min="0" max="10" class="form-control-range" name="border_shadow_spread" value="<?= $row->settings->border_shadow_spread ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_shadow_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.border_shadow_color') ?></label>
            <input id="<?= 'block_border_shadow_color_' . $row->microsite_block_id ?>" type="hidden" name="border_shadow_color" class="form-control" value="<?= $row->settings->border_shadow_color ?>" required="required" />
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
        'use strict';

        type_handler('form[name="update_microsite_"] input[name="background_type"]', 'data-background-type');
        document.querySelector('form[name="update_microsite_"] input[name="background_type"]') && document.querySelectorAll('form[name="update_microsite_"] input[name="background_type"]').forEach(element => element.addEventListener('change', () => { type_handler('form[name="update_microsite_"] input[name="background_type"]', 'data-background-type');}));
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
