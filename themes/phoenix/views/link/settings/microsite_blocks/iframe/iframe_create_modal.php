<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_iframe" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_iframe.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_iframe" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="iframe" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="iframe_location_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('global.url') ?></label>
                        <input id="iframe_location_url" type="url" class="form-control" name="location_url" required="required" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="iframe_height"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_iframe.height') ?> (px)</label>
                        <input id="iframe_height" type="number" class="form-control" name="height" value="400" min="100" max="1000" placeholder="400" />
                        <small class="form-text text-muted"><?= l('microsite_iframe.height_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="iframe_display_mode"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('microsite_iframe.display_mode') ?></label>
                        <select id="iframe_display_mode" name="display_mode" class="form-control">
                            <option value="page"><?= l('microsite_iframe.display_mode_page') ?></option>
                            <option value="modal"><?= l('microsite_iframe.display_mode_modal') ?></option>
                        </select>
                        <small class="form-text text-muted"><?= l('microsite_iframe.display_mode_help') ?></small>
                    </div>

                    <div id="modal_settings_create" style="display: none;">
                        <div class="form-group">
                            <label for="iframe_button_text"><i class="fas fa-fw fa-font fa-sm text-muted mr-1"></i> <?= l('microsite_iframe.button_text') ?></label>
                            <input id="iframe_button_text" type="text" class="form-control" name="button_text" value="View Content" maxlength="64" placeholder="View Content" />
                        </div>

                        <div class="form-group">
                            <label for="iframe_button_icon"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('microsite_iframe.button_icon') ?></label>
                            <input id="iframe_button_icon" type="text" class="form-control" name="button_icon" value="fas fa-external-link-alt" placeholder="fas fa-external-link-alt" />
                            <small class="form-text text-muted"><?= l('microsite_iframe.button_icon_help') ?></small>
                        </div>
                    </div>

                    <script>
                    document.getElementById('iframe_display_mode').addEventListener('change', function() {
                        const modalSettings = document.getElementById('modal_settings_create');
                        if (this.value === 'modal') {
                            modalSettings.style.display = 'block';
                        } else {
                            modalSettings.style.display = 'none';
                        }
                    });
                    </script>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
