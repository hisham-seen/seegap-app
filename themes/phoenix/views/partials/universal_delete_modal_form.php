<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="<?= $data->name . '_delete_modal' ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-trash-alt text-dark mr-2"></i>
                        <?= l('delete_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted text-break" id="<?= $data->name . '_delete_modal_subheader' ?>"></p>

                <span class="d-none" id="<?= $data->name . '_delete_modal_subheader_hidden' ?>">
                    <?= $data->has_dynamic_resource_name ? l('delete_modal.subheader1') : l('delete_modal.subheader2') ?>
                </span>

                <form name="<?= $data->name . '_delete_modal_form' ?>" method="post" action="" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="id" value="" />
                    <input type="hidden" name="original_request" value="<?= base64_encode(\SeeGap\Router::$original_request) ?>" />
                    <input type="hidden" name="original_request_query" value="<?= base64_encode(\SeeGap\Router::$original_request_query) ?>" />

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-danger"><?= l('global.delete') ?></button>
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
    $('<?= '#' . $data->name . '_delete_modal' ?>').on('show.bs.modal', event => {
        let related_target = event.relatedTarget;
        let current_target = event.currentTarget;

        let resource_id_value = related_target.getAttribute('data-<?= str_replace('_', '-', $data->resource_id) ?>');
        
        let form = current_target.querySelector('<?= 'form[name="' . $data->name . '_delete_modal_form"]' ?>');
        if (form) {
            form.setAttribute('action', `${url}<?= $data->path ?>`);
        }
        
        let input = current_target.querySelector('<?= 'form[name="' . $data->name . '_delete_modal_form"] input[name*="id"]' ?>');
        if (input) {
            input.setAttribute('value', resource_id_value);
            input.setAttribute('name', '<?= $data->resource_id ?>');
        }

        <?php if($data->has_dynamic_resource_name): ?>
        let subheader = current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader' ?>');
        let subheader_hidden = current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader_hidden' ?>');
        if (subheader && subheader_hidden) {
            subheader.innerHTML = subheader_hidden.innerHTML.replace('%s', related_target.getAttribute('data-resource-name'));
        }
        <?php else: ?>
        let subheader = current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader' ?>');
        let subheader_hidden = current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader_hidden' ?>');
        if (subheader && subheader_hidden) {
            subheader.innerHTML = subheader_hidden.innerHTML;
        }
        <?php endif ?>
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
