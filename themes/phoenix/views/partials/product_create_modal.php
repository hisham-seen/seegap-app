<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="product_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-plus-circle text-primary mr-1"></i>
                    <?= l('products.create.header') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="product_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="ajax_type" value="product" />

                    <div class="notification-container"></div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="modal_gtin"><i class="fas fa-fw fa-barcode fa-sm text-muted mr-1"></i> <?= l('products.input.gtin') ?> <span class="text-danger">*</span></label>
                                <input type="text" id="modal_gtin" name="gtin" class="form-control" maxlength="14" required="required" />
                                <small class="form-text text-muted"><?= l('products.input.gtin_help') ?></small>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="modal_brand_name"><i class="fas fa-fw fa-tag fa-sm text-muted mr-1"></i> <?= l('products.input.brand_name') ?><?php if(settings()->products->require_brand_name ?? false): ?> <span class="text-danger">*</span><?php endif ?></label>
                                <input type="text" id="modal_brand_name" name="brand_name" class="form-control" maxlength="128" <?php if(settings()->products->require_brand_name ?? false): ?>required="required"<?php endif ?> />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="modal_product_name"><i class="fas fa-fw fa-box fa-sm text-muted mr-1"></i> <?= l('products.input.product_name') ?><?php if(settings()->products->require_product_name ?? true): ?> <span class="text-danger">*</span><?php endif ?></label>
                        <input type="text" id="modal_product_name" name="product_name" class="form-control" maxlength="256" <?php if(settings()->products->require_product_name ?? true): ?>required="required"<?php endif ?> />
                    </div>

                    <div class="form-group">
                        <label for="modal_description"><i class="fas fa-fw fa-align-left fa-sm text-muted mr-1"></i> <?= l('products.input.product_description') ?></label>
                        <textarea id="modal_description" name="description" class="form-control" rows="3" placeholder="<?= l('global.optional') ?>"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="modal_category"><i class="fas fa-fw fa-folder fa-sm text-muted mr-1"></i> <?= l('products.input.category') ?><?php if(settings()->products->require_category ?? false): ?> <span class="text-danger">*</span><?php endif ?></label>
                                <input type="text" id="modal_category" name="category" class="form-control" maxlength="128" <?php if(settings()->products->require_category ?? false): ?>required="required"<?php endif ?> />
                            </div>
                        </div>

                        <?php if(count($data->projects ?? [])): ?>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="modal_project_id"><i class="fas fa-fw fa-project-diagram fa-sm text-muted mr-1"></i> <?= l('projects.project') ?></label>
                                <select id="modal_project_id" name="project_id" class="custom-select">
                                    <option value=""><?= l('global.none') ?></option>
                                    <?php foreach($data->projects as $project_id => $project): ?>
                                        <option value="<?= $project_id ?>"><?= $project->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <?php endif ?>
                    </div>

                    <div class="form-group">
                        <label for="modal_target_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('products.input.target_url') ?><?php if(settings()->products->require_target_url ?? false): ?> <span class="text-danger">*</span><?php endif ?></label>
                        <input type="url" id="modal_target_url" name="target_url" class="form-control" maxlength="2048" <?php if(settings()->products->require_target_url ?? false): ?>required="required"<?php endif ?> />
                        <small class="form-text text-muted"><?= l('products.input.target_url_help') ?></small>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-fw fa-info-circle mr-1"></i>
                        <?= l('products.create.modal_info') ?? 'After creating the product, you can add detailed information like ingredients, nutritional info, and more in the edit view.' ?>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax>
                            <i class="fas fa-fw fa-plus-circle mr-1"></i>
                            <?= l('global.create') ?>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('product_create_modal');
    
    if (modal) {
        console.log('Product create modal found, initializing...');
        
        // Function to reset form
        function resetForm() {
            const form = modal.querySelector('form[name="product_create"]');
            if (form) {
                console.log('Resetting product form');
                form.reset();
                // Clear any previous error states
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                // Clear notification container
                const notificationContainer = form.querySelector('.notification-container');
                if (notificationContainer) {
                    notificationContainer.innerHTML = '';
                }
            }
        }
        
        // Function to handle form submission
        function handleFormSubmission() {
            const form = modal.querySelector('form[name="product_create"]');
            
            if (form && !form.hasAttribute('data-product-handler-added')) {
                console.log('Adding form submission handler');
                
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    console.log('Product form submitted via AJAX');
                    
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    // Show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-fw fa-spinner fa-spin mr-1"></i>' + '<?= l('global.please_wait') ?>';
                    
                    // Clear previous errors
                    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                    
                    // Submit via AJAX
                    const formData = new FormData(form);
                    
                    // Debug: Log what we're sending
                    console.log('Sending product creation request with:', {
                        request_type: formData.get('request_type'),
                        ajax_type: formData.get('ajax_type'),
                        gtin: formData.get('gtin'),
                        product_name: formData.get('product_name'),
                        brand_name: formData.get('brand_name')
                    });
                    
                    fetch('<?= url('ajax') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Response received:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        
                        if (data.status === 'success') {
                            // Show success message
                            if (data.message) {
                                const notificationContainer = form.querySelector('.notification-container');
                                notificationContainer.innerHTML = `
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-fw fa-check-circle mr-1"></i>
                                        ${data.message}
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                `;
                            }
                            
                            // Redirect after a short delay
                            setTimeout(() => {
                                if (data.details && data.details.url) {
                                    window.location.href = data.details.url;
                                } else {
                                    // Fallback: reload the page
                                    window.location.reload();
                                }
                            }, 1500);
                        } else {
                            // Handle errors
                            if (data.message) {
                                const notificationContainer = form.querySelector('.notification-container');
                                notificationContainer.innerHTML = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-fw fa-exclamation-triangle mr-1"></i>
                                        ${data.message}
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                `;
                            }
                            
                            // Handle field errors
                            if (data.details && data.details.field_errors) {
                                Object.keys(data.details.field_errors).forEach(fieldName => {
                                    const field = form.querySelector(`[name="${fieldName}"]`);
                                    if (field) {
                                        field.classList.add('is-invalid');
                                        const errorDiv = document.createElement('div');
                                        errorDiv.className = 'invalid-feedback';
                                        errorDiv.textContent = data.details.field_errors[fieldName];
                                        field.parentNode.appendChild(errorDiv);
                                    }
                                });
                            }
                            
                            // Reset button state
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        const notificationContainer = form.querySelector('.notification-container');
                        notificationContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-fw fa-exclamation-triangle mr-1"></i>
                                <?= l('global.error_message.basic') ?>
                                <button type="button" class="close" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        
                        // Reset button state
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
                
                // Mark as handled to prevent duplicate listeners
                form.setAttribute('data-product-handler-added', 'true');
            }
        }
        
        // Try both Bootstrap 4 and Bootstrap 5 event patterns
        // Bootstrap 4 events
        modal.addEventListener('show.bs.modal', resetForm);
        modal.addEventListener('shown.bs.modal', handleFormSubmission);
        
        // Bootstrap 5 events (just in case)
        modal.addEventListener('show.bs.modal', resetForm);
        modal.addEventListener('shown.bs.modal', handleFormSubmission);
        
        // jQuery events as fallback (if jQuery is available)
        if (typeof $ !== 'undefined') {
            $(modal).on('show.bs.modal', resetForm);
            $(modal).on('shown.bs.modal', handleFormSubmission);
        }
        
        // Also initialize immediately in case modal is already open
        setTimeout(() => {
            handleFormSubmission();
        }, 100);
        
        console.log('Product create modal initialization complete');
    } else {
        console.log('Product create modal not found');
    }
});
</script>
