<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-calendar-alt text-primary me-2"></i>
            <?= l('products.gs1_attributes_section') ?>
        </h5>
        <p class="text-muted small mb-0"><?= l('products.gs1_attributes_description') ?></p>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Production Date (AI 11) -->
            <div class="col-lg-6 mb-3">
                <label for="production_date" class="form-label">
                    <?= l('products.production_date') ?>
                    <span class="text-muted small">(AI 11)</span>
                </label>
                <input 
                    type="date" 
                    id="production_date" 
                    name="production_date" 
                    class="form-control" 
                    value="<?= $data->product->production_date ?? '' ?>"
                    placeholder="<?= l('products.production_date_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.production_date_help') ?></div>
            </div>

            <!-- Due Date (AI 12) -->
            <div class="col-lg-6 mb-3">
                <label for="due_date" class="form-label">
                    <?= l('products.due_date') ?>
                    <span class="text-muted small">(AI 12)</span>
                </label>
                <input 
                    type="date" 
                    id="due_date" 
                    name="due_date" 
                    class="form-control" 
                    value="<?= $data->product->due_date ?? '' ?>"
                    placeholder="<?= l('products.due_date_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.due_date_help') ?></div>
            </div>

            <!-- Packaging Date (AI 13) -->
            <div class="col-lg-6 mb-3">
                <label for="packaging_date" class="form-label">
                    <?= l('products.packaging_date') ?>
                    <span class="text-muted small">(AI 13)</span>
                </label>
                <input 
                    type="date" 
                    id="packaging_date" 
                    name="packaging_date" 
                    class="form-control" 
                    value="<?= $data->product->packaging_date ?? '' ?>"
                    placeholder="<?= l('products.packaging_date_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.packaging_date_help') ?></div>
            </div>

            <!-- Best Before Date (AI 15) -->
            <div class="col-lg-6 mb-3">
                <label for="best_before_date" class="form-label">
                    <?= l('products.best_before_date') ?>
                    <span class="text-muted small">(AI 15)</span>
                </label>
                <input 
                    type="date" 
                    id="best_before_date" 
                    name="best_before_date" 
                    class="form-control" 
                    value="<?= $data->product->best_before_date ?? '' ?>"
                    placeholder="<?= l('products.best_before_date_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.best_before_date_help') ?></div>
            </div>

            <!-- Sell By Date (AI 16) -->
            <div class="col-lg-6 mb-3">
                <label for="sell_by_date" class="form-label">
                    <?= l('products.sell_by_date') ?>
                    <span class="text-muted small">(AI 16)</span>
                </label>
                <input 
                    type="date" 
                    id="sell_by_date" 
                    name="sell_by_date" 
                    class="form-control" 
                    value="<?= $data->product->sell_by_date ?? '' ?>"
                    placeholder="<?= l('products.sell_by_date_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.sell_by_date_help') ?></div>
            </div>

            <!-- Expiration Date (AI 17) -->
            <div class="col-lg-6 mb-3">
                <label for="expiration_date" class="form-label">
                    <?= l('products.expiration_date') ?>
                    <span class="text-muted small">(AI 17)</span>
                </label>
                <input 
                    type="date" 
                    id="expiration_date" 
                    name="expiration_date" 
                    class="form-control" 
                    value="<?= $data->product->expiration_date ?? '' ?>"
                    placeholder="<?= l('products.expiration_date_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.expiration_date_help') ?></div>
            </div>
        </div>

        <!-- Additional Attributes Section -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-tags me-2"></i>
            <?= l('products.additional_attributes') ?>
        </h6>

        <div class="row">
            <!-- Customer Part Number (AI 241) -->
            <div class="col-lg-6 mb-3">
                <label for="customer_part_number" class="form-label">
                    <?= l('products.customer_part_number') ?>
                    <span class="text-muted small">(AI 241)</span>
                </label>
                <input 
                    type="text" 
                    id="customer_part_number" 
                    name="customer_part_number" 
                    class="form-control" 
                    value="<?= $data->product->customer_part_number ?? '' ?>"
                    placeholder="<?= l('products.customer_part_number_placeholder') ?>"
                    maxlength="30"
                >
                <div class="form-text"><?= l('products.customer_part_number_help') ?></div>
            </div>

            <!-- Made-to-Order Variation Number (AI 242) -->
            <div class="col-lg-6 mb-3">
                <label for="made_to_order_variation" class="form-label">
                    <?= l('products.made_to_order_variation') ?>
                    <span class="text-muted small">(AI 242)</span>
                </label>
                <input 
                    type="text" 
                    id="made_to_order_variation" 
                    name="made_to_order_variation" 
                    class="form-control" 
                    value="<?= $data->product->made_to_order_variation ?? '' ?>"
                    placeholder="<?= l('products.made_to_order_variation_placeholder') ?>"
                    maxlength="6"
                >
                <div class="form-text"><?= l('products.made_to_order_variation_help') ?></div>
            </div>

            <!-- Packaging Configuration (AI 243) -->
            <div class="col-lg-6 mb-3">
                <label for="packaging_configuration" class="form-label">
                    <?= l('products.packaging_configuration') ?>
                    <span class="text-muted small">(AI 243)</span>
                </label>
                <input 
                    type="text" 
                    id="packaging_configuration" 
                    name="packaging_configuration" 
                    class="form-control" 
                    value="<?= $data->product->packaging_configuration ?? '' ?>"
                    placeholder="<?= l('products.packaging_configuration_placeholder') ?>"
                    maxlength="20"
                >
                <div class="form-text"><?= l('products.packaging_configuration_help') ?></div>
            </div>

            <!-- Secondary Serial Number (AI 250) -->
            <div class="col-lg-6 mb-3">
                <label for="secondary_serial" class="form-label">
                    <?= l('products.secondary_serial') ?>
                    <span class="text-muted small">(AI 250)</span>
                </label>
                <input 
                    type="text" 
                    id="secondary_serial" 
                    name="secondary_serial" 
                    class="form-control" 
                    value="<?= $data->product->secondary_serial ?? '' ?>"
                    placeholder="<?= l('products.secondary_serial_placeholder') ?>"
                    maxlength="30"
                >
                <div class="form-text"><?= l('products.secondary_serial_help') ?></div>
            </div>

            <!-- Reference to Source Entity (AI 251) -->
            <div class="col-lg-6 mb-3">
                <label for="reference_to_source" class="form-label">
                    <?= l('products.reference_to_source') ?>
                    <span class="text-muted small">(AI 251)</span>
                </label>
                <input 
                    type="text" 
                    id="reference_to_source" 
                    name="reference_to_source" 
                    class="form-control" 
                    value="<?= $data->product->reference_to_source ?? '' ?>"
                    placeholder="<?= l('products.reference_to_source_placeholder') ?>"
                    maxlength="30"
                >
                <div class="form-text"><?= l('products.reference_to_source_help') ?></div>
            </div>

            <!-- Global Document Type Identifier (AI 253) -->
            <div class="col-lg-6 mb-3">
                <label for="global_document_type_id" class="form-label">
                    <?= l('products.global_document_type_id') ?>
                    <span class="text-muted small">(AI 253)</span>
                </label>
                <input 
                    type="text" 
                    id="global_document_type_id" 
                    name="global_document_type_id" 
                    class="form-control" 
                    value="<?= $data->product->global_document_type_id ?? '' ?>"
                    placeholder="<?= l('products.global_document_type_id_placeholder') ?>"
                    maxlength="17"
                >
                <div class="form-text"><?= l('products.global_document_type_id_help') ?></div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-4">
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>
                <?= l('global.update') ?>
            </button>
        </div>
    </div>
</div>
