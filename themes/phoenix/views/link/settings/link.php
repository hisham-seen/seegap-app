<?php defined('SEEGAP') || die() ?>

<div class="row link-settings">
    <!-- Left Column - Settings -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3 d-flex align-items-center">
                    <i class="fas fa-fw fa-cog fa-sm text-muted mr-1"></i> 
                    <?= l('link.settings.header') ?>
                </h6>

                <form name="update_link" action="" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                    <input type="hidden" name="request_type" value="update" />
                    <input type="hidden" name="type" value="link" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />

                    <div class="notification-container"></div>

                    <!-- Tab Navigation - Matching Microsite Block Style -->
                    <div class="microsite-block-tabs">
                        <div class="nav nav-pills nav-fill nav-minimal mb-4" id="link-settings-tab" role="tablist">
                            <a class="nav-item nav-link active" 
                               id="link-general-tab" 
                               data-toggle="pill" 
                               href="#link-general" 
                               role="tab" 
                               aria-controls="link-general" 
                               aria-selected="true"
                               data-toggle="tooltip" 
                               title="<?= l('link.settings.general_tab') ?? 'General' ?>">
                                <i class="fas fa-cog"></i>
                            </a>
                            <a class="nav-item nav-link" 
                               id="link-targeting-tab" 
                               data-toggle="pill" 
                               href="#link-targeting" 
                               role="tab" 
                               aria-controls="link-targeting" 
                               aria-selected="false"
                               data-toggle="tooltip" 
                               title="<?= l('link.settings.targeting_tab') ?? 'Targeting' ?>">
                                <i class="fas fa-bullseye"></i>
                            </a>
                            <a class="nav-item nav-link" 
                               id="link-tracking-tab" 
                               data-toggle="pill" 
                               href="#link-tracking" 
                               role="tab" 
                               aria-controls="link-tracking" 
                               aria-selected="false"
                               data-toggle="tooltip" 
                               title="<?= l('link.settings.tracking_tab') ?? 'Tracking' ?>">
                                <i class="fas fa-chart-line"></i>
                            </a>
                            <a class="nav-item nav-link" 
                               id="link-security-tab" 
                               data-toggle="pill" 
                               href="#link-security" 
                               role="tab" 
                               aria-controls="link-security" 
                               aria-selected="false"
                               data-toggle="tooltip" 
                               title="<?= l('link.settings.security_tab') ?? 'Security' ?>">
                                <i class="fas fa-shield-alt"></i>
                            </a>
                            <a class="nav-item nav-link" 
                               id="link-seo-tab" 
                               data-toggle="pill" 
                               href="#link-seo" 
                               role="tab" 
                               aria-controls="link-seo" 
                               aria-selected="false"
                               data-toggle="tooltip" 
                               title="<?= l('link.settings.seo_tab') ?? 'SEO' ?>">
                                <i class="fas fa-search"></i>
                            </a>
                            <a class="nav-item nav-link" 
                               id="link-advanced-tab" 
                               data-toggle="pill" 
                               href="#link-advanced" 
                               role="tab" 
                               aria-controls="link-advanced" 
                               aria-selected="false"
                               data-toggle="tooltip" 
                               title="<?= l('link.settings.advanced_tab') ?? 'Advanced' ?>">
                                <i class="fas fa-user-tie"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="link-settings-tabContent">
                        
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="link-general" role="tabpanel" aria-labelledby="link-general-tab">
                            <div class="form-group">
                                <label for="location_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('link.settings.location_url') ?></label>
                                <input id="location_url" type="text" class="form-control" name="location_url" value="<?= $data->link->location_url ?>" maxlength="2048" required="required" placeholder="<?= l('global.url_placeholder') ?>" />
                            </div>

                            <div class="form-group">
                                <label for="url"><i class="fas fa-fw fa-bolt fa-sm text-muted mr-1"></i> <?= l('link.settings.url') ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <?php if(count($data->domains)): ?>
                                            <select name="domain_id" class="appearance-none custom-select form-control input-group-text">
                                                <?php if(settings()->links->main_domain_is_enabled || \SeeGap\Authentication::is_admin()): ?>
                                                    <option value="" <?= $data->link->domain ? 'selected="selected"' : null ?> data-full-url="<?= SITE_URL ?>"><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                                                <?php endif ?>
                                                <?php foreach($data->domains as $row): ?>
                                                    <option value="<?= $row->domain_id ?>" <?= $data->link->domain && $row->domain_id == $data->link->domain->domain_id ? 'selected="selected"' : null ?>  data-full-url="<?= $row->url ?>" data-type="<?= $row->type ?>"><?= remove_url_protocol_from_url($row->url) ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        <?php else: ?>
                                            <span class="input-group-text"><?= remove_url_protocol_from_url(SITE_URL) ?></span>
                                        <?php endif ?>
                                    </div>

                                    <input
                                            id="url"
                                            type="text"
                                            class="form-control"
                                            name="url"
                                            placeholder="<?= l('global.url_slug_placeholder') ?>"
                                            value="<?= $data->link->url ?>"
                                            maxlength="<?= $this->user->plan_settings->url_maximum_characters ?? 64 ?>"
                                            onchange="update_this_value(this, get_slug)"
                                            onkeyup="update_this_value(this, get_slug)"
                                        <?= !$this->user->plan_settings->custom_url ? 'readonly="readonly"' : null ?>
                                        <?= $this->user->plan_settings->custom_url ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>
                                    />
                                </div>
                                <small class="form-text text-muted"><?= l('link.settings.url_help') ?></small>
                            </div>

                            <?php if(count($data->domains)): ?>
                                <div id="is_main_link_wrapper" class="form-group custom-control custom-switch">
                                    <input id="is_main_link" name="is_main_link" type="checkbox" class="custom-control-input" <?= $data->link->domain_id && $data->domains[$data->link->domain_id]->link_id == $data->link->link_id ? 'checked="checked"' : null ?>>
                                    <label class="custom-control-label" for="is_main_link"><?= l('link.settings.is_main_link') ?></label>
                                    <small class="form-text text-muted"><?= l('link.settings.is_main_link_help') ?></small>
                                </div>
                            <?php endif ?>
                        </div>

                        <!-- Targeting Tab -->
                        <div class="tab-pane fade" id="link-targeting" role="tabpanel" aria-labelledby="link-targeting-tab">
                            <div <?= $this->user->plan_settings->targeting_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                <div class="<?= $this->user->plan_settings->targeting_is_enabled ? null : 'container-disabled' ?>">
                                    <div class="form-group">
                                        <label for="targeting_type"><i class="fas fa-fw fa-bullseye fa-sm text-muted mr-1"></i> <?= l('link.settings.targeting_type') ?></label>
                                        <select id="targeting_type" name="targeting_type" class="custom-select">
                                            <option value="false" <?= $data->link->settings->targeting_type == 'false' ? 'selected="selected"' : null?>>😊 <?= l('global.none') ?></option>
                                            <option value="continent_code" <?= $data->link->settings->targeting_type == 'continent_code' ? 'selected="selected"' : null?>>🌍 <?= l('global.continent') ?></option>
                                            <option value="country_code" <?= $data->link->settings->targeting_type == 'country_code' ? 'selected="selected"' : null?>>🇨🇺 <?= l('global.country') ?></option>
                                            <option value="city_name" <?= $data->link->settings->targeting_type == 'city_name' ? 'selected="selected"' : null?>>🏙️ <?= l('global.city') ?></option>
                                            <option value="device_type" <?= $data->link->settings->targeting_type == 'device_type' ? 'selected="selected"' : null?>>📱 <?= l('link.settings.targeting_type_device_type') ?></option>
                                            <option value="os_name" <?= $data->link->settings->targeting_type == 'os_name' ? 'selected="selected"' : null?>>💻 <?= l('link.settings.targeting_type_os_name') ?></option>
                                            <option value="browser_name" <?= $data->link->settings->targeting_type == 'browser_name' ? 'selected="selected"' : null?>>🌐 <?= l('link.settings.targeting_type_browser_name') ?></option>
                                            <option value="browser_language" <?= $data->link->settings->targeting_type == 'browser_language' ? 'selected="selected"' : null?>>🗣️ <?= l('link.settings.targeting_type_browser_language') ?></option>
                                            <option value="rotation" <?= $data->link->settings->targeting_type == 'rotation' ? 'selected="selected"' : null?>>🔄 <?= l('link.settings.targeting_type_rotation') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tracking Tab -->
                        <div class="tab-pane fade" id="link-tracking" role="tabpanel" aria-labelledby="link-tracking-tab">
                            <?php if(settings()->links->pixels_is_enabled): ?>
                                <div class="form-group">
                                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                                        <label><i class="fas fa-fw fa-sm fa-adjust text-muted mr-1"></i> <?= l('link.settings.pixels_ids') ?></label>
                                        <a href="<?= url('pixels') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('pixels.create') ?></a>
                                    </div>

                                    <div class="row">
                                        <?php $available_pixels = require APP_PATH . 'includes/pixels.php'; ?>
                                        <?php foreach($data->pixels as $pixel): ?>
                                            <div class="col-12 col-lg-6">
                                                <div class="custom-control custom-checkbox my-2">
                                                    <input id="pixel_id_<?= $pixel->pixel_id ?>" name="pixels_ids[]" value="<?= $pixel->pixel_id ?>" type="checkbox" class="custom-control-input" <?= in_array($pixel->pixel_id, $data->link->pixels_ids) ? 'checked="checked"' : null ?>>
                                                    <label class="custom-control-label d-flex align-items-center" for="pixel_id_<?= $pixel->pixel_id ?>">
                                                        <span class="text-truncate" title="<?= $pixel->name ?>"><?= $pixel->name ?></span>
                                                        <small class="badge badge-light ml-1" data-toggle="tooltip" title="<?= $available_pixels[$pixel->type]['name'] ?>">
                                                            <i class="<?= $available_pixels[$pixel->type]['icon'] ?> fa-fw fa-sm" style="color: <?= $available_pixels[$pixel->type]['color'] ?>"></i>
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <!-- UTM Parameters -->
                            <div <?= $this->user->plan_settings->utm ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                <div class="<?= $this->user->plan_settings->utm ? null : 'container-disabled' ?>">
                                    <h6 class="mt-4 mb-3"><i class="fas fa-fw fa-keyboard fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_header') ?></h6>
                                    
                                    <div class="form-group">
                                        <label for="utm_source"><i class="fas fa-fw fa-sitemap fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_source') ?></label>
                                        <input id="utm_source" type="text" class="form-control" name="utm_source" value="<?= $data->link->settings->utm->source ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_source_placeholder') ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="utm_medium"><i class="fas fa-fw fa-inbox fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_medium') ?></label>
                                        <input id="utm_medium" type="text" class="form-control" name="utm_medium" value="<?= $data->link->settings->utm->medium ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_medium_placeholder') ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="utm_campaign"><i class="fas fa-fw fa-bullhorn fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_campaign') ?></label>
                                        <input id="utm_campaign" type="text" class="form-control" name="utm_campaign" value="<?= $data->link->settings->utm->campaign ?? '' ?>" placeholder="<?= l('link.settings.utm_campaign_placeholder') ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="utm_preview"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_preview') ?></label>
                                        <input id="utm_preview" type="text" class="form-control-plaintext" name="utm_preview" readonly="readonly" />
                                        <small class="form-text text-muted"><?= l('link.settings.utm_preview_help') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="link-security" role="tabpanel" aria-labelledby="link-security-tab">
                            <div class="form-group custom-control custom-switch">
                                <input
                                        id="schedule"
                                        name="schedule"
                                        type="checkbox"
                                        class="custom-control-input"
                                    <?= $data->link->settings->schedule && !empty($data->link->start_date) && !empty($data->link->end_date) ? 'checked="checked"' : null ?>
                                    <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label" for="schedule"><?= l('link.settings.schedule') ?></label>
                                <small class="form-text text-muted"><?= l('link.settings.schedule_help') ?></small>
                            </div>

                            <div id="schedule_container" style="display: none;">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    name="start_date"
                                                    value="<?= \SeeGap\Date::get($data->link->start_date, 1) ?>"
                                                    placeholder="<?= l('link.settings.start_date') ?>"
                                                    autocomplete="off"
                                                    data-daterangepicker
                                            >
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    name="end_date"
                                                    value="<?= \SeeGap\Date::get($data->link->end_date, 1) ?>"
                                                    placeholder="<?= l('link.settings.end_date') ?>"
                                                    autocomplete="off"
                                                    data-daterangepicker
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="clicks_limit"><i class="fas fa-fw fa-mouse fa-sm text-muted mr-1"></i> <?= l('link.settings.clicks_limit') ?></label>
                                <input id="clicks_limit" type="number" class="form-control" name="clicks_limit" value="<?= $data->link->settings->clicks_limit ?>" />
                                <small class="form-text text-muted"><?= l('link.settings.clicks_limit_help') ?></small>
                            </div>

                            <div class="form-group">
                                <label for="expiration_url"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.expiration_url') ?></label>
                                <input id="expiration_url" type="url" class="form-control" name="expiration_url" value="<?= $data->link->settings->expiration_url ?>" maxlength="2048" />
                                <small class="form-text text-muted"><?= l('link.settings.expiration_url_help') ?></small>
                            </div>

                            <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                                <label for="qweasdzxc"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('global.password') ?></label>
                                <input id="qweasdzxc" type="password" class="form-control" name="qweasdzxc" value="<?= $data->link->settings->password ?>" autocomplete="new-password" <?= !$this->user->plan_settings->password ? 'disabled="disabled"': null ?> />
                                <small class="form-text text-muted"><?= l('link.settings.password_help') ?></small>
                            </div>

                            <div class="form-group custom-control custom-switch">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="sensitive_content"
                                        name="sensitive_content"
                                    <?= !$this->user->plan_settings->sensitive_content ? 'disabled="disabled"': null ?>
                                    <?= $data->link->settings->sensitive_content ? 'checked="checked"' : null ?>
                                >
                                <label class="custom-control-label" for="sensitive_content"><?= l('link.settings.sensitive_content') ?></label>
                                <small class="form-text text-muted"><?= l('link.settings.sensitive_content_help') ?></small>
                            </div>
                        </div>

                        <!-- SEO Tab -->
                        <div class="tab-pane fade" id="link-seo" role="tabpanel" aria-labelledby="link-seo-tab">
                            <div <?= $this->user->plan_settings->cloaking_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                <div class="<?= $this->user->plan_settings->cloaking_is_enabled ? null : 'container-disabled' ?>">
                                    <div class="form-group custom-control custom-switch">
                                        <input
                                                id="cloaking_is_enabled"
                                                name="cloaking_is_enabled"
                                                type="checkbox"
                                                class="custom-control-input"
                                            <?= $data->link->settings->cloaking_is_enabled ? 'checked="checked"' : null ?>
                                            <?= $this->user->plan_settings->cloaking_is_enabled ? null : 'disabled="disabled"' ?>
                                        >
                                        <label class="custom-control-label" for="cloaking_is_enabled"><i class="fas fa-fw fa-user-tie fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_is_enabled') ?></label>
                                        <small class="form-text text-muted"><?= l('link.settings.cloaking_is_enabled_help') ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cloaking_title"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_title') ?></label>
                                <input id="cloaking_title" type="text" class="form-control" name="cloaking_title" value="<?= $data->link->settings->cloaking_title ?>" maxlength="70" />
                            </div>

                            <div class="form-group">
                                <label for="cloaking_meta_description"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_meta_description') ?></label>
                                <input id="cloaking_meta_description" type="text" class="form-control" name="cloaking_meta_description" value="<?= $data->link->settings->cloaking_meta_description ?>" maxlength="160" />
                            </div>

                            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->favicon_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->favicon_size_limit) ?>">
                                <label for="cloaking_favicon"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_favicon') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'favicons', 'file_key' => 'cloaking_favicon', 'already_existing_image' => $data->link->settings->cloaking_favicon, 'input_data' => 'data-crop data-aspect-ratio="1"']) ?>
                                <?= \SeeGap\Alerts::output_field_error('cloaking_favicon') ?>
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('favicons')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->favicon_size_limit) ?></small>
                            </div>

                            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->seo_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->seo_image_size_limit) ?>">
                                <label for="cloaking_opengraph"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_opengraph') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'microsite_seo_image', 'file_key' => 'cloaking_opengraph', 'already_existing_image' => $data->link->settings->cloaking_opengraph, 'input_data' => 'data-crop data-aspect-ratio="1.91"']) ?>
                                <?= \SeeGap\Alerts::output_field_error('cloaking_opengraph') ?>
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('microsite_seo_image')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->seo_image_size_limit) ?></small>
                            </div>
                        </div>

                        <!-- Advanced Tab -->
                        <div class="tab-pane fade" id="link-advanced" role="tabpanel" aria-labelledby="link-advanced-tab">
                            <?php if(settings()->links->projects_is_enabled ?? false): ?>
                                <div class="form-group">
                                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                                        <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                                        <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                                    </div>
                                    <select id="project_id" name="project_id" class="custom-select">
                                        <option value=""><?= l('global.none') ?></option>
                                        <?php foreach($data->projects as $row): ?>
                                            <option value="<?= $row->project_id ?>" <?= $data->link->project_id == $row->project_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            <?php endif ?>

                            <?php if(settings()->links->splash_page_is_enabled): ?>
                                <div <?= $this->user->plan_settings->splash_pages_limit ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->splash_pages_limit ? null : 'container-disabled' ?>">
                                        <div class="form-group">
                                            <div class="d-flex flex-column flex-xl-row justify-content-between">
                                                <label for="splash_page_id"><i class="fas fa-fw fa-sm fa-droplet text-muted mr-1"></i> <?= l('splash_pages.splash_page_id') ?></label>
                                                <a href="<?= url('splash-pages') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('splash_pages.create') ?></a>
                                            </div>
                                            <select id="splash_page_id" name="splash_page_id" class="custom-select">
                                                <option value=""><?= l('global.none') ?></option>
                                                <?php foreach($data->splash_pages as $row): ?>
                                                    <option value="<?= $row->splash_page_id ?>" <?= $data->link->splash_page_id == $row->splash_page_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <div class="form-group" data-character-counter="textarea">
                                <label for="custom_css" class="d-flex justify-content-between align-items-center">
                                    <span><i class="fab fa-fw fa-sm fa-css3-alt text-muted mr-1"></i> <?= l('global.custom_css') ?></span>
                                    <small class="text-muted" data-character-counter-wrapper></small>
                                </label>
                                <textarea id="custom_css" class="form-control" name="custom_css" maxlength="10000" placeholder="<?= l('global.custom_css_placeholder') ?>"><?= $data->link->settings->custom_css ?></textarea>
                                <small class="form-text text-muted"><?= l('global.custom_css_help') ?></small>
                            </div>

                            <div class="form-group" data-character-counter="textarea">
                                <label for="custom_js" class="d-flex justify-content-between align-items-center">
                                    <span><i class="fab fa-fw fa-sm fa-js-square text-muted mr-1"></i> <?= l('global.custom_js') ?></span>
                                    <small class="text-muted" data-character-counter-wrapper></small>
                                </label>
                                <textarea id="custom_js" class="form-control" name="custom_js" maxlength="10000" placeholder="<?= l('global.custom_js_placeholder') ?>"><?= $data->link->settings->custom_js ?></textarea>
                                <small class="form-text text-muted"><?= l('global.custom_js_help') ?></small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn btn-block btn-primary mt-4">
                        <?= l('global.update') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Middle Column - Link Flow Visualization -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3 d-flex align-items-center">
                    <i class="fas fa-fw fa-route fa-sm text-muted mr-1"></i> 
                    <?= l('link.settings.flow_header') ?? 'Link Flow' ?>
                </h6>

                <div class="link-flow-visualization">
                    <!-- User Click Step -->
                    <div class="flow-step">
                        <div class="flow-icon">
                            <i class="fas fa-mouse-pointer fa-2x text-primary"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('link.settings.flow_step_click') ?? 'User Clicks Link' ?></h6>
                            <small class="text-muted">
                                <code class="small" id="flow_short_url"><?= $data->link->full_url ?></code>
                            </small>
                        </div>
                    </div>

                    <div class="flow-arrow">
                        <i class="fas fa-arrow-down text-muted"></i>
                    </div>

                    <!-- Targeting Decision Step (conditional) -->
                    <div class="flow-step" id="flow_targeting_step" style="<?= ($data->link->settings->targeting_type && $data->link->settings->targeting_type !== 'false') ? 'display: flex;' : 'display: none;' ?>">
                        <div class="flow-icon">
                            <i class="fas fa-bullseye fa-2x text-warning"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('link.settings.flow_step_targeting') ?? 'Targeting Rules' ?></h6>
                            <small class="text-muted" id="flow_targeting_description">
                                <?php if($data->link->settings->targeting_type && $data->link->settings->targeting_type !== 'false'): ?>
                                    <?php
                                    $targeting_labels = [
                                        'continent_code' => l('global.continent') . ' ' . l('link.settings.targeting_type'),
                                        'country_code' => l('global.country') . ' ' . l('link.settings.targeting_type'),
                                        'city_name' => l('global.city') . ' ' . l('link.settings.targeting_type'),
                                        'device_type' => l('link.settings.targeting_type_device_type'),
                                        'os_name' => l('link.settings.targeting_type_os_name'),
                                        'browser_name' => l('link.settings.targeting_type_browser_name'),
                                        'browser_language' => l('link.settings.targeting_type_browser_language'),
                                        'rotation' => l('link.settings.targeting_type_rotation')
                                    ];
                                    echo $targeting_labels[$data->link->settings->targeting_type] ?? l('link.settings.targeting_type');
                                    ?>
                                <?php else: ?>
                                    <?= l('link.settings.flow_step_targeting_description') ?? 'Apply targeting rules' ?>
                                <?php endif ?>
                            </small>
                        </div>
                    </div>

                    <div class="flow-arrow" id="flow_targeting_arrow" style="<?= ($data->link->settings->targeting_type && $data->link->settings->targeting_type !== 'false') ? 'display: block;' : 'display: none;' ?>">
                        <i class="fas fa-arrow-down text-muted"></i>
                    </div>

                    <!-- Security Check Step (conditional) -->
                    <div class="flow-step" id="flow_security_step" style="<?= ($data->link->settings->password || $data->link->settings->sensitive_content) ? 'display: flex;' : 'display: none;' ?>">
                        <div class="flow-icon">
                            <i class="fas fa-shield-alt fa-2x text-info"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('link.settings.flow_step_security') ?? 'Security Check' ?></h6>
                            <small class="text-muted">
                                <?php if($data->link->settings->password): ?>
                                    <?= l('link.settings.flow_password_protection') ?? 'Password protection' ?>
                                <?php elseif($data->link->settings->sensitive_content): ?>
                                    <?= l('link.settings.flow_sensitive_content') ?? 'Sensitive content warning' ?>
                                <?php endif ?>
                            </small>
                        </div>
                    </div>

                    <div class="flow-arrow" id="flow_security_arrow" style="<?= ($data->link->settings->password || $data->link->settings->sensitive_content) ? 'display: block;' : 'display: none;' ?>">
                        <i class="fas fa-arrow-down text-muted"></i>
                    </div>

                    <!-- Final Destination Step -->
                    <div class="flow-step">
                        <div class="flow-icon">
                            <i class="fas fa-external-link-alt fa-2x text-success"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('link.settings.flow_step_destination') ?? 'Final Destination' ?></h6>
                            <small class="text-muted">
                                <span id="flow_destination_url">
                                    <?php $display_url = strlen($data->link->location_url) > 40 ? substr($data->link->location_url, 0, 40) . '...' : $data->link->location_url; ?>
                                    <code class="small"><?= $display_url ?></code>
                                </span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Live Preview -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3 d-flex align-items-center">
                    <i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> 
                    <?= l('link.settings.preview_header') ?? 'Live Preview' ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary ml-auto" id="refresh_preview">
                        <i class="fas fa-sync-alt fa-sm"></i>
                    </button>
                </h6>

                <div class="d-flex justify-content-center">
                    <div class="browser-preview">
                        <div class="browser-frame">
                            <div class="browser-header">
                                <div class="browser-controls">
                                    <span class="browser-dot browser-dot-red"></span>
                                    <span class="browser-dot browser-dot-yellow"></span>
                                    <span class="browser-dot browser-dot-green"></span>
                                </div>
                                <div class="browser-url">
                                    <small class="text-muted"><?= remove_url_protocol_from_url($data->link->full_url) ?></small>
                                </div>
                            </div>
                            <div class="browser-content">
                                <iframe 
                                    id="link_preview_iframe" 
                                    src="<?= $data->link->location_url ?>" 
                                    frameborder="0" 
                                    style="width: 100%; height: 300px; border: none;"
                                    sandbox="allow-same-origin allow-scripts allow-forms"
                                    loading="lazy">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="preview-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="preview-stat">
                                    <i class="fas fa-mouse-pointer text-primary"></i>
                                    <small class="d-block text-muted"><?= l('link.statistics.clicks') ?? 'Clicks' ?></small>
                                    <strong><?= nr($data->link->clicks) ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="preview-stat">
                                    <i class="fas fa-chart-line text-success"></i>
                                    <small class="d-block text-muted"><?= l('link.statistics.impressions') ?? 'Views' ?></small>
                                    <strong><?= nr($data->link->impressions ?? 0) ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="preview-stat">
                                    <i class="fas fa-percentage text-info"></i>
                                    <small class="d-block text-muted"><?= l('link.statistics.ctr') ?? 'CTR' ?></small>
                                    <strong><?= $data->link->clicks > 0 && ($data->link->impressions ?? 0) > 0 ? number_format(($data->link->clicks / ($data->link->impressions ?? 1)) * 100, 1) . '%' : '0%' ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="preview-actions">
                        <a href="<?= $data->link->full_url ?>" target="_blank" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-external-link-alt fa-sm mr-1"></i>
                            <?= l('link.settings.test_link') ?? 'Test Link' ?>
                        </a>
                        <a href="<?= url('link/' . $data->link->link_id . '/statistics') ?>" class="btn btn-sm btn-outline-secondary btn-block mt-2">
                            <i class="fas fa-chart-bar fa-sm mr-1"></i>
                            <?= l('link.statistics.link') ?? 'View Statistics' ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.microsite-block-tabs .nav-minimal {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 4px;
    background-color: #f8f9fa;
}

.microsite-block-tabs .nav-minimal .nav-link {
    border: none;
    border-radius: 6px;
    padding: 5px;
    margin: 0 1px;
    color: #6c757d;
    background: transparent;
    transition: all 0.2s ease;
    text-align: center;
    min-height: 30px;
    min-width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.microsite-block-tabs .nav-minimal .nav-link:hover {
    background-color: #e9ecef;
    color: #495057;
    transform: translateY(-1px);
}

.microsite-block-tabs .nav-minimal .nav-link.active {
    background-color: #007bff;
    color: white;
    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
}

.microsite-block-tabs .nav-minimal .nav-link.active:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

.microsite-block-tabs .nav-minimal .nav-link i {
    font-size: 0.9rem;
}

.link-flow-visualization .flow-step {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.link-flow-visualization .flow-icon {
    margin-right: 1rem;
    min-width: 50px;
    text-align: center;
}

.link-flow-visualization .flow-content {
    flex: 1;
}

.link-flow-visualization .flow-arrow {
    text-align: center;
    margin: 0.5rem 0;
}

.browser-preview {
    width: 100%;
    max-width: 350px;
}

.browser-frame {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.browser-header {
    background: #f5f5f5;
    padding: 8px 12px;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
}

.browser-controls {
    display: flex;
    gap: 4px;
    margin-right: 12px;
}

.browser-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.browser-dot-red { background: #ff5f57; }
.browser-dot-yellow { background: #ffbd2e; }
.browser-dot-green { background: #28ca42; }

.browser-url {
    flex: 1;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 12px;
}

.browser-content {
    background: #fff;
}

.preview-stat {
    padding: 0.5rem 0;
}

.preview-stat i {
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
}

.preview-actions .btn {
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .microsite-block-tabs .nav-minimal .nav-link {
        padding: 6px;
        min-height: 32px;
        min-width: 32px;
    }
    
    .microsite-block-tabs .nav-minimal .nav-link i {
        font-size: 0.8rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh preview functionality
    document.getElementById('refresh_preview').addEventListener('click', function() {
        const iframe = document.getElementById('link_preview_iframe');
        const currentSrc = iframe.src;
        iframe.src = '';
        setTimeout(() => {
            iframe.src = currentSrc;
        }, 100);
        
        // Add loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin fa-sm"></i>';
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-sync-alt fa-sm"></i>';
        }, 1000);
    });

    // Update flow visualization when form changes
    function updateLinkFlow() {
        const locationUrl = document.getElementById('location_url').value;
        const targetingType = document.getElementById('targeting_type').value;
        const password = document.getElementById('qweasdzxc').value;
        const sensitiveContent = document.getElementById('sensitive_content').checked;

        // Update destination URL
        const flowDestinationUrl = document.getElementById('flow_destination_url');
        if (locationUrl) {
            const displayUrl = locationUrl.length > 40 ? locationUrl.substring(0, 40) + '...' : locationUrl;
            flowDestinationUrl.innerHTML = '<code class="small">' + displayUrl + '</code>';
        }

        // Show/hide targeting step
        const targetingStep = document.getElementById('flow_targeting_step');
        const targetingArrow = document.getElementById('flow_targeting_arrow');
        const targetingDescription = document.getElementById('flow_targeting_description');
        
        if (targetingType && targetingType !== 'false') {
            targetingStep.style.display = 'flex';
            targetingArrow.style.display = 'block';
            
            // Update targeting description
            const targetingLabels = {
                'continent_code': '🌍 <?= l('global.continent') ?> <?= l('link.settings.targeting_type') ?>',
                'country_code': '🇨🇺 <?= l('global.country') ?> <?= l('link.settings.targeting_type') ?>',
                'city_name': '🏙️ <?= l('global.city') ?> <?= l('link.settings.targeting_type') ?>',
                'device_type': '📱 <?= l('link.settings.targeting_type_device_type') ?>',
                'os_name': '💻 <?= l('link.settings.targeting_type_os_name') ?>',
                'browser_name': '🌐 <?= l('link.settings.targeting_type_browser_name') ?>',
                'browser_language': '🗣️ <?= l('link.settings.targeting_type_browser_language') ?>',
                'rotation': '🔄 <?= l('link.settings.targeting_type_rotation') ?>'
            };
            targetingDescription.textContent = targetingLabels[targetingType] || '<?= l('link.settings.targeting_type') ?>';
        } else {
            targetingStep.style.display = 'none';
            targetingArrow.style.display = 'none';
        }

        // Show/hide security step
        const securityStep = document.getElementById('flow_security_step');
        const securityArrow = document.getElementById('flow_security_arrow');
        
        if (password || sensitiveContent) {
            securityStep.style.display = 'flex';
            securityArrow.style.display = 'block';
        } else {
            securityStep.style.display = 'none';
            securityArrow.style.display = 'none';
        }
    }

    // Add event listeners for real-time updates
    document.getElementById('location_url').addEventListener('input', updateLinkFlow);
    document.getElementById('targeting_type').addEventListener('change', updateLinkFlow);
    document.getElementById('qweasdzxc').addEventListener('input', updateLinkFlow);
    document.getElementById('sensitive_content').addEventListener('change', updateLinkFlow);

    // Initial update
    updateLinkFlow();

    // AJAX form submission for link updates
    const form = document.querySelector('form[name="update_link"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const notificationContainer = form.querySelector('.notification-container');
            
            // Clear previous notifications
            notificationContainer.innerHTML = '';
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> <?= l('global.please_wait') ?>';
            
            // Make AJAX request
            fetch('<?= url('link-ajax') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                
                // Display notification using toast system
                if (data.status === 'success') {
                    // Show success toast
                    if (typeof showToast === 'function') {
                        showToast('success', data.message);
                    }
                    
                    // Update the flow visualization
                    updateLinkFlow();
                    
                    // Update URL in flow if it changed
                    if (data.details && data.details.url) {
                        const flowShortUrl = document.getElementById('flow_short_url');
                        if (flowShortUrl) {
                            flowShortUrl.textContent = data.details.url;
                        }
                    }
                } else {
                    // Show error in notification container for errors (not toast)
                    notificationContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle mr-1"></i>
                            ${data.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                    
                    // Scroll to notification
                    notificationContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                
                // Show error notification
                notificationContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle mr-1"></i>
                        <?= l('global.error_message.basic') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                
                // Scroll to notification
                notificationContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    }
});
</script>
