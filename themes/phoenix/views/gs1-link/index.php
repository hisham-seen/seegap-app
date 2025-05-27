<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('gs1-links') ?>"><?= l('gs1_links.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= $data['gs1_link']->gtin ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0">
            <i class="fas fa-fw fa-barcode mr-1"></i> <?= sprintf(l('gs1_link.header'), $data['gs1_link']->gtin) ?>
        </h1>

        <?= include_view(THEME_PATH . 'views/gs1-links/gs1_link_dropdown_button.php', ['id' => $data['gs1_link']->gs1_link_id, 'resource_name' => $data['gs1_link']->gtin]) ?>
    </div>

    <div class="mb-4">
        <span class="badge badge-light">
            <i class="fas fa-fw fa-calendar mr-1"></i> <?= sprintf(l('global.datetime_tooltip'), \Altum\Date::get($data['gs1_link']->datetime)) ?>
        </span>

        <span class="badge badge-light">
            <i class="fas fa-fw fa-chart-bar mr-1"></i> <?= sprintf(l('link.total_clicks'), nr($data['gs1_link']->clicks)) ?>
        </span>

        <?php if($data['gs1_link']->last_datetime): ?>
            <span class="badge badge-light">
                <i class="fas fa-fw fa-clock mr-1"></i> <?= sprintf(l('link.last_clicked'), \Altum\Date::get($data['gs1_link']->last_datetime, 2)) ?>
            </span>
        <?php endif ?>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mb-4">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="card-header-title">
                                <i class="fas fa-fw fa-pencil-alt"></i> <?= l('gs1_link.settings.header') ?>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="text-lg-right">
                                <a href="<?= url('gs1-link/' . $data['gs1_link']->gs1_link_id . '/statistics') ?>" class="btn btn-sm btn-light">
                                    <i class="fas fa-fw fa-chart-bar"></i> <?= l('link.statistics.link') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?= $this->views['method'] ?>
                </div>
            </div>

        </div>

        <div class="col-12 col-xl-4">
            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <label class="text-muted"><?= l('gs1_links.input.gtin') ?></label>
                        <div class="form-control-plaintext"><?= $data['gs1_link']->gtin ?></div>
                    </div>

                    <div class="form-group">
                        <label class="text-muted"><?= l('gs1_links.gs1_digital_link') ?></label>
                        <div class="form-control-plaintext">
                            <?php $gs1_digital_link = (new \Altum\Models\Domain())->get_domain_url($data['gs1_link']->domain_id) . '01/' . $data['gs1_link']->gtin ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?= $gs1_digital_link ?>" target="_blank" rel="noreferrer" class="text-truncate">
                                    <?= $gs1_digital_link ?>
                                </a>
                                <div>
                                    <button
                                        type="button"
                                        class="btn btn-link text-secondary p-0 ml-2"
                                        data-toggle="tooltip"
                                        title="<?= l('global.clipboard_copy') ?>"
                                        aria-label="<?= l('global.clipboard_copy') ?>"
                                        data-copy="<?= l('global.clipboard_copy') ?>"
                                        data-copied="<?= l('global.clipboard_copied') ?>"
                                        data-clipboard-text="<?= $gs1_digital_link ?>"
                                    >
                                        <i class="fas fa-fw fa-sm fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-muted"><?= l('gs1_links.input.target_url') ?></label>
                        <div class="form-control-plaintext">
                            <a href="<?= $data['gs1_link']->target_url ?>" target="_blank" rel="noreferrer" class="text-truncate">
                                <?= $data['gs1_link']->target_url ?>
                            </a>
                        </div>
                    </div>

                    <?php if(!empty($data['gs1_link']->title)): ?>
                        <div class="form-group">
                            <label class="text-muted"><?= l('gs1_links.input.title') ?></label>
                            <div class="form-control-plaintext"><?= $data['gs1_link']->title ?></div>
                        </div>
                    <?php endif ?>

                    <?php if(!empty($data['gs1_link']->description)): ?>
                        <div class="form-group">
                            <label class="text-muted"><?= l('gs1_links.input.description') ?></label>
                            <div class="form-control-plaintext"><?= $data['gs1_link']->description ?></div>
                        </div>
                    <?php endif ?>

                </div>
            </div>
        </div>
    </div>

</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* Clipboard functionality */
    new ClipboardJS('[data-clipboard-text]');
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
