<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings - following Image Block pattern exactly */
$all_styles = [];
$animation_class = '';

// Get form settings
$form_settings = $data->link->settings;

// Handle background color
if (isset($form_settings->background_color) && $form_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $form_settings->background_color;
} else {
    $all_styles[] = 'background-color: #0000001A';
}

// Handle text color
if (isset($form_settings->text_color)) {
    $all_styles[] = 'color: ' . $form_settings->text_color;
}

// Handle border - following exact image block pattern
if (isset($form_settings->border_width) && $form_settings->border_width > 0) {
    $border_width = $form_settings->border_width;
    $border_color = $form_settings->border_color ?? '#ffffff';
    $border_style = $form_settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
}

// Handle border radius - numeric values like other blocks
if (isset($form_settings->border_radius) && is_numeric($form_settings->border_radius) && $form_settings->border_radius > 0) {
    $all_styles[] = 'border-radius: ' . $form_settings->border_radius . 'px';
}

// Handle shadow - following exact image block pattern
if (isset($form_settings->border_shadow_blur) && $form_settings->border_shadow_blur > 0) {
    $shadow_x = $form_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $form_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $form_settings->border_shadow_blur ?? 0;
    $shadow_spread = $form_settings->border_shadow_spread ?? 0;
    $shadow_color = $form_settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

// Handle animation - following exact image block pattern
if (isset($form_settings->animation) && $form_settings->animation && $form_settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $form_settings->animation;
    if (isset($form_settings->animation_runs) && $form_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $form_settings->animation_runs;
    }
    if (isset($form_settings->animation_delay) && $form_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($form_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

// Create style attributes - separate for button and inline form
$button_styles = $all_styles; // Button gets all styles except padding
$inline_styles = array_merge($all_styles, ['padding: 1.5rem']); // Inline form gets padding too

$button_style_attribute = !empty($button_styles) ? 'style="' . implode('; ', $button_styles) . ';"' : '';
$inline_style_attribute = !empty($inline_styles) ? 'style="' . implode('; ', $inline_styles) . ';"' : '';
$form_id = 'form_' . $data->link->microsite_block_id;
$questions = $form_settings->questions ?? [];
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?> text-<?= $data->link->settings->text_alignment ?? 'center' ?>">
    
    <?php if(($form_settings->display_mode ?? 'inline') == 'button'): ?>
        <!-- Button mode - show button that opens modal -->
        <button type="button" class="btn btn-block btn-primary link-btn <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?> <?= 'link-btn-' . ($form_settings->border_radius ?? 'rounded') ?> <?= $animation_class ?>" 
                <?= $button_style_attribute ?>
                data-toggle="modal" data-target="#<?= $form_id ?>_modal">
            
            <?php if($form_settings->image ?? false): ?>
                <div class="link-btn-image-wrapper <?= 'link-btn-' . ($form_settings->border_radius ?? 'rounded') ?>" style="margin-bottom: 8px;">
                    <img src="<?= \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $form_settings->image ?>" class="link-btn-image" loading="lazy" style="max-height: 32px; width: auto;" />
                </div>
            <?php endif ?>

            <?php if($form_settings->icon ?? false): ?>
                <i class="<?= $form_settings->icon ?> mr-1"></i>
            <?php endif ?>

            <span><?= $form_settings->button_text ?? l('global.submit') ?></span>
        </button>

    <?php else: ?>
        <!-- Inline mode - show form directly -->
        <div class="microsite-form-block form-container form-style-<?= $form_settings->inline_form_style ?? 'card' ?> <?= $animation_class ?>" data-modal-style="<?= $form_settings->modal_form_style ?? 'standard' ?>" <?= $inline_style_attribute ?>>
            
            <?php if($form_settings->image ?? false): ?>
                <div class="text-center mb-3">
                    <img src="<?= \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $form_settings->image ?>" class="img-fluid" loading="lazy" style="max-height: 100px; width: auto;" />
                </div>
            <?php endif ?>

            <?php if($form_settings->form_heading ?? false): ?>
                <h4 class="form-title mb-3"><?= $form_settings->form_heading ?></h4>
            <?php endif ?>

            <?php if($form_settings->form_text ?? false): ?>
                <p class="form-description mb-4"><?= nl2br($form_settings->form_text) ?></p>
            <?php endif ?>

            <!-- Form Content for Inline -->
            <form id="<?= $form_id ?>" class="microsite-form" data-microsite-block-id="<?= $data->link->microsite_block_id ?>">
                <div class="form-messages"></div>
                
                <?php if(!empty($questions)): ?>
                    <?php foreach($questions as $index => $question): ?>
                        <div class="form-group mb-3">
                            <label for="question_<?= $index ?>" class="form-label">
                                <?= $question->question ?>
                                <?php if($question->required ?? false): ?>
                                    <span class="text-danger">*</span>
                                <?php endif ?>
                            </label>
                            <?php if(!empty($question->description)): ?>
                                <small class="form-text text-muted mb-2"><?= nl2br(htmlspecialchars($question->description)) ?></small>
                            <?php endif ?>
                            
                            <?php switch($question->type ?? 'text'):
                                case 'text':
                                case 'email':
                                case 'phone': ?>
                                    <input type="<?= $question->type ?>" 
                                           id="question_<?= $index ?>" 
                                           name="question_<?= $index ?>" 
                                           class="form-control" 
                                           <?= ($question->required ?? false) ? 'required' : '' ?>>
                                    <?php break; ?>
                                
                                <?php case 'textarea': ?>
                                    <textarea id="question_<?= $index ?>" 
                                              name="question_<?= $index ?>" 
                                              class="form-control" 
                                              rows="3" 
                                              <?= ($question->required ?? false) ? 'required' : '' ?>></textarea>
                                    <?php break; ?>
                                
                                <?php case 'checkbox': ?>
                                    <?php if(isset($question->options->choices) && is_array($question->options->choices)): ?>
                                        <?php foreach($question->options->choices as $choice_index => $choice): ?>
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       id="question_<?= $index ?>_<?= $choice_index ?>" 
                                                       name="question_<?= $index ?>[]" 
                                                       value="<?= htmlspecialchars($choice) ?>" 
                                                       class="form-check-input">
                                                <label for="question_<?= $index ?>_<?= $choice_index ?>" class="form-check-label">
                                                    <?= htmlspecialchars($choice) ?>
                                                </label>
                                            </div>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                    <?php break; ?>
                                
                                <?php case 'radio': ?>
                                    <?php if(isset($question->options->choices) && is_array($question->options->choices)): ?>
                                        <?php foreach($question->options->choices as $choice_index => $choice): ?>
                                            <div class="form-check">
                                                <input type="radio" 
                                                       id="question_<?= $index ?>_<?= $choice_index ?>" 
                                                       name="question_<?= $index ?>" 
                                                       value="<?= htmlspecialchars($choice) ?>" 
                                                       class="form-check-input" 
                                                       <?= ($question->required ?? false) ? 'required' : '' ?>>
                                                <label for="question_<?= $index ?>_<?= $choice_index ?>" class="form-check-label">
                                                    <?= htmlspecialchars($choice) ?>
                                                </label>
                                            </div>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                    <?php break; ?>
                                
                                <?php case 'dropdown': ?>
                                    <select id="question_<?= $index ?>" 
                                            name="question_<?= $index ?>" 
                                            class="form-control" 
                                            <?= ($question->required ?? false) ? 'required' : '' ?>>
                                        <option value=""><?= l('microsite_form.js.select_option') ?></option>
                                        <?php if(isset($question->options->choices) && is_array($question->options->choices)): ?>
                                            <?php foreach($question->options->choices as $choice): ?>
                                                <option value="<?= htmlspecialchars($choice) ?>"><?= htmlspecialchars($choice) ?></option>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </select>
                                    <?php break; ?>
                                
                                <?php case 'rating_star': ?>
                                    <div class="rating-stars" data-max-rating="<?= $question->options->max_rating ?? 5 ?>">
                                        <?php for($i = 1; $i <= ($question->options->max_rating ?? 5); $i++): ?>
                                            <span class="rating-star" data-rating="<?= $i ?>">★</span>
                                        <?php endfor ?>
                                        <input type="hidden" id="question_<?= $index ?>" name="question_<?= $index ?>" <?= ($question->required ?? false) ? 'required' : '' ?>>
                                    </div>
                                    <?php break; ?>
                                
                                <?php case 'rating_number': ?>
                                    <div class="rating-numbers" data-max-rating="<?= $question->options->max_rating ?? 5 ?>">
                                        <?php for($i = 1; $i <= ($question->options->max_rating ?? 5); $i++): ?>
                                            <button type="button" class="btn btn-outline-primary rating-number" data-rating="<?= $i ?>"><?= $i ?></button>
                                        <?php endfor ?>
                                        <input type="hidden" id="question_<?= $index ?>" name="question_<?= $index ?>" <?= ($question->required ?? false) ? 'required' : '' ?>>
                                    </div>
                                    <?php break; ?>
                                
                                <?php case 'rating_emoji': ?>
                                    <div class="rating-emojis">
                                        <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="1">😞</button>
                                        <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="2">😐</button>
                                        <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="3">🙂</button>
                                        <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="4">😊</button>
                                        <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="5">😍</button>
                                        <input type="hidden" id="question_<?= $index ?>" name="question_<?= $index ?>" <?= ($question->required ?? false) ? 'required' : '' ?>>
                                    </div>
                                    <?php break; ?>
                                
                                <?php case 'receipt_upload': ?>
                                    <!-- Receipt upload container with camera detection -->
                                    <div class="receipt-upload-container">
                                        <!-- Hidden file inputs for different capture modes -->
                                        <input type="file" 
                                               id="question_<?= $index ?>" 
                                               name="question_<?= $index ?><?= ($question->options->multiple_uploads ?? false) ? '[]' : '' ?>" 
                                               class="form-control receipt-upload-input" 
                                               accept="image/*"
                                               capture="environment"
                                               <?= ($question->options->multiple_uploads ?? false) ? 'multiple' : '' ?>
                                               <?= ($question->required ?? false) ? 'required' : '' ?>
                                               data-max-size="<?= ($question->options->max_file_size ?? 10) * 1024 * 1024 ?>"
                                               style="display: none;">
                                        
                                        <input type="file" 
                                               id="question_<?= $index ?>_gallery" 
                                               name="question_<?= $index ?>_gallery<?= ($question->options->multiple_uploads ?? false) ? '[]' : '' ?>" 
                                               class="form-control receipt-upload-input" 
                                               accept="image/*"
                                               <?= ($question->options->multiple_uploads ?? false) ? 'multiple' : '' ?>
                                               style="display: none;">
                                        
                                        <!-- Upload button container with simple options -->
                                        <div class="receipt-upload-button-container">
                                            <!-- Camera upload button -->
                                            <button type="button" 
                                                    class="btn btn-primary receipt-camera-btn form-trigger-button btn-style-<?= $form_settings->button_trigger_style ?? 'button' ?>" 
                                                    onclick="triggerCameraUpload('question_<?= $index ?>')"
                                                    style="background-color: <?= $form_settings->background_color ?? '#007bff' ?>; border-color: <?= $form_settings->background_color ?? '#007bff' ?>;">
                                                <i class="fas fa-camera mr-2"></i>
                                                Take Photo
                                            </button>
                                            
                                            <!-- Gallery upload button -->
                                            <button type="button" 
                                                    class="btn btn-primary receipt-gallery-btn form-trigger-button btn-style-<?= $form_settings->button_trigger_style ?? 'button' ?>" 
                                                    onclick="triggerGalleryUpload('question_<?= $index ?>')"
                                                    style="background-color: <?= $form_settings->background_color ?? '#007bff' ?>; border-color: <?= $form_settings->background_color ?? '#007bff' ?>;">
                                                <i class="fas fa-images mr-2"></i>
                                                Choose from Gallery
                                            </button>
                                        </div>
                                        
                                        <!-- Upload progress indicator -->
                                        <div class="upload-progress mt-2" id="upload_progress_<?= $index ?>" style="display: none;">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <small class="text-muted">Uploading...</small>
                                        </div>
                                        
                                        <!-- Error messages container -->
                                        <div class="receipt-upload-errors mt-2" id="upload_errors_<?= $index ?>" style="display: none;">
                                            <!-- Error messages will be inserted here -->
                                        </div>
                                        
                                        <!-- Preview container -->
                                        <div class="receipt-preview-container mt-3" id="preview_<?= $index ?>" style="display: none;">
                                            <div class="receipt-preview-header">
                                                <h6 class="mb-2">
                                                    <i class="fas fa-receipt mr-2"></i>
                                                    <?= ($question->options->multiple_uploads ?? false) ? 'Receipt Previews' : 'Receipt Preview' ?>
                                                </h6>
                                            </div>
                                            <div class="receipt-preview-images" id="preview_images_<?= $index ?>">
                                                <!-- Preview images will be inserted here -->
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden AI analysis input (automatically enabled if configured) -->
                                        <?php if($question->options->ai_analysis_enabled ?? false): ?>
                                            <input type="hidden" name="ai_analysis_<?= $index ?>" value="1">
                                        <?php endif ?>
                                    </div>
                                    <?php break; ?>
                                    
                            <?php endswitch ?>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
                
                <?php if($form_settings->show_agreement ?? false): ?>
                    <div class="form-group form-check mb-3">
                        <input type="checkbox" id="agreement_<?= $data->link->microsite_block_id ?>" name="agreement" class="form-check-input" required>
                        <label for="agreement_<?= $data->link->microsite_block_id ?>" class="form-check-label">
                            <?php if($form_settings->agreement_url ?? false): ?>
                                <a href="<?= $form_settings->agreement_url ?>" target="_blank"><?= $form_settings->agreement_text ?? l('microsite_form.agreement_default') ?></a>
                            <?php else: ?>
                                <?= $form_settings->agreement_text ?? l('microsite_form.agreement_default') ?>
                            <?php endif ?>
                            <span class="text-danger">*</span>
                        </label>
                    </div>
                <?php endif ?>
                
                <?php if($form_settings->gdpr_consent_required ?? false): ?>
                    <div class="form-group form-check mb-3">
                        <input type="checkbox" id="gdpr_consent_<?= $data->link->microsite_block_id ?>" name="gdpr_consent" class="form-check-input" required>
                        <label for="gdpr_consent_<?= $data->link->microsite_block_id ?>" class="form-check-label">
                            <?= l('microsite_form.gdpr_consent') ?> <span class="text-danger">*</span>
                        </label>
                    </div>
                <?php endif ?>
                
                <input type="hidden" name="request_type" value="submit_form">
                <input type="hidden" name="microsite_block_id" value="<?= $data->link->microsite_block_id ?>">
                <input type="hidden" name="form_type" value="custom">
                
                <button type="submit" class="btn btn-primary btn-block form-trigger-button btn-style-<?= $form_settings->button_trigger_style ?? 'button' ?>" style="background-color: <?= $form_settings->background_color ?? '#007bff' ?>; border-color: <?= $form_settings->background_color ?? '#007bff' ?>;">
                    <span class="submit-text"><?= $form_settings->button_text ?? l('global.submit') ?></span>
                    <span class="submit-loader d-none">
                        <i class="fas fa-spinner fa-spin"></i> <?= l('global.please_wait') ?>
                    </span>
                </button>
            </form>
        </div>
    <?php endif ?>
</div>

<style>
/* Form Block Styling Options */
.form-style-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-style-minimal {
    background: transparent;
    border: none;
    padding: 10px 0;
}

/* Button Trigger Styles */
.btn-style-button {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
}

.btn-style-link {
    background: transparent !important;
    border: none !important;
    color: #007bff !important;
    text-decoration: underline;
    padding: 5px 0;
}

.btn-style-icon {
    padding: 8px 12px;
    border-radius: 50%;
    min-width: 40px;
    min-height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Modal Form Styles */
[data-modal-style="fullscreen"] {
    /* Fullscreen modal styles will be applied via JavaScript */
}

[data-modal-style="sidebar"] {
    /* Sidebar modal styles will be applied via JavaScript */
}

[data-modal-style="standard"] {
    /* Standard modal styles (default) */
}

.microsite-form .rating-stars .rating-star {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    margin-right: 0.25rem;
    transition: color 0.2s;
}

.microsite-form .rating-stars .rating-star:hover,
.microsite-form .rating-stars .rating-star.active {
    color: #ffc107;
}

.microsite-form .rating-numbers .rating-number {
    margin-right: 0.25rem;
    min-width: 40px;
}

.microsite-form .rating-numbers .rating-number.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.microsite-form .rating-emojis .rating-emoji {
    margin-right: 0.25rem;
    font-size: 1.5rem;
}

.microsite-form .rating-emojis .rating-emoji.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.form-messages {
    margin-bottom: 1rem;
}

.form-messages .alert {
    margin-bottom: 0;
}

/* Receipt Upload Styles */
.receipt-upload-container {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: border-color 0.3s ease;
}

.receipt-upload-container:hover {
    border-color: #007bff;
}

.receipt-upload-input {
    display: none;
}

.receipt-upload-button-container {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.receipt-camera-btn, .receipt-gallery-btn, .receipt-adaptive-btn {
    min-width: 160px;
    padding: 12px 20px;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.receipt-camera-btn:hover, .receipt-adaptive-btn:hover {
    background-color: #007bff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

.receipt-gallery-btn:hover {
    background-color: #6c757d;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108,117,125,0.3);
}

/* Ensure adaptive button is visible */
.receipt-adaptive-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.receipt-preview-container {
    margin-top: 15px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.receipt-preview-header h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
}

.receipt-preview-images {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 10px;
}

.receipt-preview-item {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.receipt-preview-item:hover {
    transform: scale(1.05);
}

.receipt-preview-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
}

.receipt-preview-remove {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
}

.receipt-preview-remove:hover {
    background: rgba(220, 53, 69, 1);
}

.ai-analysis-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-radius: 8px;
    position: relative;
}

.ai-analysis-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #007bff, #6610f2, #e83e8c);
    border-radius: 8px 8px 0 0;
}

.ai-analysis-section .form-check-label {
    cursor: pointer;
    user-select: none;
}

.ai-analysis-section .form-check-input:checked + .form-check-label {
    color: #007bff;
}

.data-extraction-options {
    background: white;
    border-radius: 6px;
    padding: 12px;
    border: 1px solid #e9ecef;
}

.upload-progress {
    margin-top: 10px;
}

.upload-progress .progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
}

.upload-progress .progress-bar {
    background: linear-gradient(90deg, #007bff, #0056b3);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.receipt-upload-errors {
    margin-top: 10px;
}

.receipt-upload-errors .alert {
    margin-bottom: 5px;
    padding: 8px 12px;
    font-size: 14px;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .receipt-upload-button-container {
        flex-direction: column;
        align-items: center;
    }
    
    .receipt-camera-btn, .receipt-gallery-btn {
        width: 100%;
        max-width: 280px;
    }
    
    .receipt-preview-images {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .receipt-preview-item img {
        height: 100px;
    }
    
    .data-extraction-options .row .col-6 {
        margin-bottom: 8px;
    }
}

/* Fix modal z-index issues */
.modal {
    z-index: 9999 !important;
}

.modal-backdrop {
    z-index: 9998 !important;
}

.modal-dialog {
    z-index: 10000 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store form data per block to handle multiple forms on same page
    if (!window.formBlocksData) {
        window.formBlocksData = {};
    }
    
    // Store this form block's data
    window.formBlocksData['<?= $data->link->microsite_block_id ?>'] = {
        formSettings: <?= json_encode($form_settings) ?>,
        questions: <?= json_encode($questions) ?>
    };
    
    // Create and manage form modals dynamically
    function createFormModal(formId, formSettings, questions, micrositeBlockId) {
        // Check if modal already exists
        if (document.getElementById(formId + '_modal')) {
            return;
        }
        
        // Create modal HTML
        const modalHtml = `
            <div class="modal fade" id="${formId}_modal" tabindex="-1" role="dialog" style="z-index: 99999 !important;">
                <div class="modal-dialog" role="document" style="z-index: 100000 !important;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${formSettings.form_heading || formSettings.name || 'Form'}</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ${formSettings.form_text ? `<p class="mb-4">${formSettings.form_text.replace(/\n/g, '<br>')}</p>` : ''}
                            <form id="${formId}_modal_form" class="microsite-form" data-microsite-block-id="${micrositeBlockId}">
                                <div class="form-messages"></div>
                                ${generateQuestionsHtml(questions)}
                                ${generateAgreementHtml(formSettings, micrositeBlockId)}
                                ${generateGdprHtml(formSettings, micrositeBlockId)}
                                <input type="hidden" name="request_type" value="submit_form">
                                <input type="hidden" name="microsite_block_id" value="${micrositeBlockId}">
                                <input type="hidden" name="form_type" value="custom">
                                <button type="submit" class="btn btn-primary btn-block" style="background-color: ${formSettings.background_color || '#007bff'}; border-color: ${formSettings.background_color || '#007bff'};">
                                    <span class="submit-text">${formSettings.button_text || '<?= l('global.submit') ?>'}</span>
                                    <span class="submit-loader d-none">
                                        <i class="fas fa-spinner fa-spin"></i> <?= l('global.please_wait') ?>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Append modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Initialize form handlers for the new modal
        const modalForm = document.getElementById(formId + '_modal_form');
        initializeFormHandlers(modalForm);
        
        // Initialize receipt upload handlers for modal
        modalForm.querySelectorAll('.receipt-upload-input').forEach(function(input) {
            input.addEventListener('change', function(e) {
                handleModalReceiptUpload(e.target);
            });
        });
    }
    
    function generateQuestionsHtml(questions) {
        if (!questions || questions.length === 0) return '';
        
        let html = '';
        questions.forEach((question, index) => {
            html += `<div class="form-group mb-3">`;
            html += `<label for="modal_question_${index}" class="form-label">`;
            html += question.question;
            if (question.required) html += '<span class="text-danger">*</span>';
            html += '</label>';
            
            if (question.description) {
                html += `<small class="form-text text-muted mb-2">${question.description.replace(/\n/g, '<br>')}</small>`;
            }
            
            switch (question.type) {
                case 'text':
                case 'email':
                case 'phone':
                    html += `<input type="${question.type}" id="modal_question_${index}" name="question_${index}" class="form-control" ${question.required ? 'required' : ''}>`;
                    break;
                case 'textarea':
                    html += `<textarea id="modal_question_${index}" name="question_${index}" class="form-control" rows="3" ${question.required ? 'required' : ''}></textarea>`;
                    break;
                case 'checkbox':
                    if (question.options && question.options.choices) {
                        question.options.choices.forEach((choice, choiceIndex) => {
                            html += `<div class="form-check">`;
                            html += `<input type="checkbox" id="modal_question_${index}_${choiceIndex}" name="question_${index}[]" value="${choice}" class="form-check-input">`;
                            html += `<label for="modal_question_${index}_${choiceIndex}" class="form-check-label">${choice}</label>`;
                            html += `</div>`;
                        });
                    }
                    break;
                case 'radio':
                    if (question.options && question.options.choices) {
                        question.options.choices.forEach((choice, choiceIndex) => {
                            html += `<div class="form-check">`;
                            html += `<input type="radio" id="modal_question_${index}_${choiceIndex}" name="question_${index}" value="${choice}" class="form-check-input" ${question.required ? 'required' : ''}>`;
                            html += `<label for="modal_question_${index}_${choiceIndex}" class="form-check-label">${choice}</label>`;
                            html += `</div>`;
                        });
                    }
                    break;
                case 'dropdown':
                    html += `<select id="modal_question_${index}" name="question_${index}" class="form-control" ${question.required ? 'required' : ''}>`;
                    html += '<option value=""><?= l('microsite_form.js.select_option') ?></option>';
                    if (question.options && question.options.choices) {
                        question.options.choices.forEach(choice => {
                            html += `<option value="${choice}">${choice}</option>`;
                        });
                    }
                    html += '</select>';
                    break;
                case 'rating_star':
                    const maxRating = question.options?.max_rating || 5;
                    html += `<div class="rating-stars" data-max-rating="${maxRating}">`;
                    for (let i = 1; i <= maxRating; i++) {
                        html += `<span class="rating-star" data-rating="${i}">★</span>`;
                    }
                    html += `<input type="hidden" id="modal_question_${index}" name="question_${index}" ${question.required ? 'required' : ''}>`;
                    html += '</div>';
                    break;
                case 'rating_number':
                    const maxRatingNum = question.options?.max_rating || 5;
                    html += `<div class="rating-numbers" data-max-rating="${maxRatingNum}">`;
                    for (let i = 1; i <= maxRatingNum; i++) {
                        html += `<button type="button" class="btn btn-outline-primary rating-number" data-rating="${i}">${i}</button>`;
                    }
                    html += `<input type="hidden" id="modal_question_${index}" name="question_${index}" ${question.required ? 'required' : ''}>`;
                    html += '</div>';
                    break;
                case 'rating_emoji':
                    html += '<div class="rating-emojis">';
                    const emojis = ['😞', '😐', '🙂', '😊', '😍'];
                    emojis.forEach((emoji, emojiIndex) => {
                        html += `<button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="${emojiIndex + 1}">${emoji}</button>`;
                    });
                    html += `<input type="hidden" id="modal_question_${index}" name="question_${index}" ${question.required ? 'required' : ''}>`;
                    html += '</div>';
                    break;
            }
            html += '</div>';
        });
        return html;
    }
    
    function generateAgreementHtml(formSettings, micrositeBlockId) {
        if (!formSettings.show_agreement) return '';
        
        let html = '<div class="form-group form-check mb-3">';
        html += `<input type="checkbox" id="modal_agreement_${micrositeBlockId}" name="agreement" class="form-check-input" required>`;
        html += `<label for="modal_agreement_${micrositeBlockId}" class="form-check-label">`;
        
        if (formSettings.agreement_url) {
            html += `<a href="${formSettings.agreement_url}" target="_blank">${formSettings.agreement_text || '<?= l('microsite_form.agreement_default') ?>'}</a>`;
        } else {
            html += formSettings.agreement_text || '<?= l('microsite_form.agreement_default') ?>';
        }
        
        html += '<span class="text-danger">*</span>';
        html += '</label></div>';
        return html;
    }
    
    function generateGdprHtml(formSettings, micrositeBlockId) {
        if (!formSettings.gdpr_consent_required) return '';
        
        let html = '<div class="form-group form-check mb-3">';
        html += `<input type="checkbox" id="modal_gdpr_consent_${micrositeBlockId}" name="gdpr_consent" class="form-check-input" required>`;
        html += `<label for="modal_gdpr_consent_${micrositeBlockId}" class="form-check-label">`;
        html += '<?= l('microsite_form.gdpr_consent') ?> <span class="text-danger">*</span>';
        html += '</label></div>';
        return html;
    }
    
    // Handle modal button clicks
    document.querySelectorAll('[data-toggle="modal"][data-target*="_modal"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetModal = this.getAttribute('data-target').substring(1); // Remove #
            const formId = targetModal.replace('_modal', '');
            const blockElement = this.closest('[data-microsite-block-id]');
            const micrositeBlockId = blockElement.getAttribute('data-microsite-block-id');
            
            // Get the correct form data for this specific block
            const blockData = window.formBlocksData[micrositeBlockId];
            if (!blockData) {
                console.error('Form data not found for block:', micrositeBlockId);
                return;
            }
            
            // Create modal if it doesn't exist
            createFormModal(formId, blockData.formSettings, blockData.questions, micrositeBlockId);
            
            // Show modal
            $('#' + targetModal).modal('show');
        });
    });
    
    function initializeFormHandlers(form) {
        if (!form) return;
        
        // Initialize rating interactions for this form
        initializeRatingHandlers(form);
        
        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            const submitText = submitButton.querySelector('.submit-text');
            const submitLoader = submitButton.querySelector('.submit-loader');
            const messagesContainer = form.querySelector('.form-messages');
            
            // Show loading state
            submitButton.disabled = true;
            submitText.classList.add('d-none');
            submitLoader.classList.remove('d-none');
            
            // Clear previous messages
            messagesContainer.innerHTML = '';
            
            // Prepare form data
            const formData = new FormData(form);
            
            // Submit form
            fetch('<?= url('ajax') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messagesContainer.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    form.reset();
                    
                    // Reset rating inputs
                    form.querySelectorAll('.rating-stars .rating-star, .rating-numbers .rating-number, .rating-emojis .rating-emoji').forEach(function(el) {
                        el.classList.remove('active');
                    });
                    form.querySelectorAll('.rating-stars input, .rating-numbers input, .rating-emojis input').forEach(function(input) {
                        input.value = '';
                    });
                    
                    // Redirect if thank you URL is provided
                    if (data.details && data.details.thank_you_url) {
                        setTimeout(function() {
                            window.location.href = data.details.thank_you_url;
                        }, 2000);
                    }
                    
                    // Close modal if in modal mode
                    const modal = form.closest('.modal');
                    if (modal) {
                        $(modal).modal('hide');
                    }
                } else {
                    messagesContainer.innerHTML = '<div class="alert alert-danger">' + (data.message || 'An error occurred. Please try again.') + '</div>';
                }
            })
            .catch(error => {
                console.error('Form submission error:', error);
                messagesContainer.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitText.classList.remove('d-none');
                submitLoader.classList.add('d-none');
            });
        });
    }
    
    function initializeRatingHandlers(container) {
        // Handle star ratings
        container.querySelectorAll('.rating-stars').forEach(function(ratingContainer) {
            const stars = ratingContainer.querySelectorAll('.rating-star');
            const input = ratingContainer.querySelector('input[type="hidden"]');
            
            stars.forEach(function(star, index) {
                star.addEventListener('click', function() {
                    const rating = parseInt(star.dataset.rating);
                    input.value = rating;
                    
                    stars.forEach(function(s, i) {
                        if (i < rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });
                
                star.addEventListener('mouseover', function() {
                    const rating = parseInt(star.dataset.rating);
                    stars.forEach(function(s, i) {
                        if (i < rating) {
                            s.style.color = '#ffc107';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
            });
            
            ratingContainer.addEventListener('mouseleave', function() {
                const currentRating = parseInt(input.value) || 0;
                stars.forEach(function(s, i) {
                    if (i < currentRating) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });
        
        // Handle number ratings
        container.querySelectorAll('.rating-numbers .rating-number').forEach(function(button) {
            button.addEventListener('click', function() {
                const ratingContainer = button.closest('.rating-numbers');
                const input = ratingContainer.querySelector('input[type="hidden"]');
                const rating = parseInt(button.dataset.rating);
                
                input.value = rating;
                
                ratingContainer.querySelectorAll('.rating-number').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
            });
        });
        
        // Handle emoji ratings
        container.querySelectorAll('.rating-emojis .rating-emoji').forEach(function(button) {
            button.addEventListener('click', function() {
                const ratingContainer = button.closest('.rating-emojis');
                const input = ratingContainer.querySelector('input[type="hidden"]');
                const rating = parseInt(button.dataset.rating);
                
                input.value = rating;
                
                ratingContainer.querySelectorAll('.rating-emoji').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
            });
        });
    }
    
    // Initialize existing inline forms
    document.querySelectorAll('.microsite-form').forEach(function(form) {
        initializeFormHandlers(form);
    });
    
    // Receipt Upload Functions
    window.openGalleryUpload = function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            // Remove capture attribute to allow gallery selection
            input.removeAttribute('capture');
            input.click();
            // Re-add capture attribute after selection
            setTimeout(() => {
                input.setAttribute('capture', 'camera');
            }, 100);
        }
    };
    
    // Simple upload functions
    window.triggerCameraUpload = function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.setAttribute('capture', 'environment');
            input.click();
        }
    };
    
    window.triggerGalleryUpload = function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.removeAttribute('capture');
            input.click();
        }
    };
    
    // Initialize receipt upload handlers
    document.querySelectorAll('.receipt-upload-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            handleReceiptUpload(e.target);
        });
    });
    
    function handleReceiptUpload(input) {
        const files = Array.from(input.files);
        const questionIndex = input.id.replace('question_', '');
        const previewContainer = document.getElementById('preview_' + questionIndex);
        const previewImages = document.getElementById('preview_images_' + questionIndex);
        const errorContainer = document.getElementById('upload_errors_' + questionIndex);
        const progressContainer = document.getElementById('upload_progress_' + questionIndex);
        const maxSize = parseInt(input.dataset.maxSize) || (10 * 1024 * 1024); // 10MB default
        const isMultiple = input.hasAttribute('multiple');
        
        // Clear previous errors
        errorContainer.style.display = 'none';
        errorContainer.innerHTML = '';
        
        // Validate files
        const validFiles = [];
        const errors = [];
        
        files.forEach((file, index) => {
            // Check file type
            if (!file.type.startsWith('image/')) {
                errors.push(`File ${index + 1}: Only image files are allowed`);
                return;
            }
            
            // Check file size
            if (file.size > maxSize) {
                const maxSizeMB = Math.round(maxSize / (1024 * 1024));
                errors.push(`File ${index + 1}: File size exceeds ${maxSizeMB}MB limit`);
                return;
            }
            
            validFiles.push(file);
        });
        
        // Show errors if any
        if (errors.length > 0) {
            errorContainer.innerHTML = errors.map(error => 
                `<div class="alert alert-danger">${error}</div>`
            ).join('');
            errorContainer.style.display = 'block';
        }
        
        // Process valid files
        if (validFiles.length > 0) {
            // Clear existing previews if not multiple upload
            if (!isMultiple) {
                previewImages.innerHTML = '';
            }
            
            // Show preview container
            previewContainer.style.display = 'block';
            
            // Process each valid file
            validFiles.forEach((file, index) => {
                createImagePreview(file, previewImages, questionIndex, index);
            });
            
            // Show AI analysis status if enabled
            const aiCheckbox = document.getElementById('ai_analysis_' + questionIndex);
            const aiStatus = document.getElementById('ai_status_' + questionIndex);
            if (aiCheckbox && aiCheckbox.checked && aiStatus) {
                aiStatus.style.display = 'block';
            }
        }
        
        // Update file input with valid files only
        if (validFiles.length !== files.length) {
            // Create new FileList with valid files only
            const dt = new DataTransfer();
            validFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
        }
    }
    
    function handleModalReceiptUpload(input) {
        const files = Array.from(input.files);
        const questionIndex = input.id.replace('modal_question_', '').replace('_gallery', '');
        const previewContainer = document.getElementById('modal_preview_' + questionIndex);
        const previewImages = document.getElementById('modal_preview_images_' + questionIndex);
        const errorContainer = document.getElementById('modal_upload_errors_' + questionIndex);
        const progressContainer = document.getElementById('modal_upload_progress_' + questionIndex);
        const maxSize = parseInt(input.dataset.maxSize) || (10 * 1024 * 1024); // 10MB default
        const isMultiple = input.hasAttribute('multiple');
        
        // Clear previous errors
        errorContainer.style.display = 'none';
        errorContainer.innerHTML = '';
        
        // Validate files
        const validFiles = [];
        const errors = [];
        
        files.forEach((file, index) => {
            // Check file type
            if (!file.type.startsWith('image/')) {
                errors.push(`File ${index + 1}: Only image files are allowed`);
                return;
            }
            
            // Check file size
            if (file.size > maxSize) {
                const maxSizeMB = Math.round(maxSize / (1024 * 1024));
                errors.push(`File ${index + 1}: File size exceeds ${maxSizeMB}MB limit`);
                return;
            }
            
            validFiles.push(file);
        });
        
        // Show errors if any
        if (errors.length > 0) {
            errorContainer.innerHTML = errors.map(error => 
                `<div class="alert alert-danger">${error}</div>`
            ).join('');
            errorContainer.style.display = 'block';
        }
        
        // Process valid files
        if (validFiles.length > 0) {
            // Clear existing previews if not multiple upload
            if (!isMultiple) {
                previewImages.innerHTML = '';
            }
            
            // Show preview container
            previewContainer.style.display = 'block';
            
            // Process each valid file
            validFiles.forEach((file, index) => {
                createModalImagePreview(file, previewImages, questionIndex, index);
            });
            
            // Show AI analysis status if enabled
            const aiCheckbox = document.getElementById('modal_ai_analysis_' + questionIndex);
            const aiStatus = document.getElementById('modal_ai_status_' + questionIndex);
            if (aiCheckbox && aiCheckbox.checked && aiStatus) {
                aiStatus.style.display = 'block';
            }
        }
        
        // Update file input with valid files only
        if (validFiles.length !== files.length) {
            // Create new FileList with valid files only
            const dt = new DataTransfer();
            validFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
        }
    }
    
    function createImagePreview(file, container, questionIndex, fileIndex) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'receipt-preview-item';
            previewItem.dataset.fileIndex = fileIndex;
            
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Receipt preview" loading="lazy">
                <button type="button" class="receipt-preview-remove" onclick="removeReceiptPreview('${questionIndex}', ${fileIndex})" title="Remove image">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(previewItem);
            
            // Add fade-in animation
            setTimeout(() => {
                previewItem.style.opacity = '0';
                previewItem.style.transform = 'scale(0.8)';
                previewItem.style.transition = 'all 0.3s ease';
                
                requestAnimationFrame(() => {
                    previewItem.style.opacity = '1';
                    previewItem.style.transform = 'scale(1)';
                });
            }, 10);
        };
        
        reader.readAsDataURL(file);
    }
    
    function createModalImagePreview(file, container, questionIndex, fileIndex) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'receipt-preview-item';
            previewItem.dataset.fileIndex = fileIndex;
            
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Receipt preview" loading="lazy">
                <button type="button" class="receipt-preview-remove" onclick="removeModalReceiptPreview('${questionIndex}', ${fileIndex})" title="Remove image">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(previewItem);
            
            // Add fade-in animation
            setTimeout(() => {
                previewItem.style.opacity = '0';
                previewItem.style.transform = 'scale(0.8)';
                previewItem.style.transition = 'all 0.3s ease';
                
                requestAnimationFrame(() => {
                    previewItem.style.opacity = '1';
                    previewItem.style.transform = 'scale(1)';
                });
            }, 10);
        };
        
        reader.readAsDataURL(file);
    }
    
    window.removeReceiptPreview = function(questionIndex, fileIndex) {
        const input = document.getElementById('question_' + questionIndex);
        const previewContainer = document.getElementById('preview_' + questionIndex);
        const previewImages = document.getElementById('preview_images_' + questionIndex);
        const previewItem = previewImages.querySelector(`[data-file-index="${fileIndex}"]`);
        
        if (previewItem) {
            // Animate removal
            previewItem.style.transition = 'all 0.3s ease';
            previewItem.style.opacity = '0';
            previewItem.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                previewItem.remove();
                
                // Hide preview container if no more images
                if (previewImages.children.length === 0) {
                    previewContainer.style.display = 'none';
                }
            }, 300);
        }
        
        // Update file input
        if (input && input.files) {
            const dt = new DataTransfer();
            Array.from(input.files).forEach((file, index) => {
                if (index !== fileIndex) {
                    dt.items.add(file);
                }
            });
            input.files = dt.files;
            
            // Re-index remaining preview items
            Array.from(previewImages.children).forEach((item, newIndex) => {
                item.dataset.fileIndex = newIndex;
                const removeBtn = item.querySelector('.receipt-preview-remove');
                if (removeBtn) {
                    removeBtn.setAttribute('onclick', `removeReceiptPreview('${questionIndex}', ${newIndex})`);
                }
            });
        }
    };
    
    window.removeModalReceiptPreview = function(questionIndex, fileIndex) {
        const input = document.getElementById('modal_question_' + questionIndex);
        const previewContainer = document.getElementById('modal_preview_' + questionIndex);
        const previewImages = document.getElementById('modal_preview_images_' + questionIndex);
        const previewItem = previewImages.querySelector(`[data-file-index="${fileIndex}"]`);
        
        if (previewItem) {
            // Animate removal
            previewItem.style.transition = 'all 0.3s ease';
            previewItem.style.opacity = '0';
            previewItem.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                previewItem.remove();
                
                // Hide preview container if no more images
                if (previewImages.children.length === 0) {
                    previewContainer.style.display = 'none';
                }
            }, 300);
        }
        
        // Update file input
        if (input && input.files) {
            const dt = new DataTransfer();
            Array.from(input.files).forEach((file, index) => {
                if (index !== fileIndex) {
                    dt.items.add(file);
                }
            });
            input.files = dt.files;
            
            // Re-index remaining preview items
            Array.from(previewImages.children).forEach((item, newIndex) => {
                item.dataset.fileIndex = newIndex;
                const removeBtn = item.querySelector('.receipt-preview-remove');
                if (removeBtn) {
                    removeBtn.setAttribute('onclick', `removeModalReceiptPreview('${questionIndex}', ${newIndex})`);
                }
            });
        }
    };
    
    
    // Add receipt upload support to modal generation
    const originalGenerateQuestionsHtml = generateQuestionsHtml;
    generateQuestionsHtml = function(questions) {
        if (!questions || questions.length === 0) return '';
        
        let html = '';
        questions.forEach((question, index) => {
            html += `<div class="form-group mb-3">`;
            html += `<label for="modal_question_${index}" class="form-label">`;
            html += question.question;
            if (question.required) html += '<span class="text-danger">*</span>';
            html += '</label>';
            
            if (question.description) {
                html += `<small class="form-text text-muted mb-2">${question.description.replace(/\n/g, '<br>')}</small>`;
            }
            
            switch (question.type) {
                case 'text':
                case 'email':
                case 'phone':
                    html += `<input type="${question.type}" id="modal_question_${index}" name="question_${index}" class="form-control" ${question.required ? 'required' : ''}>`;
                    break;
                case 'textarea':
                    html += `<textarea id="modal_question_${index}" name="question_${index}" class="form-control" rows="3" ${question.required ? 'required' : ''}></textarea>`;
                    break;
                case 'checkbox':
                    if (question.options && question.options.choices) {
                        question.options.choices.forEach((choice, choiceIndex) => {
                            html += `<div class="form-check">`;
                            html += `<input type="checkbox" id="modal_question_${index}_${choiceIndex}" name="question_${index}[]" value="${choice}" class="form-check-input">`;
                            html += `<label for="modal_question_${index}_${choiceIndex}" class="form-check-label">${choice}</label>`;
                            html += `</div>`;
                        });
                    }
                    break;
                case 'radio':
                    if (question.options && question.options.choices) {
                        question.options.choices.forEach((choice, choiceIndex) => {
                            html += `<div class="form-check">`;
                            html += `<input type="radio" id="modal_question_${index}_${choiceIndex}" name="question_${index}" value="${choice}" class="form-check-input" ${question.required ? 'required' : ''}>`;
                            html += `<label for="modal_question_${index}_${choiceIndex}" class="form-check-label">${choice}</label>`;
                            html += `</div>`;
                        });
                    }
                    break;
                case 'dropdown':
                    html += `<select id="modal_question_${index}" name="question_${index}" class="form-control" ${question.required ? 'required' : ''}>`;
                    html += '<option value="">Select an option...</option>';
                    if (question.options && question.options.choices) {
                        question.options.choices.forEach(choice => {
                            html += `<option value="${choice}">${choice}</option>`;
                        });
                    }
                    html += '</select>';
                    break;
                case 'rating_star':
                    const maxRating = question.options?.max_rating || 5;
                    html += `<div class="rating-stars" data-max-rating="${maxRating}">`;
                    for (let i = 1; i <= maxRating; i++) {
                        html += `<span class="rating-star" data-rating="${i}">★</span>`;
                    }
                    html += `<input type="hidden" id="modal_question_${index}" name="question_${index}" ${question.required ? 'required' : ''}>`;
                    html += '</div>';
                    break;
                case 'rating_number':
                    const maxRatingNum = question.options?.max_rating || 5;
                    html += `<div class="rating-numbers" data-max-rating="${maxRatingNum}">`;
                    for (let i = 1; i <= maxRatingNum; i++) {
                        html += `<button type="button" class="btn btn-outline-primary rating-number" data-rating="${i}">${i}</button>`;
                    }
                    html += `<input type="hidden" id="modal_question_${index}" name="question_${index}" ${question.required ? 'required' : ''}>`;
                    html += '</div>';
                    break;
                case 'rating_emoji':
                    html += '<div class="rating-emojis">';
                    const emojis = ['😞', '😐', '🙂', '😊', '😍'];
                    emojis.forEach((emoji, emojiIndex) => {
                        html += `<button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="${emojiIndex + 1}">${emoji}</button>`;
                    });
                    html += `<input type="hidden" id="modal_question_${index}" name="question_${index}" ${question.required ? 'required' : ''}>`;
                    html += '</div>';
                    break;
                case 'receipt_upload':
                    const multipleAttr = question.options?.multiple_uploads ? 'multiple' : '';
                    const maxSize = (question.options?.max_file_size || 10) * 1024 * 1024;
                    
                    html += `<div class="receipt-upload-container">`;
                    // Hidden file inputs for different capture modes
                    html += `<input type="file" id="modal_question_${index}" name="question_${index}${question.options?.multiple_uploads ? '[]' : ''}" class="form-control receipt-upload-input" accept="image/*" capture="environment" ${multipleAttr} ${question.required ? 'required' : ''} data-max-size="${maxSize}" style="display: none;">`;
                    html += `<input type="file" id="modal_question_${index}_gallery" name="question_${index}_gallery${question.options?.multiple_uploads ? '[]' : ''}" class="form-control receipt-upload-input" accept="image/*" ${multipleAttr} style="display: none;">`;
                    
                    // Upload button container with simple options
                    html += `<div class="receipt-upload-button-container">`;
                    html += `<button type="button" class="btn btn-primary receipt-camera-btn form-trigger-button btn-style-button" onclick="triggerCameraUpload('modal_question_${index}')" style="background-color: #007bff; border-color: #007bff;">`;
                    html += `<i class="fas fa-camera mr-2"></i>Take Photo`;
                    html += `</button>`;
                    html += `<button type="button" class="btn btn-primary receipt-gallery-btn form-trigger-button btn-style-button" onclick="triggerGalleryUpload('modal_question_${index}')" style="background-color: #007bff; border-color: #007bff;">`;
                    html += `<i class="fas fa-images mr-2"></i>Choose from Gallery`;
                    html += `</button>`;
                    html += `</div>`;
                    
                    // Upload progress indicator
                    html += `<div class="upload-progress mt-2" id="modal_upload_progress_${index}" style="display: none;">`;
                    html += `<div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%"></div></div>`;
                    html += `<small class="text-muted">Uploading...</small>`;
                    html += `</div>`;
                    
                    // Error messages container
                    html += `<div class="receipt-upload-errors mt-2" id="modal_upload_errors_${index}" style="display: none;">`;
                    html += `<!-- Error messages will be inserted here -->`;
                    html += `</div>`;
                    
                    // Preview container
                    html += `<div class="receipt-preview-container mt-3" id="modal_preview_${index}" style="display: none;">`;
                    html += `<div class="receipt-preview-header">`;
                    html += `<h6 class="mb-2"><i class="fas fa-receipt mr-2"></i>${question.options?.multiple_uploads ? 'Receipt Previews' : 'Receipt Preview'}</h6>`;
                    html += `</div>`;
                    html += `<div class="receipt-preview-images" id="modal_preview_images_${index}">`;
                    html += `<!-- Preview images will be inserted here -->`;
                    html += `</div>`;
                    html += `</div>`;
                    
                    // Hidden AI analysis input (automatically enabled if configured)
                    if (question.options?.ai_analysis_enabled) {
                        html += `<input type="hidden" name="ai_analysis_${index}" value="1">`;
                    }
                    
                    html += `</div>`;
                    break;
            }
            html += '</div>';
        });
        return html;
    };
    // Handle rating interactions
    document.querySelectorAll('.rating-stars').forEach(function(container) {
        const stars = container.querySelectorAll('.rating-star');
        const input = container.querySelector('input[type="hidden"]');
        
        stars.forEach(function(star, index) {
            star.addEventListener('click', function() {
                const rating = parseInt(star.dataset.rating);
                input.value = rating;
                
                stars.forEach(function(s, i) {
                    if (i < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseover', function() {
                const rating = parseInt(star.dataset.rating);
                stars.forEach(function(s, i) {
                    if (i < rating) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });
        
        container.addEventListener('mouseleave', function() {
            const currentRating = parseInt(input.value) || 0;
            stars.forEach(function(s, i) {
                if (i < currentRating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });
    
    // Handle number ratings
    document.querySelectorAll('.rating-numbers .rating-number').forEach(function(button) {
        button.addEventListener('click', function() {
            const container = button.closest('.rating-numbers');
            const input = container.querySelector('input[type="hidden"]');
            const rating = parseInt(button.dataset.rating);
            
            input.value = rating;
            
            container.querySelectorAll('.rating-number').forEach(function(btn) {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        });
    });
    
    // Handle emoji ratings
    document.querySelectorAll('.rating-emojis .rating-emoji').forEach(function(button) {
        button.addEventListener('click', function() {
            const container = button.closest('.rating-emojis');
            const input = container.querySelector('input[type="hidden"]');
            const rating = parseInt(button.dataset.rating);
            
            input.value = rating;
            
            container.querySelectorAll('.rating-emoji').forEach(function(btn) {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        });
    });
    
    // Handle form submission
    document.querySelectorAll('.microsite-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            const submitText = submitButton.querySelector('.submit-text');
            const submitLoader = submitButton.querySelector('.submit-loader');
            const messagesContainer = form.querySelector('.form-messages');
            
            // Show loading state
            submitButton.disabled = true;
            submitText.classList.add('d-none');
            submitLoader.classList.remove('d-none');
            
            // Clear previous messages
            messagesContainer.innerHTML = '';
            
            // Prepare form data
            const formData = new FormData(form);
            
            // Submit form
            fetch('<?= url('ajax') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messagesContainer.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    form.reset();
                    
                    // Reset rating inputs
                    form.querySelectorAll('.rating-stars .rating-star, .rating-numbers .rating-number, .rating-emojis .rating-emoji').forEach(function(el) {
                        el.classList.remove('active');
                    });
                    form.querySelectorAll('.rating-stars input, .rating-numbers input, .rating-emojis input').forEach(function(input) {
                        input.value = '';
                    });
                    
                    // Redirect if thank you URL is provided
                    if (data.details && data.details.thank_you_url) {
                        setTimeout(function() {
                            window.location.href = data.details.thank_you_url;
                        }, 2000);
                    }
                    
                    // Close modal if in modal mode
                    const modal = form.closest('.modal');
                    if (modal) {
                        $(modal).modal('hide');
                    }
                } else {
                    messagesContainer.innerHTML = '<div class="alert alert-danger">' + (data.message || 'An error occurred. Please try again.') + '</div>';
                }
            })
            .catch(error => {
                console.error('Form submission error:', error);
                messagesContainer.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitText.classList.remove('d-none');
                submitLoader.classList.add('d-none');
            });
        });
    });
});
</script>
