<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-images text-primary me-2"></i>
            <?= l('products.media_images_section') ?>
        </h5>
        <p class="text-muted small mb-0"><?= l('products.media_images_description') ?></p>
    </div>
    <div class="card-body">
        <!-- Primary Product Images -->
        <h6 class="text-primary mb-3">
            <i class="fas fa-image me-2"></i>
            <?= l('products.primary_product_images') ?>
        </h6>
        <div class="row">
            <!-- Main Product Image -->
            <div class="col-lg-6 mb-3">
                <label for="main_image" class="form-label">
                    <?= l('products.main_image') ?>
                </label>
                <div class="input-group">
                    <input 
                        type="file" 
                        id="main_image" 
                        name="main_image" 
                        class="form-control" 
                        accept="image/*"
                    >
                    <button class="btn btn-outline-secondary" type="button" onclick="previewImage('main_image', 'main_image_preview')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="form-text"><?= l('products.main_image_help') ?></div>
                <?php if (!empty($data->product->main_image)): ?>
                    <div class="mt-2">
                        <img id="main_image_preview" src="<?= $data->product->main_image ?>" alt="Main Product Image" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                <?php else: ?>
                    <div class="mt-2">
                        <img id="main_image_preview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-width: 150px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Secondary Image -->
            <div class="col-lg-6 mb-3">
                <label for="secondary_image" class="form-label">
                    <?= l('products.secondary_image') ?>
                </label>
                <div class="input-group">
                    <input 
                        type="file" 
                        id="secondary_image" 
                        name="secondary_image" 
                        class="form-control" 
                        accept="image/*"
                    >
                    <button class="btn btn-outline-secondary" type="button" onclick="previewImage('secondary_image', 'secondary_image_preview')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="form-text"><?= l('products.secondary_image_help') ?></div>
                <?php if (!empty($data->product->secondary_image)): ?>
                    <div class="mt-2">
                        <img id="secondary_image_preview" src="<?= $data->product->secondary_image ?>" alt="Secondary Product Image" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                <?php else: ?>
                    <div class="mt-2">
                        <img id="secondary_image_preview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-width: 150px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Gallery Images -->
            <div class="col-12 mb-3">
                <label for="gallery_images" class="form-label">
                    <?= l('products.gallery_images') ?>
                </label>
                <input 
                    type="file" 
                    id="gallery_images" 
                    name="gallery_images[]" 
                    class="form-control" 
                    accept="image/*"
                    multiple
                >
                <div class="form-text"><?= l('products.gallery_images_help') ?></div>
                <div id="gallery_preview" class="mt-2 d-flex flex-wrap gap-2">
                    <!-- Existing gallery images will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Product Documentation -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-file-pdf me-2"></i>
            <?= l('products.product_documentation') ?>
        </h6>
        <div class="row">
            <!-- Product Brochure -->
            <div class="col-lg-6 mb-3">
                <label for="brochure_file" class="form-label">
                    <?= l('products.brochure_file') ?>
                </label>
                <input 
                    type="file" 
                    id="brochure_file" 
                    name="brochure_file" 
                    class="form-control" 
                    accept=".pdf,.doc,.docx"
                >
                <div class="form-text"><?= l('products.brochure_file_help') ?></div>
                <?php if (!empty($data->product->brochure_file)): ?>
                    <div class="mt-2">
                        <a href="<?= $data->product->brochure_file ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            <?= l('products.download_current_brochure') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- User Manual -->
            <div class="col-lg-6 mb-3">
                <label for="manual_file" class="form-label">
                    <?= l('products.manual_file') ?>
                </label>
                <input 
                    type="file" 
                    id="manual_file" 
                    name="manual_file" 
                    class="form-control" 
                    accept=".pdf,.doc,.docx"
                >
                <div class="form-text"><?= l('products.manual_file_help') ?></div>
                <?php if (!empty($data->product->manual_file)): ?>
                    <div class="mt-2">
                        <a href="<?= $data->product->manual_file ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            <?= l('products.download_current_manual') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Technical Specifications -->
            <div class="col-lg-6 mb-3">
                <label for="tech_specs_file" class="form-label">
                    <?= l('products.tech_specs_file') ?>
                </label>
                <input 
                    type="file" 
                    id="tech_specs_file" 
                    name="tech_specs_file" 
                    class="form-control" 
                    accept=".pdf,.doc,.docx,.xls,.xlsx"
                >
                <div class="form-text"><?= l('products.tech_specs_file_help') ?></div>
                <?php if (!empty($data->product->tech_specs_file)): ?>
                    <div class="mt-2">
                        <a href="<?= $data->product->tech_specs_file ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            <?= l('products.download_current_specs') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Safety Data Sheet -->
            <div class="col-lg-6 mb-3">
                <label for="safety_data_sheet" class="form-label">
                    <?= l('products.safety_data_sheet') ?>
                </label>
                <input 
                    type="file" 
                    id="safety_data_sheet" 
                    name="safety_data_sheet" 
                    class="form-control" 
                    accept=".pdf,.doc,.docx"
                >
                <div class="form-text"><?= l('products.safety_data_sheet_help') ?></div>
                <?php if (!empty($data->product->safety_data_sheet)): ?>
                    <div class="mt-2">
                        <a href="<?= $data->product->safety_data_sheet ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            <?= l('products.download_current_sds') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Video Content -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-video me-2"></i>
            <?= l('products.video_content') ?>
        </h6>
        <div class="row">
            <!-- Product Video -->
            <div class="col-lg-6 mb-3">
                <label for="product_video" class="form-label">
                    <?= l('products.product_video') ?>
                </label>
                <input 
                    type="file" 
                    id="product_video" 
                    name="product_video" 
                    class="form-control" 
                    accept="video/*"
                >
                <div class="form-text"><?= l('products.product_video_help') ?></div>
                <?php if (!empty($data->product->product_video)): ?>
                    <div class="mt-2">
                        <video controls style="max-width: 300px; max-height: 200px;">
                            <source src="<?= $data->product->product_video ?>" type="video/mp4">
                            <?= l('products.video_not_supported') ?>
                        </video>
                    </div>
                <?php endif; ?>
            </div>

            <!-- YouTube Video ID -->
            <div class="col-lg-6 mb-3">
                <label for="youtube_video_id" class="form-label">
                    <?= l('products.youtube_video_id') ?>
                </label>
                <input 
                    type="text" 
                    id="youtube_video_id" 
                    name="youtube_video_id" 
                    class="form-control" 
                    value="<?= $data->product->youtube_video_id ?? '' ?>"
                    placeholder="<?= l('products.youtube_video_id_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.youtube_video_id_help') ?></div>
                <?php if (!empty($data->product->youtube_video_id)): ?>
                    <div class="mt-2">
                        <div class="ratio ratio-16x9" style="max-width: 300px;">
                            <iframe src="https://www.youtube.com/embed/<?= $data->product->youtube_video_id ?>" allowfullscreen></iframe>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 3D Models & AR -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-cube me-2"></i>
            <?= l('products.3d_models_ar') ?>
        </h6>
        <div class="row">
            <!-- 3D Model File -->
            <div class="col-lg-6 mb-3">
                <label for="model_3d_file" class="form-label">
                    <?= l('products.model_3d_file') ?>
                </label>
                <input 
                    type="file" 
                    id="model_3d_file" 
                    name="model_3d_file" 
                    class="form-control" 
                    accept=".glb,.gltf,.obj,.fbx"
                >
                <div class="form-text"><?= l('products.model_3d_file_help') ?></div>
                <?php if (!empty($data->product->model_3d_file)): ?>
                    <div class="mt-2">
                        <a href="<?= $data->product->model_3d_file ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            <?= l('products.download_current_3d_model') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- AR Model File -->
            <div class="col-lg-6 mb-3">
                <label for="ar_model_file" class="form-label">
                    <?= l('products.ar_model_file') ?>
                </label>
                <input 
                    type="file" 
                    id="ar_model_file" 
                    name="ar_model_file" 
                    class="form-control" 
                    accept=".usdz,.glb"
                >
                <div class="form-text"><?= l('products.ar_model_file_help') ?></div>
                <?php if (!empty($data->product->ar_model_file)): ?>
                    <div class="mt-2">
                        <a href="<?= $data->product->ar_model_file ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            <?= l('products.download_current_ar_model') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Media Settings -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-cog me-2"></i>
            <?= l('products.media_settings') ?>
        </h6>
        <div class="row">
            <!-- Image Quality -->
            <div class="col-lg-6 mb-3">
                <label for="image_quality" class="form-label">
                    <?= l('products.image_quality') ?>
                </label>
                <select 
                    id="image_quality" 
                    name="image_quality" 
                    class="form-select"
                >
                    <option value="high" <?= ($data->product->image_quality ?? 'high') === 'high' ? 'selected' : '' ?>><?= l('products.image_quality_high') ?></option>
                    <option value="medium" <?= ($data->product->image_quality ?? '') === 'medium' ? 'selected' : '' ?>><?= l('products.image_quality_medium') ?></option>
                    <option value="low" <?= ($data->product->image_quality ?? '') === 'low' ? 'selected' : '' ?>><?= l('products.image_quality_low') ?></option>
                </select>
                <div class="form-text"><?= l('products.image_quality_help') ?></div>
            </div>

            <!-- Auto-resize Images -->
            <div class="col-lg-6 mb-3">
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="auto_resize_images" 
                        name="auto_resize_images" 
                        value="1"
                        <?= ($data->product->auto_resize_images ?? '1') ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="auto_resize_images">
                        <?= l('products.auto_resize_images') ?>
                    </label>
                </div>
                <div class="form-text"><?= l('products.auto_resize_images_help') ?></div>
            </div>

            <!-- Generate Thumbnails -->
            <div class="col-lg-6 mb-3">
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="generate_thumbnails" 
                        name="generate_thumbnails" 
                        value="1"
                        <?= ($data->product->generate_thumbnails ?? '1') ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="generate_thumbnails">
                        <?= l('products.generate_thumbnails') ?>
                    </label>
                </div>
                <div class="form-text"><?= l('products.generate_thumbnails_help') ?></div>
            </div>

            <!-- Watermark Images -->
            <div class="col-lg-6 mb-3">
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="watermark_images" 
                        name="watermark_images" 
                        value="1"
                        <?= ($data->product->watermark_images ?? '') ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="watermark_images">
                        <?= l('products.watermark_images') ?>
                    </label>
                </div>
                <div class="form-text"><?= l('products.watermark_images_help') ?></div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong><?= l('products.media_note_title') ?>:</strong>
            <?= l('products.media_note_description') ?>
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

<script>
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-preview when file is selected
document.getElementById('main_image').addEventListener('change', function() {
    previewImage('main_image', 'main_image_preview');
});

document.getElementById('secondary_image').addEventListener('change', function() {
    previewImage('secondary_image', 'secondary_image_preview');
});

// Gallery images preview
document.getElementById('gallery_images').addEventListener('change', function() {
    const preview = document.getElementById('gallery_preview');
    preview.innerHTML = '';
    
    for (let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail';
            img.style.maxWidth = '100px';
            img.style.maxHeight = '100px';
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(file);
    }
});
</script>
