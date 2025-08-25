<?php defined('SEEGAP') || die() ?>

<!-- Background Settings Component -->
<div class="form-group mb-3">
    <label for="settings_background_type" class="small mb-1"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('link.settings.background_type') ?></label>
    <select id="settings_background_type" name="background_type" class="custom-select custom-select-sm">
        <?php foreach($microsite_backgrounds as $key => $value): ?>
            <option value="<?= $key ?>" <?= $data->link->settings->background_type == $key ? 'selected="selected"' : null?>><?= l('link.settings.background_type_' . $key) ?></option>
        <?php endforeach ?>
    </select>
</div>

<div id="background_type_preset" class="row mb-3" style="margin-right: -3px; margin-left: -3px;">
    <?php foreach($microsite_backgrounds['preset'] as $key => $value): ?>
        <label for="settings_background_type_preset_<?= $key ?>" class="m-0 col-2 p-1">
            <input type="radio" name="background" value="<?= $key ?>" id="settings_background_type_preset_<?= $key ?>" class="d-none" <?= $data->link->settings->background_type == 'preset' && $data->link->settings->background == $key ? 'checked="checked"' : null ?>/>
            <div class="link-background-type-preset" style="<?= $value ?>; height: 40px;"></div>
        </label>
    <?php endforeach ?>
</div>

<div id="background_type_preset_abstract" class="row mb-3" style="margin-right: -3px; margin-left: -3px;">
    <?php foreach($microsite_backgrounds['preset_abstract'] as $key => $value): ?>
        <label for="settings_background_type_preset_abstract_<?= $key ?>" class="m-0 col-2 p-1">
            <input type="radio" name="background" value="<?= $key ?>" id="settings_background_type_preset_abstract_<?= $key ?>" class="d-none" <?= $data->link->settings->background_type == 'preset_abstract' && $data->link->settings->background == $key ? 'checked="checked"' : null ?>/>
            <div class="link-background-type-preset" style="<?= $value ?>; height: 40px;"></div>
        </label>
    <?php endforeach ?>
</div>

<div id="background_type_gradient">
    <div class="form-group mb-2">
        <label for="settings_background_type_gradient_color_one" class="small mb-1"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('link.settings.background_type_gradient_color_one') ?></label>
        <input type="hidden" id="settings_background_type_gradient_color_one" name="background_color_one" class="form-control form-control-sm" value="<?= $data->link->settings->background_color_one ?? '#000000' ?>" />
        <div id="settings_background_type_gradient_color_one_pickr"></div>
    </div>

    <div class="form-group mb-2">
        <label for="settings_background_type_gradient_color_two" class="small mb-1"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('link.settings.background_type_gradient_color_two') ?></label>
        <input type="hidden" id="settings_background_type_gradient_color_two" name="background_color_two" class="form-control form-control-sm" value="<?= $data->link->settings->background_color_two ?? '#000000' ?>" />
        <div id="settings_background_type_gradient_color_two_pickr"></div>
    </div>
</div>

<div id="background_type_color">
    <div class="form-group mb-2">
        <label for="settings_background_type_color" class="small mb-1"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('link.settings.background_type_color') ?></label>
        <input type="hidden" id="settings_background_type_color" name="background" class="form-control form-control-sm" value="<?= is_string($data->link->settings->background) ? $data->link->settings->background : '#000000' ?>" />
        <div id="settings_background_type_color_pickr"></div>
    </div>
</div>

<div id="background_type_image" data-image-container="background">
    <div class="form-group mb-2">
        <label class="small mb-1"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.background_type_image') ?></label>
        <div class="row">
            <div class="col">
                <input id="background_type_image_input" type="file" name="background" accept="<?= \SeeGap\Uploads::get_whitelisted_file_extensions_accept('microsite_background') ?>" class="form-control-file seegap-file-input" />
            </div>

            <?php if($data->link->settings->background_type == 'image' && is_string($data->link->settings->background) && !string_ends_with('.mp4', $data->link->settings->background)): ?>
                <div class="col-3 d-flex justify-content-center align-items-center">
                    <a href="<?= \SeeGap\Uploads::get_full_url('backgrounds') . $data->link->settings->background ?>" target="_blank" data-toggle="tooltip" title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                        <img id="background_type_image_preview" src="<?= \SeeGap\Uploads::get_full_url('backgrounds') . $data->link->settings->background ?>" data-default-src="<?= \SeeGap\Uploads::get_full_url('backgrounds') . $data->link->settings->background ?>" class="seegap-file-input-preview rounded" loading="lazy" />
                    </a>
                </div>
            <?php endif ?>
        </div>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('microsite_background')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->background_size_limit) ?></small>
    </div>
</div>

<div class="form-group mb-2">
    <label for="background_attachment" class="small mb-1"><i class="fas fa-fw fa-print fa-sm text-muted mr-1"></i> <?= l('link.settings.background_attachment') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <?php foreach(['scroll', 'fixed'] as $background_attachment): ?>
            <div class="col-6">
                <label class="btn btn-light btn-block text-truncate <?= $data->link->settings->background_attachment == $background_attachment ? 'active"' : null?>">
                    <input type="radio" name="background_attachment" value="<?= $background_attachment ?>" class="custom-control-input" <?= ($data->link->settings->background_attachment ?? null) == $background_attachment ? 'checked="checked"' : null?> />
                    <?= l('link.settings.background_attachment.' . $background_attachment) ?>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>

<div class="form-group mb-2" data-range-counter data-range-counter-suffix="px">
    <label for="background_blur" class="small mb-1"><i class="fas fa-fw fa-low-vision fa-sm text-muted mr-1"></i> <?= l('link.settings.background_blur') ?></label>
    <input id="background_blur" type="range"  min="0" max="30" class="form-control-range" name="background_blur" value="<?= $data->link->settings->background_blur ?? 0 ?>" />
</div>

<div class="form-group mb-2" data-range-counter data-range-counter-suffix="%">
    <label for="background_brightness" class="small mb-1"><i class="fas fa-fw fa-sun fa-sm text-muted mr-1"></i> <?= l('link.settings.background_brightness') ?></label>
    <input id="background_brightness" type="range"  min="0" max="150" class="form-control-range" name="background_brightness" value="<?= $data->link->settings->background_brightness ?? 100 ?>" />
</div>

<div class="form-group mb-2" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->favicon_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->favicon_size_limit) ?>">
    <label for="favicon" class="small mb-1"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.favicon') ?></label>
    <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', ['uploads_file_key' => 'favicons', 'file_key' => 'favicon', 'already_existing_image' => $data->link->settings->favicon, 'image_container' => 'favicon', 'input_data' => 'data-crop data-aspect-ratio="1"']) ?>
    <?= \SeeGap\Alerts::output_field_error('favicon') ?>
    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('favicons')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->favicon_size_limit) ?></small>
</div>
