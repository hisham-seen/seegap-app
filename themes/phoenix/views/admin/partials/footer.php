<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between align-items-center">
    <div class="text-muted small">
        <?= sprintf(l('global.footer.copyright'), date('Y'), settings()->main->title) ?>
    </div>
    
    <div class="d-flex align-items-center">
        <?php if(settings()->main->theme_style_change_is_enabled): ?>
            <button type="button" id="switch_theme_style" class="btn btn-link text-decoration-none p-2" data-toggle="tooltip" title="<?= sprintf(l('global.theme_style'), (\Altum\ThemeStyle::get() == 'light' ? l('global.theme_style_dark') : l('global.theme_style_light'))) ?>" aria-label="<?= sprintf(l('global.theme_style'), (\Altum\ThemeStyle::get() == 'light' ? l('global.theme_style_dark') : l('global.theme_style_light'))) ?>" data-title-theme-style-light="<?= sprintf(l('global.theme_style'), l('global.theme_style_light')) ?>" data-title-theme-style-dark="<?= sprintf(l('global.theme_style'), l('global.theme_style_dark')) ?>">
                <span data-theme-style="light" class="<?= \Altum\ThemeStyle::get() == 'light' ? null : 'd-none' ?>"><i class="fas fa-fw fa-sm fa-sun text-warning"></i></span>
                <span data-theme-style="dark" class="<?= \Altum\ThemeStyle::get() == 'dark' ? null : 'd-none' ?>"><i class="fas fa-fw fa-sm fa-moon"></i></span>
            </button>

            <?php include_view(THEME_PATH . 'views/partials/theme_style_js.php') ?>
        <?php endif ?>
    </div>
</div>
