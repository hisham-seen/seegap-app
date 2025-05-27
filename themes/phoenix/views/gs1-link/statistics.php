<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <div class="d-flex align-items-center mb-3 mb-md-0">
        <h1 class="h4 m-0"><i class="fas fa-fw fa-chart-bar mr-1"></i> <?= l('link.statistics.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('link.statistics.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="d-flex">
        <div>
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.export') ?>">
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('gs1-link/' . $data['gs1_link']->gs1_link_id . '/statistics?' . $data['datetime']->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('gs1-link/' . $data['gs1_link']->gs1_link_id . '/statistics?' . $data['datetime']->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.date_range') ?>">
                    <i class="fas fa-fw fa-calendar mr-1"></i>
                    <span>
                        <?php if($data['datetime']->start_date == $data['datetime']->end_date): ?>
                            <?= \Altum\Date::get($data['datetime']->start_date, 2, \Altum\Date::$default_timezone) ?>
                        <?php else: ?>
                            <?= \Altum\Date::get($data['datetime']->start_date, 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data['datetime']->end_date, 2, \Altum\Date::$default_timezone) ?>
                        <?php endif ?>
                    </span>
                    <i class="fas fa-fw fa-caret-down ml-1"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    <?php foreach(['today', 'yesterday', 'last_7_days', 'last_30_days', 'this_month', 'last_month', 'last_90_days', 'last_year'] as $date_key): ?>
                        <a class="dropdown-item <?= $data['datetime']->type == $date_key ? 'active' : null ?>" href="<?= url('gs1-link/' . $data['gs1_link']->gs1_link_id . '/statistics?type=' . $date_key) ?>">
                            <?= l('global.date.' . $date_key) ?>
                        </a>
                    <?php endforeach ?>

                    <div class="dropdown-divider"></div>

                    <div class="px-4">
                        <small class="text-muted"><?= l('global.date.custom_range') ?></small>
                        <form action="" method="get" role="form">
                            <div class="row">
                                <div class="col">
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="start_date"
                                        value="<?= $data['datetime']->start_date ?>"
                                        max="<?= \Altum\Date::get('', 4) ?>"
                                    />
                                </div>
                                <div class="col">
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="end_date"
                                        value="<?= $data['datetime']->end_date ?>"
                                        max="<?= \Altum\Date::get('', 4) ?>"
                                    />
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(!count($data['pageviews'])): ?>
    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
        'filters_get' => $data['datetime']->get_get(),
        'name' => 'track_links',
        'has_secondary_text' => false,
    ]); ?>
<?php else: ?>

    <div class="row justify-content-between mb-4">
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0">
                <div class="card-body d-flex">
                    <div class="card-icon-container bg-primary-100 text-primary-600 border border-primary-200 rounded mr-3">
                        <i class="fas fa-fw fa-chart-bar"></i>
                    </div>

                    <div class="card-text-container">
                        <span class="card-title text-truncate h4 m-0"><?= nr($data['total']['pageviews']) ?></span>
                        <span class="card-text text-truncate text-muted"><?= l('link.statistics.pageviews') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0">
                <div class="card-body d-flex">
                    <div class="card-icon-container bg-gray-100 text-gray-600 border border-gray-200 rounded mr-3">
                        <i class="fas fa-fw fa-eye"></i>
                    </div>

                    <div class="card-text-container">
                        <span class="card-title text-truncate h4 m-0"><?= nr($data['total']['visitors']) ?></span>
                        <span class="card-text text-truncate text-muted"><?= l('link.statistics.visitors') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0">
                <div class="card-body d-flex">
                    <div class="card-icon-container bg-gray-100 text-gray-600 border border-gray-200 rounded mr-3">
                        <i class="fas fa-fw fa-mouse-pointer"></i>
                    </div>

                    <div class="card-text-container">
                        <span class="card-title text-truncate h4 m-0"><?= nr(get_percentage_between_two_numbers($data['total']['visitors'], $data['total']['pageviews'])) ?>%</span>
                        <span class="card-text text-truncate text-muted"><?= l('link.statistics.visitors_percentage') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0">
                <div class="card-body d-flex">
                    <div class="card-icon-container bg-gray-100 text-gray-600 border border-gray-200 rounded mr-3">
                        <i class="fas fa-fw fa-redo"></i>
                    </div>

                    <div class="card-text-container">
                        <span class="card-title text-truncate h4 m-0"><?= nr(get_percentage_between_two_numbers($data['total']['pageviews'] - $data['total']['visitors'], $data['total']['pageviews'])) ?>%</span>
                        <span class="card-text text-truncate text-muted"><?= l('link.statistics.returning_visitors_percentage') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-container mb-5">
        <canvas id="pageviews_chart"></canvas>
    </div>

    <?php if(count($data['top_countries'])): ?>
        <div class="row mb-5">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="card-title"><?= l('link.statistics.top_countries') ?></div>
                    </div>

                    <div class="card-body">
                        <?php foreach($data['top_countries'] as $country => $pageviews): ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <div class="text-truncate">
                                        <span class="mr-2"><?= get_country_flag($country) ?></span>
                                        <span class="align-middle"><?= get_country_from_country_code($country) ?></span>
                                    </div>
                                    <div>
                                        <small class="text-muted"><?= nr($pageviews) ?> <?= l('link.statistics.pageviews') ?></small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?= get_percentage_between_two_numbers($pageviews, $data['total']['pageviews']) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="card-title"><?= l('link.statistics.top_referrers') ?></div>
                    </div>

                    <div class="card-body">
                        <?php if(count($data['top_referrers'])): ?>
                            <?php foreach($data['top_referrers'] as $referrer => $pageviews): ?>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <div class="text-truncate">
                                            <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($referrer)['host'] ?? $referrer ?>.ico" class="img-fluid icon-favicon mr-2" loading="lazy" />
                                            <span class="align-middle"><?= $referrer ?></span>
                                        </div>
                                        <div>
                                            <small class="text-muted"><?= nr($pageviews) ?> <?= l('link.statistics.pageviews') ?></small>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: <?= get_percentage_between_two_numbers($pageviews, $data['total']['pageviews']) ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else: ?>
                            <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                                'filters_get' => $data['datetime']->get_get(),
                                'name' => 'track_links',
                                'has_secondary_text' => false,
                            ]); ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if(count($data['top_os'])): ?>
        <div class="row mb-5">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="card-title"><?= l('link.statistics.top_os') ?></div>
                    </div>

                    <div class="card-body">
                        <?php foreach($data['top_os'] as $os => $pageviews): ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <div class="text-truncate">
                                        <i class="fab fa-fw fa-<?= get_os_icon($os) ?> text-muted mr-1"></i>
                                        <span class="align-middle"><?= $os ?></span>
                                    </div>
                                    <div>
                                        <small class="text-muted"><?= nr($pageviews) ?> <?= l('link.statistics.pageviews') ?></small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?= get_percentage_between_two_numbers($pageviews, $data['total']['pageviews']) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="card-title"><?= l('link.statistics.top_browsers') ?></div>
                    </div>

                    <div class="card-body">
                        <?php foreach($data['top_browsers'] as $browser => $pageviews): ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <div class="text-truncate">
                                        <i class="fab fa-fw fa-<?= get_browser_icon($browser) ?> text-muted mr-1"></i>
                                        <span class="align-middle"><?= $browser ?></span>
                                    </div>
                                    <div>
                                        <small class="text-muted"><?= nr($pageviews) ?> <?= l('link.statistics.pageviews') ?></small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?= get_percentage_between_two_numbers($pageviews, $data['total']['pageviews']) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if(count($data['top_devices'])): ?>
        <div class="row mb-5">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="card-title"><?= l('link.statistics.top_devices') ?></div>
                    </div>

                    <div class="card-body">
                        <?php foreach($data['top_devices'] as $device_type => $pageviews): ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <div class="text-truncate">
                                        <i class="fas fa-fw fa-<?= get_device_type_icon($device_type) ?> text-muted mr-1"></i>
                                        <span class="align-middle"><?= l('global.device.' . $device_type) ?></span>
                                    </div>
                                    <div>
                                        <small class="text-muted"><?= nr($pageviews) ?> <?= l('link.statistics.pageviews') ?></small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?= get_percentage_between_two_numbers($pageviews, $data['total']['pageviews']) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="card-title"><?= l('link.statistics.top_languages') ?></div>
                    </div>

                    <div class="card-body">
                        <?php if(count($data['top_languages'])): ?>
                            <?php foreach($data['top_languages'] as $language => $pageviews): ?>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <div class="text-truncate">
                                            <span class="align-middle"><?= get_language_from_locale($language) ?></span>
                                        </div>
                                        <div>
                                            <small class="text-muted"><?= nr($pageviews) ?> <?= l('link.statistics.pageviews') ?></small>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: <?= get_percentage_between_two_numbers($pageviews, $data['total']['pageviews']) ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else: ?>
                            <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                                'filters_get' => $data['datetime']->get_get(),
                                'name' => 'track_links',
                                'has_secondary_text' => false,
                            ]); ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

<?php endif ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let pageviews_chart = document.getElementById('pageviews_chart').getContext('2d');

    color_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(pageviews_chart, {
        type: 'line',
        data: {
            labels: <?= $data['pageviews_chart']['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('link.statistics.pageviews')) ?>,
                    data: <?= $data['pageviews_chart']['pageviews'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
