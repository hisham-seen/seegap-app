<?php defined('SEEGAP') || die() ?>

<!-- Enhanced Splash Page Button Settings Component -->

<!-- Primary Button Settings -->
<div class="form-group mb-4">
    <h6 class="small mb-2 font-weight-bold text-primary">
        <i class="fas fa-fw fa-mouse-pointer fa-sm mr-1"></i> 
        Primary Button (Continue)
    </h6>
    
    <!-- Primary Button Colors -->
    <div class="row mb-2">
        <div class="col-4">
            <label for="primary_button_bg_color" class="small mb-1 font-weight-bold">Background</label>
            <input type="color" id="primary_button_bg_color" name="primary_button_bg_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->primary_button_bg_color ?? '#007bff' ?>" />
        </div>
        <div class="col-4">
            <label for="primary_button_text_color" class="small mb-1 font-weight-bold">Text</label>
            <input type="color" id="primary_button_text_color" name="primary_button_text_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->primary_button_text_color ?? '#ffffff' ?>" />
        </div>
        <div class="col-4">
            <label for="primary_button_border_color" class="small mb-1 font-weight-bold">Border</label>
            <input type="color" id="primary_button_border_color" name="primary_button_border_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->primary_button_border_color ?? '#007bff' ?>" />
        </div>
    </div>

    <!-- Primary Button Style -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">Button Style</label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_style ?? 'solid') == 'solid' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_style" value="solid" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_style ?? 'solid') == 'solid' ? 'checked="checked"' : null ?> />
                    Solid
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_style ?? 'solid') == 'outline' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_style" value="outline" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_style ?? 'solid') == 'outline' ? 'checked="checked"' : null ?> />
                    Outline
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_style ?? 'solid') == 'gradient' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_style" value="gradient" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_style ?? 'solid') == 'gradient' ? 'checked="checked"' : null ?> />
                    Gradient
                </label>
            </div>
        </div>
    </div>

    <!-- Primary Button Shape -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">Button Shape</label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_shape ?? 'rounded') == 'square' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_shape" value="square" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_shape ?? 'rounded') == 'square' ? 'checked="checked"' : null ?> />
                    Square
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_shape ?? 'rounded') == 'rounded' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_shape" value="rounded" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_shape ?? 'rounded') == 'rounded' ? 'checked="checked"' : null ?> />
                    Rounded
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_shape ?? 'rounded') == 'pill' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_shape" value="pill" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_shape ?? 'rounded') == 'pill' ? 'checked="checked"' : null ?> />
                    Pill
                </label>
            </div>
        </div>
    </div>

    <!-- Primary Button Size -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">Button Size</label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_size ?? 'medium') == 'small' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_size" value="small" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_size ?? 'medium') == 'small' ? 'checked="checked"' : null ?> />
                    Small
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_size ?? 'medium') == 'medium' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_size" value="medium" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_size ?? 'medium') == 'medium' ? 'checked="checked"' : null ?> />
                    Medium
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->primary_button_size ?? 'medium') == 'large' ? 'active' : null ?>">
                    <input type="radio" name="primary_button_size" value="large" class="custom-control-input" <?= ($data->splash_page->settings->primary_button_size ?? 'medium') == 'large' ? 'checked="checked"' : null ?> />
                    Large
                </label>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Button Settings -->
<div class="form-group mb-4">
    <h6 class="small mb-2 font-weight-bold text-secondary">
        <i class="fas fa-fw fa-mouse-pointer fa-sm mr-1"></i> 
        Secondary Button
    </h6>

    <!-- Secondary Button Text and URL -->
    <div class="form-group mb-2">
        <label for="splash_secondary_button_name" class="small mb-1 font-weight-bold">Button Text</label>
        <input id="splash_secondary_button_name" type="text" name="secondary_button_name" class="form-control form-control-sm" value="<?= $data->splash_page->settings->secondary_button_name ?? '' ?>" maxlength="64" placeholder="<?= l('splash_pages.secondary_button_name_placeholder') ?>" />
        <small class="form-text text-muted"><?= l('splash_pages.secondary_button_help') ?></small>
    </div>

    <div class="form-group mb-2">
        <label for="splash_secondary_button_url" class="small mb-1 font-weight-bold">Button URL</label>
        <input id="splash_secondary_button_url" type="url" name="secondary_button_url" class="form-control form-control-sm" value="<?= $data->splash_page->settings->secondary_button_url ?? '' ?>" maxlength="1024" placeholder="<?= l('splash_pages.secondary_button_url_placeholder') ?>" />
        <small class="form-text text-muted"><?= l('splash_pages.secondary_button_url_help') ?></small>
    </div>

    <!-- Use Primary Button Settings Checkbox -->
    <div class="form-group mb-2">
        <div class="custom-control custom-switch">
            <input id="secondary_use_primary_settings" name="secondary_use_primary_settings" type="checkbox" class="custom-control-input" <?= ($data->splash_page->settings->secondary_use_primary_settings ?? false) ? 'checked="checked"' : null ?>>
            <label class="custom-control-label small font-weight-bold" for="secondary_use_primary_settings">
                <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> 
                Use same style as primary button
            </label>
        </div>
        <small class="form-text text-muted">When enabled, secondary button will inherit all styling from the primary button.</small>
    </div>

    <!-- Secondary Button Settings Container -->
    <div id="secondary_button_custom_settings">
    
    <!-- Secondary Button Colors -->
    <div class="row mb-2">
        <div class="col-4">
            <label for="secondary_button_bg_color" class="small mb-1 font-weight-bold">Background</label>
            <input type="color" id="secondary_button_bg_color" name="secondary_button_bg_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->secondary_button_bg_color ?? '#6c757d' ?>" />
        </div>
        <div class="col-4">
            <label for="secondary_button_text_color" class="small mb-1 font-weight-bold">Text</label>
            <input type="color" id="secondary_button_text_color" name="secondary_button_text_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->secondary_button_text_color ?? '#ffffff' ?>" />
        </div>
        <div class="col-4">
            <label for="secondary_button_border_color" class="small mb-1 font-weight-bold">Border</label>
            <input type="color" id="secondary_button_border_color" name="secondary_button_border_color" class="form-control form-control-sm" value="<?= $data->splash_page->settings->secondary_button_border_color ?? '#6c757d' ?>" />
        </div>
    </div>

    <!-- Secondary Button Style -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">Button Style</label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_style ?? 'outline') == 'solid' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_style" value="solid" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_style ?? 'outline') == 'solid' ? 'checked="checked"' : null ?> />
                    Solid
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_style ?? 'outline') == 'outline' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_style" value="outline" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_style ?? 'outline') == 'outline' ? 'checked="checked"' : null ?> />
                    Outline
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_style ?? 'outline') == 'gradient' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_style" value="gradient" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_style ?? 'outline') == 'gradient' ? 'checked="checked"' : null ?> />
                    Gradient
                </label>
            </div>
        </div>
    </div>

    <!-- Secondary Button Shape -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">Button Shape</label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_shape ?? 'rounded') == 'square' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_shape" value="square" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_shape ?? 'rounded') == 'square' ? 'checked="checked"' : null ?> />
                    Square
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_shape ?? 'rounded') == 'rounded' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_shape" value="rounded" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_shape ?? 'rounded') == 'rounded' ? 'checked="checked"' : null ?> />
                    Rounded
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_shape ?? 'rounded') == 'pill' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_shape" value="pill" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_shape ?? 'rounded') == 'pill' ? 'checked="checked"' : null ?> />
                    Pill
                </label>
            </div>
        </div>
    </div>

    <!-- Secondary Button Size -->
    <div class="form-group mb-2">
        <label class="small mb-1 font-weight-bold">Button Size</label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_size ?? 'medium') == 'small' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_size" value="small" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_size ?? 'medium') == 'small' ? 'checked="checked"' : null ?> />
                    Small
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_size ?? 'medium') == 'medium' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_size" value="medium" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_size ?? 'medium') == 'medium' ? 'checked="checked"' : null ?> />
                    Medium
                </label>
            </div>
            <div class="col-4">
                <label class="btn btn-light btn-sm btn-block text-truncate <?= ($data->splash_page->settings->secondary_button_size ?? 'medium') == 'large' ? 'active' : null ?>">
                    <input type="radio" name="secondary_button_size" value="large" class="custom-control-input" <?= ($data->splash_page->settings->secondary_button_size ?? 'medium') == 'large' ? 'checked="checked"' : null ?> />
                    Large
                </label>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle secondary button settings visibility
    function handleSecondaryButtonSettings() {
        const usesPrimarySettings = document.getElementById('secondary_use_primary_settings').checked;
        const customSettingsContainer = document.getElementById('secondary_button_custom_settings');
        
        if (usesPrimarySettings) {
            customSettingsContainer.style.display = 'none';
        } else {
            customSettingsContainer.style.display = 'block';
        }
    }

    // Initialize secondary button settings handler
    const usePrimaryCheckbox = document.getElementById('secondary_use_primary_settings');
    if (usePrimaryCheckbox) {
        handleSecondaryButtonSettings();
        usePrimaryCheckbox.addEventListener('change', function() {
            handleSecondaryButtonSettings();
            updateButtonPreview();
        });
    }

    // Real-time button preview updates
    function updateButtonPreview() {
        // Update primary button
        const primaryButton = document.getElementById('preview-continue-text')?.parentElement;
        if (primaryButton) {
            const primaryBgColor = document.getElementById('primary_button_bg_color').value;
            const primaryTextColor = document.getElementById('primary_button_text_color').value;
            const primaryBorderColor = document.getElementById('primary_button_border_color').value;
            const primaryStyle = document.querySelector('input[name="primary_button_style"]:checked')?.value || 'solid';
            const primaryShape = document.querySelector('input[name="primary_button_shape"]:checked')?.value || 'rounded';
            const primarySize = document.querySelector('input[name="primary_button_size"]:checked')?.value || 'medium';

            // Apply primary button styles
            if (primaryStyle === 'solid') {
                primaryButton.style.backgroundColor = primaryBgColor;
                primaryButton.style.color = primaryTextColor;
                primaryButton.style.borderColor = primaryBorderColor;
                primaryButton.style.backgroundImage = 'none';
            } else if (primaryStyle === 'outline') {
                primaryButton.style.backgroundColor = 'transparent';
                primaryButton.style.color = primaryBgColor;
                primaryButton.style.borderColor = primaryBgColor;
                primaryButton.style.backgroundImage = 'none';
            } else if (primaryStyle === 'gradient') {
                primaryButton.style.background = `linear-gradient(135deg, ${primaryBgColor} 0%, ${primaryBorderColor} 100%)`;
                primaryButton.style.color = primaryTextColor;
                primaryButton.style.borderColor = 'transparent';
            }

            // Apply primary button shape
            if (primaryShape === 'square') {
                primaryButton.style.borderRadius = '4px';
            } else if (primaryShape === 'rounded') {
                primaryButton.style.borderRadius = '8px';
            } else if (primaryShape === 'pill') {
                primaryButton.style.borderRadius = '25px';
            }

            // Apply primary button size
            if (primarySize === 'small') {
                primaryButton.style.padding = '8px 16px';
                primaryButton.style.fontSize = '14px';
            } else if (primarySize === 'medium') {
                primaryButton.style.padding = '12px 24px';
                primaryButton.style.fontSize = '16px';
            } else if (primarySize === 'large') {
                primaryButton.style.padding = '16px 32px';
                primaryButton.style.fontSize = '18px';
            }
        }

        // Update secondary button
        const secondaryButton = document.getElementById('preview-secondary-text')?.parentElement;
        if (secondaryButton) {
            const usesPrimarySettings = document.getElementById('secondary_use_primary_settings').checked;
            
            let secondaryBgColor, secondaryTextColor, secondaryBorderColor, secondaryStyle, secondaryShape, secondarySize;
            
            if (usesPrimarySettings) {
                // Use primary button settings
                secondaryBgColor = document.getElementById('primary_button_bg_color').value;
                secondaryTextColor = document.getElementById('primary_button_text_color').value;
                secondaryBorderColor = document.getElementById('primary_button_border_color').value;
                secondaryStyle = document.querySelector('input[name="primary_button_style"]:checked')?.value || 'solid';
                secondaryShape = document.querySelector('input[name="primary_button_shape"]:checked')?.value || 'rounded';
                secondarySize = document.querySelector('input[name="primary_button_size"]:checked')?.value || 'medium';
            } else {
                // Use secondary button settings
                secondaryBgColor = document.getElementById('secondary_button_bg_color').value;
                secondaryTextColor = document.getElementById('secondary_button_text_color').value;
                secondaryBorderColor = document.getElementById('secondary_button_border_color').value;
                secondaryStyle = document.querySelector('input[name="secondary_button_style"]:checked')?.value || 'outline';
                secondaryShape = document.querySelector('input[name="secondary_button_shape"]:checked')?.value || 'rounded';
                secondarySize = document.querySelector('input[name="secondary_button_size"]:checked')?.value || 'medium';
            }

            // Apply secondary button styles
            if (secondaryStyle === 'solid') {
                secondaryButton.style.backgroundColor = secondaryBgColor;
                secondaryButton.style.color = secondaryTextColor;
                secondaryButton.style.borderColor = secondaryBorderColor;
                secondaryButton.style.backgroundImage = 'none';
            } else if (secondaryStyle === 'outline') {
                secondaryButton.style.backgroundColor = 'transparent';
                secondaryButton.style.color = secondaryBgColor;
                secondaryButton.style.borderColor = secondaryBgColor;
                secondaryButton.style.backgroundImage = 'none';
            } else if (secondaryStyle === 'gradient') {
                secondaryButton.style.background = `linear-gradient(135deg, ${secondaryBgColor} 0%, ${secondaryBorderColor} 100%)`;
                secondaryButton.style.color = secondaryTextColor;
                secondaryButton.style.borderColor = 'transparent';
            }

            // Apply secondary button shape
            if (secondaryShape === 'square') {
                secondaryButton.style.borderRadius = '4px';
            } else if (secondaryShape === 'rounded') {
                secondaryButton.style.borderRadius = '8px';
            } else if (secondaryShape === 'pill') {
                secondaryButton.style.borderRadius = '25px';
            }

            // Apply secondary button size (consistent sizing)
            if (secondarySize === 'small') {
                secondaryButton.style.padding = '8px 16px';
                secondaryButton.style.fontSize = '14px';
            } else if (secondarySize === 'medium') {
                secondaryButton.style.padding = '12px 24px';
                secondaryButton.style.fontSize = '16px';
            } else if (secondarySize === 'large') {
                secondaryButton.style.padding = '16px 32px';
                secondaryButton.style.fontSize = '18px';
            }
        }
    }

    // Real-time event listeners
    document.addEventListener('input', function(event) {
        if (event.target.type === 'color' && (event.target.name.includes('button_') || event.target.id.includes('button_'))) {
            updateButtonPreview();
        }
    });

    document.addEventListener('change', function(event) {
        if (event.target.type === 'color' && (event.target.name.includes('button_') || event.target.id.includes('button_'))) {
            updateButtonPreview();
        }
        
        if (event.target.name && (event.target.name.includes('button_style') || event.target.name.includes('button_shape') || event.target.name.includes('button_size'))) {
            updateButtonPreview();
        }
    });

    // Initialize button preview
    setTimeout(updateButtonPreview, 100);
});
</script>
