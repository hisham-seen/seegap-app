<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-barcode text-primary-900 mr-2"></i> <?= l('admin_gs1_links.header') ?></h1>

    <div class="d-flex position-relative">
        <div>
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.export') ?>">
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('admin/gs1-links?' . $data['filters']->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('admin/gs1-links?' . $data['filters']->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.filters.header') ?>">
                    <i class="fas fa-fw fa-sm fa-filter"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                    <div class="dropdown-header d-flex justify-content-between">
                        <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                        <?php if($data['filters']->has_applied_filters()): ?>
                            <a href="<?= url('admin/gs1-links') ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                        <?php endif ?>
                    </div>

                    <div class="dropdown-divider"></div>

                    <form action="" method="get" role="form">
                        <div class="form-group px-4">
                            <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                            <input type="search" name="search" id="filters_search" value="<?= $data['filters']->get_search() ?>" class="form-control form-control-sm" />
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                <option value="gtin" <?= $data['filters']->get_search_by() == 'gtin' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.gtin') ?></option>
                                <option value="target_url" <?= $data['filters']->get_search_by() == 'target_url' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.target_url') ?></option>
                                <option value="title" <?= $data['filters']->get_search_by() == 'title' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.title') ?></option>
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
                            <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                            <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                <option value="datetime" <?= $data['filters']->get_order_by() == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                <option value="last_datetime" <?= $data['filters']->get_order_by() == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                <option value="gtin" <?= $data['filters']->get_order_by() == 'gtin' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.gtin') ?></option>
                                <option value="clicks" <?= $data['filters']->get_order_by() == 'clicks' ? 'selected="selected"' : null ?>><?= l('gs1_links.table.clicks') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                            <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                <option value="ASC" <?= $data['filters']->get_order_type() == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                <option value="DESC" <?= $data['filters']->get_order_type() == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                            <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                <?php foreach([10, 25, 50, 100, 250, 500] as $key): ?>
                                    <option value="<?= $key ?>" <?= $data['filters']->get_results_per_page() == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
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

<?php if(count($data['gs1_links'])): ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('gs1_links.table.user') ?></th>
                <th><?= l('gs1_links.table.gtin') ?></th>
                <th><?= l('gs1_links.table.target_url') ?></th>
                <th><?= l('gs1_links.table.clicks') ?></th>
                <th><?= l('global.status') ?></th>
                <th><?= l('global.datetime') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach($data['gs1_links'] as $row): ?>
                <tr>
                    <td class="text-nowrap">
                        <div class="d-flex align-items-center">
                            <img src="<?= get_gravatar($row->user_email) ?>" class="user-avatar rounded-circle mr-3" alt="" />

                            <div class="text-truncate">
                                <div>
                                    <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                                </div>

                                <span class="text-muted"><?= $row->user_email ?></span>
                            </div>
                        </div>
                    </td>

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
                                    <span class="font-weight-bold"><?= $row->gtin ?></span>
                                </div>

                                <?php if(!empty($row->title)): ?>
                                    <div class="text-muted">
                                        <small><?= $row->title ?></small>
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
                        <i class="fas fa-fw fa-chart-bar fa-sm mr-1"></i> <?= nr($row->clicks) ?>
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
                                    <a href="#" data-toggle="modal" data-target="#gs1_link_delete_modal" data-gs1-link-id="<?= $row->gs1_link_id ?>" data-resource-name="<?= $row->gtin ?>" class="dropdown-item">
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

    <div class="mt-3"><?= $data['pagination'] ?></div>

<?php else: ?>
    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
        'filters_get' => $data['filters']->get_get(),
        'name' => 'admin_gs1_links',
        'has_secondary_text' => false,
    ]); ?>
<?php endif ?>
