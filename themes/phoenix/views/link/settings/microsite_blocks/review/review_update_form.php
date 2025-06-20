<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="review" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'review_title_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_review.title') ?></label>
        <input id="<?= 'review_title_' . $row->microsite_block_id ?>" type="text" name="title" class="form-control" value="<?= $row->settings->title ?>" maxlength="128" />
    </div>

    <div class="form-group">
        <label for="<?= 'review_description_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('microsite_link.description') ?></label>
        <textarea id="<?= 'review_description_' . $row->microsite_block_id ?>" name="description" class="form-control" maxlength="1024"><?= $row->settings->description ?></textarea>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->image_size_limit) ?>">
        <label for="<?= 'review_image_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_review.image') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
            'id'=> 'review_image_' . $row->microsite_block_id,
            'uploads_file_key' => 'block_images',
            'file_key' => 'image',
            'already_existing_image' => $row->settings->image,
            'image_container' => 'image',
            'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['review']['whitelisted_image_extensions']),
            'input_data' => 'data-crop data-aspect-ratio="1"'
        ]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['review']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'review_author_name_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> <?= l('microsite_review.author_name') ?></label>
        <input id="<?= 'review_author_name_' . $row->microsite_block_id ?>" type="text" name="author_name" class="form-control" value="<?= $row->settings->author_name ?>" maxlength="128" required="required" />
    </div>

    <div class="form-group">
        <label for="<?= 'review_author_description_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-user-tag fa-sm text-muted mr-1"></i> <?= l('microsite_review.author_description') ?></label>
        <input id="<?= 'review_author_description_' . $row->microsite_block_id ?>" type="text" name="author_description" class="form-control" value="<?= $row->settings->author_description ?>" maxlength="128" />
    </div>

    <div class="form-group">
        <label for="<?= 'review_stars_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> <?= l('microsite_review.stars') ?></label>
        <input id="<?= 'review_stars_' . $row->microsite_block_id ?>" type="number" min="1" max="5" name="stars" class="form-control" value="<?= $row->settings->stars ?>" required="required" />
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'button_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-square-check fa-sm mr-1"></i> <?= l('microsite_link.button_header') ?>
    </button>

    <div class="collapse" id="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <div class="form-group">
            <label for="<?= 'review_title_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_review.title_color') ?></label>
            <input id="<?= 'review_title_color_' . $row->microsite_block_id ?>" type="hidden" name="title_color" class="form-control" value="<?= $row->settings->title_color ?>" required="required" />
            <div class="title_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'review_description_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.description_color') ?></label>
            <input id="<?= 'review_description_color_' . $row->microsite_block_id ?>" type="hidden" name="description_color" class="form-control" value="<?= $row->settings->description_color ?>" required="required" />
            <div class="description_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'review_author_name_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_review.author_name_color') ?></label>
            <input id="<?= 'review_author_name_color_' . $row->microsite_block_id ?>" type="hidden" name="author_name_color" class="form-control" value="<?= $row->settings->author_name_color ?>" required="required" />
            <div class="author_name_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'review_author_description_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_review.author_description_color') ?></label>
            <input id="<?= 'review_author_description_color_' . $row->microsite_block_id ?>" type="hidden" name="author_description_color" class="form-control" value="<?= $row->settings->author_description_color ?>" required="required" />
            <div class="author_description_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'review_stars_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_review.stars_color') ?></label>
            <input id="<?= 'review_stars_color_' . $row->microsite_block_id ?>" type="hidden" name="stars_color" class="form-control" value="<?= $row->settings->stars_color ?>" required="required" />
            <div class="stars_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'review_background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
            <input id="<?= 'review_background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_text_alignment_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                    <div class="col-6">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'active"' : null?>">
                            <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'checked="checked"' : null ?> />
                            <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
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
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
