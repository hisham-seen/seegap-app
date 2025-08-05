<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="product_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-sm fa-trash-alt text-gray-700"></i>
                    <?= l('product_delete_modal.header') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="product_delete_confirmation" class="text-muted"><?= l('product_delete_modal.subheader') ?></label>
                    <input type="text" id="product_delete_confirmation" class="form-control" />
                </div>

                <div class="mt-3">
                    <span class="text-muted"><?= l('global.delete_modal.information') ?></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-gray-300" data-dismiss="modal"><?= l('global.close') ?></button>

                <form action="<?= url('admin/products/delete') ?>" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                    <input type="hidden" name="product_id" value="" />
                    <button type="submit" class="btn btn-danger" disabled="disabled"><?= l('global.delete') ?></button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#product_delete_modal').on('show.bs.modal', event => {
        let product_id = $(event.relatedTarget).data('product-id');
        let resource_name = $(event.relatedTarget).data('resource-name');

        $(event.currentTarget).find('input[name="product_id"]').val(product_id);
        $(event.currentTarget).find('#product_delete_confirmation').attr('placeholder', resource_name);

        $(event.currentTarget).find('#product_delete_confirmation').off().on('paste keyup', event => {
            let confirmation_text = $(event.currentTarget).val();

            if(confirmation_text === resource_name) {
                $(event.currentTarget).closest('.modal-content').find('button[type="submit"]').prop('disabled', false);
            } else {
                $(event.currentTarget).closest('.modal-content').find('button[type="submit"]').prop('disabled', true);
            }
        });
    });

    $('#product_delete_modal').on('hidden.bs.modal', event => {
        $(event.currentTarget).find('#product_delete_confirmation').val('');
        $(event.currentTarget).find('button[type="submit"]').prop('disabled', true);
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
