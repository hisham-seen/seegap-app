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

    <?php if(\SeeGap\Meta::$link_alternate): ?>
        <link rel="alternate" href="<?= SITE_URL . \SeeGap\Router::$original_request ?>" hreflang="x-default" />
        <?php if(count(\SeeGap\Language::$active_languages) > 1): ?>
            <?php foreach(\SeeGap\Language::$active_languages as $language_name => $language_code): ?>
                <?php if(settings()->main->default_language != $language_name): ?>
                    <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \SeeGap\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>
    <?php endif ?>

    <?php if(!empty(settings()->main->favicon)): ?>
        <link href="<?= settings()->main->favicon_full_url ?>" rel="icon" />
    <?php endif ?>

    <link href="<?= ASSETS_FULL_URL . 'css/' . \SeeGap\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
    <?php foreach(['custom.css'] as $file): ?>
        <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php endforeach ?>

    <?= \SeeGap\Event::get_content('head') ?>

    <?php if(!empty(settings()->custom->head_js)): ?>
        <?= get_settings_custom_head_js() ?>
    <?php endif ?>

    <?php if(!empty(settings()->custom->head_css)): ?>
        <style><?= settings()->custom->head_css ?></style>
    <?php endif ?>
</head>

<body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?> <?= \SeeGap\ThemeStyle::get() == 'dark' ? 'cc--darkmode' : null ?>" data-theme-style="<?= \SeeGap\ThemeStyle::get() ?>">
    <?php if(!empty(settings()->custom->body_content)): ?>
        <?= settings()->custom->body_content ?>
    <?php endif ?>

    <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>

    <!-- Clean public content without any application UI -->
    <main class="seegap-animate seegap-animate-fill-none seegap-animate-fade-in">
        <?= $this->views['content'] ?>
    </main>

    <?= \SeeGap\Event::get_content('modals') ?>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

    <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'custom.js'] as $file): ?>
        <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
    <?php endforeach ?>

    <?php foreach(['libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.min.js'] as $file): ?>
        <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
    <?php endforeach ?>

    <?= \SeeGap\Event::get_content('javascript') ?>
</body>
</html>
