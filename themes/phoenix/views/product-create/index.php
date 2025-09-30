<?php defined('SEEGAP') || die() ?>

<link href="<?= ASSETS_FULL_URL ?>css/products-custom.css?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">

<div class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('products') ?>"><?= l('products.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('products.create.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-plus-circle text-primary mr-1"></i> <?= l('products.create.header') ?></h1>
    </div>

    <div class="card">
        <div class="card-header bg-white border-bottom">
            <h5 class="card-title mb-0">
                <i class="fas fa-fw fa-box fa-sm text-primary mr-2"></i>
                <?= l('products.create.form_title') ?? 'Product Information' ?>
            </h5>
        </div>
        
        <div class="card-body">
            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="gtin"><i class="fas fa-fw fa-barcode fa-sm text-muted mr-1"></i> <?= l('products.input.gtin') ?></label>
                            <input type="text" id="gtin" name="gtin" class="form-control <?= \SeeGap\Alerts::has_field_errors('gtin') ? 'is-invalid' : null ?>" value="<?= $data->values['gtin'] ?? null ?>" maxlength="14" required="required" />
                            <?= \SeeGap\Alerts::output_field_error('gtin') ?>
                            <small class="form-text text-muted"><?= l('products.input.gtin_help') ?></small>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="brand_name"><i class="fas fa-fw fa-tag fa-sm text-muted mr-1"></i> <?= l('products.input.brand_name') ?></label>
                            <input type="text" id="brand_name" name="brand_name" class="form-control <?= \SeeGap\Alerts::has_field_errors('brand_name') ? 'is-invalid' : null ?>" value="<?= $data->values['brand_name'] ?? null ?>" maxlength="128" />
                            <?= \SeeGap\Alerts::output_field_error('brand_name') ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="product_name"><i class="fas fa-fw fa-box fa-sm text-muted mr-1"></i> <?= l('products.input.product_name') ?></label>
                    <input type="text" id="product_name" name="product_name" class="form-control <?= \SeeGap\Alerts::has_field_errors('product_name') ? 'is-invalid' : null ?>" value="<?= $data->values['product_name'] ?? null ?>" maxlength="256" required="required" />
                    <?= \SeeGap\Alerts::output_field_error('product_name') ?>
                </div>

                <div class="form-group">
                    <label for="product_description"><i class="fas fa-fw fa-align-left fa-sm text-muted mr-1"></i> <?= l('products.input.product_description') ?></label>
                    <textarea id="product_description" name="product_description" class="form-control <?= \SeeGap\Alerts::has_field_errors('product_description') ? 'is-invalid' : null ?>" rows="3"><?= $data->values['product_description'] ?? null ?></textarea>
                    <?= \SeeGap\Alerts::output_field_error('product_description') ?>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="category"><i class="fas fa-fw fa-folder fa-sm text-muted mr-1"></i> <?= l('products.input.category') ?></label>
                            <input type="text" id="category" name="category" class="form-control <?= \SeeGap\Alerts::has_field_errors('category') ? 'is-invalid' : null ?>" value="<?= $data->values['category'] ?? null ?>" maxlength="128" />
                            <?= \SeeGap\Alerts::output_field_error('category') ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="subcategory"><i class="fas fa-fw fa-folder-open fa-sm text-muted mr-1"></i> <?= l('products.input.subcategory') ?></label>
                            <input type="text" id="subcategory" name="subcategory" class="form-control <?= \SeeGap\Alerts::has_field_errors('subcategory') ? 'is-invalid' : null ?>" value="<?= $data->values['subcategory'] ?? null ?>" maxlength="128" />
                            <?= \SeeGap\Alerts::output_field_error('subcategory') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="manufacturer"><i class="fas fa-fw fa-industry fa-sm text-muted mr-1"></i> <?= l('products.input.manufacturer') ?></label>
                            <input type="text" id="manufacturer" name="manufacturer" class="form-control <?= \SeeGap\Alerts::has_field_errors('manufacturer') ? 'is-invalid' : null ?>" value="<?= $data->values['manufacturer'] ?? null ?>" maxlength="256" />
                            <?= \SeeGap\Alerts::output_field_error('manufacturer') ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="country_of_origin"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('products.input.country_of_origin') ?></label>
                            <input type="text" id="country_of_origin" name="country_of_origin" class="form-control <?= \SeeGap\Alerts::has_field_errors('country_of_origin') ? 'is-invalid' : null ?>" value="<?= $data->values['country_of_origin'] ?? null ?>" maxlength="64" />
                            <?= \SeeGap\Alerts::output_field_error('country_of_origin') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="net_weight"><i class="fas fa-fw fa-weight fa-sm text-muted mr-1"></i> <?= l('products.input.net_weight') ?></label>
                            <input type="text" id="net_weight" name="net_weight" class="form-control <?= \SeeGap\Alerts::has_field_errors('net_weight') ? 'is-invalid' : null ?>" value="<?= $data->values['net_weight'] ?? null ?>" maxlength="64" />
                            <?= \SeeGap\Alerts::output_field_error('net_weight') ?>
                            <small class="form-text text-muted"><?= l('products.input.net_weight_help') ?></small>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="dimensions"><i class="fas fa-fw fa-ruler fa-sm text-muted mr-1"></i> <?= l('products.input.dimensions') ?></label>
                            <input type="text" id="dimensions" name="dimensions" class="form-control <?= \SeeGap\Alerts::has_field_errors('dimensions') ? 'is-invalid' : null ?>" value="<?= $data->values['dimensions'] ?? null ?>" maxlength="128" />
                            <?= \SeeGap\Alerts::output_field_error('dimensions') ?>
                            <small class="form-text text-muted"><?= l('products.input.dimensions_help') ?></small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ingredients"><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> <?= l('products.input.ingredients') ?></label>
                    <textarea id="ingredients" name="ingredients" class="form-control <?= \SeeGap\Alerts::has_field_errors('ingredients') ? 'is-invalid' : null ?>" rows="3"><?= $data->values['ingredients'] ?? null ?></textarea>
                    <?= \SeeGap\Alerts::output_field_error('ingredients') ?>
                </div>

                <div class="form-group">
                    <label for="nutritional_info"><i class="fas fa-fw fa-apple-alt fa-sm text-muted mr-1"></i> <?= l('products.input.nutritional_info') ?></label>
                    <textarea id="nutritional_info" name="nutritional_info" class="form-control <?= \SeeGap\Alerts::has_field_errors('nutritional_info') ? 'is-invalid' : null ?>" rows="3"><?= $data->values['nutritional_info'] ?? null ?></textarea>
                    <?= \SeeGap\Alerts::output_field_error('nutritional_info') ?>
                </div>

                <div class="form-group">
                    <label for="allergen_info"><i class="fas fa-fw fa-exclamation-triangle fa-sm text-muted mr-1"></i> <?= l('products.input.allergen_info') ?></label>
                    <textarea id="allergen_info" name="allergen_info" class="form-control <?= \SeeGap\Alerts::has_field_errors('allergen_info') ? 'is-invalid' : null ?>" rows="2"><?= $data->values['allergen_info'] ?? null ?></textarea>
                    <?= \SeeGap\Alerts::output_field_error('allergen_info') ?>
                </div>

                <div class="form-group">
                    <label for="certifications"><i class="fas fa-fw fa-certificate fa-sm text-muted mr-1"></i> <?= l('products.input.certifications') ?></label>
                    <textarea id="certifications" name="certifications" class="form-control <?= \SeeGap\Alerts::has_field_errors('certifications') ? 'is-invalid' : null ?>" rows="2"><?= $data->values['certifications'] ?? null ?></textarea>
                    <?= \SeeGap\Alerts::output_field_error('certifications') ?>
                    <small class="form-text text-muted"><?= l('products.input.certifications_help') ?></small>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="storage_instructions"><i class="fas fa-fw fa-warehouse fa-sm text-muted mr-1"></i> <?= l('products.input.storage_instructions') ?></label>
                            <textarea id="storage_instructions" name="storage_instructions" class="form-control <?= \SeeGap\Alerts::has_field_errors('storage_instructions') ? 'is-invalid' : null ?>" rows="2"><?= $data->values['storage_instructions'] ?? null ?></textarea>
                            <?= \SeeGap\Alerts::output_field_error('storage_instructions') ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="usage_instructions"><i class="fas fa-fw fa-info-circle fa-sm text-muted mr-1"></i> <?= l('products.input.usage_instructions') ?></label>
                            <textarea id="usage_instructions" name="usage_instructions" class="form-control <?= \SeeGap\Alerts::has_field_errors('usage_instructions') ? 'is-invalid' : null ?>" rows="2"><?= $data->values['usage_instructions'] ?? null ?></textarea>
                            <?= \SeeGap\Alerts::output_field_error('usage_instructions') ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="target_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('products.input.target_url') ?></label>
                    <input type="url" id="target_url" name="target_url" class="form-control <?= \SeeGap\Alerts::has_field_errors('target_url') ? 'is-invalid' : null ?>" value="<?= $data->values['target_url'] ?? null ?>" maxlength="2048" />
                    <?= \SeeGap\Alerts::output_field_error('target_url') ?>
                    <small class="form-text text-muted"><?= l('products.input.target_url_help') ?></small>
                </div>

                <div class="row">
                    <?php if(count($data->projects)): ?>
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="project_id"><i class="fas fa-fw fa-project-diagram fa-sm text-muted mr-1"></i> <?= l('projects.project') ?></label>
                            <select id="project_id" name="project_id" class="custom-select">
                                <option value=""><?= l('global.none') ?></option>
                                <?php foreach($data->projects as $project_id => $project): ?>
                                    <option value="<?= $project_id ?>" <?= isset($data->values['project_id']) && $data->values['project_id'] == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <?php endif ?>

                    <?php if(count($data->gs1_links) && settings()->gs1_links->gs1_links_is_enabled): ?>
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="gs1_link_id"><i class="fas fa-fw fa-barcode fa-sm text-muted mr-1"></i> <?= l('products.input.gs1_link') ?></label>
                            <select id="gs1_link_id" name="gs1_link_id" class="custom-select">
                                <option value=""><?= l('global.none') ?></option>
                                <?php foreach($data->gs1_links as $gs1_link): ?>
                                    <option value="<?= $gs1_link->gs1_link_id ?>" <?= isset($data->values['gs1_link_id']) && $data->values['gs1_link_id'] == $gs1_link->gs1_link_id ? 'selected="selected"' : null ?>>
                                        <?= $gs1_link->gtin ?> - <?= $gs1_link->title ?: l('global.unknown') ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <small class="form-text text-muted"><?= l('products.input.gs1_link_help') ?></small>
                        </div>
                    </div>
                    <?php endif ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.create') ?></button>
            </form>

        </div>
    </div>
</div>
