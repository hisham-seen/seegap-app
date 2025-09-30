<?php defined('SEEGAP') || die() ?>

<input type="hidden" name="section" value="gs1-digital-passport" />

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-passport text-primary mr-2"></i>
        <?= l('products.sections.gs1_digital_passport') ?>
    </h5>
    <small class="text-muted"><?= l('products.sections.gs1_digital_passport_description') ?></small>
</div>

<!-- Digital Passport Overview -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-info-circle fa-sm mr-2"></i>
        <?= l('products.gs1.passport_overview') ?>
    </h6>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="alert alert-info">
                <i class="fas fa-lightbulb mr-2"></i>
                <strong><?= l('products.gs1.what_is_digital_passport') ?></strong>
                <br>
                <small class="text-muted"><?= l('products.gs1.digital_passport_explanation') ?></small>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="text-center p-3 border rounded">
                <i class="fas fa-leaf fa-2x text-success mb-2"></i>
                <h6><?= l('products.gs1.sustainability') ?></h6>
                <small class="text-muted"><?= l('products.gs1.sustainability_desc') ?></small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="text-center p-3 border rounded">
                <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                <h6><?= l('products.gs1.compliance') ?></h6>
                <small class="text-muted"><?= l('products.gs1.compliance_desc') ?></small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="text-center p-3 border rounded">
                <i class="fas fa-history fa-2x text-warning mb-2"></i>
                <h6><?= l('products.gs1.traceability') ?></h6>
                <small class="text-muted"><?= l('products.gs1.traceability_desc') ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Sustainability Information -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-leaf fa-sm mr-2"></i>
        <?= l('products.gs1.sustainability_information') ?>
    </h6>
    <div class="row">
        <!-- Carbon Footprint -->
        <div class="col-lg-6 mb-3">
            <label for="carbon_footprint" class="form-label">
                <i class="fas fa-fw fa-smog fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.carbon_footprint') ?>
            </label>
            <div class="input-group">
                <input 
                    type="number" 
                    id="carbon_footprint" 
                    name="carbon_footprint" 
                    class="form-control" 
                    step="0.01" 
                    min="0" 
                    value="<?= $data->product->carbon_footprint ?? '' ?>" 
                    placeholder="0.00"
                >
                <div class="input-group-append">
                    <span class="input-group-text">kg CO₂e</span>
                </div>
            </div>
            <div class="form-text"><?= l('products.gs1.carbon_footprint_help') ?></div>
        </div>

        <!-- Water Usage -->
        <div class="col-lg-6 mb-3">
            <label for="water_usage" class="form-label">
                <i class="fas fa-fw fa-tint fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.water_usage') ?>
            </label>
            <div class="input-group">
                <input 
                    type="number" 
                    id="water_usage" 
                    name="water_usage" 
                    class="form-control" 
                    step="0.01" 
                    min="0" 
                    value="<?= $data->product->water_usage ?? '' ?>" 
                    placeholder="0.00"
                >
                <div class="input-group-append">
                    <span class="input-group-text">L</span>
                </div>
            </div>
            <div class="form-text"><?= l('products.gs1.water_usage_help') ?></div>
        </div>

        <!-- Renewable Energy Percentage -->
        <div class="col-lg-6 mb-3">
            <label for="renewable_energy_percentage" class="form-label">
                <i class="fas fa-fw fa-solar-panel fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.renewable_energy_percentage') ?>
            </label>
            <div class="input-group">
                <input 
                    type="number" 
                    id="renewable_energy_percentage" 
                    name="renewable_energy_percentage" 
                    class="form-control" 
                    min="0" 
                    max="100" 
                    value="<?= $data->product->renewable_energy_percentage ?? '' ?>" 
                    placeholder="0"
                >
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
            <div class="form-text"><?= l('products.gs1.renewable_energy_percentage_help') ?></div>
        </div>

        <!-- Recyclability Score -->
        <div class="col-lg-6 mb-3">
            <label for="recyclability_score" class="form-label">
                <i class="fas fa-fw fa-recycle fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.recyclability_score') ?>
            </label>
            <select id="recyclability_score" name="recyclability_score" class="form-control">
                <option value=""><?= l('global.none') ?></option>
                <option value="A" <?= ($data->product->recyclability_score ?? '') === 'A' ? 'selected' : '' ?>>A - <?= l('products.gs1.fully_recyclable') ?></option>
                <option value="B" <?= ($data->product->recyclability_score ?? '') === 'B' ? 'selected' : '' ?>>B - <?= l('products.gs1.mostly_recyclable') ?></option>
                <option value="C" <?= ($data->product->recyclability_score ?? '') === 'C' ? 'selected' : '' ?>>C - <?= l('products.gs1.partially_recyclable') ?></option>
                <option value="D" <?= ($data->product->recyclability_score ?? '') === 'D' ? 'selected' : '' ?>>D - <?= l('products.gs1.limited_recyclability') ?></option>
                <option value="E" <?= ($data->product->recyclability_score ?? '') === 'E' ? 'selected' : '' ?>>E - <?= l('products.gs1.not_recyclable') ?></option>
            </select>
            <div class="form-text"><?= l('products.gs1.recyclability_score_help') ?></div>
        </div>

        <!-- Sustainability Certifications -->
        <div class="col-12 mb-3">
            <label for="sustainability_certifications" class="form-label">
                <i class="fas fa-fw fa-certificate fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.sustainability_certifications') ?>
            </label>
            <textarea 
                id="sustainability_certifications" 
                name="sustainability_certifications" 
                class="form-control" 
                rows="3" 
                placeholder="<?= l('products.gs1.sustainability_certifications_placeholder') ?>"
            ><?= $data->product->sustainability_certifications ?? '' ?></textarea>
            <div class="form-text"><?= l('products.gs1.sustainability_certifications_help') ?></div>
        </div>
    </div>
</div>

<!-- Supply Chain & Traceability -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-route fa-sm mr-2"></i>
        <?= l('products.gs1.supply_chain_traceability') ?>
    </h6>
    <div class="row">
        <!-- Supply Chain Transparency Score -->
        <div class="col-lg-6 mb-3">
            <label for="supply_chain_transparency" class="form-label">
                <i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.supply_chain_transparency') ?>
            </label>
            <select id="supply_chain_transparency" name="supply_chain_transparency" class="form-control">
                <option value=""><?= l('global.none') ?></option>
                <option value="high" <?= ($data->product->supply_chain_transparency ?? '') === 'high' ? 'selected' : '' ?>><?= l('products.gs1.transparency_high') ?></option>
                <option value="medium" <?= ($data->product->supply_chain_transparency ?? '') === 'medium' ? 'selected' : '' ?>><?= l('products.gs1.transparency_medium') ?></option>
                <option value="low" <?= ($data->product->supply_chain_transparency ?? '') === 'low' ? 'selected' : '' ?>><?= l('products.gs1.transparency_low') ?></option>
            </select>
            <div class="form-text"><?= l('products.gs1.supply_chain_transparency_help') ?></div>
        </div>

        <!-- Ethical Sourcing -->
        <div class="col-lg-6 mb-3">
            <label for="ethical_sourcing" class="form-label">
                <i class="fas fa-fw fa-handshake fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.ethical_sourcing') ?>
            </label>
            <select id="ethical_sourcing" name="ethical_sourcing" class="form-control">
                <option value=""><?= l('global.none') ?></option>
                <option value="certified" <?= ($data->product->ethical_sourcing ?? '') === 'certified' ? 'selected' : '' ?>><?= l('products.gs1.ethical_certified') ?></option>
                <option value="verified" <?= ($data->product->ethical_sourcing ?? '') === 'verified' ? 'selected' : '' ?>><?= l('products.gs1.ethical_verified') ?></option>
                <option value="self_declared" <?= ($data->product->ethical_sourcing ?? '') === 'self_declared' ? 'selected' : '' ?>><?= l('products.gs1.ethical_self_declared') ?></option>
            </select>
            <div class="form-text"><?= l('products.gs1.ethical_sourcing_help') ?></div>
        </div>

        <!-- Key Suppliers -->
        <div class="col-12 mb-3">
            <label for="key_suppliers" class="form-label">
                <i class="fas fa-fw fa-industry fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.key_suppliers') ?>
            </label>
            <textarea 
                id="key_suppliers" 
                name="key_suppliers" 
                class="form-control" 
                rows="3" 
                placeholder="<?= l('products.gs1.key_suppliers_placeholder') ?>"
            ><?= $data->product->key_suppliers ?? '' ?></textarea>
            <div class="form-text"><?= l('products.gs1.key_suppliers_help') ?></div>
        </div>

        <!-- Blockchain Verification -->
        <div class="col-12 mb-3">
            <div class="custom-control custom-switch">
                <input 
                    type="checkbox" 
                    id="blockchain_verified" 
                    name="blockchain_verified" 
                    class="custom-control-input" 
                    <?= ($data->product->blockchain_verified ?? false) ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="blockchain_verified">
                    <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i>
                    <?= l('products.gs1.blockchain_verified') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.gs1.blockchain_verified_help') ?></div>
        </div>
    </div>
</div>

<!-- Compliance & Safety -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-shield-alt fa-sm mr-2"></i>
        <?= l('products.gs1.compliance_safety') ?>
    </h6>
    <div class="row">
        <!-- Regulatory Compliance -->
        <div class="col-lg-6 mb-3">
            <label for="regulatory_compliance" class="form-label">
                <i class="fas fa-fw fa-gavel fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.regulatory_compliance') ?>
            </label>
            <textarea 
                id="regulatory_compliance" 
                name="regulatory_compliance" 
                class="form-control" 
                rows="3" 
                placeholder="<?= l('products.gs1.regulatory_compliance_placeholder') ?>"
            ><?= $data->product->regulatory_compliance ?? '' ?></textarea>
            <div class="form-text"><?= l('products.gs1.regulatory_compliance_help') ?></div>
        </div>

        <!-- Safety Standards -->
        <div class="col-lg-6 mb-3">
            <label for="safety_standards" class="form-label">
                <i class="fas fa-fw fa-hard-hat fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.safety_standards') ?>
            </label>
            <textarea 
                id="safety_standards" 
                name="safety_standards" 
                class="form-control" 
                rows="3" 
                placeholder="<?= l('products.gs1.safety_standards_placeholder') ?>"
            ><?= $data->product->safety_standards ?? '' ?></textarea>
            <div class="form-text"><?= l('products.gs1.safety_standards_help') ?></div>
        </div>

        <!-- Quality Certifications -->
        <div class="col-12 mb-3">
            <label for="quality_certifications" class="form-label">
                <i class="fas fa-fw fa-award fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.quality_certifications') ?>
            </label>
            <textarea 
                id="quality_certifications" 
                name="quality_certifications" 
                class="form-control" 
                rows="2" 
                placeholder="<?= l('products.gs1.quality_certifications_placeholder') ?>"
            ><?= $data->product->quality_certifications ?? '' ?></textarea>
            <div class="form-text"><?= l('products.gs1.quality_certifications_help') ?></div>
        </div>
    </div>
</div>

<!-- Product Lifecycle -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-history fa-sm mr-2"></i>
        <?= l('products.gs1.product_lifecycle') ?>
    </h6>
    <div class="row">
        <!-- Lifecycle Stage -->
        <div class="col-lg-6 mb-3">
            <label for="lifecycle_stage" class="form-label">
                <i class="fas fa-fw fa-circle-notch fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.lifecycle_stage') ?>
            </label>
            <select id="lifecycle_stage" name="lifecycle_stage" class="form-control">
                <option value=""><?= l('global.select') ?></option>
                <option value="development" <?= ($data->product->lifecycle_stage ?? '') === 'development' ? 'selected' : '' ?>><?= l('products.gs1.stage_development') ?></option>
                <option value="production" <?= ($data->product->lifecycle_stage ?? '') === 'production' ? 'selected' : '' ?>><?= l('products.gs1.stage_production') ?></option>
                <option value="active" <?= ($data->product->lifecycle_stage ?? '') === 'active' ? 'selected' : '' ?>><?= l('products.gs1.stage_active') ?></option>
                <option value="mature" <?= ($data->product->lifecycle_stage ?? '') === 'mature' ? 'selected' : '' ?>><?= l('products.gs1.stage_mature') ?></option>
                <option value="declining" <?= ($data->product->lifecycle_stage ?? '') === 'declining' ? 'selected' : '' ?>><?= l('products.gs1.stage_declining') ?></option>
                <option value="discontinued" <?= ($data->product->lifecycle_stage ?? '') === 'discontinued' ? 'selected' : '' ?>><?= l('products.gs1.stage_discontinued') ?></option>
            </select>
            <div class="form-text"><?= l('products.gs1.lifecycle_stage_help') ?></div>
        </div>

        <!-- Expected Lifespan -->
        <div class="col-lg-6 mb-3">
            <label for="expected_lifespan" class="form-label">
                <i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.expected_lifespan') ?>
            </label>
            <div class="input-group">
                <input 
                    type="number" 
                    id="expected_lifespan" 
                    name="expected_lifespan" 
                    class="form-control" 
                    min="0" 
                    value="<?= $data->product->expected_lifespan ?? '' ?>" 
                    placeholder="0"
                >
                <div class="input-group-append">
                    <select name="lifespan_unit" class="form-control">
                        <option value="days" <?= ($data->product->lifespan_unit ?? '') === 'days' ? 'selected' : '' ?>><?= l('products.gs1.days') ?></option>
                        <option value="months" <?= ($data->product->lifespan_unit ?? '') === 'months' ? 'selected' : '' ?>><?= l('products.gs1.months') ?></option>
                        <option value="years" <?= ($data->product->lifespan_unit ?? 'years') === 'years' ? 'selected' : '' ?>><?= l('products.gs1.years') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-text"><?= l('products.gs1.expected_lifespan_help') ?></div>
        </div>

        <!-- End of Life Instructions -->
        <div class="col-12 mb-3">
            <label for="end_of_life_instructions" class="form-label">
                <i class="fas fa-fw fa-trash-alt fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.end_of_life_instructions') ?>
            </label>
            <textarea 
                id="end_of_life_instructions" 
                name="end_of_life_instructions" 
                class="form-control" 
                rows="3" 
                placeholder="<?= l('products.gs1.end_of_life_instructions_placeholder') ?>"
            ><?= $data->product->end_of_life_instructions ?? '' ?></textarea>
            <div class="form-text"><?= l('products.gs1.end_of_life_instructions_help') ?></div>
        </div>
    </div>
</div>

<!-- Digital Passport Settings -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-cogs fa-sm mr-2"></i>
        <?= l('products.gs1.passport_settings') ?>
    </h6>
    <div class="row">
        <!-- Public Visibility -->
        <div class="col-12 mb-3">
            <div class="custom-control custom-switch">
                <input 
                    type="checkbox" 
                    id="passport_public" 
                    name="passport_public" 
                    class="custom-control-input" 
                    <?= ($data->product->passport_public ?? true) ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="passport_public">
                    <i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i>
                    <?= l('products.gs1.make_passport_public') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.gs1.make_passport_public_help') ?></div>
        </div>

        <!-- Include in Search Engines -->
        <div class="col-12 mb-3">
            <div class="custom-control custom-switch">
                <input 
                    type="checkbox" 
                    id="passport_seo" 
                    name="passport_seo" 
                    class="custom-control-input" 
                    <?= ($data->product->passport_seo ?? true) ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="passport_seo">
                    <i class="fas fa-fw fa-search fa-sm text-muted mr-1"></i>
                    <?= l('products.gs1.include_in_search_engines') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.gs1.include_in_search_engines_help') ?></div>
        </div>

        <!-- Last Updated -->
        <div class="col-lg-6 mb-3">
            <label for="passport_last_updated" class="form-label">
                <i class="fas fa-fw fa-calendar-check fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.passport_last_updated') ?>
            </label>
            <input 
                type="datetime-local" 
                id="passport_last_updated" 
                name="passport_last_updated" 
                class="form-control" 
                value="<?= $data->product->passport_last_updated ?? date('Y-m-d\TH:i') ?>"
            >
            <div class="form-text"><?= l('products.gs1.passport_last_updated_help') ?></div>
        </div>

        <!-- Data Verification Status -->
        <div class="col-lg-6 mb-3">
            <label for="data_verification_status" class="form-label">
                <i class="fas fa-fw fa-check-circle fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.data_verification_status') ?>
            </label>
            <select id="data_verification_status" name="data_verification_status" class="form-control">
                <option value="unverified" <?= ($data->product->data_verification_status ?? 'unverified') === 'unverified' ? 'selected' : '' ?>><?= l('products.gs1.status_unverified') ?></option>
                <option value="self_verified" <?= ($data->product->data_verification_status ?? '') === 'self_verified' ? 'selected' : '' ?>><?= l('products.gs1.status_self_verified') ?></option>
                <option value="third_party_verified" <?= ($data->product->data_verification_status ?? '') === 'third_party_verified' ? 'selected' : '' ?>><?= l('products.gs1.status_third_party_verified') ?></option>
                <option value="certified" <?= ($data->product->data_verification_status ?? '') === 'certified' ? 'selected' : '' ?>><?= l('products.gs1.status_certified') ?></option>
            </select>
            <div class="form-text"><?= l('products.gs1.data_verification_status_help') ?></div>
        </div>
    </div>
</div>

<!-- Additional Information -->
<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle mr-2"></i>
    <strong><?= l('products.gs1.passport_note_title') ?>:</strong>
    <?= l('products.gs1.passport_note_description') ?>
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
