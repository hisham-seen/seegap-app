<?php defined('SEEGAP') || die() ?>

<?php
// Generate the appropriate URL based on CTA type
$cta_url = '';
switch($data->link->settings->type ?? 'email') {
    case 'email':
        $cta_url = 'mailto:' . ($data->link->settings->value ?? '');
        break;
    case 'call':
        $cta_url = 'tel:' . ($data->link->settings->value ?? '');
        break;
    case 'sms':
        $cta_url = 'sms:' . ($data->link->settings->value ?? '');
        break;
    case 'facetime':
        $cta_url = 'facetime:' . ($data->link->settings->value ?? '');
        break;
    default:
        $cta_url = $data->link->location_url ?? '#';
}

// Get icon based on CTA type
$cta_icon = '';
switch($data->link->settings->type ?? 'email') {
    case 'email':
        $cta_icon = 'fas fa-envelope';
        break;
    case 'call':
        $cta_icon = 'fas fa-phone';
        break;
    case 'sms':
        $cta_icon = 'fas fa-sms';
        break;
    case 'facetime':
        $cta_icon = 'fas fa-video';
        break;
}
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <a href="<?= $cta_url ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" rel="<?= $data->user->plan_settings->dofollow_is_enabled ? 'dofollow' : 'nofollow' ?>" class="btn btn-block btn-primary link-btn <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?> <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="background-color: <?= $data->link->settings->background_color ?? '#007bff' ?>; color: <?= $data->link->settings->text_color ?? '#ffffff' ?>; border-color: <?= $data->link->settings->border_color ?? '#007bff' ?>;" data-text-color data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-animation data-background-color data-text-alignment>
        
        <?php if($data->link->settings->image ?? false): ?>
            <div class="link-btn-image-wrapper <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="margin-bottom: 8px;">
                <img src="<?= \SeeGap\Uploads::get_full_url('block_thumbnail_images') . $data->link->settings->image ?>" class="link-btn-image" loading="lazy" style="max-height: 32px; width: auto;" />
            </div>
        <?php endif ?>

        <span data-icon>
            <?php if($data->link->settings->icon ?? false): ?>
                <i class="<?= $data->link->settings->icon ?> mr-1"></i>
            <?php elseif($cta_icon): ?>
                <i class="<?= $cta_icon ?> mr-1"></i>
            <?php endif ?>
        </span>

        <span data-name><?= $data->link->settings->name ?? 'Call to Action' ?></span>
    </a>
</div>
