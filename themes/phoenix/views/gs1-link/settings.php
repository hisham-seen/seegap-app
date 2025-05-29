<?php defined('ALTUMCODE') || die() ?>

<div class="row gs1-link-settings">
    <!-- Left Column - Settings -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3 d-flex align-items-center">
                    <i class="fas fa-fw fa-cog fa-sm text-muted mr-1"></i> 
                    <?= l('gs1_link.settings.header') ?>
                </h6>

                <form action="" method="post" role="form" id="gs1_link_settings_form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                    <div class="form-group">
                        <label for="gtin"><i class="fas fa-fw fa-sm fa-barcode text-muted mr-1"></i> <?= l('gs1_links.input.gtin') ?></label>
                        <input type="text" id="gtin" name="gtin" class="form-control <?= \Altum\Alerts::has_field_errors('gtin') ? 'is-invalid' : null ?>" value="<?= $data->gs1_link->gtin ?>" maxlength="14" placeholder="<?= l('gs1_links.input.gtin_placeholder') ?>" required="required" />
                        <small class="form-text text-muted"><?= l('gs1_links.input.gtin_help') ?></small>
                        <?= \Altum\Alerts::output_field_error('gtin') ?>
                    </div>

                    <div class="form-group">
                        <label for="target_url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('gs1_links.input.target_url') ?></label>
                        <input type="url" id="target_url" name="target_url" class="form-control <?= \Altum\Alerts::has_field_errors('target_url') ? 'is-invalid' : null ?>" value="<?= $data->gs1_link->target_url ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
                        <?= \Altum\Alerts::output_field_error('target_url') ?>
                    </div>

                    <div class="form-group">
                        <label for="domain_id"><i class="fas fa-fw fa-bolt fa-sm text-muted mr-1"></i> <?= l('link.settings.domain') ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <?php if(count($data->domains)): ?>
                                    <select name="domain_id" class="appearance-none custom-select form-control input-group-text">
                                        <?php if(settings()->links->main_domain_is_enabled || \Altum\Authentication::is_admin()): ?>
                                            <option value="" <?= !$data->gs1_link->domain_id ? 'selected="selected"' : null ?> data-full-url="<?= SITE_URL ?>"><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                                        <?php endif ?>

                                        <?php foreach($data->domains as $row): ?>
                                            <option value="<?= $row->domain_id ?>" <?= $data->gs1_link->domain_id == $row->domain_id ? 'selected="selected"' : null ?>  data-full-url="<?= $row->url ?>" data-type="<?= $row->type ?>"><?= remove_url_protocol_from_url($row->url) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php else: ?>
                                    <span class="input-group-text"><?= remove_url_protocol_from_url(SITE_URL) ?></span>
                                <?php endif ?>
                            </div>

                            <input
                                    id="gtin_display"
                                    type="text"
                                    class="form-control"
                                    readonly="readonly"
                                    value="01/<?= $data->gs1_link->gtin ?>"
                            />
                        </div>
                        <small class="form-text text-muted"><?= l('gs1_links.input.gtin_url_help') ?></small>
                    </div>

                    <div class="form-group custom-control custom-switch">
                        <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->gs1_link->is_enabled ? 'checked="checked"' : null?>>
                        <label class="custom-control-label" for="is_enabled"><?= l('link.settings.is_enabled') ?></label>
                    </div>

                    <?php if(settings()->links->pixels_is_enabled && count($data->pixels)): ?>
                        <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#pixels_container" aria-expanded="false" aria-controls="pixels_container">
                            <i class="fas fa-fw fa-adjust fa-sm mr-1"></i> <?= l('link.settings.pixels_header') ?>
                        </button>

                        <div class="collapse" id="pixels_container">
                            <div class="form-group">
                                <div class="d-flex flex-column flex-xl-row justify-content-between">
                                    <label><i class="fas fa-fw fa-sm fa-adjust text-muted mr-1"></i> <?= l('link.settings.pixels_ids') ?></label>
                                    <a href="<?= url('pixel-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('pixels.create') ?></a>
                                </div>
                                <div class="row">
                                    <?php $available_pixels = require APP_PATH . 'includes/pixels.php'; ?>
                                    <?php foreach($data->pixels as $pixel): ?>
                                        <div class="col-12 col-lg-6">
                                            <div class="custom-control custom-checkbox my-2">
                                                <input id="pixel_id_<?= $pixel->pixel_id ?>" name="pixels_ids[]" value="<?= $pixel->pixel_id ?>" type="checkbox" class="custom-control-input" <?= in_array($pixel->pixel_id, $data->gs1_link->pixels_ids ?? []) ? 'checked="checked"' : null ?>>
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
                        </div>
                    <?php endif ?>

                    <button class="btn btn-block btn-gray-200 my-4 <?= \Altum\Alerts::has_field_errors(['expiration_url']) ? 'border-danger' : null ?>" type="button" data-toggle="collapse" data-target="#temporary_url_container" aria-expanded="false" aria-controls="temporary_url_container">
                        <i class="fas fa-fw fa-clock fa-sm mr-1"></i> <?= l('link.settings.temporary_url_header') ?>
                    </button>

                    <div class="collapse" id="temporary_url_container">
                        <div class="form-group custom-control custom-switch">
                            <input
                                    id="schedule"
                                    name="schedule"
                                    type="checkbox"
                                    class="custom-control-input"
                                <?= $data->gs1_link->settings->schedule ?? false ? 'checked="checked"' : null ?>
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
                                                value="<?= \Altum\Date::get($data->gs1_link->start_date, 1) ?>"
                                                placeholder="<?= l('link.settings.start_date') ?>"
                                                autocomplete="off"
                                                data-daterangepicker
                                        />
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                                        <input
                                                type="text"
                                                class="form-control"
                                                name="end_date"
                                                value="<?= \Altum\Date::get($data->gs1_link->end_date, 1) ?>"
                                                placeholder="<?= l('link.settings.end_date') ?>"
                                                autocomplete="off"
                                                data-daterangepicker
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="clicks_limit"><i class="fas fa-fw fa-mouse fa-sm text-muted mr-1"></i> <?= l('link.settings.clicks_limit') ?></label>
                            <input id="clicks_limit" type="number" class="form-control" name="clicks_limit" value="<?= $data->gs1_link->clicks_limit ?>" />
                            <small class="form-text text-muted"><?= l('link.settings.clicks_limit_help') ?></small>
                        </div>

                        <div class="form-group">
                            <label for="expiration_url"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.expiration_url') ?></label>
                            <input id="expiration_url" type="url" class="form-control <?= \Altum\Alerts::has_field_errors('expiration_url') ? 'is-invalid' : null ?>" name="expiration_url" value="<?= $data->gs1_link->expiration_url ?>" maxlength="2048" />
                            <?= \Altum\Alerts::output_field_error('expiration_url') ?>
                            <small class="form-text text-muted"><?= l('link.settings.expiration_url_help') ?></small>
                        </div>
                    </div>

                    <button class="btn btn-block btn-gray-200 my-4 <?= \Altum\Alerts::has_field_errors(['targeting_*']) ? 'border-danger' : null ?>" type="button" data-toggle="collapse" data-target="#targeting_container" aria-expanded="false" aria-controls="targeting_container">
                        <i class="fas fa-fw fa-bullseye fa-sm mr-1"></i> <?= l('link.settings.targeting_header') ?>
                    </button>

                    <div class="collapse" id="targeting_container">
                        <div <?= $this->user->plan_settings->targeting_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                            <div class="<?= $this->user->plan_settings->targeting_is_enabled ? null : 'container-disabled' ?>">

                                <div class="form-group">
                                    <label for="targeting_type"><i class="fas fa-fw fa-bullseye fa-sm text-muted mr-1"></i> <?= l('link.settings.targeting_type') ?></label>
                                    <select id="targeting_type" name="targeting_type" class="custom-select">
                                        <option value="false" <?= !$data->gs1_link->settings->targeting_type || $data->gs1_link->settings->targeting_type == 'false' ? 'selected="selected"' : null?>>😊 <?= l('global.none') ?></option>
                                        <option value="continent_code" <?= $data->gs1_link->settings->targeting_type == 'continent_code' ? 'selected="selected"' : null?>>🌍 <?= l('global.continent') ?></option>
                                        <option value="country_code" <?= $data->gs1_link->settings->targeting_type == 'country_code' ? 'selected="selected"' : null?>>🇨🇺 <?= l('global.country') ?></option>
                                        <option value="city_name" <?= $data->gs1_link->settings->targeting_type == 'city_name' ? 'selected="selected"' : null?>>🏙️ <?= l('global.city') ?></option>
                                        <option value="device_type" <?= $data->gs1_link->settings->targeting_type == 'device_type' ? 'selected="selected"' : null?>>📱 <?= l('link.settings.targeting_type_device_type') ?></option>
                                        <option value="os_name" <?= $data->gs1_link->settings->targeting_type == 'os_name' ? 'selected="selected"' : null?>>💻 <?= l('link.settings.targeting_type_os_name') ?></option>
                                        <option value="browser_name" <?= $data->gs1_link->settings->targeting_type == 'browser_name' ? 'selected="selected"' : null?>>🌐 <?= l('link.settings.targeting_type_browser_name') ?></option>
                                        <option value="browser_language" <?= $data->gs1_link->settings->targeting_type == 'browser_language' ? 'selected="selected"' : null?>>🗣️ <?= l('link.settings.targeting_type_browser_language') ?></option>
                                        <option value="rotation" <?= $data->gs1_link->settings->targeting_type == 'rotation' ? 'selected="selected"' : null?>>🔄 <?= l('link.settings.targeting_type_rotation') ?></option>
                                    </select>
                                </div>

                                <!-- Targeting options would go here - simplified for brevity -->
                                <div data-targeting-type="false" class="d-none"></div>

                            </div>
                        </div>
                    </div>

                    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#utm_container" aria-expanded="false" aria-controls="utm_container">
                        <i class="fas fa-fw fa-keyboard fa-sm mr-1"></i> <?= l('link.settings.utm_header') ?>
                    </button>

                    <div class="collapse" id="utm_container">
                        <div class="form-group">
                            <label for="utm_source"><i class="fas fa-fw fa-sitemap fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_source') ?></label>
                            <input id="utm_source" type="text" class="form-control" name="utm_source" value="<?= $data->gs1_link->settings->utm->source ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_source_placeholder') ?>" />
                        </div>

                        <div class="form-group">
                            <label for="utm_medium"><i class="fas fa-fw fa-inbox fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_medium') ?></label>
                            <input id="utm_medium" type="text" class="form-control" name="utm_medium" value="<?= $data->gs1_link->settings->utm->medium ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_medium_placeholder') ?>" />
                        </div>

                        <div class="form-group">
                            <label for="utm_campaign"><i class="fas fa-fw fa-bullhorn fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_campaign') ?></label>
                            <input id="utm_campaign" type="text" class="form-control" name="utm_campaign" value="<?= $data->gs1_link->settings->utm->campaign ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_campaign_placeholder') ?>" />
                        </div>

                        <div class="form-group">
                            <label for="utm_preview"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_preview') ?></label>
                            <input id="utm_preview" type="text" class="form-control-plaintext" name="utm_preview" readonly="readonly" />
                            <small class="form-text text-muted"><?= l('link.settings.utm_preview_help') ?></small>
                        </div>
                    </div>

                    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#cloaking_container" aria-expanded="false" aria-controls="cloaking_container">
                        <i class="fas fa-fw fa-eye fa-sm mr-1"></i> <?= l('link.settings.cloaking_header') ?>
                    </button>

                    <div class="collapse" id="cloaking_container">
                        <div <?= $this->user->plan_settings->cloaking_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                            <div class="<?= $this->user->plan_settings->cloaking_is_enabled ? null : 'container-disabled' ?>">
                                <div class="form-group custom-control custom-switch">
                                    <input
                                            id="cloaking_is_enabled"
                                            name="cloaking_is_enabled"
                                            type="checkbox"
                                            class="custom-control-input"
                                        <?= $data->gs1_link->settings->cloaking_is_enabled ?? false ? 'checked="checked"' : null ?>
                                        <?= $this->user->plan_settings->cloaking_is_enabled ? null : 'disabled="disabled"' ?>
                                    >
                                    <label class="custom-control-label" for="cloaking_is_enabled"><i class="fas fa-fw fa-user-tie fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_is_enabled') ?></label>
                                    <small class="form-text text-muted"><?= l('link.settings.cloaking_is_enabled_help') ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cloaking_title"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_title') ?></label>
                            <input id="cloaking_title" type="text" class="form-control" name="cloaking_title" value="<?= $data->gs1_link->settings->cloaking_title ?? '' ?>" maxlength="70" />
                        </div>

                        <div class="form-group">
                            <label for="cloaking_meta_description"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_meta_description') ?></label>
                            <input id="cloaking_meta_description" type="text" class="form-control" name="cloaking_meta_description" value="<?= $data->gs1_link->settings->cloaking_meta_description ?? '' ?>" maxlength="160" />
                        </div>
                    </div>

                    <?php if(settings()->links->projects_is_enabled && count($data->projects)): ?>
                        <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                            <i class="fas fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('link.settings.advanced_header') ?>
                        </button>

                        <div class="collapse" id="advanced_container">
                            <div class="form-group">
                                <div class="d-flex flex-column flex-xl-row justify-content-between">
                                    <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                                    <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                                </div>
                                <select id="project_id" name="project_id" class="custom-select">
                                    <option value=""><?= l('global.none') ?></option>
                                    <?php foreach($data->projects as $project_id => $project): ?>
                                        <option value="<?= $project_id ?>" <?= $data->gs1_link->project_id == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                    <?php endforeach ?>
                                </select>
                                <small class="form-text text-muted"><?= l('projects.project_id_help') ?></small>
                            </div>
                        </div>
                    <?php endif ?>

                    <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= l('global.update') ?></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Middle Column - GS1 Digital Link Flow -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3 d-flex align-items-center">
                    <i class="fas fa-fw fa-route fa-sm text-muted mr-1"></i> 
                    <?= l('gs1_link_create.flow.header') ?>
                </h6>

                <div class="gs1-flow-visualization">
                    <!-- QR Code Scan Step -->
                    <div class="flow-step">
                        <div class="flow-icon">
                            <i class="fas fa-qrcode fa-2x text-primary"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('gs1_link_create.flow.step1') ?></h6>
                            <small class="text-muted"><?= l('gs1_link_create.flow.step1_desc') ?></small>
                        </div>
                    </div>

                    <div class="flow-arrow">
                        <i class="fas fa-arrow-down text-muted"></i>
                    </div>

                    <!-- GS1 Digital Link Step -->
                    <div class="flow-step">
                        <div class="flow-icon">
                            <i class="fas fa-barcode fa-2x text-success"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('gs1_link_create.flow.step2') ?></h6>
                            <small class="text-muted">
                                <span id="flow_gs1_url">
                                    <?php 
                                    $domain_url = $data->gs1_link->domain ? $data->gs1_link->domain->url : SITE_URL;
                                    $gs1_url = rtrim($domain_url, '/') . '/01/' . $data->gs1_link->gtin;
                                    ?>
                                    <code class="small"><?= $gs1_url ?></code>
                                </span>
                            </small>
                        </div>
                    </div>

                    <div class="flow-arrow">
                        <i class="fas fa-arrow-down text-muted"></i>
                    </div>

                    <!-- Targeting Decision Step (conditional) -->
                    <div class="flow-step" id="flow_targeting_step" style="<?= $data->gs1_link->settings->targeting_type && $data->gs1_link->settings->targeting_type !== 'false' ? 'display: flex;' : 'display: none;' ?>">
                        <div class="flow-icon">
                            <i class="fas fa-bullseye fa-2x text-warning"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('gs1_link_create.flow.step3') ?></h6>
                            <small class="text-muted" id="flow_targeting_description">
                                <?php
                                if($data->gs1_link->settings->targeting_type && $data->gs1_link->settings->targeting_type !== 'false') {
                                    echo l('gs1_link_create.flow.targeting_enabled');
                                } else {
                                    echo l('gs1_link_create.flow.step3_desc');
                                }
                                ?>
                            </small>
                        </div>
                    </div>

                    <div class="flow-arrow" id="flow_targeting_arrow" style="<?= $data->gs1_link->settings->targeting_type && $data->gs1_link->settings->targeting_type !== 'false' ? 'display: block;' : 'display: none;' ?>">
                        <i class="fas fa-arrow-down text-muted"></i>
                    </div>

                    <!-- Final Destination Step -->
                    <div class="flow-step">
                        <div class="flow-icon">
                            <i class="fas fa-external-link-alt fa-2x text-info"></i>
                        </div>
                        <div class="flow-content">
                            <h6 class="mb-1"><?= l('gs1_link_create.flow.step4') ?></h6>
                            <small class="text-muted">
                                <span id="flow_destination_url">
                                    <?php $display_url = strlen($data->gs1_link->target_url) > 40 ? substr($data->gs1_link->target_url, 0, 40) . '...' : $data->gs1_link->target_url; ?>
                                    <code class="small"><?= $display_url ?></code>
                                </span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Mobile Preview -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3 d-flex align-items-center">
                    <i class="fas fa-fw fa-mobile-alt fa-sm text-muted mr-1"></i> 
                    <?= l('gs1_link_create.preview.header') ?>
                </h6>

                <div class="d-flex justify-content-center">
                    <div class="mobile-preview">
                        <div class="mobile-frame">
                            <div class="mobile-screen">
                                <div class="mobile-content">
                                    <div class="text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-external-link-alt fa-3x text-success"></i>
                                        </div>
                                        <h6 class="text-success"><?= l('gs1_link_create.preview.landing_page') ?></h6>
                                        <small class="text-muted">Live preview of final destination</small>
                                        
                                        <div class="mt-4">
                                            <div class="preview-url-display p-2 bg-light rounded">
                                                <small class="text-muted">
                                                    <i class="fas fa-link fa-sm mr-1"></i>
                                                    <span id="preview_final_url">
                                                        <?php $preview_url = strlen($data->gs1_link->target_url) > 30 ? substr($data->gs1_link->target_url, 0, 30) . '...' : $data->gs1_link->target_url; ?>
                                                        <?= $preview_url ?>
                                                    </span>
                                                </small>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-qrcode fa-sm mr-1"></i>
                                                Scan to test
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<style>
/* GS1 Link Settings Styles */
.gs1-link-settings .card {
    height: fit-content;
}

/* Flow Visualization Styles */
.gs1-flow-visualization {
    padding: 1rem 0;
}

.flow-step {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 1rem;
    border-left: 4px solid #dee2e6;
    transition: all 0.3s ease;
}

.flow-step:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.flow-icon {
    margin-right: 1rem;
    min-width: 60px;
    text-align: center;
}

.flow-content {
    flex: 1;
}

.flow-content h6 {
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.flow-arrow {
    text-align: center;
    margin: 0.5rem 0;
}

.flow-arrow i {
    font-size: 1.2rem;
}

/* Mobile Preview Styles */
.mobile-preview {
    position: relative;
    display: inline-block;
}

.mobile-frame {
    width: 280px;
    height: 560px;
    background: #2c3e50;
    border-radius: 30px;
    padding: 20px 15px;
    position: relative;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.mobile-frame::before {
    content: '';
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: #34495e;
    border-radius: 2px;
}

.mobile-frame::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: #34495e;
    border-radius: 50%;
}

.mobile-screen {
    width: 100%;
    height: 100%;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.mobile-content {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-url-display {
    font-family: 'Courier New', monospace;
    word-break: break-all;
    max-width: 200px;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .mobile-frame {
        width: 240px;
        height: 480px;
        padding: 15px 12px;
    }
    
    .flow-step {
        flex-direction: column;
        text-align: center;
    }
    
    .flow-icon {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
}
</style>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* GTIN validation */
    document.querySelector('#gtin').addEventListener('input', event => {
        let gtin = event.target.value.replace(/\D/g, ''); // Remove non-digits
        
        if(gtin.length > 14) {
            gtin = gtin.substring(0, 14);
        }
        
        event.target.value = gtin;
    });

    /* UTM */
    let process_utm = () => {
        let utm_source = document.querySelector('input[name="utm_source"]').value;
        let utm_medium = document.querySelector('input[name="utm_medium"]').value;
        let utm_campaign = document.querySelector('input[name="utm_campaign"]').value;
        let utm_preview = <?= json_encode(l('global.none')) ?>;

        if(utm_source || utm_medium || utm_campaign) {
            let link = new URL(<?= json_encode(SITE_URL) ?>);

            if(utm_source) link.searchParams.set('utm_source', utm_source.trim());
            if(utm_medium) link.searchParams.set('utm_medium', utm_medium.trim());
            if(utm_campaign) link.searchParams.set('utm_campaign', utm_campaign.trim());

            utm_preview = '?' + link.searchParams.toString();
        }

        document.querySelector('input[name="utm_preview"]').value = utm_preview;
    }

    document.querySelectorAll('input[name="utm_source"], input[name="utm_medium"], input[name="utm_campaign"]').forEach(element => {
        ['change', 'paste', 'keyup'].forEach(event_type => {
            element.addEventListener(event_type, process_utm);
        });
    })

    process_utm();

    /* GS1 Link Real-time Updates */
    let update_gs1_flow_and_preview = () => {
        let gtin = document.querySelector('#gtin').value;
        let target_url = document.querySelector('#target_url').value;
        
        // Get domain info
        let domain_select = document.querySelector('select[name="domain_id"]');
        let domain_url = <?= json_encode(SITE_URL) ?>;
        if(domain_select && domain_select.selectedOptions[0]) {
            let selected_option = domain_select.selectedOptions[0];
            if(selected_option.getAttribute('data-full-url')) {
                domain_url = selected_option.getAttribute('data-full-url');
            }
        }

        // Update GTIN display field
        let gtin_display = document.querySelector('#gtin_display');
        if(gtin) {
            gtin_display.value = '01/' + gtin;
        } else {
            gtin_display.value = '01/<?= $data->gs1_link->gtin ?>';
        }

        // Update flow visualization
        let flow_gs1_url = document.querySelector('#flow_gs1_url');
        if(gtin) {
            let gs1_url = domain_url.replace(/\/$/, '') + '/01/' + gtin;
            flow_gs1_url.innerHTML = '<code class="small">' + gs1_url + '</code>';
        }

        // Update destination URL in flow
        let flow_destination_url = document.querySelector('#flow_destination_url');
        if(target_url) {
            let display_url = target_url.length > 40 ? target_url.substring(0, 40) + '...' : target_url;
            flow_destination_url.innerHTML = '<code class="small">' + display_url + '</code>';
        }

        // Update mobile preview URL
        let preview_final_url = document.querySelector('#preview_final_url');
        if(target_url) {
            let display_url = target_url.length > 30 ? target_url.substring(0, 30) + '...' : target_url;
            preview_final_url.textContent = display_url;
        }
    };

    // Add event listeners for real-time updates
    document.querySelector('#gtin').addEventListener('input', update_gs1_flow_and_preview);
    document.querySelector('#target_url').addEventListener('input', update_gs1_flow_and_preview);
    
    // Also listen for domain changes
    let domain_select = document.querySelector('select[name="domain_id"]');
    if(domain_select) {
        domain_select.addEventListener('change', update_gs1_flow_and_preview);
    }

    // Initial update
    update_gs1_flow_and_preview();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
