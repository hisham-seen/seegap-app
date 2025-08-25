<?php defined('SEEGAP') || die() ?>

<?php ob_start() ?>

<div class="row link-settings">
    <!-- Left Column - Blocks -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-fw fa-th-large fa-sm text-muted mr-1"></i> 
                        <span><?= l('link.header.blocks_tab') ?></span>
                    </h6>
                    <div class="d-flex">
                        <form id="update_microsite_canvas_form" name="update_microsite_canvas" action="" method="post" role="form" enctype="multipart/form-data" class="mr-1">
                            <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                            <input type="hidden" name="request_type" value="update" />
                            <input type="hidden" name="type" value="microsite" />
                            <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                            <button type="submit" name="submit" class="btn btn-xs btn-outline-success" data-is-ajax disabled id="canvas-save-btn">
                                <i class="fas fa-fw fa-layer-group fa-sm"></i> <span class="canvas-save-text">Layout Saved</span>
                            </button>
                        </form>
                        <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" class="btn btn-xs btn-primary">
                            <i class="fas fa-fw fa-plus fa-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Blocks Content -->
                <div id="microsite_blocks" class="mt-3">
                    <?php if($data->link_links_result->num_rows): ?>
                        <?php while($row = $data->link_links_result->fetch_object()): ?>
                            <?php if(!isset($data->microsite_blocks[$row->type])) continue; ?>

                            <?php $row->settings = (object) json_decode($row->settings) ?>
                            <?php
                            $row->settings->border_shadow_offset_x = $row->settings->border_shadow_offset_x ?? '0';
                            $row->settings->border_shadow_offset_y = $row->settings->border_shadow_offset_y ?? '0';
                            $row->settings->border_shadow_blur = $row->settings->border_shadow_blur ?? '20';
                            $row->settings->border_shadow_spread = $row->settings->border_shadow_spread ?? '0';
                            $row->settings->border_shadow_color = $row->settings->border_shadow_color ?? '#00000010';
                            ?>

                            <div class="microsite_block card shadow-sm <?= $row->is_enabled ? null : 'custom-row-inactive' ?> mb-2" data-microsite-block-id="<?= $row->microsite_block_id ?>">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-1">
                                            <span data-toggle="tooltip" title="<?= l('link.microsite_blocks.link_sort') ?>">
                                                <i class="fas fa-fw fa-bars fa-xs text-muted drag"></i>
                                            </span>
                                        </div>

                                        <div class="mr-2 d-none d-lg-block">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px; background-color: <?= $data->microsite_blocks[$row->type]['color'] ?>;">
                                                <i class="<?= $data->microsite_blocks[$row->type]['icon'] ?> fa-fw fa-xs text-white"></i>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="d-flex flex-column">
                                                <div class="text-truncate">
                                                    <a href="#"
                                                       data-toggle="collapse"
                                                       data-target="#microsite_block_expanded_content_<?= $row->microsite_block_id ?>"
                                                       aria-expanded="false"
                                                       aria-controls="microsite_block_expanded_content_<?= $row->microsite_block_id ?>"
                                                       class="text-truncate small font-weight-bold"
                                                    >
                                                        <?= $data->microsite_blocks[$row->type]['display_dynamic_name'] ? ($row->settings->{$data->microsite_blocks[$row->type]['display_dynamic_name']} ? string_truncate($row->settings->{$data->microsite_blocks[$row->type]['display_dynamic_name']}, 20) : l('link.microsite.blocks.' . $row->type)) : l('link.microsite.blocks.' . $row->type) ?>
                                                    </a>
                                                </div>

                                                <div class="d-flex align-items-center text-truncate">
                                                <?php if(!empty($row->location_url)): ?>
                                                    <?php if($parsed_host = parse_url($row->location_url, PHP_URL_HOST)): ?>
                                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($parsed_host) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" style="width: 12px; height: 12px;" />
                                                    <?php endif ?>

                                                    <span class="d-inline-block text-truncate">
                                                        <a href="<?= $row->location_url ?>" class="text-muted small" style="font-size: 10px;" title="<?= $row->location_url ?>" target="_blank" rel="noreferrer"><?= $row->location_url ?></a>
                                                    </span>
                                                <?php elseif(!empty($row->url)): ?>
                                                    <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url(url($row->url))['host']) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" style="width: 12px; height: 12px;" />

                                                    <span class="d-inline-block text-truncate">
                                                        <a href="<?= url($row->url) ?>" class="text-muted small" style="font-size: 10px;" title="<?= url($row->url) ?>" target="_blank" rel="noreferrer"><?= url($row->url) ?></a>
                                                    </span>
                                                <?php endif ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml-2 d-flex align-items-center">
                                            <div class="custom-control custom-switch mr-2" data-toggle="tooltip" title="<?= l('link.microsite_blocks.is_enabled_tooltip') ?>">
                                                <input
                                                        type="checkbox"
                                                        class="custom-control-input"
                                                        id="microsite_block_is_enabled_<?= $row->microsite_block_id ?>"
                                                        data-row-id="<?= $row->microsite_block_id ?>"
                                                    <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                                >
                                                <label class="custom-control-label" for="microsite_block_is_enabled_<?= $row->microsite_block_id ?>"></label>
                                            </div>

                                            <div class="dropdown">
                                                <button type="button" class="btn btn-link text-secondary p-0 dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                                    <i class="fas fa-fw fa-ellipsis-v fa-xs"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#"
                                                       class="dropdown-item"
                                                       data-toggle="collapse"
                                                       data-target="#microsite_block_expanded_content_<?= $row->microsite_block_id ?>"
                                                       aria-expanded="false"
                                                       aria-controls="microsite_block_expanded_content_<?= $row->microsite_block_id ?>"
                                                    >
                                                        <i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?>
                                                    </a>

                                                    <?php if($data->microsite_blocks[$row->type]['has_statistics']): ?>
                                                        <a href="<?= url('microsite-block/' . $row->microsite_block_id . '/statistics') ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('link.statistics.link') ?></a>
                                                    <?php endif ?>

                                                    <?php if($data->microsite_blocks[$row->type]['type'] == 'payment'): ?>
                                                        <a href="<?= url('guests-payments?microsite_block_id=' . $row->microsite_block_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('guests_payments.link') ?></a>
                                                        <a href="<?= url('guests-payments-statistics?microsite_block_id=' . $row->microsite_block_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-chart-pie mr-2"></i> <?= l('guests_payments_statistics.link') ?></a>
                                                    <?php endif ?>

                                                    <?php if(in_array($row->type, ['email_collector', 'phone_collector', 'contact_collector', 'feedback_collector'])): ?>
                                                        <a href="<?= url('data?microsite_block_id=' . $row->microsite_block_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-database mr-2"></i> <?= l('data.link') ?></a>
                                                    <?php endif ?>

                                                    <a href="<?= $data->link->full_url . '#microsite_block_id_' . $row->microsite_block_id ?>" target="_blank" class="dropdown-item" data-microsite-block-id="<?= $row->microsite_block_id ?>"><i class="fas fa-fw fa-sm fa-external-link-alt mr-2"></i> <?= l('global.view') ?></a>

                                                    <a href="#" data-toggle="modal" data-target="#microsite_block_duplicate_modal" class="dropdown-item" data-microsite-block-id="<?= $row->microsite_block_id ?>"><i class="fas fa-fw fa-sm fa-clone mr-2"></i> <?= l('global.duplicate') ?></a>

                                                    <a href="#" data-toggle="modal" data-target="#microsite_block_delete_modal" class="dropdown-item" data-microsite-block-id="<?= $row->microsite_block_id ?>"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="collapse mt-3" id="microsite_block_expanded_content_<?= $row->microsite_block_id ?>" data-link-type="<?= $row->type ?>" data-parent="#microsite_blocks">
                                        <?php require THEME_PATH . 'views/link/settings/microsite_blocks/' . $row->type . '/' . $row->type . '_update_form.php' ?>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile ?>
                    <?php else: ?>

                        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                            'filters_get' => $data->filters->get ?? [],
                            'name' => 'link.microsite_blocks',
                            'has_secondary_text' => true,
                        ]); ?>

                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Column - Canvas/Preview -->
    <div class="col-12 col-lg-4">
        <div class="d-flex justify-content-center mb-3">
            <div class="microsite-preview">
                <div class="microsite-preview-iframe-container position-relative">
                    <iframe id="microsite_preview_iframe" class="microsite-preview-iframe" src="<?= SITE_URL . $data->link->url . '?preview=' . md5($data->link->user_id) ?>"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Settings -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-fw fa-wrench fa-sm text-muted mr-1"></i> 
                        <span><?= l('link.header.settings_tab') ?></span>
                    </h6>
                </div>

                <!-- Settings Navigation Tabs -->
                <div class="microsite-block-tabs">
                    <div class="nav nav-pills nav-fill nav-minimal mb-4" id="settings-tabs" role="tablist">
                        <a class="nav-item nav-link active" id="settings-general-tab" data-toggle="pill" href="#settings-general" role="tab" aria-controls="settings-general" aria-selected="true" data-toggle="tooltip" title="General Settings">
                            <i class="fas fa-cog"></i>
                        </a>
                        <a class="nav-item nav-link" id="settings-theme-tab" data-toggle="pill" href="#settings-theme" role="tab" aria-controls="settings-theme" aria-selected="false" data-toggle="tooltip" title="Theme Settings">
                            <i class="fas fa-palette"></i>
                        </a>
                        <a class="nav-item nav-link" id="settings-customization-tab" data-toggle="pill" href="#settings-customization" role="tab" aria-controls="settings-customization" aria-selected="false" data-toggle="tooltip" title="Design Settings">
                            <i class="fas fa-paint-brush"></i>
                        </a>
                        <a class="nav-item nav-link" id="settings-features-tab" data-toggle="pill" href="#settings-features" role="tab" aria-controls="settings-features" aria-selected="false" data-toggle="tooltip" title="Features Settings">
                            <i class="fas fa-star"></i>
                        </a>
                        <a class="nav-item nav-link" id="settings-advanced-tab" data-toggle="pill" href="#settings-advanced" role="tab" aria-controls="settings-advanced" aria-selected="false" data-toggle="tooltip" title="Advanced Settings">
                            <i class="fas fa-code"></i>
                        </a>
                    </div>
                </div>

                <form id="update_microsite" name="update_microsite" action="" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                    <input type="hidden" name="request_type" value="update" />
                    <input type="hidden" name="type" value="microsite" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />

                    <div class="notification-container"></div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="settings-tabContent">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="settings-general" role="tabpanel" aria-labelledby="settings-general-tab">
                            <?php include THEME_PATH . 'views/partials/microsite_settings/general_settings.php'; ?>
                        </div>

                        <!-- Theme Tab -->
                        <div class="tab-pane fade" id="settings-theme" role="tabpanel" aria-labelledby="settings-theme-tab">
                            <?php include THEME_PATH . 'views/partials/microsite_settings/theme_settings.php'; ?>
                        </div>

                        <!-- Customization Tab -->
                        <div class="tab-pane fade" id="settings-customization" role="tabpanel" aria-labelledby="settings-customization-tab">
                            <?php include THEME_PATH . 'views/partials/microsite_settings/customization_settings.php'; ?>
                        </div>

                        <!-- Features Tab -->
                        <div class="tab-pane fade" id="settings-features" role="tabpanel" aria-labelledby="settings-features-tab">
                            <?php include THEME_PATH . 'views/partials/microsite_settings/features_settings.php'; ?>
                        </div>

                        <!-- Advanced Tab -->
                        <div class="tab-pane fade" id="settings-advanced" role="tabpanel" aria-labelledby="settings-advanced-tab">
                            <?php include THEME_PATH . 'views/partials/microsite_settings/security_seo_settings.php'; ?>
                        </div>
                    </div>

                    <!-- Unified Update Button -->
                    <div class="mt-4">
                        <button type="submit" name="submit" id="unified-update-btn" class="btn btn-block btn-primary" data-is-ajax>
                            <i class="fas fa-fw fa-save fa-sm mr-2"></i>
                            <span class="unified-update-text"><?= l('global.update') ?></span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

</div>
</div>

<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/pickr.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/fontawesome-iconpicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    /* Type handler function for form elements - declared first to prevent reference errors */
    if (typeof window.type_handler === 'undefined') {
        window.type_handler = (selector, attribute, operator = '=') => {
            let element = document.querySelector(selector);
            if(!element) return;
            
            let value = element.value;
            let target_selector = `[${attribute}${operator}"${value}"]`;
            
            // Hide all elements first
            document.querySelectorAll(`[${attribute}]`).forEach(el => {
                el.style.display = 'none';
            });
            
            // Show matching elements
            document.querySelectorAll(target_selector).forEach(el => {
                el.style.display = 'block';
            });
        };
    }

    /* Settings Tab */
    const container = document.querySelector('.microsite-themes-wrapper');
    if(container) {
        const fade_left = document.querySelector('.microsite-themes-wrapper-left');
        const fade_right = document.querySelector('.microsite-themes-wrapper-right');

        const update_fades = () => {
            fade_left.style.opacity = container.scrollLeft ? 1 : 0;
            fade_right.style.opacity = (container.scrollLeft + container.clientWidth + 1 >= container.scrollWidth) ? 0 : 1;
        };

        container.addEventListener('scroll', update_fades);
        window.addEventListener('resize', update_fades);
    }

    /* Initiate the color picker */
    let pickr_options = {
        comparison: false,

        components: {
            preview: true,
            opacity: true,
            hue: true,
            comparison: false,
            interaction: {
                hex: true,
                rgba: false,
                hsla: false,
                hsva: false,
                cmyk: false,
                input: true,
                clear: false,
                save: false
            }
        }
    };

    /* UTM */
    let process_utm = () => {

        let utm_source = document.querySelector('input[name="utm_source"]').value;
        let utm_medium = document.querySelector('input[name="utm_medium"]').value;
        let utm_campaign = 'UTM_CAMPAIGN';
        let utm_preview = <?= json_encode(l('global.none')) ?>;

        if(utm_source || utm_medium) {
            let link = new URL(<?= json_encode(SITE_URL) ?>);

            if(utm_source) link.searchParams.set('utm_source', utm_source.trim());
            if(utm_medium) link.searchParams.set('utm_medium', utm_medium.trim());
            if(utm_campaign) link.searchParams.set('utm_campaign', utm_campaign.trim());

            utm_preview = '?' + link.searchParams.toString();
        }

        document.querySelector('input[name="utm_preview"]').value = utm_preview;
    }

    document.querySelectorAll('input[name="utm_source"], input[name="utm_medium"], input[name="utm_campaign"]').forEach(element => {
        ['change', 'paste', 'keyup'].forEach(event_type => {
            element.addEventListener(event_type, process_utm);
        });
    })

    process_utm();

    /* Global refresh coordination to prevent double refreshes */
    window.previewRefreshState = {
        isRefreshing: false,
        pendingRefresh: false,
        lastRefreshTime: 0
    };

    /* Enhanced microsite preview refresh function with proper loading handling */
    window.refresh_microsite_preview = window.refresh_microsite_preview || (() => {
        let microsite_preview_iframe = document.querySelector('#microsite_preview_iframe');
        if(!microsite_preview_iframe) return;

        const now = Date.now();
        const minRefreshInterval = 1000; // Minimum 1 second between refreshes

        // Prevent multiple simultaneous refreshes
        if(window.previewRefreshState.isRefreshing) {
            window.previewRefreshState.pendingRefresh = true;
            return;
        }

        // Prevent too frequent refreshes
        if(now - window.previewRefreshState.lastRefreshTime < minRefreshInterval) {
            if(!window.previewRefreshState.pendingRefresh) {
                window.previewRefreshState.pendingRefresh = true;
                setTimeout(() => {
                    if(window.previewRefreshState.pendingRefresh) {
                        window.previewRefreshState.pendingRefresh = false;
                        window.refresh_microsite_preview();
                    }
                }, minRefreshInterval - (now - window.previewRefreshState.lastRefreshTime));
            }
            return;
        }
        
        window.previewRefreshState.isRefreshing = true;
        window.previewRefreshState.lastRefreshTime = now;
        
        // Add loading overlay to prevent flash
        let container = microsite_preview_iframe.closest('.microsite-preview-iframe-container');
        if(container && !container.querySelector('.preview-loading-overlay')) {
            let overlay = document.createElement('div');
            overlay.className = 'preview-loading-overlay';
            overlay.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
                border-radius: inherit;
            `;
            overlay.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
            container.appendChild(overlay);
        }

        // Create a promise that resolves when iframe loads
        const loadPromise = new Promise((resolve, reject) => {
            const timeout = setTimeout(() => {
                reject(new Error('Iframe load timeout'));
            }, 10000); // 10 second timeout

            const onLoad = () => {
                clearTimeout(timeout);
                microsite_preview_iframe.removeEventListener('load', onLoad);
                microsite_preview_iframe.removeEventListener('error', onError);
                resolve();
            };

            const onError = () => {
                clearTimeout(timeout);
                microsite_preview_iframe.removeEventListener('load', onLoad);
                microsite_preview_iframe.removeEventListener('error', onError);
                reject(new Error('Iframe load error'));
            };

            microsite_preview_iframe.addEventListener('load', onLoad);
            microsite_preview_iframe.addEventListener('error', onError);
        });

        // Update iframe src
        let current_src = microsite_preview_iframe.getAttribute('src');
        let url = new URL(current_src);
        url.searchParams.set('_refresh', Date.now());
        microsite_preview_iframe.setAttribute('src', url.toString());

        // Handle load completion
        loadPromise
            .then(() => {
                // Remove loading overlay
                let overlay = container?.querySelector('.preview-loading-overlay');
                if(overlay) {
                    overlay.remove();
                }
                
                // Dispatch refreshed event
                setTimeout(() => {
                    microsite_preview_iframe.dispatchEvent(new Event('refreshed'));
                }, 100); // Small delay to ensure DOM is ready
            })
            .catch((error) => {
                console.warn('Preview refresh failed:', error);
                // Remove loading overlay even on error
                let overlay = container?.querySelector('.preview-loading-overlay');
                if(overlay) {
                    overlay.remove();
                }
            })
            .finally(() => {
                window.previewRefreshState.isRefreshing = false;
                
                // Handle any pending refresh request
                if(window.previewRefreshState.pendingRefresh) {
                    window.previewRefreshState.pendingRefresh = false;
                    setTimeout(() => {
                        window.refresh_microsite_preview();
                    }, 500); // Small delay before processing pending refresh
                }
            });
    });

    /* Switching themes & previewing */
    let microsite_theme_preview = () => {
        let microsite_theme_id = document.querySelector('input[name="microsite_theme_id"]:checked').value;

        /* Refresh iframe */
        let microsite_preview_iframe = document.querySelector('#microsite_preview_iframe');

        setTimeout(() => {
            let microsite_preview_iframe_url = new URL(microsite_preview_iframe.getAttribute('src'));
            microsite_preview_iframe_url.searchParams.set('microsite_theme_id', microsite_theme_id);
            microsite_preview_iframe_url.search = microsite_preview_iframe_url.searchParams.toString()
            microsite_preview_iframe.setAttribute('src', microsite_preview_iframe_url.toString());
            
            // Dispatch refreshed event after iframe loads
            setTimeout(() => {
                document.querySelector('#microsite_preview_iframe').dispatchEvent(new Event('refreshed'));
            }, 500);
        }, 750)
    }

    document.querySelectorAll('input[name="microsite_theme_id"]').forEach(element => {
        element.addEventListener('change', microsite_theme_preview);
    })

    /* Function to switch theme to custom */
    let set_microsite_theme_id_null = () => {
        if(document.querySelector('input[name="microsite_theme_id"][value=""]')) {
            if(!document.querySelector('input[name="microsite_theme_id"][value=""]').checked) {
                document.querySelector('input[name="microsite_theme_id"][value=""]').checked = true;
                microsite_theme_preview();
            }
        }
    }

    /* Display verified */
    let display_verified = () => {
        let verified_location = document.querySelector('input[name="verified_location"]:checked').value;
        let microsite_preview_iframe = $('#microsite_preview_iframe');

        switch(verified_location) {
            case 'top':
                microsite_preview_iframe.contents().find(`#link-verified-wrapper-top`).show();
                microsite_preview_iframe.contents().find(`#link-verified-wrapper-bottom`).hide();
                break;

            case 'bottom':
                microsite_preview_iframe.contents().find(`#link-verified-wrapper-top`).hide();
                microsite_preview_iframe.contents().find(`#link-verified-wrapper-bottom`).show();
                break;

            case '':
                microsite_preview_iframe.contents().find(`#link-verified-wrapper-top`).hide();
                microsite_preview_iframe.contents().find(`#link-verified-wrapper-bottom`).hide();
                break;
        }
    }

    document.querySelector('input[name="verified_location"]') && document.querySelectorAll('input[name="verified_location"]').forEach(element => element.addEventListener('change', display_verified));

    /* Text Color Handler */
    let settings_text_color_pickr = Pickr.create({
        el: '#settings_text_color_pickr',
        default: $('#settings_text_color').val(),
        ...pickr_options
    });

    settings_text_color_pickr.on('change', hsva => {
        set_microsite_theme_id_null();

        $('#settings_text_color').val(hsva.toHEXA().toString());
        $('#microsite_preview_iframe').contents().find('#branding').css('color', hsva.toHEXA().toString());
        if($('#microsite_preview_iframe').contents().find('#branding a')) {
            $('#microsite_preview_iframe').contents().find('#branding a').css('color', hsva.toHEXA().toString());
        }
    });

    /* Background blur */
    document.querySelector('#background_blur').addEventListener('change', event => {
        let blur = document.querySelector('#background_blur').value;
        let brightness = document.querySelector('#background_brightness').value;
        $('#microsite_preview_iframe').contents().find('.link-body-backdrop').css('backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
        $('#microsite_preview_iframe').contents().find('.link-body-backdrop').css('-webkit-backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
    });

    /* Background brightness */
    document.querySelector('#background_brightness').addEventListener('change', event => {
        let blur = document.querySelector('#background_blur').value;
        let brightness = document.querySelector('#background_brightness').value;
        $('#microsite_preview_iframe').contents().find('.link-body-backdrop').css('backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
        $('#microsite_preview_iframe').contents().find('.link-body-backdrop').css('-webkit-backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
    });

    /* Fonts size */
    document.querySelector('#settings_font_size').addEventListener('change', event => {
        let font_size = event.currentTarget.value;
        $('#microsite_preview_iframe').contents().find('body').css('font-size', `${font_size}px`);
        set_microsite_theme_id_null();
    });

    /* Font family */
    document.querySelectorAll('input[name="font"]').forEach(element => element.addEventListener('change', event => {
        let font_key = event.currentTarget.value;
        let font_family = event.currentTarget.getAttribute('data-font-family');
        let font_css_url = event.currentTarget.getAttribute('data-font-css-url');
        if(!font_family) font_family = 'inherit';

        if(font_css_url) {
            let font_css_link = document.querySelector('#microsite_preview_iframe').contentDocument.createElement('link');

            if(!document.querySelector('#microsite_preview_iframe').contentDocument.head.querySelector(`link[id="${font_key}"]`)) {
                font_css_link.rel = 'stylesheet';
                font_css_link.href = font_css_url;
                font_css_link.id = font_key;
                document.querySelector('#microsite_preview_iframe').contentDocument.head.appendChild(font_css_link);
            }
        }

        document.querySelector('#microsite_preview_iframe').contentDocument.querySelector('body').style.setProperty('font-family', `${font_family}`, 'important');

        set_microsite_theme_id_null();
    }));

    /* Background Type Handler */
    let background_type_handler = () => {
        let type = $('#settings_background_type').find(':selected').val();

        /* Show only the active background type */
        $(`div[id="background_type_${type}"]`).show();
        $(`div[id="background_type_${type}"]`).find('[name^="background"]').removeAttr('disabled');

        /* Disable the other possible types so they dont get submitted */
        let background_type_containers = $(`div[id^="background_type_"]:not(div[id$="_${type}"])`);

        background_type_containers.hide();
        background_type_containers.find('[name^="background"]').attr('disabled', 'disabled');
    };

    background_type_handler();

    $('#settings_background_type').on('change', background_type_handler);

    /* Preset background preview */
    $('#background_type_preset input[name="background"]').on('change', event => {
        set_microsite_theme_id_null();

        let preset_style = $(event.currentTarget).parent().find('.link-background-type-preset')[0].getAttribute('style');
        $('#microsite_preview_iframe').contents().find('body').attr('style', preset_style);
        $('#microsite_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Preset background preview */
    $('#background_type_preset_abstract input[name="background"]').on('change', event => {
        set_microsite_theme_id_null();

        let preset_abstract_style = $(event.currentTarget).parent().find('.link-background-type-preset')[0].getAttribute('style');
        $('#microsite_preview_iframe').contents().find('body').attr('style', preset_abstract_style);
        $('#microsite_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Gradient Background */
    let settings_background_type_gradient_color_one_pickr = Pickr.create({
        el: '#settings_background_type_gradient_color_one_pickr',
        default: $('#settings_background_type_gradient_color_one').val(),
        ...pickr_options
    });

    settings_background_type_gradient_color_one_pickr.on('change', hsva => {
        set_microsite_theme_id_null();

        $('#settings_background_type_gradient_color_one').val(hsva.toHEXA().toString());

        let color_one = $('#settings_background_type_gradient_color_one').val();
        let color_two = $('#settings_background_type_gradient_color_two').val();

        $('#microsite_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background-image: linear-gradient(135deg, ${color_one} 10%, ${color_two} 100%);`);
        $('#microsite_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    let settings_background_type_gradient_color_two_pickr = Pickr.create({
        el: '#settings_background_type_gradient_color_two_pickr',
        default: $('#settings_background_type_gradient_color_two').val(),
        ...pickr_options
    });

    settings_background_type_gradient_color_two_pickr.on('change', hsva => {
        set_microsite_theme_id_null();

        $('#settings_background_type_gradient_color_two').val(hsva.toHEXA().toString());

        let color_one = $('#settings_background_type_gradient_color_one').val();
        let color_two = $('#settings_background_type_gradient_color_two').val();

        $('#microsite_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background-image: linear-gradient(135deg, ${color_one} 10%, ${color_two} 100%);`);
        $('#microsite_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Color Background */
    let settings_background_type_color_pickr = Pickr.create({
        el: '#settings_background_type_color_pickr',
        default: $('#settings_background_type_color').val(),
        ...pickr_options
    });

    settings_background_type_color_pickr.on('change', hsva => {
        set_microsite_theme_id_null();

        $('#settings_background_type_color').val(hsva.toHEXA().toString());

        $('#microsite_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background: ${hsva.toHEXA().toString()};`);
        $('#microsite_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Image Background */
    function generate_background_preview(input) {
        if(input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = event => {
                $('#background_type_image_preview').attr('src', event.target.result);
                $('#microsite_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background: url(${event.target.result});`);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#background_type_image_input').on('change', event => {
        set_microsite_theme_id_null();

        generate_background_preview(event.currentTarget);
    });

    /* Display branding switcher */
    $('#display_branding').on('change', event => {
        if($(event.currentTarget).is(':checked')) {
            $('#microsite_preview_iframe').contents().find('#branding').show();
        } else {
            $('#microsite_preview_iframe').contents().find('#branding').hide();
        }
    });

    /* Branding change */
    $('#branding_name').on('change paste keyup', event => {
        let branding_name = event.currentTarget.value.trim();

        if(branding_name != '') {
            $('#microsite_preview_iframe').contents().find('#branding').text(branding_name);
            document.querySelector('#branding_url_text_color').classList.remove('container-disabled');
        } else {
            document.querySelector('#branding_url_text_color').classList.add('container-disabled');
        }
    });

    /* Smart Change Detection System */
    let initialFormState = {};
    let initialBlockOrder = [];
    let hasSettingsChanges = false;
    let hasLayoutChanges = false;

    // Capture initial form state
    function captureFormState() {
        const form = document.getElementById('update_microsite');
        const formData = new FormData(form);
        const state = {};
        
        for (let [key, value] of formData.entries()) {
            if (key.endsWith('[]')) {
                // Handle array inputs (like checkboxes)
                if (!state[key]) state[key] = [];
                state[key].push(value);
            } else {
                state[key] = value;
            }
        }
        
        return state;
    }

    // Capture initial block order
    function captureBlockOrder() {
        const blocks = [];
        $('#microsite_blocks > .microsite_block').each((i, elm) => {
            blocks.push({
                microsite_block_id: $(elm).data('microsite-block-id'),
                order: i,
                is_enabled: $(elm).find('input[type="checkbox"]').is(':checked')
            });
        });
        return blocks;
    }

    // Deep equality check for objects
    function deepEqual(obj1, obj2) {
        if (obj1 === obj2) return true;
        if (obj1 == null || obj2 == null) return false;
        if (typeof obj1 !== typeof obj2) return false;
        
        if (typeof obj1 === 'object') {
            const keys1 = Object.keys(obj1);
            const keys2 = Object.keys(obj2);
            
            if (keys1.length !== keys2.length) return false;
            
            for (let key of keys1) {
                if (!keys2.includes(key)) return false;
                if (!deepEqual(obj1[key], obj2[key])) return false;
            }
            return true;
        }
        
        return obj1 === obj2;
    }

    // Update button states based on changes
    function updateButtonStates(settingsChanged, layoutChanged) {
        const canvasSaveBtn = document.getElementById('canvas-save-btn');
        const canvasSaveText = document.querySelector('.canvas-save-text');
        const settingsUpdateBtn = document.getElementById('settings-update-btn');
        const settingsUpdateText = document.querySelector('.settings-update-text');
        
        // Canvas button for layout changes
        if (canvasSaveBtn && canvasSaveText) {
            canvasSaveBtn.disabled = !layoutChanged;
            canvasSaveBtn.className = layoutChanged ? 
                'btn btn-xs btn-success' : 
                'btn btn-xs btn-outline-success';
            canvasSaveText.textContent = layoutChanged ? 'Save Layout' : 'Layout Saved';
        }
        
        // Settings button for form changes
        if (settingsUpdateBtn && settingsUpdateText) {
            settingsUpdateBtn.disabled = !settingsChanged;
            settingsUpdateBtn.className = settingsChanged ? 
                'btn btn-sm btn-primary' : 
                'btn btn-sm btn-outline-primary';
            settingsUpdateText.textContent = settingsChanged ? 'Update Settings' : 'Settings Saved';
        }
        
        // Store current state
        hasSettingsChanges = settingsChanged;
        hasLayoutChanges = layoutChanged;
    }

    // Detect changes function
    function detectChanges() {
        const currentFormState = captureFormState();
        const currentBlockOrder = captureBlockOrder();
        
        const settingsChanged = !deepEqual(initialFormState, currentFormState);
        const layoutChanged = !deepEqual(initialBlockOrder, currentBlockOrder);
        
        updateButtonStates(settingsChanged, layoutChanged);
    }

    // Initialize change detection when page loads
    $(document).ready(function() {
        // Capture initial states
        initialFormState = captureFormState();
        initialBlockOrder = captureBlockOrder();
        
        // Set initial button states
        updateButtonStates(false, false);
        
        // Monitor form changes
        $('#update_microsite').on('input change', 'input, select, textarea', function() {
            setTimeout(detectChanges, 100); // Small delay to ensure DOM is updated
        });
        
        // Monitor color picker changes
        $(document).on('pickr:change', function() {
            setTimeout(detectChanges, 100);
        });
        
        // Monitor file input changes
        $('#update_microsite').on('change', 'input[type="file"]', function() {
            setTimeout(detectChanges, 100);
        });
    });

    /* Enhanced form handling with separate handlers for main form vs canvas form */
    
    // Settings update button handler (new dedicated button)
    $('#settings-update-btn').on('click', function(event) {
        if (!hasSettingsChanges) return;
        
        const form = document.getElementById('update_microsite');
        const formData = new FormData(form);
        const notificationContainer = form.querySelector('.notification-container');
        
        handleFormSubmission(form, formData, notificationContainer, 'settings');
    });
    
    // Main settings form handler (right panel) - for form submission via Enter key
    $('form[name="update_microsite"]').on('submit', function(event) {
        let form = $(event.currentTarget)[0];
        let formData = new FormData(form);
        let notificationContainer = event.currentTarget.querySelector('.notification-container');
        
        handleFormSubmission(form, formData, notificationContainer, 'settings');
        event.preventDefault();
    });

    // Canvas form handler (floppy disk button - left panel)
    $('form[name="update_microsite_canvas"]').on('submit', function(event) {
        let form = $(event.currentTarget)[0];
        let mainForm = document.getElementById('update_microsite');
        
        if(!mainForm) {
            return false;
        }
        
        // Create FormData from main form to ensure all fields are included
        let formData = new FormData(mainForm);
        
        // Override with canvas form's hidden fields to ensure correct submission context
        let canvasFormData = new FormData(form);
        for (let [key, value] of canvasFormData.entries()) {
            formData.set(key, value);
        }
        
        let notificationContainer = mainForm.querySelector('.notification-container');
        
        handleFormSubmission(form, formData, notificationContainer, 'canvas');
        event.preventDefault();
    });

    // Unified form submission handler
    function handleFormSubmission(form, formData, notificationContainer, submissionType) {
        notificationContainer.innerHTML = '';
        let submitButton = form.querySelector('[type="submit"][name="submit"]');
        pause_submit_button(submitButton);

        // Add loading overlay with submission type indicator
        let micrositePreviewIframe = document.querySelector('#microsite_preview_iframe');
        let container = micrositePreviewIframe?.closest('.microsite-preview-iframe-container');
        
        if(container && !container.querySelector('.save-loading-overlay')) {
            let overlay = document.createElement('div');
            overlay.className = 'save-loading-overlay';
            overlay.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.9);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                z-index: 15;
                border-radius: inherit;
            `;
            overlay.innerHTML = `
                <div class="spinner-border spinner-border-sm mb-2" role="status"></div>
                <small class="text-muted">Saving changes...</small>
            `;
            container.appendChild(overlay);
        }

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: `${url}ajax`,
            data: formData,
            dataType: 'json',
            timeout: 30000,
            success: function(responseData) {
                handleFormSuccess(responseData, notificationContainer, submitButton, container, submissionType);
            },
            error: function(xhr, status, error) {
                handleFormError(xhr, status, error, notificationContainer, submitButton, container);
            },
        });
    }

    // Success handler
    function handleFormSuccess(responseData, notificationContainer, submitButton, container, submissionType) {
        display_notifications(responseData.message, responseData.status, notificationContainer);
        notificationContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        enable_submit_button(submitButton);

        // Handle image updates
        if(responseData.details?.images) {
            updateImagePreviews(responseData.details.images);
        }

        // Handle successful save
        if(responseData.status === 'success') {
            // Remove loading overlay
            let saveOverlay = container?.querySelector('.save-loading-overlay');
            if(saveOverlay) {
                saveOverlay.remove();
            }
            
            // Coordinated refresh with toast persistence
            setTimeout(function() {
                window.refresh_microsite_preview();
            }, 800); // Reduced delay for better UX
        } else {
            // Remove loading overlay on error
            let saveOverlay = container?.querySelector('.save-loading-overlay');
            if(saveOverlay) {
                saveOverlay.remove();
            }
        }
    }

    // Error handler
    function handleFormError(xhr, status, error, notificationContainer, submitButton, container) {
        enable_submit_button(submitButton);
        
        let saveOverlay = container?.querySelector('.save-loading-overlay');
        if(saveOverlay) {
            saveOverlay.remove();
        }
        
        let errorMessage = <?= json_encode(l('global.error_message.basic')) ?>;
        if(status === 'timeout') {
            errorMessage = 'Save operation timed out. Please try again.';
        } else if(xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        }
        
        display_notifications(errorMessage, 'error', notificationContainer);
    }

    // Image preview update helper
    function updateImagePreviews(images) {
        for(const [key, value] of Object.entries(images)) {
            const inputElement = document.querySelector(`input[name="${key}"]`);
            if(inputElement) {
                inputElement.value = null;
            }

            const removeElement = document.querySelector(`[name="${key}_remove"]`);
            if(removeElement && removeElement.checked) {
                removeElement.click();
            }

            const imgContainer = document.querySelector(`[data-image-container="${key}"] img`);
            const linkElement = document.querySelector(`[data-image-container="${key}"] a`);
            const containers = document.querySelectorAll(`[data-image-container="${key}"]`);

            if(value) {
                if(imgContainer) {
                    imgContainer.setAttribute('src', value);
                    imgContainer.classList.remove('d-none');
                }
                if(linkElement) {
                    linkElement.setAttribute('href', value);
                    linkElement.classList.remove('d-none');
                }
                containers.forEach(element => element.classList.remove('d-none'));
            } else {
                if(imgContainer) {
                    imgContainer.setAttribute('src', '');
                    imgContainer.classList.add('d-none');
                }
                if(linkElement) {
                    linkElement.setAttribute('href', '');
                    linkElement.classList.add('d-none');
                }
                containers.forEach(element => element.classList.add('d-none'));
            }

            // Special handling for background input
            if(key == 'background') {
                const backgroundInput = document.querySelector('#background_type_image_input');
                if(backgroundInput) {
                    backgroundInput.value = '';
                }
            } else {
                const keyInput = document.querySelector(`#${key}`);
                if(keyInput) {
                    keyInput.value = '';
                }
            }
        }
    }


    /* Form handling create */
    $('form[name^="create_microsite_"]').on('submit', function(event) {
        let form = $(event.currentTarget)[0];
        let formData = new FormData(form);
        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: `${url}ajax`,
            data: formData,
            dataType: 'json',
            success: function(responseData) {
                let notificationContainer = event.currentTarget.querySelector('.notification-container');
                notificationContainer.innerHTML = '';
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                if(responseData.status == 'error') {
                    display_notifications(responseData.message, 'error', notificationContainer);
                }

                else if(responseData.status == 'success') {

                    /* Redirect */
                    redirect(responseData.details.url, true);

                }
            },
        });

        event.preventDefault();
    })

    /* Daterangepicker */
    let locale = <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>;
    $('[data-daterangepicker]').daterangepicker({
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: {...locale, format: 'YYYY-MM-DD HH:mm:ss'},
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
    }, (start, end, label) => {});
</script>

<script src="<?= ASSETS_FULL_URL . 'js/libraries/sortable.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    /* Enhanced sortable with proper race condition handling */
    let isReordering = false;
    let reorderTimeout = null;
    
    let sortable = Sortable.create(document.getElementById('microsite_blocks'), {
        animation: 150,
        handle: '.drag',
        onStart: (event) => {
            // Clear any pending reorder operations
            if (reorderTimeout) {
                clearTimeout(reorderTimeout);
                reorderTimeout = null;
            }
        },
        onUpdate: (event) => {
            // Prevent concurrent reordering operations
            if (isReordering) {
                console.log('Reordering already in progress, skipping...');
                return;
            }
            
            isReordering = true;
            
            // Add visual feedback
            const container = document.getElementById('microsite_blocks');
            container.style.opacity = '0.7';
            container.style.pointerEvents = 'none';
            
            // Collect new order
            let microsite_blocks = [];
            $('#microsite_blocks > .microsite_block').each((i, elm) => {
                microsite_blocks.push({
                    microsite_block_id: $(elm).data('microsite-block-id'),
                    order: i
                });
            });

            // Make AJAX request with proper promise handling
            $.ajax({
                type: 'POST',
                url: `${url}ajax`,
                dataType: 'json',
                data: {
                    request_type: 'order',
                    microsite_blocks,
                    global_token
                },
                timeout: 10000 // 10 second timeout
            })
            .done((data) => {
                console.log('Block reorder successful:', data);
                
                // Wait longer for server to process and database to commit, then refresh preview
                setTimeout(() => {
                    window.refresh_microsite_preview();
                }, 1000); // Increased delay to ensure database commit
            })
            .fail((xhr, status, error) => {
                console.error('Block reorder failed:', error);
                showToast('error', 'Failed to update block order. Please try again.');
                
                // Optionally revert the visual order on failure
                // This would require storing the original order before the change
            })
            .always(() => {
                // Re-enable interface
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                isReordering = false;
            });
        }
    });

    /* Status change handler for the links */
    $('[id^="microsite_block_is_enabled_"]').on('change', event => {
        ajax_call_helper(event, 'ajax', 'is_enabled_toggle', () => {

            $(event.currentTarget).closest('.microsite_block').toggleClass('custom-row-inactive');

            /* Refresh iframe */
            window.refresh_microsite_preview();
        });
    });

    /* When an expanding happens for a link settings */
    $('[id^="microsite_block_expanded_content"]').off('show.bs.collapse').on('show.bs.collapse', event => {
        let update_form_content = event.currentTarget;
        let link_type = $(update_form_content).data('link-type');
        let microsite_block_id = $(update_form_content.querySelector('input[name="microsite_block_id"]')).val();
        let microsite_link = $('#microsite_preview_iframe').contents().find(`div[data-microsite-block-id="${microsite_block_id}"]`);

        // Clear any existing iframe event handlers to prevent multiple bindings
        $('#microsite_preview_iframe').off('refreshed.block-' + microsite_block_id);
        
        $('#microsite_preview_iframe').on('refreshed.block-' + microsite_block_id, event => {
            setTimeout(() => {
                microsite_link = $('#microsite_preview_iframe').contents().find(`div[data-microsite-block-id="${microsite_block_id}"]`);
                block_expanded_content_init();
            }, 900)
        })

        let extra_updating_and_potentially_color_inputs = [];

        let block_expanded_content_init = () => {
            // Clear any existing event handlers for this block to prevent duplicates
            $(update_form_content).find('*').off('.block-' + microsite_block_id);
            
            type_handler(`#microsite_block_expanded_content_${microsite_block_id} select[name="animation"]`, 'data-animation', '*=');
            update_form_content.querySelector(`#microsite_block_expanded_content_${microsite_block_id} select[name="animation"]`) && update_form_content.querySelectorAll(`#microsite_block_expanded_content_${microsite_block_id} select[name="animation"]`).forEach(element => element.addEventListener('change', () => { type_handler(`#microsite_block_expanded_content_${microsite_block_id} select[name="animation"]`, 'data-animation', '*='); }));

            switch (link_type) {
                case 'link':
                case 'file':
                case 'cta':
                case 'share':
                case 'pdf_document':
                case 'powerpoint_presentation':
                case 'excel_spreadsheet':
                case 'email_collector':
                case 'phone_collector':
                case 'paypal':
                case 'donation':
                case 'service':
                case 'product':
                case 'youtube_feed':
                    extra_updating_and_potentially_color_inputs = ['name'];
                    break;

                case 'alert':
                    extra_updating_and_potentially_color_inputs = ['text'];
                    break;

                case 'review':
                    extra_updating_and_potentially_color_inputs = ['title', 'description', 'author_name', 'author_description', 'stars'];
                    
                    // Handle multiple reviews preview updates
                    $(update_form_content).find('.review-author-input').off('change.block-' + microsite_block_id).on('change.block-' + microsite_block_id + ' paste.block-' + microsite_block_id + ' keyup.block-' + microsite_block_id, function() {
                        // Update the header title in the accordion
                        let wrapper = $(this).closest('.review-item-wrapper');
                        let titleSpan = wrapper.find('.review-item-title');
                        if (titleSpan.length) {
                            titleSpan.text($(this).val() || 'New Review');
                        }
                        
                        // Refresh the preview iframe to show updated reviews
                        setTimeout(() => {
                            window.refresh_microsite_preview();
                        }, 500);
                    });
                    
                    // Handle star rating changes
                    $(update_form_content).find('.star-input').off('click.block-' + microsite_block_id).on('click.block-' + microsite_block_id, function() {
                        let rating = parseInt($(this).attr('data-rating'));
                        let container = $(this).closest('.star-rating-input');
                        let hiddenInput = container.find('input[type="hidden"]');
                        let stars = container.find('.star-input');
                        
                        // Update hidden input
                        hiddenInput.val(rating);
                        
                        // Update star display
                        stars.each(function(i) {
                            if (i < rating) {
                                $(this).addClass('active');
                            } else {
                                $(this).removeClass('active');
                            }
                        });
                        
                        // Update header stars
                        let wrapper = $(this).closest('.review-item-wrapper');
                        let headerStars = wrapper.find('.review-item-header .ml-2');
                        if (headerStars.length) {
                            headerStars.empty();
                            for (let i = 0; i < 5; i++) {
                                let star = $('<i>').addClass('fas fa-star').css('font-size', '0.75rem');
                                if (i < rating) {
                                    star.addClass('text-warning');
                                } else {
                                    star.addClass('text-muted');
                                }
                                headerStars.append(star);
                            }
                        }
                        
                        // Refresh the preview iframe
                        setTimeout(() => {
                            window.refresh_microsite_preview();
                        }, 500);
                    });
                    
                    // Handle slider behavior changes
                    $(update_form_content).find('input[name="slider_mode"], input[name="auto_play"], input[name="slide_duration"], input[name="show_navigation"], input[name="show_indicators"], input[name="transition_effect"]').off('change.block-' + microsite_block_id).on('change.block-' + microsite_block_id, function() {
                        // Refresh the preview iframe to show updated slider behavior
                        setTimeout(() => {
                            window.refresh_microsite_preview();
                        }, 500);
                    });
                    
                    break;

                case 'external_item':
                    extra_updating_and_potentially_color_inputs = ['name', 'description', 'price'];
                    break;

                case 'timeline':
                    extra_updating_and_potentially_color_inputs = ['title', 'description', 'date'];

                    let line_color_pickr = update_form_content.querySelector(`.line_color_pickr`);
                    let line_color_input = update_form_content.querySelector(`input[name="line_color"]`);

                    if(line_color_pickr) {
                        let color_pickr = Pickr.create({
                            el: line_color_pickr,
                            default: line_color_input.value,
                            ...pickr_options
                        });

                        color_pickr.off().on('change', hsva => {
                            line_color_input.value = hsva.toHEXA().toString();

                            microsite_link.find(`[data-line-background-color]`).css('background-color', hsva.toHEXA().toString());
                            microsite_link.find(`[data-line-border-color]`).css('border-color', hsva.toHEXA().toString());
                        });
                    }

                    break;

                case 'heading':
                    extra_updating_and_potentially_color_inputs = ['text'];

                    $(update_form_content.querySelectorAll('input[name="heading_type"]')).off().on('change', event => {
                        microsite_link.find('[data-text]').removeClass('h1 h2 h3 h4 h5 h6').addClass(event.currentTarget.value);
                    });

                    break;

                case 'paragraph':
                case 'markdown':
                    extra_updating_and_potentially_color_inputs = ['text'];
                    break;

                case 'avatar':
                    extra_updating_and_potentially_color_inputs = [];

                    $(update_form_content.querySelectorAll('input[name="border_radius"]')).off().on('change', event => {
                        let border_radius = event.currentTarget.value;

                        switch (border_radius) {
                            case 'straight':
                                microsite_link.find('[data-border-avatar-radius]').removeClass('link-avatar-round link-avatar-rounded');
                                break;

                            case 'round':
                                microsite_link.find('[data-border-avatar-radius]').removeClass('link-avatar-rounded').addClass('link-avatar-round');
                                break;

                            case 'rounded':
                                microsite_link.find('[data-border-avatar-radius]').removeClass('link-avatar-round').addClass('link-avatar-rounded');
                                break;
                        }
                    });

                    $(update_form_content.querySelector('select[name="size"]')).off().on('change paste keyup', event => {
                        let size = event.currentTarget.value;
                        microsite_link.find('[data-avatar]').css('width', size + 'px').css('height', size + 'px');
                    });

                    $(update_form_content.querySelectorAll('input[name="object_fit"]')).off().on('change paste keyup', event => {
                        let object_fit = document.querySelector(`input[name="object_fit"]:checked`).value;
                        microsite_link.find('[data-avatar]').css('object-fit', object_fit);
                    });

                    break;

                case 'header':
                    extra_updating_and_potentially_color_inputs = [];

                    $(update_form_content.querySelectorAll('input[name="border_radius"]')).off().on('change', event => {
                        let border_radius = event.currentTarget.value;

                        switch (border_radius) {
                            case 'straight':
                                microsite_link.find('[data-border-avatar-radius]').removeClass('link-avatar-round link-avatar-rounded');
                                break;

                            case 'round':
                                microsite_link.find('[data-border-avatar-radius]').removeClass('link-avatar-rounded').addClass('link-avatar-round');
                                break;

                            case 'rounded':
                                microsite_link.find('[data-border-avatar-radius]').removeClass('link-avatar-round').addClass('link-avatar-rounded');
                                break;
                        }
                    });

                    $(update_form_content.querySelector('select[name="avatar_size"]')).off().on('change paste keyup', event => {
                        let size = event.currentTarget.value;
                        microsite_link.find('[data-avatar]').css('width', size + 'px').css('height', size + 'px');
                    });

                    $(update_form_content.querySelectorAll('input[name="object_fit"]')).off().on('change paste keyup', event => {
                        let object_fit = document.querySelector(`input[name="object_fit"]:checked`).value;
                        microsite_link.find('[data-avatar]').css('object-fit', object_fit);
                    });

                    break;

                case 'big_link':
                    extra_updating_and_potentially_color_inputs = ['name', 'description'];
                    break;

                case 'socials':
                    extra_updating_and_potentially_color_inputs = [];

                    let item_color_pickr = update_form_content.querySelector(`.color_pickr`);
                    let item_color_input = update_form_content.querySelector(`input[name="color"]`);

                    if(item_color_pickr) {
                        let color_pickr = Pickr.create({
                            el: item_color_pickr,
                            default: item_color_input.value,
                            ...pickr_options
                        });

                        color_pickr.off().on('change', hsva => {
                            item_color_input.value = hsva.toHEXA().toString();

                            if(microsite_link.find(`[data-color]`).length) {
                                microsite_link.find(`[data-color]`).css('color', hsva.toHEXA().toString());
                            }
                        });
                    }

                    break;



            }

            /* Extra colored inputs */
            extra_updating_and_potentially_color_inputs.forEach(item => {
                let item_input = update_form_content.querySelector(`[name="${item}"]`);
                let item_color_pickr = update_form_content.querySelector(`.${item}_color_pickr`);
                let item_color_input = update_form_content.querySelector(`input[name="${item}_color"]`);

                if(item_color_pickr) {
                    let color_pickr = Pickr.create({
                        el: item_color_pickr,
                        default: item_color_input.value,
                        ...pickr_options
                    });

                    color_pickr.off().on('change', hsva => {
                        item_color_input.value = hsva.toHEXA().toString();

                        if(microsite_link.find(`[data-${item}-color]`).length) {
                            microsite_link.find(`[data-${item}-color]`).css('color', hsva.toHEXA().toString());
                        }

                        if(microsite_link.find(`[data-${item}-background-color]`).length) {
                            microsite_link.find(`[data-${item}-background-color]`).css('background-color', hsva.toHEXA().toString());
                        }
                    });
                }

                if(item_input) {
                    $(item_input).off().on('change paste keyup', event => {
                        if(microsite_link.find(`[data-${item}]`).length) {
                            microsite_link.find(`[data-${item}]`).text($(event.currentTarget).val());
                        }

                        if(update_form_content.querySelector('input[name="icon"]')) {
                            $(update_form_content.querySelector('input[name="icon"]')).trigger('change');
                        }

                        /* Set the name in the form title */
                        if(item == 'name') {
                            $(`[data-target="#microsite_block_expanded_content${microsite_block_id}"] > strong`).text(name);
                        }
                    });
                }
            });

            /* Iconpicker + icon */
            if(update_form_content.querySelector('input[name="icon"]')) {
                /* Delete previous instances */
                if(update_form_content.querySelector('input[name="icon"]').classList.contains('iconpicker-input')) {
                    $.iconpicker.batch(update_form_content.querySelector('input[name="icon"]'), 'destroy');
                }

                setTimeout(() => {
                    $(update_form_content.querySelector('input[name="icon"]')).iconpicker({
                        animation: false,
                        templates: {
                            popover: '<div class="iconpicker-popover popover"><div class="popover-title"></div><div class="popover-content"></div></div>',
                            search: '<input type="search" class="form-control iconpicker-search" placeholder="<?= l('global.search') ?>" />',
                            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
                            iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
                        }
                    });

                }, 500);

                $(update_form_content.querySelector('input[name="icon"]')).off().on('change paste keyup iconpickerSelected', event => {
                    let icon = $(event.currentTarget).val();

                    if(microsite_link.find('[data-icon]').length) {
                        if(!icon) {
                            microsite_link.find('svg').remove();
                        } else {
                            microsite_link.find('svg,i').remove();
                            microsite_link.find('[data-icon]').html(`<i class="${icon} mr-1"></i>`);
                        }
                    }
                });
            }

            /* Border width */
            if(update_form_content.querySelector('input[name="border_width"]') && microsite_link.find('[data-border-width]').length) {
                $(update_form_content.querySelector('input[name="border_width"]')).off().on('change paste keyup', event => {
                    let border_width = $(event.currentTarget).val();
                    microsite_link.find('[data-border-width]').css('border-width', border_width + 'px');
                });
            }

            /* Generate box shadow values for the preview */
            let generate_box_shadow = () => {
                if(microsite_link.find('[data-border-shadow]').length) {
                    let border_shadow_offset_x = update_form_content.querySelector('input[name="border_shadow_offset_x"]').value;
                    let border_shadow_offset_y = update_form_content.querySelector('input[name="border_shadow_offset_y"]').value;
                    let border_shadow_blur = update_form_content.querySelector('input[name="border_shadow_blur"]').value;
                    let border_shadow_spread = update_form_content.querySelector('input[name="border_shadow_spread"]').value;
                    let border_shadow_color = update_form_content.querySelector('input[name="border_shadow_color"]').value;

                    microsite_link.find('[data-border-shadow]').css('box-shadow', `${border_shadow_offset_x}px ${border_shadow_offset_y}px ${border_shadow_blur}px ${border_shadow_spread}px ${border_shadow_color}`);
                }
            }

            /* Border shadow color */
            let border_shadow_color_pickr_element = update_form_content.querySelector('.border_shadow_color_pickr');

            if(border_shadow_color_pickr_element) {
                let border_shadow_color = update_form_content.querySelector('input[name="border_shadow_color"]');

                /* text color handler */
                let color_pickr = Pickr.create({
                    el: border_shadow_color_pickr_element,
                    default: $(border_shadow_color).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(border_shadow_color).val(hsva.toHEXA().toString());
                    generate_box_shadow()
                });
            }

            $(update_form_content.querySelectorAll('input[name^="border_shadow_"]')).off().on('change', event => {
                generate_box_shadow();
            });

            /* Border color */
            let border_color_pickr_element = update_form_content.querySelector('.border_color_pickr');

            if(border_color_pickr_element) {
                let color_input = update_form_content.querySelector('input[name="border_color"]');

                /* text color handler */
                let color_pickr = Pickr.create({
                    el: border_color_pickr_element,
                    default: $(color_input).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(color_input).val(hsva.toHEXA().toString());

                    if(microsite_link.find('[data-border-color]').length) {
                        microsite_link.find('[data-border-color]').css('border-color', hsva.toHEXA().toString());
                    }
                });
            }

            /* Border radius */
            if(update_form_content.querySelector('input[name="border_radius"]') && microsite_link.find('[data-border-radius]').length) {
                $(update_form_content.querySelectorAll('input[name="border_radius"]')).off().on('change', event => {
                    let border_radius = event.currentTarget.value;

                    switch (border_radius) {
                        case 'straight':
                            microsite_link.find('[data-border-radius]').removeClass('link-btn-round link-btn-rounded');
                            break;

                        case 'round':
                            microsite_link.find('[data-border-radius]').removeClass('link-btn-rounded').addClass('link-btn-round');
                            break;

                        case 'rounded':
                            microsite_link.find('[data-border-radius]').removeClass('link-btn-round').addClass('link-btn-rounded');
                            break;
                    }
                });
            }

            /* Border style */
            if(update_form_content.querySelector('input[name="border_style"]') && microsite_link.find('[data-border-style]').length) {
                $(update_form_content.querySelectorAll('input[name="border_style"]')).off().on('change', event => {
                    microsite_link.find('[data-border-style]').css('border-style', event.currentTarget.value);
                });
            }

            /* Animation */
            if(update_form_content.querySelector('select[name="animation"]')) {
                let current_animation = update_form_content.querySelector('select[name="animation"]').value;

                $(update_form_content.querySelector('select[name="animation"]')).off().on('change', event => {
                    let animation = $(event.currentTarget).find(':selected').val();

                    switch (animation) {
                        case 'false':
                            microsite_link.find('[data-animation]').removeClass(`animated ${current_animation}`);
                            current_animation = false;
                            break;

                        default:
                            microsite_link.find('[data-animation]').removeClass(`animated ${current_animation}`).addClass(`animated ${animation}`);
                            current_animation = animation;
                            break;
                    }
                });
            }

            /* Text alignment */
            if(update_form_content.querySelectorAll('input[name="text_alignment"]').length) {
                $(update_form_content.querySelectorAll('input[name="text_alignment"]')).off().on('change', event => {
                    microsite_link.find('[data-text-alignment]').css('text-align', event.currentTarget.value);
                });
            }

            /* Text color */
            let text_color_pickr_element = update_form_content.querySelector('.text_color_pickr');

            if(text_color_pickr_element) {
                let color_input = update_form_content.querySelector('input[name="text_color"]');

                /* text color handler */
                let color_pickr = Pickr.create({
                    el: text_color_pickr_element,
                    default: $(color_input).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(color_input).val(hsva.toHEXA().toString());
                    microsite_link.find('[data-text-color]').css('color', hsva.toHEXA().toString());
                });
            }

            /* Background color */
            let background_color_pickr_element = update_form_content.querySelector('.background_color_pickr');

            if(background_color_pickr_element) {
                let color_input = update_form_content.querySelector('input[name="background_color"]');

                /* background color handler */
                let color_pickr = Pickr.create({
                    el: background_color_pickr_element,
                    default: $(color_input).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(color_input).val(hsva.toHEXA().toString());
                    microsite_link.find('[data-background-color]').css('background-color', hsva.toHEXA().toString());
                });
            }

            /* Schedule Handler */
            let schedule_handler = () => {
                if($(update_form_content.querySelector('input[name="schedule"]')).is(':checked')) {
                    $(update_form_content.querySelector('.schedule_container')).show();
                } else {
                    $(update_form_content.querySelector('.schedule_container')).hide();
                }
            };
            $(update_form_content.querySelector('input[name="schedule"]')).off().on('change', schedule_handler);
            schedule_handler();

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
        }

        block_expanded_content_init();
    });

    /* Clean up when collapsing */
    $('[id^="microsite_block_expanded_content"]').off('hide.bs.collapse').on('hide.bs.collapse', event => {
        let update_form_content = event.currentTarget;
        let microsite_block_id = $(update_form_content.querySelector('input[name="microsite_block_id"]')).val();
        
        // Clean up event handlers specific to this block
        $('#microsite_preview_iframe').off('refreshed.block-' + microsite_block_id);
        $(update_form_content).find('*').off('.block-' + microsite_block_id);
        
        // Destroy any color pickers to prevent memory leaks
        $(update_form_content).find('.pickr').each(function() {
            if(this._pickr) {
                this._pickr.destroy();
            }
        });
    });

</script>

<script>
    /* Live block highlighting */
    'use strict';

    let microsite_blocks = document.querySelectorAll('.microsite_block');

    microsite_blocks.forEach(block => {
        block.addEventListener("mouseenter", function () {
            if(block.classList.contains('custom-row-inactive')) return;

            let block_id = block.getAttribute("data-microsite-block-id");
            let iframe_contents = $('#microsite_preview_iframe').contents();
            let target_element = iframe_contents.find(`[data-microsite-block-id='${block_id}']`);

            if(target_element.length) {
                target_element.addClass('preview-highlight');

                let scrollable = iframe_contents.find('html, body');
                let element_top = target_element.offset().top;

                scrollable.stop().animate({
                    scrollTop: element_top - 100
                }, 150);
            }
        });

        block.addEventListener("mouseleave", function () {
            let block_id = block.getAttribute("data-microsite-block-id");
            let target_element = $('#microsite_preview_iframe').contents().find(`[data-microsite-block-id='${block_id}']`);

            if(target_element.length) {
                target_element.removeClass('preview-highlight');
            }
        });
    });
</script>

<?php include_view(THEME_PATH . 'views/partials/js_cropper.php') ?>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
