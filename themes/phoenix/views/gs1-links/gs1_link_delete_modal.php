<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="gs1_link_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-trash-alt text-danger mr-2"></i>
                    <?= l('gs1_link_delete_modal.header') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-muted"><?= l('gs1_link_delete_modal.subheader') ?></p>

                <div class="mt-4">
                    <span class="font-weight-bold text-muted"><?= l('global.name') ?></span>
                    <div>
                        <span id="gs1_link_delete_modal_title"></span>
                    </div>
                </div>

                <div class="mt-3">
                    <span class="font-weight-bold text-muted"><?= l('gs1_links.gtin') ?></span>
                    <div>
                        <span id="gs1_link_delete_modal_gtin"></span>
                    </div>
                </div>

                <div class="mt-3">
                    <span class="font-weight-bold text-muted"><?= l('gs1_links.target_url') ?></span>
                    <div>
                        <span id="gs1_link_delete_modal_target_url"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-gray-300" data-dismiss="modal"><?= l('global.close') ?></button>

                <form method="post" action="<?= url('gs1-link-ajax') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                    <input type="hidden" name="request_type" value="delete" />
                    <input type="hidden" name="gs1_link_id" value="" />
                    <button type="submit" class="btn btn-danger"><?= l('global.delete') ?></button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#gs1_link_delete_modal').on('show.bs.modal', event => {
        let gs1_link_id = $(event.relatedTarget).data('gs1-link-id');
        let title = $(event.relatedTarget).data('title');
        let gtin = $(event.relatedTarget).data('gtin');
        let target_url = $(event.relatedTarget).data('target-url');

        $(event.currentTarget).find('input[name="gs1_link_id"]').val(gs1_link_id);
        $(event.currentTarget).find('#gs1_link_delete_modal_title').html(title);
        $(event.currentTarget).find('#gs1_link_delete_modal_gtin').html(gtin);
        $(event.currentTarget).find('#gs1_link_delete_modal_target_url').html(target_url);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
