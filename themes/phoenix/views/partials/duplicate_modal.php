<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="<?= $data->modal_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-clone text-dark mr-2"></i>
                        <?= l('duplicate_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('duplicate_modal.subheader') ?></p>

                <form name="<?= $data->modal_id ?>" method="post" role="form" onsubmit="return false;">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="<?= $data->resource_id ?>" value="" />

                    <div class="mt-4">
                        <button type="button" name="submit" class="btn btn-block btn-primary" onclick="duplicateItem(this)"><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('<?= '#' . $data->modal_id ?>').on('show.bs.modal', event => {
        let id = $(event.relatedTarget).data('product-id');
        $(event.currentTarget).find('input[name="<?= $data->resource_id ?>"]').val(id);
    });

    function duplicateItem(button) {
        let form = button.closest('form');
        let modal = button.closest('.modal');
        let formData = {
            token: form.querySelector('input[name="token"]').value,
            <?= $data->resource_id ?>: form.querySelector('input[name="<?= $data->resource_id ?>"]').value
        };

        $.ajax({
            type: 'POST',
            url: '<?= url($data->path) ?>',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    showToast('success', response.message);
                    setTimeout(() => {
                        if(response.url) {
                            window.location.href = response.url;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                } else {
                    showToast('error', response.message);
                }
                $(modal).modal('hide');
            },
            error: function() {
                showToast('error', 'An error occurred while processing your request.');
                $(modal).modal('hide');
            }
        });
    }
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
