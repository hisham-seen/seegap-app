<?php defined('SEEGAP') || die() ?>

<?php
/* Check for display rules */
if(!$data->microsite_block->is_display_enabled) {
    return;
}

/* Prepare variables */
$microsite_block = $data->microsite_block;
$settings = $microsite_block->settings;
$form_type = $settings->form_type ?? 'email';

/* Generate unique form ID */
$form_id = 'form_' . $microsite_block->microsite_block_id;

/* Prepare CSS classes */
$css_classes = [
    'microsite-block',
    'microsite-block-form',
    'microsite-block-form-' . $form_type,
    $settings->border_radius ?? 'rounded',
];

if($settings->animation ?? false) {
    $css_classes[] = 'animate__animated';
    $css_classes[] = 'animate__' . $settings->animation;
    if($settings->animation_runs ?? 'repeat-1' != 'repeat-1') {
        $css_classes[] = 'animate__' . $settings->animation_runs;
    }
}

/* Prepare inline styles */
$inline_styles = [];
$inline_styles[] = 'color: ' . ($settings->text_color ?? '#000000');
$inline_styles[] = 'background-color: ' . ($settings->background_color ?? '#ffffff');
$inline_styles[] = 'text-align: ' . ($settings->text_alignment ?? 'center');

if(isset($settings->border_width) && $settings->border_width > 0) {
    $inline_styles[] = 'border: ' . $settings->border_width . 'px ' . ($settings->border_style ?? 'solid') . ' ' . ($settings->border_color ?? '#000000');
}

if(isset($settings->border_shadow_blur) && $settings->border_shadow_blur > 0) {
    $shadow_x = $settings->border_shadow_offset_x ?? 0;
    $shadow_y = $settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $settings->border_shadow_blur ?? 0;
    $shadow_spread = $settings->border_shadow_spread ?? 0;
    $shadow_color = $settings->border_shadow_color ?? '#000000';
    $inline_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
}

$style_attribute = 'style="' . implode('; ', $inline_styles) . '"';
?>

<div class="<?= implode(' ', $css_classes) ?>" <?= $style_attribute ?> data-microsite-block-id="<?= $microsite_block->microsite_block_id ?>">
    
    <?php if(!empty($settings->image) || !empty($settings->icon)): ?>
        <div class="microsite-block-form-media mb-3">
            <?php if(!empty($settings->image)): ?>
                <img src="<?= \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $settings->image ?>" class="img-fluid" alt="<?= $settings->name ?>" loading="lazy" />
            <?php elseif(!empty($settings->icon)): ?>
                <i class="<?= $settings->icon ?> fa-2x mb-2"></i>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if($form_type == 'custom' && (!empty($settings->form_heading) || !empty($settings->form_text))): ?>
        <div class="microsite-block-form-header mb-4">
            <?php if(!empty($settings->form_heading)): ?>
                <h3 class="microsite-block-form-heading"><?= $settings->form_heading ?></h3>
            <?php endif ?>
            <?php if(!empty($settings->form_text)): ?>
                <p class="microsite-block-form-text"><?= nl2br($settings->form_text) ?></p>
            <?php endif ?>
        </div>
    <?php endif ?>

    <form id="<?= $form_id ?>" class="microsite-block-form-container" data-form-type="<?= $form_type ?>" data-microsite-block-id="<?= $microsite_block->microsite_block_id ?>">
        
        <?php if($form_type == 'email'): ?>
            <!-- Email Collection Form -->
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="<?= $settings->email_placeholder ?? l('microsite_email_collector.email_placeholder_default') ?>" required />
            </div>
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="<?= $settings->name_placeholder ?? l('microsite_email_collector.name_placeholder_default') ?>" />
            </div>

        <?php elseif($form_type == 'phone'): ?>
            <!-- Phone Collection Form -->
            <div class="form-group">
                <input type="tel" name="phone" class="form-control" placeholder="<?= $settings->phone_placeholder ?? l('microsite_phone_collector.phone_placeholder_default') ?>" required />
            </div>
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="<?= $settings->name_placeholder ?? l('microsite_phone_collector.name_placeholder_default') ?>" />
            </div>

        <?php elseif($form_type == 'contact'): ?>
            <!-- Contact Form -->
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="<?= $settings->email_placeholder ?? l('microsite_contact_collector.email_placeholder_default') ?>" required />
            </div>
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="<?= $settings->name_placeholder ?? l('microsite_contact_collector.name_placeholder_default') ?>" />
            </div>
            <div class="form-group">
                <textarea name="message" class="form-control" rows="4" placeholder="<?= $settings->message_placeholder ?? l('microsite_contact_collector.message_placeholder_default') ?>" required></textarea>
            </div>

        <?php elseif($form_type == 'custom' && isset($settings->questions) && is_array($settings->questions)): ?>
            <!-- Custom Form Questions -->
            <?php foreach($settings->questions as $index => $question): ?>
                <div class="form-group" data-question-index="<?= $index ?>">
                    <label class="form-label"><?= $question->question ?><?= $question->required ? ' *' : '' ?></label>
                    
                    <?php if($question->type == 'text'): ?>
                        <input type="text" name="question_<?= $index ?>" class="form-control" <?= $question->required ? 'required' : '' ?> />
                    
                    <?php elseif($question->type == 'textarea'): ?>
                        <textarea name="question_<?= $index ?>" class="form-control" rows="3" <?= $question->required ? 'required' : '' ?>></textarea>
                    
                    <?php elseif($question->type == 'email'): ?>
                        <input type="email" name="question_<?= $index ?>" class="form-control" <?= $question->required ? 'required' : '' ?> />
                    
                    <?php elseif($question->type == 'phone'): ?>
                        <input type="tel" name="question_<?= $index ?>" class="form-control" <?= $question->required ? 'required' : '' ?> />
                    
                    <?php elseif($question->type == 'rating_star'): ?>
                        <div class="rating-stars" data-max-rating="<?= $question->options->max_rating ?? 5 ?>">
                            <?php for($i = 1; $i <= ($question->options->max_rating ?? 5); $i++): ?>
                                <span class="rating-star" data-rating="<?= $i ?>">
                                    <i class="far fa-star"></i>
                                </span>
                            <?php endfor ?>
                            <input type="hidden" name="question_<?= $index ?>" <?= $question->required ? 'required' : '' ?> />
                        </div>
                    
                    <?php elseif($question->type == 'rating_number'): ?>
                        <div class="rating-numbers" data-max-rating="<?= $question->options->max_rating ?? 5 ?>">
                            <?php for($i = 1; $i <= ($question->options->max_rating ?? 5); $i++): ?>
                                <button type="button" class="btn btn-outline-primary rating-number" data-rating="<?= $i ?>"><?= $i ?></button>
                            <?php endfor ?>
                            <input type="hidden" name="question_<?= $index ?>" <?= $question->required ? 'required' : '' ?> />
                        </div>
                    
                    <?php elseif($question->type == 'rating_emoji'): ?>
                        <div class="rating-emojis">
                            <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="1">😞</button>
                            <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="2">😐</button>
                            <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="3">🙂</button>
                            <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="4">😊</button>
                            <button type="button" class="btn btn-outline-secondary rating-emoji" data-rating="5">😍</button>
                            <input type="hidden" name="question_<?= $index ?>" <?= $question->required ? 'required' : '' ?> />
                        </div>
                    
                    <?php elseif($question->type == 'checkbox' && isset($question->options->choices)): ?>
                        <?php foreach($question->options->choices as $choice_index => $choice): ?>
                            <div class="form-check">
                                <input type="checkbox" name="question_<?= $index ?>[]" value="<?= $choice ?>" id="question_<?= $index ?>_<?= $choice_index ?>" class="form-check-input" />
                                <label class="form-check-label" for="question_<?= $index ?>_<?= $choice_index ?>"><?= $choice ?></label>
                            </div>
                        <?php endforeach ?>
                    
                    <?php elseif($question->type == 'radio' && isset($question->options->choices)): ?>
                        <?php foreach($question->options->choices as $choice_index => $choice): ?>
                            <div class="form-check">
                                <input type="radio" name="question_<?= $index ?>" value="<?= $choice ?>" id="question_<?= $index ?>_<?= $choice_index ?>" class="form-check-input" <?= $question->required ? 'required' : '' ?> />
                                <label class="form-check-label" for="question_<?= $index ?>_<?= $choice_index ?>"><?= $choice ?></label>
                            </div>
                        <?php endforeach ?>
                    
                    <?php elseif($question->type == 'dropdown' && isset($question->options->choices)): ?>
                        <select name="question_<?= $index ?>" class="form-control" <?= $question->required ? 'required' : '' ?>>
                            <option value=""><?= l('global.choose') ?></option>
                            <?php foreach($question->options->choices as $choice): ?>
                                <option value="<?= $choice ?>"><?= $choice ?></option>
                            <?php endforeach ?>
                        </select>
                    
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        <?php endif ?>

        <?php if($settings->show_agreement ?? false): ?>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="agreement_<?= $microsite_block->microsite_block_id ?>" class="form-check-input" required />
                    <label class="form-check-label" for="agreement_<?= $microsite_block->microsite_block_id ?>">
                        <?php if(!empty($settings->agreement_url)): ?>
                            <a href="<?= $settings->agreement_url ?>" target="_blank" rel="noopener"><?= $settings->agreement_text ?></a>
                        <?php else: ?>
                            <?= $settings->agreement_text ?>
                        <?php endif ?>
                    </label>
                </div>
            </div>
        <?php endif ?>

        <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary btn-block">
                <?= $settings->button_text ?? l('global.submit') ?>
            </button>
        </div>

        <div class="form-response mt-3" style="display: none;"></div>
    </form>
</div>

<style>
/* Form Block Specific Styles */
.microsite-block-form {
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.microsite-block-form .form-control {
    margin-bottom: 1rem;
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    padding: 0.75rem;
}

.microsite-block-form .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.microsite-block-form .btn {
    border-radius: 0.375rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

/* Rating Styles */
.rating-stars {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.rating-star {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ffc107;
    transition: all 0.2s ease;
}

.rating-star:hover,
.rating-star.active {
    transform: scale(1.1);
}

.rating-star.active i {
    color: #ffc107;
}

.rating-star i {
    color: #e9ecef;
}

.rating-numbers {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.rating-number {
    min-width: 3rem;
    border-radius: 50%;
}

.rating-number.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.rating-emojis {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.rating-emoji {
    font-size: 1.5rem;
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rating-emoji.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

/* Form Check Styles */
.microsite-block-form .form-check {
    margin-bottom: 0.5rem;
}

.microsite-block-form .form-check-label {
    margin-left: 0.5rem;
}

/* Response Styles */
.form-response.success {
    color: #28a745;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    padding: 0.75rem;
    border-radius: 0.375rem;
}

.form-response.error {
    color: #dc3545;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    padding: 0.75rem;
    border-radius: 0.375rem;
}

/* Media Styles */
.microsite-block-form-media {
    text-align: center;
}

.microsite-block-form-media img {
    max-height: 100px;
    width: auto;
    border-radius: 0.375rem;
}

/* Header Styles */
.microsite-block-form-header {
    text-align: center;
}

.microsite-block-form-heading {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.microsite-block-form-text {
    font-size: 1rem;
    opacity: 0.8;
    margin-bottom: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('<?= $form_id ?>');
    if (!form) return;

    // Rating interactions
    setupRatingInteractions(form);
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(form);
    });

    function setupRatingInteractions(form) {
        // Star ratings
        form.querySelectorAll('.rating-stars').forEach(container => {
            const stars = container.querySelectorAll('.rating-star');
            const hiddenInput = container.querySelector('input[type="hidden"]');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    hiddenInput.value = rating;
                    
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.add('active');
                            s.querySelector('i').className = 'fas fa-star';
                        } else {
                            s.classList.remove('active');
                            s.querySelector('i').className = 'far fa-star';
                        }
                    });
                });
            });
        });

        // Number ratings
        form.querySelectorAll('.rating-numbers').forEach(container => {
            const buttons = container.querySelectorAll('.rating-number');
            const hiddenInput = container.querySelector('input[type="hidden"]');
            
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    hiddenInput.value = rating;
                    
                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });

        // Emoji ratings
        form.querySelectorAll('.rating-emojis').forEach(container => {
            const buttons = container.querySelectorAll('.rating-emoji');
            const hiddenInput = container.querySelector('input[type="hidden"]');
            
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    hiddenInput.value = rating;
                    
                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    }

    function submitForm(form) {
        const formData = new FormData(form);
        const responseContainer = form.querySelector('.form-response');
        
        // Add metadata if enabled
        const metadata = collectMetadata();
        for (const [key, value] of Object.entries(metadata)) {
            formData.append('metadata_' + key, value);
        }
        
        // Add form identification
        formData.append('microsite_block_id', form.dataset.micrositeBlockId);
        formData.append('form_type', form.dataset.formType);
        formData.append('action', 'submit_form');
        
        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = '<?= l('global.loading') ?>';
        
        fetch('<?= url('l/microsite-block-ajax') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                responseContainer.className = 'form-response success';
                responseContainer.textContent = '<?= $settings->success_text ?? l('global.success_message.basic') ?>';
                responseContainer.style.display = 'block';
                
                // Reset form
                form.reset();
                form.querySelectorAll('.active').forEach(el => el.classList.remove('active'));
                form.querySelectorAll('input[type="hidden"]').forEach(input => input.value = '');
                
                // Redirect if thank you URL is set
                <?php if(!empty($settings->thank_you_url)): ?>
                setTimeout(() => {
                    window.location.href = '<?= $settings->thank_you_url ?>';
                }, 2000);
                <?php endif ?>
            } else {
                responseContainer.className = 'form-response error';
                responseContainer.textContent = data.message || '<?= l('global.error_message.basic') ?>';
                responseContainer.style.display = 'block';
            }
        })
        .catch(error => {
            responseContainer.className = 'form-response error';
            responseContainer.textContent = '<?= l('global.error_message.basic') ?>';
            responseContainer.style.display = 'block';
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    }

    function collectMetadata() {
        const metadata = {};
        
        // Basic metadata (always collected)
        metadata.submission_timestamp = new Date().toISOString();
        metadata.form_id = '<?= $form_id ?>';
        metadata.session_id = getSessionId();
        metadata.javascript_enabled = true;
        metadata.cookies_enabled = navigator.cookieEnabled;
        
        // Additional metadata based on settings
        <?php if(isset($settings->metadata_capture)): ?>
            <?php foreach($settings->metadata_capture as $field => $enabled): ?>
                <?php if($enabled): ?>
                    <?php if($field == 'country_alpha3' || $field == 'region_code' || $field == 'city_alpha3'): ?>
                        // Geographic data would be collected server-side
                    <?php elseif($field == 'browser_name'): ?>
                        metadata.browser_name = getBrowserName();
                    <?php elseif($field == 'browser_version'): ?>
                        metadata.browser_version = getBrowserVersion();
                    <?php elseif($field == 'os_name'): ?>
                        metadata.os_name = getOSName();
                    <?php elseif($field == 'device_type'): ?>
                        metadata.device_type = getDeviceType();
                    <?php elseif($field == 'screen_resolution'): ?>
                        metadata.screen_resolution = screen.width + 'x' + screen.height;
                    <?php elseif($field == 'language'): ?>
                        metadata.language = navigator.language;
                    <?php elseif($field == 'referrer_domain'): ?>
                        metadata.referrer_domain = document.referrer ? new URL(document.referrer).hostname : '';
                    <?php elseif($field == 'time_on_page'): ?>
                        metadata.time_on_page = Math.floor((Date.now() - performance.timing.navigationStart) / 1000);
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>
        
        return metadata;
    }

    // Helper functions for metadata collection
    function getSessionId() {
        let sessionId = sessionStorage.getItem('microsite_session_id');
        if (!sessionId) {
            sessionId = 'sess_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('microsite_session_id', sessionId);
        }
        return sessionId;
    }

    function getBrowserName() {
        const userAgent = navigator.userAgent;
        if (userAgent.includes('Chrome')) return 'Chrome';
        if (userAgent.includes('Firefox')) return 'Firefox';
        if (userAgent.includes('Safari')) return 'Safari';
        if (userAgent.includes('Edge')) return 'Edge';
        return 'Unknown';
    }

    function getBrowserVersion() {
        const userAgent = navigator.userAgent;
        const match = userAgent.match(/(Chrome|Firefox|Safari|Edge)\/(\d+)/);
        return match ? match[2] : 'Unknown';
    }

    function getOSName() {
        const userAgent = navigator.userAgent;
        if (userAgent.includes('Windows')) return 'Windows';
        if (userAgent.includes('Mac')) return 'macOS';
        if (userAgent.includes('Linux')) return 'Linux';
        if (userAgent.includes('Android')) return 'Android';
        if (userAgent.includes('iOS')) return 'iOS';
        return 'Unknown';
    }

    function getDeviceType() {
        if (/Mobi|Android/i.test(navigator.userAgent)) return 'mobile';
        if (/Tablet|iPad/i.test(navigator.userAgent)) return 'tablet';
        return 'desktop';
    }
});
</script>
