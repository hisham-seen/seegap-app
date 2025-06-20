<?php defined('SEEGAP') || die() ?>
<!DOCTYPE html>
<html lang="<?= \SeeGap\Language::$code ?>" dir="<?= l('direction') ?>">
<head>
    <title><?= \SeeGap\Title::get() ?></title>
    <base href="<?= SITE_URL; ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php if(\SeeGap\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
        <meta name="theme-color" content="<?= settings()->pwa->theme_color ?>"/>
        <link rel="manifest" href="<?= SITE_URL . UPLOADS_URL_PATH . \SeeGap\Uploads::get_path('pwa') . 'manifest.json' ?>" />
    <?php endif ?>

    <?php if(\SeeGap\Meta::$description): ?>
        <meta name="description" content="<?= \SeeGap\Meta::$description ?>" />
    <?php endif ?>
    <?php if(\SeeGap\Meta::$keywords): ?>
        <meta name="keywords" content="<?= \SeeGap\Meta::$keywords ?>" />
    <?php endif ?>

    <?php \SeeGap\Meta::output() ?>

    <?php if(\SeeGap\Meta::$canonical): ?>
        <link rel="canonical" href="<?= \SeeGap\Meta::$canonical ?>" />
    <?php endif ?>

    <?php if(\SeeGap\Meta::$robots): ?>
        <meta name="robots" content="<?= \SeeGap\Meta::$robots ?>">
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

    <!-- Preload critical FontAwesome files for better icon loading -->
    <?php foreach(['libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.min.js'] as $file): ?>
        <link rel="preload" href="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>" as="script">
    <?php endforeach ?>

    <link href="<?= ASSETS_FULL_URL . 'css/' . \SeeGap\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
    <?php foreach(['custom.css', 'libraries/select2.css'] as $file): ?>
        <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php endforeach ?>

    <?= \SeeGap\Event::get_content('head') ?>

        <?php if(is_logged_in() && !user()->plan_settings->export->pdf): ?>
            <style>@media print { body { display: none; } }</style>
        <?php endif ?>

    <?php if(!empty(settings()->custom->head_js)): ?>
        <?= get_settings_custom_head_js() ?>
    <?php endif ?>

    <?php if(!empty(settings()->custom->head_css)): ?>
        <style><?= settings()->custom->head_css ?></style>
    <?php endif ?>
</head>

<body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?> app <?= \SeeGap\ThemeStyle::get() == 'dark' ? 'cc--darkmode' : null ?>" data-theme-style="<?= \SeeGap\ThemeStyle::get() ?>">
    <?php if(!empty(settings()->custom->body_content)): ?>
        <?= settings()->custom->body_content ?>
    <?php endif ?>

    <?php //SEEGAP:DEMO if(DEMO) echo include_view(THEME_PATH . 'views/partials/ac_banner.php', ['demo_url' => 'https://66microsites.com/demo/', 'product_name' => PRODUCT_NAME, 'product_url' => PRODUCT_URL]) ?>
    <?php if((isset(settings()->main->admin_spotlight_is_enabled) && settings()->main->admin_spotlight_is_enabled) || (isset(settings()->main->user_spotlight_is_enabled) && settings()->main->user_spotlight_is_enabled)) require THEME_PATH . 'views/partials/spotlight.php' ?>
    <?php if(\SeeGap\Plugin::is_active('pwa') && settings()->pwa->is_enabled && settings()->pwa->display_install_bar) require \SeeGap\Plugin::get('pwa')->path . 'views/partials/pwa.php' ?>

    <div id="app_overlay" class="app-overlay" style="display: none"></div>

    <div class="app-container">
        <?= $this->views['app_sidebar'] ?>

        <section class="app-content">
            <?php require THEME_PATH . 'views/partials/js_welcome.php' ?>
            <?php require THEME_PATH . 'views/partials/admin_impersonate_user.php' ?>
            <?php require THEME_PATH . 'views/partials/team_delegate_access.php' ?>
            <?php require THEME_PATH . 'views/partials/announcements.php' ?>
            <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>
            <?php require THEME_PATH . 'views/partials/ad_blocker_detector.php' ?>
            <?php if(\SeeGap\Plugin::is_active('push-notifications') && settings()->push_notifications->is_enabled) require \SeeGap\Plugin::get('push-notifications')->path . 'views/partials/push_notifications_js.php' ?>

            <div class="container-fluid">
                <?= $this->views['app_menu'] ?>
            </div>

            <div class="py-4 p-lg-5 flex-grow-1">
                <?php require THEME_PATH . 'views/partials/ads_header.php' ?>

                <main class="seegap-animate seegap-animate-fill-none seegap-animate-fade-in">
                    <?= $this->views['content'] ?>
                </main>

                <?php require THEME_PATH . 'views/partials/ads_footer.php' ?>
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

    <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'custom.js', 'libraries/select2.min.js'] as $file): ?>
        <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
    <?php endforeach ?>

    <?php foreach(['libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.min.js'] as $file): ?>
        <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
    <?php endforeach ?>

    <?= \SeeGap\Event::get_content('javascript') ?>

    <script>
        let toggle_app_sidebar = () => {
            /* Open sidebar menu */
            let body = document.querySelector('body');
            body.classList.toggle('app-sidebar-opened');

            /* Toggle overlay */
            let app_overlay = document.querySelector('#app_overlay');
            app_overlay.style.display == 'none' ? app_overlay.style.display = 'block' : app_overlay.style.display = 'none';

            /* Change toggle button content */
            let button = document.querySelector('#app_menu_toggler');

            if(body.classList.contains('app-sidebar-opened')) {
                button.innerHTML = `<i class="fas fa-fw fa-times"></i>`;
            } else {
                button.innerHTML = `<i class="fas fa-fw fa-bars"></i>`;
            }
        };

        /* Toggler for the sidebar */
        document.querySelector('#app_menu_toggler').addEventListener('click', event => {
            event.preventDefault();

            toggle_app_sidebar();

            let app_sidebar_is_opened = document.querySelector('body').classList.contains('app-sidebar-opened');

            if(app_sidebar_is_opened) {
                document.querySelector('#app_overlay').removeEventListener('click', toggle_app_sidebar);
                document.querySelector('#app_overlay').addEventListener('click', toggle_app_sidebar);
            } else {
                document.querySelector('#app_overlay').removeEventListener('click', toggle_app_sidebar);
            }
        });

        /* Icon loading enhancement */
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading class to all FontAwesome icons initially
            const icons = document.querySelectorAll('i[class*="fa-"]');
            icons.forEach(icon => {
                icon.classList.add('icon-loading');
            });

            // Remove loading class once FontAwesome is fully loaded
            const checkFontAwesome = () => {
                if (window.FontAwesome && window.FontAwesome.dom && window.FontAwesome.dom.i2svg) {
                    // FontAwesome is loaded, remove loading classes
                    icons.forEach(icon => {
                        icon.classList.remove('icon-loading');
                    });
                } else {
                    // Check again in 100ms
                    setTimeout(checkFontAwesome, 100);
                }
            };
            
            // Start checking after a short delay
            setTimeout(checkFontAwesome, 50);
        });

        /* Custom select implementation */
        $('select:not([multiple="multiple"]):not([class="input-group-text"]):not([class="custom-select custom-select-sm"]):not([class^="ql"]):not([data-is-not-custom-select])').each(function() {
            let $select = $(this);
            $select.select2({
                dir: <?= json_encode(l('direction')) ?>,
                minimumResultsForSearch: 5,
            });

            /* Make sure to trigger the select when the label is clicked as well */
            let selectId = $select.attr('id');
            if(selectId) {
                $('label[for="' + selectId + '"]').on('click', function(event) {
                    event.preventDefault();
                    $select.select2('open');
                });
            }
        });
    </script>
</body>
</html>
