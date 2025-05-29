<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="link-iframe-round">
        <?= $data->link->settings->content ?>
    </div>
</div>

<?php if(!\Altum\Event::exists_content_type_key('head', 'reddit')): ?>
    <?php ob_start() ?>
    <style>
        .embedly-card-hug {
            max-width: 100% !important;
        }
        .embedly-card-hug iframe {
            width: 100% !important;
        }
    </style>    <?php \Altum\Event::add_content(ob_get_clean(), 'head', 'reddit') ?>
<?php endif ?>
