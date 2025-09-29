<?php defined('SEEGAP') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="products_is_enabled" name="products_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->products->products_is_enabled ?? false) ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="products_is_enabled"><i class="fas fa-fw fa-sm fa-box text-muted mr-1"></i> <?= l('admin_settings.products.products_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.products.products_is_enabled_help') ?></small>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#gtin_validation_container" aria-expanded="false" aria-controls="gtin_validation_container">
        <i class="fas fa-fw fa-hashtag fa-sm mr-1"></i> <?= l('admin_settings.products.gtin_validation') ?>
    </button>

    <div class="collapse" id="gtin_validation_container">
        <div class="form-group custom-control custom-switch">
            <input id="gtin_validation_is_enabled" name="gtin_validation_is_enabled" type="checkbox" class="custom-control-input" <?= (settings()->products->gtin_validation_is_enabled ?? true) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="gtin_validation_is_enabled"><?= l('admin_settings.products.gtin_validation_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.gtin_validation_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="gtin_format_validation"><?= l('admin_settings.products.gtin_format_validation') ?></label>
            <select id="gtin_format_validation" name="gtin_format_validation" class="custom-select">
                <option value="strict" <?= (settings()->products->gtin_format_validation ?? 'strict') == 'strict' ? 'selected="selected"' : null ?>><?= l('admin_settings.products.gtin_format_validation.strict') ?></option>
                <option value="lenient" <?= (settings()->products->gtin_format_validation ?? 'strict') == 'lenient' ? 'selected="selected"' : null ?>><?= l('admin_settings.products.gtin_format_validation.lenient') ?></option>
                <option value="disabled" <?= (settings()->products->gtin_format_validation ?? 'strict') == 'disabled' ? 'selected="selected"' : null ?>><?= l('admin_settings.products.gtin_format_validation.disabled') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.products.gtin_format_validation_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#required_fields_container" aria-expanded="false" aria-controls="required_fields_container">
        <i class="fas fa-fw fa-asterisk fa-sm mr-1"></i> <?= l('admin_settings.products.required_fields') ?>
    </button>

    <div class="collapse" id="required_fields_container">
        <div class="form-group custom-control custom-switch">
            <input id="require_product_name" name="require_product_name" type="checkbox" class="custom-control-input" <?= (settings()->products->require_product_name ?? true) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_product_name"><?= l('admin_settings.products.require_product_name') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_product_name_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_brand_name" name="require_brand_name" type="checkbox" class="custom-control-input" <?= (settings()->products->require_brand_name ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_brand_name"><?= l('admin_settings.products.require_brand_name') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_brand_name_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_category" name="require_category" type="checkbox" class="custom-control-input" <?= (settings()->products->require_category ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_category"><?= l('admin_settings.products.require_category') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_category_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_manufacturer" name="require_manufacturer" type="checkbox" class="custom-control-input" <?= (settings()->products->require_manufacturer ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_manufacturer"><?= l('admin_settings.products.require_manufacturer') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_manufacturer_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_country_of_origin" name="require_country_of_origin" type="checkbox" class="custom-control-input" <?= (settings()->products->require_country_of_origin ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_country_of_origin"><?= l('admin_settings.products.require_country_of_origin') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_country_of_origin_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_net_weight" name="require_net_weight" type="checkbox" class="custom-control-input" <?= (settings()->products->require_net_weight ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_net_weight"><?= l('admin_settings.products.require_net_weight') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_net_weight_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_ingredients" name="require_ingredients" type="checkbox" class="custom-control-input" <?= (settings()->products->require_ingredients ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_ingredients"><?= l('admin_settings.products.require_ingredients') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_ingredients_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_nutritional_info" name="require_nutritional_info" type="checkbox" class="custom-control-input" <?= (settings()->products->require_nutritional_info ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_nutritional_info"><?= l('admin_settings.products.require_nutritional_info') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_nutritional_info_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_allergen_info" name="require_allergen_info" type="checkbox" class="custom-control-input" <?= (settings()->products->require_allergen_info ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_allergen_info"><?= l('admin_settings.products.require_allergen_info') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_allergen_info_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_storage_instructions" name="require_storage_instructions" type="checkbox" class="custom-control-input" <?= (settings()->products->require_storage_instructions ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_storage_instructions"><?= l('admin_settings.products.require_storage_instructions') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_storage_instructions_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="require_target_url" name="require_target_url" type="checkbox" class="custom-control-input" <?= (settings()->products->require_target_url ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="require_target_url"><?= l('admin_settings.products.require_target_url') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.require_target_url_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#integration_features_container" aria-expanded="false" aria-controls="integration_features_container">
        <i class="fas fa-fw fa-cogs fa-sm mr-1"></i> <?= l('admin_settings.products.integration_features') ?>
    </button>

    <div class="collapse" id="integration_features_container">
        <div class="form-group custom-control custom-switch">
            <input id="auto_generate_gs1_links" name="auto_generate_gs1_links" type="checkbox" class="custom-control-input" <?= (settings()->products->auto_generate_gs1_links ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="auto_generate_gs1_links"><?= l('admin_settings.products.auto_generate_gs1_links') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.auto_generate_gs1_links_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="auto_generate_qr_codes" name="auto_generate_qr_codes" type="checkbox" class="custom-control-input" <?= (settings()->products->auto_generate_qr_codes ?? false) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="auto_generate_qr_codes"><?= l('admin_settings.products.auto_generate_qr_codes') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.auto_generate_qr_codes_help') ?></small>
        </div>

        <div class="form-group">
            <label for="image_upload_limit"><?= l('admin_settings.products.image_upload_limit') ?></label>
            <input id="image_upload_limit" type="number" name="image_upload_limit" class="form-control" value="<?= settings()->products->image_upload_limit ?? 5 ?>" min="1" max="20" />
            <small class="form-text text-muted"><?= l('admin_settings.products.image_upload_limit_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#export_options_container" aria-expanded="false" aria-controls="export_options_container">
        <i class="fas fa-fw fa-download fa-sm mr-1"></i> <?= l('admin_settings.products.export_options') ?>
    </button>

    <div class="collapse" id="export_options_container">
        <div class="form-group custom-control custom-switch">
            <input id="enable_csv_export" name="enable_csv_export" type="checkbox" class="custom-control-input" <?= (settings()->products->enable_csv_export ?? true) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="enable_csv_export"><?= l('admin_settings.products.enable_csv_export') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.enable_csv_export_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="enable_json_export" name="enable_json_export" type="checkbox" class="custom-control-input" <?= (settings()->products->enable_json_export ?? true) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="enable_json_export"><?= l('admin_settings.products.enable_json_export') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.enable_json_export_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="enable_xml_export" name="enable_xml_export" type="checkbox" class="custom-control-input" <?= (settings()->products->enable_xml_export ?? true) ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="enable_xml_export"><?= l('admin_settings.products.enable_xml_export') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.enable_xml_export_help') ?></small>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
