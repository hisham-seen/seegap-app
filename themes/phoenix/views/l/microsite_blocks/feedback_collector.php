<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <?php
    // Determine if this is a mega button
    $is_mega_button = isset($data->link->settings->image_display) && $data->link->settings->image_display === 'mega_button';
    $mega_button_height = $is_mega_button ? ($data->link->settings->mega_button_height ?? '200') : null;
    $show_text = $is_mega_button ? ($data->link->settings->show_text ?? true) : true;
    $image_fit = $is_mega_button ? ($data->link->settings->image_fit ?? 'cover') : null;
    
    // Prepare additional styles for mega button
    $mega_button_style = '';
    if ($is_mega_button && $data->link->settings->image) {
        $mega_button_style = "height: {$mega_button_height}px; background-image: url('" . \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $data->link->settings->image . "'); background-size: {$image_fit}; background-position: center; background-repeat: no-repeat;";
    }
    ?>
    <a href="#" data-toggle="modal" data-target="<?= '#feedback_collector_' . $data->link->microsite_block_id ?>" class="btn btn-block btn-primary link-btn <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?> <?= 'link-btn-' . $data->link->settings->border_radius ?> <?= $data->link->design->link_class ?> <?= $is_mega_button ? 'mega-button' : '' ?>" style="<?= $data->link->design->link_style ?> <?= $mega_button_style ?>" data-text-color data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-animation data-background-color data-text-alignment>
        <?php if (!$is_mega_button): ?>
        <div class="link-btn-image-wrapper <?= 'link-btn-' . $data->link->settings->border_radius ?>" <?= $data->link->settings->image ? null : 'style="display: none;"' ?>>
            <img src="<?= $data->link->settings->image ? \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $data->link->settings->image : null ?>" class="link-btn-image" loading="lazy" />
        </div>

        <span data-icon>
            <?php if($data->link->settings->icon): ?>
                <i class="<?= $data->link->settings->icon ?> mr-1"></i>
            <?php endif ?>
        </span>
        <?php endif; ?>

        <?php if ($show_text): ?>
        <span data-name><?= $data->link->settings->name ?></span>
        <?php endif; ?>
    </a>
</div>

<style>
/* Mega Button Styles */
.mega-button {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.mega-button:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.mega-button span[data-name] {
    position: relative;
    z-index: 2;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    font-weight: bold;
    font-size: 1.2em;
}
</style>

<?php ob_start() ?>
<div class="modal fade" id="<?= 'feedback_collector_' . $data->link->microsite_block_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $data->link->settings->name ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="<?= 'feedback_collector_form_' . $data->link->microsite_block_id ?>" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="microsite_block_id" value="<?= $data->link->microsite_block_id ?>" />

                    <div class="notification-container"></div>

                    <?php if(isset($data->link->settings->questions) && is_array($data->link->settings->questions) && count($data->link->settings->questions)): ?>
                        <?php foreach($data->link->settings->questions as $question_index => $question): ?>
                            <div class="form-group">
                                <label><?= $question->question ?> <?= $question->required ? '<span class="text-danger">*</span>' : '' ?></label>
                                
                                <?php if($question->type == 'text'): ?>
                                    <input type="text" class="form-control" name="question_<?= $question_index ?>" <?= $question->required ? 'required="required"' : '' ?>>
                                
                                <?php elseif($question->type == 'textarea'): ?>
                                    <textarea class="form-control" name="question_<?= $question_index ?>" rows="3" <?= $question->required ? 'required="required"' : '' ?>></textarea>
                                
                                <?php elseif($question->type == 'rating_star'): ?>
                                    <div class="star-rating" data-question-index="<?= $question_index ?>" data-max="<?= $question->options->max_rating ?? 5 ?>">
                                        <?php for($i = 1; $i <= ($question->options->max_rating ?? 5); $i++): ?>
                                            <i class="far fa-star rating-star" data-value="<?= $i ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" name="question_<?= $question_index ?>" class="rating-value" <?= $question->required ? 'required="required"' : '' ?>>
                                
                                <?php elseif($question->type == 'rating_number'): ?>
                                    <div class="number-rating" data-question-index="<?= $question_index ?>">
                                        <?php for($i = 1; $i <= ($question->options->max_rating ?? 5); $i++): ?>
                                            <span class="number-rating-item" data-value="<?= $i ?>"><?= $i ?></span>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" name="question_<?= $question_index ?>" class="rating-value" <?= $question->required ? 'required="required"' : '' ?>>
                                
                                <?php elseif($question->type == 'rating_emoji'): ?>
                                    <div class="emoji-rating" data-question-index="<?= $question_index ?>">
                                        <span class="emoji-rating-item" data-value="1">😞</span>
                                        <span class="emoji-rating-item" data-value="2">😐</span>
                                        <span class="emoji-rating-item" data-value="3">🙂</span>
                                        <span class="emoji-rating-item" data-value="4">😊</span>
                                        <span class="emoji-rating-item" data-value="5">😍</span>
                                    </div>
                                    <input type="hidden" name="question_<?= $question_index ?>" class="rating-value" <?= $question->required ? 'required="required"' : '' ?>>
                                
                                <?php elseif($question->type == 'checkbox'): ?>
                                    <?php if(isset($question->options->choices) && is_array($question->options->choices)): ?>
                                        <?php foreach($question->options->choices as $choice_index => $choice): ?>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="question_<?= $question_index ?>_<?= $choice_index ?>" name="question_<?= $question_index ?>[]" value="<?= $choice ?>">
                                                <label class="custom-control-label" for="question_<?= $question_index ?>_<?= $choice_index ?>"><?= $choice ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                
                                <?php elseif($question->type == 'radio'): ?>
                                    <?php if(isset($question->options->choices) && is_array($question->options->choices)): ?>
                                        <?php foreach($question->options->choices as $choice_index => $choice): ?>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="question_<?= $question_index ?>_<?= $choice_index ?>" name="question_<?= $question_index ?>" value="<?= $choice ?>" <?= $question->required ? 'required="required"' : '' ?>>
                                                <label class="custom-control-label" for="question_<?= $question_index ?>_<?= $choice_index ?>"><?= $choice ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                
                                <?php elseif($question->type == 'dropdown'): ?>
                                    <select class="form-control" name="question_<?= $question_index ?>" <?= $question->required ? 'required="required"' : '' ?>>
                                        <option value="">-- <?= l('global.select') ?> --</option>
                                        <?php if(isset($question->options->choices) && is_array($question->options->choices)): ?>
                                            <?php foreach($question->options->choices as $choice): ?>
                                                <option value="<?= $choice ?>"><?= $choice ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="form-group">
                            <textarea class="form-control" name="message" maxlength="512" required="required" placeholder="<?= $data->link->settings->message_placeholder ?>" aria-label="<?= $data->link->settings->message_placeholder ?>"></textarea>
                        </div>
                    <?php endif; ?>

                    <?php if($data->link->settings->show_agreement): ?>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" id="<?= 'feedback_collector_agreement_' . $data->link->microsite_block_id ?>" name="agreement" class="custom-control-input" required="required" />
                            <label class="custom-control-label font-weight-normal" for="<?= 'feedback_collector_agreement_' . $data->link->microsite_block_id ?>">
                                <?= $data->link->settings->agreement_text ?>

                                <?php if($data->link->settings->agreement_url): ?>
                                    <a href="<?= $data->link->settings->agreement_url ?>" target="_blank"><i class="fas fa-fw fa-sm fa-external-link-alt"></i></a>
                                <?php endif ?>
                            </label>
                        </div>
                    <?php endif ?>

                    <?php if(settings()->captcha->microsite_is_enabled && settings()->captcha->type != 'basic'): ?>
                        <div class="form-group">
                            <?php (new \SeeGap\Captcha())->display() ?>
                        </div>
                    <?php endif ?>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-lg btn-primary" data-is-ajax><?= $data->link->settings->button_text ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'modals') ?>

<style>
/* Star Rating */
.star-rating {
    display: inline-flex;
    font-size: 24px;
    cursor: pointer;
    margin-bottom: 10px;
}

.star-rating .rating-star {
    color: #ffc107;
    margin-right: 5px;
    transition: transform 0.2s;
}

.star-rating .rating-star:hover,
.star-rating .rating-star.active {
    transform: scale(1.2);
}

/* Number Rating */
.number-rating {
    display: flex;
    flex-wrap: wrap;
    gap: 3px;
    margin-bottom: 10px;
    max-width: 100%;
}

.number-rating-item {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #dee2e6;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.number-rating-item:hover,
.number-rating-item.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
    transform: scale(1.1);
}

/* Emoji Rating */
.emoji-rating {
    display: inline-flex;
    gap: 10px;
    font-size: 24px;
    margin-bottom: 10px;
}

.emoji-rating-item {
    cursor: pointer;
    transition: transform 0.2s;
}

.emoji-rating-item:hover,
.emoji-rating-item.active {
    transform: scale(1.2);
}
</style>

<?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'feedback_collector')): ?>
    <?php ob_start() ?>
    <script>
        'use strict';

        /* Go over all mail buttons to make sure the user can still submit mail */
        $('form[id^="feedback_collector_"]').each((index, element) => {
            let microsite_block_id = $(element).find('input[name="microsite_block_id"]').val();
            let is_converted = sessionStorage.getItem(`feedback_collector_${microsite_block_id}`);

            if(is_converted) {
                /* Set the submit button to disabled */
                $(element).find('button[type="submit"]').attr('disabled', 'disabled');
            }
            
            /* Initialize rating systems */
            initRatingSystems(element);
        });
        
        /* Initialize rating systems */
        function initRatingSystems(formElement) {
            /* Star rating functionality */
            $(formElement).find('.star-rating .rating-star').on('click', function() {
                const container = $(this).closest('.star-rating');
                const value = $(this).data('value');
                
                // Update visual state
                container.find('.rating-star').removeClass('fas').addClass('far');
                container.find('.rating-star').each(function(index) {
                    if (index < value) {
                        $(this).removeClass('far').addClass('fas');
                    }
                });
                
                // Update hidden input
                container.siblings('.rating-value').val(value);
            });

            /* Number rating functionality */
            $(formElement).find('.number-rating-item').on('click', function() {
                const container = $(this).closest('.number-rating');
                const value = $(this).data('value');
                
                // Update visual state
                container.find('.number-rating-item').removeClass('active');
                $(this).addClass('active');
                
                // Update hidden input
                container.siblings('.rating-value').val(value);
            });

            /* Emoji rating functionality */
            $(formElement).find('.emoji-rating-item').on('click', function() {
                const container = $(this).closest('.emoji-rating');
                const value = $(this).data('value');
                
                // Update visual state
                container.find('.emoji-rating-item').removeClass('active');
                $(this).addClass('active');
                
                // Update hidden input
                container.siblings('.rating-value').val(value);
            });
        }
        
        /* Form handling for mail submissions if any */
        $('form[id^="feedback_collector_"]').on('submit', event => {
            let microsite_block_id = $(event.currentTarget).find('input[name="microsite_block_id"]').val();
            let is_converted = sessionStorage.getItem(`feedback_collector_${microsite_block_id}`);

            let notification_container = event.currentTarget.querySelector('.notification-container');
            notification_container.innerHTML = '';
            pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

            if(!is_converted) {
                $.ajax({
                    type: 'POST',
                    url: `${site_url}l/link/feedback_collector`,
                    data: $(event.currentTarget).serialize(),
                    dataType: 'json',
                    success: (data) => {
                        enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                        if(data.status == 'error') {
                            display_notifications(data.message, 'error', notification_container);
                        } else if(data.status == 'success') {
                            display_notifications(data.message, 'success', notification_container);

                            setTimeout(() => {

                                /* Hide modal */
                                $(event.currentTarget).closest('.modal').modal('hide');

                                /* Remove the notification */
                                notification_container.innerHTML = '';

                                /* Set the localstorage to mention that the user was converted */
                                sessionStorage.setItem(`feedback_collector_${microsite_block_id}`, true);

                                /* Set the submit button to disabled */
                                $(event.currentTarget).find('button[type="submit"]').attr('disabled', 'disabled');

                                if(data.details.thank_you_url) {
                                    window.location.replace(data.details.thank_you_url);
                                }

                            }, 750);
                        }

                        /* Reset captcha */
                        try {
                            grecaptcha.reset();
                            hcaptcha.reset();
                            turnstile.reset();
                        } catch (error) {}
                    },
                    error: () => {
                        enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));
                        display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
                    },
                });

            }

            event.preventDefault();
        })
    </script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'feedback_collector') ?>
<?php endif ?>
