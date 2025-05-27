<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="gs1_link_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-sm fa-trash-alt text-gray-700"></i>
                    <?= l('gs1_link_delete_modal.header') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p><?= l('gs1_link_delete_modal.subheader') ?></p>

                <div class="mt-4">
                    <span id="gs1_link_delete_modal_subheader"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-gray-300" data-dismiss="modal"><?= l('global.close') ?></button>

                <form action="" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                    <input type="hidden" name="gs1_link_id" value="" />

                    <button type="submit" class="btn btn-primary"><?= l('global.delete') ?></button>
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
        let resource_name = $(event.relatedTarget).data('resource-name');

        $(event.currentTarget).find('input[name="gs1_link_id"]').val(gs1_link_id);
        $(event.currentTarget).find('#gs1_link_delete_modal_subheader').html(<?= json_encode(l('gs1_link_delete_modal.subheader2')) ?>.replace('%s', `<strong>${resource_name}</strong>`));
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
