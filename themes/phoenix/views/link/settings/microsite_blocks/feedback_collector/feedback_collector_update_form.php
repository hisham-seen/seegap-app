<?php defined('ALTUMCODE') || die() ?>

<form id="update_microsite_<?= $row->microsite_block_id ?>" name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="feedback_collector" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'feedback_collector_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'feedback_collector_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-wrench fa-sm mr-1"></i> <?= l('microsite_feedback_collector.feedback_collector_header') ?>
    </button>

    <div class="collapse" id="<?= 'feedback_collector_settings_container_' . $row->microsite_block_id ?>">
        <div class="form-group">
            <label><?= l('microsite_feedback_collector.questions') ?></label>
            <p class="text-muted"><?= l('microsite_feedback_collector.questions_help') ?></p>
            <div id="questions_container_<?= $row->microsite_block_id ?>">
                <?php if(isset($row->settings->questions) && is_array($row->settings->questions) && count($row->settings->questions)): ?>
                    <?php foreach($row->settings->questions as $key => $question): ?>
                        <div class="card mb-3 question-item">
                            <div class="card-body">
                                <div class="form-group">
                                    <label><?= l('microsite_feedback_collector.question_type') ?></label>
                                    <select class="form-control question-type" name="question_type[]">
                                        <option value="text" <?= $question->type == 'text' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_text') ?></option>
                                        <option value="textarea" <?= $question->type == 'textarea' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_textarea') ?></option>
                                        <option value="rating_star" <?= $question->type == 'rating_star' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_rating_star') ?></option>
                                        <option value="rating_number" <?= $question->type == 'rating_number' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_rating_number') ?></option>
                                        <option value="rating_emoji" <?= $question->type == 'rating_emoji' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_rating_emoji') ?></option>
                                        <option value="checkbox" <?= $question->type == 'checkbox' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_checkbox') ?></option>
                                        <option value="radio" <?= $question->type == 'radio' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_radio') ?></option>
                                        <option value="dropdown" <?= $question->type == 'dropdown' ? 'selected="selected"' : null ?>><?= l('microsite_feedback_collector.question_type_dropdown') ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label><?= l('microsite_feedback_collector.question_text') ?></label>
                                    <input type="text" class="form-control question-text" name="question_text[]" value="<?= $question->question ?>" placeholder="<?= l('microsite_feedback_collector.question_text_placeholder') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input question-required" id="question_required_<?= $row->microsite_block_id ?>_<?= $key ?>" name="question_required[]" value="1" <?= $question->required ? 'checked="checked"' : null ?>>
                                        <label class="custom-control-label" for="question_required_<?= $row->microsite_block_id ?>_<?= $key ?>"><?= l('microsite_feedback_collector.question_required') ?></label>
                                    </div>
                                </div>
                                
                                <div class="question-options-container">
                                    <?php if(in_array($question->type, ['rating_star', 'rating_number'])): ?>
                                        <div class="form-group">
                                            <label><?= l('microsite_feedback_collector.max_rating') ?></label>
                                            <select class="form-control option-max-rating" name="question_max_rating[]" style="min-width: 80px;">
                                                <option value="5" <?= ($question->options->max_rating ?? 5) == 5 ? 'selected="selected"' : null ?>>5</option>
                                                <option value="10" <?= ($question->options->max_rating ?? 5) == 10 ? 'selected="selected"' : null ?>>10</option>
                                            </select>
                                        </div>
                                    <?php endif ?>
                                    
                                    <?php if(in_array($question->type, ['checkbox', 'radio', 'dropdown'])): ?>
                                        <div class="form-group">
                                            <label><?= l('microsite_feedback_collector.choices') ?></label>
                                            <textarea class="form-control option-choices" name="question_choices[]" rows="4"><?= implode("\n", $question->options->choices ?? []) ?></textarea>
                                            <small class="form-text text-muted"><?= l('microsite_feedback_collector.choices_help') ?></small>
                                        </div>
                                    <?php endif ?>
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-outline-danger mt-3 remove-question">
                                    <i class="fas fa-trash fa-sm mr-1"></i> <?= l('microsite_feedback_collector.remove_question') ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
            <button type="button" id="add_question_<?= $row->microsite_block_id ?>" class="btn btn-sm btn-outline-primary mt-3">
                <i class="fas fa-plus fa-sm mr-1"></i> <?= l('microsite_feedback_collector.add_question') ?>
            </button>
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_button_text_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-square fa-sm text-muted mr-1"></i> <?= l('microsite_link.button_text') ?></label>
            <input id="<?= 'feedback_collector_button_text_' . $row->microsite_block_id ?>" type="text" name="button_text" class="form-control" value="<?= $row->settings->button_text ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_success_text_' . $row->microsite_block_id ?>"><?= l('microsite_feedback_collector.success_text') ?></label>
            <input id="<?= 'feedback_collector_success_text_' . $row->microsite_block_id ?>" type="text" name="success_text" class="form-control" value="<?= $row->settings->success_text ?>" maxlength="256" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_thank_you_url_' . $row->microsite_block_id ?>"><?= l('microsite_feedback_collector.thank_you_url') ?></label>
            <input id="<?= 'feedback_collector_thank_you_url_' . $row->microsite_block_id ?>" type="url" name="thank_you_url" class="form-control" value="<?= $row->settings->thank_you_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
        </div>

        <div class="form-group custom-control custom-switch">
            <input
                    type="checkbox"
                    class="custom-control-input"
                    id="<?= 'feedback_collector_show_agreement_' . $row->microsite_block_id ?>"
                    name="show_agreement"
                <?= $row->settings->show_agreement ? 'checked="checked"' : null ?>
            >
            <label class="custom-control-label" for="<?= 'feedback_collector_show_agreement_' . $row->microsite_block_id ?>"><?= l('microsite_feedback_collector.show_agreement') ?></label>
            <div><small class="form-text text-muted"><?= l('microsite_feedback_collector.show_agreement_help') ?></small></div>
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_agreement_text_' . $row->microsite_block_id ?>"><?= l('microsite_feedback_collector.agreement_text') ?></label>
            <input id="<?= 'feedback_collector_agreement_text_' . $row->microsite_block_id ?>" type="text" name="agreement_text" class="form-control" value="<?= $row->settings->agreement_text ?>" maxlength="256" />
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_agreement_url_' . $row->microsite_block_id ?>"><?= l('microsite_feedback_collector.agreement_url') ?></label>
            <input id="<?= 'feedback_collector_agreement_url_' . $row->microsite_block_id ?>" type="text" name="agreement_url" class="form-control" value="<?= $row->settings->agreement_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'feedback_collector_data_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'feedback_collector_data_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-database fa-sm mr-1"></i> <?= l('microsite_block.data_header') ?>
    </button>

    <div class="collapse" id="<?= 'feedback_collector_data_container_' . $row->microsite_block_id ?>">
        <div class="alert alert-info">
            <i class="fas fa-fw fa-sm fa-info-circle mr-1"></i> <?= l('microsite_block.data_help') ?>
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_email_notification_' . $row->microsite_block_id ?>"><?= l('microsite_block.email_notification') ?></label>
            <input type="text" id="<?= 'feedback_collector_email_notification_' . $row->microsite_block_id ?>" name="email_notification" class="form-control" value="<?= $row->settings->email_notification ?? null ?>" placeholder="<?= l('global.email_placeholder') ?>" maxlength="320" />
            <small class="form-text text-muted"><?= l('microsite_block.email_notification_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_webhook_url_' . $row->microsite_block_id ?>"><?= l('microsite_block.webhook_url') ?></label>
            <input id="<?= 'feedback_collector_webhook_url_' . $row->microsite_block_id ?>" type="text" name="webhook_url" class="form-control" value="<?= $row->settings->webhook_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
            <small class="form-text text-muted"><?= l('microsite_block.webhook_url_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'button_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-square-check fa-sm mr-1"></i> <?= l('microsite_link.button_header') ?>
    </button>

    <div class="collapse" id="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <div class="form-group">
            <label for="<?= 'feedback_collector_name_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
            <input id="<?= 'feedback_collector_name_' . $row->microsite_block_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?>" maxlength="128" required="required" />
        </div>

        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->thumbnail_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?>">
            <label for="<?= 'feedback_collector_image_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_link.image') ?></label>
            <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
                'id'=> 'feedback_collector_image_' . $row->microsite_block_id,
                'uploads_file_key' => 'block_thumbnail_images',
                'file_key' => 'image',
                'already_existing_image' => $row->settings->image,
                'image_container' => 'image',
                'accept' => \Altum\Uploads::array_to_list_format($data->microsite_blocks['feedback_collector']['whitelisted_thumbnail_image_extensions']),
            ]) ?>
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->microsite_blocks['feedback_collector']['whitelisted_thumbnail_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_image_display_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_display') ?></label>
            <select id="<?= 'feedback_collector_image_display_' . $row->microsite_block_id ?>" name="image_display" class="custom-select">
                <option value="icon" <?= $row->settings->image_display ?? 'icon' == 'icon' ? 'selected="selected"' : null ?>><?= l('microsite_link.image_display.icon') ?></option>
                <option value="image" <?= $row->settings->image_display ?? null == 'image' ? 'selected="selected"' : null ?>><?= l('microsite_link.image_display.image') ?></option>
                <option value="background" <?= $row->settings->image_display ?? null == 'background' ? 'selected="selected"' : null ?>><?= l('microsite_link.image_display.background') ?></option>
                <option value="mega_button" <?= $row->settings->image_display ?? null == 'mega_button' ? 'selected="selected"' : null ?>>Mega Button</option>
            </select>
        </div>

        <div id="<?= 'mega_button_options_' . $row->microsite_block_id ?>" style="<?= ($row->settings->image_display ?? 'icon') == 'mega_button' ? null : 'display: none;' ?>">
            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'feedback_collector_mega_button_height_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> Button Height</label>
                <input id="<?= 'feedback_collector_mega_button_height_' . $row->microsite_block_id ?>" type="range" min="100" max="500" step="10" class="form-control-range" name="mega_button_height" value="<?= $row->settings->mega_button_height ?? '200' ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="<?= 'feedback_collector_image_fit_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> Image Fit</label>
                <select id="<?= 'feedback_collector_image_fit_' . $row->microsite_block_id ?>" name="image_fit" class="custom-select">
                    <option value="cover" <?= $row->settings->image_fit ?? 'cover' == 'cover' ? 'selected="selected"' : null ?>>Cover</option>
                    <option value="contain" <?= $row->settings->image_fit ?? null == 'contain' ? 'selected="selected"' : null ?>>Contain</option>
                    <option value="fill" <?= $row->settings->image_fit ?? null == 'fill' ? 'selected="selected"' : null ?>>Fill</option>
                </select>
                <small class="form-text text-muted">How the image should fit within the button area.</small>
            </div>

            <div class="form-group custom-control custom-switch">
                <input
                        type="checkbox"
                        class="custom-control-input"
                        id="<?= 'feedback_collector_show_text_' . $row->microsite_block_id ?>"
                        name="show_text"
                    <?= isset($row->settings->show_text) ? ($row->settings->show_text ? 'checked="checked"' : null) : 'checked="checked"' ?>
                >
                <label class="custom-control-label" for="<?= 'feedback_collector_show_text_' . $row->microsite_block_id ?>">Show Button Text</label>
                <div><small class="form-text text-muted">Whether to show the button text or just display the image.</small></div>
            </div>
        </div>

        <div id="<?= 'icon_options_' . $row->microsite_block_id ?>" style="<?= ($row->settings->image_display ?? 'icon') == 'icon' ? null : 'display: none;' ?>">
            <div class="form-group">
                <label for="<?= 'feedback_collector_icon_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
                <input id="<?= 'feedback_collector_icon_' . $row->microsite_block_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" />
                <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_text_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_color') ?></label>
            <input id="<?= 'feedback_collector_text_color_' . $row->microsite_block_id ?>" type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
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
            <label for="<?= 'feedback_collector_background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
            <input id="<?= 'feedback_collector_background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'feedback_collector_animation_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
            <select id="<?= 'feedback_collector_animation_' . $row->microsite_block_id ?>" name="animation" class="custom-select">
                <option value="false" <?= !$row->settings->animation ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                    <option value="<?= $animation ?>" <?= $row->settings->animation == $animation ? 'selected="selected"' : null ?>><?= $animation ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group" data-animation="<?= implode(',', require APP_PATH . 'includes/microsite_animations.php') ?>">
            <label for="<?= 'feedback_collector_animation_runs_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-play-circle fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
            <select id="<?= 'feedback_collector_animation_runs_' . $row->microsite_block_id ?>" name="animation_runs" class="custom-select">
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

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'display_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'display_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-display fa-sm mr-1"></i> <?= l('microsite_link.display_settings_header') ?>
    </button>

    <div class="collapse" id="<?= 'display_settings_container_' . $row->microsite_block_id ?>">
        <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
            <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                <div class="form-group custom-control custom-switch">
                    <input
                            id="<?= 'link_schedule_' . $row->microsite_block_id ?>"
                            name="schedule" type="checkbox"
                            class="custom-control-input"
                        <?= !empty($row->start_date) && !empty($row->end_date) ? 'checked="checked"' : null ?>
                        <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'disabled="disabled"' ?>
                    >
                    <label class="custom-control-label" for="<?= 'link_schedule_' . $row->microsite_block_id ?>"><?= l('link.settings.schedule') ?></label>
                    <small class="form-text text-muted"><?= l('link.settings.schedule_help') ?></small>
                </div>
            </div>
        </div>

        <div class="mt-3 schedule_container" style="display: none;">
            <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="<?= 'link_start_date_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
                                <input
                                        id="<?= 'link_start_date_' . $row->microsite_block_id ?>"
                                        type="text"
                                        class="form-control"
                                        name="start_date"
                                        value="<?= \Altum\Date::get($row->start_date, 1) ?>"
                                        placeholder="<?= l('link.settings.start_date') ?>"
                                        autocomplete="off"
                                        data-daterangepicker
                                >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="<?= 'link_end_date_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                                <input
                                        id="<?= 'link_end_date_' . $row->microsite_block_id ?>"
                                        type="text"
                                        class="form-control"
                                        name="end_date"
                                        value="<?= \Altum\Date::get($row->end_date, 1) ?>"
                                        placeholder="<?= l('link.settings.end_date') ?>"
                                        autocomplete="off"
                                        data-daterangepicker
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_continents_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-earth-europe fa-sm text-muted mr-1"></i> <?= l('global.continents') ?></label>
            <select id="<?= 'link_display_continents_' . $row->microsite_block_id ?>" name="display_continents[]" class="custom-select" multiple="multiple">
                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                    <option value="<?= $continent_code ?>" <?= in_array($continent_code, $row->settings->display_continents ?? []) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_countries_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.countries') ?></label>
            <select id="<?= 'link_display_countries_' . $row->microsite_block_id ?>" name="display_countries[]" class="custom-select" multiple="multiple">
                <?php foreach(get_countries_array() as $country => $country_name): ?>
                    <option value="<?= $country ?>" <?= in_array($country, $row->settings->display_countries ?? []) ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_cities_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-sm fa-city text-muted mr-1"></i> <?= l('global.cities') ?></label>
            <input type="text" id="<?= 'link_display_cities_' . $row->microsite_block_id ?>" name="display_cities" value="<?= implode(',', $row->settings->display_cities ?? []) ?>" class="form-control" placeholder="<?= l('microsite_link.display_cities_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('microsite_link.display_cities_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_devices_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-laptop fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_devices') ?></label>
            <select id="<?= 'link_display_devices_' . $row->microsite_block_id ?>" name="display_devices[]" class="custom-select" multiple="multiple">
                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                    <option value="<?= $device_type ?>" <?= in_array($device_type, $row->settings->display_devices ?? []) ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_operating_systems_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_operating_systems') ?></label>
            <select id="<?= 'link_display_operating_systems_' . $row->microsite_block_id ?>" name="display_operating_systems[]" class="custom-select" multiple="multiple">
                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                    <option value="<?= $os_name ?>" <?= in_array($os_name, $row->settings->display_operating_systems ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_browsers_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_browsers') ?></label>
            <select id="<?= 'link_display_browsers_' . $row->microsite_block_id ?>" name="display_browsers[]" class="custom-select" multiple="multiple">
                <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                    <option value="<?= $browser_name ?>" <?= in_array($browser_name, $row->settings->display_browsers ?? []) ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_languages_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_languages') ?></label>
            <select id="<?= 'link_display_languages_' . $row->microsite_block_id ?>" name="display_languages[]" class="custom-select" multiple="multiple">
                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                    <option value="<?= $locale ?>" <?= in_array($locale, $row->settings->display_languages ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const microsite_block_id = '<?= $row->microsite_block_id ?>';
    
    // Add question button
    document.getElementById('add_question_' + microsite_block_id).addEventListener('click', function() {
        addNewQuestion(microsite_block_id);
    });
    
    // Initialize existing questions
    document.querySelectorAll('#questions_container_' + microsite_block_id + ' .question-item').forEach(function(questionItem) {
        const questionType = questionItem.querySelector('.question-type');
        if (questionType) {
            questionType.addEventListener('change', function() {
                updateQuestionOptions(questionItem);
            });
        }
        
        const removeButton = questionItem.querySelector('.remove-question');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                questionItem.remove();
            });
        }
    });
    
    // Handle image display type changes
    const imageDisplaySelect = document.getElementById('feedback_collector_image_display_' + microsite_block_id);
    const iconOptionsContainer = document.getElementById('icon_options_' + microsite_block_id);
    const megaButtonOptionsContainer = document.getElementById('mega_button_options_' + microsite_block_id);
    
    function updateImageDisplayOptions() {
        const selectedValue = imageDisplaySelect.value;
        
        // Show/hide icon options based on selection
        if (selectedValue === 'icon') {
            iconOptionsContainer.style.display = 'block';
            megaButtonOptionsContainer.style.display = 'none';
        } else {
            iconOptionsContainer.style.display = 'none';
            
            // Show mega button options only for mega_button
            if (selectedValue === 'mega_button') {
                megaButtonOptionsContainer.style.display = 'block';
            } else {
                megaButtonOptionsContainer.style.display = 'none';
            }
        }
    }
    
    // Set initial state
    updateImageDisplayOptions();
    
    // Add event listener for changes
    imageDisplaySelect.addEventListener('change', updateImageDisplayOptions);
});

function addNewQuestion(microsite_block_id) {
    const questionsContainer = document.getElementById('questions_container_' + microsite_block_id);
    const questionCount = questionsContainer.querySelectorAll('.question-item').length;
    
    // Create new question item
    const questionItem = document.createElement('div');
    questionItem.className = 'card mb-3 question-item';
    questionItem.innerHTML = `
        <div class="card-body">
            <div class="form-group">
                <label><?= l('microsite_feedback_collector.question_type') ?></label>
                <select class="form-control question-type" name="question_type[]">
                    <option value="text"><?= l('microsite_feedback_collector.question_type_text') ?></option>
                    <option value="textarea"><?= l('microsite_feedback_collector.question_type_textarea') ?></option>
                    <option value="rating_star"><?= l('microsite_feedback_collector.question_type_rating_star') ?></option>
                    <option value="rating_number"><?= l('microsite_feedback_collector.question_type_rating_number') ?></option>
                    <option value="rating_emoji"><?= l('microsite_feedback_collector.question_type_rating_emoji') ?></option>
                    <option value="checkbox"><?= l('microsite_feedback_collector.question_type_checkbox') ?></option>
                    <option value="radio"><?= l('microsite_feedback_collector.question_type_radio') ?></option>
                    <option value="dropdown"><?= l('microsite_feedback_collector.question_type_dropdown') ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?= l('microsite_feedback_collector.question_text') ?></label>
                <input type="text" class="form-control question-text" name="question_text[]" placeholder="<?= l('microsite_feedback_collector.question_text_placeholder') ?>">
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input question-required" id="question_required_${microsite_block_id}_${questionCount}" name="question_required[]" value="1">
                    <label class="custom-control-label" for="question_required_${microsite_block_id}_${questionCount}"><?= l('microsite_feedback_collector.question_required') ?></label>
                </div>
            </div>
            
            <div class="question-options-container">
                <!-- Will be populated based on question type -->
            </div>
            
            <button type="button" class="btn btn-sm btn-outline-danger mt-3 remove-question">
                <i class="fas fa-trash fa-sm mr-1"></i> <?= l('microsite_feedback_collector.remove_question') ?>
            </button>
        </div>
    `;
    
    questionsContainer.appendChild(questionItem);
    
    // Add event listeners
    const questionType = questionItem.querySelector('.question-type');
    questionType.addEventListener('change', function() {
        updateQuestionOptions(questionItem);
    });
    
    const removeButton = questionItem.querySelector('.remove-question');
    removeButton.addEventListener('click', function() {
        questionItem.remove();
    });
    
    // Initialize options based on default type
    updateQuestionOptions(questionItem);
}

function updateQuestionOptions(questionItem) {
    const type = questionItem.querySelector('.question-type').value;
    const optionsContainer = questionItem.querySelector('.question-options-container');
    
    optionsContainer.innerHTML = '';
    
    switch(type) {
        case 'rating_star':
        case 'rating_number':
            optionsContainer.innerHTML = `
                <div class="form-group">
                    <label><?= l('microsite_feedback_collector.max_rating') ?></label>
                    <select class="form-control option-max-rating" name="question_max_rating[]" style="min-width: 80px;">
                        <option value="5">5</option>
                        <option value="10">10</option>
                    </select>
                </div>
            `;
            break;
            
        case 'checkbox':
        case 'radio':
        case 'dropdown':
            optionsContainer.innerHTML = `
                <div class="form-group">
                    <label><?= l('microsite_feedback_collector.choices') ?></label>
                    <textarea class="form-control option-choices" name="question_choices[]" rows="4"></textarea>
                    <small class="form-text text-muted"><?= l('microsite_feedback_collector.choices_help') ?></small>
                </div>
            `;
            break;
    }
}
</script>
