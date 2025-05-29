<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="gs1_links_is_enabled" name="gs1_links_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->gs1_links_is_enabled ?? false) ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="gs1_links_is_enabled"><i class="fas fa-fw fa-sm fa-barcode text-muted mr-1"></i> <?= l('admin_settings.gs1_links.gs1_links_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.gs1_links.gs1_links_is_enabled_help') ?></small>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#gtin_settings_container" aria-expanded="false" aria-controls="gtin_settings_container">
        <i class="fas fa-fw fa-hashtag fa-sm mr-1"></i> <?= l('admin_settings.gs1_links.gtin_settings') ?>
    </button>

    <div class="collapse" id="gtin_settings_container">
        <div class="form-group custom-control custom-switch">
            <input id="gtin_validation_is_enabled" name="gtin_validation_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->gtin_validation_is_enabled ?? true) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="gtin_validation_is_enabled"><?= l('admin_settings.gs1_links.gtin_validation_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.gtin_validation_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="gtin_format_validation"><?= l('admin_settings.gs1_links.gtin_format_validation') ?></label>
            <select id="gtin_format_validation" name="gtin_format_validation" class="custom-select">
                <option value="strict" <?= (settings()->gs1_links->gtin_format_validation ?? 'strict') == 'strict' ? 'selected="selected"' : null ?>><?= l('admin_settings.gs1_links.gtin_format_validation.strict') ?></option>
                <option value="lenient" <?= (settings()->gs1_links->gtin_format_validation ?? 'strict') == 'lenient' ? 'selected="selected"' : null ?>><?= l('admin_settings.gs1_links.gtin_format_validation.lenient') ?></option>
                <option value="disabled" <?= (settings()->gs1_links->gtin_format_validation ?? 'strict') == 'disabled' ? 'selected="selected"' : null ?>><?= l('admin_settings.gs1_links.gtin_format_validation.disabled') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.gtin_format_validation_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_target_url" name="require_target_url" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->require_target_url ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_target_url"><?= l('admin_settings.gs1_links.require_target_url') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.require_target_url_help') ?></small>
        </div>

        <div class="form-group">
            <label for="default_target_url"><?= l('admin_settings.gs1_links.default_target_url') ?></label>
            <input id="default_target_url" type="url" name="default_target_url" class="form-control" placeholder="<?= l('global.url_placeholder') ?>" value="<?= settings()->gs1_links->default_target_url ?? 'https://example.com/' ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.default_target_url_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#integration_container" aria-expanded="false" aria-controls="integration_container">
        <i class="fas fa-fw fa-plug fa-sm mr-1"></i> <?= l('admin_settings.gs1_links.integration') ?>
    </button>

    <div class="collapse" id="integration_container">
        <div class="form-group custom-control custom-switch">
            <input id="domains_is_enabled" name="domains_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->domains_is_enabled ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="domains_is_enabled"><?= l('admin_settings.gs1_links.domains_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.domains_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="projects_is_enabled" name="projects_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->projects_is_enabled ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="projects_is_enabled"><?= l('admin_settings.gs1_links.projects_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.projects_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="pixels_is_enabled" name="pixels_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->pixels_is_enabled ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pixels_is_enabled"><?= l('admin_settings.gs1_links.pixels_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.pixels_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="analytics_is_enabled" name="analytics_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->analytics_is_enabled ?? true) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="analytics_is_enabled"><?= l('admin_settings.gs1_links.analytics_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.analytics_is_enabled_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#features_container" aria-expanded="false" aria-controls="features_container">
        <i class="fas fa-fw fa-cogs fa-sm mr-1"></i> <?= l('admin_settings.gs1_links.features') ?>
    </button>

    <div class="collapse" id="features_container">
        <div class="form-group custom-control custom-switch">
            <input id="auto_generate_qr_codes" name="auto_generate_qr_codes" type="checkbox" class="custom-control-input" <?= (settings()->gs1_links->auto_generate_qr_codes ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="auto_generate_qr_codes"><?= l('admin_settings.gs1_links.auto_generate_qr_codes') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.auto_generate_qr_codes_help') ?></small>
        </div>

        <div class="form-group">
            <label for="branding"><?= l('admin_settings.gs1_links.branding') ?></label>
            <textarea id="branding" name="branding" class="form-control"><?= settings()->gs1_links->branding ?? '' ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.branding_help') ?></small>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.branding_help2') ?></small>
        </div>

        <div class="form-group">
            <label for="random_gtin_length"><?= l('admin_settings.gs1_links.random_gtin_length') ?></label>
            <select id="random_gtin_length" name="random_gtin_length" class="custom-select">
                <option value="8" <?= (settings()->gs1_links->random_gtin_length ?? '13') == '8' ? 'selected="selected"' : null ?>>GTIN-8</option>
                <option value="12" <?= (settings()->gs1_links->random_gtin_length ?? '13') == '12' ? 'selected="selected"' : null ?>>GTIN-12</option>
                <option value="13" <?= (settings()->gs1_links->random_gtin_length ?? '13') == '13' ? 'selected="selected"' : null ?>>GTIN-13</option>
                <option value="14" <?= (settings()->gs1_links->random_gtin_length ?? '13') == '14' ? 'selected="selected"' : null ?>>GTIN-14</option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.random_gtin_length_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#security_container" aria-expanded="false" aria-controls="security_container">
        <i class="fas fa-fw fa-shield-alt fa-sm mr-1"></i> <?= l('admin_settings.gs1_links.security') ?>
    </button>

    <div class="collapse" id="security_container">
        <div class="form-group">
            <label for="blacklisted_gtins"><?= l('admin_settings.gs1_links.blacklisted_gtins') ?></label>
            <textarea id="blacklisted_gtins" class="form-control" name="blacklisted_gtins"><?= implode(',', settings()->gs1_links->blacklisted_gtins ?? []) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.blacklisted_gtins_help') ?></small>
        </div>

        <div class="form-group">
            <label for="allowed_gtin_prefixes"><?= l('admin_settings.gs1_links.allowed_gtin_prefixes') ?></label>
            <textarea id="allowed_gtin_prefixes" class="form-control" name="allowed_gtin_prefixes"><?= implode(',', settings()->gs1_links->allowed_gtin_prefixes ?? []) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.gs1_links.allowed_gtin_prefixes_help') ?></small>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
