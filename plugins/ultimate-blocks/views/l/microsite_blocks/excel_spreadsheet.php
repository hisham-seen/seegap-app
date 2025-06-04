<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <a href="#" data-toggle="modal" data-target="<?= '#excel_spreadsheet_' . $data->link->microsite_block_id ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" class="btn btn-block btn-primary link-btn <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?> <?= 'link-btn-' . $data->link->settings->border_radius ?> <?= $data->link->design->link_class ?>" style="<?= $data->link->design->link_style ?>" download data-text-color data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-animation data-background-color data-text-alignment>
        <div class="link-btn-image-wrapper <?= 'link-btn-' . $data->link->settings->border_radius ?>" <?= $data->link->settings->image ? null : 'style="display: none;"' ?>>
            <img src="<?= $data->link->settings->image ? \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $data->link->settings->image : null ?>" class="link-btn-image" loading="lazy" />
        </div>

        <span data-icon>
            <?php if($data->link->settings->icon): ?>
                <i class="<?= $data->link->settings->icon ?> mr-1"></i>
            <?php endif ?>
        </span>

        <span data-name><?= $data->link->settings->name ?></span>
    </a>
</div>

<?php ob_start() ?>
    <div class="modal fade" id="<?= 'excel_spreadsheet_' . $data->link->microsite_block_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><?= $data->link->settings->name ?></h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <iframe src="https://docs.google.com/viewer?embedded=true&url=<?= \SeeGap\Uploads::get_full_url('files') . $data->link->settings->file ?>" style="width: 100%; height: 500px; object-fit: contain; border: 0;" class="w-100 bg-gray-100 rounded" loading="lazy"></iframe>

                    <a href="<?= \SeeGap\Uploads::get_full_url('files') . $data->link->settings->file ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" rel="<?= $data->user->plan_settings->dofollow_is_enabled ? 'dofollow' : 'nofollow' ?>" class="mt-4 btn btn-block btn-primary link-btn <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?> <?= 'link-btn-' . $data->link->settings->border_radius ?> <?= $data->link->design->link_class ?>" style="<?= $data->link->design->link_style ?>" data-text-color data-border-shadow data-border-width data-border-radius data-border-style data-border-color data-animation data-background-color data-text-alignment>
                        <?= l('global.download') ?> <i class="fas fa-fw fa-xs fa-download ml-1"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'modals') ?>
