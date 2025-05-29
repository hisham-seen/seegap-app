<?php defined('ALTUMCODE') || die() ?>

<?php
$size = 'fa-2x';
switch ($data->link->settings->size) {
    case 's':
        $size = '';
        break;

    case 'm':
        $size = 'fa-lg';
        break;

    case 'l':
        $size = 'fa-2x';
        break;

    case 'xl':
        $size = 'fa-3x';
        break;
}
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="d-flex flex-wrap justify-content-center">
        <?php $microsite_socials = require APP_PATH . 'includes/microsite_socials.php'; ?>
        <?php foreach($data->link->settings->socials as $key => $value): ?>
            <?php if($value): ?>
                <div class="my-2 mx-2 p-2 <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') ?>" style="background: <?= $data->link->settings->background_color ?: '#FFFFFF00' ?>" data-toggle="tooltip" title="<?= l('microsite_socials.' . $key . '.name') ?>" data-border-radius data-background-color>
                    <a href="<?= sprintf($microsite_socials[$key]['format'], $value) ?>" target="_blank" rel="noreferrer" class="<?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
                        <i class="<?= $microsite_socials[$key]['icon'] ?> <?= $size ?> fa-fw" style="color: <?= $data->link->settings->color ?>" data-color></i>
                    </a>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>

