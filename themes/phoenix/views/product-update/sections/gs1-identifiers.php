<?php defined('SEEGAP') || die() ?>

<input type="hidden" name="section" value="gs1-identifiers" />

<div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="fas fa-barcode text-primary mr-2"></i>
            <?= l('products.sections.gs1_identifiers') ?>
        </h5>
        <small class="text-muted"><?= l('products.sections.gs1_identifiers_description') ?></small>
    </div>

    <!-- Primary GS1 Identifiers -->
    <div class="mb-4">
        <h6 class="mb-3">
            <i class="fas fa-id-card fa-sm mr-2"></i>
            <?= l('products.gs1.primary_identifiers') ?>
        </h6>
        <div class="row">
                <!-- GLN (413) - Global Location Number -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="gln">
                            <i class="fas fa-fw fa-map-marker-alt fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.gln_413') ?>
                        </label>
                        <input type="text" id="gln" name="gln" class="form-control <?= \SeeGap\Alerts::has_field_errors('gln') ? 'is-invalid' : null ?>" value="<?= $data->product->gln ?>" maxlength="13" />
                        <?= \SeeGap\Alerts::output_field_error('gln') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (413):</strong> <?= l('products.gs1.gln_help') ?>
                        </small>
                    </div>
                </div>

                <!-- Variant Number (20) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="variant">
                            <i class="fas fa-fw fa-code-branch fa-sm text-muted mr-1"></i>
                            <?= l('products.gs1.variant_number_20') ?>
                        </label>
                        <input type="text" id="variant" name="variant" class="form-control <?= \SeeGap\Alerts::has_field_errors('variant') ? 'is-invalid' : null ?>" value="<?= $data->product->variant ?>" maxlength="20" />
                        <?= \SeeGap\Alerts::output_field_error('variant') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (20):</strong> <?= l('products.gs1.variant_number_help') ?>
                        </small>
                    </div>
                </div>
            </div>
    </div>

    <!-- Batch & Serial Information -->
    <div class="mb-4">
        <h6 class="mb-3">
            <i class="fas fa-hashtag fa-sm mr-2"></i>
            <?= l('products.gs1.batch_serial_info') ?>
        </h6>
            <div class="row">
                <!-- Batch/Lot Number (10) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="batch_lot_number">
                            <i class="fas fa-fw fa-layer-group fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.batch_lot_number_10') ?>
                        </label>
                        <input type="text" id="batch_lot_number" name="batch_lot_number" class="form-control <?= \SeeGap\Alerts::has_field_errors('batch_lot_number') ? 'is-invalid' : null ?>" value="<?= $data->product->batch_lot_number ?>" maxlength="20" />
                        <?= \SeeGap\Alerts::output_field_error('batch_lot_number') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (10):</strong> <?= l('products.gs1.batch_lot_number_help') ?>
                        </small>
                    </div>
                </div>

                <!-- Serial Number (21) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="serial">
                            <i class="fas fa-fw fa-fingerprint fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.serial_number_21') ?>
                        </label>
                        <input type="text" id="serial" name="serial" class="form-control <?= \SeeGap\Alerts::has_field_errors('serial') ? 'is-invalid' : null ?>" value="<?= $data->product->serial ?>" maxlength="20" />
                        <?= \SeeGap\Alerts::output_field_error('serial') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (21):</strong> <?= l('products.gs1.serial_number_help') ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Secondary Serial (250) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="secondary_serial">
                            <i class="fas fa-fw fa-fingerprint fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.secondary_serial_250') ?>
                        </label>
                        <input type="text" id="secondary_serial" name="secondary_serial" class="form-control <?= \SeeGap\Alerts::has_field_errors('secondary_serial') ? 'is-invalid' : null ?>" value="<?= $data->product->secondary_serial ?>" maxlength="30" />
                        <?= \SeeGap\Alerts::output_field_error('secondary_serial') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (250):</strong> <?= l('products.gs1.secondary_serial_help') ?>
                        </small>
                    </div>
                </div>

                <!-- Reference to Source Entity (251) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="reference_source_entity">
                            <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.reference_source_entity_251') ?>
                        </label>
                        <input type="text" id="reference_source_entity" name="reference_source_entity" class="form-control <?= \SeeGap\Alerts::has_field_errors('reference_source_entity') ? 'is-invalid' : null ?>" value="<?= $data->product->reference_source_entity ?>" maxlength="30" />
                        <?= \SeeGap\Alerts::output_field_error('reference_source_entity') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (251):</strong> <?= l('products.gs1.reference_source_entity_help') ?>
                        </small>
                    </div>
                </div>
            </div>
    </div>

    <!-- Additional Identifiers -->
    <div class="mb-4">
        <h6 class="mb-3">
            <i class="fas fa-tags fa-sm mr-2"></i>
            <?= l('products.gs1.additional_identifiers') ?>
        </h6>
            <div class="row">
                <!-- Customer Part Number (241) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="customer_part_number">
                            <i class="fas fa-fw fa-user-tag fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.customer_part_number_241') ?>
                        </label>
                        <input type="text" id="customer_part_number" name="customer_part_number" class="form-control <?= \SeeGap\Alerts::has_field_errors('customer_part_number') ? 'is-invalid' : null ?>" value="<?= $data->product->customer_part_number ?>" maxlength="30" />
                        <?= \SeeGap\Alerts::output_field_error('customer_part_number') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (241):</strong> <?= l('products.gs1.customer_part_number_help') ?>
                        </small>
                    </div>
                </div>

                <!-- Made-to-Order Variation (242) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="made_to_order_variation">
                            <i class="fas fa-fw fa-cogs fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.made_to_order_variation_242') ?>
                        </label>
                        <input type="text" id="made_to_order_variation" name="made_to_order_variation" class="form-control <?= \SeeGap\Alerts::has_field_errors('made_to_order_variation') ? 'is-invalid' : null ?>" value="<?= $data->product->made_to_order_variation ?>" maxlength="30" />
                        <?= \SeeGap\Alerts::output_field_error('made_to_order_variation') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (242):</strong> <?= l('products.gs1.made_to_order_variation_help') ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Packaging Configuration (243) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="packaging_configuration">
                            <i class="fas fa-fw fa-box-open fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.packaging_configuration_243') ?>
                        </label>
                        <input type="text" id="packaging_configuration" name="packaging_configuration" class="form-control <?= \SeeGap\Alerts::has_field_errors('packaging_configuration') ? 'is-invalid' : null ?>" value="<?= $data->product->packaging_configuration ?>" maxlength="30" />
                        <?= \SeeGap\Alerts::output_field_error('packaging_configuration') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (243):</strong> <?= l('products.gs1.packaging_configuration_help') ?>
                        </small>
                    </div>
                </div>

                <!-- GDTI (253) -->
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="gdti">
                            <i class="fas fa-fw fa-file-alt fa-sm text-muted mr-1"></i> 
                            <?= l('products.gs1.gdti_253') ?>
                        </label>
                        <input type="text" id="gdti" name="gdti" class="form-control <?= \SeeGap\Alerts::has_field_errors('gdti') ? 'is-invalid' : null ?>" value="<?= $data->product->gdti ?>" maxlength="30" />
                        <?= \SeeGap\Alerts::output_field_error('gdti') ?>
                        <small class="form-text text-muted">
                            <strong>GS1 AI (253):</strong> <?= l('products.gs1.gdti_help') ?>
                        </small>
                    </div>
                </div>
            </div>
    </div>

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
