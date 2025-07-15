<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_text" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_text.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_text" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="text" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="text_type"><i class="fas fa-fw fa-text-height fa-sm text-muted mr-1"></i> <?= l('microsite_text.type') ?></label>
                        <select id="text_type" name="text_type" class="custom-select" onchange="toggleTextTypeFields()">
                            <option value="paragraph"><?= l('microsite_text.type_paragraph') ?></option>
                            <option value="heading"><?= l('microsite_text.type_heading') ?></option>
                            <option value="list"><?= l('microsite_text.type_list') ?></option>
                        </select>
                    </div>

                    <!-- Heading Type Selection (only for heading) -->
                    <div class="form-group" id="heading_type_group" style="display: none;">
                        <label for="heading_type"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('microsite_text.heading_level') ?></label>
                        <select id="heading_type" name="heading_type" class="custom-select">
                            <option value="h1">H1</option>
                            <option value="h2">H2</option>
                            <option value="h3">H3</option>
                            <option value="h4">H4</option>
                            <option value="h5">H5</option>
                            <option value="h6">H6</option>
                        </select>
                    </div>

                    <!-- Text Content (for heading and paragraph) -->
                    <div class="form-group" id="text_content_group">
                        <label for="text"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_text.content') ?></label>
                        <textarea id="text" name="text" class="form-control" rows="3" maxlength="2048"></textarea>
                        <small class="form-text text-muted" id="text_help_paragraph"><?= l('microsite_text.content_help_paragraph') ?></small>
                        <small class="form-text text-muted" id="text_help_heading" style="display: none;"><?= l('microsite_text.content_help_heading') ?></small>
                    </div>

                    <!-- List Type Selection (only for list) -->
                    <div class="form-group" id="list_type_group" style="display: none;">
                        <label for="list_type"><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> <?= l('microsite_text.list_type') ?></label>
                        <select id="list_type" name="list_type" class="custom-select">
                            <option value="unordered"><?= l('microsite_text.list_unordered') ?></option>
                            <option value="ordered"><?= l('microsite_text.list_ordered') ?></option>
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
function toggleTextTypeFields() {
    const textType = document.getElementById('text_type').value;
    const headingTypeGroup = document.getElementById('heading_type_group');
    const textContentGroup = document.getElementById('text_content_group');
    const listTypeGroup = document.getElementById('list_type_group');
    const textHelpParagraph = document.getElementById('text_help_paragraph');
    const textHelpHeading = document.getElementById('text_help_heading');
    const textField = document.getElementById('text');

    // Hide all conditional fields first
    headingTypeGroup.style.display = 'none';
    listTypeGroup.style.display = 'none';
    textHelpParagraph.style.display = 'none';
    textHelpHeading.style.display = 'none';

    if (textType === 'heading') {
        headingTypeGroup.style.display = 'block';
        textContentGroup.style.display = 'block';
        textHelpHeading.style.display = 'block';
        textField.maxLength = 256;
        textField.rows = 2;
    } else if (textType === 'paragraph') {
        textContentGroup.style.display = 'block';
        textHelpParagraph.style.display = 'block';
        textField.maxLength = 2048;
        textField.rows = 4;
    } else if (textType === 'list') {
        textContentGroup.style.display = 'none';
        listTypeGroup.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTextTypeFields();
});
</script>
