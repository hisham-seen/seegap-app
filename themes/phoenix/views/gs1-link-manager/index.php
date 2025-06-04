<?php defined('SEEGAP') || die() ?>

<div class="container-fluid">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= url('gs1-links') ?>"><?= l('gs1_links.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page">
                    <?php if($data->mode === 'create'): ?>
                        <?= l('gs1_link_create.breadcrumb') ?>
                    <?php else: ?>
                        <?= l('gs1_link.breadcrumb') ?>
                    <?php endif ?>
                </li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h4 text-truncate mb-4">
        <i class="fas fa-fw fa-xs fa-barcode mr-1"></i> 
        <?php if($data->mode === 'create'): ?>
            <?= l('gs1_link_create.header') ?>
        <?php else: ?>
            <?= sprintf(l('gs1_link.header'), $data->gs1_link->gtin) ?>
        <?php endif ?>
    </h1>

    <div class="row gs1-link-<?= $data->mode ?>">
        <!-- Left Column - Settings -->
        <div class="col-12 col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3 d-flex align-items-center">
                        <i class="fas fa-fw fa-cog fa-sm text-muted mr-1"></i> 
                        <?php if($data->mode === 'create'): ?>
                            <?= l('gs1_link_create.settings_header') ?>
                        <?php else: ?>
                            <?= l('gs1_link.settings.header') ?>
                        <?php endif ?>
                    </h6>

                    <form action="" method="post" role="form" id="gs1_link_form">
                        <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />

                        <div class="form-group">
                            <label for="gtin"><i class="fas fa-fw fa-sm fa-barcode text-muted mr-1"></i> <?= l('gs1_links.input.gtin') ?></label>
                            <input type="text" id="gtin" name="gtin" class="form-control <?= \SeeGap\Alerts::has_field_errors('gtin') ? 'is-invalid' : null ?>" value="<?= $data->values['gtin'] ?>" maxlength="14" placeholder="<?= l('gs1_links.input.gtin_placeholder') ?>" required="required" />
                            <small class="form-text text-muted"><?= l('gs1_links.input.gtin_help') ?></small>
                            <?= \SeeGap\Alerts::output_field_error('gtin') ?>
                        </div>

                        <div class="form-group">
                            <label for="target_url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('gs1_links.input.target_url') ?></label>
                            <input type="url" id="target_url" name="target_url" class="form-control <?= \SeeGap\Alerts::has_field_errors('target_url') ? 'is-invalid' : null ?>" value="<?= $data->values['target_url'] ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
                            <?= \SeeGap\Alerts::output_field_error('target_url') ?>
                        </div>

                        <div class="form-group">
                            <label for="domain_id"><i class="fas fa-fw fa-bolt fa-sm text-muted mr-1"></i> <?= l('link.settings.domain') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <?php if(count($data->domains)): ?>
                                        <select name="domain_id" class="appearance-none custom-select form-control input-group-text">
                                            <?php if(settings()->links->main_domain_is_enabled || \SeeGap\Authentication::is_admin()): ?>
                                                <option value="" <?= $data->values['domain_id'] ? 'selected="selected"' : null ?> data-full-url="<?= SITE_URL ?>"><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                                            <?php endif ?>

                                            <?php foreach($data->domains as $row): ?>
                                                <option value="<?= $row->domain_id ?>" <?= $data->values['domain_id'] && $data->values['domain_id'] == $row->domain_id ? 'selected="selected"' : null ?>  data-full-url="<?= $row->url ?>" data-type="<?= $row->type ?>"><?= remove_url_protocol_from_url($row->url) ?></option>
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
                                        <?php if($data->mode === 'create'): ?>
                                            placeholder="01/<?= l('gs1_links.input.gtin_placeholder') ?>"
                                        <?php else: ?>
                                            value="01/<?= $data->gs1_link->gtin ?>"
                                        <?php endif ?>
                                />
                            </div>
                            <small class="form-text text-muted"><?= l('gs1_links.input.gtin_url_help') ?></small>
                        </div>

                        <div class="form-group custom-control custom-switch">
                            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->values['is_enabled'] ? 'checked="checked"' : null?>>
                            <label class="custom-control-label" for="is_enabled"><?= l('link.settings.is_enabled') ?></label>
                        </div>

                        <?php if(settings()->links->pixels_is_enabled && settings()->gs1_links->pixels_is_enabled): ?>
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
                                                    <input id="pixel_id_<?= $pixel->pixel_id ?>" name="pixels_ids[]" value="<?= $pixel->pixel_id ?>" type="checkbox" class="custom-control-input" <?= in_array($pixel->pixel_id, $data->values['pixels_ids'] ?? []) ? 'checked="checked"' : null ?>>
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

                        <button class="btn btn-block btn-gray-200 my-4 <?= \SeeGap\Alerts::has_field_errors(['expiration_url']) ? 'border-danger' : null ?>" type="button" data-toggle="collapse" data-target="#temporary_url_container" aria-expanded="false" aria-controls="temporary_url_container">
                            <i class="fas fa-fw fa-clock fa-sm mr-1"></i> <?= l('link.settings.temporary_url_header') ?>
                        </button>

                        <div class="collapse" id="temporary_url_container">
                            <div class="form-group custom-control custom-switch">
                                <input
                                        id="schedule"
                                        name="schedule"
                                        type="checkbox"
                                        class="custom-control-input"
                                    <?= $data->values['schedule'] && !empty($data->values['start_date']) && !empty($data->values['end_date']) ? 'checked="checked"' : null ?>
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
                                                    value="<?= \SeeGap\Date::get($data->values['start_date'], 1) ?>"
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
                                                    value="<?= \SeeGap\Date::get($data->values['end_date'], 1) ?>"
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
                                <input id="clicks_limit" type="number" class="form-control" name="clicks_limit" value="<?= $data->values['clicks_limit'] ?>" />
                                <small class="form-text text-muted"><?= l('link.settings.clicks_limit_help') ?></small>
                            </div>

                            <div class="form-group">
                                <label for="expiration_url"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.expiration_url') ?></label>
                                <input id="expiration_url" type="url" class="form-control <?= \SeeGap\Alerts::has_field_errors('expiration_url') ? 'is-invalid' : null ?>" name="expiration_url" value="<?= $data->values['expiration_url'] ?>" maxlength="2048" />
                                <?= \SeeGap\Alerts::output_field_error('expiration_url') ?>
                                <small class="form-text text-muted"><?= l('link.settings.expiration_url_help') ?></small>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-200 my-4 <?= \SeeGap\Alerts::has_field_errors(['targeting_*']) ? 'border-danger' : null ?>" type="button" data-toggle="collapse" data-target="#targeting_container" aria-expanded="false" aria-controls="targeting_container">
                            <i class="fas fa-fw fa-bullseye fa-sm mr-1"></i> <?= l('link.settings.targeting_header') ?>
                        </button>

                        <div class="collapse" id="targeting_container">
                            <div <?= $this->user->plan_settings->targeting_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                <div class="<?= $this->user->plan_settings->targeting_is_enabled ? null : 'container-disabled' ?>">

                                    <div class="form-group">
                                        <label for="targeting_type"><i class="fas fa-fw fa-bullseye fa-sm text-muted mr-1"></i> <?= l('link.settings.targeting_type') ?></label>
                                        <select id="targeting_type" name="targeting_type" class="custom-select">
                                            <option value="false" <?= $data->values['targeting_type'] == 'false' ? 'selected="selected"' : null?>>😊 <?= l('global.none') ?></option>
                                            <option value="continent_code" <?= $data->values['targeting_type'] == 'continent_code' ? 'selected="selected"' : null?>>🌍 <?= l('global.continent') ?></option>
                                            <option value="country_code" <?= $data->values['targeting_type'] == 'country_code' ? 'selected="selected"' : null?>>🇨🇺 <?= l('global.country') ?></option>
                                            <option value="city_name" <?= $data->values['targeting_type'] == 'city_name' ? 'selected="selected"' : null?>>🏙️ <?= l('global.city') ?></option>
                                            <option value="device_type" <?= $data->values['targeting_type'] == 'device_type' ? 'selected="selected"' : null?>>📱 <?= l('link.settings.targeting_type_device_type') ?></option>
                                            <option value="os_name" <?= $data->values['targeting_type'] == 'os_name' ? 'selected="selected"' : null?>>💻 <?= l('link.settings.targeting_type_os_name') ?></option>
                                            <option value="browser_name" <?= $data->values['targeting_type'] == 'browser_name' ? 'selected="selected"' : null?>>🌐 <?= l('link.settings.targeting_type_browser_name') ?></option>
                                            <option value="browser_language" <?= $data->values['targeting_type'] == 'browser_language' ? 'selected="selected"' : null?>>🗣️ <?= l('link.settings.targeting_type_browser_language') ?></option>
                                            <option value="rotation" <?= $data->values['targeting_type'] == 'rotation' ? 'selected="selected"' : null?>>🔄 <?= l('link.settings.targeting_type_rotation') ?></option>
                                        </select>
                                    </div>

                                    <div data-targeting-type="false" class="d-none"></div>

                                    <div data-targeting-type="continent_code" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_continent_code_help') ?></p>

                                        <div data-targeting-list="continent_code">
                                            <?php if(isset($data->values['targeting_continent_code']) && !empty($data->values['targeting_continent_code'])): ?>
                                                <?php foreach($data->values['targeting_continent_code'] as $key => $targeting): ?>
                                                <?php $targeting = (object) $targeting ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <select name="targeting_continent_code_key[<?= $key ?>]" class="custom-select">
                                                                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                                                                    <option value="<?= $continent_code ?>" <?= $targeting->key == $continent_code ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_continent_code_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_continent_code_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_continent_code_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="continent_code" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                    <div data-targeting-type="country_code" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_country_code_help') ?></p>

                                        <div data-targeting-list="country_code">
                                            <?php if(isset($data->values['targeting_country_code']) && !empty($data->values['targeting_country_code'])): ?>
                                                <?php foreach($data->values['targeting_country_code'] as $key => $targeting): ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <select name="targeting_country_code_key[<?= $key ?>]" class="custom-select">
                                                                <?php foreach(get_countries_array() as $country => $country_name): ?>
                                                                    <option value="<?= $country ?>" <?= $targeting->key == $country ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_country_code_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_country_code_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_country_code_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="country_code" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                    <div data-targeting-type="city_name" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_city_name_help') ?></p>

                                        <div data-targeting-list="city_name">
                                            <?php if(isset($data->values['targeting_city_name']) && !empty($data->values['targeting_city_name'])): ?>
                                                <?php foreach($data->values['targeting_city_name'] as $key => $targeting): ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <input type="text" name="targeting_city_name_key[<?= $key ?>]" class="form-control" value="<?= $targeting->key ?>" placeholder="<?= l('link.settings.targeting_type_city_name_placeholder') ?>" maxlength="128" />
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_city_name_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_city_name_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_city_name_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="city_name" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                    <div data-targeting-type="device_type" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_device_type_help') ?></p>

                                        <div data-targeting-list="device_type">
                                            <?php if(isset($data->values['targeting_device_type']) && !empty($data->values['targeting_device_type'])): ?>
                                                <?php foreach($data->values['targeting_device_type'] as $key => $targeting): ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <select name="targeting_device_type_key[<?= $key ?>]" class="custom-select">
                                                                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                                                                    <option value="<?= $device_type ?>" <?= $targeting->key == $device_type ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_device_type_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_device_type_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_device_type_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="device_type" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                    <div data-targeting-type="os_name" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_os_name_help') ?></p>

                                        <div data-targeting-list="os_name">
                                            <?php if(isset($data->values['targeting_os_name']) && !empty($data->values['targeting_os_name'])): ?>
                                                <?php foreach($data->values['targeting_os_name'] as $key => $targeting): ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <select name="targeting_os_name_key[<?= $key ?>]" class="custom-select">
                                                                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                                                                    <option value="<?= $os_name ?>" <?= $targeting->key == $os_name ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_os_name_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_os_name_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_os_name_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="os_name" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                    <div data-targeting-type="browser_name" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_browser_name_help') ?></p>

                                        <div data-targeting-list="browser_name">
                                            <?php if(isset($data->values['targeting_browser_name']) && !empty($data->values['targeting_browser_name'])): ?>
                                                <?php foreach($data->values['targeting_browser_name'] as $key => $targeting): ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <select name="targeting_browser_name_key[<?= $key ?>]" class="custom-select">
                                                                <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                                                                    <option value="<?= $browser_name ?>" <?= $targeting->key == $browser_name ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_browser_name_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_browser_name_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_browser_name_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="browser_name" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                    <div data-targeting-type="browser_language" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_browser_language_help') ?></p>

                                        <div data-targeting-list="browser_language">
                                            <?php if(isset($data->values['targeting_browser_language']) && !empty($data->values['targeting_browser_language'])): ?>
                                                <?php foreach($data->values['targeting_browser_language'] as $key => $targeting): ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <select name="targeting_browser_language_key[<?= $key ?>]" class="custom-select">
                                                                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                                                                    <option value="<?= $locale ?>" <?= $targeting->key == $locale ? 'selected="selected"' : null ?>><?= $language ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_browser_language_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_browser_language_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_browser_language_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="browser_language" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                    <div data-targeting-type="rotation" class="d-none">
                                        <p class="small text-muted"><?= l('link.settings.targeting_type_rotation_help') ?></p>

                                        <div data-targeting-list="rotation">
                                            <?php if(isset($data->values['targeting_rotation']) && !empty($data->values['targeting_rotation'])): ?>
                                                <?php foreach($data->values['targeting_rotation'] as $key => $targeting): ?>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-5">
                                                            <input type="number" min="0" max="100" name="targeting_rotation_key[<?= $key ?>]" class="form-control" value="<?= $targeting->key ?? 1 ?>" placeholder="<?= l('link.settings.targeting_type_percentage') ?>" required="required" />
                                                        </div>

                                                        <div class="form-group col-lg-5">
                                                            <input type="url" name="targeting_rotation_value[<?= $key ?>]" class="form-control <?= \SeeGap\Alerts::has_field_errors('targeting_rotation_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                                            <?= \SeeGap\Alerts::output_field_error('targeting_rotation_value[' . $key . ']') ?>
                                                        </div>

                                                        <div class="form-group col-lg-2 text-center">
                                                            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>

                                        <div class="mb-3">
                                            <button data-targeting-add="rotation" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#utm_container" aria-expanded="false" aria-controls="utm_container">
                            <i class="fas fa-fw fa-keyboard fa-sm mr-1"></i> <?= l('link.settings.utm_header') ?>
                        </button>

                        <div class="collapse" id="utm_container">
                            <div <?= $this->user->plan_settings->utm ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                <div class="<?= $this->user->plan_settings->utm ? null : 'container-disabled' ?>">
                                    <div class="form-group">
                                        <label for="utm_source"><i class="fas fa-fw fa-sitemap fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_source') ?></label>
                                        <input id="utm_source" type="text" class="form-control" name="utm_source" value="<?= $data->values['utm_source'] ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_source_placeholder') ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="utm_medium"><i class="fas fa-fw fa-inbox fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_medium') ?></label>
                                        <input id="utm_medium" type="text" class="form-control" name="utm_medium" value="<?= $data->values['utm_medium'] ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_medium_placeholder') ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="utm_campaign"><i class="fas fa-fw fa-bullhorn fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_campaign') ?></label>
                                        <input id="utm_campaign" type="text" class="form-control" name="utm_campaign" value="<?= $data->values['utm_campaign'] ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_campaign_placeholder') ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="utm_preview"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_preview') ?></label>
                                        <input id="utm_preview" type="text" class="form-control-plaintext" name="utm_preview" readonly="readonly" />
                                        <small class="form-text text-muted"><?= l('link.settings.utm_preview_help') ?></small>
                                    </div>
                                </div>
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
                                            <?= $data->values['cloaking_is_enabled'] ? 'checked="checked"' : null ?>
                                            <?= $this->user->plan_settings->cloaking_is_enabled ? null : 'disabled="disabled"' ?>
                                        >
                                        <label class="custom-control-label" for="cloaking_is_enabled"><i class="fas fa-fw fa-user-tie fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_is_enabled') ?></label>
                                        <small class="form-text text-muted"><?= l('link.settings.cloaking_is_enabled_help') ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cloaking_title"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_title') ?></label>
                                <input id="cloaking_title" type="text" class="form-control" name="cloaking_title" value="<?= $data->values['cloaking_title'] ?>" maxlength="70" />
                            </div>

                            <div class="form-group">
                                <label for="cloaking_meta_description"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_meta_description') ?></label>
                                <input id="cloaking_meta_description" type="text" class="form-control" name="cloaking_meta_description" value="<?= $data->values['cloaking_meta_description'] ?>" maxlength="160" />
                            </div>

                            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->favicon_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->favicon_size_limit) ?>">
                                <label><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_favicon') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'favicons', 'file_key' => 'cloaking_favicon', 'already_existing_image' => null, 'input_data' => 'data-crop data-aspect-ratio="1"']) ?>
                                <?= \SeeGap\Alerts::output_field_error('cloaking_favicon') ?>
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('favicons')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->favicon_size_limit) ?></small>
                            </div>

                            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->seo_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->seo_image_size_limit) ?>">
                                <label for="cloaking_opengraph"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_opengraph') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'microsite_seo_image', 'file_key' => 'cloaking_opengraph', 'already_existing_image' => null, 'input_data' => 'data-crop data-aspect-ratio="1.91"']) ?>
                                <?= \SeeGap\Alerts::output_field_error('cloaking_opengraph') ?>
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('microsite_seo_image')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->seo_image_size_limit) ?></small>
                            </div>

                            <div <?= 1 ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                <div class="form-group <?= 1 ? null : 'container-disabled' ?>" data-character-counter="textarea">
                                    <label for="cloaking_custom_js" class="d-flex justify-content-between align-items-center">
                                        <span><i class="fab fa-fw fa-sm fa-js-square text-muted mr-1"></i> <?= l('global.custom_js') ?></span>
                                        <small class="text-muted" data-character-counter-wrapper></small>
                                    </label>
                                    <textarea id="cloaking_custom_js" class="form-control" name="cloaking_custom_js" maxlength="10000" placeholder="<?= l('global.custom_js_placeholder') ?>"><?= $data->values['cloaking_custom_js'] ?></textarea>
                                    <small class="form-text text-muted"><?= l('global.custom_js_help') ?></small>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                            <i class="fas fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('link.settings.advanced_header') ?>
                        </button>

                        <div class="collapse" id="advanced_container">
                            <?php if(isset(settings()->links->projects_is_enabled) && settings()->links->projects_is_enabled && settings()->gs1_links->projects_is_enabled): ?>
                                <div class="form-group">
                                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                                        <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                                        <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                                    </div>
                                    <select id="project_id" name="project_id" class="custom-select">
                                        <option value=""><?= l('global.none') ?></option>
                                        <?php foreach($data->projects as $project_id => $project): ?>
                                            <option value="<?= $project_id ?>" <?= $data->values['project_id'] == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <small class="form-text text-muted"><?= l('projects.project_id_help') ?></small>
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
                                                    <option value="<?= $row->splash_page_id ?>" <?= $data->values['splash_page_id'] == $row->splash_page_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="form-group custom-control custom-switch">
                                <input
                                        id="forward_query_parameters_is_enabled"
                                        name="forward_query_parameters_is_enabled"
                                        type="checkbox"
                                        class="custom-control-input"
                                    <?= $data->values['forward_query_parameters_is_enabled'] ? 'checked="checked"' : null ?>
                                >
                                <label class="custom-control-label" for="forward_query_parameters_is_enabled"><i class="fas fa-fw fa-forward fa-sm text-muted mr-1"></i> <?= l('link.settings.forward_query_parameters_is_enabled') ?></label>
                                <small class="form-text text-muted"><?= l('link.settings.forward_query_parameters_is_enabled_help') ?></small>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="btn btn-block btn-primary mt-4">
                            <?php if($data->mode === 'create'): ?>
                                <?= l('global.create') ?>
                            <?php else: ?>
                                <?= l('global.update') ?>
                            <?php endif ?>
                        </button>
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
                        <?= l('gs1_link_create.flow_header') ?>
                    </h6>

                    <div class="gs1-flow-visualization">
                        <!-- QR Code Scan Step -->
                        <div class="flow-step">
                            <div class="flow-icon">
                                <i class="fas fa-qrcode fa-2x text-primary"></i>
                            </div>
                            <div class="flow-content">
                                <h6 class="mb-1"><?= l('gs1_link_create.flow_step_scan') ?></h6>
                                <small class="text-muted"><?= l('gs1_link_create.flow_step_scan_description') ?></small>
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
                                <h6 class="mb-1"><?= l('gs1_link_create.flow_step_gs1') ?></h6>
                                <small class="text-muted">
                                    <span id="flow_gs1_url">
                                        <?php if($data->mode === 'edit'): ?>
                                            <?php 
                                            $domain_url = SITE_URL;
                                            if($data->gs1_link->domain_id && isset($data->domains[$data->gs1_link->domain_id])) {
                                                $domain_url = $data->domains[$data->gs1_link->domain_id]->url;
                                            }
                                            $gs1_url = rtrim($domain_url, '/') . '/01/' . $data->gs1_link->gtin;
                                            ?>
                                            <code class="small"><?= $gs1_url ?></code>
                                        <?php else: ?>
                                            <?= l('gs1_link_create.flow_step_gs1_description') ?>
                                        <?php endif ?>
                                    </span>
                                </small>
                            </div>
                        </div>

                        <div class="flow-arrow">
                            <i class="fas fa-arrow-down text-muted"></i>
                        </div>

                        <!-- Targeting Decision Step (conditional) -->
                        <div class="flow-step" id="flow_targeting_step" style="<?= ($data->mode === 'edit' && $data->gs1_link->settings->targeting_type && $data->gs1_link->settings->targeting_type !== 'false') ? 'display: flex;' : 'display: none;' ?>">
                            <div class="flow-icon">
                                <i class="fas fa-bullseye fa-2x text-warning"></i>
                            </div>
                            <div class="flow-content">
                                <h6 class="mb-1"><?= l('gs1_link_create.flow_step_targeting') ?></h6>
                                <small class="text-muted" id="flow_targeting_description"><?= l('gs1_link_create.flow_step_targeting_description') ?></small>
                            </div>
                        </div>

                        <div class="flow-arrow" id="flow_targeting_arrow" style="<?= ($data->mode === 'edit' && $data->gs1_link->settings->targeting_type && $data->gs1_link->settings->targeting_type !== 'false') ? 'display: block;' : 'display: none;' ?>">
                            <i class="fas fa-arrow-down text-muted"></i>
                        </div>

                        <!-- Final Destination Step -->
                        <div class="flow-step">
                            <div class="flow-icon">
                                <i class="fas fa-external-link-alt fa-2x text-info"></i>
                            </div>
                            <div class="flow-content">
                                <h6 class="mb-1"><?= l('gs1_link_create.flow_step_destination') ?></h6>
                                <small class="text-muted">
                                    <span id="flow_destination_url">
                                        <?php if($data->mode === 'edit'): ?>
                                            <?php $display_url = strlen($data->gs1_link->target_url) > 40 ? substr($data->gs1_link->target_url, 0, 40) . '...' : $data->gs1_link->target_url; ?>
                                            <code class="small"><?= $display_url ?></code>
                                        <?php else: ?>
                                            <?= l('gs1_link_create.flow_step_destination_description') ?>
                                        <?php endif ?>
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
                        <?= l('gs1_link_create.preview_header') ?>
                    </h6>

                    <div class="d-flex justify-content-center">
                        <div class="mobile-preview">
                            <div class="mobile-frame">
                                <div class="mobile-screen">
                                    <div class="mobile-content">
                                        <div class="text-center p-4">
                                            <?php if($data->mode === 'create'): ?>
                                                <div class="mb-3">
                                                    <i class="fas fa-mobile-alt fa-3x text-muted"></i>
                                                </div>
                                                <h6 class="text-muted"><?= l('gs1_link_create.preview_disabled') ?></h6>
                                                <small class="text-muted"><?= l('gs1_link_create.preview_disabled_description') ?></small>
                                            <?php else: ?>
                                                <div class="mb-3">
                                                    <i class="fas fa-external-link-alt fa-3x text-success"></i>
                                                </div>
                                                <h6 class="text-success"><?= l('gs1_link_create.preview_landing_page') ?></h6>
                                                <small class="text-muted">Live preview of final destination</small>
                                            <?php endif ?>
                                            
                                            <div class="mt-4">
                                                <div class="preview-url-display p-2 bg-light rounded">
                                                    <small class="text-muted">
                                                        <i class="fas fa-link fa-sm mr-1"></i>
                                                        <span id="preview_final_url">
                                                            <?php if($data->mode === 'edit'): ?>
                                                                <?php $preview_url = strlen($data->gs1_link->target_url) > 30 ? substr($data->gs1_link->target_url, 0, 30) . '...' : $data->gs1_link->target_url; ?>
                                                                <?= $preview_url ?>
                                                            <?php else: ?>
                                                                <?= l('gs1_link_create.preview_url_placeholder') ?>
                                                            <?php endif ?>
                                                        </span>
                                                    </small>
                                                </div>
                                            </div>

                                            <?php if($data->mode === 'edit'): ?>
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-qrcode fa-sm mr-1"></i>
                                                        Scan to test
                                                    </small>
                                                </div>
                                            <?php endif ?>
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

<template id="template_targeting_continent_code">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_continent_code_key[]" class="custom-select">
                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                    <option value="<?= $continent_code ?>"><?= $continent_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_continent_code_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_country_code">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_country_code_key[]" class="custom-select">
                <?php foreach(get_countries_array() as $country => $country_name): ?>
                    <option value="<?= $country ?>"><?= $country_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_country_code_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_city_name">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <input type="text" name="targeting_city_name_key[]" class="form-control" value="" placeholder="<?= l('link.settings.targeting_type_city_name_placeholder') ?>" maxlength="128" />
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_city_name_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_device_type">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_device_type_key[]" class="custom-select">
                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                    <option value="<?= $device_type ?>"><?= l('global.device.' . $device_type) ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_device_type_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_os_name">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_os_name_key[]" class="custom-select">
                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                    <option value="<?= $os_name ?>"><?= $os_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_os_name_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_browser_name">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_browser_name_key[]" class="custom-select">
                <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                    <option value="<?= $browser_name ?>"><?= $browser_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_browser_name_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_browser_language">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_browser_language_key[]" class="custom-select">
                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                    <option value="<?= $locale ?>"><?= $language ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_browser_language_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_rotation">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <input type="number" min="0" max="100" name="targeting_rotation_key[]" class="form-control" value="1" placeholder="<?= l('link.settings.targeting_type_percentage') ?>" required="required" />
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_rotation_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \SeeGap\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

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

    /* Targeting */
    let targeting_type_handler = () => {
        let targeting_type = document.querySelector('#targeting_type').value;

        document.querySelectorAll('[data-targeting-type]').forEach(element => {
            let element_targeting_type = element.getAttribute('data-targeting-type');

            if(element_targeting_type == targeting_type) {
                document.querySelector(`[data-targeting-type="${element_targeting_type}"]`).classList.remove('d-none');
            } else {
                document.querySelector(`[data-targeting-type="${element_targeting_type}"]`).classList.add('d-none');
            }
        })
    }

    targeting_type_handler();
    document.querySelector('#targeting_type').addEventListener('change', targeting_type_handler);

    /* add new targeting */
    let targeting_add = event => {
        let type = event.currentTarget.getAttribute('data-targeting-add');

        let clone = document.querySelector(`#template_targeting_${type}`).content.cloneNode(true);

        let targeting_count = document.querySelectorAll(`[data-targeting-list="${type}"] .form-row`).length;

        clone.querySelector(`[name="targeting_${type}_key[]"`).setAttribute('name', `targeting_${type}_key[${targeting_count}]`);
        clone.querySelector(`[name="targeting_${type}_value[]"`).setAttribute('name', `targeting_${type}_value[${targeting_count}]`);

        document.querySelector(`[data-targeting-list="${type}"]`).appendChild(clone);

        targeting_remove_initiator();
    };

    document.querySelectorAll('[data-targeting-add]').forEach(element => {
        element.addEventListener('click', targeting_add);
    })

    /* remove targeting */
    let targeting_remove = event => {
        event.currentTarget.closest('.form-row').remove();
    };

    let targeting_remove_initiator = () => {
        document.querySelectorAll('[data-targeting-remove]').forEach(element => {
            element.removeEventListener('click', targeting_remove);
            element.addEventListener('click', targeting_remove)
        })
    };

    targeting_remove_initiator();

    /* Schedule */
    let schedule_handler = () => {
        if(document.querySelector('#schedule').checked) {
            document.querySelector('#schedule_container').style.display = 'block';
        } else {
            document.querySelector('#schedule_container').style.display = 'none';
        }
    };

    document.querySelector('#schedule').addEventListener('change', schedule_handler);

    schedule_handler();

    /* Daterangepicker */
    let locale = <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>;
    document.querySelectorAll('[data-daterangepicker]').forEach(element => {
        new daterangepicker(element, {
            minDate: new Date(),
            alwaysShowCalendars: true,
            singleCalendar: true,
            singleDatePicker: true,
            locale: {...locale, format: 'YYYY-MM-DD HH:mm:ss'},
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
        });
    });

    /* GS1 Link Real-time Updates */
    let update_gs1_flow_and_preview = () => {
        let gtin = document.querySelector('#gtin').value;
        let target_url = document.querySelector('#target_url').value;
        let targeting_type = document.querySelector('#targeting_type').value;
        
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
            <?php if($data->mode === 'create'): ?>
                gtin_display.placeholder = '01/<?= l('gs1_links.input.gtin_placeholder') ?>';
                gtin_display.value = '';
            <?php endif ?>
        }

        // Update flow visualization
        let flow_gs1_url = document.querySelector('#flow_gs1_url');
        if(gtin) {
            let gs1_url = domain_url.replace(/\/$/, '') + '/01/' + gtin;
            flow_gs1_url.innerHTML = '<code class="small">' + gs1_url + '</code>';
        } else {
            <?php if($data->mode === 'create'): ?>
                flow_gs1_url.textContent = '<?= l('gs1_link_create.flow_step_gs1_description') ?>';
            <?php endif ?>
        }

        // Update destination URL in flow
        let flow_destination_url = document.querySelector('#flow_destination_url');
        if(target_url) {
            let display_url = target_url.length > 40 ? target_url.substring(0, 40) + '...' : target_url;
            flow_destination_url.innerHTML = '<code class="small">' + display_url + '</code>';
        } else {
            <?php if($data->mode === 'create'): ?>
                flow_destination_url.textContent = '<?= l('gs1_link_create.flow_step_destination_description') ?>';
            <?php endif ?>
        }

        // Show/hide targeting step
        let targeting_step = document.querySelector('#flow_targeting_step');
        let targeting_arrow = document.querySelector('#flow_targeting_arrow');
        let targeting_description = document.querySelector('#flow_targeting_description');
        
        if(targeting_type && targeting_type !== 'false') {
            targeting_step.style.display = 'flex';
            targeting_arrow.style.display = 'block';
            
            // Update targeting description based on type
            let targeting_text = '';
            switch(targeting_type) {
                case 'continent_code':
                    targeting_text = '<?= l('global.continent') ?> <?= l('link.settings.targeting_type') ?>';
                    break;
                case 'country_code':
                    targeting_text = '<?= l('global.country') ?> <?= l('link.settings.targeting_type') ?>';
                    break;
                case 'city_name':
                    targeting_text = '<?= l('global.city') ?> <?= l('link.settings.targeting_type') ?>';
                    break;
                case 'device_type':
                    targeting_text = '<?= l('link.settings.targeting_type_device_type') ?>';
                    break;
                case 'os_name':
                    targeting_text = '<?= l('link.settings.targeting_type_os_name') ?>';
                    break;
                case 'browser_name':
                    targeting_text = '<?= l('link.settings.targeting_type_browser_name') ?>';
                    break;
                case 'browser_language':
                    targeting_text = '<?= l('link.settings.targeting_type_browser_language') ?>';
                    break;
                case 'rotation':
                    targeting_text = '<?= l('link.settings.targeting_type_rotation') ?>';
                    break;
                default:
                    targeting_text = '<?= l('link.settings.targeting_type') ?>';
            }
            targeting_description.textContent = targeting_text;
        } else {
            targeting_step.style.display = 'none';
            targeting_arrow.style.display = 'none';
        }

        // Update mobile preview URL
        let preview_final_url = document.querySelector('#preview_final_url');
        if(target_url) {
            let display_url = target_url.length > 30 ? target_url.substring(0, 30) + '...' : target_url;
            preview_final_url.textContent = display_url;
        } else {
            <?php if($data->mode === 'create'): ?>
                preview_final_url.textContent = '<?= l('gs1_link_create.preview_url_placeholder') ?>';
            <?php endif ?>
        }
    };

    // Add event listeners for real-time updates
    document.querySelector('#gtin').addEventListener('input', update_gs1_flow_and_preview);
    document.querySelector('#target_url').addEventListener('input', update_gs1_flow_and_preview);
    document.querySelector('#targeting_type').addEventListener('change', update_gs1_flow_and_preview);
    
    // Also listen for domain changes
    let domain_select = document.querySelector('select[name="domain_id"]');
    if(domain_select) {
        domain_select.addEventListener('change', update_gs1_flow_and_preview);
    }

    // Also update targeting when targeting sections change
    document.querySelector('#targeting_type').addEventListener('change', () => {
        targeting_type_handler();
        update_gs1_flow_and_preview();
    });

    // Initial update
    update_gs1_flow_and_preview();
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/js_cropper.php') ?>
