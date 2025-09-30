<?php defined('SEEGAP') || die() ?>

<input type="hidden" name="section" value="general" />

<div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="fas fa-info-circle text-primary mr-2"></i>
            <?= l('products.sections.general') ?>
        </h5>
        <small class="text-muted"><?= l('products.sections.general_description') ?></small>
    </div>

    <div class="row">
        <!-- GTIN (01) - Primary Identifier -->
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="gtin">
                    <i class="fas fa-fw fa-barcode fa-sm text-muted mr-1"></i> 
                    <?= l('products.gs1.gtin_01') ?>
                    <span class="text-danger">*</span>
                </label>
                <input type="text" id="gtin" name="gtin" class="form-control <?= \SeeGap\Alerts::has_field_errors('gtin') ? 'is-invalid' : null ?>" value="<?= $data->product->gtin ?>" maxlength="14" required="required" />
                <?= \SeeGap\Alerts::output_field_error('gtin') ?>
                <small class="form-text text-muted">
                    <strong>GS1 AI (01):</strong> <?= l('products.gs1.gtin_help') ?>
                </small>
            </div>
        </div>

        <!-- Product Status -->
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label><?= l('global.status') ?></label>
                <div class="custom-control custom-switch">
                    <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->product->is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="is_enabled">
                        <span class="text-success"><?= l('products.input.is_enabled') ?></span>
                    </label>
                    <small class="form-text text-muted"><?= l('products.input.is_enabled_help') ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Name -->
        <div class="col-12 col-lg-8">
            <div class="form-group">
                <label for="product_name">
                    <i class="fas fa-fw fa-box fa-sm text-muted mr-1"></i> 
                    <?= l('products.input.product_name') ?>
                    <span class="text-danger">*</span>
                </label>
                <input type="text" id="product_name" name="product_name" class="form-control <?= \SeeGap\Alerts::has_field_errors('product_name') ? 'is-invalid' : null ?>" value="<?= $data->product->product_name ?>" maxlength="256" required="required" />
                <?= \SeeGap\Alerts::output_field_error('product_name') ?>
                <small class="form-text text-muted"><?= l('products.input.product_name_help') ?></small>
            </div>
        </div>

        <!-- Brand Name -->
        <div class="col-12 col-lg-4">
            <div class="form-group">
                <label for="brand_name">
                    <i class="fas fa-fw fa-tag fa-sm text-muted mr-1"></i> 
                    <?= l('products.input.brand_name') ?>
                    <?php if(settings()->products->require_brand_name ?? false): ?>
                        <span class="text-danger">*</span>
                    <?php endif ?>
                </label>
                <input type="text" id="brand_name" name="brand_name" class="form-control <?= \SeeGap\Alerts::has_field_errors('brand_name') ? 'is-invalid' : null ?>" value="<?= $data->product->brand_name ?>" maxlength="128" <?php if(settings()->products->require_brand_name ?? false): ?>required="required"<?php endif ?> />
                <?= \SeeGap\Alerts::output_field_error('brand_name') ?>
                <small class="form-text text-muted"><?= l('products.input.brand_name_help') ?></small>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    <div class="form-group">
        <label for="product_description">
            <i class="fas fa-fw fa-align-left fa-sm text-muted mr-1"></i> 
            <?= l('products.input.product_description') ?>
        </label>
        <textarea id="product_description" name="product_description" class="form-control <?= \SeeGap\Alerts::has_field_errors('product_description') ? 'is-invalid' : null ?>" rows="3" maxlength="1000"><?= $data->product->product_description ?></textarea>
        <?= \SeeGap\Alerts::output_field_error('product_description') ?>
        <small class="form-text text-muted"><?= l('products.input.product_description_help') ?></small>
    </div>

    <div class="row">
        <!-- Category -->
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="category">
                    <i class="fas fa-fw fa-folder fa-sm text-muted mr-1"></i> 
                    <?= l('products.input.category') ?>
                    <?php if(settings()->products->require_category ?? false): ?>
                        <span class="text-danger">*</span>
                    <?php endif ?>
                </label>
                <input type="text" id="category" name="category" class="form-control <?= \SeeGap\Alerts::has_field_errors('category') ? 'is-invalid' : null ?>" value="<?= $data->product->category ?>" maxlength="128" <?php if(settings()->products->require_category ?? false): ?>required="required"<?php endif ?> />
                <?= \SeeGap\Alerts::output_field_error('category') ?>
                <small class="form-text text-muted"><?= l('products.input.category_help') ?></small>
            </div>
        </div>

        <!-- Subcategory -->
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="subcategory">
                    <i class="fas fa-fw fa-folder-open fa-sm text-muted mr-1"></i> 
                    <?= l('products.input.subcategory') ?>
                </label>
                <input type="text" id="subcategory" name="subcategory" class="form-control <?= \SeeGap\Alerts::has_field_errors('subcategory') ? 'is-invalid' : null ?>" value="<?= $data->product->subcategory ?>" maxlength="128" />
                <?= \SeeGap\Alerts::output_field_error('subcategory') ?>
                <small class="form-text text-muted"><?= l('products.input.subcategory_help') ?></small>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Manufacturer -->
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="manufacturer">
                    <i class="fas fa-fw fa-industry fa-sm text-muted mr-1"></i> 
                    <?= l('products.input.manufacturer') ?>
                </label>
                <input type="text" id="manufacturer" name="manufacturer" class="form-control <?= \SeeGap\Alerts::has_field_errors('manufacturer') ? 'is-invalid' : null ?>" value="<?= $data->product->manufacturer ?>" maxlength="256" />
                <?= \SeeGap\Alerts::output_field_error('manufacturer') ?>
                <small class="form-text text-muted"><?= l('products.input.manufacturer_help') ?></small>
            </div>
        </div>

        <!-- Country of Origin (422) -->
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="country_of_origin">
                    <i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> 
                    <?= l('products.gs1.country_of_origin_422') ?>
                </label>
                <input type="text" id="country_of_origin" name="country_of_origin" class="form-control <?= \SeeGap\Alerts::has_field_errors('country_of_origin') ? 'is-invalid' : null ?>" value="<?= $data->product->country_of_origin ?>" maxlength="3" placeholder="IRL" />
                <?= \SeeGap\Alerts::output_field_error('country_of_origin') ?>
                <small class="form-text text-muted">
                    <strong>GS1 AI (422):</strong> <?= l('products.gs1.country_of_origin_help') ?>
                </small>
            </div>
        </div>
    </div>

    <!-- Project Assignment -->
    <?php if(count($data->projects)): ?>
    <div class="form-group">
        <label for="project_id">
            <i class="fas fa-fw fa-project-diagram fa-sm text-muted mr-1"></i> 
            <?= l('projects.project') ?>
        </label>
        <select id="project_id" name="project_id" class="custom-select">
            <option value=""><?= l('global.none') ?></option>
            <?php foreach($data->projects as $project_id => $project): ?>
                <option value="<?= $project_id ?>" <?= $data->product->project_id == $project_id ? 'selected="selected"' : null ?>>
                    <?= $project->name ?>
                </option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted"><?= l('products.input.project_help') ?></small>
    </div>
    <?php endif ?>

    <!-- Save Button -->
    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-primary">
            <i class="fas fa-save fa-sm mr-1"></i>
            <?= l('global.update') ?>
        </button>
        <a href="<?= url('products') ?>" class="btn btn-outline-secondary ml-2">
            <i class="fas fa-times fa-sm mr-1"></i>
            <?= l('global.cancel') ?>
        </a>
    </div>
