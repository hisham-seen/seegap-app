<?php defined('SEEGAP') || die() ?>
<!DOCTYPE html>
<html lang="<?= \SeeGap\Language::$code ?>" dir="<?= l('direction') ?>" class="w-100 h-100">
<head>
    <title><?= \SeeGap\Title::get() ?></title>
    <base href="<?= SITE_URL; ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php if(\SeeGap\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
        <meta name="theme-color" content="<?= settings()->pwa->theme_color ?>"/>
        <link rel="manifest" href="<?= SITE_URL . UPLOADS_URL_PATH . \SeeGap\Uploads::get_path('pwa') . 'manifest.json' ?>" />
    <?php endif ?>

    <link rel="alternate" href="<?= SITE_URL . \SeeGap\Router::$original_request ?>" hreflang="x-default" />
    <?php if(count(\SeeGap\Language::$active_languages) > 1): ?>
        <?php foreach(\SeeGap\Language::$active_languages as $language_name => $language_code): ?>
            <?php if(settings()->main->default_language != $language_name): ?>
                <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \SeeGap\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>

    <?php if(!empty(settings()->main->favicon)): ?>
        <link href="<?= settings()->main->favicon_full_url ?>" rel="icon" />
    <?php endif ?>

    <link href="<?= ASSETS_FULL_URL . 'css/admin-' . \SeeGap\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
    <?php foreach(['admin-custom.css', 'libraries/select2.css'] as $file): ?>
        <link href="<?= ASSETS_FULL_URL ?>css/<?= $file ?>?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php endforeach ?>

    <?= \SeeGap\Event::get_content('head') ?>

        <?php if(is_logged_in() && !user()->plan_settings->export->pdf): ?>
            <style>@media print { body { display: none; } }</style>
        <?php endif ?>
</head>

<body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?>" data-theme-style="<?= \SeeGap\ThemeStyle::get() ?>">
<div id="admin_overlay" class="admin-overlay" style="display: none"></div>
<?php if((settings()->main->admin_spotlight_is_enabled ?? false) || (settings()->main->user_spotlight_is_enabled ?? false)) require THEME_PATH . 'views/partials/spotlight.php' ?>

<div class="app-container">
    <?= $this->views['admin_sidebar'] ?>

    <section class="app-content">
        <div class="py-4 p-lg-5 flex-grow-1">
            <?= include_view(THEME_PATH . 'views/admin/partials/admin_version_updates_bar.php') ?>
            <?= include_view(THEME_PATH . 'views/admin/partials/admin_support_bar.php') ?>

            <main class="seegap-animate seegap-animate-fill-none seegap-animate-fade-in">
                <?= $this->views['content'] ?>
            </main>
        </div>

        <footer class="app-footer">
            <div class="container-fluid d-print-none">
                <?= $this->views['footer'] ?>
            </div>
        </footer>
    </section>
</div>

    <?= \SeeGap\Event::get_content('modals') ?>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

<?php foreach(['libraries/jquery.slim.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'custom.js'] as $file): ?>
    <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
<?php endforeach ?>

<?php foreach(['libraries/select2.min.js', 'admin_custom.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.min.js',] as $file): ?>
    <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>" defer></script>
<?php endforeach ?>

<?= \SeeGap\Event::get_content('javascript') ?>

</body>
</html>
