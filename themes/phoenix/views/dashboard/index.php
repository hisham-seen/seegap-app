<?php defined('ALTUMCODE') || die() ?>

<div class="container-fluid">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="mb-4">
        <div class="row m-n3 justify-content-between">
            <?php if(settings()->links->microsites_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(94, 68, 255, 0.1); color: var(--phoenix-primary);">
                                    <i class="fas fa-fw fa-hashtag"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->microsite_links_total) ?></div>
                                    <div class="dashboard-stats-label"><?= l('dashboard.microsites') ?></div>
                                </div>
                            </div>
                            <a href="<?= url('links?type=microsite') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->shortener_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(20, 184, 166, 0.1); color: #14b8a6;">
                                    <i class="fas fa-fw fa-link"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->link_links_total) ?></div>
                                    <div class="dashboard-stats-label"><?= l('dashboard.links') ?></div>
                                </div>
                            </div>
                            <a href="<?= url('links?type=link') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->files_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <i class="fas fa-fw fa-file"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->file_links_total) ?></div>
                                    <div class="dashboard-stats-label"><?= l('dashboard.file_links') ?></div>
                                </div>
                            </div>
                            <a href="<?= url('links?type=file') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->events_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                    <i class="fas fa-fw fa-calendar"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->event_links_total) ?></div>
                                    <div class="dashboard-stats-label"><?= l('dashboard.event_links') ?></div>
                                </div>
                            </div>
                            <a href="<?= url('links?type=event') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->static_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(192, 38, 211, 0.1); color: #c026d3;">
                                    <i class="fas fa-fw fa-file-code"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->static_links_total) ?></div>
                                    <div class="dashboard-stats-label"><?= l('dashboard.static_links') ?></div>
                                </div>
                            </div>
                            <a href="<?= url('links?type=static') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if($data->gs1_links_total !== null): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                    <i class="fas fa-fw fa-barcode"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->gs1_links_total) ?></div>
                                    <div class="dashboard-stats-label">GS1 Links</div>
                                </div>
                            </div>
                            <a href="<?= url('gs1-links') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if($data->qr_codes_total !== null): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                    <i class="fas fa-fw fa-qrcode"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->qr_codes_total) ?></div>
                                    <div class="dashboard-stats-label">QR Codes</div>
                                </div>
                            </div>
                            <a href="<?= url('qr-codes') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if($data->data_submissions_total !== null): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card dashboard-card h-100 position-relative">
                        <div class="card-body">
                            <div class="dashboard-stats-card">
                                <div class="dashboard-stats-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                                    <i class="fas fa-fw fa-database"></i>
                                </div>
                                <div class="dashboard-stats-info">
                                    <div class="dashboard-stats-value"><?= nr($data->data_submissions_total) ?></div>
                                    <div class="dashboard-stats-label">Data Submissions</div>
                                </div>
                            </div>
                            <a href="<?= url('data') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <?php if($data->links_chart): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><?= l('dashboard.statistics') ?></h5>
                    <div class="chart-container <?= !$data->links_chart['is_empty'] ? null : 'd-none' ?>">
                        <canvas id="pageviews_chart"></canvas>
                    </div>
                    <?= !$data->links_chart['is_empty'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>

                    <?php if(!$data->links_chart['is_empty'] && settings()->main->chart_cache ?? 12): ?>
                        <small class="text-muted mt-2 d-block"><i class="fas fa-fw fa-sm fa-info-circle mr-1"></i> <?= sprintf(l('global.chart_help'), settings()->main->chart_cache ?? 12, settings()->main->chart_days ?? 30) ?></small>
                    <?php endif ?>
                </div>
            </div>

<?php require THEME_PATH . 'views/partials/js_chart_defaults.php' ?>

            <?php ob_start() ?>
            <script>
                if(document.getElementById('pageviews_chart')) {
                    /* Phoenix theme colors */
                    let pageviews_color = '#5e44ff'; // Phoenix primary color
                    let visitors_color = '#6c757d'; // Phoenix secondary color
                    let pageviews_color_gradient = null;
                    let visitors_color_gradient = null;

                    /* Chart */
                    let pageviews_chart = document.getElementById('pageviews_chart').getContext('2d');

                    /* Colors */
                    pageviews_color_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
                    pageviews_color_gradient.addColorStop(0, set_hex_opacity(pageviews_color, 0.6));
                    pageviews_color_gradient.addColorStop(1, set_hex_opacity(pageviews_color, 0.1));

                    visitors_color_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
                    visitors_color_gradient.addColorStop(0, set_hex_opacity(visitors_color, 0.6));
                    visitors_color_gradient.addColorStop(1, set_hex_opacity(visitors_color, 0.1));

                    new Chart(pageviews_chart, {
                        type: 'line',
                        data: {
                            labels: <?= $data->links_chart['labels'] ?? '[]' ?>,
                            datasets: [
                                {
                                    label: <?= json_encode(l('link.statistics.pageviews')) ?>,
                                    data: <?= $data->links_chart['pageviews'] ?? '[]' ?>,
                                    backgroundColor: pageviews_color_gradient,
                                    borderColor: pageviews_color,
                                    fill: true
                                },
                                {
                                    label: <?= json_encode(l('link.statistics.visitors')) ?>,
                                    data: <?= $data->links_chart['visitors'] ?? '[]' ?>,
                                    backgroundColor: visitors_color_gradient,
                                    borderColor: visitors_color,
                                    fill: true
                                }
                            ]
                        },
                        options: chart_options
                    });
                }
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
        <?php endif ?>

        <?php if(!empty($data->analytics_data) || true): ?>
            <!-- Geographic Analytics -->
            <div class="row mt-4">
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-fw fa-globe mr-2"></i>Top Countries</h5>
                            <?php if(!empty($data->analytics_data['countries'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php foreach($data->analytics_data['countries'] as $country): ?>
                                                <?php 
                                                    $total_countries = array_sum(array_column($data->analytics_data['countries'], 'count'));
                                                    $percentage = $total_countries > 0 ? round(($country->count / $total_countries) * 100, 1) : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <i class="flag-icon flag-icon-<?= strtolower($country->country_code) ?> mr-2"></i>
                                                        <?= get_country_from_country_code($country->country_code) ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="badge badge-light"><?= nr($country->count) ?></span>
                                                    </td>
                                                    <td class="text-right text-muted small"><?= $percentage ?>%</td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-globe fa-3x mb-3 text-muted"></i>
                                    <p class="mb-0">No country data available yet</p>
                                    <small>Geographic analytics will appear here once you have link visits</small>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-fw fa-city mr-2"></i>Top Cities</h5>
                            <?php if(!empty($data->analytics_data['cities'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php foreach($data->analytics_data['cities'] as $city): ?>
                                                <?php 
                                                    $total_cities = array_sum(array_column($data->analytics_data['cities'], 'count'));
                                                    $percentage = $total_cities > 0 ? round(($city->count / $total_cities) * 100, 1) : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <i class="flag-icon flag-icon-<?= strtolower($city->country_code) ?> mr-2"></i>
                                                        <?= $city->city_name ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="badge badge-light"><?= nr($city->count) ?></span>
                                                    </td>
                                                    <td class="text-right text-muted small"><?= $percentage ?>%</td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-city fa-3x mb-3 text-muted"></i>
                                    <p class="mb-0">No city data available yet</p>
                                    <small>City analytics will appear here once you have link visits</small>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Traffic Sources & UTM Analytics -->
            <div class="row mt-4">
                <div class="col-12 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-fw fa-external-link-alt mr-2"></i>Top Referrers</h5>
                            <?php if(!empty($data->analytics_data['referrers'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php foreach($data->analytics_data['referrers'] as $referrer): ?>
                                                <?php 
                                                    $total_referrers = array_sum(array_column($data->analytics_data['referrers'], 'count'));
                                                    $percentage = $total_referrers > 0 ? round(($referrer->count / $total_referrers) * 100, 1) : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <i class="fas fa-fw fa-globe text-muted mr-2"></i>
                                                        <?= $referrer->referrer_host ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="badge badge-light"><?= nr($referrer->count) ?></span>
                                                    </td>
                                                    <td class="text-right text-muted small"><?= $percentage ?>%</td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-external-link-alt fa-3x mb-3 text-muted"></i>
                                    <p class="mb-0">No referrer data available yet</p>
                                    <small>Traffic source analytics will appear here once you have link visits</small>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-fw fa-tags mr-2"></i>UTM Sources</h5>
                            <?php if(!empty($data->analytics_data['utm_sources'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php foreach($data->analytics_data['utm_sources'] as $utm_source): ?>
                                                <?php 
                                                    $total_utm_sources = array_sum(array_column($data->analytics_data['utm_sources'], 'count'));
                                                    $percentage = $total_utm_sources > 0 ? round(($utm_source->count / $total_utm_sources) * 100, 1) : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <i class="fas fa-fw fa-tag text-muted mr-2"></i>
                                                        <?= $utm_source->utm_source ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="badge badge-light"><?= nr($utm_source->count) ?></span>
                                                    </td>
                                                    <td class="text-right text-muted small"><?= $percentage ?>%</td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-tags fa-3x mb-3 text-muted"></i>
                                    <p class="mb-0">No UTM source data available yet</p>
                                    <small>Campaign source analytics will appear here once you have UTM-tagged visits</small>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-fw fa-bullhorn mr-2"></i>UTM Campaigns</h5>
                            <?php if(!empty($data->analytics_data['utm_campaigns'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php foreach($data->analytics_data['utm_campaigns'] as $utm_campaign): ?>
                                                <?php 
                                                    $total_utm_campaigns = array_sum(array_column($data->analytics_data['utm_campaigns'], 'count'));
                                                    $percentage = $total_utm_campaigns > 0 ? round(($utm_campaign->count / $total_utm_campaigns) * 100, 1) : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <i class="fas fa-fw fa-bullhorn text-muted mr-2"></i>
                                                        <?= $utm_campaign->utm_campaign ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="badge badge-light"><?= nr($utm_campaign->count) ?></span>
                                                    </td>
                                                    <td class="text-right text-muted small"><?= $percentage ?>%</td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-bullhorn fa-3x mb-3 text-muted"></i>
                                    <p class="mb-0">No UTM campaign data available yet</p>
                                    <small>Campaign analytics will appear here once you have UTM-tagged visits</small>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>


        <?php endif ?>
    </div>

    <?= $this->views['links_content'] ?>
</div>
