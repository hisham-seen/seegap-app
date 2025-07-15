<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-wpforms text-dark mr-2"></i>
                    <?= l('microsite_form.header') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_form" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="form" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="form_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.name') ?></label>
                        <input type="text" id="form_name" name="name" class="form-control" value="" maxlength="128" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="form_type"><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.form_type') ?></label>
                        <select id="form_type" name="form_type" class="form-control" required="required">
                            <option value="email"><?= l('microsite_form.form_type.email') ?></option>
                            <option value="phone"><?= l('microsite_form.form_type.phone') ?></option>
                            <option value="contact"><?= l('microsite_form.form_type.contact') ?></option>
                            <option value="custom"><?= l('microsite_form.form_type.custom') ?></option>
                        </select>
                        <small class="form-text text-muted"><?= l('microsite_form.input.form_type_help') ?></small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.create') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
