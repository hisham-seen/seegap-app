<?php defined('ALTUMCODE') || die() ?>

<!DOCTYPE html>
<html lang="<?= \Altum\Language::$code ?>" dir="<?= l('direction') ?>">
<head>
    <title><?= \Altum\Title::get() ?></title>
    <base href="<?= SITE_URL ?>">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
        <meta name="theme-color" content="<?= settings()->pwa->theme_color ?>"/>
        <link rel="manifest" href="<?= SITE_URL . UPLOADS_URL_PATH . 'pwa/' . 'manifest.json' ?>">
    <?php endif ?>

    <link rel="alternate" href="<?= SITE_URL . \Altum\Router::$original_request ?>" hreflang="x-default" />
    <?php if(count(\Altum\Language::$active_languages) > 1): ?>
        <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
            <?php if(settings()->main->default_language != $language_name): ?>
                <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \Altum\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>

    <?php if(!empty(settings()->main->favicon)): ?>
        <link href="<?= UPLOADS_FULL_URL . 'main/' . settings()->main->favicon ?>" rel="shortcut icon" />
    <?php endif ?>

    <link href="<?= ASSETS_FULL_URL . 'css/bootstrap.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php foreach(['custom.css'] as $file): ?>
        <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php endforeach ?>
    
    <!-- Apache Superset SDK (Local) -->
    <script src="<?= ASSETS_FULL_URL . 'js/superset-embedded-sdk.js?v=' . PRODUCT_CODE ?>"></script>

    <?php if(!empty(settings()->custom->head_css)): ?>
        <style><?= settings()->custom->head_css ?></style>
    <?php endif ?>

    <?php if(!empty(settings()->custom->head_js)): ?>
        <?= settings()->custom->head_js ?>
    <?php endif ?>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        .report-container {
            width: 100vw;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        .report-frame {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        
        .report-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1001;
        }
        
        .report-error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1001;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="report-container">
        <div class="report-loading" id="report-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only"><?= l('global.loading') ?></span>
            </div>
            <div class="mt-3">
                <h5><?= l('reports.loading') ?></h5>
                <p class="text-muted"><?= l('reports.loading_description') ?></p>
            </div>
        </div>

        <div class="report-error d-none" id="report-error">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5><?= l('reports.error') ?></h5>
                <p class="text-muted"><?= l('reports.error_description') ?></p>
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    <i class="fas fa-redo mr-1"></i> <?= l('global.retry') ?>
                </button>
                <a href="<?= url('reports') ?>" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left mr-1"></i> <?= l('reports.back_to_list') ?>
                </a>
            </div>
        </div>

        <?php if(!empty($data->report->superset_embed_code)): ?>
            <div id="superset-container" style="width: 100%; height: 100%;"></div>
        <?php else: ?>
            <div class="report-error">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5><?= l('reports.no_embed_code') ?></h5>
                    <p class="text-muted"><?= l('reports.no_embed_code_description') ?></p>
                    <a href="<?= url('reports') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-1"></i> <?= l('reports.back_to_list') ?>
                    </a>
                </div>
            </div>
        <?php endif ?>
    </div>

    <?php if(!empty($data->report->superset_embed_code)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loading = document.getElementById('report-loading');
            const error = document.getElementById('report-error');
            const container = document.getElementById('superset-container');
            
            // Check if Superset SDK is loaded
            if (typeof supersetEmbeddedSdk === 'undefined') {
                console.error('Superset SDK not loaded');
                loading.classList.add('d-none');
                error.classList.remove('d-none');
                return;
            }
            
            try {
                // Embed the Superset dashboard using the official SDK
                supersetEmbeddedSdk.embedDashboard({
                    id: "<?= $data->report->superset_embed_code ?>", // UUID from the database
                    supersetDomain: "<?= $data->report->superset_domain ?>", // Domain from database
                    mountPoint: container,
                    fetchGuestToken: () => {
                        // You'll need to implement guest token fetching
                        // This should return a promise that resolves to a guest token
                        return fetch('/api/superset-guest-token', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                dashboard_id: "<?= $data->report->superset_embed_code ?>"
                            })
                        }).then(response => response.json()).then(data => data.token);
                    },
                    dashboardUiConfig: {
                        hideTitle: false,
                        hideTab: false,
                        hideChartControls: false,
                        filters: {
                            expanded: false,
                            visible: true
                        }
                    },
                    debug: true // Enable debug mode for development
                }).then((dashboard) => {
                    // Dashboard loaded successfully
                    console.log('Dashboard embedded successfully:', dashboard);
                    loading.classList.add('d-none');
                }).catch((error) => {
                    console.error('Error loading Superset dashboard:', error);
                    loading.classList.add('d-none');
                    document.getElementById('report-error').classList.remove('d-none');
                });
                
            } catch (e) {
                console.error('Error initializing Superset dashboard:', e);
                loading.classList.add('d-none');
                error.classList.remove('d-none');
            }
        });
    </script>
    <?php endif ?>

    <?php if(!empty(settings()->custom->body_content)): ?>
        <?= settings()->custom->body_content ?>
    <?php endif ?>
</body>
</html>
