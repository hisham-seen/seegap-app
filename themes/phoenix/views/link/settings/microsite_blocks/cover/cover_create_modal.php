<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_cover" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_cover.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_cover" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="cover" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="cover_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_cover.name') ?></label>
                        <input id="cover_name" type="text" name="name" class="form-control" maxlength="128" placeholder="<?= l('microsite_cover.name_placeholder') ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="cover_description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('microsite_cover.description') ?> <small class="text-muted">(<?= l('global.optional') ?>)</small></label>
                        <input id="cover_description" type="text" name="description" class="form-control" maxlength="256" placeholder="<?= l('microsite_cover.description_placeholder') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="cover_background_type"><i class="fas fa-fw fa-sm fa-images text-muted mr-1"></i> <?= l('microsite_cover.background_type') ?></label>
                        <div class="row btn-group-toggle" data-toggle="buttons">
                            <div class="col-12 col-lg-6">
                                <label class="btn btn-light btn-block text-truncate active">
                                    <input type="radio" name="background_type" value="image" class="custom-control-input" checked="checked" required="required" />
                                    <i class="fas fa-fill fa-fw fa-sm mr-1"></i> <?= l('global.image') ?>
                                </label>
                            </div>

                            <div class="col-12 col-lg-6">
                                <label class="btn btn-light btn-block text-truncate">
                                    <input type="radio" name="background_type" value="video" class="custom-control-input" required="required" />
                                    <i class="fas fa-video fa-fw fa-sm mr-1"></i> <?= l('microsite_cover.video') ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div data-cover-create-background-type="image" class="form-group">
                        <label for="cover_background"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('microsite_cover.background') ?> <small class="text-muted">(<?= l('global.optional') ?>)</small></label>
                        <input id="cover_background" type="file" name="background" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions'] ?? ["jpg", "jpeg", "png", "gif", "webp", "svg"]) ?>" class="form-control-file seegap-file-input" data-crop />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions'] ?? ["jpg", "jpeg", "png", "gif", "webp", "svg"])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->background_size_limit) ?></small>
                    </div>

                    <div data-cover-create-background-type="video" class="form-group">
                        <label for="cover_video_url"><i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> <?= l('microsite_cover.video_url') ?> <small class="text-muted">(<?= l('global.optional') ?>)</small></label>
                        <input id="cover_video_url" type="text" class="form-control" name="video_url" maxlength="2048" placeholder="<?= l('microsite_cover.video_url_placeholder') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="cover_avatar"><i class="fas fa-fw fa-portrait fa-sm text-muted mr-1"></i> <?= l('microsite_cover.avatar') ?> <small class="text-muted">(<?= l('global.optional') ?>)</small></label>
                        <input id="cover_avatar" type="file" name="avatar" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions'] ?? ["jpg", "jpeg", "png", "gif", "webp", "svg"]) ?>" class="form-control-file seegap-file-input" data-crop data-aspect-ratio="1" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['cover']['whitelisted_image_extensions'] ?? ["jpg", "jpeg", "png", "gif", "webp", "svg"])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
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
    'use strict';

    type_handler('form[name="create_microsite_cover"] input[name="background_type"]', 'data-cover-create-background-type');
    document.querySelector('form[name="create_microsite_cover"] input[name="background_type"]') && document.querySelectorAll('form[name="create_microsite_cover"] input[name="background_type"]').forEach(element => element.addEventListener('change', () => { type_handler('form[name="create_microsite_cover"] input[name="background_type"]', 'data-cover-create-background-type');}));
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
