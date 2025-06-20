<?php defined('SEEGAP') || die() ?>

<div class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-chart-bar text-primary-900 mr-2"></i> <?= l('reports.header') ?></h1>

            <div class="ml-2">
                <span class="badge badge-secondary" data-toggle="tooltip" title="<?= l('reports.total') ?>"><?= nr($data->total_reports) ?></span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if(isset($this->user->plan_settings->reports_limit) && $this->user->plan_settings->reports_limit != -1 && $data->total_reports >= $this->user->plan_settings->reports_limit): ?>
                    <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('reports.create') ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('report-create') ?>" class="btn btn-primary" data-toggle="tooltip" title="<?= l('reports.create') ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('reports.create') ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('reports?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('reports?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
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
                                <a href="<?= url(\SeeGap\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
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
        </div>
    </div>

    <?php if(count($data->reports)): ?>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.name') ?></th>
                    <th><?= l('global.description') ?></th>
                    <th><?= l('global.datetime') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->reports as $row): ?>

                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="report-avatar">
                                        <i class="fas fa-fw fa-chart-bar text-primary-600"></i>
                                    </div>
                                </div>

                                <div class="d-flex flex-column">
                                    <div>
                                        <a href="<?= url('report-view/' . $row->report_id) ?>" class="font-weight-bold" data-toggle="tooltip" title="<?= l('reports.view') ?>">
                                            <?= $row->name ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <span class="text-muted" data-toggle="tooltip" title="<?= $row->description ?>">
                                <?= string_truncate($row->description, 32) ?>
                            </span>
                        </td>

                        <td class="text-nowrap text-muted">
                            <span data-toggle="tooltip" title="<?= \SeeGap\Date::get($row->datetime, 1) ?>">
                                <?= \SeeGap\Date::get($row->datetime, 2) ?>
                            </span>
                        </td>

                        <td>
                            <div class="d-flex justify-content-end">
                                <a href="<?= url('report-view/' . $row->report_id) ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="<?= l('reports.view') ?>">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>

    <?php else: ?>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="mb-4">
                        <i class="fas fa-chart-bar fa-5x text-muted"></i>
                    </div>
                    <h2 class="h5 text-muted text-center m-0"><?= l('reports.no_data') ?></h2>
                </div>
            </div>
        </div>

    <?php endif ?>

</div>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'report',
    'resource_id' => 'report_id',
    'has_dynamic_resource_name' => true,
    'path' => 'reports/delete'
]), 'modals') ?>
