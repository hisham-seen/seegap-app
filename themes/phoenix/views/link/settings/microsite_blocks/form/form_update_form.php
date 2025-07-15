<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />
    <input type="hidden" name="block_type" value="form" />

    <div class="notification-container"></div>

    <div class="nav nav-pills nav-fill nav-tabbed mb-4" id="pills-tab" role="tablist">
        <a class="nav-item nav-link active" id="pills-content-tab" data-toggle="pill" href="#pills-content" role="tab" aria-controls="pills-content" aria-selected="true">
            <i class="fas fa-fw fa-pen fa-sm mr-1"></i>
            <?= l('microsite_form.tab.content') ?>
        </a>

        <a class="nav-item nav-link" id="pills-style-tab" data-toggle="pill" href="#pills-style" role="tab" aria-controls="pills-style" aria-selected="false">
            <i class="fas fa-fw fa-paint-brush fa-sm mr-1"></i>
            <?= l('microsite_form.tab.style') ?>
        </a>

        <a class="nav-item nav-link" id="pills-integrations-tab" data-toggle="pill" href="#pills-integrations" role="tab" aria-controls="pills-integrations" aria-selected="false">
            <i class="fas fa-fw fa-plug fa-sm mr-1"></i>
            <?= l('microsite_form.tab.integrations') ?>
        </a>

        <a class="nav-item nav-link" id="pills-metadata-tab" data-toggle="pill" href="#pills-metadata" role="tab" aria-controls="pills-metadata" aria-selected="false">
            <i class="fas fa-fw fa-database fa-sm mr-1"></i>
            <?= l('microsite_form.tab.metadata') ?>
        </a>

        <a class="nav-item nav-link" id="pills-display-tab" data-toggle="pill" href="#pills-display" role="tab" aria-controls="pills-display" aria-selected="false">
            <i class="fas fa-fw fa-display fa-sm mr-1"></i>
            <?= l('microsite_form.tab.display') ?>
        </a>
    </div>

    <div class="tab-content" id="pills-tabContent">
        
        <!-- Content Tab -->
        <div class="tab-pane fade show active" id="pills-content" role="tabpanel" aria-labelledby="pills-content-tab">
            
            <div class="form-group">
                <label for="form_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.name') ?></label>
                <input type="text" id="form_name" name="name" class="form-control" value="<?= $row->settings->name ?>" maxlength="128" required="required" />
            </div>

            <div class="form-group">
                <label for="form_type"><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.form_type') ?></label>
                <select id="form_type" name="form_type" class="form-control" required="required">
                    <option value="email" <?= $row->settings->form_type == 'email' ? 'selected="selected"' : null ?>><?= l('microsite_form.form_type.email') ?></option>
                    <option value="phone" <?= $row->settings->form_type == 'phone' ? 'selected="selected"' : null ?>><?= l('microsite_form.form_type.phone') ?></option>
                    <option value="contact" <?= $row->settings->form_type == 'contact' ? 'selected="selected"' : null ?>><?= l('microsite_form.form_type.contact') ?></option>
                    <option value="custom" <?= $row->settings->form_type == 'custom' ? 'selected="selected"' : null ?>><?= l('microsite_form.form_type.custom') ?></option>
                </select>
            </div>

            <!-- Email Form Fields -->
            <div id="email_form_fields" class="form-type-fields" style="display: <?= $row->settings->form_type == 'email' ? 'block' : 'none' ?>">
                <div class="form-group">
                    <label for="email_placeholder"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.email_placeholder') ?></label>
                    <input type="text" id="email_placeholder" name="email_placeholder" class="form-control" value="<?= $row->settings->email_placeholder ?? '' ?>" maxlength="64" />
                </div>

                <div class="form-group">
                    <label for="name_placeholder"><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.name_placeholder') ?></label>
                    <input type="text" id="name_placeholder" name="name_placeholder" class="form-control" value="<?= $row->settings->name_placeholder ?? '' ?>" maxlength="64" />
                </div>

                <div class="form-group">
                    <label for="mailchimp_api"><i class="fab fa-fw fa-mailchimp fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.mailchimp_api') ?></label>
                    <input type="text" id="mailchimp_api" name="mailchimp_api" class="form-control" value="<?= $row->settings->mailchimp_api ?? '' ?>" maxlength="64" />
                    <small class="form-text text-muted"><?= l('microsite_form.input.mailchimp_api_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="mailchimp_api_list"><i class="fab fa-fw fa-mailchimp fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.mailchimp_api_list') ?></label>
                    <input type="text" id="mailchimp_api_list" name="mailchimp_api_list" class="form-control" value="<?= $row->settings->mailchimp_api_list ?? '' ?>" maxlength="64" />
                </div>
            </div>

            <!-- Phone Form Fields -->
            <div id="phone_form_fields" class="form-type-fields" style="display: <?= $row->settings->form_type == 'phone' ? 'block' : 'none' ?>">
                <div class="form-group">
                    <label for="phone_placeholder"><i class="fas fa-fw fa-phone fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.phone_placeholder') ?></label>
                    <input type="text" id="phone_placeholder" name="phone_placeholder" class="form-control" value="<?= $row->settings->phone_placeholder ?? '' ?>" maxlength="64" />
                </div>

                <div class="form-group">
                    <label for="name_placeholder_phone"><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.name_placeholder') ?></label>
                    <input type="text" id="name_placeholder_phone" name="name_placeholder" class="form-control" value="<?= $row->settings->name_placeholder ?? '' ?>" maxlength="64" />
                </div>
            </div>

            <!-- Contact Form Fields -->
            <div id="contact_form_fields" class="form-type-fields" style="display: <?= $row->settings->form_type == 'contact' ? 'block' : 'none' ?>">
                <div class="form-group">
                    <label for="email_placeholder_contact"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.email_placeholder') ?></label>
                    <input type="text" id="email_placeholder_contact" name="email_placeholder" class="form-control" value="<?= $row->settings->email_placeholder ?? '' ?>" maxlength="64" />
                </div>

                <div class="form-group">
                    <label for="name_placeholder_contact"><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.name_placeholder') ?></label>
                    <input type="text" id="name_placeholder_contact" name="name_placeholder" class="form-control" value="<?= $row->settings->name_placeholder ?? '' ?>" maxlength="64" />
                </div>

                <div class="form-group">
                    <label for="message_placeholder"><i class="fas fa-fw fa-comment fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.message_placeholder') ?></label>
                    <input type="text" id="message_placeholder" name="message_placeholder" class="form-control" value="<?= $row->settings->message_placeholder ?? '' ?>" maxlength="128" />
                </div>
            </div>

            <!-- Custom Form Fields -->
            <div id="custom_form_fields" class="form-type-fields" style="display: <?= $row->settings->form_type == 'custom' ? 'block' : 'none' ?>">
                <div class="form-group">
                    <label for="form_heading"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.form_heading') ?></label>
                    <input type="text" id="form_heading" name="form_heading" class="form-control" value="<?= $row->settings->form_heading ?? '' ?>" maxlength="128" />
                </div>

                <div class="form-group">
                    <label for="form_text"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.form_text') ?></label>
                    <textarea id="form_text" name="form_text" class="form-control" maxlength="2048"><?= $row->settings->form_text ?? '' ?></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-fw fa-question fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.questions') ?></label>
                    <div id="questions_container">
                        <?php if(isset($row->settings->questions) && is_array($row->settings->questions)): ?>
                            <?php foreach($row->settings->questions as $key => $question): ?>
                                <div class="question-item border rounded p-3 mb-3" data-question-index="<?= $key ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= l('microsite_form.input.question_type') ?></label>
                                                <select name="question_type[]" class="form-control question-type-select">
                                                    <option value="text" <?= $question->type == 'text' ? 'selected' : '' ?>><?= l('microsite_form.question_type.text') ?></option>
                                                    <option value="textarea" <?= $question->type == 'textarea' ? 'selected' : '' ?>><?= l('microsite_form.question_type.textarea') ?></option>
                                                    <option value="email" <?= $question->type == 'email' ? 'selected' : '' ?>><?= l('microsite_form.question_type.email') ?></option>
                                                    <option value="phone" <?= $question->type == 'phone' ? 'selected' : '' ?>><?= l('microsite_form.question_type.phone') ?></option>
                                                    <option value="rating_star" <?= $question->type == 'rating_star' ? 'selected' : '' ?>><?= l('microsite_form.question_type.rating_star') ?></option>
                                                    <option value="rating_number" <?= $question->type == 'rating_number' ? 'selected' : '' ?>><?= l('microsite_form.question_type.rating_number') ?></option>
                                                    <option value="rating_emoji" <?= $question->type == 'rating_emoji' ? 'selected' : '' ?>><?= l('microsite_form.question_type.rating_emoji') ?></option>
                                                    <option value="checkbox" <?= $question->type == 'checkbox' ? 'selected' : '' ?>><?= l('microsite_form.question_type.checkbox') ?></option>
                                                    <option value="radio" <?= $question->type == 'radio' ? 'selected' : '' ?>><?= l('microsite_form.question_type.radio') ?></option>
                                                    <option value="dropdown" <?= $question->type == 'dropdown' ? 'selected' : '' ?>><?= l('microsite_form.question_type.dropdown') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= l('microsite_form.input.question_text') ?></label>
                                                <input type="text" name="question_text[]" class="form-control" value="<?= $question->question ?>" maxlength="256" required />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" name="question_required[<?= $key ?>]" class="form-check-input" <?= $question->required ? 'checked' : '' ?> />
                                        <label class="form-check-label"><?= l('microsite_form.input.question_required') ?></label>
                                    </div>

                                    <?php if(in_array($question->type, ['checkbox', 'radio', 'dropdown'])): ?>
                                        <div class="form-group mt-2">
                                            <label><?= l('microsite_form.input.question_choices') ?></label>
                                            <textarea name="question_choices[]" class="form-control" placeholder="<?= l('microsite_form.input.question_choices_help') ?>"><?= isset($question->options->choices) ? implode("\n", $question->options->choices) : '' ?></textarea>
                                        </div>
                                    <?php endif ?>

                                    <?php if(in_array($question->type, ['rating_star', 'rating_number'])): ?>
                                        <div class="form-group mt-2">
                                            <label><?= l('microsite_form.input.question_max_rating') ?></label>
                                            <select name="question_max_rating[]" class="form-control">
                                                <?php for($i = 3; $i <= 10; $i++): ?>
                                                    <option value="<?= $i ?>" <?= (isset($question->options->max_rating) && $question->options->max_rating == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                                <?php endfor ?>
                                            </select>
                                        </div>
                                    <?php endif ?>

                                    <button type="button" class="btn btn-sm btn-outline-danger remove-question"><?= l('global.delete') ?></button>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                    <button type="button" id="add_question" class="btn btn-sm btn-outline-primary"><?= l('microsite_form.input.add_question') ?></button>
                </div>
            </div>

            <!-- Common Form Fields -->
            <div class="form-group">
                <label for="button_text"><i class="fas fa-fw fa-mouse-pointer fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.button_text') ?></label>
                <input type="text" id="button_text" name="button_text" class="form-control" value="<?= $row->settings->button_text ?>" maxlength="64" required="required" />
            </div>

            <div class="form-group">
                <label for="success_text"><i class="fas fa-fw fa-check fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.success_text') ?></label>
                <input type="text" id="success_text" name="success_text" class="form-control" value="<?= $row->settings->success_text ?>" maxlength="256" />
            </div>

            <div class="form-group">
                <label for="thank_you_url"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.thank_you_url') ?></label>
                <input type="url" id="thank_you_url" name="thank_you_url" class="form-control" value="<?= $row->settings->thank_you_url ?>" maxlength="2048" />
                <small class="form-text text-muted"><?= l('microsite_form.input.thank_you_url_help') ?></small>
            </div>

            <div class="custom-control custom-switch my-3">
                <input id="show_agreement" name="show_agreement" type="checkbox" class="custom-control-input" <?= $row->settings->show_agreement ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="show_agreement"><?= l('microsite_form.input.show_agreement') ?></label>
                <small class="form-text text-muted"><?= l('microsite_form.input.show_agreement_help') ?></small>
            </div>

            <div id="agreement_container" style="display: <?= $row->settings->show_agreement ? 'block' : 'none' ?>">
                <div class="form-group">
                    <label for="agreement_text"><i class="fas fa-fw fa-file-contract fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.agreement_text') ?></label>
                    <input type="text" id="agreement_text" name="agreement_text" class="form-control" value="<?= $row->settings->agreement_text ?>" maxlength="256" />
                </div>

                <div class="form-group">
                    <label for="agreement_url"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.agreement_url') ?></label>
                    <input type="url" id="agreement_url" name="agreement_url" class="form-control" value="<?= $row->settings->agreement_url ?>" maxlength="2048" />
                </div>
            </div>

        </div>

        <!-- Style Tab -->
        <div class="tab-pane fade" id="pills-style" role="tabpanel" aria-labelledby="pills-style-tab">
            
            <div class="form-group">
                <label for="image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.image') ?></label>
                <input id="image" type="file" name="image" accept="<?= \SeeGap\Uploads::get_whitelisted_file_extensions_accept('jpg,jpeg,png,svg,gif,webp,avif') ?>" class="form-control-file altum-file-input" />
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('jpg,jpeg,png,svg,gif,webp,avif')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
                <?php if(!empty($row->settings->image)): ?>
                    <div class="row">
                        <div class="m-1 col-6 col-xl-3">
                            <div class="custom-control custom-checkbox">
                                <input id="image_remove" name="image_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#image').required = true : document.querySelector('#image').required = false">
                                <label class="custom-control-label" for="image_remove">
                                    <span class="text-muted"><?= l('global.delete_file') ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="icon"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.icon') ?></label>
                <input type="text" id="icon" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= l('microsite_form.input.icon_placeholder') ?>" />
                <small class="form-text text-muted"><?= l('microsite_form.input.icon_help') ?></small>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="text_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.text_color') ?></label>
                        <input type="color" id="text_color" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="background_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.background_color') ?></label>
                        <input type="color" id="background_color" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="text_alignment"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.text_alignment') ?></label>
                <select id="text_alignment" name="text_alignment" class="form-control">
                    <option value="center" <?= $row->settings->text_alignment == 'center' ? 'selected="selected"' : null ?>><?= l('global.center') ?></option>
                    <option value="left" <?= $row->settings->text_alignment == 'left' ? 'selected="selected"' : null ?>><?= l('global.left') ?></option>
                    <option value="right" <?= $row->settings->text_alignment == 'right' ? 'selected="selected"' : null ?>><?= l('global.right') ?></option>
                    <option value="justify" <?= $row->settings->text_alignment == 'justify' ? 'selected="selected"' : null ?>><?= l('global.justify') ?></option>
                </select>
            </div>

            <!-- Border & Shadow Settings -->
            <div class="form-group">
                <label for="border_radius"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.border_radius') ?></label>
                <select id="border_radius" name="border_radius" class="form-control">
                    <option value="straight" <?= $row->settings->border_radius == 'straight' ? 'selected="selected"' : null ?>><?= l('global.straight') ?></option>
                    <option value="round" <?= $row->settings->border_radius == 'round' ? 'selected="selected"' : null ?>><?= l('global.round') ?></option>
                    <option value="rounded" <?= $row->settings->border_radius == 'rounded' ? 'selected="selected"' : null ?>><?= l('global.rounded') ?></option>
                </select>
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="border_width"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.border_width') ?></label>
                        <select id="border_width" name="border_width" class="form-control">
                            <?php foreach([0, 1, 2, 3, 4, 5] as $border_width): ?>
                                <option value="<?= $border_width ?>" <?= $row->settings->border_width == $border_width ? 'selected="selected"' : null ?>><?= $border_width ?>px</option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="border_style"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.border_style') ?></label>
                        <select id="border_style" name="border_style" class="form-control">
                            <?php foreach(['solid', 'dashed', 'double', 'inset', 'outset'] as $border_style): ?>
                                <option value="<?= $border_style ?>" <?= $row->settings->border_style == $border_style ? 'selected="selected"' : null ?>><?= l('global.' . $border_style) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="border_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.border_color') ?></label>
                        <input type="color" id="border_color" name="border_color" class="form-control" value="<?= $row->settings->border_color ?>" required="required" />
                    </div>
                </div>
            </div>

            <!-- Animation Settings -->
            <div class="form-group">
                <label for="animation"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.animation') ?></label>
                <select id="animation" name="animation" class="form-control">
                    <option value="false" <?= !$row->settings->animation ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                    <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                        <option value="<?= $animation ?>" <?= $row->settings->animation == $animation ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="animation_runs"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.animation_runs') ?></label>
                <select id="animation_runs" name="animation_runs" class="form-control">
                    <option value="repeat-1" <?= $row->settings->animation_runs == 'repeat-1' ? 'selected="selected"' : null ?>>1</option>
                    <option value="repeat-2" <?= $row->settings->animation_runs == 'repeat-2' ? 'selected="selected"' : null ?>>2</option>
                    <option value="repeat-3" <?= $row->settings->animation_runs == 'repeat-3' ? 'selected="selected"' : null ?>>3</option>
                    <option value="infinite" <?= $row->settings->animation_runs == 'infinite' ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
                </select>
            </div>

        </div>

        <!-- Integrations Tab -->
        <div class="tab-pane fade" id="pills-integrations" role="tabpanel" aria-labelledby="pills-integrations-tab">
            
            <div class="form-group">
                <label for="email_notification"><i class="fas fa-fw fa-bell fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.email_notification') ?></label>
                <input type="email" id="email_notification" name="email_notification" class="form-control" value="<?= $row->settings->email_notification ?>" maxlength="320" />
                <small class="form-text text-muted"><?= l('microsite_form.input.email_notification_help') ?></small>
            </div>

            <div class="form-group">
                <label for="webhook_url"><i class="fas fa-fw fa-satellite-dish fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.webhook_url') ?></label>
                <input type="url" id="webhook_url" name="webhook_url" class="form-control" value="<?= $row->settings->webhook_url ?>" maxlength="2048" />
                <small class="form-text text-muted"><?= l('microsite_form.input.webhook_url_help') ?></small>
            </div>

        </div>

        <!-- Metadata Tab -->
        <div class="tab-pane fade" id="pills-metadata" role="tabpanel" aria-labelledby="pills-metadata-tab">
            
            <div class="alert alert-info">
                <i class="fas fa-fw fa-info-circle"></i>
                <?= l('microsite_form.metadata.description') ?>
            </div>

            <!-- Essential Data (Always Enabled) -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-fw fa-check-circle mr-2"></i>
                        <?= l('microsite_form.metadata.essential_data') ?>
                        <small class="ml-2"><?= l('microsite_form.metadata.gdpr_safe') ?></small>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked disabled>
                                <label class="custom-control-label text-muted">
                                    <strong><?= l('microsite_form.metadata.submission_timestamp') ?></strong><br>
                                    <small><?= l('microsite_form.metadata.submission_timestamp_desc') ?></small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked disabled>
                                <label class="custom-control-label text-muted">
                                    <strong><?= l('microsite_form.metadata.form_id') ?></strong><br>
                                    <small><?= l('microsite_form.metadata.form_id_desc') ?></small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked disabled>
                                <label class="custom-control-label text-muted">
                                    <strong><?= l('microsite_form.metadata.session_id') ?></strong><br>
                                    <small><?= l('microsite_form.metadata.session_id_desc') ?></small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked disabled>
                                <label class="custom-control-label text-muted">
                                    <strong><?= l('microsite_form.metadata.validation_errors') ?></strong><br>
                                    <small><?= l('microsite_form.metadata.validation_errors_desc') ?></small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Data (Legitimate Interest) -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-fw fa-exclamation-triangle mr-2"></i>
                        <?= l('microsite_form.metadata.analytics_data') ?>
                        <small class="ml-2"><?= l('microsite_form.metadata.legitimate_interest') ?></small>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        $analytics_fields = [
                            'country_alpha3' => l('microsite_form.metadata.country_alpha3_desc'),
                            'region_code' => l('microsite_form.metadata.region_code_desc'),
                            'city_alpha3' => l('microsite_form.metadata.city_alpha3_desc'),
                            'timezone' => l('microsite_form.metadata.timezone_desc'),
                            'browser_name' => l('microsite_form.metadata.browser_name_desc'),
                            'browser_version' => l('microsite_form.metadata.browser_version_desc'),
                            'os_name' => l('microsite_form.metadata.os_name_desc'),
                            'device_type' => l('microsite_form.metadata.device_type_desc'),
                            'screen_resolution' => l('microsite_form.metadata.screen_resolution_desc'),
                            'language' => l('microsite_form.metadata.language_desc'),
                            'referrer_domain' => l('microsite_form.metadata.referrer_domain_desc'),
                            'time_on_page' => l('microsite_form.metadata.time_on_page_desc'),
                            'pages_visited' => l('microsite_form.metadata.pages_visited_desc'),
                        ];
                        foreach($analytics_fields as $field => $description): ?>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="metadata_capture[]" value="<?= $field ?>" id="metadata_<?= $field ?>" class="custom-control-input" <?= isset($row->settings->metadata_capture->$field) && $row->settings->metadata_capture->$field ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="metadata_<?= $field ?>">
                                        <strong><?= l('microsite_form.metadata.' . $field) ?></strong><br>
                                        <small class="text-muted"><?= $description ?></small>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <!-- Restricted Data (Requires Consent) -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-fw fa-shield-alt mr-2"></i>
                        <?= l('microsite_form.metadata.restricted_data') ?>
                        <small class="ml-2"><?= l('microsite_form.metadata.requires_consent') ?></small>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        $restricted_fields = [
                            'ip_address' => l('microsite_form.metadata.ip_address_desc'),
                            'latitude' => l('microsite_form.metadata.latitude_desc'),
                            'longitude' => l('microsite_form.metadata.longitude_desc'),
                            'postal_code' => l('microsite_form.metadata.postal_code_desc'),
                            'user_agent' => l('microsite_form.metadata.user_agent_desc'),
                            'device_brand' => l('microsite_form.metadata.device_brand_desc'),
                            'device_model' => l('microsite_form.metadata.device_model_desc'),
                            'referrer_url' => l('microsite_form.metadata.referrer_url_desc'),
                            'landing_page_url' => l('microsite_form.metadata.landing_page_url_desc'),
                            'current_page_url' => l('microsite_form.metadata.current_page_url_desc'),
                            'utm_source' => l('microsite_form.metadata.utm_source_desc'),
                            'utm_medium' => l('microsite_form.metadata.utm_medium_desc'),
                            'utm_campaign' => l('microsite_form.metadata.utm_campaign_desc'),
                            'utm_term' => l('microsite_form.metadata.utm_term_desc'),
                            'utm_content' => l('microsite_form.metadata.utm_content_desc'),
                            'gclid' => l('microsite_form.metadata.gclid_desc'),
                            'fbclid' => l('microsite_form.metadata.fbclid_desc'),
                            'affiliate_id' => l('microsite_form.metadata.affiliate_id_desc'),
                            'is_return_visitor' => l('microsite_form.metadata.is_return_visitor_desc'),
                            'previous_submissions' => l('microsite_form.metadata.previous_submissions_desc'),
                            'field_interactions' => l('microsite_form.metadata.field_interactions_desc'),
                            'copy_paste_events' => l('microsite_form.metadata.copy_paste_events_desc'),
                            'tab_switches' => l('microsite_form.metadata.tab_switches_desc'),
                        ];
                        foreach($restricted_fields as $field => $description): ?>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="metadata_capture[]" value="<?= $field ?>" id="metadata_<?= $field ?>" class="custom-control-input" <?= isset($row->settings->metadata_capture->$field) && $row->settings->metadata_capture->$field ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="metadata_<?= $field ?>">
                                        <strong><?= l('microsite_form.metadata.' . $field) ?></strong><br>
                                        <small class="text-muted"><?= $description ?></small>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <!-- High-Risk Data (Not Recommended) -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-fw fa-ban mr-2"></i>
                        <?= l('microsite_form.metadata.high_risk_data') ?>
                        <small class="ml-2"><?= l('microsite_form.metadata.not_recommended') ?></small>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-fw fa-exclamation-triangle"></i>
                        <?= l('microsite_form.metadata.high_risk_warning') ?>
                    </div>
                    <div class="row">
                        <?php 
                        $high_risk_fields = [
                            'battery_level' => l('microsite_form.metadata.battery_level_desc'),
                            'network_speed' => l('microsite_form.metadata.network_speed_desc'),
                            'webgl_enabled' => l('microsite_form.metadata.webgl_enabled_desc'),
                            'color_depth' => l('microsite_form.metadata.color_depth_desc'),
                            'pixel_ratio' => l('microsite_form.metadata.pixel_ratio_desc'),
                        ];
                        foreach($high_risk_fields as $field => $description): ?>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="metadata_capture[]" value="<?= $field ?>" id="metadata_<?= $field ?>" class="custom-control-input" <?= isset($row->settings->metadata_capture->$field) && $row->settings->metadata_capture->$field ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="metadata_<?= $field ?>">
                                        <strong><?= l('microsite_form.metadata.' . $field) ?></strong><br>
                                        <small class="text-muted"><?= $description ?></small>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <!-- Data Retention Settings -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-fw fa-clock mr-2"></i>
                        <?= l('microsite_form.metadata.data_retention') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_retention_days"><?= l('microsite_form.metadata.data_retention_days') ?></label>
                                <select id="data_retention_days" name="data_retention_days" class="form-control">
                                    <option value="30" <?= $row->settings->data_retention_days == 30 ? 'selected' : '' ?>>30 <?= l('global.days') ?></option>
                                    <option value="90" <?= $row->settings->data_retention_days == 90 ? 'selected' : '' ?>>90 <?= l('global.days') ?></option>
                                    <option value="180" <?= $row->settings->data_retention_days == 180 ? 'selected' : '' ?>>180 <?= l('global.days') ?></option>
                                    <option value="365" <?= $row->settings->data_retention_days == 365 ? 'selected' : '' ?>>1 <?= l('global.year') ?></option>
                                    <option value="730" <?= $row->settings->data_retention_days == 730 ? 'selected' : '' ?>>2 <?= l('global.years') ?></option>
                                    <option value="1095" <?= $row->settings->data_retention_days == 1095 ? 'selected' : '' ?>>3 <?= l('global.years') ?></option>
                                </select>
                                <small class="form-text text-muted"><?= l('microsite_form.metadata.data_retention_days_help') ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="anonymize_after_days"><?= l('microsite_form.metadata.anonymize_after_days') ?></label>
                                <select id="anonymize_after_days" name="anonymize_after_days" class="form-control">
                                    <option value="7" <?= $row->settings->anonymize_after_days == 7 ? 'selected' : '' ?>>7 <?= l('global.days') ?></option>
                                    <option value="30" <?= $row->settings->anonymize_after_days == 30 ? 'selected' : '' ?>>30 <?= l('global.days') ?></option>
                                    <option value="90" <?= $row->settings->anonymize_after_days == 90 ? 'selected' : '' ?>>90 <?= l('global.days') ?></option>
                                    <option value="180" <?= $row->settings->anonymize_after_days == 180 ? 'selected' : '' ?>>180 <?= l('global.days') ?></option>
                                </select>
                                <small class="form-text text-muted"><?= l('microsite_form.metadata.anonymize_after_days_help') ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="custom-control custom-switch">
                        <input id="gdpr_consent_required" name="gdpr_consent_required" type="checkbox" class="custom-control-input" <?= $row->settings->gdpr_consent_required ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="gdpr_consent_required"><?= l('microsite_form.metadata.gdpr_consent_required') ?></label>
                        <small class="form-text text-muted"><?= l('microsite_form.metadata.gdpr_consent_required_help') ?></small>
                    </div>
                </div>
            </div>

        </div>

        <!-- Display Tab -->
        <div class="tab-pane fade" id="pills-display" role="tabpanel" aria-labelledby="pills-display-tab">
            <?= include_view(THEME_PATH . 'views/partials/microsite_block_display_settings.php', ['row' => $row]) ?>
        </div>

    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form type switching
    const formTypeSelect = document.getElementById('form_type');
    const formTypeFields = document.querySelectorAll('.form-type-fields');
    
    function toggleFormFields() {
        const selectedType = formTypeSelect.value;
        formTypeFields.forEach(field => {
            field.style.display = 'none';
        });
        
        const targetField = document.getElementById(selectedType + '_form_fields');
        if (targetField) {
            targetField.style.display = 'block';
        }
    }
    
    formTypeSelect.addEventListener('change', toggleFormFields);
    
    // Agreement toggle
    const showAgreementCheckbox = document.getElementById('show_agreement');
    const agreementContainer = document.getElementById('agreement_container');
    
    showAgreementCheckbox.addEventListener('change', function() {
        agreementContainer.style.display = this.checked ? 'block' : 'none';
    });
    
    // Custom form question management
    let questionIndex = <?= isset($row->settings->questions) ? count($row->settings->questions) : 0 ?>;
    
    document.getElementById('add_question')?.addEventListener('click', function() {
        const container = document.getElementById('questions_container');
        const questionHtml = `
            <div class="question-item border rounded p-3 mb-3" data-question-index="${questionIndex}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= l('microsite_form.input.question_type') ?></label>
                            <select name="question_type[]" class="form-control question-type-select">
                                <option value="text"><?= l('microsite_form.question_type.text') ?></option>
                                <option value="textarea"><?= l('microsite_form.question_type.textarea') ?></option>
                                <option value="email"><?= l('microsite_form.question_type.email') ?></option>
                                <option value="phone"><?= l('microsite_form.question_type.phone') ?></option>
                                <option value="rating_star"><?= l('microsite_form.question_type.rating_star') ?></option>
                                <option value="rating_number"><?= l('microsite_form.question_type.rating_number') ?></option>
                                <option value="rating_emoji"><?= l('microsite_form.question_type.rating_emoji') ?></option>
                                <option value="checkbox"><?= l('microsite_form.question_type.checkbox') ?></option>
                                <option value="radio"><?= l('microsite_form.question_type.radio') ?></option>
                                <option value="dropdown"><?= l('microsite_form.question_type.dropdown') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= l('microsite_form.input.question_text') ?></label>
                            <input type="text" name="question_text[]" class="form-control" maxlength="256" required />
                        </div>
                    </div>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" name="question_required[${questionIndex}]" class="form-check-input" />
                    <label class="form-check-label"><?= l('microsite_form.input.question_required') ?></label>
                </div>
                
                <button type="button" class="btn btn-sm btn-outline-danger remove-question"><?= l('global.delete') ?></button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', questionHtml);
        questionIndex++;
    });
    
    // Remove question functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) {
            e.target.closest('.question-item').remove();
        }
    });
});
</script>
