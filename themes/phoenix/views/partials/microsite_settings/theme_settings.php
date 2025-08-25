<?php defined('SEEGAP') || die() ?>

<!-- Theme Settings Component -->
<div class="card-body">
    <?php if(settings()->links->microsites_themes_is_enabled): ?>
        <div class="form-group mb-2">
            <label class="small mb-1"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('link.settings.microsite_theme_id') ?></label>
            <div class="position-relative">
                <?php $microsite_socials = require APP_PATH . 'includes/microsite_socials.php'; ?>

                <div class="microsite-themes-wrapper-left" style="opacity: 0"></div>
                <div class="microsite-themes-wrapper-right" style="opacity: 1"></div>

                <div id="microsites_themes" class="microsite-themes-wrapper d-flex" style="overflow-x: scroll; width: 100%;">
                    <?php foreach($data->microsites_themes as $key => $theme): ?>
                        <?php $link_style = \SeeGap\Link::get_processed_link_style($theme->settings->microsite_block) ?>

                        <label for="settings_microsite_theme_id_<?= $key ?>" class="m-0 col-6 p-2" <?= in_array($theme->microsite_theme_id, $this->user->plan_settings->microsites_themes ?? []) ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                            <input type="radio" name="microsite_theme_id" value="<?= $key ?>" id="settings_microsite_theme_id_<?= $key ?>" class="d-none" <?= $data->link->microsite_theme_id == $key ? 'checked="checked"' : null ?> />
                            <div class="link-microsite-theme card h-100 <?= in_array($theme->microsite_theme_id, $this->user->plan_settings->microsites_themes ?? []) ? null : 'container-disabled' ?>" style="<?= \SeeGap\Link::get_processed_background_style($theme->settings->microsite); ?>">
                                <div class="card-body flex-column d-flex justify-content-center align-items-center text-truncate">

                                    <div class="w-100" style="cursor: not-allowed;pointer-events: none;">

                                        <div class="text-center text-truncate mb-1">
                                            <span style="color: <?= $theme->settings->microsite_block_heading->text_color ?? '#ffffff' ?>"><?= $this->link->url ?></span>
                                        </div>

                                        <div class="text-center text-truncate small mb-2">
                                            <span style="color: <?= $theme->settings->microsite_block_paragraph->text_color ?? '#ffffff' ?>"><?= l('link.settings.microsite_theme_sample_description') ?></span>
                                        </div>

                                        <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->microsite_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                            <small><?= $theme->name ?></small>
                                        </button>

                                        <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->microsite_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                            <small><?= $theme->name ?></small>
                                        </button>

                                        <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->microsite_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                            <small><?= $theme->name ?></small>
                                        </button>

                                        <div class="d-flex flex-wrap justify-content-center mt-2">
                                            <?php foreach(array_slice($microsite_socials, 0, 3) as $key => $value): ?>
                                                <?php if($value): ?>
                                                    <div class="my-1 mx-1 <?= 'link-btn-' . ($theme->settings->microsite_block_socials->border_radius ?? 'rounded') ?>" style="background: <?= $theme->settings->microsite_block_socials->background_color ?: '#FFFFFF00' ?>; padding: .05rem .3rem;">
                                                        <a href="#">
                                                            <i class="<?= $microsite_socials[$key]['icon'] ?> fa-xs fa-fw" style="color: <?= $theme->settings->microsite_block_socials->color ?>" data-color></i>
                                                        </a>
                                                    </div>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </label><br />

                    <?php endforeach ?>

                    <label for="settings_microsite_theme_id_null" class="m-0 col-6 p-2">
                        <input type="radio" name="microsite_theme_id" value="" id="settings_microsite_theme_id_null" class="d-none" <?= !$data->link->microsite_theme_id ? 'checked="checked"' : null ?> />
                        <div class="link-microsite-theme link-microsite-theme-custom card h-100">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <?= l('link.settings.microsite_theme_id_null') ?>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
