<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="d-flex align-items-center justify-content-center">
        <div id="<?= 'seegap_countdown_' . $data->link->microsite_block_id ?>" class="seegap-countdown-container"></div>
    </div>
</div>

<?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'seegap_countdown')): ?>
    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/countdown-styles.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'head', 'seegap_countdown') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/seegap-countdown.js?v=' . PRODUCT_CODE ?>"></script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'seegap_countdown') ?>
<?php endif ?>

<?php ob_start() ?>
<script>
    'use strict';
    document.addEventListener('DOMContentLoaded', () => {
        new SeeGapCountdown({
            containerId: '<?= 'seegap_countdown_' . $data->link->microsite_block_id ?>',
            endDate: <?= (new DateTime($data->link->settings->counter_end_date))->getTimestamp() ?>,
            style: <?= json_encode($data->link->settings->style ?? 'digital-led') ?>,
            theme: <?= json_encode($data->link->settings->theme ?? 'light') ?>,
            labels: [
                <?= json_encode(l('global.date.days')) ?>, 
                <?= json_encode(l('global.date.hours')) ?>, 
                <?= json_encode(l('global.date.minutes')) ?>, 
                <?= json_encode(l('global.date.seconds')) ?>
            ],
            onComplete: () => {
                console.log('Countdown completed!');
            }
        });
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
