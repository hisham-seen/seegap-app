<?php defined('ALTUMCODE') || die() ?>

<div class="row">
    <div class="col-12 col-lg-6 col-xl-3 mb-4">
        <div class="card border-0 h-100">
            <div class="card-body d-flex">
                <div class="card-icon-container bg-primary-100 text-primary-600 mr-3">
                    <i class="fas fa-fw fa-chart-bar"></i>
                </div>

                <div class="card-text-container">
                    <div class="card-title text-truncate"><?= l('link.statistics.total_clicks') ?></div>
                    <span class="h4"><?= nr($data->total_clicks ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-3 mb-4">
        <div class="card border-0 h-100">
            <div class="card-body d-flex">
                <div class="card-icon-container bg-primary-100 text-primary-600 mr-3">
                    <i class="fas fa-fw fa-eye"></i>
                </div>

                <div class="card-text-container">
                    <div class="card-title text-truncate"><?= l('link.statistics.unique_clicks') ?></div>
                    <span class="h4"><?= nr($data->unique_clicks ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-3 mb-4">
        <div class="card border-0 h-100">
            <div class="card-body d-flex">
                <div class="card-icon-container bg-primary-100 text-primary-600 mr-3">
                    <i class="fas fa-fw fa-globe"></i>
                </div>

                <div class="card-text-container">
                    <div class="card-title text-truncate"><?= l('link.statistics.countries') ?></div>
                    <span class="h4"><?= nr($data->countries ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-3 mb-4">
        <div class="card border-0 h-100">
            <div class="card-body d-flex">
                <div class="card-icon-container bg-primary-100 text-primary-600 mr-3">
                    <i class="fas fa-fw fa-mobile"></i>
                </div>

                <div class="card-text-container">
                    <div class="card-title text-truncate"><?= l('link.statistics.device_types') ?></div>
                    <span class="h4"><?= nr($data->device_types ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($data->clicks_chart)): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title"><?= l('link.statistics.clicks_chart') ?></div>
    </div>

    <div class="card-body">
        <canvas id="clicks_chart" class="chart"></canvas>
    </div>
</div>
<?php endif ?>

<?php ob_start() ?>
<script>
    'use strict';

    <?php if(!empty($data->clicks_chart)): ?>
    /* Clicks chart */
    let clicks_chart = document.getElementById('clicks_chart').getContext('2d');

    new Chart(clicks_chart, {
        type: 'line',
        data: {
            labels: <?= $data->clicks_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('link.statistics.total_clicks')) ?>,
                    data: <?= $data->clicks_chart['total_clicks'] ?? '[]' ?>,
                    backgroundColor: 'rgba(63, 136, 253, 0.1)',
                    borderColor: 'rgba(63, 136, 253, 1)',
                    fill: true
                },
                {
                    label: <?= json_encode(l('link.statistics.unique_clicks')) ?>,
                    data: <?= $data->clicks_chart['unique_clicks'] ?? '[]' ?>,
                    backgroundColor: 'rgba(134, 142, 150, 0.1)',
                    borderColor: 'rgba(134, 142, 150, 1)',
                    fill: true
                }
            ]
        },
        options: chart_options
    });
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
