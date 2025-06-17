<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="countdown" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'countdown_counter_end_date' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_countdown.end_date') ?></label>
        <input
                id="<?= 'countdown_counter_end_date' . $row->microsite_block_id ?>"
                type="text"
                class="form-control"
                name="counter_end_date"
                value="<?= \SeeGap\Date::get($row->settings->counter_end_date, 1) ?>"
                autocomplete="off"
                data-daterangepicker
        />
    </div>

    <div class="form-group">
        <label for="<?= 'countdown_style_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('microsite_countdown.style') ?></label>
        <select id="<?= 'countdown_style_' . $row->microsite_block_id ?>" name="style" class="custom-select">
            <optgroup label="Digital Styles">
                <option value="digital-led" <?= ($row->settings->style ?? 'digital-led') == 'digital-led' ? 'selected="selected"' : null ?>>LED Display</option>
                <option value="digital-lcd" <?= ($row->settings->style ?? 'digital-led') == 'digital-lcd' ? 'selected="selected"' : null ?>>LCD Display</option>
                <option value="neon-style" <?= ($row->settings->style ?? 'digital-led') == 'neon-style' ? 'selected="selected"' : null ?>>Neon Style</option>
                <option value="matrix-style" <?= ($row->settings->style ?? 'digital-led') == 'matrix-style' ? 'selected="selected"' : null ?>>Matrix Style</option>
            </optgroup>
            <optgroup label="Analog/Visual Styles">
                <option value="circular-progress" <?= ($row->settings->style ?? 'digital-led') == 'circular-progress' ? 'selected="selected"' : null ?>>Circular Progress</option>
                <option value="gauge-style" <?= ($row->settings->style ?? 'digital-led') == 'gauge-style' ? 'selected="selected"' : null ?>>Gauge Style</option>
                <option value="card-flip" <?= ($row->settings->style ?? 'digital-led') == 'card-flip' ? 'selected="selected"' : null ?>>Card Flip</option>
                <option value="slide-animation" <?= ($row->settings->style ?? 'digital-led') == 'slide-animation' ? 'selected="selected"' : null ?>>Slide Animation</option>
            </optgroup>
            <optgroup label="Modern Styles">
                <option value="glassmorphism" <?= ($row->settings->style ?? 'digital-led') == 'glassmorphism' ? 'selected="selected"' : null ?>>Glassmorphism</option>
                <option value="neumorphism" <?= ($row->settings->style ?? 'digital-led') == 'neumorphism' ? 'selected="selected"' : null ?>>Neumorphism</option>
                <option value="gradient" <?= ($row->settings->style ?? 'digital-led') == 'gradient' ? 'selected="selected"' : null ?>>Gradient</option>
                <option value="minimalist" <?= ($row->settings->style ?? 'digital-led') == 'minimalist' ? 'selected="selected"' : null ?>>Minimalist</option>
            </optgroup>
        </select>
    </div>

    <div class="form-group">
        <label for="<?= 'countdown_theme_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-sun fa-sm text-muted mr-1"></i> <?= l('microsite_countdown.theme') ?></label>
        <select id="<?= 'countdown_theme_' . $row->microsite_block_id ?>" name="theme" class="custom-select">
            <option value="light" <?= $row->settings->theme == 'light' ? 'selected="selected"' : null ?>><?= l('global.theme_style_light') ?></option>
            <option value="dark" <?= $row->settings->theme == 'dark' ? 'selected="selected"' : null ?>><?= l('global.theme_style_dark') ?></option>
        </select>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
