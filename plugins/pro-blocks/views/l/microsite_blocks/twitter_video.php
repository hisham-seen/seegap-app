<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?> d-flex justify-content-center">
    <blockquote class="twitter-tweet" data-media-max-width="1920"><a href="<?= $data->link->location_url ?>"></a></blockquote>
</div>

<?php if(!\Altum\Event::exists_content_type_key('javascript', 'twitter')): ?>
    <?php ob_start() ?>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'twitter') ?>
<?php endif ?>

<?php ob_start() ?>
<style media="screen,print">
    .twitter-tweet iframe {
        border-radius: var(--border-radius) !important;
    }
</style>
<?php \Altum\Event::add_content(ob_get_clean(), 'head', 'countdown') ?>



