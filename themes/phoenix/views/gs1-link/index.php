<?php defined('ALTUMCODE') || die() ?>

<div class="container-fluid">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('gs1-links') ?>"><?= l('gs1_links.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= $data->gs1_link->gtin ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0">
            <i class="fas fa-fw fa-barcode mr-1"></i> <?= sprintf(l('gs1_link.header'), $data->gs1_link->gtin) ?>
        </h1>

        <?= include_view(THEME_PATH . 'views/gs1-links/gs1_link_dropdown_button.php', ['id' => $data->gs1_link->gs1_link_id, 'resource_name' => $data->gs1_link->gtin]) ?>
    </div>

    <div class="mb-4">
        <span class="badge badge-light">
            <i class="fas fa-fw fa-calendar mr-1"></i> <?= sprintf(l('global.datetime_tooltip'), \Altum\Date::get($data->gs1_link->datetime)) ?>
        </span>

        <span class="badge badge-light">
            <i class="fas fa-fw fa-chart-bar mr-1"></i> <?= sprintf(l('link.total_clicks'), nr($data->gs1_link->clicks)) ?>
        </span>

        <?php if($data->gs1_link->last_datetime): ?>
            <span class="badge badge-light">
                <i class="fas fa-fw fa-clock mr-1"></i> <?= sprintf(l('link.last_clicked'), \Altum\Date::get($data->gs1_link->last_datetime, 2)) ?>
            </span>
        <?php endif ?>
    </div>

    <?= $this->views['method'] ?>

</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* Clipboard functionality */
    new ClipboardJS('[data-clipboard-text]');
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
