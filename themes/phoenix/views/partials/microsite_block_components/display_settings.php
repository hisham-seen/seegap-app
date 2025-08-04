<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Enhanced Display Settings Component for Microsite Blocks
 * This provides display targeting settings for microsite blocks
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $row - Block data object (for scheduling)
 * @param object $user - User object (for timezone)
 */

$block_id = $block_id ?? 'default';
$row = $row ?? (object)[];
$user = $user ?? $this->user ?? (object)['timezone' => 'UTC'];

// Ensure row has microsite_block_id for compatibility
if (!isset($row->microsite_block_id)) {
    $row->microsite_block_id = $block_id;
}

// Ensure row has settings object
if (!isset($row->settings)) {
    $row->settings = (object)[];
}

// Ensure all required settings properties exist
$default_settings = [
    'display_continents' => [],
    'display_countries' => [],
    'display_cities' => [],
    'display_devices' => [],
    'display_operating_systems' => [],
    'display_browsers' => [],
    'display_languages' => []
];

foreach ($default_settings as $key => $default_value) {
    if (!isset($row->settings->$key)) {
        $row->settings->$key = $default_value;
    }
}
?>

<!-- Display Settings Component -->
<button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'display_settings_container_' . $block_id ?>" aria-expanded="false" aria-controls="<?= 'display_settings_container_' . $block_id ?>">
    <i class="fas fa-fw fa-display fa-sm mr-1"></i> <?= l('microsite_link.display_settings_header') ?>
</button>

<div class="collapse" id="<?= 'display_settings_container_' . $block_id ?>">
    <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
            <div class="form-group custom-control custom-switch">
                <input
                        id="<?= 'link_schedule_' . $block_id ?>"
                        name="schedule" type="checkbox"
                        class="custom-control-input"
                    <?= !empty($row->start_date) && !empty($row->end_date) ? 'checked="checked"' : null ?>
                    <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'disabled="disabled"' ?>
                >
                <label class="custom-control-label" for="<?= 'link_schedule_' . $block_id ?>"><?= l('link.settings.schedule') ?></label>
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
                            <label for="<?= 'link_start_date_' . $block_id ?>"><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
                            <input
                                    id="<?= 'link_start_date_' . $block_id ?>"
                                    type="text"
                                    class="form-control"
                                    name="start_date"
                                    value="<?= !empty($row->start_date) ? \SeeGap\Date::get($row->start_date, 1) : '' ?>"
                                    placeholder="<?= l('link.settings.start_date') ?>"
                                    autocomplete="off"
                                    data-daterangepicker
                            >
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="<?= 'link_end_date_' . $block_id ?>"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                            <input
                                    id="<?= 'link_end_date_' . $block_id ?>"
                                    type="text"
                                    class="form-control"
                                    name="end_date"
                                    value="<?= !empty($row->end_date) ? \SeeGap\Date::get($row->end_date, 1) : '' ?>"
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
        <label for="<?= 'link_display_continents_' . $block_id ?>"><i class="fas fa-fw fa-earth-europe fa-sm text-muted mr-1"></i> <?= l('global.continents') ?></label>
        <select id="<?= 'link_display_continents_' . $block_id ?>" name="display_continents[]" class="custom-select" multiple="multiple">
            <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                <option value="<?= $continent_code ?>" <?= in_array($continent_code, $row->settings->display_continents ?? []) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'link_display_countries_' . $block_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.countries') ?></label>
        <select id="<?= 'link_display_countries_' . $block_id ?>" name="display_countries[]" class="custom-select" multiple="multiple">
            <?php foreach(get_countries_array() as $country => $country_name): ?>
                <option value="<?= $country ?>" <?= in_array($country, $row->settings->display_countries ?? []) ? 'selected="selected"' : null ?>><?= $country_name ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'link_display_cities_' . $block_id ?>"><i class="fas fa-fw fa-sm fa-city text-muted mr-1"></i> <?= l('global.cities') ?></label>
        <input type="text" id="<?= 'link_display_cities_' . $block_id ?>" name="display_cities" value="<?= implode(',', $row->settings->display_cities ?? []) ?>" class="form-control" placeholder="<?= l('microsite_link.display_cities_placeholder') ?>" />
        <small class="form-text text-muted"><?= l('microsite_link.display_cities_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'link_display_devices_' . $block_id ?>"><i class="fas fa-fw fa-laptop fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_devices') ?></label>
        <select id="<?= 'link_display_devices_' . $block_id ?>" name="display_devices[]" class="custom-select" multiple="multiple">
            <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                <option value="<?= $device_type ?>" <?= in_array($device_type, $row->settings->display_devices ?? []) ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'link_display_operating_systems_' . $block_id ?>"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_operating_systems') ?></label>
        <select id="<?= 'link_display_operating_systems_' . $block_id ?>" name="display_operating_systems[]" class="custom-select" multiple="multiple">
            <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                <option value="<?= $os_name ?>" <?= in_array($os_name, $row->settings->display_operating_systems ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'link_display_browsers_' . $block_id ?>"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_browsers') ?></label>
        <select id="<?= 'link_display_browsers_' . $block_id ?>" name="display_browsers[]" class="custom-select" multiple="multiple">
            <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                <option value="<?= $browser_name ?>" <?= in_array($browser_name, $row->settings->display_browsers ?? []) ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'link_display_languages_' . $block_id ?>"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('microsite_link.display_languages') ?></label>
        <select id="<?= 'link_display_languages_' . $block_id ?>" name="display_languages[]" class="custom-select" multiple="multiple">
            <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                <option value="<?= $locale ?>" <?= in_array($locale, $row->settings->display_languages ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted"><?= l('microsite_link.settings.display_help') ?></small>
    </div>
</div>
