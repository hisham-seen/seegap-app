<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="product_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-sm fa-trash-alt text-danger mr-2"></i>
                    <?= l('products.delete_modal.header') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="product_delete_confirmation" class="text-muted"><?= l('products.delete_modal.subheader') ?></label>
                    <input type="text" id="product_delete_confirmation" class="form-control" />
                </div>

                <div class="mt-4">
                    <span class="text-muted"><?= l('products.delete_modal.subheader2') ?></span>
                    <div>
                        <span class="font-weight-bold text-gray-800" id="product_delete_display_name"></span>
                    </div>
                    <div>
                        <span class="text-muted"><?= l('products.table.gtin') ?>:</span>
                        <span class="font-weight-bold text-gray-800" id="product_delete_display_gtin"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-gray-300" data-dismiss="modal"><?= l('global.close') ?></button>

                <form method="post" action="<?= url('products/delete') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                    <input type="hidden" name="product_id" value="" id="product_delete_product_id" />
                    <button type="submit" class="btn btn-danger" id="product_delete_submit"><?= l('global.delete') ?></button>
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
        let gtin = $(event.relatedTarget).data('gtin');

        $(event.currentTarget).find('#product_delete_product_id').val(product_id);
        $(event.currentTarget).find('#product_delete_display_name').html(resource_name);
        $(event.currentTarget).find('#product_delete_display_gtin').html(gtin);

        $(event.currentTarget).find('#product_delete_confirmation').val('');
    });

    $('#product_delete_confirmation').on('paste keyup', event => {

        let confirmation_text = $(event.currentTarget).val();
        let expected_confirmation_text = $('#product_delete_display_name').text();

        if(confirmation_text !== expected_confirmation_text) {
            $('#product_delete_submit').attr('disabled', 'disabled');
        } else {
            $('#product_delete_submit').removeAttr('disabled');
        }

    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
