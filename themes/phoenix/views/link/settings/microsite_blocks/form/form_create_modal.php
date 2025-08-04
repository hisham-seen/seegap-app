<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
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
                <form name="create_microsite_form" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="form" />

                    <div class="notification-container"></div>

                    <?php
                    $block_id = 'create';
                    $settings = (object)[];
                    $form_type = 'create';
                    $row = (object)['microsite_block_id' => 'create', 'settings' => $settings];
                    include THEME_PATH . 'views/partials/microsite_block_components/form_block_form_panel.php';
                    ?>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.create') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
