<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-chart-bar text-primary-900 mr-2"></i> <?= l('admin_reports.header') ?></h1>

    <div class="d-flex position-relative">
        <div>
            <button
                id="bulk_enable"
                type="button"
                class="btn btn-light"
                data-toggle="tooltip"
                title="<?= l('global.bulk_actions') ?>"
                data-tooltip-hide-on-click
            ><i class="fas fa-fw fa-list"></i></button>

            <div id="bulk_group" class="btn-group d-none" role="group">
                <div class="btn-group dropdown" role="group">
                    <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                        <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="bulk_actions">
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                    </div>
                </div>

                <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('admin/reports?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('admin/reports?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
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
                            <input type="search" name="search" id="filters_search" value="<?= $data->filters->search ?>" class="form-control form-control-sm" />
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_is_enabled" class="small"><?= l('global.status') ?></label>
                            <select name="is_enabled" id="filters_is_enabled" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                                <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                            <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
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
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create_modal">
                <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_reports.create') ?>
            </button>
        </div>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th data-bulk-table-selector-target>
                <div class="custom-control custom-checkbox">
                    <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label" for="bulk_select_all"></label>
                </div>
            </th>
            <th><?= l('global.name') ?></th>
            <th><?= l('global.user') ?></th>
            <th><?= l('admin_reports.assigned_users') ?></th>
            <th><?= l('global.status') ?></th>
            <th><?= l('global.datetime') ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($data->reports as $row): ?>
            <tr>
                <td data-bulk-table-selector-target>
                    <div class="custom-control custom-checkbox">
                        <input id="bulk_select_<?= $row->report_id ?>" type="checkbox" class="custom-control-input" data-bulk-table-selector-item value="<?= $row->report_id ?>" />
                        <label class="custom-control-label" for="bulk_select_<?= $row->report_id ?>"></label>
                    </div>
                </td>

                <td class="text-nowrap">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="report-avatar">
                                <i class="fas fa-fw fa-chart-bar text-primary-600"></i>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <div>
                                <span class="font-weight-bold" data-toggle="tooltip" title="<?= $row->description ?>">
                                    <?= $row->name ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </td>

                <td class="text-nowrap">
                    <div class="d-flex align-items-center">
                        <a href="<?= url('admin/user-view/' . $row->user_id) ?>" class="text-decoration-none">
                            <?= $row->user_name ?>
                        </a>
                    </div>
                </td>

                <td class="text-nowrap">
                    <span class="badge badge-secondary">
                        <?= count($row->assigned_user_ids) ?> <?= l('admin_reports.assigned_users') ?>
                    </span>
                </td>

                <td class="text-nowrap">
                    <?php if($row->is_enabled): ?>
                        <span class="badge badge-success"><i class="fas fa-fw fa-check"></i> <?= l('global.active') ?></span>
                    <?php else: ?>
                        <span class="badge badge-warning"><i class="fas fa-fw fa-eye-slash"></i> <?= l('global.disabled') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-nowrap text-muted">
                    <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                        <?= \Altum\Date::get($row->datetime, 2) ?>
                    </span>
                </td>

                <td>
                    <div class="d-flex justify-content-end">
                        <div class="dropdown">
                            <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                <i class="fas fa-fw fa-ellipsis-v"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" data-toggle="modal" data-target="#update_modal" 
                                   data-report-id="<?= $row->report_id ?>" 
                                   data-report-name="<?= $row->name ?>" 
                                   data-report-description="<?= $row->description ?>" 
                                   data-report-superset-embed-code="<?= htmlspecialchars($row->superset_embed_code) ?>" 
                                   data-report-assigned-user-ids="<?= htmlspecialchars(json_encode($row->assigned_user_ids)) ?>" 
                                   data-report-is-enabled="<?= $row->is_enabled ?>" 
                                   class="dropdown-item">
                                    <i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?>
                                </a>
                                <a href="#" data-toggle="modal" data-target="#delete_modal" 
                                   data-report-id="<?= $row->report_id ?>" 
                                   data-report-name="<?= $row->name ?>" 
                                   class="dropdown-item">
                                    <i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>

        </tbody>
    </table>
</div>

<div class="mt-3"><?= $data->pagination ?></div>

<?php require THEME_PATH . 'views/admin/reports/report_create_modal.php' ?>
<?php require THEME_PATH . 'views/admin/reports/report_update_modal.php' ?>
<?php require THEME_PATH . 'views/admin/reports/report_delete_modal.php' ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php', ['name' => 'reports', 'resource_id' => 'report_id', 'has_dynamic_resource_name' => true]), 'modals') ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#update_modal').on('show.bs.modal', event => {
        let report_id = $(event.relatedTarget).data('report-id');
        let report_name = $(event.relatedTarget).data('report-name');
        let report_description = $(event.relatedTarget).data('report-description');
        let report_superset_embed_code = $(event.relatedTarget).data('report-superset-embed-code');
        let report_assigned_user_ids = $(event.relatedTarget).data('report-assigned-user-ids');
        let report_is_enabled = $(event.relatedTarget).data('report-is-enabled');

        $(event.currentTarget).find('input[name="report_id"]').val(report_id);
        $(event.currentTarget).find('input[name="name"]').val(report_name);
        $(event.currentTarget).find('textarea[name="description"]').val(report_description);
        $(event.currentTarget).find('textarea[name="superset_embed_code"]').val(report_superset_embed_code);
        $(event.currentTarget).find('input[name="is_enabled"]').prop('checked', report_is_enabled);

        /* Clear and set assigned users */
        $(event.currentTarget).find('select[name="assigned_user_ids[]"]').val(null).trigger('change');
        if(report_assigned_user_ids && report_assigned_user_ids.length > 0) {
            $(event.currentTarget).find('select[name="assigned_user_ids[]"]').val(report_assigned_user_ids).trigger('change');
        }
    });

    /* On modal show load new data */
    $('#delete_modal').on('show.bs.modal', event => {
        let report_id = $(event.relatedTarget).data('report-id');
        let report_name = $(event.relatedTarget).data('report-name');

        $(event.currentTarget).find('input[name="report_id"]').val(report_id);
        $(event.currentTarget).find('#delete_modal_name').html(report_name);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
