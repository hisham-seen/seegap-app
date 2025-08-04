<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form" id="text-update-form-<?= $row->microsite_block_id ?>">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="text" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <?php
    // Use the reusable text block form panel
    $block_id = $row->microsite_block_id;
    $settings = $row->settings;
    $form_type = 'update';
    include THEME_PATH . 'views/partials/microsite_block_components/text_block_form_panel.php';
    ?>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('text-update-form-<?= $row->microsite_block_id ?>');
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Add form submission listener to sync WYSIWYG content
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sync all WYSIWYG editors before form submission
            if (typeof syncTextQuillEditors === 'function') {
                syncTextQuillEditors();
            }
        });
    }
});
</script>
