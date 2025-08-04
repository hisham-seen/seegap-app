<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_text" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
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

                    <?php
                    // Use the reusable text block form panel
                    $block_id = 'create';
                    $settings = (object)[];
                    $form_type = 'create';
                    $row = (object)['microsite_block_id' => 'create', 'settings' => $settings];
                    include THEME_PATH . 'views/partials/microsite_block_components/text_block_form_panel.php';
                    ?>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('create_microsite_text');
    
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const form = modal.querySelector('form[name="create_microsite_text"]');
            
            if (form && !form.hasAttribute('data-text-handler-added')) {
                form.addEventListener('submit', function(e) {
                    // Sync all WYSIWYG editors before form submission
                    if (typeof syncTextQuillEditors === 'function') {
                        syncTextQuillEditors();
                    }
                });
                
                // Mark as handled to prevent duplicate listeners
                form.setAttribute('data-text-handler-added', 'true');
            }
        });
    }
});
</script>
