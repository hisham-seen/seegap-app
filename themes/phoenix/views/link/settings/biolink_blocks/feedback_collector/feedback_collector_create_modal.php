<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_feedback_collector" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= l('biolink_feedback_collector.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add question button
    document.getElementById('add_question').addEventListener('click', function() {
        addNewQuestion();
    });
});

function addNewQuestion() {
    const questionsContainer = document.getElementById('feedback_collector_questions_container');
    const questionCount = questionsContainer.querySelectorAll('.question-item').length;
    
    // Create new question item
    const questionItem = document.createElement('div');
    questionItem.className = 'card mb-3 question-item';
    questionItem.innerHTML = `
        <div class="card-body">
            <div class="form-group">
                <label><?= l('biolink_feedback_collector.question_type') ?></label>
                <select class="form-control question-type" name="question_type[]">
                    <option value="text"><?= l('biolink_feedback_collector.question_type_text') ?></option>
                    <option value="textarea"><?= l('biolink_feedback_collector.question_type_textarea') ?></option>
                    <option value="rating_star"><?= l('biolink_feedback_collector.question_type_rating_star') ?></option>
                    <option value="rating_number"><?= l('biolink_feedback_collector.question_type_rating_number') ?></option>
                    <option value="rating_emoji"><?= l('biolink_feedback_collector.question_type_rating_emoji') ?></option>
                    <option value="checkbox"><?= l('biolink_feedback_collector.question_type_checkbox') ?></option>
                    <option value="radio"><?= l('biolink_feedback_collector.question_type_radio') ?></option>
                    <option value="dropdown"><?= l('biolink_feedback_collector.question_type_dropdown') ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?= l('biolink_feedback_collector.question_text') ?></label>
                <input type="text" class="form-control question-text" name="question_text[]" placeholder="<?= l('biolink_feedback_collector.question_text_placeholder') ?>">
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input question-required" id="question_required_${questionCount}" name="question_required[]" value="1">
                    <label class="custom-control-label" for="question_required_${questionCount}"><?= l('biolink_feedback_collector.question_required') ?></label>
                </div>
            </div>
            
            <div class="question-options-container">
                <!-- Will be populated based on question type -->
            </div>
            
            <button type="button" class="btn btn-sm btn-outline-danger mt-3 remove-question">
                <i class="fas fa-trash fa-sm mr-1"></i> <?= l('biolink_feedback_collector.remove_question') ?>
            </button>
        </div>
    `;
    
    questionsContainer.appendChild(questionItem);
    
    // Add event listeners
    const questionType = questionItem.querySelector('.question-type');
    questionType.addEventListener('change', function() {
        updateQuestionOptions(questionItem);
    });
    
    const removeButton = questionItem.querySelector('.remove-question');
    removeButton.addEventListener('click', function() {
        questionItem.remove();
    });
    
    // Initialize options based on default type
    updateQuestionOptions(questionItem);
}

function updateQuestionOptions(questionItem) {
    const type = questionItem.querySelector('.question-type').value;
    const optionsContainer = questionItem.querySelector('.question-options-container');
    
    optionsContainer.innerHTML = '';
    
    switch(type) {
        case 'rating_star':
        case 'rating_number':
            optionsContainer.innerHTML = `
                <div class="form-group">
                    <label><?= l('biolink_feedback_collector.max_rating') ?></label>
                    <select class="form-control option-max-rating" name="question_max_rating[]" style="min-width: 80px;">
                        <option value="5">5</option>
                        <option value="10">10</option>
                    </select>
                </div>
            `;
            break;
            
        case 'checkbox':
        case 'radio':
        case 'dropdown':
            optionsContainer.innerHTML = `
                <div class="form-group">
                    <label><?= l('biolink_feedback_collector.choices') ?></label>
                    <textarea class="form-control option-choices" name="question_choices[]" rows="4"></textarea>
                    <small class="form-text text-muted"><?= l('biolink_feedback_collector.choices_help') ?></small>
                </div>
            `;
            break;
    }
}
</script>

            <div class="modal-body">
                <form name="create_biolink_feedback_collector" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="feedback_collector" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="feedback_collector_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('biolink_link.name') ?></label>
                        <input id="feedback_collector_name" type="text" name="name" maxlength="128" class="form-control" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="feedback_collector_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('biolink_link.image') ?></label>
                        <div data-file-image-input="image" class="form-group" data-file-input-wrapper-size-limit="<?= settings()->links->thumbnail_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?>">
                            <input id="feedback_collector_image" type="file" name="image" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['feedback_collector']['whitelisted_thumbnail_image_extensions']) ?>" class="form-control-file altum-file-input" />
                            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['feedback_collector']['whitelisted_thumbnail_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
                        </div>
                    </div>

                    <div id="image_fit_container" class="form-group" style="display: none;">
                        <label for="feedback_collector_image_fit"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('biolink_link.image_fit') ?></label>
                        <select id="feedback_collector_image_fit" name="image_fit" class="custom-select">
                            <option value="contain" selected="selected"><?= l('biolink_link.image_fit_contain') ?></option>
                            <option value="cover"><?= l('biolink_link.image_fit_cover') ?></option>
                            <option value="fill"><?= l('biolink_link.image_fit_fill') ?></option>
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.create') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
