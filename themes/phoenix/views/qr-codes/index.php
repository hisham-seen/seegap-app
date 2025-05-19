<?php defined('ALTUMCODE') || die() ?>

<section class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-qrcode mr-1"></i> <?= l('qr_codes.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('qr_codes.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if($this->user->plan_settings->qr_codes_limit != -1 && $data->total_qr_codes >= $this->user->plan_settings->qr_codes_limit): ?>
                    <button type="button" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>" class="btn btn-primary disabled">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('qr_codes.create') ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('qr-code-create') ?>" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->total_qr_codes, $this->user->plan_settings->qr_codes_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('qr_codes.create') ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->qr_codes) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('qr-codes?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('qr-codes?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <a href="#" onclick="window.print();return false;" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->qr_codes) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                                <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                                <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                </select>
                            </div>

                            <?php if(settings()->links->projects_is_enabled): ?>
                            <div class="form-group px-4">
                                <div class="d-flex justify-content-between">
                                    <label for="filters_project_id" class="small"><?= l('projects.project_id') ?></label>
                                    <a href="<?= url('projects') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.create') ?></a>
                                </div>
                                <select name="project_id" id="filters_project_id" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach($data->projects as $row): ?>
                                        <option value="<?= $row->project_id ?>" <?= isset($data->filters->filters['project_id']) && $data->filters->filters['project_id'] == $row->project_id ? 'selected="selected"' : null ?>><?= $row->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <?php endif ?>

                            <div class="form-group px-4">
                                <label for="filters_type" class="small"><?= l('global.type') ?></label>
                                <select name="type" id="filters_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach(array_keys((require APP_PATH . 'includes/enabled_qr_codes.php')) as $type): ?>
                                        <option value="<?= $type ?>" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == $type ? 'selected="selected"' : null ?>><?= $data->available_qr_codes[$type]['emoji'] . ' ' . l('qr_codes.type.' . $type) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="qr_code_id" <?= $data->filters->order_by == 'qr_code_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                    <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="type" <?= $data->filters->order_by == 'type' ? 'selected="selected"' : null ?>><?= l('global.type') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-light" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_download_modal"><i class="fas fa-fw fa-sm fa-download mr-2"></i> <?= l('global.download') ?></a>
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->qr_codes)): ?>
        <form id="table" action="<?= SITE_URL . 'qr-codes/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />
            <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
            <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

            <div class="row link-settings col-4">
                <!-- Left Column - QR Code Types -->
                <div class="col-12 col-lg-3">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> 
                                    <span><?= l('qr_codes.types') ?></span>
                                </h6>
                            </div>

                            <!-- QR Code Types List -->
                            <div id="qr_code_types" class="mt-3">
                                <?php foreach(array_keys((require APP_PATH . 'includes/enabled_qr_codes.php')) as $type): ?>
                                    <div class="qr_code_type card shadow-sm mb-2" data-qr-code-type="<?= $type ?>">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px; background-color: <?= $data->available_qr_codes[$type]['color'] ?? '#0e1a2f' ?>;">
                                                        <i class="<?= $data->available_qr_codes[$type]['icon'] ?> fa-fw fa-xs text-white"></i>
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <div class="d-flex flex-column">
                                                        <div class="text-truncate">
                                                            <a href="#" class="text-truncate small font-weight-bold qr-code-type-select" data-type="<?= $type ?>">
                                                                <?= $data->available_qr_codes[$type]['emoji'] . ' ' . l('qr_codes.type.' . $type) ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Column - QR Code Settings -->
                <div class="col-12 col-lg-5">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="fas fa-fw fa-wrench fa-sm text-muted mr-1"></i> 
                                    <span><?= l('qr_codes.settings') ?></span>
                                </h6>
                            </div>

                            <!-- QR Code Settings Content -->
                            <div id="qr_code_settings" class="mt-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-fw fa-info-circle mr-1"></i>
                                    <?= l('qr_codes.select_type_first') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - QR Code Preview -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> 
                                    <span><?= l('qr_codes.preview') ?></span>
                                </h6>
                            </div>

                            <!-- QR Code Preview Content -->
                            <div id="qr_code_preview" class="mt-3 text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-fw fa-info-circle mr-1"></i>
                                    <?= l('qr_codes.preview_available_after_settings') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                                <label class="custom-control-label" for="bulk_select_all"></label>
                            </div>
                        </th>
                        <th><?= l('global.name') ?></th>
                        <th><?= l('global.type') ?></th>
                        <?php if(settings()->links->projects_is_enabled): ?>
                        <th></th>
                        <?php endif ?>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->qr_codes as $row): ?>
                        <tr>
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_qr_code_id_<?= $row->qr_code_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->qr_code_id ?>" />
                                    <label class="custom-control-label" for="selected_qr_code_id_<?= $row->qr_code_id ?>"></label>
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" data-toggle="tooltip" title="<?= l('global.download') ?>">
                                        <a href="<?= \Altum\Uploads::get_full_url('qr_code') . $row->qr_code ?>" download="<?= $row->name . '.svg' ?>" target="_blank">
                                            <img src="<?= \Altum\Uploads::get_full_url('qr_code') . $row->qr_code ?>" class="qr-code-avatar" loading="lazy" />
                                        </a>
                                    </div>

                                    <div class="d-flex flex-column">
                                        <div>
                                            <a href="<?= url('qr-code-update/' . $row->qr_code_id) ?>" class="font-weight-bold text-truncate"><?= $row->name ?></a>
                                        </div>
                                        <?php if($row->type == 'url'): ?>
                                            <div class="d-flex align-items-center">
                                                <small class="d-inline-block text-truncate text-muted">
                                                    <?= remove_url_protocol_from_url($row->settings->url) ?>
                                                </small>

                                                <?php if($row->link_id): ?>
                                                    <a href="<?= url('link/' . $row->link_id) ?>" class="btn btn-sm btn-link" data-toggle="tooltip" title="<?= l('global.update') ?>"><i class="fas fa-fw fa-pencil-alt"></i></a>
                                                    <a href="<?= url('link/' . $row->link_id . '/statistics') ?>" class="btn btn-sm btn-link" data-toggle="tooltip" title="<?= l('link.statistics.pageviews') ?>"><i class="fas fa-fw fa-chart-bar"></i></a>
                                                <?php endif ?>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </td>

                            <td class="text-nowrap">
                            <span class="badge badge-light">
                                <i class="<?= $data->available_qr_codes[$row->type]['icon'] ?> fa-fw fa-sm mr-1"></i>
                                <?= l('qr_codes.type.' . $row->type) ?>
                            </span>
                            </td>

                            <?php if(settings()->links->projects_is_enabled): ?>
                            <td class="text-nowrap">
                                <?php if($row->project_id): ?>
                                    <a href="<?= url('qr-codes?project_id=' . $row->project_id) ?>" class="text-decoration-none" data-toggle="tooltip" title="<?= l('projects.project_id') ?>">
                                    <span class="badge badge-light" style="color: <?= $data->projects[$row->project_id]->color ?> !important;">
                                        <?= $data->projects[$row->project_id]->name ?>
                                    </span>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php endif ?>

                            <td class="text-nowrap text-muted">
                            <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                <i class="fas fa-fw fa-calendar text-muted"></i>
                            </span>

                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_datetime) . ')</small>' : '<br />-')) ?>">
                                <i class="fas fa-fw fa-history text-muted"></i>
                            </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-block btn-link dropdown-toggle dropdown-toggle-simple" title="<?= l('global.download') ?>" data-toggle="dropdown" aria-expanded="false" data-tooltip data-tooltip-hide-on-click>
                                            <i class="fas fa-fw fa-sm fa-download"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <a href="<?= \Altum\Uploads::get_full_url('qr_code') . $row->qr_code ?>" class="dropdown-item" download="<?= get_slug($row->name) . '.svg' ?>"><?= sprintf(l('global.download_as'), 'SVG') ?></a>
                                            <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others('<?= \Altum\Uploads::get_full_url('qr_code') . $row->qr_code ?>', 'png', '<?= get_slug($row->name) . '.png' ?>');"><?= sprintf(l('global.download_as'), 'PNG') ?></button>
                                            <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others('<?= \Altum\Uploads::get_full_url('qr_code') . $row->qr_code ?>', 'jpg', '<?= get_slug($row->name) . '.jpg' ?>');"><?= sprintf(l('global.download_as'), 'JPG') ?></button>
                                            <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others('<?= \Altum\Uploads::get_full_url('qr_code') . $row->qr_code ?>', 'webp', '<?= get_slug($row->name) . '.webp' ?>');"><?= sprintf(l('global.download_as'), 'WEBP') ?></button>
                                        </div>
                                    </div>

                                    <?= include_view(THEME_PATH . 'views/qr-codes/qr_code_dropdown_button.php', ['id' => $row->qr_code_id, 'resource_name' => $row->name]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>

        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'qr_codes',
            'has_secondary_text' => true,
        ]); ?>

    <?php endif ?>

</section>

<!-- Add JavaScript for QR code type selection and interaction -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle QR code type selection
    document.querySelectorAll('.qr-code-type-select').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Highlight the selected type
            document.querySelectorAll('.qr_code_type').forEach(el => {
                el.classList.remove('bg-light');
            });
            this.closest('.qr_code_type').classList.add('bg-light');
            
            // Get the selected type
            const type = this.getAttribute('data-type');
            
            // Update the settings panel
            loadQrCodeSettings(type);
        });
    });
    
    // Function to load QR code settings based on type
    function loadQrCodeSettings(type) {
        const settingsContainer = document.getElementById('qr_code_settings');
        
        // Here you would typically load the settings form for the selected QR code type
        // For now, we'll just show a placeholder
        settingsContainer.innerHTML = `
            <div class="form-group">
                <label for="qr_name" class="small mb-1"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input id="qr_name" type="text" class="form-control form-control-sm" name="name" placeholder="<?= l('global.name') ?>" />
            </div>
            
            <div class="form-group">
                <label for="qr_content" class="small mb-1"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.content') ?></label>
                <textarea id="qr_content" class="form-control form-control-sm" name="content" placeholder="<?= l('qr_codes.content_placeholder') ?>"></textarea>
            </div>
            
            <div class="form-group">
                <button type="button" id="generate_qr_code" class="btn btn-sm btn-block btn-primary"><?= l('qr_codes.generate') ?></button>
            </div>
        `;
        
        // Add event listener for the generate button
        document.getElementById('generate_qr_code').addEventListener('click', function() {
            generateQrCodePreview(type);
        });
    }
    
    // Function to generate QR code preview
    function generateQrCodePreview(type) {
        const previewContainer = document.getElementById('qr_code_preview');
        const name = document.getElementById('qr_name').value || 'Sample QR Code';
        const content = document.getElementById('qr_content').value || 'https://example.com';
        
        // In a real implementation, you would generate the QR code on the server
        // For now, we'll just show a placeholder
        previewContainer.innerHTML = `
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">${name}</h6>
                    <div class="qr-code-preview-image mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(content)}" class="img-fluid" alt="QR Code Preview" />
                    </div>
                    <div class="small text-muted">${content}</div>
                </div>
            </div>
        `;
    }
});
</script>

<?
