<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="fb-post-responsive">
        <div class="fb-post" data-href="<?= $data->link->location_url ?>" data-width="auto"></div>
    </div>
</div>

<?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'facebook')): ?>
    <?php ob_start() ?>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v14.0"></script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'facebook') ?>

    <?php ob_start() ?>
    <style>
        .fb-post-responsive {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: .3rem;
        }

        .fb-post-responsive .fb-post {
            max-width: 100%;
            width: 100%;
            display: block;
        }
    </style>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'head', 'facebook') ?>
<?php endif ?>


