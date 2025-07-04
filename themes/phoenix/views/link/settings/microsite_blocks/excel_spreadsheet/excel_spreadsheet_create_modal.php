<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_excel_spreadsheet" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_excel_spreadsheet.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_excel_spreadsheet" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="excel_spreadsheet" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="excel_spreadsheet_file"><i class="fas fa-fw fa-file fa-sm text-muted mr-1"></i> <?= l('microsite_excel_spreadsheet.file') ?></label>
                        <input id="excel_spreadsheet_file" type="file" name="file" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['excel_spreadsheet']['whitelisted_file_extensions']) ?>" class="form-control-file seegap-file-input" required="required" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['excel_spreadsheet']['whitelisted_file_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->file_size_limit) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="excel_spreadsheet_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
                        <input id="excel_spreadsheet_name" type="text" name="name" maxlength="128" class="form-control" required="required" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
