<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-trash-alt text-primary-900 mr-2"></i>
                    <?= l('admin_reports.delete') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-fw fa-exclamation-triangle fa-3x text-warning mr-3"></i>

                        <div class="text-left">
                            <?= l('admin_reports.delete_modal.header') ?>
                            <br />
                            <strong id="delete_modal_name"></strong>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <form name="delete_report" method="post" role="form">
                        <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                        <input type="hidden" name="request_type" value="delete" />
                        <input type="hidden" name="report_id" value="" />

                        <div class="form-group">
                            <label for="delete_confirmation" class="text-muted"><?= l('global.delete_modal.confirmation') ?></label>
                            <input id="delete_confirmation" name="delete_confirmation" type="text" class="form-control" />
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" name="submit" class="btn btn-lg btn-block btn-danger" data-is-ajax><?= l('global.delete') ?></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
