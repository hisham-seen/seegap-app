<?php defined('SEEGAP') || die() ?>

<?php
/* Generate height styles based on settings */
$height_style = '';
$height_class = 'h-auto';

if (isset($data->link->settings->image_height)) {
    switch ($data->link->settings->image_height) {
        case 'small':
            $height_style = 'height: 200px; object-fit: cover;';
            $height_class = '';
            break;
        case 'medium':
            $height_style = 'height: 400px; object-fit: cover;';
            $height_class = '';
            break;
        case 'large':
            $height_style = 'height: 600px; object-fit: cover;';
            $height_class = '';
            break;
        case 'custom':
            if (isset($data->link->settings->image_height_custom) && $data->link->settings->image_height_custom > 0) {
                $height_style = 'height: ' . (int)$data->link->settings->image_height_custom . 'px; object-fit: cover;';
                $height_class = '';
            }
            break;
        case 'auto':
        default:
            $height_class = 'h-auto';
            break;
    }
}
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <?php if($data->link->location_url): ?>
    <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="<?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
        <img src="<?= \SeeGap\Uploads::get_full_url('block_images') . $data->link->settings->image ?>" class="w-100 <?= $height_class ?> rounded <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" alt="<?= $data->link->settings->image_alt ?>" loading="lazy" <?= $height_style ? 'style="' . $height_style . '"' : '' ?> />
    </a>
    <?php else: ?>
    <img src="<?= \SeeGap\Uploads::get_full_url('block_images') . $data->link->settings->image ?>" class="w-100 <?= $height_class ?> rounded" alt="<?= $data->link->settings->image_alt ?>" loading="lazy" <?= $height_style ? 'style="' . $height_style . '"' : '' ?> />
    <?php endif ?>
</div>
