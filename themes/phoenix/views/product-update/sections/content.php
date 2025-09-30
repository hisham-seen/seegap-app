<?php defined('SEEGAP') || die() ?>

<input type="hidden" name="section" value="content" />

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-list-alt text-primary mr-2"></i>
        <?= l('products.content_compliance_section') ?>
    </h5>
    <small class="text-muted"><?= l('products.content_compliance_description') ?></small>
</div>

<!-- Product Content Information -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-info-circle fa-sm mr-2"></i>
        <?= l('products.product_content') ?>
    </h6>
    <div class="row">
        <!-- Product Description -->
        <div class="col-12 mb-3">
            <label for="product_description" class="form-label">
                <i class="fas fa-fw fa-align-left fa-sm text-muted mr-1"></i>
                <?= l('products.product_description') ?>
            </label>
            <textarea 
                id="product_description" 
                name="product_description" 
                class="form-control" 
                rows="4"
                placeholder="<?= l('products.product_description_placeholder') ?>"
            ><?= $data->product->product_description ?? '' ?></textarea>
            <div class="form-text"><?= l('products.product_description_help') ?></div>
        </div>

        <!-- Ingredients List -->
        <div class="col-12 mb-3">
            <label for="ingredients" class="form-label">
                <i class="fas fa-fw fa-list-ul fa-sm text-muted mr-1"></i>
                <?= l('products.ingredients') ?>
            </label>
            <textarea 
                id="ingredients" 
                name="ingredients" 
                class="form-control" 
                rows="4"
                placeholder="<?= l('products.ingredients_placeholder') ?>"
            ><?= $data->product->ingredients ?? '' ?></textarea>
            <div class="form-text"><?= l('products.ingredients_help') ?></div>
        </div>

        <!-- Allergen Information -->
        <div class="col-12 mb-3">
            <label for="allergen_info" class="form-label">
                <i class="fas fa-fw fa-exclamation-triangle fa-sm text-muted mr-1"></i>
                <?= l('products.allergen_info') ?>
            </label>
            <textarea 
                id="allergen_info" 
                name="allergen_info" 
                class="form-control" 
                rows="3"
                placeholder="<?= l('products.allergen_info_placeholder') ?>"
            ><?= $data->product->allergen_info ?? '' ?></textarea>
            <div class="form-text"><?= l('products.allergen_info_help') ?></div>
        </div>

        <!-- Nutritional Information -->
        <div class="col-12 mb-3">
            <label for="nutritional_info" class="form-label">
                <i class="fas fa-fw fa-apple-alt fa-sm text-muted mr-1"></i>
                <?= l('products.nutritional_info') ?>
            </label>
            <textarea 
                id="nutritional_info" 
                name="nutritional_info" 
                class="form-control" 
                rows="4"
                placeholder="<?= l('products.nutritional_info_placeholder') ?>"
            ><?= $data->product->nutritional_info ?? '' ?></textarea>
            <div class="form-text"><?= l('products.nutritional_info_help') ?></div>
        </div>
    </div>
</div>

<!-- Compliance & Certifications -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-certificate fa-sm mr-2"></i>
        <?= l('products.compliance_certifications') ?>
    </h6>
    <div class="row">
        <!-- Organic Certification -->
        <div class="col-lg-6 mb-3">
            <label for="organic_certification" class="form-label">
                <i class="fas fa-fw fa-leaf fa-sm text-muted mr-1"></i>
                <?= l('products.organic_certification') ?>
            </label>
            <select 
                id="organic_certification" 
                name="organic_certification" 
                class="form-control"
            >
                <option value=""><?= l('products.organic_certification_placeholder') ?></option>
                <option value="eu_organic" <?= ($data->product->organic_certification ?? '') === 'eu_organic' ? 'selected' : '' ?>>EU Organic</option>
                <option value="usda_organic" <?= ($data->product->organic_certification ?? '') === 'usda_organic' ? 'selected' : '' ?>>USDA Organic</option>
                <option value="soil_association" <?= ($data->product->organic_certification ?? '') === 'soil_association' ? 'selected' : '' ?>>Soil Association</option>
                <option value="demeter" <?= ($data->product->organic_certification ?? '') === 'demeter' ? 'selected' : '' ?>>Demeter Biodynamic</option>
                <option value="other" <?= ($data->product->organic_certification ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
            <div class="form-text"><?= l('products.organic_certification_help') ?></div>
        </div>

        <!-- Fair Trade Certification -->
        <div class="col-lg-6 mb-3">
            <label for="fair_trade_certification" class="form-label">
                <i class="fas fa-fw fa-handshake fa-sm text-muted mr-1"></i>
                <?= l('products.fair_trade_certification') ?>
            </label>
            <select 
                id="fair_trade_certification" 
                name="fair_trade_certification" 
                class="form-control"
            >
                <option value=""><?= l('products.fair_trade_certification_placeholder') ?></option>
                <option value="fairtrade_international" <?= ($data->product->fair_trade_certification ?? '') === 'fairtrade_international' ? 'selected' : '' ?>>Fairtrade International</option>
                <option value="fair_trade_usa" <?= ($data->product->fair_trade_certification ?? '') === 'fair_trade_usa' ? 'selected' : '' ?>>Fair Trade USA</option>
                <option value="rainforest_alliance" <?= ($data->product->fair_trade_certification ?? '') === 'rainforest_alliance' ? 'selected' : '' ?>>Rainforest Alliance</option>
                <option value="other" <?= ($data->product->fair_trade_certification ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
            <div class="form-text"><?= l('products.fair_trade_certification_help') ?></div>
        </div>

        <!-- Halal Certification -->
        <div class="col-lg-6 mb-3">
            <div class="custom-control custom-checkbox">
                <input 
                    class="custom-control-input" 
                    type="checkbox" 
                    id="halal_certified" 
                    name="halal_certified" 
                    value="1"
                    <?= ($data->product->halal_certified ?? '') ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="halal_certified">
                    <i class="fas fa-fw fa-moon fa-sm text-muted mr-1"></i>
                    <?= l('products.halal_certified') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.halal_certified_help') ?></div>
        </div>

        <!-- Kosher Certification -->
        <div class="col-lg-6 mb-3">
            <div class="custom-control custom-checkbox">
                <input 
                    class="custom-control-input" 
                    type="checkbox" 
                    id="kosher_certified" 
                    name="kosher_certified" 
                    value="1"
                    <?= ($data->product->kosher_certified ?? '') ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="kosher_certified">
                    <i class="fas fa-fw fa-star-of-david fa-sm text-muted mr-1"></i>
                    <?= l('products.kosher_certified') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.kosher_certified_help') ?></div>
        </div>

        <!-- Gluten Free -->
        <div class="col-lg-6 mb-3">
            <div class="custom-control custom-checkbox">
                <input 
                    class="custom-control-input" 
                    type="checkbox" 
                    id="gluten_free" 
                    name="gluten_free" 
                    value="1"
                    <?= ($data->product->gluten_free ?? '') ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="gluten_free">
                    <i class="fas fa-fw fa-ban fa-sm text-muted mr-1"></i>
                    <?= l('products.gluten_free') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.gluten_free_help') ?></div>
        </div>

        <!-- Vegan -->
        <div class="col-lg-6 mb-3">
            <div class="custom-control custom-checkbox">
                <input 
                    class="custom-control-input" 
                    type="checkbox" 
                    id="vegan" 
                    name="vegan" 
                    value="1"
                    <?= ($data->product->vegan ?? '') ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="vegan">
                    <i class="fas fa-fw fa-seedling fa-sm text-muted mr-1"></i>
                    <?= l('products.vegan') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.vegan_help') ?></div>
        </div>

        <!-- Vegetarian -->
        <div class="col-lg-6 mb-3">
            <div class="custom-control custom-checkbox">
                <input 
                    class="custom-control-input" 
                    type="checkbox" 
                    id="vegetarian" 
                    name="vegetarian" 
                    value="1"
                    <?= ($data->product->vegetarian ?? '') ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="vegetarian">
                    <i class="fas fa-fw fa-carrot fa-sm text-muted mr-1"></i>
                    <?= l('products.vegetarian') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.vegetarian_help') ?></div>
        </div>

        <!-- Non-GMO -->
        <div class="col-lg-6 mb-3">
            <div class="custom-control custom-checkbox">
                <input 
                    class="custom-control-input" 
                    type="checkbox" 
                    id="non_gmo" 
                    name="non_gmo" 
                    value="1"
                    <?= ($data->product->non_gmo ?? '') ? 'checked' : '' ?>
                >
                <label class="custom-control-label" for="non_gmo">
                    <i class="fas fa-fw fa-dna fa-sm text-muted mr-1"></i>
                    <?= l('products.non_gmo') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.non_gmo_help') ?></div>
        </div>
    </div>
</div>

<!-- Usage & Care Instructions -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-book fa-sm mr-2"></i>
        <?= l('products.usage_care_instructions') ?>
    </h6>
    <div class="row">
        <!-- Usage Instructions -->
        <div class="col-lg-6 mb-3">
            <label for="usage_instructions" class="form-label">
                <i class="fas fa-fw fa-play-circle fa-sm text-muted mr-1"></i>
                <?= l('products.usage_instructions') ?>
            </label>
            <textarea 
                id="usage_instructions" 
                name="usage_instructions" 
                class="form-control" 
                rows="4"
                placeholder="<?= l('products.usage_instructions_placeholder') ?>"
            ><?= $data->product->usage_instructions ?? '' ?></textarea>
            <div class="form-text"><?= l('products.usage_instructions_help') ?></div>
        </div>

        <!-- Care Instructions -->
        <div class="col-lg-6 mb-3">
            <label for="care_instructions" class="form-label">
                <i class="fas fa-fw fa-heart fa-sm text-muted mr-1"></i>
                <?= l('products.care_instructions') ?>
            </label>
            <textarea 
                id="care_instructions" 
                name="care_instructions" 
                class="form-control" 
                rows="4"
                placeholder="<?= l('products.care_instructions_placeholder') ?>"
            ><?= $data->product->care_instructions ?? '' ?></textarea>
            <div class="form-text"><?= l('products.care_instructions_help') ?></div>
        </div>

        <!-- Storage Instructions -->
        <div class="col-12 mb-3">
            <label for="storage_instructions" class="form-label">
                <i class="fas fa-fw fa-archive fa-sm text-muted mr-1"></i>
                <?= l('products.storage_instructions') ?>
            </label>
            <textarea 
                id="storage_instructions" 
                name="storage_instructions" 
                class="form-control" 
                rows="3"
                placeholder="<?= l('products.storage_instructions_placeholder') ?>"
            ><?= $data->product->storage_instructions ?? '' ?></textarea>
            <div class="form-text"><?= l('products.storage_instructions_help') ?></div>
        </div>

        <!-- Warning Information -->
        <div class="col-12 mb-3">
            <label for="warning_info" class="form-label">
                <i class="fas fa-fw fa-exclamation-triangle fa-sm text-muted mr-1"></i>
                <?= l('products.warning_info') ?>
            </label>
            <textarea 
                id="warning_info" 
                name="warning_info" 
                class="form-control" 
                rows="3"
                placeholder="<?= l('products.warning_info_placeholder') ?>"
            ><?= $data->product->warning_info ?? '' ?></textarea>
            <div class="form-text"><?= l('products.warning_info_help') ?></div>
        </div>
    </div>
</div>

<!-- Additional Information -->
<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle mr-2"></i>
    <strong><?= l('products.content_note_title') ?>:</strong>
    <?= l('products.content_note_description') ?>
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
