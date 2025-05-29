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
    </div>

    <?= $this->views['links_content'] ?>
</div>
