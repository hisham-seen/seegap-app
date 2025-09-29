<?php defined('SEEGAP') || die() ?>

<!-- Simplified Splash Page Background Settings Component -->
<div class="form-group mb-3">
    <label for="splash_background_type" class="small mb-2 font-weight-bold">
        <i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> 
        <?= l('splash_pages.background_type') ?>
    </label>
    <select id="splash_background_type" name="background_type" class="custom-select custom-select-sm">
        <option value="preset" <?= ($data->splash_page->settings->background_type ?? 'preset') == 'preset' ? 'selected="selected"' : null ?>><?= l('splash_pages.background_type_preset') ?></option>
        <option value="solid" <?= ($data->splash_page->settings->background_type ?? 'preset') == 'solid' ? 'selected="selected"' : null ?>><?= l('splash_pages.background_type_solid') ?></option>
        <option value="gradient" <?= ($data->splash_page->settings->background_type ?? 'preset') == 'gradient' ? 'selected="selected"' : null ?>><?= l('splash_pages.background_type_gradient') ?></option>
        <option value="image" <?= ($data->splash_page->settings->background_type ?? 'preset') == 'image' ? 'selected="selected"' : null ?>><?= l('splash_pages.background_type_image') ?></option>
        <option value="video" <?= ($data->splash_page->settings->background_type ?? 'preset') == 'video' ? 'selected="selected"' : null ?>><?= l('splash_pages.background_type_video') ?></option>
    </select>
</div>

<!-- Preset Backgrounds -->
<div id="splash_background_type_preset" class="mb-3">
    <label class="small mb-2 font-weight-bold">
        <i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> 
        <?= l('splash_pages.choose_preset') ?>
    </label>
    <div class="row" style="margin-right: -3px; margin-left: -3px;">
        <?php foreach($splash_page_backgrounds['preset'] as $key => $value): ?>
            <div class="col-3 p-1">
                <label for="splash_background_preset_<?= $key ?>" class="m-0 w-100">
                    <input type="radio" name="background" value="<?= $key ?>" id="splash_background_preset_<?= $key ?>" class="d-none" <?= ($data->splash_page->settings->background_type ?? 'preset') == 'preset' && ($data->splash_page->settings->background ?? 'ocean') == $key ? 'checked="checked"' : null ?>/>
                    <div class="splash-background-preset-preview" style="<?= $value ?>; height: 50px; border-radius: 8px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s ease;" data-background-style="<?= $value ?>"></div>
                    <small class="d-block text-center mt-1 text-muted" style="font-size: 0.75rem;"><?= ucfirst($key) ?></small>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>

<!-- Solid Color Background -->
<div id="splash_background_type_solid" class="mb-3" style="display: none;">
    <div class="form-group">
        <label for="splash_background_solid_color" class="small mb-2 font-weight-bold">
            <i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.background_color') ?>
        </label>
        <input type="color" id="splash_background_solid_color" name="background" class="form-control form-control-sm" value="<?= ($data->splash_page->settings->background_type ?? 'preset') == 'solid' ? ($data->splash_page->settings->background ?? '#667eea') : '#667eea' ?>" />
    </div>
</div>

<!-- Gradient Background -->
<div id="splash_background_type_gradient" class="mb-3" style="display: none;">
    <div class="form-group mb-2">
        <label for="splash_background_gradient_color_one" class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.gradient_color_start') ?>
        </label>
        <input type="color" id="splash_background_gradient_color_one" name="background_color_one" class="form-control form-control-sm" value="<?= $data->splash_page->settings->background_color_one ?? '#667eea' ?>" />
    </div>

    <div class="form-group mb-2">
        <label for="splash_background_gradient_color_two" class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.gradient_color_end') ?>
        </label>
        <input type="color" id="splash_background_gradient_color_two" name="background_color_two" class="form-control form-control-sm" value="<?= $data->splash_page->settings->background_color_two ?? '#764ba2' ?>" />
    </div>
</div>

<!-- Image Background -->
<div id="splash_background_type_image" class="mb-3" style="display: none;">
    <div class="form-group mb-2" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->background_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->background_size_limit) ?>">
        <label for="background_image" class="small mb-2 font-weight-bold">
            <i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.background_image') ?>
        </label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', [
            'uploads_file_key' => 'backgrounds', 
            'file_key' => 'background_image', 
            'already_existing_image' => (($data->splash_page->settings->background_type ?? 'preset') == 'image' && !empty($data->splash_page->settings->background) && !in_array($data->splash_page->settings->background, array_keys($splash_page_backgrounds['preset']))) ? $data->splash_page->settings->background : null, 
            'input_data' => ''
        ]) ?>
        <small class="form-text text-muted"><?= l('splash_pages.background_image_help') ?></small>
    </div>

    <!-- Overlay Settings -->
    <div class="form-group mb-2">
        <label for="splash_background_image_overlay_color" class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-layer-group fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.overlay_color') ?>
        </label>
        <input type="color" id="splash_background_image_overlay_color" name="background_overlay_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->background_overlay_color ?? '#000000' ?>" />
    </div>

    <div class="form-group mb-2" data-range-counter data-range-counter-suffix="%">
        <label for="splash_background_image_overlay_opacity" class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.overlay_opacity') ?>
        </label>
        <input id="splash_background_image_overlay_opacity" type="range" min="0" max="100" class="form-control-range" name="background_overlay_opacity" value="<?= $data->splash_page->settings->background_overlay_opacity ?? 50 ?>" />
    </div>

    <!-- Background Size -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.background_size') ?>
        </label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_size ?? 'cover') == 'cover' ? 'active' : null ?>">
                    <input type="radio" name="background_size" value="cover" class="custom-control-input" <?= ($data->splash_page->settings->background_size ?? 'cover') == 'cover' ? 'checked="checked"' : null ?> />
                    Cover
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_size ?? 'cover') == 'contain' ? 'active' : null ?>">
                    <input type="radio" name="background_size" value="contain" class="custom-control-input" <?= ($data->splash_page->settings->background_size ?? 'cover') == 'contain' ? 'checked="checked"' : null ?> />
                    Contain
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_size ?? 'cover') == 'auto' ? 'active' : null ?>">
                    <input type="radio" name="background_size" value="auto" class="custom-control-input" <?= ($data->splash_page->settings->background_size ?? 'cover') == 'auto' ? 'checked="checked"' : null ?> />
                    Auto
                </label>
            </div>
        </div>
    </div>

    <!-- Background Position -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.background_position') ?>
        </label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'top left' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="top left" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'top left' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-up"></i><i class="fas fa-arrow-left"></i>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'top center' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="top center" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'top center' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-up"></i>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'top right' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="top right" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'top right' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-up"></i><i class="fas fa-arrow-right"></i>
                </label>
            </div>
        </div>
        <div class="row btn-group-toggle mt-1" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'center left' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="center left" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'center left' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-left"></i>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'center' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="center" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'center' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-dot-circle"></i>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'center right' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="center right" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'center right' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-right"></i>
                </label>
            </div>
        </div>
        <div class="row btn-group-toggle mt-1" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'bottom left' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="bottom left" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'bottom left' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-down"></i><i class="fas fa-arrow-left"></i>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'bottom center' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="bottom center" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'bottom center' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-down"></i>
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->background_position ?? 'center') == 'bottom right' ? 'active' : null ?>">
                    <input type="radio" name="background_position" value="bottom right" class="custom-control-input" <?= ($data->splash_page->settings->background_position ?? 'center') == 'bottom right' ? 'checked="checked"' : null ?> />
                    <i class="fas fa-arrow-down"></i><i class="fas fa-arrow-right"></i>
                </label>
            </div>
        </div>
    </div>
</div>

<!-- Video Background -->
<div id="splash_background_type_video" class="mb-3" style="display: none;">
    <div class="form-group mb-2">
        <label for="splash_background_video_url" class="small mb-2 font-weight-bold">
            <i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.background_video_url') ?>
        </label>
        <input id="splash_background_video_url" type="url" name="background_video_url" class="form-control form-control-sm" value="<?= $data->splash_page->settings->background_video_url ?? '' ?>" placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/..." />
        <small class="form-text text-muted"><?= l('splash_pages.background_video_url_help') ?></small>
    </div>

    <!-- Video Controls -->
    <div class="form-group mb-2">
        <label class="small mb-2 font-weight-bold">
            <i class="fas fa-fw fa-cog fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.video_controls') ?>
        </label>
        
        <div class="row">
            <div class="col-6">
                <div class="custom-control custom-switch">
                    <input id="splash_video_autoplay" name="background_video_autoplay" type="checkbox" class="custom-control-input" <?= ($data->splash_page->settings->background_video_autoplay ?? true) ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label small" for="splash_video_autoplay"><?= l('splash_pages.video_autoplay') ?></label>
                </div>
            </div>
            <div class="col-6">
                <div class="custom-control custom-switch">
                    <input id="splash_video_loop" name="background_video_loop" type="checkbox" class="custom-control-input" <?= ($data->splash_page->settings->background_video_loop ?? true) ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label small" for="splash_video_loop"><?= l('splash_pages.video_loop') ?></label>
                </div>
            </div>
        </div>
        
        <div class="row mt-2">
            <div class="col-6">
                <div class="custom-control custom-switch">
                    <input id="splash_video_mute" name="background_video_mute" type="checkbox" class="custom-control-input" <?= ($data->splash_page->settings->background_video_mute ?? true) ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label small" for="splash_video_mute"><?= l('splash_pages.video_mute') ?></label>
                </div>
            </div>
            <div class="col-6">
                <div class="custom-control custom-switch">
                    <input id="splash_video_controls" name="background_video_controls" type="checkbox" class="custom-control-input" <?= ($data->splash_page->settings->background_video_controls ?? false) ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label small" for="splash_video_controls"><?= l('splash_pages.video_show_controls') ?></label>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay Settings -->
    <div class="form-group mb-2">
        <label for="splash_background_video_overlay_color" class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-layer-group fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.overlay_color') ?>
        </label>
        <input type="color" id="splash_background_video_overlay_color" name="background_overlay_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->background_overlay_color ?? '#000000' ?>" />
    </div>

    <div class="form-group mb-2" data-range-counter data-range-counter-suffix="%">
        <label for="splash_background_video_overlay_opacity" class="small mb-1 font-weight-bold">
            <i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> 
            <?= l('splash_pages.overlay_opacity') ?>
        </label>
        <input id="splash_background_video_overlay_opacity" type="range" min="0" max="100" class="form-control-range" name="background_overlay_opacity" value="<?= $data->splash_page->settings->background_overlay_opacity ?? 50 ?>" />
    </div>
</div>

<style>
/* Splash Background Preview Styles */
.splash-background-preset-preview:hover {
    border-color: #007bff !important;
    transform: scale(1.05);
}

input[type="radio"]:checked + .splash-background-preset-preview {
    border-color: #007bff !important;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.splash-background-preset-preview {
    position: relative;
}

.splash-background-preset-preview::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    opacity: 0;
    transition: opacity 0.2s ease;
}

input[type="radio"]:checked + .splash-background-preset-preview::after {
    opacity: 1;
}

input[type="radio"]:checked + .splash-background-preset-preview::before {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #007bff;
    font-weight: bold;
    font-size: 14px;
    z-index: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple background type handler
    function handleSplashBackgroundType() {
        const typeSelect = document.querySelector('#splash_background_type');
        if (!typeSelect) return;
        
        const selectedType = typeSelect.value;

        // Hide all background type containers
        document.querySelectorAll('[id^="splash_background_type_"]').forEach(container => {
            if (container.id !== 'splash_background_type') {
                container.style.display = 'none';
            }
        });

        // Show only the active background type
        const activeContainer = document.querySelector(`#splash_background_type_${selectedType}`);
        if (activeContainer) {
            activeContainer.style.display = 'block';
        }
    }

    // Initialize background type handler
    const typeSelect = document.querySelector('#splash_background_type');
    if (typeSelect) {
        handleSplashBackgroundType();
        typeSelect.addEventListener('change', handleSplashBackgroundType);
    }

    // Preset background preview handlers
    document.querySelectorAll('#splash_background_type_preset input[name="background"]').forEach(input => {
        input.addEventListener('change', function(event) {
            const presetStyle = event.currentTarget.parentElement.querySelector('.splash-background-preset-preview').getAttribute('data-background-style');
            const previewContent = document.getElementById('splash-preview-content');
            if (previewContent && presetStyle) {
                previewContent.setAttribute('style', presetStyle + '; height: 600px; border-radius: 8px; overflow: hidden; position: relative;');
            }
        });
    });

    // Simple color update function
    function updateSplashPreview() {
        const previewContent = document.getElementById('splash-preview-content');
        if (!previewContent) return;

        const backgroundType = document.getElementById('splash_background_type').value;

        if (backgroundType === 'solid') {
            const color = document.getElementById('splash_background_solid_color').value;
            previewContent.style.background = color;
            previewContent.style.backgroundImage = 'none';
        } else if (backgroundType === 'gradient') {
            const colorOne = document.getElementById('splash_background_gradient_color_one').value;
            const colorTwo = document.getElementById('splash_background_gradient_color_two').value;
            previewContent.style.background = `linear-gradient(135deg, ${colorOne} 0%, ${colorTwo} 100%)`;
            previewContent.style.backgroundImage = `linear-gradient(135deg, ${colorOne} 0%, ${colorTwo} 100%)`;
        }
    }

    // Enhanced real-time update function for image backgrounds
    function updateImageBackgroundPreview() {
        const previewContent = document.getElementById('splash-preview-content');
        if (!previewContent) return;

        const backgroundType = document.getElementById('splash_background_type').value;
        if (backgroundType !== 'image') return;

        // Get current image from preview
        const currentBackground = previewContent.style.background;
        if (!currentBackground.includes('url(')) return;

        // Extract the image URL from the current background
        const urlMatch = currentBackground.match(/url\(['"]?([^'"]+)['"]?\)/);
        if (!urlMatch) return;
        
        const imageUrl = urlMatch[1];
        
        // Get overlay settings
        const overlayColor = document.getElementById('splash_background_image_overlay_color').value;
        const overlayOpacity = document.getElementById('splash_background_image_overlay_opacity').value / 100;
        const backgroundSize = document.querySelector('input[name="background_size"]:checked')?.value || 'cover';
        const backgroundPosition = document.querySelector('input[name="background_position"]:checked')?.value || 'center';
        
        // Convert hex to rgba
        const r = parseInt(overlayColor.substr(1,2), 16);
        const g = parseInt(overlayColor.substr(3,2), 16);
        const b = parseInt(overlayColor.substr(5,2), 16);
        
        // Apply updated styles
        previewContent.style.background = `linear-gradient(rgba(${r},${g},${b},${overlayOpacity}), rgba(${r},${g},${b},${overlayOpacity})), url('${imageUrl}')`;
        previewContent.style.backgroundSize = backgroundSize;
        previewContent.style.backgroundPosition = backgroundPosition;
    }

    // Simple event listeners for color inputs
    document.addEventListener('input', function(event) {
        if (event.target.type === 'color' && event.target.id.includes('splash_background')) {
            updateSplashPreview();
            updateImageBackgroundPreview();
        }
        
        // Real-time overlay opacity updates
        if (event.target.type === 'range' && event.target.name === 'background_overlay_opacity') {
            updateImageBackgroundPreview();
        }
    });

    document.addEventListener('change', function(event) {
        if (event.target.type === 'color' && event.target.id.includes('splash_background')) {
            updateSplashPreview();
            updateImageBackgroundPreview();
        }
        
        // Real-time background size and position updates
        if (event.target.name === 'background_size' || event.target.name === 'background_position') {
            updateImageBackgroundPreview();
        }
        
        // Handle image upload preview with file size validation
        if (event.target.type === 'file' && event.target.name === 'background_image') {
            const file = event.target.files[0];
            if (file) {
                // Check file size (5MB limit to prevent 413 error)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    alert('Image file is too large. Please choose an image smaller than 5MB.');
                    event.target.value = ''; // Clear the input
                    return;
                }
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewContent = document.getElementById('splash-preview-content');
                        if (previewContent) {
                            const overlayColor = document.getElementById('splash_background_image_overlay_color').value;
                            const overlayOpacity = document.getElementById('splash_background_image_overlay_opacity').value / 100;
                            
                            // Convert hex to rgba
                            const r = parseInt(overlayColor.substr(1,2), 16);
                            const g = parseInt(overlayColor.substr(3,2), 16);
                            const b = parseInt(overlayColor.substr(5,2), 16);
                            
                            const backgroundSize = document.getElementById('splash_background_image_size')?.value || 'cover';
                            const backgroundPosition = document.getElementById('splash_background_image_position')?.value || 'center';
                            
                            previewContent.style.background = `linear-gradient(rgba(${r},${g},${b},${overlayOpacity}), rgba(${r},${g},${b},${overlayOpacity})), url('${e.target.result}')`;
                            previewContent.style.backgroundSize = backgroundSize;
                            previewContent.style.backgroundPosition = backgroundPosition;
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a valid image file.');
                    event.target.value = ''; // Clear the input
                }
            }
        }
    });
});
</script>
