<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="gs1_link_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-trash-alt text-dark mr-2"></i>
                        <?= l('gs1_link_delete_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('gs1_link_delete_modal.subheader') ?></p>

                <div class="card border-danger">
                    <div class="card-body">
                        <h6 class="card-title text-danger mb-3"><?= l('global.details') ?></h6>
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?= l('global.name') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span id="gs1_link_delete_modal_name">-</span>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-sm-4">
                                <strong><?= l('gs1_links.table.gtin') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span id="gs1_link_delete_modal_gtin">-</span>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-sm-4">
                                <strong><?= l('gs1_links.table.target_url') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span id="gs1_link_delete_modal_target_url" class="text-break">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form name="gs1_link_delete_modal_form" method="post" action="" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="gs1_link_id" value="" />
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
    $('#gs1_link_delete_modal').on('show.bs.modal', event => {
        let related_target = event.relatedTarget;
        let current_target = event.currentTarget;

        let gs1_link_id = related_target.getAttribute('data-gs1-link-id');
        let gtin = related_target.getAttribute('data-gtin') || related_target.getAttribute('data-resource-name');
        let name = related_target.getAttribute('data-name') || gtin;
        let target_url = related_target.getAttribute('data-target-url') || '-';
        
        // Set form action and input values
        let form = current_target.querySelector('form[name="gs1_link_delete_modal_form"]');
        if (form) {
            form.setAttribute('action', `${url}gs1-links/delete`);
        }
        
        let input = current_target.querySelector('form[name="gs1_link_delete_modal_form"] input[name="gs1_link_id"]');
        if (input) {
            input.setAttribute('value', gs1_link_id);
        }

        // Update modal content
        let nameElement = current_target.querySelector('#gs1_link_delete_modal_name');
        if (nameElement) {
            nameElement.textContent = name;
        }

        let gtinElement = current_target.querySelector('#gs1_link_delete_modal_gtin');
        if (gtinElement) {
            gtinElement.textContent = gtin;
        }

        let targetUrlElement = current_target.querySelector('#gs1_link_delete_modal_target_url');
        if (targetUrlElement) {
            targetUrlElement.textContent = target_url;
        }
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
