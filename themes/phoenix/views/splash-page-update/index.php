<?php defined('SEEGAP') || die() ?>

<div class="container-fluid">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= url('splash-pages') ?>"><?= l('splash_pages.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= l('splash_page_update.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-truncate"><i class="fas fa-fw fa-xs fa-droplet mr-1"></i> <?= sprintf(l('splash_page_update.header'), $data->splash_page->name) ?></h1>
        
        <div class="d-flex align-items-center">
            <!-- Update Button -->
            <button type="submit" form="splash_update_form" name="submit" class="btn btn-primary mr-2">
                <i class="fas fa-fw fa-save fa-sm mr-1"></i>
                <?= l('global.update') ?>
            </button>
            
            <div class="dropdown">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                    <i class="fas fa-fw fa-ellipsis-v"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="<?= url('splash-page/' . $data->splash_page->splash_page_id) ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-external-link-alt mr-2"></i> <?= l('global.view') ?>
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <a href="#" data-toggle="modal" data-target="#splash_page_delete_modal" data-splash-page-id="<?= $data->splash_page->splash_page_id ?>" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="post" role="form" enctype="multipart/form-data" id="splash_update_form">
        <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />

        <div class="row link-settings">
            <!-- Left Column - Elements -->
            <div class="col-12 col-lg-4">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-fw fa-th-large fa-sm text-muted mr-1"></i> 
                                <span><?= l('splash_pages.elements') ?></span>
                            </h6>
                        </div>

                        <!-- Elements Navigation Tabs -->
                        <div class="microsite-block-tabs">
                            <div class="nav nav-pills nav-fill nav-minimal mb-4" id="elements-tabs" role="tablist">
                                <a class="nav-item nav-link active" id="elements-basic-tab" data-toggle="pill" href="#elements-basic" role="tab" aria-controls="elements-basic" aria-selected="true" data-toggle="tooltip" title="Basic Information">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a class="nav-item nav-link" id="elements-media-tab" data-toggle="pill" href="#elements-media" role="tab" aria-controls="elements-media" aria-selected="false" data-toggle="tooltip" title="Media">
                                    <i class="fas fa-images"></i>
                                </a>
                                <a class="nav-item nav-link" id="elements-timing-tab" data-toggle="pill" href="#elements-timing" role="tab" aria-controls="elements-timing" aria-selected="false" data-toggle="tooltip" title="Timing">
                                    <i class="fas fa-clock"></i>
                                </a>
                                <a class="nav-item nav-link" id="elements-buttons-tab" data-toggle="pill" href="#elements-buttons" role="tab" aria-controls="elements-buttons" aria-selected="false" data-toggle="tooltip" title="Buttons">
                                    <i class="fas fa-mouse-pointer"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content" id="elements-tabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="elements-basic" role="tabpanel" aria-labelledby="elements-basic-tab">
                            <div class="form-group">
                                <label for="name" class="small font-weight-bold"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                                <input type="text" id="name" name="name" class="form-control form-control-sm <?= \SeeGap\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->splash_page->name ?>" maxlength="64" required="required" />
                                <?= \SeeGap\Alerts::output_field_error('name') ?>
                            </div>

                            <!-- Simple Content Fields -->
                            <div class="form-group">
                                <label for="title" class="small font-weight-bold"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('splash_pages.title') ?></label>
                                <input type="text" id="title" name="title" class="form-control form-control-sm" value="<?= $data->splash_page->title ?>" maxlength="128" placeholder="Enter your title here" />
                                <small class="form-text text-muted"><?= l('splash_pages.title_help') ?></small>
                            </div>

                            <div class="form-group">
                                <label for="description" class="small font-weight-bold"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('splash_pages.description') ?></label>
                                <textarea id="description" name="description" class="form-control form-control-sm" rows="3" maxlength="256" placeholder="Enter your description here"><?= $data->splash_page->description ?></textarea>
                                <small class="form-text text-muted"><?= l('splash_pages.description_help') ?></small>
                            </div>
                        </div>

                        <!-- Media Tab -->
                        <div class="tab-pane fade" id="elements-media" role="tabpanel" aria-labelledby="elements-media-tab">
                            <?php include THEME_PATH . 'views/partials/splash_page_settings/media_settings.php'; ?>
                        </div>

                        <!-- Timing Tab -->
                        <div class="tab-pane fade" id="elements-timing" role="tabpanel" aria-labelledby="elements-timing-tab">
                            <div class="form-group">
                                <label for="link_unlock_seconds" class="small font-weight-bold"><i class="fas fa-fw fa-stopwatch fa-sm text-muted mr-1"></i> <?= l('splash_pages.link_unlock_seconds') ?></label>
                                <div class="input-group">
                                    <input id="link_unlock_seconds" type="number" min="0" step="1" max="600" name="link_unlock_seconds" class="form-control form-control-sm" value="<?= $data->splash_page->link_unlock_seconds ?>" />
                                    <div class="input-group-append">
                                        <span class="input-group-text small"><?= l('global.date.seconds') ?></span>
                                    </div>
                                </div>
                                <small class="form-text text-muted"><?= l('splash_pages.link_unlock_seconds_help') ?></small>
                            </div>

                            <div class="form-group custom-control custom-switch">
                                <input id="auto_redirect" name="auto_redirect" type="checkbox" class="custom-control-input" <?= $data->splash_page->auto_redirect ? 'checked="checked"' : null?>>
                                <label class="custom-control-label small font-weight-bold" for="auto_redirect"><i class="fas fa-fw fa-square-up-right fa-sm text-muted mr-1"></i> <?= l('splash_pages.auto_redirect') ?></label>
                                <small class="form-text text-muted"><?= l('splash_pages.auto_redirect_help') ?></small>
                            </div>
                        </div>

                        <!-- Buttons Tab -->
                        <div class="tab-pane fade" id="elements-buttons" role="tabpanel" aria-labelledby="elements-buttons-tab">
                            <?php include THEME_PATH . 'views/partials/splash_page_settings/button_settings.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column - Preview -->
        <div class="col-12 col-lg-4">
            <div class="d-flex justify-content-center mb-3">
                <div class="microsite-preview">
                    <!-- iPhone-style Preview Frame (19.5:9 aspect ratio) -->
                    <div class="microsite-preview-iframe-container position-relative" style="width: 100%; height: 800px; background: #000; border-radius: 50px; box-shadow: 0 6px 30px rgba(0,0,0,0.3);">
                        <!-- Screen (proper iPhone aspect ratio) -->
                        <div id="splash-preview-content" class="splash-preview-content" style="width: 100%; height: 100%; <?php 
                            // Use new splash page backgrounds with saved settings or default
                            $splash_page_backgrounds = require APP_PATH . 'includes/splash_page_backgrounds.php';
                            $background_type = $data->splash_page->settings->background_type ?? 'preset';
                            $background = $data->splash_page->settings->background ?? 'ocean';
                            
                            if ($background_type == 'preset' && isset($splash_page_backgrounds['preset'][$background])) {
                                echo $splash_page_backgrounds['preset'][$background];
                            } elseif ($background_type == 'solid') {
                                $solid_color = $data->splash_page->settings->background ?? '#667eea';
                                echo "background: {$solid_color};";
                            } elseif ($background_type == 'gradient') {
                                $color_one = $data->splash_page->settings->background_color_one ?? '#667eea';
                                $color_two = $data->splash_page->settings->background_color_two ?? '#764ba2';
                                echo "background: linear-gradient(135deg, {$color_one} 0%, {$color_two} 100%);";
                            } elseif ($background_type == 'image' && !empty($data->splash_page->settings->background)) {
                                $overlay_color = $data->splash_page->settings->background_overlay_color ?? '#000000';
                                $overlay_opacity = ($data->splash_page->settings->background_overlay_opacity ?? 50) / 100;
                                $image_url = \SeeGap\Uploads::get_full_url('backgrounds') . $data->splash_page->settings->background;
                                echo "background: linear-gradient(rgba(" . implode(',', sscanf($overlay_color, "#%02x%02x%02x")) . ",{$overlay_opacity}), rgba(" . implode(',', sscanf($overlay_color, "#%02x%02x%02x")) . ",{$overlay_opacity})), url('{$image_url}'); background-size: cover; background-position: center;";
                            } elseif ($background_type == 'video' && !empty($data->splash_page->settings->background_video_url)) {
                                $overlay_color = $data->splash_page->settings->background_overlay_color ?? '#000000';
                                $overlay_opacity = ($data->splash_page->settings->background_overlay_opacity ?? 50) / 100;
                                echo "background: linear-gradient(rgba(" . implode(',', sscanf($overlay_color, "#%02x%02x%02x")) . ",{$overlay_opacity}), rgba(" . implode(',', sscanf($overlay_color, "#%02x%02x%02x")) . ",{$overlay_opacity})), #000000;";
                            } else {
                                // Default background
                                echo 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';
                            }
                        ?>; border-radius: 40px; overflow: hidden; position: relative;">
                            <!-- Fixed Layout Structure -->
                            <div class="splash-preview-inner d-flex flex-column text-center text-white p-4" style="height: 100%; position: relative;">
                                
                                <!-- Top Section - Logo (if present) -->
                                <div class="splash-logo-section mb-4" style="flex: 0 0 auto;">
                                    <div id="preview-logo" class="mb-3" style="<?= !empty($data->splash_page->settings->logo) ? 'display: block;' : 'display: none;' ?>">
                                        <img id="preview-logo-img" src="<?= !empty($data->splash_page->settings->logo) ? \SeeGap\Uploads::get_full_url('splash_pages') . $data->splash_page->settings->logo : '' ?>" alt="Logo" style="max-width: 120px; max-height: 80px; object-fit: contain;">
                                    </div>
                                </div>
                                
                                <!-- Middle Section - Content (flexible) -->
                                <div class="splash-content-section d-flex flex-column justify-content-center align-items-center" style="flex: 1 1 auto;">
                                    <!-- Title -->
                                    <h1 id="preview-title" class="h2 mb-3 font-weight-bold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);"><?= !empty($data->splash_page->title) ? $data->splash_page->title : 'Your Title' ?></h1>
                                    
                                    <!-- Description -->
                                    <p id="preview-description" class="lead mb-4" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3); max-width: 400px;"><?= !empty($data->splash_page->description) ? $data->splash_page->description : 'Enter your description to see it here' ?></p>
                                </div>
                                
                                <!-- Bottom Section - Buttons (fixed to bottom) -->
                                <div class="splash-buttons-section" style="flex: 0 0 auto; position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); width: 100%; max-width: 300px;">
                                    <!-- Primary Button (Continue) -->
                                    <button class="btn btn-light btn-lg mb-2 px-4 w-100" style="border-radius: 25px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                        <span id="preview-continue-text">Continue</span>
                                        <span id="preview-timer" class="ml-2" style="<?= $data->splash_page->link_unlock_seconds > 0 ? 'display: inline;' : 'display: none;' ?>">(<?= $data->splash_page->link_unlock_seconds ?>)</span>
                                    </button>
                                    
                                    <!-- Secondary Button -->
                                    <div id="preview-secondary-button" style="<?= !empty($data->splash_page->settings->secondary_button_name) ? 'display: block;' : 'display: none;' ?>">
                                        <button class="btn btn-outline-light btn-sm px-3 w-100" style="border-radius: 20px;">
                                            <span id="preview-secondary-text"><?= !empty($data->splash_page->settings->secondary_button_name) ? $data->splash_page->settings->secondary_button_name : 'Secondary Button' ?></span>
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Settings -->
        <div class="col-12 col-lg-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-fw fa-wrench fa-sm text-muted mr-1"></i> 
                            <span><?= l('link.header.settings_tab') ?></span>
                        </h6>
                    </div>

                    <!-- Settings Navigation Tabs -->
                    <div class="microsite-block-tabs">
                        <div class="nav nav-pills nav-fill nav-minimal mb-4" id="settings-tabs" role="tablist">
                            <a class="nav-item nav-link active" id="settings-theme-tab" data-toggle="pill" href="#settings-theme" role="tab" aria-controls="settings-theme" aria-selected="true" data-toggle="tooltip" title="Background Settings">
                                <i class="fas fa-palette"></i>
                            </a>
                            <a class="nav-item nav-link" id="settings-buttons-tab" data-toggle="pill" href="#settings-buttons" role="tab" aria-controls="settings-buttons" aria-selected="false" data-toggle="tooltip" title="Button Settings">
                                <i class="fas fa-mouse-pointer"></i>
                            </a>
                            <a class="nav-item nav-link" id="settings-customization-tab" data-toggle="pill" href="#settings-customization" role="tab" aria-controls="settings-customization" aria-selected="false" data-toggle="tooltip" title="SEO Settings">
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>

                    <div class="notification-container"></div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="settings-tabContent">
                            <!-- Theme Tab -->
                            <div class="tab-pane fade show active" id="settings-theme" role="tabpanel" aria-labelledby="settings-theme-tab">
                                <?php 
                                // Load splash page backgrounds and set up data structure
                                $splash_page_backgrounds = require APP_PATH . 'includes/splash_page_backgrounds.php';
                                // Use the actual splash page data with proper defaults
                                $data_backup = $data;
                                ?>
                                <?php include THEME_PATH . 'views/partials/splash_page_settings/background_settings.php'; ?>
                                <?php $data = $data_backup; // Restore original data ?>
                            </div>

                            <!-- Buttons Tab -->
                            <div class="tab-pane fade" id="settings-buttons" role="tabpanel" aria-labelledby="settings-buttons-tab">
                                <?php include THEME_PATH . 'views/partials/splash_page_settings/button_settings.php'; ?>
                            </div>

                            <!-- Customization Tab -->
                            <div class="tab-pane fade" id="settings-customization" role="tabpanel" aria-labelledby="settings-customization-tab">
                                <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->favicon_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->favicon_size_limit) ?>">
                                    <label for="favicon" class="small font-weight-bold"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('splash_pages.favicon') ?></label>
                                    <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'splash_pages', 'file_key' => 'favicon', 'already_existing_image' => $data->splash_page->settings->favicon ?? null, 'input_data' => 'data-crop data-aspect-ratio="1"']) ?>
                                    <?= \SeeGap\Alerts::output_field_error('favicon') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('splash_pages')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->favicon_size_limit) ?></small>
                                </div>

                                <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->seo_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->seo_image_size_limit) ?>">
                                    <label for="opengraph" class="small font-weight-bold"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('splash_pages.opengraph') ?></label>
                                    <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'splash_pages', 'file_key' => 'opengraph', 'already_existing_image' => $data->splash_page->settings->opengraph ?? null, 'input_data' => 'data-crop data-aspect-ratio="1.91"']) ?>
                                    <?= \SeeGap\Alerts::output_field_error('opengraph') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('splash_pages')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->seo_image_size_limit) ?></small>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/microsite-tabs-theme.css?v=' . PRODUCT_CODE ?>">

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
<?php include_view(THEME_PATH . 'views/partials/js_cropper.php') ?>
<?php include_view(THEME_PATH . 'views/partials/wysiwyg_editor.php') ?>

<style>
.microsite-preview {
    width: 100%;
    max-width: 400px;
}

.microsite-preview-iframe-container {
    width: 100%;
    height: 600px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    background: #fff;
}

.microsite-preview-iframe {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 8px;
}

.microsite-block-tabs .nav-pills {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 4px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-control-sm {
    font-size: 0.875rem;
}

.small.font-weight-bold {
    font-weight: 600;
}
</style>

<script>
/* Initialize WYSIWYG editors when DOM is ready and Quill is available */
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Quill to be available and then initialize editors
    function waitForQuillAndInitialize() {
        if (typeof Quill !== 'undefined') {
            // Initialize editors directly since Quill is available
            setTimeout(function() {
                document.querySelectorAll('.wysiwyg-editor').forEach(function(textarea) {
                    if (!textarea.classList.contains('wysiwyg-initialized')) {
                        // Initialize Quill editor
                        const editorContainer = document.createElement('div');
                        editorContainer.style.minHeight = '200px';
                        textarea.style.display = 'none';
                        textarea.parentNode.insertBefore(editorContainer, textarea);
                        
                        const quill = new Quill(editorContainer, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ 'color': [] }, { 'background': [] }],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    [{ 'align': [] }],
                                    ['link'],
                                    ['clean']
                                ]
                            },
                            placeholder: 'Enter your content here. Use the toolbar to format text, add headings, lists, and more...'
                        });
                        
                        // Set initial content
                        if (textarea.value) {
                            quill.root.innerHTML = textarea.value;
                        }
                        
                        // Update textarea when content changes
                        quill.on('text-change', function() {
                            textarea.value = quill.root.innerHTML;
                        });
                        
                        textarea.classList.add('wysiwyg-initialized');
                    }
                });
            }, 100);
        } else {
            setTimeout(waitForQuillAndInitialize, 100);
        }
    }
    
    // Start checking for Quill availability
    setTimeout(waitForQuillAndInitialize, 100);
});

/* Real-time splash page preview functionality */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('splash_update_form');
    if (!form) return;
    
    // Preview elements
    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const previewLogo = document.getElementById('preview-logo');
    const previewLogoImg = document.getElementById('preview-logo-img');
    const previewSecondaryButton = document.getElementById('preview-secondary-button');
    const previewSecondaryText = document.getElementById('preview-secondary-text');
    const previewTimer = document.getElementById('preview-timer');
    const previewContent = document.getElementById('splash-preview-content');
    
    // Form inputs - check if they exist to avoid null reference errors
    const titleInput = document.querySelector('input[name="title"]') || document.querySelector('textarea[name="content"]');
    const descriptionInput = document.querySelector('textarea[name="description"]') || document.querySelector('textarea[name="content"]');
    const secondaryButtonNameInput = document.querySelector('input[name="name"]') || document.querySelector('input[name="secondary_button_name"]');
    const linkUnlockSecondsInput = document.getElementById('link_unlock_seconds');
    
    // Update preview function
    function updatePreview() {
        // Update title
        const titleValue = titleInput.value.trim();
        if (titleValue) {
            previewTitle.textContent = titleValue;
        } else {
            previewTitle.textContent = 'Your Title';
        }
        
        // Update description
        const descriptionValue = descriptionInput.value.trim();
        if (descriptionValue) {
            previewDescription.textContent = descriptionValue;
        } else {
            previewDescription.textContent = 'Enter your description to see it here';
        }
        
        // Update secondary button
        const secondaryButtonValue = secondaryButtonNameInput.value.trim();
        if (secondaryButtonValue) {
            previewSecondaryButton.style.display = 'block';
            previewSecondaryText.textContent = secondaryButtonValue;
        } else {
            previewSecondaryButton.style.display = 'none';
        }
        
        // Update timer
        const timerValue = linkUnlockSecondsInput.value;
        if (timerValue && timerValue > 0) {
            previewTimer.textContent = `(${timerValue})`;
            previewTimer.style.display = 'inline';
        } else {
            previewTimer.style.display = 'none';
        }
    }
    
    // Handle logo upload preview
    function handleLogoPreview() {
        const logoInput = document.querySelector('input[name="logo"]');
        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewLogoImg.src = e.target.result;
                        previewLogo.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else if (!file) {
                    // If no file selected, keep existing logo if any
                    const existingLogo = previewLogoImg.getAttribute('data-original-src');
                    if (existingLogo) {
                        previewLogoImg.src = existingLogo;
                        previewLogo.style.display = 'block';
                    } else {
                        previewLogo.style.display = 'none';
                    }
                }
            });
        }
    }
    
    // Background Type Handler for Splash Pages
    let background_type_handler = () => {
        let type = document.querySelector('#splash_background_type');
        if (!type) return;
        
        let selectedType = type.value;

        // Hide all background type containers first
        document.querySelectorAll('[id^="splash_background_type_"]').forEach(container => {
            if (container.id !== 'splash_background_type') {
                container.style.display = 'none';
                // Disable inputs in hidden containers
                container.querySelectorAll('input').forEach(input => {
                    input.disabled = true;
                });
            }
        });

        // Show only the active background type
        let activeContainer = document.querySelector(`#splash_background_type_${selectedType}`);
        if (activeContainer) {
            activeContainer.style.display = 'block';
            // Enable inputs in active container
            activeContainer.querySelectorAll('input').forEach(input => {
                input.disabled = false;
            });
        }
    };

    // Initialize background type handler
    if (document.querySelector('#splash_background_type')) {
        background_type_handler();
        document.querySelector('#splash_background_type').addEventListener('change', background_type_handler);
    }

    // Preset background preview handlers
    document.querySelectorAll('#splash_background_type_preset input[name="background"]').forEach(input => {
        input.addEventListener('change', function(event) {
            let presetStyle = event.currentTarget.parentElement.querySelector('.splash-background-preset-preview').getAttribute('data-background-style');
            if (previewContent && presetStyle) {
                previewContent.setAttribute('style', presetStyle + '; height: 600px; border-radius: 8px; overflow: hidden; position: relative;');
            }
        });
    });

    // Font preview handlers
    document.querySelectorAll('input[name="font"]').forEach(input => {
        input.addEventListener('change', function(event) {
            let fontFamily = event.currentTarget.getAttribute('data-font-family');
            let fontCssUrl = event.currentTarget.getAttribute('data-font-css-url');
            
            // Load font CSS if needed
            if (fontCssUrl && fontCssUrl !== 'false') {
                let existingLink = document.querySelector(`link[href="${fontCssUrl}"]`);
                if (!existingLink) {
                    let link = document.createElement('link');
                    link.href = fontCssUrl;
                    link.rel = 'stylesheet';
                    document.head.appendChild(link);
                }
            }
            
            // Apply font to preview text elements
            if (fontFamily && fontFamily !== 'false') {
                if (previewTitle) previewTitle.style.fontFamily = fontFamily;
                if (previewDescription) previewDescription.style.fontFamily = fontFamily;
            } else {
                // Reset to default font
                if (previewTitle) previewTitle.style.fontFamily = '';
                if (previewDescription) previewDescription.style.fontFamily = '';
            }
        });
    });

    // Font size preview handler
    const fontSizeInput = document.getElementById('settings_font_size');
    if (fontSizeInput) {
        fontSizeInput.addEventListener('input', function(event) {
            let fontSize = event.currentTarget.value + 'px';
            if (previewTitle) previewTitle.style.fontSize = fontSize;
            if (previewDescription) previewDescription.style.fontSize = (parseInt(event.currentTarget.value) - 2) + 'px';
        });
    }
    
    // Store original logo src for fallback
    if (previewLogoImg.src) {
        previewLogoImg.setAttribute('data-original-src', previewLogoImg.src);
    }
    
    // Initialize saved font if any
    const savedFontInput = document.querySelector('input[name="font"]:checked');
    if (savedFontInput) {
        let fontFamily = savedFontInput.getAttribute('data-font-family');
        let fontCssUrl = savedFontInput.getAttribute('data-font-css-url');
        
        // Load font CSS if needed
        if (fontCssUrl && fontCssUrl !== 'false') {
            let existingLink = document.querySelector(`link[href="${fontCssUrl}"]`);
            if (!existingLink) {
                let link = document.createElement('link');
                link.href = fontCssUrl;
                link.rel = 'stylesheet';
                document.head.appendChild(link);
            }
        }
        
        // Apply font to preview text elements
        if (fontFamily && fontFamily !== 'false') {
            if (previewTitle) previewTitle.style.fontFamily = fontFamily;
            if (previewDescription) previewDescription.style.fontFamily = fontFamily;
        }
    }

    // Initialize saved font size if any
    if (fontSizeInput && fontSizeInput.value) {
        let fontSize = fontSizeInput.value + 'px';
        if (previewTitle) previewTitle.style.fontSize = fontSize;
        if (previewDescription) previewDescription.style.fontSize = (parseInt(fontSizeInput.value) - 2) + 'px';
    }

    // Initialize preview with current values
    updatePreview();
    handleLogoPreview();
    
    // Add event listeners for real-time updates
    if (titleInput) {
        ['input', 'change', 'paste', 'keyup'].forEach(eventType => {
            titleInput.addEventListener(eventType, updatePreview);
        });
    }
    
    if (descriptionInput) {
        ['input', 'change', 'paste', 'keyup'].forEach(eventType => {
            descriptionInput.addEventListener(eventType, updatePreview);
        });
    }
    
    if (secondaryButtonNameInput) {
        ['input', 'change', 'paste', 'keyup'].forEach(eventType => {
            secondaryButtonNameInput.addEventListener(eventType, updatePreview);
        });
    }
    
    if (linkUnlockSecondsInput) {
        ['input', 'change'].forEach(eventType => {
            linkUnlockSecondsInput.addEventListener(eventType, updatePreview);
        });
    }
    
    // Handle file input changes for logo
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        if (input.name === 'logo') {
            input.addEventListener('change', handleLogoPreview);
        }
    });
    
    // Form submission handler to sync microsite block fields
    form.addEventListener('submit', function(e) {
        // Sync Quill editors first
        if (typeof syncTextQuillEditors === 'function') {
            syncTextQuillEditors();
        }
        
        // Extract title and description from content field
        const contentField = document.querySelector('textarea[name="content"]');
        if (contentField && contentField.value) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = contentField.value;
            
            // Extract title (first h1, h2, h3, etc.)
            const titleElement = tempDiv.querySelector('h1, h2, h3, h4, h5, h6');
            if (titleElement) {
                // Create hidden title field if it doesn't exist
                let titleField = document.querySelector('input[name="title"]');
                if (!titleField) {
                    titleField = document.createElement('input');
                    titleField.type = 'hidden';
                    titleField.name = 'title';
                    form.appendChild(titleField);
                }
                titleField.value = titleElement.textContent.trim();
            }
            
            // Extract description (first p tag)
            const descElement = tempDiv.querySelector('p');
            if (descElement) {
                // Create hidden description field if it doesn't exist
                let descField = document.querySelector('textarea[name="description"]');
                if (!descField) {
                    descField = document.createElement('textarea');
                    descField.name = 'description';
                    descField.style.display = 'none';
                    form.appendChild(descField);
                }
                descField.value = descElement.textContent.trim();
            }
        }
        
        // Sync secondary button fields
        const buttonNameField = document.querySelector('input[name="name"]');
        const buttonUrlField = document.querySelector('input[name="location_url"]');
        
        if (buttonNameField && buttonNameField.value) {
            // Create hidden secondary button name field
            let secondaryButtonNameField = document.querySelector('input[name="secondary_button_name"]');
            if (!secondaryButtonNameField) {
                secondaryButtonNameField = document.createElement('input');
                secondaryButtonNameField.type = 'hidden';
                secondaryButtonNameField.name = 'secondary_button_name';
                form.appendChild(secondaryButtonNameField);
            }
            secondaryButtonNameField.value = buttonNameField.value;
        }
        
        if (buttonUrlField && buttonUrlField.value) {
            // Create hidden secondary button URL field
            let secondaryButtonUrlField = document.querySelector('input[name="secondary_button_url"]');
            if (!secondaryButtonUrlField) {
                secondaryButtonUrlField = document.createElement('input');
                secondaryButtonUrlField.type = 'hidden';
                secondaryButtonUrlField.name = 'secondary_button_url';
                form.appendChild(secondaryButtonUrlField);
            }
            secondaryButtonUrlField.value = buttonUrlField.value;
        }
    });
});
</script>
