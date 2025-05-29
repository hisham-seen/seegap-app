<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-barcode mr-1"></i> <?= l('gs1_links.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('gs1_links.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if(($this->user->plan_settings->gs1_links_limit ?? -1) != -1 && count((array)$data->gs1_links) >= ($this->user->plan_settings->gs1_links_limit ?? 0)): ?>
                    <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('gs1_links.create') ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('gs1-link-create') ?>" class="btn btn-primary">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('gs1_links.create') ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.export') ?>" data-tooltip>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('gs1-links?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('gs1-links?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.filters.header') ?>" data-tooltip>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url('gs1-links') ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
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
                                    <option value="gtin" <?= $data->filters->search_by == 'gtin' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.gtin') ?></option>
                                    <option value="target_url" <?= $data->filters->search_by == 'target_url' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.target_url') ?></option>
                                    <option value="title" <?= $data->filters->search_by == 'title' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.title') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_is_enabled" class="small"><?= l('global.filters.status') ?></label>
                                <select name="is_enabled" id="filters_is_enabled" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.filters.all') ?></option>
                                    <option value="1" <?= isset($_GET['is_enabled']) && $_GET['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                                    <option value="0" <?= isset($_GET['is_enabled']) && $_GET['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_project_id" class="small"><?= l('projects.project') ?></label>
                                <select name="project_id" id="filters_project_id" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.filters.all') ?></option>
                                    <?php foreach($data->projects as $project_id => $project): ?>
                                        <option value="<?= $project_id ?>" <?= isset($_GET['project_id']) && $_GET['project_id'] == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                    <option value="gtin" <?= $data->filters->order_by == 'gtin' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.gtin') ?></option>
                                    <option value="clicks" <?= $data->filters->order_by == 'clicks' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.clicks') ?></option>
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
                                    <?php foreach([10, 25, 50, 100, 250, 500] as $key): ?>
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

    <?php if(count((array)$data->gs1_links)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('gs1_links.table.gtin') ?></th>
                    <th><?= l('gs1_links.table.target_url') ?></th>
                    <th><?= l('gs1_links.table.clicks') ?></th>
                    <th><?= l('global.status') ?></th>
                    <th><?= l('global.datetime') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->gs1_links as $row): ?>
                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <span class="fa-stack fa-1x" data-toggle="tooltip" title="<?= l('gs1_links.gs1_link') ?>">
                                        <i class="fas fa-circle fa-stack-2x text-primary-100"></i>
                                        <i class="fas fa-barcode fa-stack-1x text-primary-600"></i>
                                    </span>
                                </div>

                                <div class="text-truncate">
                                    <div>
                                        <a href="<?= url('gs1-link/' . $row->gs1_link_id) ?>" class="font-weight-bold"><?= $row->gtin ?></a>
                                    </div>

                                    <?php if(!empty($row->title)): ?>
                                        <div class="text-muted">
                                            <small><?= $row->title ?></small>
                                        </div>
                                    <?php endif ?>

                                    <?php if($row->project_id): ?>
                                        <div>
                                            <a href="<?= url('gs1-links?project_id=' . $row->project_id) ?>" class="text-decoration-none">
                                                <span class="py-1 px-2 border rounded small" style="border-color: <?= $data->projects[$row->project_id]->color ?> !important; color: <?= $data->projects[$row->project_id]->color ?> !important;">
                                                    <?= $data->projects[$row->project_id]->name ?>
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div class="text-truncate">
                                <a href="<?= $row->target_url ?>" target="_blank" rel="noreferrer" class="text-muted" data-toggle="tooltip" title="<?= $row->target_url ?>">
                                    <i class="fas fa-fw fa-external-link-alt fa-sm mr-1"></i> <?= string_truncate($row->target_url, 32) ?>
                                </a>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <a href="<?= url('gs1-link/' . $row->gs1_link_id . '/statistics') ?>" class="text-decoration-none text-muted" data-toggle="tooltip" title="<?= l('gs1_links.table.clicks') ?>">
                                <i class="fas fa-fw fa-chart-bar fa-sm mr-1"></i> <?= nr($row->clicks) ?>
                            </a>
                        </td>

                        <td class="text-nowrap text-muted">
                            <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                <?= \Altum\Date::get($row->datetime, 2) ?>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex align-items-center justify-content-end">

                                <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= l('gs1_links.is_enabled_tooltip') ?>">
                                    <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="gs1_link_is_enabled_<?= $row->gs1_link_id ?>"
                                            data-row-id="<?= $row->gs1_link_id ?>"
                                            onchange="ajax_call_helper(event, 'gs1-link-ajax', 'is_enabled_toggle')"
                                        <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                    >
                                    <label class="custom-control-label" for="gs1_link_is_enabled_<?= $row->gs1_link_id ?>"></label>
                                </div>

                                <button
                                        id="url_copy"
                                        type="button"
                                        class="btn btn-link text-secondary"
                                        data-toggle="tooltip"
                                        title="<?= l('global.clipboard_copy') ?>"
                                        aria-label="<?= l('global.clipboard_copy') ?>"
                                        data-copy="<?= l('global.clipboard_copy') ?>"
                                        data-copied="<?= l('global.clipboard_copied') ?>"
                                        data-clipboard-text="<?= url('01/' . $row->gtin) ?>"
                                >
                                    <i class="fas fa-fw fa-sm fa-copy"></i>
                                </button>

                                <?= include_view(THEME_PATH . 'views/gs1-links/gs1_link_dropdown_button.php', ['id' => $row->gs1_link_id, 'resource_name' => $row->gtin]) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'gs1_links',
            'has_secondary_text' => true,
        ]); ?>
    <?php endif ?>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'gs1_link',
    'resource_id' => 'gs1_link_id',
    'has_dynamic_resource_name' => true,
    'path' => 'gs1-links/delete'
]), 'modals') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'gs1_link_duplicate_modal', 'resource_id' => 'gs1_link_id', 'path' => 'gs1-link-ajax/duplicate']), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/x_reset_modal.php', ['modal_id' => 'gs1_link_reset_modal', 'resource_id' => 'gs1_link_id', 'path' => 'gs1-links/reset']), 'modals'); ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
