<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="avatar" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <!-- Avatar Shape Selection -->
    <div class="form-group">
        <label><i class="fas fa-fw fa-shapes fa-sm text-muted mr-1"></i> Avatar Shape</label>
        <div class="row">
            <div class="col-6">
                <label class="shape-option">
                    <input type="radio" name="avatar_shape" value="circle" <?= ($row->settings->avatar_shape ?? 'circle') == 'circle' ? 'checked' : '' ?> class="d-none">
                    <div class="shape-preview circle-shape <?= ($row->settings->avatar_shape ?? 'circle') == 'circle' ? 'active' : '' ?>">
                        <div class="shape-demo"></div>
                        <small>Circle</small>
                    </div>
                </label>
            </div>
            <div class="col-6">
                <label class="shape-option">
                    <input type="radio" name="avatar_shape" value="square" <?= ($row->settings->avatar_shape ?? '') == 'square' ? 'checked' : '' ?> class="d-none">
                    <div class="shape-preview square-shape <?= ($row->settings->avatar_shape ?? '') == 'square' ? 'active' : '' ?>">
                        <div class="shape-demo"></div>
                        <small>Square</small>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Square Border Radius (shown only for square shape) -->
    <div class="form-group" id="square-border-radius-container" style="display: <?= ($row->settings->avatar_shape ?? 'circle') == 'square' ? 'block' : 'none' ?>;">
        <label for="<?= 'square_border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> Corner Radius</label>
        <input type="range" id="<?= 'square_border_radius_' . $row->microsite_block_id ?>" name="square_border_radius" min="0" max="50" value="<?= $row->settings->square_border_radius ?? 8 ?>" class="form-control-range">
        <small class="form-text text-muted">Adjust corner roundness for square avatars (0 = sharp corners, 50 = fully rounded)</small>
    </div>

    <!-- Template Selection -->
    <div class="form-group">
        <label><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> Avatar Template</label>
        <div class="row">
            <div class="col-6 col-md-4 mb-3">
                <label class="avatar-template-option">
                    <input type="radio" name="template" value="classic" <?= ($row->settings->template ?? 'classic') == 'classic' ? 'checked' : '' ?> class="d-none">
                    <div class="avatar-template-preview classic-template <?= ($row->settings->template ?? 'classic') == 'classic' ? 'active' : '' ?>">
                        <div class="template-avatar"></div>
                        <small class="template-name">Classic</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <label class="avatar-template-option">
                    <input type="radio" name="template" value="gradient_ring" <?= ($row->settings->template ?? '') == 'gradient_ring' ? 'checked' : '' ?> class="d-none">
                    <div class="avatar-template-preview gradient-ring-template <?= ($row->settings->template ?? '') == 'gradient_ring' ? 'active' : '' ?>">
                        <div class="template-avatar"></div>
                        <small class="template-name">Gradient Ring</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <label class="avatar-template-option">
                    <input type="radio" name="template" value="professional" <?= ($row->settings->template ?? '') == 'professional' ? 'checked' : '' ?> class="d-none">
                    <div class="avatar-template-preview professional-template <?= ($row->settings->template ?? '') == 'professional' ? 'active' : '' ?>">
                        <div class="template-avatar"></div>
                        <small class="template-name">Professional</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <label class="avatar-template-option">
                    <input type="radio" name="template" value="creative" <?= ($row->settings->template ?? '') == 'creative' ? 'checked' : '' ?> class="d-none">
                    <div class="avatar-template-preview creative-template <?= ($row->settings->template ?? '') == 'creative' ? 'active' : '' ?>">
                        <div class="template-avatar"></div>
                        <small class="template-name">Creative</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <label class="avatar-template-option">
                    <input type="radio" name="template" value="minimalist" <?= ($row->settings->template ?? '') == 'minimalist' ? 'checked' : '' ?> class="d-none">
                    <div class="avatar-template-preview minimalist-template <?= ($row->settings->template ?? '') == 'minimalist' ? 'active' : '' ?>">
                        <div class="template-avatar"></div>
                        <small class="template-name">Minimalist</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <label class="avatar-template-option">
                    <input type="radio" name="template" value="neon_glow" <?= ($row->settings->template ?? '') == 'neon_glow' ? 'checked' : '' ?> class="d-none">
                    <div class="avatar-template-preview neon-glow-template <?= ($row->settings->template ?? '') == 'neon_glow' ? 'active' : '' ?>">
                        <div class="template-avatar"></div>
                        <small class="template-name">Neon Glow</small>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Image Upload -->
    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->avatar_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->avatar_size_limit) ?>">
        <label for="<?= 'avatar_image_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
            'id'=> 'avatar_image_' . $row->microsite_block_id,
            'uploads_file_key' => 'avatars',
            'file_key' => 'image',
            'already_existing_image' => $row->settings->image,
            'image_container' => 'image',
            'accept' => \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['avatar']['whitelisted_image_extensions']),
            'input_data' => 'data-crop data-aspect-ratio="1"'
        ]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['avatar']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
    </div>

    <!-- Background Color for Transparent Images -->
    <div class="form-group" id="background-color-container" style="display: <?= (!empty($row->settings->image) && (pathinfo($row->settings->image, PATHINFO_EXTENSION) == 'png' || pathinfo($row->settings->image, PATHINFO_EXTENSION) == 'svg')) ? 'block' : 'none' ?>;">
        <label for="<?= 'background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> Background Color</label>
        <input id="<?= 'background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?? '#ffffff' ?>" />
        <div class="background_color_pickr"></div>
        <small class="form-text text-muted">Background color for transparent PNG/SVG images</small>
    </div>

    <!-- Alt Text -->
    <div class="form-group">
        <label for="<?= 'avatar_image_alt_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_alt') ?></label>
        <input id="<?= 'avatar_image_alt_' . $row->microsite_block_id ?>" type="text" class="form-control" name="image_alt" value="<?= $row->settings->image_alt ?? '' ?>" maxlength="100" />
        <small class="form-text text-muted"><?= l('microsite_link.image_alt_help') ?></small>
    </div>

    <!-- Cover Image Section -->
    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'cover_image_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'cover_image_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-image fa-sm mr-1"></i> Cover Image (Optional)
    </button>

    <div class="collapse" id="<?= 'cover_image_container_' . $row->microsite_block_id ?>">
        <!-- Cover Image Upload -->
        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->background_size_limit ?? 10 ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->background_size_limit ?? 10) ?>">
            <label for="<?= 'cover_image_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> Cover Image</label>
            <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
                'id'=> 'cover_image_' . $row->microsite_block_id,
                'uploads_file_key' => 'backgrounds',
                'file_key' => 'cover_image',
                'already_existing_image' => $row->settings->cover_image ?? '',
                'image_container' => 'cover_image',
                'accept' => \SeeGap\Uploads::array_to_list_format(['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif']),
                'input_data' => ''
            ]) ?>
            <small class="form-text text-muted">Recommended: 1200x600px or larger for best quality</small>
        </div>

        <!-- Cover Position -->
        <div class="form-group">
            <label for="<?= 'cover_position_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-crosshairs fa-sm text-muted mr-1"></i> Avatar Position</label>
            <select id="<?= 'cover_position_' . $row->microsite_block_id ?>" name="cover_position" class="custom-select">
                <option value="center" <?= ($row->settings->cover_position ?? 'center') == 'center' ? 'selected' : '' ?>>Center</option>
                <option value="top-left" <?= ($row->settings->cover_position ?? '') == 'top-left' ? 'selected' : '' ?>>Top Left</option>
                <option value="top-right" <?= ($row->settings->cover_position ?? '') == 'top-right' ? 'selected' : '' ?>>Top Right</option>
                <option value="bottom-left" <?= ($row->settings->cover_position ?? '') == 'bottom-left' ? 'selected' : '' ?>>Bottom Left</option>
                <option value="bottom-right" <?= ($row->settings->cover_position ?? '') == 'bottom-right' ? 'selected' : '' ?>>Bottom Right</option>
            </select>
            <small class="form-text text-muted">Position of avatar over the cover image</small>
        </div>

        <!-- Cover Blur -->
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'cover_blur_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> Background Blur</label>
            <input id="<?= 'cover_blur_' . $row->microsite_block_id ?>" type="range" min="0" max="10" class="form-control-range" name="cover_blur" value="<?= $row->settings->cover_blur ?? 0 ?>" />
            <small class="form-text text-muted">Blur effect for better avatar visibility</small>
        </div>

        <!-- Cover Overlay -->
        <div class="form-group">
            <label for="<?= 'cover_overlay_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> Overlay Color</label>
            <input id="<?= 'cover_overlay_color_' . $row->microsite_block_id ?>" type="hidden" name="cover_overlay_color" class="form-control" value="<?= $row->settings->cover_overlay_color ?? '#000000' ?>" />
            <div class="cover_overlay_color_pickr"></div>
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="%">
            <label for="<?= 'cover_overlay_opacity_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> Overlay Opacity</label>
            <input id="<?= 'cover_overlay_opacity_' . $row->microsite_block_id ?>" type="range" min="0" max="80" class="form-control-range" name="cover_overlay_opacity" value="<?= $row->settings->cover_overlay_opacity ?? 0 ?>" />
            <small class="form-text text-muted">Dark overlay for better text contrast</small>
        </div>
    </div>

    <!-- Mobile-Optimized Size Selection -->
    <div class="form-group">
        <label for="<?= 'avatar_size_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-mobile-alt fa-sm text-muted mr-1"></i> Size (Mobile Optimized)</label>
        <div class="row">
            <div class="col-6 col-md-3">
                <label class="size-option">
                    <input type="radio" name="size" value="80" <?= ($row->settings->size ?? 100) == 80 ? 'checked' : '' ?> class="d-none">
                    <div class="size-preview size-80 <?= ($row->settings->size ?? 100) == 80 ? 'active' : '' ?>">
                        <div class="size-circle"></div>
                        <small>Compact<br>80px</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-3">
                <label class="size-option">
                    <input type="radio" name="size" value="100" <?= ($row->settings->size ?? 100) == 100 ? 'checked' : '' ?> class="d-none">
                    <div class="size-preview size-100 <?= ($row->settings->size ?? 100) == 100 ? 'active' : '' ?>">
                        <div class="size-circle"></div>
                        <small>Standard<br>100px</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-3">
                <label class="size-option">
                    <input type="radio" name="size" value="120" <?= ($row->settings->size ?? 100) == 120 ? 'checked' : '' ?> class="d-none">
                    <div class="size-preview size-120 <?= ($row->settings->size ?? 100) == 120 ? 'active' : '' ?>">
                        <div class="size-circle"></div>
                        <small>Large<br>120px</small>
                    </div>
                </label>
            </div>
            <div class="col-6 col-md-3">
                <label class="size-option">
                    <input type="radio" name="size" value="140" <?= ($row->settings->size ?? 100) == 140 ? 'checked' : '' ?> class="d-none">
                    <div class="size-preview size-140 <?= ($row->settings->size ?? 100) == 140 ? 'active' : '' ?>">
                        <div class="size-circle"></div>
                        <small>Hero<br>140px</small>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Link URL -->
    <div class="form-group">
        <label for="<?= 'avatar_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
        <input id="<?= 'avatar_location_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="location_url" value="<?= $row->location_url ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
    </div>

    <!-- Open in New Tab -->
    <div class="form-group custom-control custom-switch">
        <input
                id="<?= 'avatar_open_in_new_tab_' . $row->microsite_block_id ?>"
                name="open_in_new_tab" type="checkbox"
                class="custom-control-input"
            <?= ($row->settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'avatar_open_in_new_tab_' . $row->microsite_block_id ?>"><?= l('microsite_link.open_in_new_tab') ?></label>
    </div>

    <!-- Verified Badge Section -->
    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'verified_badge_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'verified_badge_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-certificate fa-sm mr-1"></i> Verified Badge Settings
    </button>

    <div class="collapse" id="<?= 'verified_badge_container_' . $row->microsite_block_id ?>">
        <!-- Enable Verified Badge -->
        <div class="form-group custom-control custom-switch">
            <input
                    id="<?= 'verified_badge_enabled_' . $row->microsite_block_id ?>"
                    name="verified_badge_enabled" type="checkbox"
                    class="custom-control-input"
                <?= ($row->settings->verified_badge->enabled ?? false) ? 'checked="checked"' : null ?>
            >
            <label class="custom-control-label" for="<?= 'verified_badge_enabled_' . $row->microsite_block_id ?>">Enable Verified Badge</label>
            <small class="form-text text-muted">Show a verification badge on your avatar</small>
        </div>

        <!-- Badge Style -->
        <div class="form-group">
            <label for="<?= 'verified_badge_style_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> Badge Style</label>
            <div class="row">
                <div class="col-6 col-md-3">
                    <label class="badge-style-option">
                        <input type="radio" name="verified_badge_style" value="checkmark" <?= ($row->settings->verified_badge->style ?? 'checkmark') == 'checkmark' ? 'checked' : '' ?> class="d-none">
                        <div class="badge-style-preview checkmark-style <?= ($row->settings->verified_badge->style ?? 'checkmark') == 'checkmark' ? 'active' : '' ?>">
                            <i class="fas fa-check-circle"></i>
                            <small>Checkmark</small>
                        </div>
                    </label>
                </div>
                <div class="col-6 col-md-3">
                    <label class="badge-style-option">
                        <input type="radio" name="verified_badge_style" value="star" <?= ($row->settings->verified_badge->style ?? '') == 'star' ? 'checked' : '' ?> class="d-none">
                        <div class="badge-style-preview star-style <?= ($row->settings->verified_badge->style ?? '') == 'star' ? 'active' : '' ?>">
                            <i class="fas fa-star"></i>
                            <small>Star</small>
                        </div>
                    </label>
                </div>
                <div class="col-6 col-md-3">
                    <label class="badge-style-option">
                        <input type="radio" name="verified_badge_style" value="crown" <?= ($row->settings->verified_badge->style ?? '') == 'crown' ? 'checked' : '' ?> class="d-none">
                        <div class="badge-style-preview crown-style <?= ($row->settings->verified_badge->style ?? '') == 'crown' ? 'active' : '' ?>">
                            <i class="fas fa-crown"></i>
                            <small>Crown</small>
                        </div>
                    </label>
                </div>
                <div class="col-6 col-md-3">
                    <label class="badge-style-option">
                        <input type="radio" name="verified_badge_style" value="shield" <?= ($row->settings->verified_badge->style ?? '') == 'shield' ? 'checked' : '' ?> class="d-none">
                        <div class="badge-style-preview shield-style <?= ($row->settings->verified_badge->style ?? '') == 'shield' ? 'active' : '' ?>">
                            <i class="fas fa-shield-alt"></i>
                            <small>Shield</small>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Badge Position -->
        <div class="form-group">
            <label for="<?= 'verified_badge_position_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-crosshairs fa-sm text-muted mr-1"></i> Badge Position</label>
            <select id="<?= 'verified_badge_position_' . $row->microsite_block_id ?>" name="verified_badge_position" class="custom-select">
                <option value="bottom_right" <?= ($row->settings->verified_badge->position ?? 'bottom_right') == 'bottom_right' ? 'selected' : '' ?>>Bottom Right</option>
                <option value="top_right" <?= ($row->settings->verified_badge->position ?? '') == 'top_right' ? 'selected' : '' ?>>Top Right</option>
                <option value="bottom_left" <?= ($row->settings->verified_badge->position ?? '') == 'bottom_left' ? 'selected' : '' ?>>Bottom Left</option>
                <option value="center_bottom" <?= ($row->settings->verified_badge->position ?? '') == 'center_bottom' ? 'selected' : '' ?>>Center Bottom</option>
            </select>
        </div>

        <!-- Badge Size -->
        <div class="form-group">
            <label for="<?= 'verified_badge_size_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> Badge Size</label>
            <select id="<?= 'verified_badge_size_' . $row->microsite_block_id ?>" name="verified_badge_size" class="custom-select">
                <option value="small" <?= ($row->settings->verified_badge->size ?? 'medium') == 'small' ? 'selected' : '' ?>>Small</option>
                <option value="medium" <?= ($row->settings->verified_badge->size ?? 'medium') == 'medium' ? 'selected' : '' ?>>Medium</option>
                <option value="large" <?= ($row->settings->verified_badge->size ?? 'medium') == 'large' ? 'selected' : '' ?>>Large</option>
            </select>
        </div>

        <!-- Badge Color -->
        <div class="form-group">
            <label for="<?= 'verified_badge_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> Badge Color</label>
            <input id="<?= 'verified_badge_color_' . $row->microsite_block_id ?>" type="hidden" name="verified_badge_color" class="form-control" value="<?= $row->settings->verified_badge->color ?? '#1da1f2' ?>" required="required" />
            <div class="verified_badge_color_pickr"></div>
        </div>
    </div>

    <!-- Hover Effects Section -->
    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'hover_effects_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'hover_effects_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-magic fa-sm mr-1"></i> Hover Effects & Styling
    </button>

    <div class="collapse" id="<?= 'hover_effects_container_' . $row->microsite_block_id ?>">
        <!-- Hover Effect -->
        <div class="form-group">
            <label for="<?= 'hover_effect_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-mouse-pointer fa-sm text-muted mr-1"></i> Hover Effect</label>
            <select id="<?= 'hover_effect_' . $row->microsite_block_id ?>" name="hover_effect" class="custom-select">
                <option value="none" <?= ($row->settings->hover_effect ?? 'none') == 'none' ? 'selected' : '' ?>>None</option>
                <option value="zoom" <?= ($row->settings->hover_effect ?? '') == 'zoom' ? 'selected' : '' ?>>Zoom</option>
                <option value="glow" <?= ($row->settings->hover_effect ?? '') == 'glow' ? 'selected' : '' ?>>Glow</option>
                <option value="rotate" <?= ($row->settings->hover_effect ?? '') == 'rotate' ? 'selected' : '' ?>>Rotate</option>
                <option value="bounce" <?= ($row->settings->hover_effect ?? '') == 'bounce' ? 'selected' : '' ?>>Bounce</option>
            </select>
            <small class="form-text text-muted">Note: Hover effects work best on desktop devices</small>
        </div>

        <!-- Border Radius -->
        <div class="form-group">
            <label for="<?= 'avatar_border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_avatar.border_radius') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius ?? 'rounded') == 'straight' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius ?? 'rounded') == 'straight' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> Straight
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius ?? 'rounded') == 'round' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius ?? 'rounded') == 'round' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-circle fa-sm mr-1"></i> Round
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius ?? 'rounded') == 'rounded' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius ?? 'rounded') == 'rounded' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square fa-sm mr-1"></i> Rounded
                    </label>
                </div>
            </div>
        </div>
    </div>

    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<style>
/* Template Styles */
.avatar-template-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.avatar-template-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.avatar-template-preview:hover,
.avatar-template-preview.active,
.avatar-template-option input:checked + .avatar-template-preview {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.template-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin: 0 auto 5px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-ring-template .template-avatar {
    border: 2px solid transparent;
    background: linear-gradient(white, white) padding-box, linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1) border-box;
}

.professional-template .template-avatar {
    background: #6c757d;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.creative-template .template-avatar {
    background: linear-gradient(45deg, #ff9a9e, #fecfef);
    border: 1px solid #fff;
    box-shadow: 0 0 0 1px #ff9a9e;
}

.minimalist-template .template-avatar {
    background: #ffffff;
    border: 1px solid #dee2e6;
}

.neon-glow-template .template-avatar {
    background: #667eea;
    box-shadow: 0 0 15px rgba(102, 126, 234, 0.6);
}

/* Shape Selection Styles */
.shape-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.shape-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.shape-preview:hover,
.shape-preview.active,
.shape-option input:checked + .shape-preview {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.shape-demo {
    width: 30px;
    height: 30px;
    margin: 0 auto 5px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.circle-shape .shape-demo {
    border-radius: 50%;
}

.square-shape .shape-demo {
    border-radius: 6px;
}

.shape-preview small {
    font-weight: 500;
    color: #495057;
}

/* Size Selection Styles */
.size-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.size-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 10px 5px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.size-preview:hover,
.size-preview.active,
.size-option input:checked + .size-preview {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.size-circle {
    border-radius: 50%;
    margin: 0 auto 5px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.size-80 .size-circle { width: 16px; height: 16px; }
.size-100 .size-circle { width: 20px; height: 20px; }
.size-120 .size-circle { width: 24px; height: 24px; }
.size-140 .size-circle { width: 28px; height: 28px; }

/* Badge Style Selection */
.badge-style-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.badge-style-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px 10px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.badge-style-preview:hover,
.badge-style-preview.active,
.badge-style-option input:checked + .badge-style-preview {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.badge-style-preview i {
    font-size: 1.5rem;
    margin-bottom: 5px;
    color: #007bff;
}

.badge-style-preview small {
    font-weight: 500;
    color: #495057;
}

/* Mobile Optimizations */
@media (max-width: 576px) {
    .avatar-template-preview,
    .size-preview,
    .badge-style-preview {
        padding: 8px 5px;
    }
    
    .template-avatar {
        width: 25px;
        height: 25px;
    }
    
    .badge-style-preview i {
        font-size: 1.2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Template selection handling
    const templateOptions = document.querySelectorAll('input[name="template"]');
    const templatePreviews = document.querySelectorAll('.avatar-template-preview');
    
    // Add click handlers to template previews
    templatePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="template"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all template previews
                templatePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected template
                this.classList.add('active');
            }
        });
    });
    
    // Also handle radio button changes directly
    templateOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all template previews
            templatePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected template
            const selectedPreview = this.parentElement.querySelector('.avatar-template-preview');
            selectedPreview.classList.add('active');
        });
    });
    
    // Size selection handling
    const sizeOptions = document.querySelectorAll('input[name="size"]');
    const sizePreviews = document.querySelectorAll('.size-preview');
    
    // Add click handlers to size previews
    sizePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="size"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all size previews
                sizePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected size
                this.classList.add('active');
            }
        });
    });
    
    // Also handle radio button changes directly
    sizeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all size previews
            sizePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected size
            const selectedPreview = this.parentElement.querySelector('.size-preview');
            selectedPreview.classList.add('active');
        });
    });
    
    // Badge style selection handling
    const badgeStyleOptions = document.querySelectorAll('input[name="verified_badge_style"]');
    const badgeStylePreviews = document.querySelectorAll('.badge-style-preview');
    
    // Add click handlers to badge style previews
    badgeStylePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="verified_badge_style"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all badge style previews
                badgeStylePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected badge style
                this.classList.add('active');
            }
        });
    });
    
    // Also handle radio button changes directly
    badgeStyleOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all badge style previews
            badgeStylePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected badge style
            const selectedPreview = this.parentElement.querySelector('.badge-style-preview');
            selectedPreview.classList.add('active');
        });
    });

    // Initialize color pickers
    if (typeof Pickr !== 'undefined') {
        // Verified badge color picker
        const badgeColorPicker = Pickr.create({
            el: '.verified_badge_color_pickr',
            theme: 'classic',
            default: '<?= $row->settings->verified_badge->color ?? '#1da1f2' ?>',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    save: true
                }
            }
        });

        badgeColorPicker.on('save', (color) => {
            document.getElementById('<?= 'verified_badge_color_' . $row->microsite_block_id ?>').value = color.toHEXA().toString();
        });

        // Background color picker
        const backgroundColorPicker = Pickr.create({
            el: '.background_color_pickr',
            theme: 'classic',
            default: '<?= $row->settings->background_color ?? '#ffffff' ?>',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    save: true
                }
            }
        });

        backgroundColorPicker.on('save', (color) => {
            document.getElementById('<?= 'background_color_' . $row->microsite_block_id ?>').value = color.toHEXA().toString();
        });

        // Cover overlay color picker
        const coverOverlayColorPicker = Pickr.create({
            el: '.cover_overlay_color_pickr',
            theme: 'classic',
            default: '<?= $row->settings->cover_overlay_color ?? '#000000' ?>',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    save: true
                }
            }
        });

        coverOverlayColorPicker.on('save', (color) => {
            document.getElementById('<?= 'cover_overlay_color_' . $row->microsite_block_id ?>').value = color.toHEXA().toString();
        });
    }

    // Shape selection handling
    const shapeOptions = document.querySelectorAll('input[name="avatar_shape"]');
    const shapePreviews = document.querySelectorAll('.shape-preview');
    const squareBorderRadiusContainer = document.getElementById('square-border-radius-container');
    
    // Add click handlers to shape previews
    shapePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="avatar_shape"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all shape previews
                shapePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected shape
                this.classList.add('active');
                
                // Show/hide square border radius controls
                if (radioInput.value === 'square') {
                    squareBorderRadiusContainer.style.display = 'block';
                } else {
                    squareBorderRadiusContainer.style.display = 'none';
                }
            }
        });
    });
    
    // Also handle radio button changes directly
    shapeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all shape previews
            shapePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected shape
            const selectedPreview = this.parentElement.querySelector('.shape-preview');
            selectedPreview.classList.add('active');
            
            // Show/hide square border radius controls
            if (this.value === 'square') {
                squareBorderRadiusContainer.style.display = 'block';
            } else {
                squareBorderRadiusContainer.style.display = 'none';
            }
        });
    });

    // File upload handling for background color detection
    const fileInput = document.getElementById('<?= 'avatar_image_' . $row->microsite_block_id ?>');
    const backgroundColorContainer = document.getElementById('background-color-container');
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const fileName = file.name.toLowerCase();
                const fileExtension = fileName.split('.').pop();
                
                // Show background color picker for transparent image formats
                if (fileExtension === 'png' || fileExtension === 'svg') {
                    backgroundColorContainer.style.display = 'block';
                } else {
                    backgroundColorContainer.style.display = 'none';
                }
            } else {
                // Hide background color picker when no file is selected
                backgroundColorContainer.style.display = 'none';
            }
        });
    }
});
</script>
