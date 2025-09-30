<?php defined('SEEGAP') || die() ?>

<input type="hidden" name="section" value="gs1-digital-link" />

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-link text-primary mr-2"></i>
        <?= l('products.sections.gs1_digital_link') ?>
    </h5>
    <small class="text-muted"><?= l('products.sections.gs1_digital_link_description') ?></small>
</div>

<!-- GS1 Digital Link Status -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-info-circle fa-sm mr-2"></i>
        <?= l('products.gs1.link_status') ?>
    </h6>
    <div class="row">
        <div class="col-12">
            <?php if($data->product->gs1_link_id): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong><?= l('products.gs1.link_active') ?></strong>
                    <br>
                    <small class="text-muted">
                        <?= l('products.gs1.link_id') ?>: <?= $data->product->gs1_link_id ?>
                        <?php if($data->product->gs1_target_url): ?>
                            <br><?= l('products.gs1.target_url') ?>: <a href="<?= $data->product->gs1_target_url ?>" target="_blank"><?= $data->product->gs1_target_url ?></a>
                        <?php endif ?>
                        <?php if($data->product->gs1_clicks): ?>
                            <br><?= l('products.gs1.total_clicks') ?>: <?= number_format($data->product->gs1_clicks) ?>
                        <?php endif ?>
                    </small>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong><?= l('products.gs1.no_link') ?></strong>
                    <br>
                    <small class="text-muted"><?= l('products.gs1.no_link_description') ?></small>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<!-- GS1 Digital Link Configuration -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-cog fa-sm mr-2"></i>
        <?= l('products.gs1.link_configuration') ?>
    </h6>
    <div class="row">
        <!-- Enable GS1 Digital Link -->
        <div class="col-12 mb-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" id="gs1_link_enabled" name="gs1_link_enabled" class="custom-control-input" <?= $data->product->gs1_link_enabled ? 'checked' : '' ?> />
                <label class="custom-control-label" for="gs1_link_enabled">
                    <i class="fas fa-fw fa-toggle-on fa-sm text-muted mr-1"></i>
                    <?= l('products.gs1.enable_digital_link') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.gs1.enable_digital_link_help') ?></div>
        </div>

        <!-- Link Type Selection -->
        <div class="col-lg-6 mb-3">
            <label for="gs1_link_type" class="form-label">
                <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.link_type') ?>
            </label>
            <select id="gs1_link_type" name="gs1_link_type" class="form-control">
                <option value="custom" <?= ($data->product->gs1_link_type ?? 'custom') === 'custom' ? 'selected' : '' ?>><?= l('products.gs1.link_type_custom') ?></option>
                <option value="microsite" <?= ($data->product->gs1_link_type ?? '') === 'microsite' ? 'selected' : '' ?>><?= l('products.gs1.link_type_microsite') ?></option>
                <option value="link" <?= ($data->product->gs1_link_type ?? '') === 'link' ? 'selected' : '' ?>><?= l('products.gs1.link_type_link') ?></option>
                <option value="file" <?= ($data->product->gs1_link_type ?? '') === 'file' ? 'selected' : '' ?>><?= l('products.gs1.link_type_file') ?></option>
                <option value="event" <?= ($data->product->gs1_link_type ?? '') === 'event' ? 'selected' : '' ?>><?= l('products.gs1.link_type_event') ?></option>
                <option value="static" <?= ($data->product->gs1_link_type ?? '') === 'static' ? 'selected' : '' ?>><?= l('products.gs1.link_type_static') ?></option>
                <option value="default" <?= ($data->product->gs1_link_type ?? '') === 'default' ? 'selected' : '' ?>><?= l('products.gs1.link_type_default') ?></option>
            </select>
            <div class="form-text"><?= l('products.gs1.link_type_help') ?></div>
        </div>

        <!-- Custom URL Field -->
        <div class="col-lg-6 mb-3" id="gs1_custom_url_field">
            <label for="gs1_target_url" class="form-label">
                <i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.target_url') ?>
            </label>
            <input 
                type="url" 
                id="gs1_target_url" 
                name="gs1_target_url" 
                class="form-control <?= \SeeGap\Alerts::has_field_errors('gs1_target_url') ? 'is-invalid' : null ?>" 
                value="<?= $data->product->gs1_target_url ?? $data->product->target_url ?? '' ?>" 
                placeholder="https://example.com/product-page"
            >
            <?= \SeeGap\Alerts::output_field_error('gs1_target_url') ?>
            <div class="form-text"><?= l('products.gs1.target_url_help') ?></div>
        </div>

        <!-- Existing Content Selection -->
        <div class="col-lg-6 mb-3" id="gs1_existing_content_field" style="display: none;">
            <label for="gs1_existing_content_id" class="form-label">
                <i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.existing_content') ?>
            </label>
            <select id="gs1_existing_content_id" name="gs1_existing_content_id" class="form-control">
                <option value=""><?= l('products.gs1.select_content') ?></option>
                <!-- Options will be populated via JavaScript based on selected type -->
                <?php if($data->product->gs1_existing_content_id): ?>
                    <option value="<?= $data->product->gs1_existing_content_id ?>" selected>Loading selected content...</option>
                <?php endif ?>
            </select>
            <div class="form-text"><?= l('products.gs1.existing_content_help') ?></div>
        </div>

        <!-- Link Title -->
        <div class="col-lg-6 mb-3">
            <label for="gs1_link_title" class="form-label">
                <i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.link_title') ?>
            </label>
            <input 
                type="text" 
                id="gs1_link_title" 
                name="gs1_link_title" 
                class="form-control <?= \SeeGap\Alerts::has_field_errors('gs1_link_title') ? 'is-invalid' : null ?>" 
                value="<?= $data->product->gs1_link_title ?? $data->product->product_name ?? '' ?>" 
                maxlength="256"
            >
            <?= \SeeGap\Alerts::output_field_error('gs1_link_title') ?>
            <div class="form-text"><?= l('products.gs1.link_title_help') ?></div>
        </div>

        <!-- Link Description -->
        <div class="col-12 mb-3">
            <label for="gs1_link_description" class="form-label">
                <i class="fas fa-fw fa-align-left fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.link_description') ?>
            </label>
            <textarea 
                id="gs1_link_description" 
                name="gs1_link_description" 
                class="form-control <?= \SeeGap\Alerts::has_field_errors('gs1_link_description') ? 'is-invalid' : null ?>" 
                rows="3" 
                maxlength="500"
            ><?= $data->product->gs1_link_description ?? $data->product->product_description ?? '' ?></textarea>
            <?= \SeeGap\Alerts::output_field_error('gs1_link_description') ?>
            <div class="form-text"><?= l('products.gs1.link_description_help') ?></div>
        </div>
    </div>
</div>

<!-- QR Code Settings -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-qrcode fa-sm mr-2"></i>
        <?= l('products.gs1.qr_code_settings') ?>
    </h6>
    <div class="row">
        <!-- Auto-generate QR Code -->
        <div class="col-12 mb-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" id="auto_generate_qr" name="auto_generate_qr" class="custom-control-input" <?= $data->product->auto_generate_qr ? 'checked' : '' ?> />
                <label class="custom-control-label" for="auto_generate_qr">
                    <i class="fas fa-fw fa-magic fa-sm text-muted mr-1"></i>
                    <?= l('products.gs1.auto_generate_qr') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.gs1.auto_generate_qr_help') ?></div>
        </div>

        <!-- QR Code Size -->
        <div class="col-lg-6 mb-3">
            <label for="qr_code_size" class="form-label">
                <i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.qr_code_size') ?>
            </label>
            <select id="qr_code_size" name="qr_code_size" class="form-control">
                <option value="200" <?= ($data->product->qr_code_size ?? '500') === '200' ? 'selected' : '' ?>>200x200 px</option>
                <option value="300" <?= ($data->product->qr_code_size ?? '500') === '300' ? 'selected' : '' ?>>300x300 px</option>
                <option value="500" <?= ($data->product->qr_code_size ?? '500') === '500' ? 'selected' : '' ?>>500x500 px</option>
                <option value="800" <?= ($data->product->qr_code_size ?? '500') === '800' ? 'selected' : '' ?>>800x800 px</option>
                <option value="1000" <?= ($data->product->qr_code_size ?? '500') === '1000' ? 'selected' : '' ?>>1000x1000 px</option>
            </select>
            <div class="form-text"><?= l('products.gs1.qr_code_size_help') ?></div>
        </div>

        <!-- QR Code Error Correction -->
        <div class="col-lg-6 mb-3">
            <label for="qr_code_ecc" class="form-label">
                <i class="fas fa-fw fa-shield-alt fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.qr_code_ecc') ?>
            </label>
            <select id="qr_code_ecc" name="qr_code_ecc" class="form-control">
                <option value="L" <?= ($data->product->qr_code_ecc ?? 'M') === 'L' ? 'selected' : '' ?>><?= l('products.gs1.ecc_low') ?> (7%)</option>
                <option value="M" <?= ($data->product->qr_code_ecc ?? 'M') === 'M' ? 'selected' : '' ?>><?= l('products.gs1.ecc_medium') ?> (15%)</option>
                <option value="Q" <?= ($data->product->qr_code_ecc ?? 'M') === 'Q' ? 'selected' : '' ?>><?= l('products.gs1.ecc_quartile') ?> (25%)</option>
                <option value="H" <?= ($data->product->qr_code_ecc ?? 'M') === 'H' ? 'selected' : '' ?>><?= l('products.gs1.ecc_high') ?> (30%)</option>
            </select>
            <div class="form-text"><?= l('products.gs1.qr_code_ecc_help') ?></div>
        </div>
    </div>
</div>

<!-- Advanced Settings -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-cogs fa-sm mr-2"></i>
        <?= l('products.gs1.advanced_settings') ?>
    </h6>
    <div class="row">
        <!-- Schedule -->
        <div class="col-12 mb-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" id="gs1_schedule_enabled" name="gs1_schedule_enabled" class="custom-control-input" <?= $data->product->gs1_schedule_enabled ? 'checked' : '' ?> />
                <label class="custom-control-label" for="gs1_schedule_enabled">
                    <i class="fas fa-fw fa-calendar-alt fa-sm text-muted mr-1"></i>
                    <?= l('products.gs1.enable_scheduling') ?>
                </label>
            </div>
            <div class="form-text"><?= l('products.gs1.enable_scheduling_help') ?></div>
        </div>

        <!-- Start Date -->
        <div class="col-lg-6 mb-3" id="gs1_schedule_fields" style="display: <?= $data->product->gs1_schedule_enabled ? 'block' : 'none' ?>;">
            <label for="gs1_start_date" class="form-label">
                <i class="fas fa-fw fa-calendar-plus fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.start_date') ?>
            </label>
            <input type="datetime-local" id="gs1_start_date" name="gs1_start_date" class="form-control" value="<?= $data->product->gs1_start_date ? date('Y-m-d\TH:i', strtotime($data->product->gs1_start_date)) : '' ?>" />
            <div class="form-text"><?= l('products.gs1.start_date_help') ?></div>
        </div>

        <!-- End Date -->
        <div class="col-lg-6 mb-3" id="gs1_end_date_field" style="display: <?= $data->product->gs1_schedule_enabled ? 'block' : 'none' ?>;">
            <label for="gs1_end_date" class="form-label">
                <i class="fas fa-fw fa-calendar-times fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.end_date') ?>
            </label>
            <input type="datetime-local" id="gs1_end_date" name="gs1_end_date" class="form-control" value="<?= $data->product->gs1_end_date ? date('Y-m-d\TH:i', strtotime($data->product->gs1_end_date)) : '' ?>" />
            <div class="form-text"><?= l('products.gs1.end_date_help') ?></div>
        </div>

        <!-- Click Limit -->
        <div class="col-lg-6 mb-3">
            <label for="gs1_clicks_limit" class="form-label">
                <i class="fas fa-fw fa-mouse-pointer fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.clicks_limit') ?>
            </label>
            <input 
                type="number" 
                id="gs1_clicks_limit" 
                name="gs1_clicks_limit" 
                class="form-control" 
                min="0" 
                value="<?= $data->product->gs1_clicks_limit ?? '' ?>"
                placeholder="<?= l('products.gs1.unlimited') ?>"
            >
            <div class="form-text"><?= l('products.gs1.clicks_limit_help') ?></div>
        </div>

        <!-- Expiration URL -->
        <div class="col-lg-6 mb-3">
            <label for="gs1_expiration_url" class="form-label">
                <i class="fas fa-fw fa-exclamation-triangle fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.expiration_url') ?>
            </label>
            <input 
                type="url" 
                id="gs1_expiration_url" 
                name="gs1_expiration_url" 
                class="form-control" 
                value="<?= $data->product->gs1_expiration_url ?? '' ?>"
                placeholder="https://example.com/expired"
            >
            <div class="form-text"><?= l('products.gs1.expiration_url_help') ?></div>
        </div>
    </div>
</div>

<!-- UTM Parameters -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-chart-line fa-sm mr-2"></i>
        <?= l('products.gs1.utm_parameters') ?>
    </h6>
    <div class="row">
        <!-- UTM Source -->
        <div class="col-lg-4 mb-3">
            <label for="gs1_utm_source" class="form-label">
                <i class="fas fa-fw fa-tag fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.utm_source') ?>
            </label>
            <input 
                type="text" 
                id="gs1_utm_source" 
                name="gs1_utm_source" 
                class="form-control" 
                maxlength="128" 
                value="<?= $data->product->gs1_utm_source ?? '' ?>"
                placeholder="qr-code"
            >
            <div class="form-text"><?= l('products.gs1.utm_source_help') ?></div>
        </div>

        <!-- UTM Medium -->
        <div class="col-lg-4 mb-3">
            <label for="gs1_utm_medium" class="form-label">
                <i class="fas fa-fw fa-bullhorn fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.utm_medium') ?>
            </label>
            <input 
                type="text" 
                id="gs1_utm_medium" 
                name="gs1_utm_medium" 
                class="form-control" 
                maxlength="128" 
                value="<?= $data->product->gs1_utm_medium ?? '' ?>"
                placeholder="gs1-digital-link"
            >
            <div class="form-text"><?= l('products.gs1.utm_medium_help') ?></div>
        </div>

        <!-- UTM Campaign -->
        <div class="col-lg-4 mb-3">
            <label for="gs1_utm_campaign" class="form-label">
                <i class="fas fa-fw fa-rocket fa-sm text-muted mr-1"></i>
                <?= l('products.gs1.utm_campaign') ?>
            </label>
            <input 
                type="text" 
                id="gs1_utm_campaign" 
                name="gs1_utm_campaign" 
                class="form-control" 
                maxlength="128" 
                value="<?= $data->product->gs1_utm_campaign ?? '' ?>"
                placeholder="product-launch"
            >
            <div class="form-text"><?= l('products.gs1.utm_campaign_help') ?></div>
        </div>
    </div>
</div>

<!-- Generated URLs Preview -->
<?php if($data->product->gtin): ?>
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-eye fa-sm mr-2"></i>
        <?= l('products.gs1.url_preview') ?>
    </h6>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="alert alert-info">
                <strong><?= l('products.gs1.digital_link_url') ?>:</strong><br>
                <code id="gs1_digital_link_preview">
                    <?= url('01/' . $data->product->gtin) ?>
                </code>
                <button type="button" class="btn btn-sm btn-outline-primary ml-2" onclick="copyToClipboard('gs1_digital_link_preview')">
                    <i class="fas fa-copy fa-sm"></i> <?= l('global.copy') ?>
                </button>
            </div>
        </div>
        
        <div class="col-12 mb-3">
            <div class="alert alert-secondary">
                <strong><?= l('products.gs1.qr_code_data') ?>:</strong><br>
                <code id="gs1_qr_code_preview">
                    <?= url('01/' . $data->product->gtin) ?>
                </code>
                <button type="button" class="btn btn-sm btn-outline-secondary ml-2" onclick="copyToClipboard('gs1_qr_code_preview')">
                    <i class="fas fa-copy fa-sm"></i> <?= l('global.copy') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<!-- Additional Information -->
<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle mr-2"></i>
    <strong><?= l('products.gs1.note_title') ?>:</strong>
    <?= l('products.gs1.note_description') ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle schedule fields
    const scheduleCheckbox = document.getElementById('gs1_schedule_enabled');
    const scheduleFields = document.getElementById('gs1_schedule_fields');
    const endDateField = document.getElementById('gs1_end_date_field');
    
    scheduleCheckbox.addEventListener('change', function() {
        if (this.checked) {
            scheduleFields.style.display = 'block';
            endDateField.style.display = 'block';
        } else {
            scheduleFields.style.display = 'none';
            endDateField.style.display = 'none';
        }
    });

    // Handle link type selection
    const linkTypeSelect = document.getElementById('gs1_link_type');
    const customUrlField = document.getElementById('gs1_custom_url_field');
    const existingContentField = document.getElementById('gs1_existing_content_field');
    const existingContentSelect = document.getElementById('gs1_existing_content_id');
    
    linkTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        
        if (selectedType === 'custom') {
            customUrlField.style.display = 'block';
            existingContentField.style.display = 'none';
        } else if (selectedType === 'default') {
            customUrlField.style.display = 'none';
            existingContentField.style.display = 'none';
        } else {
            customUrlField.style.display = 'none';
            existingContentField.style.display = 'block';
            loadExistingContent(selectedType);
        }
    });

    // Load existing content based on type
    function loadExistingContent(type, selectedId = null) {
        // Clear existing options
        existingContentSelect.innerHTML = '<option value=""><?= l('products.gs1.select_content') ?></option>';
        
        // Show loading state
        existingContentSelect.innerHTML += '<option value="" disabled>Loading...</option>';
        
        // Make AJAX request to load content
        fetch(`<?= url('product-ajax') ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `type=get_existing_content&content_type=${type}&token=<?= \SeeGap\Csrf::get() ?>`
        })
        .then(response => response.json())
        .then(data => {
            // Debug logging
            console.log('AJAX Response:', data);
            console.log('Response status:', data.status);
            console.log('Response data:', data.data);
            console.log('Response details:', data.details);
            console.log('Selected ID to restore:', selectedId);
            
            // Clear loading state
            existingContentSelect.innerHTML = '<option value=""><?= l('products.gs1.select_content') ?></option>';
            
            // Check both data and details properties for backward compatibility
            const responseData = data.data || data.details?.data;
            console.log('Final data to use:', responseData);
            
            if (data.status === 'success' && responseData && Array.isArray(responseData) && responseData.length > 0) {
                console.log('Found', responseData.length, 'items');
                responseData.forEach(item => {
                    console.log('Adding option:', item);
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    
                    // Select the option if it matches the saved ID
                    if (selectedId && item.id == selectedId) {
                        option.selected = true;
                        console.log('Selected option:', item.name, 'with ID:', item.id);
                    }
                    
                    existingContentSelect.appendChild(option);
                });
            } else {
                console.log('No content found or error. Status:', data.status, 'Data:', responseData);
                const option = document.createElement('option');
                option.value = '';
                option.textContent = '<?= l('products.gs1.no_content_available') ?>';
                option.disabled = true;
                existingContentSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error loading content:', error);
            existingContentSelect.innerHTML = '<option value=""><?= l('products.gs1.error_loading_content') ?></option>';
        });
    }

    // Initialize on page load - handle existing values
    const initialLinkType = linkTypeSelect.value;
    const savedContentId = '<?= $data->product->gs1_existing_content_id ?? '' ?>';
    
    if (initialLinkType && initialLinkType !== 'custom' && initialLinkType !== 'default') {
        // Show the existing content field and load content
        existingContentField.style.display = 'block';
        customUrlField.style.display = 'none';
        
        // Load content for the saved type
        loadExistingContent(initialLinkType, savedContentId);
    } else {
        // Trigger normal change event for custom/default types
        linkTypeSelect.dispatchEvent(new Event('change'));
    }
});

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent.trim();
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const button = element.nextElementSibling;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check fa-sm"></i> <?= l('global.copied') ?>';
            button.classList.remove('btn-outline-primary', 'btn-outline-secondary');
            button.classList.add('btn-success');
            
            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add(elementId.includes('digital_link') ? 'btn-outline-primary' : 'btn-outline-secondary');
            }, 2000);
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }
}
</script>
