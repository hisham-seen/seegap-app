<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_feedback_collector" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_feedback_collector.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_feedback_collector" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="feedback_collector" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="feedback_collector_form_heading"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> Form Heading</label>
        <input id="feedback_collector_form_heading" type="text" name="form_heading" class="form-control" maxlength="128" placeholder="Enter form heading..." />
    </div>

    <div class="form-group">
        <label for="feedback_collector_form_text"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> Form Text</label>
        <div 
            id="feedback_collector_form_text_editor" 
            class="minimalistic-editor" 
            contenteditable="true"
            data-placeholder="Enter form description..."
        ></div>
        <input type="hidden" name="form_text" id="feedback_collector_form_text" />
    </div>

    <div class="form-group">
        <label for="feedback_collector_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
        <input id="feedback_collector_name" type="text" name="name" class="form-control" maxlength="128" required="required" />
    </div>

    <div class="form-group">
        <label for="feedback_collector_description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('microsite_link.description') ?></label>
        <textarea id="feedback_collector_description" name="description" class="form-control" maxlength="256"></textarea>
    </div>

                    <div class="form-group">
                        <label><?= l('microsite_feedback_collector.questions') ?></label>
                        <p class="text-muted"><?= l('microsite_feedback_collector.questions_help') ?></p>
                        <div id="<?= 'feedback_collector_questions_create' ?>" data-microsite-block-id="create"></div>

                        <div class="mb-3">
                            <button data-add="feedback_question" data-microsite-block-id="create" type="button" class="btn btn-outline-success btn-block"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('microsite_feedback_collector.add_question') ?></button>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<template id="template_feedback_question">
    <div class="card mb-3">
        <div class="card-body">
            <div class="form-group">
                <label><?= l('microsite_feedback_collector.question_type') ?></label>
                <select class="form-control question-type" name="question_type[]">
                    <option value="text"><?= l('microsite_feedback_collector.question_type_text') ?></option>
                    <option value="textarea"><?= l('microsite_feedback_collector.question_type_textarea') ?></option>
                    <option value="rating_star"><?= l('microsite_feedback_collector.question_type_rating_star') ?></option>
                    <option value="rating_number"><?= l('microsite_feedback_collector.question_type_rating_number') ?></option>
                    <option value="rating_emoji"><?= l('microsite_feedback_collector.question_type_rating_emoji') ?></option>
                    <option value="checkbox"><?= l('microsite_feedback_collector.question_type_checkbox') ?></option>
                    <option value="radio"><?= l('microsite_feedback_collector.question_type_radio') ?></option>
                    <option value="dropdown"><?= l('microsite_feedback_collector.question_type_dropdown') ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?= l('microsite_feedback_collector.question_text') ?></label>
                <input type="text" class="form-control question-text" name="question_text[]" placeholder="<?= l('microsite_feedback_collector.question_text_placeholder') ?>" required="required">
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input question-required" name="question_required[]" value="1">
                    <label class="custom-control-label"><?= l('microsite_feedback_collector.question_required') ?></label>
                </div>
            </div>
            
            <div class="question-options-container">
                <!-- Will be populated based on question type -->
            </div>
            
            <button type="button" data-remove="question" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('microsite_feedback_collector.remove_question') ?></button>
        </div>
    </div>
</template>

<?php ob_start() ?>
    <script>
        'use strict';

        $('#create_microsite_feedback_collector').on('shown.bs.modal', event => {
            $(event.currentTarget).find('button[data-add]').click();
        })
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script>
    /* Feedback Collector Questions Script */
    'use strict';

    /* add new question */
    let feedback_question_add = event => {
        let microsite_block_id = event.currentTarget.getAttribute('data-microsite-block-id');
        let clone = document.querySelector(`#template_feedback_question`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="feedback_collector_questions_${microsite_block_id}"] .card`).length;

        if(count >= 20) return;

        // Set unique IDs for form elements
        let questionRequiredCheckbox = clone.querySelector(`input[name="question_required[]"]`);
        let questionRequiredLabel = clone.querySelector(`.custom-control-label`);
        let uniqueId = `question_required_${microsite_block_id}_${count}`;
        
        questionRequiredCheckbox.setAttribute('id', uniqueId);
        questionRequiredLabel.setAttribute('for', uniqueId);

        document.querySelector(`[id="feedback_collector_questions_${microsite_block_id}"]`).appendChild(clone);

        // Add event listener for question type change
        let questionTypeSelect = document.querySelector(`[id="feedback_collector_questions_${microsite_block_id}"] .card:last-child .question-type`);
        questionTypeSelect.addEventListener('change', function() {
            updateQuestionOptions(this.closest('.card'));
        });

        // Initialize options for the newly added question
        updateQuestionOptions(document.querySelector(`[id="feedback_collector_questions_${microsite_block_id}"] .card:last-child`));

        feedback_question_remove_initiator();
    };

    document.querySelectorAll('[data-add="feedback_question"]').forEach(element => {
        element.addEventListener('click', feedback_question_add);
    })

    /* remove question */
    let feedback_question_remove = event => {
        event.currentTarget.closest('.card').remove();
    };

    let feedback_question_remove_initiator = () => {
        document.querySelectorAll('[id^="feedback_collector_questions_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', feedback_question_remove);
            element.addEventListener('click', feedback_question_remove)
        })
    };

    /* Update question options based on type */
    function updateQuestionOptions(questionCard) {
        const type = questionCard.querySelector('.question-type').value;
        const optionsContainer = questionCard.querySelector('.question-options-container');
        
        optionsContainer.innerHTML = '';
        
        switch(type) {
            case 'rating_star':
            case 'rating_number':
                optionsContainer.innerHTML = `
                    <div class="form-group">
                        <label><?= l('microsite_feedback_collector.max_rating') ?></label>
                        <select class="form-control option-max-rating" name="question_max_rating[]" style="min-width: 80px;">
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                `;
                break;
                
            case 'checkbox':
            case 'radio':
                optionsContainer.innerHTML = `
                    <div class="form-group">
                        <label><?= l('microsite_feedback_collector.choices') ?></label>
                        <textarea class="form-control option-choices" name="question_choices[]" rows="4"></textarea>
                        <small class="form-text text-muted"><?= l('microsite_feedback_collector.choices_help') ?></small>
                    </div>
                    <div class="form-group">
                        <label><?= l('microsite_feedback_collector.layout') ?></label>
                        <select class="form-control option-layout" name="question_layout[]">
                            <option value="block"><?= l('microsite_feedback_collector.layout_block') ?></option>
                            <option value="inline"><?= l('microsite_feedback_collector.layout_inline') ?></option>
                        </select>
                    </div>
                `;
                break;
                
            case 'dropdown':
                optionsContainer.innerHTML = `
                    <div class="form-group">
                        <label><?= l('microsite_feedback_collector.choices') ?></label>
                        <textarea class="form-control option-choices" name="question_choices[]" rows="4"></textarea>
                        <small class="form-text text-muted"><?= l('microsite_feedback_collector.choices_help') ?></small>
                    </div>
                `;
                break;
        }
    }

    feedback_question_remove_initiator();
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'feedback_collector_block') ?>

<?php include THEME_PATH . 'views/partials/wysiwyg_editor.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize minimalistic editor for create modal when modal is shown
    $('#create_microsite_feedback_collector').on('shown.bs.modal', function() {
        const editorInstance = initMinimalisticEditor(
            'feedback_collector_form_text_editor',
            'feedback_collector_form_text',
            {
                placeholder: 'Enter form description...',
                toolbar: ['bold', 'italic', 'underline', '|', 'insertUnorderedList', 'insertOrderedList', '|', 'createLink']
            }
        );
        
        // Handle form submission manually since form doesn't have an ID
        const form = document.querySelector('form[name="create_microsite_feedback_collector"]');
        if (form && editorInstance) {
            form.addEventListener('submit', function() {
                const hiddenInput = document.getElementById('feedback_collector_form_text');
                if (hiddenInput && editorInstance) {
                    hiddenInput.value = editorInstance.getContent();
                }
            });
        }
    });
});
</script>
