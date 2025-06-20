<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="card position-relative p-3 <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?> <?= 'link-btn-' . $data->link->settings->border_radius ?> <?= $data->link->design->card_class ?>" style="<?= $data->link->design->card_style ?>" data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-animation data-background-color>
        <div class="row g-0">
            <?php if($data->link->settings->image): ?>
            <div class="col-md-4 d-flex justify-content-center align-items-center mb-3 mb-md-0">
                <img src="<?= $data->link->settings->image ? \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $data->link->settings->image : null ?>" class="link-external-item-image <?= 'link-btn-' . $data->link->settings->border_radius ?>" loading="lazy" />
            </div>
            <?php endif ?>

            <div class="col-md-8">
                <div class="d-flex flex-column" style="<?= 'text-align: ' . ($data->link->settings->text_alignment ?? 'left') . ';' ?>"  data-text-alignment>
                    <span class="h5" style="<?= 'color: ' . $data->link->settings->name_color ?>" data-name data-name-color><?= $data->link->settings->name ?></span>
                    <p class="p-0" style="<?= 'color: ' . $data->link->settings->description_color ?>" data-description data-description-color><?= $data->link->settings->description ?></p>
                    <span class="h4" style="<?= 'color: ' . $data->link->settings->price_color ?>" data-price data-price-color><?= $data->link->settings->price ?></span>
                </div>
            </div>
        </div>

        <?php if($data->link->location_url): ?>
        <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" rel="<?= $data->user->plan_settings->dofollow_is_enabled ? 'dofollow' : 'nofollow' ?>" class="stretched-link"></a>
        <?php endif ?>
    </div>
</div>


