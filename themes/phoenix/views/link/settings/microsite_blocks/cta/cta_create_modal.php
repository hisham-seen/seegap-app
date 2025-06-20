<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_cta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_cta.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_cta" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="cta" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="cta_type"><i class="fas fa-fw fa-comments fa-sm text-muted mr-1"></i> <?= l('global.type') ?></label>
                        <select id="cta_type" name="type" class="custom-select">
                            <option value="email"><?= l('microsite_cta.type_email') ?></option>
                            <option value="call"><?= l('microsite_cta.type_call') ?></option>
                            <option value="sms"><?= l('microsite_cta.type_sms') ?></option>
                            <option value="facetime"><?= l('microsite_cta.type_facetime') ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cta_value">
                            <span data-cta-type="email" class="d-none"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('microsite_cta.value_email') ?></span>
                            <span data-cta-type="call" class="d-none"><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('microsite_cta.value_call') ?></span>
                            <span data-cta-type="sms" class="d-none"><i class="fas fa-fw fa-sms fa-sm text-muted mr-1"></i> <?= l('microsite_cta.value_sms') ?></span>
                            <span data-cta-type="facetime" class="d-none"><i class="fas fa-fw fa-headset fa-sm text-muted mr-1"></i> <?= l('microsite_cta.value_facetime') ?></span>
                        </label>
                        <input id="cta_value" type="text" class="form-control" name="value" maxlength="320" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="cta_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
                        <input id="cta_name" type="text" name="name" maxlength="128" class="form-control" required="required" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    type_handler('#cta_type', 'data-cta-type');
    document.querySelector('#cta_type') && document.querySelectorAll('#cta_type').forEach(element => element.addEventListener('change', () => { type_handler('#cta_type', 'data-cta-type'); }));
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
