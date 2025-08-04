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

// Handle border radius - following exact image block pattern
if (isset($form_settings->border_radius)) {
    switch ($form_settings->border_radius) {
        case 'straight':
            $all_styles[] = 'border-radius: 0';
            break;
        case 'round':
            $all_styles[] = 'border-radius: 50px';
            break;
        case 'rounded':
            $all_styles[] = 'border-radius: 0.25rem';
            break;
        case 'rounded-sm':
            $all_styles[] = 'border-radius: 0.125rem';
            break;
        case 'rounded-lg':
            $all_styles[] = 'border-radius: 0.5rem';
            break;
        case 'rounded-xl':
            $all_styles[] = 'border-radius: 0.75rem';
            break;
        case 'rounded-2xl':
            $all_styles[] = 'border-radius: 1rem';
            break;
        case 'rounded-3xl':
            $all_styles[] = 'border-radius: 1.5rem';
            break;
        case 'rounded-full':
            $all_styles[] = 'border-radius: 9999px';
            break;
    }
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
        <div class="microsite-form-block <?= $animation_class ?>" <?= $inline_style_attribute ?>>
            
            <?php if($form_settings->image ?? false): ?>
                <div class="text-center mb-3">
                    <img src="<?= \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $form_settings->image ?>" class="img-fluid" loading="lazy" style="max-height: 100px; width: auto;" />
                </div>
            <?php endif ?>

            <?php if($form_settings->form_heading ?? false): ?>
                <h4 class="mb-3"><?= $form_settings->form_heading ?></h4>
            <?php endif ?>

            <?php if($form_settings->form_text ?? false): ?>
                <p class="mb-4"><?= nl2br($form_settings->form_text) ?></p>
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
                                        <option value="">Select an option...</option>
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
                                    
                            <?php endswitch ?>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
                
                <?php if($form_settings->show_agreement ?? false): ?>
                    <div class="form-group form-check mb-3">
                        <input type="checkbox" id="agreement_<?= $data->link->microsite_block_id ?>" name="agreement" class="form-check-input" required>
                        <label for="agreement_<?= $data->link->microsite_block_id ?>" class="form-check-label">
                            <?php if($form_settings->agreement_url ?? false): ?>
                                <a href="<?= $form_settings->agreement_url ?>" target="_blank"><?= $form_settings->agreement_text ?? 'I agree to the terms and conditions' ?></a>
                            <?php else: ?>
                                <?= $form_settings->agreement_text ?? 'I agree to the terms and conditions' ?>
                            <?php endif ?>
                            <span class="text-danger">*</span>
                        </label>
                    </div>
                <?php endif ?>
                
                <?php if($form_settings->gdpr_consent_required ?? false): ?>
                    <div class="form-group form-check mb-3">
                        <input type="checkbox" id="gdpr_consent_<?= $data->link->microsite_block_id ?>" name="gdpr_consent" class="form-check-input" required>
                        <label for="gdpr_consent_<?= $data->link->microsite_block_id ?>" class="form-check-label">
                            I consent to the processing of my personal data <span class="text-danger">*</span>
                        </label>
                    </div>
                <?php endif ?>
                
                <input type="hidden" name="request_type" value="submit_form">
                <input type="hidden" name="microsite_block_id" value="<?= $data->link->microsite_block_id ?>">
                <input type="hidden" name="form_type" value="custom">
                
                <button type="submit" class="btn btn-primary btn-block" style="background-color: <?= $form_settings->background_color ?? '#007bff' ?>; border-color: <?= $form_settings->background_color ?? '#007bff' ?>;">
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
                            <form id="${formId}_modal_form" class="microsite-form" data-microsite-block-id="${micrositeBlockId}">
                                <div class="form-messages"></div>
                                ${generateQuestionsHtml(questions)}
                                ${generateAgreementHtml(formSettings, micrositeBlockId)}
                                ${generateGdprHtml(formSettings, micrositeBlockId)}
                                <input type="hidden" name="request_type" value="submit_form">
                                <input type="hidden" name="microsite_block_id" value="${micrositeBlockId}">
                                <input type="hidden" name="form_type" value="custom">
                                <button type="submit" class="btn btn-primary btn-block" style="background-color: ${formSettings.background_color || '#007bff'}; border-color: ${formSettings.background_color || '#007bff'};">
                                    <span class="submit-text">${formSettings.button_text || 'Submit'}</span>
                                    <span class="submit-loader d-none">
                                        <i class="fas fa-spinner fa-spin"></i> Please wait...
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
        initializeFormHandlers(document.getElementById(formId + '_modal_form'));
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
            html += `<a href="${formSettings.agreement_url}" target="_blank">${formSettings.agreement_text || 'I agree to the terms and conditions'}</a>`;
        } else {
            html += formSettings.agreement_text || 'I agree to the terms and conditions';
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
        html += 'I consent to the processing of my personal data <span class="text-danger">*</span>';
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
            
            // Get form settings from PHP (we'll need to pass this data)
            const formSettings = <?= json_encode($form_settings) ?>;
            const questions = <?= json_encode($questions) ?>;
            
            // Create modal if it doesn't exist
            createFormModal(formId, formSettings, questions, micrositeBlockId);
            
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
            fetch('<?= url('microsite-block-ajax') ?>', {
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
            fetch('<?= url('microsite-block-ajax') ?>', {
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
