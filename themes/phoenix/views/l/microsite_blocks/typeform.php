<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="w-100 link-iframe-round" style="height: 500px" data-tf-widget="<?= $data->embed ?>"></div>
</div>

<?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'typeform')): ?>
    <?php ob_start() ?>
    <script defer src="//embed.typeform.com/next/embed.js"></script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'typeform') ?>
<?php endif ?>
