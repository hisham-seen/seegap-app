<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-ruler text-primary me-2"></i>
            <?= l('products.gs1_measurements_section') ?>
        </h5>
        <p class="text-muted small mb-0"><?= l('products.gs1_measurements_description') ?></p>
    </div>
    <div class="card-body">
        <!-- Weight Measurements -->
        <h6 class="text-primary mb-3">
            <i class="fas fa-weight me-2"></i>
            <?= l('products.weight_measurements') ?>
        </h6>
        <div class="row">
            <!-- Net Weight (AI 310n) -->
            <div class="col-lg-4 mb-3">
                <label for="net_weight_kg" class="form-label">
                    <?= l('products.net_weight_kg') ?>
                    <span class="text-muted small">(AI 3103)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="net_weight_kg" 
                        name="net_weight_kg" 
                        class="form-control" 
                        value="<?= $data->product->net_weight_kg ?? '' ?>"
                        placeholder="<?= l('products.net_weight_kg_placeholder') ?>"
                        step="0.001"
                        min="0"
                    >
                    <span class="input-group-text">kg</span>
                </div>
                <div class="form-text"><?= l('products.net_weight_kg_help') ?></div>
            </div>

            <!-- Length (AI 311n) -->
            <div class="col-lg-4 mb-3">
                <label for="length_m" class="form-label">
                    <?= l('products.length_m') ?>
                    <span class="text-muted small">(AI 3112)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="length_m" 
                        name="length_m" 
                        class="form-control" 
                        value="<?= $data->product->length_m ?? '' ?>"
                        placeholder="<?= l('products.length_m_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m</span>
                </div>
                <div class="form-text"><?= l('products.length_m_help') ?></div>
            </div>

            <!-- Width (AI 312n) -->
            <div class="col-lg-4 mb-3">
                <label for="width_m" class="form-label">
                    <?= l('products.width_m') ?>
                    <span class="text-muted small">(AI 3122)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="width_m" 
                        name="width_m" 
                        class="form-control" 
                        value="<?= $data->product->width_m ?? '' ?>"
                        placeholder="<?= l('products.width_m_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m</span>
                </div>
                <div class="form-text"><?= l('products.width_m_help') ?></div>
            </div>

            <!-- Height (AI 313n) -->
            <div class="col-lg-4 mb-3">
                <label for="height_m" class="form-label">
                    <?= l('products.height_m') ?>
                    <span class="text-muted small">(AI 3132)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="height_m" 
                        name="height_m" 
                        class="form-control" 
                        value="<?= $data->product->height_m ?? '' ?>"
                        placeholder="<?= l('products.height_m_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m</span>
                </div>
                <div class="form-text"><?= l('products.height_m_help') ?></div>
            </div>

            <!-- Area (AI 314n) -->
            <div class="col-lg-4 mb-3">
                <label for="area_m2" class="form-label">
                    <?= l('products.area_m2') ?>
                    <span class="text-muted small">(AI 3142)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="area_m2" 
                        name="area_m2" 
                        class="form-control" 
                        value="<?= $data->product->area_m2 ?? '' ?>"
                        placeholder="<?= l('products.area_m2_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m²</span>
                </div>
                <div class="form-text"><?= l('products.area_m2_help') ?></div>
            </div>

            <!-- Net Volume (AI 315n) -->
            <div class="col-lg-4 mb-3">
                <label for="net_volume_l" class="form-label">
                    <?= l('products.net_volume_l') ?>
                    <span class="text-muted small">(AI 3153)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="net_volume_l" 
                        name="net_volume_l" 
                        class="form-control" 
                        value="<?= $data->product->net_volume_l ?? '' ?>"
                        placeholder="<?= l('products.net_volume_l_placeholder') ?>"
                        step="0.001"
                        min="0"
                    >
                    <span class="input-group-text">L</span>
                </div>
                <div class="form-text"><?= l('products.net_volume_l_help') ?></div>
            </div>

            <!-- Gross Weight (AI 316n) -->
            <div class="col-lg-4 mb-3">
                <label for="gross_weight_kg" class="form-label">
                    <?= l('products.gross_weight_kg') ?>
                    <span class="text-muted small">(AI 3163)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="gross_weight_kg" 
                        name="gross_weight_kg" 
                        class="form-control" 
                        value="<?= $data->product->gross_weight_kg ?? '' ?>"
                        placeholder="<?= l('products.gross_weight_kg_placeholder') ?>"
                        step="0.001"
                        min="0"
                    >
                    <span class="input-group-text">kg</span>
                </div>
                <div class="form-text"><?= l('products.gross_weight_kg_help') ?></div>
            </div>
        </div>

        <!-- Logistic Measurements -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-cube me-2"></i>
            <?= l('products.logistic_measurements') ?>
        </h6>
        <div class="row">
            <!-- Logistic Weight (AI 330n) -->
            <div class="col-lg-4 mb-3">
                <label for="logistic_weight_kg" class="form-label">
                    <?= l('products.logistic_weight_kg') ?>
                    <span class="text-muted small">(AI 3303)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="logistic_weight_kg" 
                        name="logistic_weight_kg" 
                        class="form-control" 
                        value="<?= $data->product->logistic_weight_kg ?? '' ?>"
                        placeholder="<?= l('products.logistic_weight_kg_placeholder') ?>"
                        step="0.001"
                        min="0"
                    >
                    <span class="input-group-text">kg</span>
                </div>
                <div class="form-text"><?= l('products.logistic_weight_kg_help') ?></div>
            </div>

            <!-- Logistic Length -->
            <div class="col-lg-4 mb-3">
                <label for="logistic_length_m" class="form-label">
                    <?= l('products.logistic_length_m') ?>
                    <span class="text-muted small">(AI 3312)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="logistic_length_m" 
                        name="logistic_length_m" 
                        class="form-control" 
                        value="<?= $data->product->logistic_length_m ?? '' ?>"
                        placeholder="<?= l('products.logistic_length_m_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m</span>
                </div>
                <div class="form-text"><?= l('products.logistic_length_m_help') ?></div>
            </div>

            <!-- Logistic Width -->
            <div class="col-lg-4 mb-3">
                <label for="logistic_width_m" class="form-label">
                    <?= l('products.logistic_width_m') ?>
                    <span class="text-muted small">(AI 3322)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="logistic_width_m" 
                        name="logistic_width_m" 
                        class="form-control" 
                        value="<?= $data->product->logistic_width_m ?? '' ?>"
                        placeholder="<?= l('products.logistic_width_m_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m</span>
                </div>
                <div class="form-text"><?= l('products.logistic_width_m_help') ?></div>
            </div>

            <!-- Logistic Height -->
            <div class="col-lg-4 mb-3">
                <label for="logistic_height_m" class="form-label">
                    <?= l('products.logistic_height_m') ?>
                    <span class="text-muted small">(AI 3332)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="logistic_height_m" 
                        name="logistic_height_m" 
                        class="form-control" 
                        value="<?= $data->product->logistic_height_m ?? '' ?>"
                        placeholder="<?= l('products.logistic_height_m_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m</span>
                </div>
                <div class="form-text"><?= l('products.logistic_height_m_help') ?></div>
            </div>

            <!-- Logistic Area -->
            <div class="col-lg-4 mb-3">
                <label for="logistic_area_m2" class="form-label">
                    <?= l('products.logistic_area_m2') ?>
                    <span class="text-muted small">(AI 3342)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="logistic_area_m2" 
                        name="logistic_area_m2" 
                        class="form-control" 
                        value="<?= $data->product->logistic_area_m2 ?? '' ?>"
                        placeholder="<?= l('products.logistic_area_m2_placeholder') ?>"
                        step="0.01"
                        min="0"
                    >
                    <span class="input-group-text">m²</span>
                </div>
                <div class="form-text"><?= l('products.logistic_area_m2_help') ?></div>
            </div>

            <!-- Logistic Volume -->
            <div class="col-lg-4 mb-3">
                <label for="logistic_volume_l" class="form-label">
                    <?= l('products.logistic_volume_l') ?>
                    <span class="text-muted small">(AI 3353)</span>
                </label>
                <div class="input-group">
                    <input 
                        type="number" 
                        id="logistic_volume_l" 
                        name="logistic_volume_l" 
                        class="form-control" 
                        value="<?= $data->product->logistic_volume_l ?? '' ?>"
                        placeholder="<?= l('products.logistic_volume_l_placeholder') ?>"
                        step="0.001"
                        min="0"
                    >
                    <span class="input-group-text">L</span>
                </div>
                <div class="form-text"><?= l('products.logistic_volume_l_help') ?></div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong><?= l('products.measurement_note_title') ?>:</strong>
            <?= l('products.measurement_note_description') ?>
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
