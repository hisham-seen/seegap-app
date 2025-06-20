<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_avatar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_avatar.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_avatar" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="avatar" />

                    <div class="notification-container"></div>

                    <!-- Template Selection -->
                    <div class="form-group">
                        <label><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> Avatar Template</label>
                        <div class="row">
                            <div class="col-6 col-md-4 mb-3">
                                <label class="avatar-template-option">
                                    <input type="radio" name="template" value="classic" checked class="d-none">
                                    <div class="avatar-template-preview classic-template active">
                                        <div class="template-avatar"></div>
                                        <small class="template-name">Classic</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <label class="avatar-template-option">
                                    <input type="radio" name="template" value="gradient_ring" class="d-none">
                                    <div class="avatar-template-preview gradient-ring-template">
                                        <div class="template-avatar"></div>
                                        <small class="template-name">Gradient Ring</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <label class="avatar-template-option">
                                    <input type="radio" name="template" value="professional" class="d-none">
                                    <div class="avatar-template-preview professional-template">
                                        <div class="template-avatar"></div>
                                        <small class="template-name">Professional</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <label class="avatar-template-option">
                                    <input type="radio" name="template" value="creative" class="d-none">
                                    <div class="avatar-template-preview creative-template">
                                        <div class="template-avatar"></div>
                                        <small class="template-name">Creative</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <label class="avatar-template-option">
                                    <input type="radio" name="template" value="minimalist" class="d-none">
                                    <div class="avatar-template-preview minimalist-template">
                                        <div class="template-avatar"></div>
                                        <small class="template-name">Minimalist</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <label class="avatar-template-option">
                                    <input type="radio" name="template" value="neon_glow" class="d-none">
                                    <div class="avatar-template-preview neon-glow-template">
                                        <div class="template-avatar"></div>
                                        <small class="template-name">Neon Glow</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

    <!-- Avatar Shape Selection -->
    <div class="form-group">
        <label><i class="fas fa-fw fa-shapes fa-sm text-muted mr-1"></i> Avatar Shape</label>
        <div class="row">
            <div class="col-6">
                <label class="shape-option">
                    <input type="radio" name="avatar_shape" value="circle" checked class="d-none">
                    <div class="shape-preview circle-shape active">
                        <div class="shape-demo"></div>
                        <small>Circle</small>
                    </div>
                </label>
            </div>
            <div class="col-6">
                <label class="shape-option">
                    <input type="radio" name="avatar_shape" value="square" class="d-none">
                    <div class="shape-preview square-shape">
                        <div class="shape-demo"></div>
                        <small>Square</small>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Square Border Radius (shown only for square shape) -->
    <div class="form-group" id="square-border-radius-container" style="display: none;">
        <label for="square_border_radius"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> Corner Radius</label>
        <input type="range" id="square_border_radius" name="square_border_radius" min="0" max="50" value="8" class="form-control-range">
        <small class="form-text text-muted">Adjust corner roundness for square avatars (0 = sharp corners, 50 = fully rounded)</small>
    </div>

    <!-- Avatar Image Upload -->
                    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->avatar_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->avatar_size_limit) ?>">
                        <label for="avatar_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
                        <div class="avatar-upload-area">
                            <input id="avatar_image" type="file" name="image" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['avatar']['whitelisted_image_extensions']) ?>" class="form-control-file seegap-file-input" data-crop data-aspect-ratio="1" />
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Drag & drop or click to upload</p>
                                <small class="text-muted">Perfect for mobile viewing</small>
                            </div>
                        </div>
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['avatar']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
                    </div>

                    <!-- Mobile-Optimized Size Selection -->
                    <div class="form-group">
                        <label for="avatar_size"><i class="fas fa-fw fa-mobile-alt fa-sm text-muted mr-1"></i> Size (Mobile Optimized)</label>
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <label class="size-option">
                                    <input type="radio" name="size" value="80" class="d-none">
                                    <div class="size-preview size-80">
                                        <div class="size-circle"></div>
                                        <small>Compact<br>80px</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="size-option">
                                    <input type="radio" name="size" value="100" checked class="d-none">
                                    <div class="size-preview size-100 active">
                                        <div class="size-circle"></div>
                                        <small>Standard<br>100px</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="size-option">
                                    <input type="radio" name="size" value="120" class="d-none">
                                    <div class="size-preview size-120">
                                        <div class="size-circle"></div>
                                        <small>Large<br>120px</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="size-option">
                                    <input type="radio" name="size" value="140" class="d-none">
                                    <div class="size-preview size-140">
                                        <div class="size-circle"></div>
                                        <small>Hero<br>140px</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Background Color for Transparent Images -->
                    <div class="form-group" id="background-color-container" style="display: none;">
                        <label for="background_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> Background Color</label>
                        <input id="background_color" type="hidden" name="background_color" class="form-control" value="#ffffff" />
                        <div class="background_color_pickr"></div>
                        <small class="form-text text-muted">Background color for transparent PNG/SVG images</small>
                    </div>

                    <!-- Alt Text -->
                    <div class="form-group">
                        <label for="avatar_image_alt"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_alt') ?></label>
                        <input id="avatar_image_alt" type="text" class="form-control" name="image_alt" placeholder="Describe your avatar for accessibility" maxlength="100" />
                        <small class="form-text text-muted"><?= l('microsite_link.image_alt_help') ?></small>
                    </div>

                    <!-- Cover Image Section -->
                    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#cover_image_container" aria-expanded="false" aria-controls="cover_image_container">
                        <i class="fas fa-fw fa-image fa-sm mr-1"></i> Cover Image (Optional)
                    </button>

                    <div class="collapse" id="cover_image_container">
                        <!-- Cover Image Upload -->
                        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->background_size_limit ?? 10 ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->background_size_limit ?? 10) ?>">
                            <label for="cover_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> Cover Image</label>
                            <div class="cover-upload-area">
                                <input id="cover_image" type="file" name="cover_image" accept="<?= \SeeGap\Uploads::array_to_list_format(['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif']) ?>" class="form-control-file seegap-file-input" />
                                <div class="upload-placeholder">
                                    <i class="fas fa-mountain fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Upload background image</p>
                                    <small class="text-muted">Appears behind your avatar</small>
                                </div>
                            </div>
                            <small class="form-text text-muted">Recommended: 1200x600px or larger for best quality</small>
                        </div>

                        <!-- Cover Position -->
                        <div class="form-group">
                            <label for="cover_position"><i class="fas fa-fw fa-crosshairs fa-sm text-muted mr-1"></i> Avatar Position</label>
                            <select id="cover_position" name="cover_position" class="custom-select">
                                <option value="center" selected>Center</option>
                                <option value="top-left">Top Left</option>
                                <option value="top-right">Top Right</option>
                                <option value="bottom-left">Bottom Left</option>
                                <option value="bottom-right">Bottom Right</option>
                            </select>
                            <small class="form-text text-muted">Position of avatar over the cover image</small>
                        </div>

                        <!-- Cover Blur -->
                        <div class="form-group" data-range-counter data-range-counter-suffix="px">
                            <label for="cover_blur"><i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> Background Blur</label>
                            <input id="cover_blur" type="range" min="0" max="10" class="form-control-range" name="cover_blur" value="0" />
                            <small class="form-text text-muted">Blur effect for better avatar visibility</small>
                        </div>

                        <!-- Cover Overlay -->
                        <div class="form-group">
                            <label for="cover_overlay_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> Overlay Color</label>
                            <input id="cover_overlay_color" type="hidden" name="cover_overlay_color" class="form-control" value="#000000" />
                            <div class="cover_overlay_color_pickr"></div>
                        </div>

                        <div class="form-group" data-range-counter data-range-counter-suffix="%">
                            <label for="cover_overlay_opacity"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> Overlay Opacity</label>
                            <input id="cover_overlay_opacity" type="range" min="0" max="80" class="form-control-range" name="cover_overlay_opacity" value="0" />
                            <small class="form-text text-muted">Dark overlay for better text contrast</small>
                        </div>
                    </div>

                    <!-- Link URL -->
                    <div class="form-group">
                        <label for="avatar_location_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?> <small class="text-muted">(Optional)</small></label>
                        <input id="avatar_location_url" type="text" class="form-control" name="location_url" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
                    </div>

                    <!-- Open in New Tab -->
                    <div class="form-group custom-control custom-switch">
                        <input id="avatar_open_in_new_tab" name="open_in_new_tab" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="avatar_open_in_new_tab"><?= l('microsite_link.open_in_new_tab') ?></label>
                    </div>

                    <!-- Live Preview -->
                    <div class="form-group">
                        <label><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> Live Preview</label>
                        <div class="avatar-live-preview">
                            <div id="preview-avatar" class="preview-avatar-container">
                                <div class="preview-avatar classic-template">
                                    <div class="preview-placeholder">
                                        <i class="fas fa-user fa-2x text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted d-block text-center mt-2">Preview updates as you make changes</small>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary btn-lg" data-is-ajax>
                            <i class="fas fa-fw fa-plus mr-1"></i> <?= l('global.create') ?> Avatar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
/* Avatar Template Styles */
.avatar-template-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.avatar-template-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.avatar-template-preview:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.15);
}

.avatar-template-option input:checked + .avatar-template-preview,
.avatar-template-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.template-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.gradient-ring-template .template-avatar {
    border: 3px solid transparent;
    background: linear-gradient(white, white) padding-box, linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1) border-box;
}

.professional-template .template-avatar {
    background: #6c757d;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.creative-template .template-avatar {
    background: linear-gradient(45deg, #ff9a9e, #fecfef, #fecfef);
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #ff9a9e;
}

.minimalist-template .template-avatar {
    background: #ffffff;
    border: 1px solid #dee2e6;
}

.neon-glow-template .template-avatar {
    background: #667eea;
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.6);
}

.template-name {
    font-weight: 500;
    color: #495057;
}

/* Shape Selection Styles */
.shape-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.shape-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.shape-preview:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.15);
}

.shape-option input:checked + .shape-preview,
.shape-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.shape-demo {
    width: 40px;
    height: 40px;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.circle-shape .shape-demo {
    border-radius: 50%;
}

.square-shape .shape-demo {
    border-radius: 8px;
}

.shape-preview small {
    font-weight: 500;
    color: #495057;
}

/* Cover Upload Area Styles */
.cover-upload-area {
    position: relative;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.cover-upload-area:hover {
    border-color: #007bff;
    background: #e7f3ff;
}

/* Upload Area Styles */
.avatar-upload-area {
    position: relative;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.avatar-upload-area:hover {
    border-color: #007bff;
    background: #e7f3ff;
}

.upload-placeholder {
    pointer-events: none;
}

/* Size Selection Styles */
.size-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.size-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px 10px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.size-preview:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.size-option input:checked + .size-preview,
.size-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.size-circle {
    border-radius: 50%;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.size-80 .size-circle { width: 20px; height: 20px; }
.size-100 .size-circle { width: 25px; height: 25px; }
.size-120 .size-circle { width: 30px; height: 30px; }
.size-140 .size-circle { width: 35px; height: 35px; }

.size-preview small {
    font-weight: 500;
    color: #495057;
    line-height: 1.2;
}

/* Live Preview Styles */
.avatar-live-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.preview-avatar-container {
    display: inline-block;
}

.preview-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto;
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-placeholder {
    color: rgba(255,255,255,0.8);
}

/* Preview Avatar Template Styles */
.preview-avatar.gradient-ring-template {
    border: 3px solid transparent;
    background: linear-gradient(white, white) padding-box, linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1) border-box;
}

.preview-avatar.professional-template {
    background: #6c757d;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-avatar.creative-template {
    background: linear-gradient(45deg, #ff9a9e, #fecfef, #fecfef);
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #ff9a9e;
}

.preview-avatar.minimalist-template {
    background: #ffffff;
    border: 1px solid #dee2e6;
}

.preview-avatar.minimalist-template .preview-placeholder {
    color: #6c757d;
}

.preview-avatar.neon-glow-template {
    background: #667eea;
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.6);
}

/* Preview Avatar Shape Classes */
.preview-avatar.square-shape {
    border-radius: 8px !important;
}

.preview-avatar.circle-shape {
    border-radius: 50% !important;
}

/* Mobile Optimizations */
@media (max-width: 576px) {
    .avatar-template-preview {
        padding: 10px;
    }
    
    .template-avatar {
        width: 30px;
        height: 30px;
    }
    
    .size-preview {
        padding: 10px 5px;
    }
    
    .avatar-upload-area {
        padding: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Template selection handling
    const templateOptions = document.querySelectorAll('input[name="template"]');
    const templatePreviews = document.querySelectorAll('.avatar-template-preview');
    const previewAvatar = document.getElementById('preview-avatar').querySelector('.preview-avatar');
    
    // Add click handlers to template previews
    templatePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="template"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all template previews
                templatePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected template
                this.classList.add('active');
                
                // Update preview avatar class
                previewAvatar.className = 'preview-avatar ' + radioInput.value.replace('_', '-') + '-template';
            }
        });
    });
    
    // Also handle radio button changes directly
    templateOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all template previews
            templatePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected template
            const selectedPreview = this.parentElement.querySelector('.avatar-template-preview');
            selectedPreview.classList.add('active');
            
            // Update preview avatar class
            previewAvatar.className = 'preview-avatar ' + this.value.replace('_', '-') + '-template';
        });
    });
    
    // Size selection handling
    const sizeOptions = document.querySelectorAll('input[name="size"]');
    const sizePreviews = document.querySelectorAll('.size-preview');
    
    // Add click handlers to size previews
    sizePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="size"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all size previews
                sizePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected size
                this.classList.add('active');
                
                const size = parseInt(radioInput.value);
                const previewSize = Math.min(size * 0.8, 100); // Scale for preview
                previewAvatar.style.width = previewSize + 'px';
                previewAvatar.style.height = previewSize + 'px';
            }
        });
    });
    
    // Also handle radio button changes directly
    sizeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all size previews
            sizePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected size
            const selectedPreview = this.parentElement.querySelector('.size-preview');
            selectedPreview.classList.add('active');
            
            const size = parseInt(this.value);
            const previewSize = Math.min(size * 0.8, 100); // Scale for preview
            previewAvatar.style.width = previewSize + 'px';
            previewAvatar.style.height = previewSize + 'px';
        });
    });
    
    // Shape selection handling
    const shapeOptions = document.querySelectorAll('input[name="avatar_shape"]');
    const shapePreviews = document.querySelectorAll('.shape-preview');
    const squareBorderRadiusContainer = document.getElementById('square-border-radius-container');
    
    // Add click handlers to shape previews
    shapePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="avatar_shape"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all shape previews
                shapePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected shape
                this.classList.add('active');
                
                // Show/hide square border radius controls and update preview
                if (radioInput.value === 'square') {
                    squareBorderRadiusContainer.style.display = 'block';
                    previewAvatar.classList.remove('circle-shape');
                    previewAvatar.classList.add('square-shape');
                } else {
                    squareBorderRadiusContainer.style.display = 'none';
                    previewAvatar.classList.remove('square-shape');
                    previewAvatar.classList.add('circle-shape');
                }
            }
        });
    });
    
    // Also handle radio button changes directly
    shapeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all shape previews
            shapePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected shape
            const selectedPreview = this.parentElement.querySelector('.shape-preview');
            selectedPreview.classList.add('active');
            
            // Show/hide square border radius controls
            if (this.value === 'square') {
                squareBorderRadiusContainer.style.display = 'block';
                previewAvatar.style.borderRadius = '8px';
            } else {
                squareBorderRadiusContainer.style.display = 'none';
                previewAvatar.style.borderRadius = '50%';
            }
        });
    });
    
    // Square border radius handling
    const squareBorderRadiusInput = document.getElementById('square_border_radius');
    if (squareBorderRadiusInput) {
        squareBorderRadiusInput.addEventListener('input', function() {
            const selectedShape = document.querySelector('input[name="avatar_shape"]:checked');
            if (selectedShape && selectedShape.value === 'square') {
                previewAvatar.style.borderRadius = this.value + 'px';
            }
        });
    }

    // File upload handling
    const fileInput = document.getElementById('avatar_image');
    const backgroundColorContainer = document.getElementById('background-color-container');
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const fileName = file.name.toLowerCase();
                const fileExtension = fileName.split('.').pop();
                
                // Show background color picker for transparent image formats
                if (fileExtension === 'png' || fileExtension === 'svg') {
                    backgroundColorContainer.style.display = 'block';
                } else {
                    backgroundColorContainer.style.display = 'none';
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewAvatar.style.backgroundImage = 'url(' + e.target.result + ')';
                    previewAvatar.style.backgroundSize = 'cover';
                    previewAvatar.style.backgroundPosition = 'center';
                    previewAvatar.innerHTML = ''; // Remove placeholder icon
                };
                reader.readAsDataURL(file);
            } else {
                // Hide background color picker when no file is selected
                backgroundColorContainer.style.display = 'none';
            }
        });
    }

    // Initialize color pickers if Pickr is available
    if (typeof Pickr !== 'undefined') {
        // Background color picker
        const backgroundColorPicker = Pickr.create({
            el: '.background_color_pickr',
            theme: 'classic',
            default: '#ffffff',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    save: true
                }
            }
        });

        backgroundColorPicker.on('save', (color) => {
            document.getElementById('background_color').value = color.toHEXA().toString();
        });

        // Cover overlay color picker
        const coverOverlayColorPicker = Pickr.create({
            el: '.cover_overlay_color_pickr',
            theme: 'classic',
            default: '#000000',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    save: true
                }
            }
        });

        coverOverlayColorPicker.on('save', (color) => {
            document.getElementById('cover_overlay_color').value = color.toHEXA().toString();
        });
    }
});
</script>
