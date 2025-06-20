<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_image" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_image.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_image" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="image" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="image_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
                        <input id="image_image" type="file" name="image" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions']) ?>" class="form-control-file seegap-file-input" required="required" data-crop />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="image_height"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_image.height') ?? 'Image Height' ?></label>
                        <select id="image_height" name="image_height" class="form-control">
                            <option value="auto"><?= l('microsite_image.height_auto') ?? 'Auto (Original)' ?></option>
                            <option value="small"><?= l('microsite_image.height_small') ?? 'Small (200px)' ?></option>
                            <option value="medium"><?= l('microsite_image.height_medium') ?? 'Medium (400px)' ?></option>
                            <option value="large"><?= l('microsite_image.height_large') ?? 'Large (600px)' ?></option>
                            <option value="custom"><?= l('microsite_image.height_custom') ?? 'Custom' ?></option>
                        </select>
                    </div>

                    <div class="form-group" id="image_height_custom_container" style="display: none;">
                        <label for="image_height_custom"><i class="fas fa-fw fa-ruler fa-sm text-muted mr-1"></i> <?= l('microsite_image.height_custom_value') ?? 'Custom Height (px)' ?></label>
                        <input id="image_height_custom" type="number" class="form-control" name="image_height_custom" min="50" max="1000" value="200" placeholder="200" />
                        <small class="form-text text-muted"><?= l('microsite_image.height_custom_help') ?? 'Enter height in pixels (50-1000)' ?></small>
                    </div>

                    <div class="form-group">
                        <label for="image_location_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
                        <input id="image_location_url" type="url" class="form-control" name="location_url" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heightSelect = document.getElementById('image_height');
    const customContainer = document.getElementById('image_height_custom_container');
    
    if (heightSelect && customContainer) {
        heightSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customContainer.style.display = 'block';
            } else {
                customContainer.style.display = 'none';
            }
        });
    }
});
</script>
