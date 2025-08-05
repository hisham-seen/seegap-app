<?php defined('SEEGAP') || die() ?>

<div class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <div class="d-print-none">
        <?php if(settings()->main->breadcrumbs_is_enabled): ?>
            <nav aria-label="breadcrumb">
                <ol class="custom-breadcrumbs small">
                    <li>
                        <a href="<?= url('qr-codes') ?>"><?= l('qr_codes.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page">
                        <?= l('qr_code_create.breadcrumb') ?>
                    </li>
                </ol>
            </nav>
        <?php endif ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 text-truncate mb-0 mr-2">
                <i class="fas fa-fw fa-xs fa-qrcode mr-1"></i> 
                <?= l('qr_code_create.header') ?>
            </h1>
        </div>
    </div>

    <form id="form" action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
        <input type="hidden" name="api_key" value="<?= $this->user->api_key ?>" />
        <input type="hidden" name="qr_code" value="<?= $data->values['qr_code'] ?? null ?>" />
        <input type="hidden" name="embedded_data" value="<?= $data->values['embedded_data'] ?? null ?>" />
        <input type="hidden" name="reload" value="" data-reload-qr-code />
        <input type="hidden" name="is_readable" value="" />

        <div class="row">
            <div class="col-12 col-lg-6 d-print-none mb-5 mb-lg-0">
                <div class="card">
                    <div class="card-body">
                        <div class="notification-container"></div>

                        <?php
                        // Define tabs for the QR code creator
                        $tabs = [
                            [
                                'id' => 'general',
                                'title' => l('global.general'),
                                'icon' => 'fas fa-cog'
                            ],
                            [
                                'id' => 'design',
                                'title' => l('qr_codes.input.design'),
                                'icon' => 'fas fa-palette'
                            ],
                            [
                                'id' => 'frame',
                                'title' => l('qr_codes.input.frame'),
                                'icon' => 'fas fa-crop-alt'
                            ],
                            [
                                'id' => 'branding',
                                'title' => l('qr_codes.input.branding'),
                                'icon' => 'fas fa-copyright'
                            ],
                            [
                                'id' => 'advanced',
                                'title' => l('qr_codes.input.advanced'),
                                'icon' => 'fas fa-wrench'
                            ]
                        ];

                        // Set the block_id for the tab component
                        $block_id = 'qr-code';
                        $active_tab = 'general';

                        // Include the reusable tab navigation
                        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
                        ?>

                        <!-- Tab Content -->
                        <div class="tab-content" id="qr-code-tabContent">
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="qr-code-general" role="tabpanel" aria-labelledby="qr-code-general-tab">
                                <!-- QR Code Type Selector -->
                                <div class="form-group">
                                    <label><i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.type') ?></label>
                                    <div class="flex-wrap btn-group-toggle d-flex" data-toggle="buttons">
                                        <?php 
                                        $allowed_types = ['text', 'url', 'phone', 'email'];
                                        foreach($data->available_qr_codes as $key => $value): 
                                            if (!in_array($key, $allowed_types)) continue;
                                        ?>
                                            <label class="mr-2 mb-2 btn btn-light font-size-small font-weight-500 <?= $data->values['type'] == $key ? 'active' : null ?>" data-toggle="tooltip" title="<?= l('qr_codes.type.' . $key . '_description') ?>" data-tooltip-hide-on-click>
                                                <input type="radio" name="type" value="<?= $key ?>" class="custom-control-input" <?= $data->values['type'] == $key ? 'checked="checked"' : null ?> required="required" data-reload-qr-code />
                                                <i class="<?= $value['icon'] ?> fa-fw fa-sm mr-1"></i> <?= l('qr_codes.type.' . $key) ?>
                                            </label>
                                        <?php endforeach ?>
                                    </div>
                                </div>

                                <div class="form-group d-lg-none">
                                    <label for="type_mobile"><i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.type') ?></label>
                                    <select id="type_mobile" name="type" class="custom-select">
                                        <?php 
                                        $allowed_types = ['text', 'url', 'phone', 'email'];
                                        foreach(array_keys($data->available_qr_codes) as $type): 
                                            if (!in_array($type, $allowed_types)) continue;
                                        ?>
                                            <option value="<?= $type ?>" <?= ($data->values['type'] ?? null) == $type ? 'selected="selected"' : null ?>><?= $data->available_qr_codes[$type]['emoji'] . ' ' . l('qr_codes.type.' . $type) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                                    <input type="text" id="name" name="name" class="form-control <?= \SeeGap\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?? null ?>" maxlength="64" required="required" />
                                    <?= \SeeGap\Alerts::output_field_error('name') ?>
                                </div>

                        <?php if(settings()->links->projects_is_enabled): ?>
                        <div class="form-group">
                            <div class="d-flex flex-column flex-xl-row justify-content-between">
                                <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                                <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                            </div>
                            <select id="project_id" name="project_id" class="custom-select">
                                <option value=""><?= l('global.none') ?></option>
                                <?php foreach($data->projects as $row): ?>
                                    <option value="<?= $row->project_id ?>" <?= ($data->values['project_id'] ?? null) == $row->project_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <?php endif ?>

                        <div>
                            <div class="form-group" data-type="text" data-character-counter="textarea">
                                <label for="text" class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.text') ?></span>
                                    <small class="text-muted" data-character-counter-wrapper></small>
                                </label>
                                <textarea id="text" name="text" class="form-control <?= \SeeGap\Alerts::has_field_errors('text') ? 'is-invalid' : null ?>" maxlength="<?= $data->available_qr_codes['text']['max_length'] ?>" required="required" data-reload-qr-code><?= $data->values['settings']['text'] ?? null ?></textarea>
                                <?= \SeeGap\Alerts::output_field_error('text') ?>
                            </div>

                            <div class="form-group" data-type="text">
                                <div <?= $this->user->plan_settings->qr_codes_bulk_limit ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->qr_codes_bulk_limit ? null : 'container-disabled' ?>">
                                        <div class="custom-control custom-checkbox">
                                            <input id="is_bulk" name="is_bulk" type="checkbox" class="custom-control-input" <?= ($data->values['is_bulk'] ?? null) ? 'checked="checked"' : null ?> data-reload-qr-code />
                                            <label class="custom-control-label" for="is_bulk"><?= l('qr_codes.input.is_bulk') ?></label>
                                            <small class="form-text text-muted"><?= sprintf(l('qr_codes.input.is_bulk_help'), $this->user->plan_settings->qr_codes_bulk_limit) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="url" data-url>
                                <label for="url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('global.url') ?></label>
                                <input type="url" id="url" name="url" class="form-control <?= \SeeGap\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['url'] ?? null ?>" maxlength="<?= $data->available_qr_codes['url']['max_length'] ?>" required="required" placeholder="<?= l('global.url_placeholder') ?>" data-reload-qr-code />
                                <?= \SeeGap\Alerts::output_field_error('url') ?>
                            </div>

                            <div class="form-group" data-type="url" data-dynamic-type>
                                <label for="dynamic_type"><i class="fas fa-fw fa-layer-group fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.dynamic_type') ?></label>
                                <select id="dynamic_type" name="dynamic_type" class="custom-select" data-reload-qr-code>
                                    <option value=""><?= l('global.choose') ?></option>
                                    <option value="links" <?= ($data->values['dynamic_type'] ?? null) == 'links' ? 'selected="selected"' : null?>><i class="fas fa-fw fa-link mr-1"></i> <?= l('links.links') ?></option>
                                    <option value="gs1_links" <?= ($data->values['dynamic_type'] ?? null) == 'gs1_links' ? 'selected="selected"' : null?>><i class="fas fa-fw fa-barcode mr-1"></i> <?= l('gs1_links.gs1_links') ?></option>
                                </select>
                            </div>

                            <div class="form-group" data-type="url" data-dynamic-links>
                                <div class="d-flex flex-column flex-xl-row justify-content-between">
                                    <label for="link_id"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.link_id') ?></label>
                                    <a href="<?= url('link-create') ?>" target="_blank" class="small mb-2" id="create_link_btn"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.create') ?></a>
                                </div>
                                <select id="link_id" name="link_id" class="custom-select" required="required" data-reload-qr-code>
                                    <option value=""><?= l('global.none') ?></option>
                                    <?php foreach($data->links as $row): ?>
                                        <option value="<?= $row->link_id ?>" <?= ($data->values['link_id'] ?? null) == $row->link_id ? 'selected="selected"' : null?> data-url="<?= $row->full_url ?>">
                                            <?= remove_url_protocol_from_url($row->full_url) . ($row->location_url ? ' -> ' . remove_url_protocol_from_url($row->location_url) : null) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                                <?php if(empty($data->links)): ?>
                                    <small class="form-text text-muted"><?= l('qr_codes.input.no_links_available') ?></small>
                                <?php endif ?>
                            </div>

                            <div class="form-group" data-type="url" data-dynamic-gs1-links>
                                <div class="d-flex flex-column flex-xl-row justify-content-between">
                                    <label for="gs1_link_id"><i class="fas fa-fw fa-barcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.gs1_link_id') ?></label>
                                    <a href="<?= url('gs1-link-manager/create') ?>" target="_blank" class="small mb-2" id="create_gs1_link_btn"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.create') ?></a>
                                </div>
                                <select id="gs1_link_id" name="gs1_link_id" class="custom-select" data-reload-qr-code>
                                    <option value=""><?= l('global.none') ?></option>
                                    <?php foreach($data->gs1_links as $row): ?>
                                        <?php 
                                        // Ensure we have a proper full URL for the data-url attribute
                                        if ($row->full_url) {
                                            $gs1_full_url = $row->full_url;
                                        } else {
                                            // Generate fallback URL with proper domain handling
                                            if ($row->domain_id && isset($row->scheme) && isset($row->host)) {
                                                $domain = $row->scheme . $row->host;
                                            } else {
                                                $domain = SITE_URL;
                                            }
                                            $gs1_full_url = rtrim($domain, '/') . '/01/' . $row->gtin;
                                        }
                                        ?>
                                        <option value="<?= $row->gs1_link_id ?>" <?= ($data->values['gs1_link_id'] ?? null) == $row->gs1_link_id ? 'selected="selected"' : null?> data-url="<?= $gs1_full_url ?>">
                                            <?= $gs1_full_url ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                                <?php if(empty($data->gs1_links)): ?>
                                    <small class="form-text text-muted"><?= l('qr_codes.input.no_gs1_links_available') ?></small>
                                <?php endif ?>
                            </div>

                            <div class="form-group" data-type="url">
                                <div class="custom-control custom-checkbox">
                                    <input id="url_dynamic" name="url_dynamic" type="checkbox" class="custom-control-input" <?= ($data->values['url_dynamic'] ?? null) ? 'checked="checked"' : null ?> data-reload-qr-code />
                                    <label class="custom-control-label" for="url_dynamic"><?= l('qr_codes.input.url_dynamic') ?></label>
                                    <small class="form-text text-muted"><?= l('qr_codes.input.url_dynamic_help') ?></small>
                                    <small class="form-text text-muted"><?= l('qr_codes.input.url_dynamic_help2') ?></small>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="phone">
                                <label for="phone"><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.phone') ?></label>
                                <input type="text" id="phone" name="phone" class="form-control <?= \SeeGap\Alerts::has_field_errors('phone') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['phone'] ?? null ?>" maxlength="<?= $data->available_qr_codes['phone']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \SeeGap\Alerts::output_field_error('phone') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="email">
                                <label for="email"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.email') ?></label>
                                <input type="text" id="email" name="email" class="form-control <?= \SeeGap\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['email'] ?? null ?>" maxlength="<?= $data->available_qr_codes['email']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \SeeGap\Alerts::output_field_error('email') ?>
                            </div>

                            <div class="form-group" data-type="email">
                                <label for="email_subject"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.email_subject') ?></label>
                                <input type="text" id="email_subject" name="email_subject" class="form-control <?= \SeeGap\Alerts::has_field_errors('email_subject') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['email_subject'] ?? null ?>" maxlength="<?= $data->available_qr_codes['email']['body']['max_length'] ?>" data-reload-qr-code />
                                <?= \SeeGap\Alerts::output_field_error('email_subject') ?>
                            </div>

                            <div class="form-group" data-type="email">
                                <label for="email_body"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.email_body') ?></label>
                                <textarea id="email_body" name="email_body" class="form-control <?= \SeeGap\Alerts::has_field_errors('email_body') ? 'is-invalid' : null ?>" maxlength="<?= $data->available_qr_codes['email']['body']['max_length'] ?>" data-reload-qr-code><?= $data->values['settings']['email_body'] ?? null ?></textarea>
                                <?= \SeeGap\Alerts::output_field_error('email_body') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Design Tab -->
                    <div class="tab-pane fade" id="qr-code-design" role="tabpanel" aria-labelledby="qr-code-design-tab">
                        <div class="form-group">
                            <label for="style"><i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.style') ?></label>
                            <div class="row btn-group-toggle p-2" data-toggle="buttons">
                                <?php foreach($data->styles as $key => $style): ?>
                                    <div class="col-2 p-1">
                                        <label class="btn btn-light btn-block mb-0 text-truncate <?= ($data->values['settings']['style'] ?? null) == $key ? 'active' : null?>" data-toggle="tooltip" title="<?= l('qr_codes.input.style.' . $key) ?>" data-tooltip-hide-on-click>
                                            <input type="radio" name="style" value="<?= $key ?>" class="custom-control-input" <?= ($data->values['settings']['style'] ?? null) == $key ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                            <div class="py-1">
                                                <?= sprintf($style['svg'], 'var(--primary-800)') ?>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <!-- Colors Section -->
                        <h6 class="mt-4 mb-3"><?= l('qr_codes.input.colors') ?></h6>
                        <div class="form-group">
                            <label for="foreground_type"><i class="fas fa-fw fa-paint-roller fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.foreground_type') ?></label>
                            <div class="row btn-group-toggle" data-toggle="buttons">
                                <div class="col-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($data->values['settings']['foreground_type'] ?? null) == 'color' ? 'active"' : null?>">
                                        <input type="radio" name="foreground_type" value="color" class="custom-control-input" <?= ($data->values['settings']['foreground_type'] ?? null) == 'color' ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                        <i class="fas fa-fw fa-eyedropper fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.foreground_type_color') ?>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($data->values['settings']['foreground_type'] ?? null) == 'gradient' ? 'active' : null?>">
                                        <input type="radio" name="foreground_type" value="gradient" class="custom-control-input" <?= ($data->values['settings']['foreground_type'] ?? null) == 'gradient' ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                        <i class="fas fa-fw fa-fill-drip fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.foreground_type_gradient') ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" data-foreground-type="color">
                            <label for="foreground_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.foreground_color') ?></label>
                            <input type="hidden" id="foreground_color" name="foreground_color" class="form-control <?= \SeeGap\Alerts::has_field_errors('foreground_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['foreground_color'] ?? '#000000' ?>" data-reload-qr-code data-color-picker />
                            <?= \SeeGap\Alerts::output_field_error('foreground_color') ?>
                        </div>

                        <div class="form-group">
                            <label for="background_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.background_color') ?></label>
                            <input type="hidden" id="background_color" name="background_color" class="form-control <?= \SeeGap\Alerts::has_field_errors('background_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['background_color'] ?? '#ffffff' ?>" data-reload-qr-code data-color-picker />
                            <?= \SeeGap\Alerts::output_field_error('background_color') ?>
                        </div>
                    </div>

                    <!-- Frame Tab -->
                    <div class="tab-pane fade" id="qr-code-frame" role="tabpanel" aria-labelledby="qr-code-frame-tab">
                        <div class="form-group">
                            <label for="frame"><i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.frame') ?></label>
                            <div class="row btn-group-toggle" data-toggle="buttons">
                                <div class="col-6 col-lg-4 mb-3">
                                    <label class="btn btn-light btn-block d-flex align-items-center justify-content-center <?= !($data->values['settings']['frame']) ? 'active"' : null?>" data-toggle="tooltip" data-tooltip-hide-on-click title="<?= l('global.none') ?>" style="height: 125px;">
                                        <input type="radio" name="frame" value="" class="custom-control-input" <?= !($data->values['settings']['frame']) ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                        <i class="fas fa-fw fa-3x fa-times"></i>
                                    </label>
                                </div>

                                <?php foreach($data->frames as $key => $frame): ?>
                                    <div class="col-6 col-lg-4 mb-3">
                                        <label class="btn btn-light btn-block d-flex align-items-center justify-content-center <?= ($data->values['settings']['frame']) == $key ? 'active"' : null?>" style="height: 125px;">
                                            <input type="radio" name="frame" value="<?= $key ?>" class="custom-control-input" <?= ($data->values['settings']['frame']) == $key ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                            <?= sprintf($frame['svg'], 75, 75 * $frame['frame_height_scale'], 75 / $frame['frame_scale'], 'var(--gray-900)', 75 * $frame['frame_translate_x'], 75 * $frame['frame_translate_y']) ?>
                                        </label>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="frame_text"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.frame_text') ?></label>
                            <input type="text" id="frame_text" name="frame_text" class="form-control <?= \SeeGap\Alerts::has_field_errors('frame_text') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['frame_text'] ?>" maxlength="64" data-reload-qr-code />
                            <?= \SeeGap\Alerts::output_field_error('frame_text') ?>
                        </div>
                    </div>

                    <!-- Branding Tab -->
                    <div class="tab-pane fade" id="qr-code-branding" role="tabpanel" aria-labelledby="qr-code-branding-tab">
                        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->codes->logo_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->codes->logo_size_limit) ?>">
                            <label for="qr_code_logo"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('qr_codes.input.qr_code_logo') ?></label>
                            <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'qr_code_logo', 'file_key' => 'qr_code_logo', 'already_existing_image' => null, 'input_data' => 'data-reload-qr-code']) ?>
                            <?= \SeeGap\Alerts::output_field_error('qr_code_logo') ?>
                            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('qr_code_logo')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->codes->logo_size_limit) ?></small>
                        </div>

                        <div class="form-group" data-range-counter>
                            <label for="qr_code_logo_size"><i class="fas fa-fw fa-expand-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.qr_code_logo_size') ?></label>
                            <input id="qr_code_logo_size" type="range" min="5" max="40" name="qr_code_logo_size" value="<?= $data->values['settings']['qr_code_logo_size'] ?? 25 ?>" class="form-control-range <?= \SeeGap\Alerts::has_field_errors('qr_code_logo_size') ? 'is-invalid' : null ?>" data-reload-qr-code />
                            <?= \SeeGap\Alerts::output_field_error('qr_code_logo_size') ?>
                        </div>
                    </div>

                    <!-- Advanced Tab -->
                    <div class="tab-pane fade" id="qr-code-advanced" role="tabpanel" aria-labelledby="qr-code-advanced-tab">
                        <div class="form-group">
                            <label for="size"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.size') ?></label>
                            <div class="input-group">
                                <input id="size" type="number" min="50" max="2000" name="size" class="form-control <?= \SeeGap\Alerts::has_field_errors('size') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['size'] ?? 500 ?>" data-reload-qr-code />
                                <div class="input-group-append">
                                    <span class="input-group-text">px</span>
                                </div>
                            </div>
                            <?= \SeeGap\Alerts::output_field_error('size') ?>
                        </div>

                        <div class="form-group">
                            <label for="margin"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.margin') ?></label>
                            <input id="margin" type="number" min="0" max="25" name="margin" class="form-control <?= \SeeGap\Alerts::has_field_errors('margin') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['margin'] ?? 0 ?>" data-reload-qr-code />
                            <?= \SeeGap\Alerts::output_field_error('margin') ?>
                        </div>

                        <div class="form-group">
                            <label for="ecc"><i class="fas fa-fw fa-check fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.ecc') ?></label>
                            <select id="ecc" name="ecc" class="custom-select" data-reload-qr-code>
                                <?php foreach(['L', 'M', 'Q', 'H'] as $level): ?>
                                    <option value="<?= $level ?>" <?= ($data->values['settings']['ecc'] ?? 'M') == $level ? 'selected="selected"' : null ?>><?= l('qr_codes.input.ecc_' . mb_strtolower($level)) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="encoding"><i class="fas fa-fw fa-feather-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.encoding') ?></label>
                            <select id="encoding" name="encoding" class="custom-select" data-reload-qr-code>
                                <?php foreach(['ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5', 'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10', 'ISO-8859-11', 'ISO-8859-12', 'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16', 'SHIFT-JIS', 'WINDOWS-1250', 'WINDOWS-1251', 'WINDOWS-1252', 'WINDOWS-1256', 'UTF-16BE', 'UTF-8', 'ASCII', 'GBK', 'EUC-KR'] as $encoding): ?>
                                    <option value="<?= $encoding ?>" <?= ($data->values['settings']['encoding'] ?? 'UTF-8') == $encoding ? 'selected="selected"' : null ?>><?= $encoding ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4">
                    <?= l('global.create') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="sticky">
            <div class="mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 300px;">
                        <img id="qr_code" src="<?= settings()->codes->qr_codes_default_image ? \SeeGap\Uploads::get_full_url('qr_code_default_image') . settings()->codes->qr_codes_default_image : ASSETS_FULL_URL . 'images/qr_code.svg' ?>" class="img-fluid qr-code" loading="lazy" />
                    </div>
                </div>
            </div>

            <div class="row mb-4 d-print-none">
                <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                    <button type="button" onclick="window.print()" class="btn btn-block btn-outline-secondary d-print-none <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>" <?= $this->user->plan_settings->export->pdf ? null : 'disabled="disabled" data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-1"></i> <?= l('qr_codes.print') ?>
                    </button>
                </div>

                <div class="col-12 col-lg-6 mb-3 mb-lg-0 dropdown">
                    <button type="button" class="btn btn-block btn-primary d-print-none dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-fw fa-sm fa-download mr-1"></i> <?= l('global.download') ?>
                    </button>

                    <div class="dropdown-menu">
                        <a href="<?= $data->values['settings']['data'] ?? (settings()->codes->qr_codes_default_image ? \SeeGap\Uploads::get_full_url('qr_code_default_image') . settings()->codes->qr_codes_default_image : ASSETS_FULL_URL . 'images/qr_code.svg') ?>" id="download_svg" class="dropdown-item" download="<?= get_slug($data->values['name'] ?? settings()->main->title) . '.svg' ?>"><?= sprintf(l('global.download_as'), 'SVG') ?></a>
                        <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others(null, 'png', '<?= get_slug($data->values['name'] ?? settings()->main->title) . '.png' ?>');"><?= sprintf(l('global.download_as'), 'PNG') ?></button>
                        <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others(null, 'jpg', '<?= get_slug($data->values['name'] ?? settings()->main->title) . '.jpg' ?>');"><?= sprintf(l('global.download_as'), 'JPG') ?></button>
                        <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others(null, 'webp', '<?= get_slug($data->values['name'] ?? settings()->main->title) . '.webp' ?>');"><?= sprintf(l('global.download_as'), 'WEBP') ?></button>
                    </div>
                </div>
            </div>

            <button id="embedded_data_container_button" class="btn btn-block btn-light my-4 d-none d-print-none" type="button" data-toggle="collapse" data-target="#embedded_data_container" aria-expanded="false" aria-controls="embedded_data_container">
                <i class="fas fa-fw fa-bars fa-sm mr-1"></i> <?= l('qr_codes.embedded_data') ?>
            </button>

            <div class="collapse" id="embedded_data_container">
                <div class="card my-4">
                    <div class="card-body" id="embedded_data_display"></div>
                </div>
            </div>

            <div class="mb-4 text-center d-print-none">
                <small>
                    <i class="fas fa-fw fa-info-circle fa-sm text-muted mr-1"></i> <span class="text-muted"><?= l('qr_codes.info') ?></span>
                </small>

                <div id="is_readable" class="d-none text-success small mt-2">
                    <i class="fas fa-fw fa-check-circle fa-sm mr-1"></i> <?= l('qr_codes.is_readable') ?>
                </div>

                <div id="is_not_readable" class="d-none text-warning small mt-2">
                    <i class="fas fa-fw fa-exclamation-circle fa-sm mr-1"></i> <?= l('qr_codes.is_not_readable') ?>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
</div>

<?php require THEME_PATH . 'views/qr-codes/js_qr_codes.php' ?>
<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
