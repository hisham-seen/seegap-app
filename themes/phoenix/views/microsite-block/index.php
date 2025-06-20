<?php defined('SEEGAP') || die() ?>

<div class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('links') ?>"><?= l('links.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('link/' . $data->microsite_block->link_id . '?tab=blocks') ?>"><?= l('link.breadcrumb.microsite') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page">
                <?= l('link.breadcrumb.microsite_block') . ' ' . l('link.statistics.breadcrumb') ?>
            </li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row">
        <div class="col text-truncate">
            <h1 class="h3 text-truncate"><?= sprintf(l('link.header.header'), $data->microsite_block->location_url ?? $data->microsite_block->settings->name ?? l('microsite_block.title')) ?></h1>
        </div>
    </div>

    <div class="d-flex align-items-baseline mb-5">
        <span class="mr-1">
            <i class="fas fa-fw fa-circle fa-sm" style="color: <?= $data->microsite_blocks[$data->microsite_block->type]['color'] ?>"></i>
        </span>

        <div class="text-muted text-truncate">
            <?= l('link.microsite.blocks.' . $data->microsite_block->type) ?>
        </div>
    </div>

    <?= $this->views['method'] ?>
</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \SeeGap\Event::add_content(ob_get_clean(), 'head') ?>

