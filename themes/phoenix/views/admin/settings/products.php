<?php defined('SEEGAP') || die() ?>

<div class="card">
    <div class="card-body">

        <div class="form-group">
            <label for="products_is_enabled"><?= l('admin_settings.products.products_is_enabled') ?></label>
            <select id="products_is_enabled" name="products_is_enabled" class="custom-select">
                <option value="1" <?= settings()->products->products_is_enabled ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
                <option value="0" <?= !settings()->products->products_is_enabled ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.products.products_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="gtin_validation"><?= l('admin_settings.products.gtin_validation') ?></label>
            <select id="gtin_validation" name="gtin_validation" class="custom-select">
                <option value="disabled" <?= settings()->products->gtin_validation == 'disabled' ? 'selected="selected"' : null ?>><?= l('admin_settings.products.gtin_validation_disabled') ?></option>
                <option value="lenient" <?= settings()->products->gtin_validation == 'lenient' ? 'selected="selected"' : null ?>><?= l('admin_settings.products.gtin_validation_lenient') ?></option>
                <option value="strict" <?= settings()->products->gtin_validation == 'strict' ? 'selected="selected"' : null ?>><?= l('admin_settings.products.gtin_validation_strict') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.products.gtin_validation_help') ?></small>
        </div>

        <div class="form-group">
            <label><?= l('admin_settings.products.required_fields') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.required_fields_help') ?></small>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_brand_name" name="required_brand_name" type="checkbox" class="custom-control-input" <?= settings()->products->required_brand_name ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_brand_name"><?= l('products.input.brand_name') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_category" name="required_category" type="checkbox" class="custom-control-input" <?= settings()->products->required_category ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_category"><?= l('products.input.category') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_manufacturer" name="required_manufacturer" type="checkbox" class="custom-control-input" <?= settings()->products->required_manufacturer ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_manufacturer"><?= l('products.input.manufacturer') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_country_of_origin" name="required_country_of_origin" type="checkbox" class="custom-control-input" <?= settings()->products->required_country_of_origin ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_country_of_origin"><?= l('products.input.country_of_origin') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_net_weight" name="required_net_weight" type="checkbox" class="custom-control-input" <?= settings()->products->required_net_weight ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_net_weight"><?= l('products.input.net_weight') ?></label>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_ingredients" name="required_ingredients" type="checkbox" class="custom-control-input" <?= settings()->products->required_ingredients ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_ingredients"><?= l('products.input.ingredients') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_nutritional_info" name="required_nutritional_info" type="checkbox" class="custom-control-input" <?= settings()->products->required_nutritional_info ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_nutritional_info"><?= l('products.input.nutritional_info') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_allergen_info" name="required_allergen_info" type="checkbox" class="custom-control-input" <?= settings()->products->required_allergen_info ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_allergen_info"><?= l('products.input.allergen_info') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_storage_instructions" name="required_storage_instructions" type="checkbox" class="custom-control-input" <?= settings()->products->required_storage_instructions ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_storage_instructions"><?= l('products.input.storage_instructions') ?></label>
                    </div>

                    <div class="custom-control custom-checkbox my-2">
                        <input id="required_target_url" name="required_target_url" type="checkbox" class="custom-control-input" <?= settings()->products->required_target_url ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="required_target_url"><?= l('products.input.target_url') ?></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="auto_generate_gs1_links"><?= l('admin_settings.products.auto_generate_gs1_links') ?></label>
            <select id="auto_generate_gs1_links" name="auto_generate_gs1_links" class="custom-select">
                <option value="1" <?= settings()->products->auto_generate_gs1_links ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
                <option value="0" <?= !settings()->products->auto_generate_gs1_links ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.products.auto_generate_gs1_links_help') ?></small>
        </div>

        <div class="form-group">
            <label for="auto_generate_qr_codes"><?= l('admin_settings.products.auto_generate_qr_codes') ?></label>
            <select id="auto_generate_qr_codes" name="auto_generate_qr_codes" class="custom-select">
                <option value="1" <?= settings()->products->auto_generate_qr_codes ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
                <option value="0" <?= !settings()->products->auto_generate_qr_codes ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.products.auto_generate_qr_codes_help') ?></small>
        </div>

        <div class="form-group">
            <label for="image_upload_limit"><?= l('admin_settings.products.image_upload_limit') ?></label>
            <input id="image_upload_limit" type="number" min="0" max="50" name="image_upload_limit" class="form-control" value="<?= settings()->products->image_upload_limit ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.products.image_upload_limit_help') ?></small>
        </div>

        <div class="form-group">
            <label><?= l('admin_settings.products.export_formats') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.products.export_formats_help') ?></small>

            <div class="custom-control custom-checkbox my-2">
                <input id="enable_csv_export" name="enable_csv_export" type="checkbox" class="custom-control-input" <?= settings()->products->enable_csv_export ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="enable_csv_export"><?= l('admin_settings.products.enable_csv_export') ?></label>
            </div>

            <div class="custom-control custom-checkbox my-2">
                <input id="enable_json_export" name="enable_json_export" type="checkbox" class="custom-control-input" <?= settings()->products->enable_json_export ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="enable_json_export"><?= l('admin_settings.products.enable_json_export') ?></label>
            </div>

            <div class="custom-control custom-checkbox my-2">
                <input id="enable_xml_export" name="enable_xml_export" type="checkbox" class="custom-control-input" <?= settings()->products->enable_xml_export ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="enable_xml_export"><?= l('admin_settings.products.enable_xml_export') ?></label>
            </div>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
    </div>
</div>
