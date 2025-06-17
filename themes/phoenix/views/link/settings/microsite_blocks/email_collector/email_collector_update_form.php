<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="email_collector" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'email_collector_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'email_collector_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-wrench fa-sm mr-1"></i> <?= l('microsite_email_collector.email_collector_header') ?>
    </button>

    <div class="collapse" id="<?= 'email_collector_settings_container_' . $row->microsite_block_id ?>">
        <div class="form-group">
            <label for="<?= 'email_collector_email_placeholder_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.email_placeholder') ?></label>
            <input id="<?= 'email_collector_email_placeholder_' . $row->microsite_block_id ?>" type="text" name="email_placeholder" class="form-control" value="<?= $row->settings->email_placeholder ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_name_placeholder_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.name_placeholder') ?></label>
            <input id="<?= 'email_collector_name_placeholder_' . $row->microsite_block_id ?>" type="text" name="name_placeholder" class="form-control" value="<?= $row->settings->name_placeholder ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_button_text_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-square fa-sm text-muted mr-1"></i> <?= l('microsite_link.button_text') ?></label>
            <input id="<?= 'email_collector_button_text_' . $row->microsite_block_id ?>" type="text" name="button_text" class="form-control" value="<?= $row->settings->button_text ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_success_text_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.success_text') ?></label>
            <input id="<?= 'email_collector_success_text_' . $row->microsite_block_id ?>" type="text" name="success_text" class="form-control" value="<?= $row->settings->success_text ?>" maxlength="256" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_thank_you_url_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.thank_you_url') ?></label>
            <input id="<?= 'email_collector_thank_you_url_' . $row->microsite_block_id ?>" type="url" name="thank_you_url" class="form-control" value="<?= $row->settings->thank_you_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
        </div>

        <div class="form-group custom-control custom-switch">
            <input
                    type="checkbox"
                    class="custom-control-input"
                    id="<?= 'email_collector_show_agreement_' . $row->microsite_block_id ?>"
                    name="show_agreement"
                <?= $row->settings->show_agreement ? 'checked="checked"' : null ?>
            >
            <label class="custom-control-label" for="<?= 'email_collector_show_agreement_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.show_agreement') ?></label>
            <div><small class="form-text text-muted"><?= l('microsite_email_collector.show_agreement_help') ?></small></div>
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_agreement_text_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.agreement_text') ?></label>
            <input id="<?= 'email_collector_agreement_text_' . $row->microsite_block_id ?>" type="text" name="agreement_text" class="form-control" value="<?= $row->settings->agreement_text ?>" maxlength="256" />
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_agreement_url_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.agreement_url') ?></label>
            <input id="<?= 'email_collector_agreement_url_' . $row->microsite_block_id ?>" type="text" name="agreement_url" class="form-control" value="<?= $row->settings->agreement_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'email_collector_data_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'email_collector_data_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-database fa-sm mr-1"></i> <?= l('microsite_block.data_header') ?>
    </button>

    <div class="collapse" id="<?= 'email_collector_data_container_' . $row->microsite_block_id ?>">
        <div class="alert alert-info">
            <i class="fas fa-fw fa-sm fa-info-circle mr-1"></i> <?= l('microsite_block.data_help') ?>
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_mailchimp_api_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.mailchimp_api') ?></label>
            <input id="<?= 'email_collector_mailchimp_api_' . $row->microsite_block_id ?>" type="text" name="mailchimp_api" class="form-control" value="<?= $row->settings->mailchimp_api ?>" maxlength="64" />
            <small class="form-text text-muted"><?= l('microsite_email_collector.mailchimp_api_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_mailchimp_api_list_' . $row->microsite_block_id ?>"><?= l('microsite_email_collector.mailchimp_api_list') ?></label>
            <input id="<?= 'email_collector_mailchimp_api_list_' . $row->microsite_block_id ?>" type="text" name="mailchimp_api_list" class="form-control" value="<?= $row->settings->mailchimp_api_list ?>" maxlength="64" />
            <small class="form-text text-muted"><?= l('microsite_email_collector.mailchimp_api_list_help') ?></small>
        </div>

        <hr />

        <div class="form-group">
            <label for="<?= 'phone_collector_email_notification_' . $row->microsite_block_id ?>"><?= l('microsite_block.email_notification') ?></label>
            <input type="text" id="<?= 'phone_collector_email_notification_' . $row->microsite_block_id ?>" name="email_notification" class="form-control" value="<?= $row->settings->email_notification ?? null ?>" placeholder="<?= l('global.email_placeholder') ?>" maxlength="320" />
            <small class="form-text text-muted"><?= l('microsite_block.email_notification_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_webhook_url_' . $row->microsite_block_id ?>"><?= l('microsite_block.webhook_url') ?></label>
            <input id="<?= 'email_collector_webhook_url_' . $row->microsite_block_id ?>" type="text" name="webhook_url" class="form-control" value="<?= $row->settings->webhook_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
            <small class="form-text text-muted"><?= l('microsite_block.webhook_url_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'button_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-square-check fa-sm mr-1"></i> <?= l('microsite_link.button_header') ?>
    </button>

    <div class="collapse" id="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <div class="form-group">
            <label for="<?= 'email_collector_name_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
            <input id="<?= 'email_collector_name_' . $row->microsite_block_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?>" maxlength="128" required="required" />
        </div>

        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->thumbnail_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?>">
            <label for="<?= 'email_collector_image_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_link.image') ?></label>
            <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
                'id'=> 'email_collector_image_' . $row->microsite_block_id,
                'uploads_file_key' => 'block_thumbnail_images',
                'file_key' => 'image',
                'already_existing_image' => $row->settings->image,
                'image_container' => 'image',
                'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['email_collector']['whitelisted_thumbnail_image_extensions']),
            ]) ?>
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['email_collector']['whitelisted_thumbnail_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_icon_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
            <input id="<?= 'email_collector_icon_' . $row->microsite_block_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_text_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_color') ?></label>
            <input id="<?= 'email_collector_text_color_' . $row->microsite_block_id ?>" type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
            <div class="text_color_pickr"></div>
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

        <div class="form-group">
            <label for="<?= 'email_collector_background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
            <input id="<?= 'email_collector_background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'email_collector_animation_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
            <select id="<?= 'email_collector_animation_' . $row->microsite_block_id ?>" name="animation" class="custom-select">
                <option value="false" <?= !$row->settings->animation ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                    <option value="<?= $animation ?>" <?= $row->settings->animation == $animation ? 'selected="selected"' : null ?>><?= $animation ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group" data-animation="<?= implode(',', require APP_PATH . 'includes/microsite_animations.php') ?>">
            <label for="<?= 'email_collector_animation_runs_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-play-circle fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
            <select id="<?= 'email_collector_animation_runs_' . $row->microsite_block_id ?>" name="animation_runs" class="custom-select">
                <option value="repeat-1" <?= $row->settings->animation_runs == 'repeat-1' ? 'selected="selected"' : null ?>>1</option>
                <option value="repeat-2" <?= $row->settings->animation_runs == 'repeat-2' ? 'selected="selected"' : null ?>>2</option>
                <option value="repeat-3" <?= $row->settings->animation_runs == 'repeat-3' ? 'selected="selected"' : null ?>>3</option>
                <option value="infinite" <?= $row->settings->animation_runs == 'infinite' ? 'selected="selected"' : null ?>><?= l('microsite_link.animation_runs_infinite') ?></option>
            </select>
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
